<?php

namespace App\Http\Controllers;

use App\Models\Costing;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\OrderDetails;
use App\Models\ShipmentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaanShipmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:shipments-create')->only('create', 'store');
        $this->middleware('permission:shipments-read')->only('index', 'show');
        $this->middleware('permission:shipments-update')->only('edit', 'update');
        $this->middleware('permission:shipments-delete')->only('destroy');
    }
    public function index()
    {
        $orders =  Order::has('costing')
                        ->with('party:id,name')
                        ->select('id', 'order_no', 'party_id')
                        ->where('status', 2)
                        ->latest()
                        ->get();

        $shipments = Shipment::with('order:id,order_no')
                        ->with('user')
                        ->withSum('details', 'qty')->withSum('details', 'total_cm')
                        ->when(!auth()->user()->can('shipments-list'), function($query) {
                            $query->where('user_id', auth()->id())
                            ->orWhereHas('order', function($q) {
                                $q->orWhere('party_id', auth()->id());
                            });
                        })
                        ->latest()
                        ->paginate(10);

        return view('pages.order.shipment.index',compact('orders','shipments'));
    }

    /** filter the table list */
    public function maanFilter(Request $request)
    {
        $shipments = Shipment::with('order')
                        ->when(!auth()->user()->can('shipments-list'), function($query) {
                            $query->where('user_id', auth()->id());
                        })
                        ->when($request->search, function ($q) use($request) {
                            $q->where(function($q) use($request) {
                                $q->where('style', 'like', '%'.$request->search.'%')
                                    ->orWhere('item', 'like', '%'.$request->search.'%')
                                    ->orWhere('qty', 'like', '%'.$request->search.'%')
                                    ->orWhereHas('order', function($q) use($request){
                                        $q->where('order_no', 'like', '%'.$request->search.'%');
                                    });
                            });
                        })
                        ->latest()
                        ->paginate($request->per_page ?? 10);

        if($request->ajax()){
            return response()->json([
                'data' => view('pages.order.shipment.datas',compact('shipments'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    public function getOrder(Request $request)
    {
        $order = Order::with('party', 'orderDetails')->findOrFail($request->id);
        return response()->json([
            'order' => $order,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'styles' => 'required|array',
            'styles.*' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($request) {
                    $duplicates = array_diff_assoc($request->styles, array_unique($request->styles));
                    if (!empty($duplicates)) {
                        $fail("Duplicate values found in the style field.");
                    }
                },
            ],
            'colors' => 'nullable|array',
            'items' => 'nullable|array',
            'dates' => 'nullable|array',
            'dates.*' => 'required|string',
            'sizes' => 'nullable|array',
            'qts' => 'nullable|array',
            'qts.*' => 'required|integer|min:1',
        ], [
            'styles.*.required' => 'Sorry the style can not be empty!',
            'qts.*.min' => 'Quantity must be at least 1.',
        ]);

        DB::beginTransaction();
        try {

            $invoice_no = "SHIP-" . date('Y-') . str_pad(Shipment::max('id') + 1, 4, '0', STR_PAD_LEFT);

            $order = Order::with('party')->find($request->order_id);
            $order_details = OrderDetails::where('order_id', $order->id)->get();
            $has_prev_shipments = Shipment::with('details')->where('order_id', $order->id)->get();

            $costings = Costing::where('order_id', $order->id)->select('grand_total', 'order_info')->get();

            $shipment = Shipment::create([
                'user_id' => auth()->id(),
                'invoice_no' => $invoice_no,
                'order_id' => $request->order_id,
            ]);

            foreach ($request->styles as $key => $style) {

                $order_detail = $order_details->where('style', $style)->first();
                if (!$order_detail) {
                    return response()->json([
                        'message' => __('Invalid Style no '. $style)
                    ], 400);
                }

                $prev_shipment_qty = $has_prev_shipments->filter(function ($shipment) use ($style) {
                        return $shipment->details->where('style', $style)->isNotEmpty();
                    })->sum(function ($shipment) use ($style) {
                        return $shipment->details->where('style', $style)->sum('qty');
                });

                $costing = $costings->filter(function ($costing) use ($style) {
                    return ($costing->order_info['style'] ?? false == 'all' || $costing->order_info['style'] ?? false == $style);
                })->first();

                $remaining_qty = $order_detail->qty - $prev_shipment_qty;
                if ($remaining_qty < $request->qts[$key]) {
                    return response()->json([
                        'message' => __('You have shipped ' . $prev_shipment_qty . ' quantities for this style '. $style . ' now you can only shipped '.$remaining_qty.' quantities')
                    ], 400);
                }

                $total_cm = ($costing->grand_total / $costing->order_info['qty']) * $request->qts[$key];
                $total_sale = ($costing->order_info['lc'] / $costing->order_info['qty']) * $request->qts[$key];

                ShipmentDetail::create([
                    'shipment_id' => $shipment->id,
                    'total_cm' => $total_cm,
                    'total_sale' => $total_sale,
                    'qty' => $request->qts[$key],
                    'size' => $request->sizes[$key],
                    'item' => $request->items[$key],
                    'style' => $request->styles[$key],
                    'color' => $request->colors[$key],
                    'shipment_date' => $request->dates[$key],
                ]);

                sendNotification($shipment->id, route('shipments.index'), __('New Shipment has been created.'));
            }

            DB::commit();
            return response()->json([
                'message'   => __('Shipment saved successfully'),
                'redirect'  => route('shipments.index')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(__('Something was wrong.'), 400);
        }
    }

    public function edit($id)
    {
        $shipment = Shipment::with('order:id,order_no,party_id', 'details', 'order.party:id,name')->findOrFail($id);
        abort_if(!check_visibility($shipment->created_at, 'shipments', $shipment->user_id), 403);
        $orders = Order::has('costing')->with('party:id,name')
                    ->select('id', 'order_no', 'party_id')
                    ->where('status', 2)
                    ->latest()
                    ->get();

        return view('pages.order.shipment.edit', compact('shipment','orders'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'styles' => 'required|array',
            'styles.*' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($request) {
                    $duplicates = array_diff_assoc($request->styles, array_unique($request->styles));
                    if (!empty($duplicates)) {
                        $fail("Duplicate values found in the style field.");
                    }
                },
            ],
            'colors' => 'nullable|array',
            'items' => 'nullable|array',
            'dates' => 'nullable|array',
            'dates.*' => 'required|string',
            'sizes' => 'nullable|array',
            'qts' => 'nullable|array',
            'qts.*' => 'required|integer|min:1',
        ], [
            'styles.*.required' => 'Sorry the style can not be empty!',
            'qts.*.min' => 'Quantity must be at least 1.',
        ]);

        DB::beginTransaction();
        try {

            // Previous shipment
            $shipment = Shipment::find($id);
            $prev_shipment = $shipment;
            $shipment->delete();

            $order = Order::with('party')->find($request->order_id);
            $order_details = OrderDetails::where('order_id', $order->id)->get();

            $has_prev_shipments = Shipment::with('details')->where('order_id', $order->id)->get();
            $costings = Costing::where('order_id', $order->id)->select('grand_total', 'order_info')->get();

            $shipment = Shipment::create([
                'user_id' => auth()->id(),
                'order_id' => $request->order_id,
                'invoice_no' => $prev_shipment->invoice_no,
                'created_at' => $prev_shipment->created_at,
            ]);

            foreach ($request->styles as $key => $style) {

                $order_detail = $order_details->where('style', $style)->first();
                if (!$order_detail) {
                    return response()->json([
                        'message' => __('Invalid Style no '. $style)
                    ], 400);
                }

                $prev_shipment_qty = $has_prev_shipments->filter(function ($shipment) use ($style) {
                        return $shipment->details->where('style', $style)->isNotEmpty();
                    })->sum(function ($shipment) use ($style) {
                        return $shipment->details->where('style', $style)->sum('qty');
                });

                $costing = $costings->filter(function ($costing) use ($style) {
                    return ($costing->order_info['style'] ?? false == 'all' || $costing->order_info['style'] ?? false == $style);
                })->first();

                $remaining_qty = $order_detail->qty - $prev_shipment_qty;
                if ($remaining_qty < $request->qts[$key]) {
                    return response()->json([
                        'message' => __('You have shipped ' . $prev_shipment_qty . ' quantities for this style '. $style . ' now you can only shipped '.$remaining_qty.' quantities')
                    ], 400);
                }

                $total_cm = ($costing->grand_total / $costing->order_info['qty']) * $request->qts[$key];
                $total_sale = ($costing->order_info['lc'] / $costing->order_info['qty']) * $request->qts[$key];

                ShipmentDetail::create([
                    'shipment_id' => $shipment->id,
                    'total_cm' => $total_cm,
                    'total_sale' => $total_sale,
                    'qty' => $request->qts[$key],
                    'size' => $request->sizes[$key],
                    'item' => $request->items[$key],
                    'style' => $request->styles[$key],
                    'color' => $request->colors[$key],
                    'shipment_date' => $request->dates[$key],
                ]);

                sendNotification($shipment->id, route('shipments.index'), __('New Shipment has been created.'));
            }

            DB::commit();
            return response()->json([
                'message'   => __('Shipment saved successfully'),
                'redirect'  => route('shipments.index')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(__('Something was wrong.'), 400);
        }
    }

    public function destroy($id)
    {
        $shipment = Shipment::findOrFail($id);
        $permission = check_visibility($shipment->created_at, 'shipments', $shipment->user_id);
        if (!$permission) {
            return response()->json(__('You don\'t have permission.'), 403);
        }

        $shipment->delete();
        return response()->json([
            'message'   => __('Shipment deleted successfully'),
            'redirect'  => route('shipments.index')
        ]);
    }

    public function print($order_id, $invoice_no)
    {
        $order = Order::findOrFail($order_id);

        if ($invoice_no !='order'){
            $shipments = Shipment::with('details')->where('invoice_no', $invoice_no)->get();
        }else{
            $shipments = Shipment::with('details')->where('order_id', $order_id)->get();
        }

        $details = [];
        foreach ($shipments as $shipment) {
            $details = array_merge($details, $shipment->details->toArray());
        }


        return view('pages.order.shipment.print', compact( 'order', 'details'));
    }
}

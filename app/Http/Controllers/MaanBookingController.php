<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Order;
use App\Models\Booking;
use App\Helpers\HasUploader;
use Illuminate\Http\Request;
use App\Models\Bookingdetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MaanBookingController extends Controller
{
    use HasUploader;

    public function __construct()
    {
        $this->middleware('permission:bookings-create')->only('create', 'store');
        $this->middleware('permission:bookings-read')->only('index', 'show');
        $this->middleware('permission:bookings-update')->only('edit', 'update');
        $this->middleware('permission:bookings-delete')->only('destroy');
    }

    public function index()
    {
        $orders = Order::select('id','order_no')->doesntHave('booking')->whereStatus(2)->latest()->get();
        $bookings   = Booking::with('order','order.party','order.merchandiser')
            ->when(!auth()->user()->can('bookings-list'), function($query) {
                $query->where('user_id', auth()->id());
            })
            ->latest()
            ->paginate(10);

        return view('pages.order.booking.index',compact('bookings', 'orders'));
    }

    /** filter the table list */
    public function maanFilter(Request $request)
    {
        $bookings = Booking::with('order', 'order.party')
            ->when(!auth()->user()->can('bookings-list'), function($query) {
                $query->where('user_id', auth()->id());
            })
            ->when($request->from_date, function($q) {
                $q->whereDate('created_at', '>=', request('from_date'));
            })
            ->when($request->to_date, function($q) {
                $q->whereDate('created_at', '<=', request('to_date'));
            })
            ->when(request('search'), function($q) {
                $q->where(function($q) {
                    $q->orWhere('composition', 'like', '%'.request('search').'%')
                        ->orWhere('meta->fab_consumption', 'like', '%'.request('search').'%')
                        ->orWhere('meta->process_loss', 'like', '%'.request('search').'%')
                        ->orWhere('meta->other_fabric', 'like', '%'.request('search').'%')
                        ->orWhere('meta->rib', 'like', '%'.request('search').'%')
                        ->orWhere('meta->collar', 'like', '%'.request('search').'%')
                        ->orWhereHas('order', function($q) {
                            $q->where('order_no', 'like', '%'.request('search').'%');
                        })
                        ->orWhereHas('order.party', function($q) {
                            $q->where('name', 'like', '%'.request('search').'%')
                            ->orWhere('type', 'like', '%'.request('search').'%');
                        });
                });
            })
            ->latest('id')
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('pages.order.booking.datas', compact('bookings'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer|unique:bookings',
            'composition' => 'nullable|string',
            'booking_date' => 'required|date',
            'meta' => 'nullable|array',
            'data' => 'nullable|array',
            'header' => 'nullable|array',
            'style' => 'required|array',
            'style.*' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($request) {
                    // Check for duplicates within the current request
                    $duplicates = array_diff_assoc($request->style, array_unique($request->style));
                    if (!empty($duplicates)) {
                        $fail("Duplicate values found in the style field.");
                    }
                },
            ],
            'color' => 'nullable|array',
            'item' => 'nullable|array',
            'shipment_date' => 'required|array',
            'shipment_date.*' => 'required|string',
            'qty' => 'required|array',
            'qty.*' => 'required|numeric',
            'unit_price' => 'required|array',
            'unit_price.*' => 'required|numeric',
            'total_price' => 'nullable|array',
        ], [
            'style.*.required' => 'Sorry the style number can not be empty!',
        ]);


        DB::beginTransaction();
        try {

            $booking = Booking::create($request->except('image') + [
                    'user_id' => auth()->id(),
                ]);

            $data = [
                'desc_garments' => $request->data['desc_garments'],
                'pantone' => $request->data['pantone'],
                'body_fab' => $request->data['body_fab'],
                'yarn_count_body' => $request->data['yarn_count_body'],
                'garments_qty_dzn' => $request->data['garments_qty_dzn'],
                'body_fab_dzn' => $request->data['body_fab_dzn'],
                'body_gray_fab' => $request->data['body_gray_fab'],
                'desc_garments_rib' => $request->data['desc_garments_rib'],
                'yarn_count_rib' => $request->data['yarn_count_rib'],
                'consump_rib_dzn' => $request->data['consump_rib_dzn'],
                'rib_kg' => $request->data['rib_kg'],
                'yarn_count_rib_lycra' => $request->data['yarn_count_rib_lycra'],
                'receive' => $request->data['receive'],
                'balance' => $request->data['balance'],
                'gray_body_fab' => $request->data['gray_body_fab'],
                'gray_body_rib' => $request->data['gray_body_rib'],
                'revised' => $request->data['revised'],
                'images' => isset($request->data['images']) ? $this->multipleUpload($request, 'data.images') : []
            ];

            $bookingDetails = new Bookingdetails([
                    'booking_id' => $booking->id,
                    'data' => $data,
                    'cuff_color' => $request->cuff_color,
                    'collar_size_qty' => $request->collar_size_qty,
                    'cuff_solid' => $request->cuff_solid,
            ]);

            $bookingDetails->save();

            $order = Order::findOrFail($booking->order_id);

            $order->update([
                'lc' =>collect($request->total_price)->sum()
            ]);
            $totalPriceSum = $order->orderDetails->sum('total_price');

            $order->orderDetails()->delete();

            foreach ($request->style as $key => $style) {
                $order->orderDetails()->create([
                    'order_id' => $order->id,
                    'style' => $style,
                    'color' => $request->color[$key],
                    'item' => $request->item[$key],
                    'shipment_date' => $request->shipment_date[$key],
                    'qty' => $request->qty[$key],
                    'unit_price' => $request->unit_price[$key],
                    'total_price' => $request->qty[$key] * $request->unit_price[$key],
                ]);
            }

            $order->load('orderDetails');
            $party = $order->party;
            $requestTotalPriceSum = collect($request->total_price)->sum(); // requested sum of total price
            if (auth()->user()->role == 'superadmin' || auth()->user()->role == 'admin') {
                $booking->update(['status' => 2]);

                $income = Income::where('category_name', $order->order_no)->first();
                $prev_income_id = $income->id ?? null;

                $income->update([
                    'total_due' => ($income->total_due - $totalPriceSum) + $requestTotalPriceSum,
                    'total_bill' => ($income->total_bill - $totalPriceSum) + $requestTotalPriceSum,
                ]);

                $party->update([
                    'total_bill' => ($party->total_bill - $totalPriceSum) + $requestTotalPriceSum,
                    'due_amount' => ($party->due_amount - $totalPriceSum) + $requestTotalPriceSum,
                ]);

            }

            createIncomeInvoice($order, $party, $income ?? null, $prev_income_id ?? null);

            DB::commit();
            sendNotification($booking->id, route('bookings.index'), __('New booking has been created.'));
            return response()->json([
                'message' => __('Booking saved successfully'),
                'redirect' => route('bookings.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(__('Something was wrong.'), 400);
        }
    }

    public function show(Booking $booking)
    {
        $booking->load('detail');
        $order = Order::with('orderDetails')->findOrFail($booking->order_id);
        return view('pages.order.booking.fabric-details',compact( 'booking', 'order'));
    }

    public function edit(Booking $booking)
    {
        abort_if(!check_visibility($booking->created_at, 'bookings', $booking->user_id), 403);

        $booking->load('detail');
        $orders = Order::select('id','order_no')->whereStatus(2)->latest()->get();
        $singleOrder = Order::with('orderDetails')->findOrFail($booking->order_id);

        return view('pages.order.booking.edit',compact('orders', 'booking', 'singleOrder'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'composition' => 'nullable|string',
            'booking_date' => 'required|date',
            'meta' => 'nullable|array',
            'header' => 'nullable|array',
            'style' => 'required|array',
            'style.*' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($request) {
                    // Check for duplicates within the current request
                    $duplicates = array_diff_assoc($request->style, array_unique($request->style));
                    if (!empty($duplicates)) {
                        $fail("Duplicate values found in the style field.");
                    }
                },
            ],
            'color' => 'nullable|array',
            'item' => 'nullable|array',
            'shipment_date' => 'required|array',
            'shipment_date.*' => 'required|string',
            'qty' => 'required|array',
            'qty.*' => 'required|numeric',
            'unit_price' => 'required|array',
            'unit_price.*' => 'required|numeric',
            'total_price' => 'nullable|array',
        ], [
            'style.*.required' => 'Sorry the style number can not be empty!',
        ]);

        $booking = Booking::with('detail')->findOrFail($id);

        $permission = check_visibility($booking->created_at, 'bookings', $booking->user_id);
        if (!$permission) {
            return response()->json(__('You don\'t have permission.'), 403);
        }

        DB::beginTransaction();
        try {

            createHistory($booking, 'bookings');

            $party = $booking->order->party;

            $booking->update($request->except('image','order_id') + [
                    'user_id' => auth()->id(),
                    'order_id' => $booking->order_id,
                ]);

            $bookingDetail = Bookingdetails::where('booking_id', $id)->first();
            $data = [
                'desc_garments' => $request->data['desc_garments'],
                'pantone' => $request->data['pantone'],
                'body_fab' => $request->data['body_fab'],
                'yarn_count_body' => $request->data['yarn_count_body'],
                'garments_qty_dzn' => $request->data['garments_qty_dzn'],
                'body_fab_dzn' => $request->data['body_fab_dzn'],
                'body_gray_fab' => $request->data['body_gray_fab'],
                'desc_garments_rib' => $request->data['desc_garments_rib'],
                'yarn_count_rib' => $request->data['yarn_count_rib'],
                'consump_rib_dzn' => $request->data['consump_rib_dzn'],
                'rib_kg' => $request->data['rib_kg'],
                'yarn_count_rib_lycra' => $request->data['yarn_count_rib_lycra'],
                'receive' => $request->data['receive'],
                'balance' => $request->data['balance'],
                'gray_body_fab' => $request->data['gray_body_fab'],
                'gray_body_rib' => $request->data['gray_body_rib'],
                'revised' => $request->data['revised'],
                'images' => isset($request->data['images'])? $this->multipleUpload($request, 'data.images', $bookingDetail->data['images']): $bookingDetail->data['images']
            ];

            $bookingDetail->update([
                'booking_id' => $booking->id,
                'data' => $data,
                'cuff_color' => $request->cuff_color,
                'collar_size_qty' => $request->collar_size_qty,
                'cuff_solid' => $request->cuff_solid,
            ]);

            $order = Order::findOrFail($booking->order_id);
            $income = Income::where('category_name', $order->order_no)->first();
            $totalPriceSum = $order->orderDetails->sum('total_price'); // order_details table sum of total_price
            $prev_income_id = $income->id ?? null;

            $order->update([
               'lc' =>collect($request->total_price)->sum()
            ]);

            $order->orderDetails()->delete();

            foreach ($request->style as $key => $style) {
                $order->orderDetails()->create([
                    'order_id' => $order->id,
                    'style' => $style,
                    'color' => $request->color[$key],
                    'item' => $request->item[$key],
                    'shipment_date' => $request->shipment_date[$key],
                    'qty' => $request->qty[$key],
                    'unit_price' => $request->unit_price[$key],
                    'total_price' => $request->qty[$key] * $request->unit_price[$key],
                ]);
            }


            $updated_order = Order::with('orderDetails', 'party')->findOrFail($booking->order_id); // for updated order
            $updated_party = $updated_order->party;
            $requestTotalPriceSum = collect($request->total_price)->sum(); // requested sum of total price

            if ($booking->status == 2) {

                $income->update([
                    'total_bill' => ($income->total_bill - $totalPriceSum) + $requestTotalPriceSum,
                    'total_due' => ($income->total_due - $totalPriceSum) + $requestTotalPriceSum,
                ]);

                // for unchanged order no
                if ($party->id == $updated_party->id) {
                    $party->update([
                        'total_bill' => ($party->total_bill - $totalPriceSum) + $requestTotalPriceSum,
                        'due_amount' => ($party->due_amount - $totalPriceSum) + $requestTotalPriceSum,
                    ]);
                } // for change order No ( currently it skip for disable order_no , so it should be update )
                else {
                    $party->update([
                        'total_bill' => ($party->total_bill - $totalPriceSum),
                        'due_amount' => ($party->due_amount - $totalPriceSum),
                    ]);
                    $updated_party->update([
                        'total_bill' => ($updated_party->total_bill + $requestTotalPriceSum),
                        'due_amount' => ($updated_party->due_amount + $requestTotalPriceSum),
                    ]);
                }
            }

            createIncomeInvoice($order, $party, $income ?? null, $prev_income_id ?? null);
            DB::commit();
            return response()->json([
                'message' => __('Booking updated successfully'),
                'redirect' => route('bookings.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(__('Something was wrong.'), 400);
        }

    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $booking = Booking::with('detail')->findOrFail($id);

            $permission = check_visibility($booking->created_at, 'bookings', $booking->user_id);
            if (!$permission) {
                return response()->json(__('You don\'t have permission.'), 403);
            }

            if (file_exists($booking->image)) {
                Storage::delete($booking->image);
            }

            $booking->delete();

            DB::commit();
            return response()->json([
                'message'   => __('Booking deleted successfully'),
                'redirect'  => route('bookings.index')
            ]);

        }catch ( \Exception $e){
            DB::rollBack();
            return response()->json(__('Something was wrong.'), 400);
        }

    }

    /** update costing status & note from storage */
    public function status(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|integer',
            'reason' => 'nullable|string|min:4|max:255'
        ]);
        if ($request->status != 2) {
            $request->validate([
                'reason' => 'required|string|min:4|max:255'
            ]);
        }

        DB::beginTransaction();
        try {
            $booking = Booking::findOrFail($id);
            $order = Order::findOrFail($booking->order_id);
            $totalPriceSum = $order->orderDetails->sum('total_price');
            $party = $order->party;
            $income = Income::where('party_id', $party->id)->first();
            if ($request->status == 2) {
                if ($party !== null) {

                    $party->update([
                        'total_bill' => ($party->total_bill - $income->total_bill) + $totalPriceSum,
                        'due_amount' => ($party->due_amount - $income->total_bill) + $totalPriceSum,
                    ]);

                    $income->update([
                        'total_bill' => $totalPriceSum,
                        'total_due' => $totalPriceSum,
                    ]);
                }
            }


            $booking->update([
                'status' => $request->status == 2 ? $request->status : 0,
                'reason' => $request->reason ,
            ]);

            DB::commit();
            return response()->json([
                'message'   => __('Booking status updated successfully'),
                'redirect'  => route('bookings.index')
            ]);

        } catch (\Exception $e){
            DB::rollback();
            return response()->json(__('Something was wrong.'), 400);
        }
    }

    public function maanFabric($id)
    {
        $booking = Booking::findOrFail($id);
        return view('pages.order.booking.fabric',compact('booking'));
    }
    public function maanCollarCuff($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->load('detail');
        $order = Order::with('orderDetails')->findOrFail($booking->order_id);

        return view('pages.order.booking.collar-cuff',compact('booking', 'order'));
    }

    /** Dropdown order list */
    public function maanOrder(Request $request)
    {
        $order = Order::with('merchandiser', 'orderDetails')->findOrFail($request->order_id);
        return response()->json([
            'order' => $order,
        ]);
    }
}

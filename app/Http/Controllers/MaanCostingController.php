<?php

namespace App\Http\Controllers;

use App\Models\Costing;
use App\Models\Order;
use App\Models\OrderDetails;
use Illuminate\Http\Request;

class MaanCostingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:costings-create')->only('create', 'store');
        $this->middleware('permission:costings-read')->only('index', 'show');
        $this->middleware('permission:costings-update')->only('edit', 'update');
        $this->middleware('permission:costings-delete')->only('destroy');
    }
    public function index()
    {
        $orders  = Order::select('id','order_no')->whereStatus(2)->latest()->get();
        $costings = Costing::with('order','order.party')
                        ->when(!auth()->user()->can('costings-list'), function($query) {
                            $query->where('user_id', auth()->id());
                        })
                        ->latest()
                        ->paginate(10);

        return view('pages.costing.index', compact('orders','costings'));
    }

    /** filter the table list */
    public function filter(Request $request)
    {
        $costings = Costing::with('order','order.party')
                        ->when(request('search'), function($q) {
                            $q->where(function($q) {
                                $q->orWhere('order_info->style', 'like', '%'.request('search').'%')
                                    ->orWhereHas('order', function($q) {
                                        $q->where('order_no', 'like', '%'.request('search').'%');
                                    })
                                    ->orWhereHas('order.party', function($q) {
                                        $q->where('name', 'like', '%'.request('search').'%')
                                        ->orWhere('type', 'like', '%'.request('search').'%');
                                    });
                            });
                        })
                        ->when(!auth()->user()->can('costings-list'), function($query) {
                            $query->where('user_id', auth()->id());
                        })
                        ->latest()
                        ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('pages.costing.datas', compact('costings'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
        ]);
        $costing = Costing::create($request->except('status') + [
                'user_id' => auth()->id(),
                'status' => in_array(auth()->user()->role, ['superadmin', 'admin']) ? 2 : 1,
            ]);
        sendNotification($costing->id, route('costings.index'), __('New costing has been created.'));
        return response()->json([
            'message'   => __('Costing saved Successfully'),
            'redirect'  => route('costings.index')
        ]);
    }

    /** print data */
    public function show($id)
    {
        $costing = Costing::with('order')->findOrFail($id);
        return view('pages.costing.print', compact('costing'));
    }

    public function edit($id)
    {
        $orders  = Order::select('id','order_no')->whereStatus(2)->latest()->get();
        $costings = Costing::with('order')->latest()->paginate(10);
        $costing = Costing::findOrFail($id);
        abort_if(!check_visibility($costing->created_at, 'costings', $costing->user_id), 403);
        return view('pages.costing.edit',compact('orders','costings','costing'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'order_id' => 'required|integer',
        ]);
        $costing = Costing::findOrFail($id);
        $permission = check_visibility($costing->created_at, 'costings', $costing->user_id);
        if (!$permission) {
            return response()->json(__('You don\'t have permission.'), 403);
        }

        $costing->update($request->all() + [
            'user_id' => auth()->id()
        ]);
        return response()->json([
            'message'   => __('Costing updated Successfully'),
            'redirect'  => route('costings.index')
        ]);
    }

    public function destroy($id)
    {
        $costing = Costing::findOrFail($id);

        $permission = check_visibility($costing->created_at, 'costings', $costing->user_id);
        if (!$permission) {
            return response()->json(__('You don\'t have permission.'), 403);
        }

        $costing->delete();
        return response()->json([
            'message'   => __('Costing deleted successfully'),
            'redirect'  => route('costings.index')
        ]);
    }

    /** Dropdown order list */
    public function maanOrder(Request $request)
    {
        $order = Order::with(['party' => function ($query) {
            $query->select('id','name','type');
        }])->findOrFail($request->order_id);

        $details = OrderDetails::where('order_id',$request->order_id)->get();

        return response()->json([
            'order' => $order,
            'details' => $details,
        ]);
    }

    public function status(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|integer',
            'reason' => 'nullable|string|min:4|max:1000'
        ]);
        if ($request->status != 2) {
            $request->validate([
                'reason' => 'required|string|min:4|max:250'
            ]);
        }

        $sample = Costing::findOrFail($id);

        $sample->update([
            'status' => $request->status == 2 ? $request->status : 0,
            'reason' => $sample->reason ,
        ]);

        return response()->json([
            'message'   => __('Status updated Successfully'),
            'redirect'  => route('costings.index')
        ]);
    }

    /** view data on modal */
    public function maanCostingView($id)
    {
        $costing = Costing::findOrFail($id);
        return response()->json([
            'data'  => view('pages.costing.data', compact('costing'))->render()
        ]);
    }
}

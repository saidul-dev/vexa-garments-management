<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Production;
use Illuminate\Http\Request;

class MaanProductionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:productions-create')->only('create', 'store');
        $this->middleware('permission:productions-read')->only('index', 'show');
        $this->middleware('permission:productions-update')->only('edit', 'update');
        $this->middleware('permission:productions-delete')->only('destroy');
    }
    public function index(){
        $orders = Order::latest()->get();
        $productions = Production::with('order:id,party_id,order_no','order.party:id,name,type')
                    ->when(!auth()->user()->can('productions-list'), function($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->latest()
                    ->paginate(10);
        return view('pages.production.index',compact('productions','orders'));
    }

    /** filter the table list */
    public function maanFilter(Request $request)
    {
        $productions = Production::with('order:id,party_id,order_no','order.party:id,name')
            ->when($request->from_date, function($q) {
                $q->whereDate('created_at', '>=', request('from_date'));
            })
            ->when($request->to_date, function($q) {
                $q->whereDate('created_at', '<=', request('to_date'));
            })
            ->when(request('search'), function($q) {
                $q->where(function($q) {
                    $q->orWhere('item', 'like', '%'.request('search').'%')
                        ->orWhere('po', 'like', '%'.request('search').'%')
                        ->orWhere('style', 'like', '%'.request('search').'%')
                        ->orWhere('color', 'like', '%'.request('search').'%')
                        ->orWhereHas('order.party', function($q) {
                            $q->where('name', 'like', '%'.request('search').'%');
                        });
                });
            })
            ->when(!auth()->user()->can('productions-list'), function($query) {
                $query->where('user_id', auth()->id());
            })
            ->latest('id')
            ->paginate($request->per_page ?? 10);

        if ($request->ajax()){
            return response()->json([
                'data' => view('pages.production.datas', compact('productions'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    public function create()
    {
        $order_id   = request('order');
        $orders     = Order::latest()->get();
        return view('pages.production.create',compact('orders','order_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'order_info' => 'nullable|array',
            'cutting' => 'nullable|array',
            'print' => 'nullable|array',
            'input_line' => 'nullable|array',
            'output_line' => 'nullable|array',
            'finishing' => 'nullable|array',
            'poly' => 'nullable|array',
            'remarks' => 'nullable|string',

        ]);

        $production = Production::create($request->all() + [
            'user_id' => auth()->id()
        ]);

        sendNotification($production->id, route('productions.index'), __('New productions has been created.'));
        return response()->json([
            'message'   => __('Production saved successfully'),
            'redirect'  => route('productions.index')
        ]);
    }
    public function show($id)
    {
        $productions =  Production::with('order:id,party_id,order_no','order.party:id,name')->where('order_id',$id)->get();
        return view('pages.production.report',compact('productions'));
    }

    public function edit($id)
    {
        $orders         = Order::latest()->get();
        $production     = Production::findOrFail($id);
        $singleOrder    = Order::with('orderDetails')->findOrfail($production->order_id);

        abort_if(!check_visibility($production->created_at, 'productions', $production->user_id), 403);
        return view('pages.production.edit',compact('production','orders', 'singleOrder'));

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'order_id'          => 'required|integer',
            'item'              => 'nullable|string|max:250',
            'po'                => 'nullable|string|max:255',
            'style'             => 'nullable|string|max:255',
            'color'             => 'nullable|string|max:250',
            'order_qty'         => 'nullable|numeric',
            'order_qty_extra'   => 'nullable|numeric',
        ]);

        $production = Production::findOrFail($id);
        $permission = check_visibility($production->created_at, 'productions', $production->user_id);
        if (!$permission) {
            return response()->json(__('You don\'t have permission.'), 403);
        }

        $production->update($request->all() + [
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'message'   => __('Production updated successfully'),
            'redirect'  => route('productions.index')
        ]);
    }

    public function destroy($id)
    {
        $production = Production::findOrFail($id);
        $permission = check_visibility($production->created_at, 'productions', $production->user_id);
        if (!$permission) {
            return response()->json(__('You don\'t have permission.'), 403);
        }

        $production->delete();
        return response()->json([
            'message'   => __('Production deleted successfully'),
            'redirect'  => route('productions.index')
        ]);
    }

    /** Dropdown order list */
    public function getOrder(Request $request)
    {
        $order = Order::with('party', 'orderDetails')->findOrFail($request->id);
        return response()->json([
            'order' => $order,
        ]);
    }

}

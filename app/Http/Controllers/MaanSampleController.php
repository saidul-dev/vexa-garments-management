<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Sample;
use Illuminate\Http\Request;

class MaanSampleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:samples-create')->only('create', 'store');
        $this->middleware('permission:samples-read')->only('index', 'show');
        $this->middleware('permission:samples-update')->only('edit', 'update');
        $this->middleware('permission:samples-delete')->only('destroy');
    }

    public function index()
    {
        $orders = Order::select('id','order_no')->doesntHave('sample')->whereStatus(2)->latest()->get();
        $samples = Sample::with('order')
                    ->when(!auth()->user()->can('samples-list'), function($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->latest()
                    ->paginate(10);
        return view('pages.sample.index',compact('orders','samples'));
    }

    /** filter the table list */
    public function maanFilter(Request $request)
    {
        $samples = Sample::with('order')
                    ->when(!auth()->user()->can('samples-list'), function($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->when(request('search'), function($q) use($request) {
                        $q->where(function($q) use($request) {
                            $q->where('consignee', 'like', '%'.$request->search.'%')
                                ->orWhere('styles', 'like', '%'.$request->search.'%')
                                ->orWhere('types', 'like', '%'.$request->search.'%')
                                ->orWhere('quantities', 'like', '%'.$request->search.'%')
                                ->orWhereHas('order', function($q) use($request){
                                    $q->where('order_no', 'like', '%'.$request->search.'%');
                                });
                        });
                    })
                    ->latest()
                    ->paginate($request->per_page ?? 10);

        if($request->ajax()){
            return response()->json([
                'data' => view('pages.sample.datas',compact('samples'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id'   =>'required|integer|unique:samples',
            'consignee'  =>'required|string',
            'styles'     =>'required|array',
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
            'colors'     =>'nullable|array',
            'items'      =>'nullable|array',
            'types'      =>'nullable|array',
            'sizes'      =>'nullable|array',
            'quantities' =>'required|array',
            'status'     =>'nullable|string',
        ], [
            'styles.*.required' => 'Sorry the style number can not be empty!',
        ]);


        $sample = Sample::create($request->all() + [
            'user_id' => auth()->id(),
             'status' => auth()->user()->role == 'superadmin' || auth()->user()->role == 'admin' ? 2 : 1,

            ]);
        sendNotification($sample->id, route('samples.index'), __('New Sample has been created.'));
        return response()->json([
            'message'   => __('Sample saved successfully'),
            'redirect'  => route('samples.index')
        ]);
    }

    public function show($id)
    {
        $sample  = Sample::findOrFail($id);
        return view('pages.sample.show',compact('sample'));
    }

    public function edit($id)
    {
        $orders = Order::select('id','order_no')->whereStatus(2)->latest()->get();
        $sample = Sample::findOrFail($id);
        abort_if(!check_visibility($sample->created_at, 'samples', $sample->user_id), 403);
        return view('pages.sample.edit',compact('sample','orders'));
    }

    public function update(Request $request, $id)
    {
         $request->validate([
            'order_id'   =>'required|integer|unique:samples,order_id,'.$id,
            'consignee'  =>'required|string',
            'styles'     =>'required|array',
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
            'colors'     =>'nullable|array',
            'items'      =>'nullable|array',
            'types'      =>'nullable|array',
            'sizes'      =>'nullable|array',
            'quantities' =>'required|array',
            'status'     =>'nullable|string',
        ], [
            'styles.*.required' => 'Sorry the style number can not be empty!',
        ]);



        $sample = Sample::findOrFail($id);
        $permission = check_visibility($sample->created_at, 'samples', $sample->user_id);
        if (!$permission) {
            return response()->json(__('You don\'t have permission.'), 403);
        }

        $sample->update($request->all() + [
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'message'   => __('Sample updated successfully'),
            'redirect'  => route('samples.index')
        ]);
    }

    public function destroy($id)
    {
        $sample = Sample::findOrFail($id);
        $permission = check_visibility($sample->created_at, 'samples', $sample->user_id);
        if (!$permission) {
            return response()->json(__('You don\'t have permission.'), 403);
        }

        $sample->delete();
        return response()->json([
            'message'   => __('Sample deleted successfully'),
            'redirect'  => route('samples.index')
        ]);
    }

    public function getOrder(Request $request)
    {
        $order = Order::with( 'orderDetails')->findOrFail($request->id);

        return response()->json([
            'order' => $order,
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

        $sample = Sample::findOrFail($id);

        $sample->update([
            'status' => $request->status == 2 ? $request->status : 0,
            'reason' => $sample->reason ,
        ]);

        return response()->json([
            'message'   => __('Sample status updated successfully'),
            'redirect'  => route('samples.index')
        ]);
    }

    public function print($id)
    {
        $sample = Sample::with('order')->findOrFail($id);
        $qty = 0;

        foreach ($sample->styles ?? [] as $key => $style) {
            $qty += $sample->quantities[$key] ?? '';
        }
        return view('pages.sample.print', compact('sample', 'qty'));
    }


}

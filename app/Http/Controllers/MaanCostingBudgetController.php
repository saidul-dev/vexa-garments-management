<?php

namespace App\Http\Controllers;

use App\Helpers\HasUploader;
use App\Models\Costbudget;
use App\Models\Order;
use App\Models\OrderDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaanCostingBudgetController extends Controller
{
    use HasUploader;

    public function __construct()
    {
        $this->middleware('permission:budgets-create')->only('create', 'store');
        $this->middleware('permission:budgets-read')->only('index', 'show');
        $this->middleware('permission:budgets-update')->only('edit', 'update');
        $this->middleware('permission:budgets-delete')->only('destroy');
    }

    public function index()
    {
        $orders = Order::select('id','order_no')->whereStatus(2)->latest()->get();
        $budgets = Costbudget::with('order','order.merchandiser','order.party')
                    ->when(!auth()->user()->can('budgets-list'), function($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->latest()
                    ->paginate(10);
        return view('pages.budget.index',compact('budgets','orders'));
    }

    public function maanFilter(Request $request)
    {
        $budgets = Costbudget::with('order')
                    ->when(!auth()->user()->can('budgets-list'), function($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->when(request('search'), function($q) use($request) {
                        $q->where(function($q) use($request) {
                            $q->whereHas('order', function($q) use($request) {
                                $q->where('order_no', 'like', '%'.$request->search.'%')
                                ->orWhere('order_info->style', 'like', '%'.$request->search.'%');
                            })
                            ->orWhereHas('order.merchandiser', function ($q) use($request){
                                $q->where('name', 'like', '%'.$request->search.'%');
                            })
                            ->orWhereHas('order.party', function ($q) use($request){
                                $q->where('name', 'like', '%'.$request->search.'%')
                                ->orWhere('type', 'like', '%'.$request->search.'%');
                            });
                        });
                    })
                    ->latest()
                    ->paginate($request->per_page ?? 10);

        if($request->ajax()){
           return response()->json([
               'data' => view('pages.budget.datas',compact('budgets'))->render()
           ]);
       }
        return redirect(url()->previous());
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id'          =>'required|integer',
            'pre_cost_date'     =>'required|date',
            'post_cost_date'    =>'required|date',
            'image'             =>'nullable|image',
            'other_cost'        => 'nullable|numeric',
        ]);

        $budget = Costbudget::create($request->except('image') + [
            'user_id' => auth()->id(),
            'other_cost' => $request->input('other_cost', 0),
            'image' => $request->image ? $this->upload($request,'image') : null,
            'status' => in_array(auth()->user()->role, ['superadmin', 'admin']) ? 2 : 1,
        ]);
        sendNotification($budget->id, route('budgets.index'), __('New budget has been created.'));
        return response()->json([
            'message'   => __('Budget saved successfully'),
            'redirect'  => route('budgets.index')
        ]);

    }

    public function edit($id)
    {
        $budget = Costbudget::with('order')->findOrFail($id);
        abort_if(!check_visibility($budget->created_at, 'budgets', $budget->user_id), 403);
        $orders = Order::select('id','order_no')->whereStatus(2)->latest()->get();
        return view('pages.budget.edit', compact('orders','budget'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'order_id'          =>'required|integer',
            'pre_cost_date'     =>'required|date',
            'post_cost_date'    =>'required|date',
            'image'             =>'nullable|image',
        ]);

        $budget = Costbudget::findOrFail($id);
        $permission = check_visibility($budget->created_at, 'budgets', $budget->user_id);
        if (!$permission) {
            return response()->json(__('You don\'t have permission.'), 403);
        }

        $budget->update($request->except('image') + [
            'user_id' => auth()->id(),
            'image' => $request->image ? $this->upload($request, 'image', $budget->image) : $budget->image,
        ]);
        return response()->json([
            'message'   => __('Budget updated successfully'),
            'redirect'  => route('budgets.index')
        ]);
    }

    public function destroy($id)
    {
        $budget = Costbudget::findOrFail($id);
        $permission = check_visibility($budget->created_at, 'budgets', $budget->user_id);
        if (!$permission) {
            return response()->json(__('You don\'t have permission.'), 403);
        }

        if (file_exists($budget->image)) {
            Storage::delete($budget->image);
        }
        $budget->delete();
        return response()->json([
            'message'   => __('Budget deleted successfully'),
            'redirect'  => route('budgets.index')
        ]);
    }

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
                'reason' => 'required|string|min:4|max:1000'
            ]);
        }
        $budget = Costbudget::findOrFail($id);
        $budget->update([
            'status' => $request->status == 2 ? $request->status : 0,
            'meta' => [
                'reason' => $request->reason
            ],
        ]);
        return response()->json([
            'message'   => __('Budget status updated successfully'),
            'redirect'  => route('budgets.index')
        ]);
    }

    public function maanBudgetPrint($id)
    {
        $budget = Costbudget::with('order')->findOrFail($id);
        return view('pages.budget.print',compact('budget'));
    }

    /** view data on modal */
    public function maanBudgetView($id)
    {
        $budget = Costbudget::findOrFail($id);
        return response()->json([
            'data'  => view('pages.budget.print', compact('budget'))->render()
        ]);
    }
}

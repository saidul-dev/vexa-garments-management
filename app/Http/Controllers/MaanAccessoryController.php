<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Accessory;
use Illuminate\Http\Request;

class MaanAccessoryController extends Controller
{
    public function index()
    {
        $units = Unit::where('status', 1)->latest()->get();
        $accessories = Accessory::with('unit')->latest()->paginate(10);
        return view('pages.accessory.list.index',compact('accessories', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'nullable|string',
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:255|unique:accessories',
            'unit_price' => 'required|numeric|min:0|max:9999999999999',
        ]);

        $accessory = Accessory::create($request->all() + [
            'user_id' => auth()->id(),
        ]);

        sendNotification($accessory->id, route('accessories.index'), __('New Accessories has been created.'));
        return response()->json([
            'message'   => __('Accessory created successfully'),
            'redirect'  => route('accessories.index')
        ]);
    }

    public function edit(Accessory $accessory)
    {
        abort_if(!check_visibility($accessory->created_at, 'accessories', $accessory->user_id), 403);
        $units = Unit::where('status', 1)->latest()->get();
        return view('pages.accessory.list.edit', compact('accessory', 'units'));
    }


    public function update(Request $request, Accessory $accessory)
    {
        $request->validate([
            'description' => 'nullable|string',
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:255|unique:accessories,name,'.$accessory->id,
            'unit_price' => 'required|numeric|min:0|max:9999999999999',
        ]);

        $permission = check_visibility($accessory->created_at, 'accessories', $accessory->user_id);
        if (!$permission) {
            return response()->json(__('You don\'t have permission.'), 403);
        }

        $accessory->update($request->all()+[
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'message'   => __('Accessory updated successfully'),
            'redirect'  => route('accessories.index')
        ]);
    }

    public function destroy(Accessory $accessory)
    {
        $permission = check_visibility($accessory->created_at, 'accessories', $accessory->user_id);
        if (!$permission) {
            return response()->json(__('You don\'t have permission.'), 403);
        }

        $accessory->delete();
        return response()->json([
            'message'   => __('Accessory deleted successfully'),
            'redirect'  => route('accessories.index')
        ]);
    }
}

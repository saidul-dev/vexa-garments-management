<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class AcnooUnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:units-create')->only( 'store');
        $this->middleware('permission:units-read')->only('index');
        $this->middleware('permission:units-update')->only('edit', 'update');
        $this->middleware('permission:units-delete')->only('destroy');
    }

    public function index()
    {
        $units = Unit::latest()->paginate(10);
        return view('pages.accessory.units.index',compact('units'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:units',
        ]);

        $unit = Unit::create($request->all());

        sendNotification($unit->id, route('units.index'), __('New Units has been created.'));
        return response()->json([
            'message'   => __('Unit created successfully'),
            'redirect'  => route('units.index')
        ]);
    }

    public function edit(string $id)
    {
        $unit = Unit::findOrfail($id);

        return view('pages.accessory.units.edit',compact('unit'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:units,name,'.$id,
        ]);
        $unit = Unit::findOrfail($id);

        $unit->update($request->all());

        return response()->json([
            'message'   => __('Unit updated successfully'),
            'redirect'  => route('units.index')
        ]);
    }

    public function destroy(string $id)
    {
        $unit = Unit::findOrfail($id);

        $unit->delete();
        return response()->json([
            'message'   => __('Unit deleted successfully'),
            'redirect'  => route('units.index')
        ]);
    }
}

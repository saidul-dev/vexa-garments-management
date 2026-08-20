<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;

class AcnooDesignationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:designations-create')->only('store');
        $this->middleware('permission:designations-read')->only('index');
        $this->middleware('permission:designations-update')->only('update', 'status');
        $this->middleware('permission:designations-delete')->only('destroy');
    }

    public function index()
    {
        $designations = Designation::latest()->paginate(10);
        return view('pages.designations.index', compact('designations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Designation::create($request->all());

        return response()->json([
            'message' => 'Designation created cuccessfully.',
            'redirect' => route('designations.index'),
        ]);
    }

    public function update(Request $request, Designation $designation)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $designation->update($request->all());

        return response()->json([
            'message' => 'Designation updated successfully.',
            'redirect' => route('designations.index'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Designation $designation)
    {
        $designation->delete();

        return response()->json([
            'message' => 'Designation deleted successfully.',
            'redirect' => route('designations.index'),
        ]);
    }
}

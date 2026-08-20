<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Designation;
use App\Helpers\HasUploader;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AcnooEmployeeController extends Controller
{
    use HasUploader;
    public function __construct()
    {
        $this->middleware('permission:employees-create')->only('store');
        $this->middleware('permission:employees-read')->only('index');
        $this->middleware('permission:employees-update')->only('update', 'status');
        $this->middleware('permission:employees-delete')->only('destroy');
    }

    public function index()
    {
        $employees = Employee::with('designation:id,name')->latest()->paginate(10);
        return view('pages.employees.index', compact('employees'));
    }

    public function create()
    {
        $designations = Designation::select('id','name')->latest()->get();
        return view('pages.employees.create', compact( 'designations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'status' => 'boolean',
            'join_date' => 'nullable|date',
            'birth_date' => 'nullable|date',
            'email' => 'nullable|email|max:50',
            'phone' => 'nullable|string|max:20',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'gender' => 'nullable|string|in:male,female,other',
            'salary' => 'required|numeric|between:0,9999999.999',
            'designation_id' => 'required|exists:designations,id',
            'employee_type' => 'nullable|string|in:part_time,full_time',
            'nid_back' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nid_front' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $nidFront  = $request->nid_front ? $this->upload($request, 'nid_front') : null;
        $nidBack  = $request->nid_back ? $this->upload($request, 'nid_back') : null;

        Employee::create($request->all() + [
            'meta' => [
                'nid_front' => $nidFront,
                'nid_back'  => $nidBack,
            ],
        ]);

        return response()->json([
            'message'  => 'Employee created successfully.',
            'redirect' => route('employees.index'),
        ]);
    }
    

    public function edit(Employee $employee)
    {
        $designations = Designation::select('id','name')->latest()->get();
        return view('pages.employees.edit', compact( 'designations', 'employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'status' => 'boolean',
            'join_date' => 'nullable|date',
            'birth_date' => 'nullable|date',
            'email' => 'nullable|email|max:50',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'gender' => 'nullable|string|in:male,female,other',
            'salary' => 'required|numeric|between:0,9999999.999',
            'designation_id' => 'required|exists:designations,id',
            'employee_type' => 'nullable|string|in:part_time,full_time',
            'nid_back' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nid_front' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $employee->update($request->all() + [
            'meta' => [
                'nid_front' => $request->nid_front ? $this->upload($request, 'nid_front') : $employee->meta['nid_front'],
                'nid_back'  => $request->nid_back ? $this->upload($request, 'nid_back') : $employee->meta['nid_back'],
            ],
        ]);

        return response()->json([
            'message'  => 'Employee updated successfully.',
            'redirect' => route('employees.index'),
        ]);
    }

    public function destroy(Employee $employee)
    {
        if (!empty($employee->meta['nid_front']) && file_exists($employee->meta['nid_front'])) {
            Storage::delete($employee->meta['nid_front']);
        }
        if (!empty($employee->meta['nid_back']) && file_exists($employee->meta['nid_back'])) {
            Storage::delete($employee->meta['nid_back']);
        }

        $employee->delete();

        return response()->json([
            'message'  => 'Employee deleted successfully.',
            'redirect' => route('employees.index'),
        ]);
    }
}

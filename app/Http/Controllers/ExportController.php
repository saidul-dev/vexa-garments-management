<?php

namespace App\Http\Controllers;

use App\Exports\Export;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;


class ExportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request('model') == 'Shipment') {
            $columns = Schema::getColumnListing('shipments');
        }
        if (request('model') == 'Booking') {
            $columns = Schema::getColumnListing('bookings');
        }
        if (request('model') == 'Order') {
            $columns = Schema::getColumnListing('bookings');
        }
        if (request('model') == 'Bank') {
            $columns = Schema::getColumnListing('banks');
        }
        if (request('model') == 'Cash') {
            $columns = Schema::getColumnListing('cashes');
        }
        if (request('model') == 'Production') {
            $columns = Schema::getColumnListing('productions');
        }
        if (request('model') == 'Cheque') {
            $columns = Schema::getColumnListing('cheques');
        }
        if (request('model') == 'Costing') {
            $columns = Schema::getColumnListing('costings');
        }
        if (request('model') == 'CostBudget') {
            $columns = Schema::getColumnListing('costbudgets');
        }
        if (request('model') == 'Sample') {
            $columns = Schema::getColumnListing('samples');
        }
        if (request('model') == 'User') {
            $columns = Schema::getColumnListing('users');
        }
        if (request('model') == 'Accessory') {
            $columns = Schema::getColumnListing('accessories');
        }
        if (request('model') == 'AccessoryOrder') {
            $columns = Schema::getColumnListing('accessory_orders');
        }
        if (request('model') == 'Income') {
            $columns = Schema::getColumnListing('incomes');
        }
        if (request('model') == 'Expense') {
            $columns = Schema::getColumnListing('expenses');
        }
        if (request('model') == 'Party') {
            $columns = Schema::getColumnListing('parties');
        }
        if (request('model') == 'Voucher') {
            $columns = Schema::getColumnListing('vouchers');
        }
        if (request('model') == 'Unit') {
            $columns = Schema::getColumnListing('units');
        }
        if (request('model') == 'History') {
            $columns = Schema::getColumnListing('histories');
        }
        if (request('model') == 'Designation') {
            $columns = Schema::getColumnListing('designations');
        }
        if (request('model') == 'Employee') {
            $columns = Schema::getColumnListing('employees');
        }
        if (request('model') == 'Salary') {
            $columns = Schema::getColumnListing('salaries');
        }

        session()->put('model', request('model'));

        if (request('extension') === 'xlsx') {
            return Excel::download(new Export($columns), formatted_date(now()) . '-' . request('model') . '.xlsx');
        }
        if (request('extension') === 'csv') {
            return Excel::download(new Export($columns), formatted_date(now()) . '-' . request('model') . '.csv');
        }
        if (request('extension') === 'pdf') {
            return Excel::download(new Export($columns), formatted_date(now()) . '-' . request('model') . '.pdf');

        }
    }
}

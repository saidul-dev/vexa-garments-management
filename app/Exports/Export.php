<?php

namespace App\Exports;

use App\Models\Accessory;
use App\Models\AccessoryOrder;
use App\Models\Bank;
use App\Models\Booking;
use App\Models\Cash;
use App\Models\Cheque;
use App\Models\Costbudget;
use App\Models\Costing;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\History;
use App\Models\Income;
use App\Models\Order;
use App\Models\Party;
use App\Models\Production;
use App\Models\Salary;
use App\Models\Sample;
use App\Models\Shipment;
use App\Models\Unit;
use App\Models\User;
use App\Models\Voucher;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Export implements FromQuery, WithHeadings
{
    private $columns;

    public function __construct($columns)
    {
        $this->columns = $columns;
    }

    public function query()
    {
        $model = session('model');
        session()->forget('model');
        if ($model === 'Shipment') {
            return Shipment::query();
        }
        if ($model === 'Booking') {
            return Booking::query();
        }
        if ($model === 'Order') {
            return Order::query();
        }
        if ($model === 'Bank') {
            return Bank::query();
        }
        if ($model === 'Cash') {
            return Cash::query();
        }
        if ($model === 'Production') {
            return Production::query();
        }
        if ($model === 'Cheque') {
            return Cheque::query();
        }
        if ($model === 'Costing') {
            return Costing::query();
        }
        if ($model === 'CostBudget') {
            return Costbudget::query();
        }
        if ($model === 'Sample') {
            return Sample::query();
        }
        if ($model === 'Accessory') {
            return Accessory::query();
        }
        if ($model === 'AccessoryOrder') {
            return AccessoryOrder::query();
        }
        if ($model === 'Income') {
            return Income::query();
        }
        if ($model === 'Expense') {
            return Expense::query();
        }
        if ($model === 'Unit') {
            return Unit::query();
        }
        if ($model === 'User') {
            // Get the role value from the request
            $role = request('role');
            return User::where('role', $role);
        }
        if ($model === 'Party') {
            $query = Party::query();
            $type = request('type');
            if (request()->has('due')) {
                $query->where('type', $type)->where('due_amount', '>', 0);
            }
            else{

                $query->where('type', $type);
            }
            return $query;
        }
        if ($model === 'Voucher') {
            $query = Voucher::query();
            $type = request('type');
            return $query->where('type', $type);
        }
        if ($model === 'History') {
            $query = History::query();
            $table = request('table');
            return $query->where('table', $table);
        }
        if ($model === 'Designation') {
            return Designation::query();
        }
        if ($model === 'Employee') {
            return Employee::query();
        }
        if ($model === 'Salary') {
            return Salary::query();
        }

    }


    public function headings(): array
    {
        return $this->columns;
    }

}

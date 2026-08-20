<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CurrencySeeder::class,
            PermissionSeeder::class,
            UserTableSeeder::class,
            PartyTableSeeder::class,
            BankTableSeeder::class,
            UnitSeeder::class,
            AccessorySeeder::class,
            OrderTableSeeder::class,
            CostbudgetTableSeeder::class,
            CostingTableSeeder::class,
            SampleTableSeeder::class,
            BookingTableSeeder::class,
            ProductionSeeder::class,
            ExpenseTableSeeder::class,
            IncomeTableSeeder::class,
            VoucherTableSeeder::class,
            CashTableSeeder::class,
            BookingdetailsTableSeeder::class,
            DesignationTableSeeder::class,
            EmployeeTableSeeder::class,
            SalaryTableSeeder::class,
            ShipmentSeeder::class,
            OptionSeeder::class,
        ]);
    }
}

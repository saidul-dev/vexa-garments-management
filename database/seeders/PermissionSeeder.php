<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
        /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rolesStructure = [
            'Super Admin' => [
                'dashboard' => 'r',

                // Order Management
                'orders' => 'r,c,u,d,s,l',
                'costings' => 'r,c,u,d,s,l',
                'budgets' => 'r,c,u,d,s,l',
                'samples' => 'r,c,u,d,s,l',
                'bookings' => 'r,c,u,d,s,l',
                'accessories' => 'r,c,u,d,l',
                'accessory-orders' => 'r,c,u,d,l',
                'units' => 'r,c,u,d,l',
                'shipments' => 'r,c,u,d,l',

                // User List
                'users' => 'r,c,u,d',

                // Accounts
                'banks' => 'r,c,u,d,l',
                'cashes' => 'r,c,u,d,l',
                'cheques' => 'r,u,d,l',
                'transfers' => 'r,c,u,l',
                'dues' => 'r,c,u,d',
                'party-ledger' => 'r',
                'income' => 'r,c,u,d',
                'expense' => 'r,c,u,d',
                'credit-vouchers' => 'r,c,u,d,l',
                'debit-vouchers' => 'r,c,u,d,l',
                'transactions' => 'r,l',
                'loss-profit' => 'r',

                //HRM
                'designations' => 'r,c,u,d',
                'employees' => 'r,c,u,d',
                'salaries' => 'r,c,u,d',

                // Party List
                'parties' => 'r,c,u,d,f',
                'productions' => 'r,c,u,d,l',

                'reports' => 'r',
                'settings' => 'r,u',
                'currencies' => 'r,c,u,d,l',
                'roles' => 'r,c,u,d',
                'permissions' => 'r,c',
            ],
            'Admin' => [
                'dashboard' => 'r',

                // Order Management
                'orders' => 'r,c,u,d,s,l',
                'costings' => 'r,c,u,d,s,l',
                'budgets' => 'r,c,u,d,s,l',
                'samples' => 'r,c,u,d,s,l',
                'bookings' => 'r,c,u,d,s,l',
                'accessories' => 'r,c,u,d,l',
                'accessory-orders' => 'r,c,u,d,l',
                'units' => 'r,c,u,d,l',
                'shipments' => 'r,c,u,d,l',

                // User List
                'users' => 'r,c,u,d',

                // Accounts
                'banks' => 'r,c,u,d,l',
                'cashes' => 'r,c,u,d,l',
                'cheques' => 'r,u,d,l',
                'transfers' => 'r,c,u,l',
                'dues' => 'r,c,u,d',
                'party-ledger' => 'r',
                'income' => 'r,c,u,d',
                'expense' => 'r,c,u,d',
                'credit-vouchers' => 'r,c,u,d,l',
                'debit-vouchers' => 'r,c,u,d,l',
                'transactions' => 'r,l',
                'loss-profit' => 'r',

                //HRM
                'designations' => 'r,c,u,d',
                'employees' => 'r,c,u,d',
                'salaries' => 'r,c,u,d',

                // Party List
                'parties' => 'r,c,u,d,f',
                'productions' => 'r,c,u,d,l',

                'reports' => 'r',
                'roles' => 'r,c,u,d',
                'permissions' => 'r,c',
            ],
            'Manager' => [
                // Order Management
                'orders' => 'r,c,u,d',
                'costings' => 'r,c,u,d',
                'budgets' => 'r,c,u,d',
                'samples' => 'r,c,u,d',
                'bookings' => 'r,c,u,d',
                'accessories' => 'r,c,u,d',
                'accessory-orders' => 'r,c,u,d,l',
                'shipments' => 'r,c,u,d',

                // User List
                'users' => 'r,c,u,d',

                // Accounts
                'banks' => 'r,c,u,d,l',
                'cashes' => 'r,c,u,d',
                'cheques' => 'r,u,d',
                'transfers' => 'r,u',
                'dues' => 'r',
                'party-ledger' => 'r',
                'income' => 'r,c,u,d',
                'expense' => 'r,c,u,d',
                'credit-vouchers' => 'r,c,u,d',
                'debit-vouchers' => 'r,c,u,d',
                'transactions' => 'r',

                // Party List
                'parties' => 'r,c,u,d',
                'settings' => 'r,u',
            ],
            'Buyer' => [
                // Order Management
                'orders' => 'r,c,u,d',
                'budgets' => 'r,c,u,d',
            ],
            'Customer' => [
                // Order Management
                'orders' => 'r,c,u,d',
                'budgets' => 'r,c,u,d',
            ],
            'Supplier' => [
                // Order Management
                'orders' => 'r,c,u,d',
                'budgets' => 'r,c,u,d',
            ],
            'Merchandiser' => [
                // Order Management
                'orders' => 'r,c,u,d',
                'budgets' => 'r,c,u,d',
                'shipments' => 'r,c,u,d',
                'parties' => 'r,f',
            ],
            'Commercial' => [
                // Order Management
                'orders' => 'r,c,u,d',
                'budgets' => 'r,c,u,d',
            ],
            'Accountant' => [
                // Accounts
                'banks' => 'r,c,u,d,l',
                'cashes' => 'r,c,u,d,l',
                'cheques' => 'r,u,d,l',
                'transfers' => 'r,c,u,l',
                'dues' => 'r,c,u,d',
                'party-ledger' => 'r',
                'income' => 'r,c,u,d',
                'expense' => 'r,c,u,d',
                'credit-vouchers' => 'r,c,u,d,l',
                'debit-vouchers' => 'r,c,u,d,l',
                'transactions' => 'r,l',
                'loss-profit' => 'r',

                // Party List
                'parties' => 'r,c,u,d,f',
            ],
            'Production' => [
                // Order Management
                'productions' => 'r,c,u,d,l',
            ],

        ];

        foreach ($rolesStructure as $key => $modules) {
            // Create a new role
            $role = Role::firstOrCreate([
                'name' => str($key)->remove(' ')->lower(),
                'guard_name' => 'web'
            ]);
            $permissions = [];

            $this->command->info('Creating Role '. strtoupper($key));

            // Reading role permission modules
            foreach ($modules as $module => $value) {

                foreach (explode(',', $value) as $perm) {

                    $permissionValue = $this->permissionMap()->get($perm);

                    $permissions[] = Permission::firstOrCreate([
                        'name' => $module . '-' . $permissionValue,
                        'guard_name' => 'web'
                    ])->id;

                    $this->command->info('Creating Permission to '.$permissionValue.' for '. $module);
                }
            }

            // Attach all permissions to the role
            $role->permissions()->sync($permissions);

            $this->command->info("Creating '{$key}' user");
            // Create default user for each role
            $user = User::create([
                'phone' => '01111111',
                'role' => str($key)->remove(' ')->lower(),
                'name' => ucwords(str_replace('_', ' ', $key)),
                'password' => bcrypt(str($key)->remove(' ')->lower()),
                'email' => str($key)->remove(' ')->lower().'@'.str($key)->remove(' ')->lower().'.com',
                'image' => 'https://api.dicebear.com/9.x/pixel-art/svg?seed='.str($key)->slug(),
                'email_verified_at' => now(),
            ]);

            $user->assignRole($role);
        }
    }

    private function permissionMap(){
        return collect([
            'c' => 'create',
            'r' => 'read',
            'u' => 'update',
            'd' => 'delete',
            's' => 'status',
            'f' => 'folder',
            'l' => 'list',
        ]);
    }
}

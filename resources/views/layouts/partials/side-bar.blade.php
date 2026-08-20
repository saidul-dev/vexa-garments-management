<nav class="side-bar">
    <div class="side-bar-logo">
        <a href="javascript:void(0)"><img src="{{ asset( get_option('company')['logo'] ?? 'assets/images/logo/backend_logo.png') }}" alt="Logo"></a>
        <button class="close-btn"><i class="fal fa-times"></i></button>
    </div>
    <div class="side-bar-manu">
        <ul>
            @canany(['dashboard-read'])
            <li class="{{ Request::routeIs('dashboard') ? 'active' : ''}}">
                <a href="{{ route('dashboard') }}" class="active"><span class="sidebar-icon"><img src="{{ asset('assets/images/icons/home.svg') }}" alt="home.svg"></span>{{__('Dashboard')}}</a>
            </li>
            @endcanany

            @canany(['orders-read', 'costings-read', 'budgets-read', 'samples-read', 'bookings-read', 'shipments-read', 'productions-read'])
            <li class="dropdown {{ Request::routeIs('bookings.index','bookings.edit','orders.index','orders.edit','costings.index','costings.edit','budgets.index','budgets.edit','samples.index','samples.edit','samples.show','shipments.index','shipments.edit','productions.create','productions.index','productions.edit', 'order.history') ? 'active' : '' }}"><a href="#"> <span class="sidebar-icon"><img src="{{ asset('assets/images/icons/order.svg') }}" alt=""></span>
                {{__('Order Management')}}</a>
                <ul>

                    @can('orders-read')
                    <li><a class="{{ Request::routeIs('orders.index', 'productions.create', 'order.history') ? 'active' : '' }}" href="{{ route('orders.index') }}">{{__('Order List')}}</a></li>
                    @endcan

                    @can('bookings-read')
                        <li><a class="{{ Request::routeIs('bookings.index', 'bookings.edit') ? 'active' : '' }}" href="{{ route('bookings.index') }}">{{__('Booking List')}}</a></li>
                    @endcan

                    @can('budgets-read')
                    <li><a class="{{ Request::routeIs('budgets.index') ? 'active' : '' }}" href="{{ route('budgets.index') }}">{{__('Budget List')}}</a></li>
                    @endcan

                    @can('costings-read')
                    <li><a class="{{ Request::routeIs('costings.index') ? 'active' : '' }}" href="{{ route('costings.index') }}">{{__('Costing List')}}</a></li>
                    @endcan

                    @can('samples-read')
                    <li><a class="{{ Request::routeIs('samples.index') ? 'active' : '' }}" href="{{ route('samples.index') }}">{{__('Sample List')}}</a></li>
                    @endcan

                    @can('productions-read')
                        <li><a class="{{ Request::routeIs('productions.index') ? 'active' : '' }}" href="{{ route('productions.index') }}">{{__('Production List')}}</a></li>
                    @endcan

                    @can('shipments-read')
                    <li><a class="{{ Request::routeIs('shipments.index', 'shipments.edit') ? 'active' : '' }}" href="{{ route('shipments.index') }}">{{__('Shipments List')}}</a></li>
                    @endcan

                </ul>
            </li>
            @endcanany

            @canany(['accessories-read', 'accessory-orders-read'])
            <li class="dropdown {{ Request::routeIs('accessories.index','accessories.edit','accessory-orders.index','accessory-orders.edit', 'units.index', 'units.edit') ? 'active' : '' }}">
                <a href="#"><span class="sidebar-icon"><img src="{{ asset('assets/images/icons/accessory.png') }}" alt="item.svg"></span>{{__('Manage Inventory')}}</a>
                <ul>
                    @can('units-read')
                        <li><a class="{{ Request::routeIs('units.index') ? 'active' : '' }}" href="{{ route('units.index') }}">{{__('Units')}}</a></li>
                    @endcan
                    @can('accessories-read')
                        <li><a class="{{ Request::routeIs('accessories.index') ? 'active' : '' }}" href="{{ route('accessories.index') }}">{{__('Accessory List')}}</a></li>
                    @endcan
                    @can('accessory-orders-read')
                        <li><a class="{{ Request::routeIs('accessory-orders.index') ? 'active' : '' }}" href="{{ route('accessory-orders.index') }}">{{__('Accessories Orders')}}</a></li>
                    @endcan
                </ul>
            </li>
            @endcanany

            @canany(['users-read'])
            <li class="dropdown {{ Request::routeIs('users.edit') || request('users') ? 'active' : '' }}">
                <a href="#"><span class="sidebar-icon"><img src="{{ asset('assets/images/icons/user.svg') }}" alt="user.svg"></span>
                    {{__('User Management')}} </a>
                <ul>
                    <li><a class="{{ request('users') == 'admin' ? 'active' : '' }}" href="{{ route('users.index', ['users' => 'admin']) }}">{{__('Admin')}}</a></li>

                    <li><a class="{{ request('users') == 'buyer' ? 'active' : '' }}" href="{{ route('users.index', ['users' => 'buyer']) }}">{{__('Buyer')}}</a></li>

                    <li><a class="{{ request('users') == 'merchandiser' ? 'active' : '' }}" href="{{ route('users.index', ['users' => 'merchandiser']) }}">{{__('Merchandiser')}}</a></li>

                    <li><a class="{{ request('users') == 'commercial' ? 'active' : '' }}" href="{{ route('users.index', ['users' => 'commercial']) }}">{{__('Commercial')}}</a></li>

                    <li><a class="{{ request('users') == 'accountant' ? 'active' : '' }}" href="{{ route('users.index', ['users' => 'accountant']) }}">{{__('Accountant')}}</a></li>

                    <li><a class="{{ request('users') == 'production' ? 'active' : '' }}" href="{{ route('users.index', ['users' => 'production']) }}">{{__('Production')}}</a></li>
                </ul>
            </li>
            @endcanany

            @canany(['banks-read', 'cashes-read', 'cheques-read', 'parties-read', 'dues-read', 'party-ledger-read', 'income-read', 'expense-read', 'credit-vouchers-read', 'debit-vouchers-read', 'debit-vouchers.create', 'transactions-read'])

            <li class="dropdown {{ !request('parties-type') && Request::routeIs('income.index',  'income.edit','expense.index', 'expense.edit', 'credit-vouchers.index', 'credit-vouchers.create', 'credit-vouchers.edit', 'debit-vouchers.index', 'debit-vouchers.edit', 'debit-vouchers.create', 'banks.index', 'cashes.index', 'cashes.edit', 'parties.create', 'cheques.index', 'cheques.edit', 'transactions.index','transaction.daily', 'transfers.index', 'transfers.edit', 'party-ledger.index', 'reports.cashbooks') || request()->has('parties') || request()->has('transfer') ? 'active' : '' }}">

                <a href="#"><span class="sidebar-icon"><img src="{{ asset('assets/images/icons/Wallet.svg') }}" alt=""></span>{{__('Accounts & Bank')}} </a>
                <ul>

                    @canany(['banks-read', 'cashes-read', 'cheques-read'])
                    <li class="dropdown inner-dropdown {{ Request::routeIs('banks.index','cashes.index', 'cashes.edit', 'cheques.index', 'cheques.edit', 'transfers.index', 'transfers.edit') || request('transfer') ? 'active' : '' }}">
                        <a href="#">{{__('Commercial')}}</a>
                        <ul>
                            @can('banks-read')
                            <li><a class="{{ Request::routeIs('banks.index', 'transfers.index') || request('transfer') ? 'active' : '' }}" href="{{ route('banks.index') }}">{{__('Bank Accounts')}}</a></li>
                            @endcan

                            @can('cashes-read')
                            <li><a class="{{ Request::routeIs('cashes.index', 'cashes.edit') ? 'active' : '' }}" href="{{ route('cashes.index') }}">{{__('Cash in Hand')}}</a></li>
                            @endcan

                            @can('cheques-read')
                            <li><a class="{{ Request::routeIs('cheques.index', 'cheques.edit') ? 'active' : '' }}" href="{{ route('cheques.index') }}" class="">@lang('Cheques')</a></li>
                            @endcan

                        </ul>
                    </li>
                    @endcanany

                    @canany(['parties-read', 'dues-read', 'income-read', 'expense-read', 'credit-vouchers-read', 'debit-vouchers-read', 'transactions-read'])

                    <li class="dropdown inner-dropdown {{ !request('parties-type') && Request::routeIs('income.index', 'income.edit', 'expense.index', 'expense.edit', 'credit-vouchers.index', 'credit-vouchers.create', 'credit-vouchers.edit', 'debit-vouchers.index', 'debit-vouchers.create', 'debit-vouchers.edit', 'parties.create', 'transactions.index','transaction.daily', 'party-ledger.index', 'reports.cashbooks') || request('parties') ? 'active' : '' }}">
                        <a href="#">{{__('General')}}</a>
                        <ul>
                            @can('income-read')
                            <li><a class="{{ Request::routeIs('income.index', 'income.edit') ? 'active' : '' }}" href="{{ route('income.index') }}">{{__('Income')}}</a></li>
                            @endcan

                            @can('expense-read')
                            <li><a class="{{ Request::routeIs('expense.index', 'expense.edit') ? 'active' : '' }}" href="{{ route('expense.index') }}">{{__('Expenses')}}</a></li>
                            @endcan

                            @canany(['credit-vouchers-read', 'credit-vouchers-create'])
                            <li><a class="{{ Request::routeIs('credit-vouchers.index', 'credit-vouchers.create', 'credit-vouchers.edit') ? 'active' : '' }}" href="{{ route('credit-vouchers.index') }}">{{__('Credit Voucher')}}</a></li>
                            @endcanany

                            @canany(['debit-vouchers-read', 'debit-vouchers.create'])
                            <li><a class="{{ Request::routeIs('debit-vouchers.index', 'debit-vouchers.create', 'debit-vouchers.edit') ? 'active' : '' }}" href="{{ route('debit-vouchers.index') }}">{{__('Debit Voucher')}}</a></li>
                            @endcanany

                            @can('transactions-read')
                            <li><a class="{{ Request::routeIs('transactions.index') ? 'active' : '' }}" href="{{ route('transactions.index') }}">{{__('Monthly Transaction')}}</a></li>
                            @endcan

                            @can('party-ledger-read')
                                <li><a class="{{ Request::routeIs('party-ledger.index') ? 'active' : '' }}" href="{{ route('party-ledger.index', ['type' => 'buyer']) }}">{{__('Party Ledger')}}</a></li>
                            @endcan
                            <li>
                                <a class="{{ Request::routeIs('reports.cashbooks') ? 'active' : '' }}" href="{{ route('reports.cashbooks')  }}">{{__('Daily Cashbook')}}</a>
                            </li>
                        </ul>
                    </li>
                    @endcanany
                </ul>
            </li>
            @endcanany

            @can('parties-read')
            <li class="dropdown {{ Request::routeIs('parties.edit') || request('parties-type') ? 'active' : '' }}">
                <a href="#"><span class="sidebar-icon"><img src="{{ asset('assets/images/icons/Garments-Item.svg') }}" alt="item.svg"></span>
                    {{__('Party List')}} </a>
                <ul>
                    <li><a class="{{ request('parties-type') == 'buyer' ? 'active' : '' }}" href="{{ route('parties.index', ['parties-type' => 'buyer']) }}">{{__('Buyers')}}</a></li>
                    <li><a class="{{ request('parties-type') == 'supplier' ? 'active' : '' }}" href="{{ route('parties.index', ['parties-type' => 'supplier']) }}">{{__('Suppliers')}}</a></li>
                </ul>
            </li>
            @endcan

            @canany(['designations-read', 'employees-read', 'salaries-read'])
            <li class="dropdown {{ Request::routeIs('designations.index', 'employees.index', 'employees.create', 'employees.edit', 'salaries.index', 'salaries.create', 'salaries.edit') ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon"><img src="{{ asset('assets/images/icons/user.svg') }}" alt="item.svg"></span>
                    {{__('HRM Management')}} </a>
                <ul>
                    @can('designations-read')
                        <li>
                            <a class="{{ Request::routeIs('designations.index') ? 'active' : '' }}" href="{{ route('designations.index') }}">
                                {{ __('Designations')}}
                            </a>
                        </li>
                    @endcan

                    @can('employees-read')
                        <li>
                            <a class="{{ Request::routeIs('employees.index', 'employees.create', 'employees.edit') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                                {{ __('Employees') }}
                            </a>
                        </li>
                    @endcan

                    @can('salaries-read')
                        <li>
                            <a class="{{ Request::routeIs('salaries.index', 'salaries.create', 'salaries.edit') ? 'active' : '' }}" href="{{ route('salaries.index') }}">
                                {{ __('Salaries List') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
            @endcanany

            @can('dues-read')
                <li class="dropdown {{ Request::routeIs('dues.index') ? 'active' : '' }}">
                    <a class="{{ Request::routeIs('dues.index') ? 'active' : '' }}" href="{{ route('dues.index', ['type' => 'buyer']) }}">
                        <span class="sidebar-icon"><img src="{{ asset('assets/images/icons/dues.png') }}" alt="item.svg"></span>
                        {{ __('Party Due List') }}
                    </a>
                </li>
            @endcan

            @can('loss-profit-read')
                <li class="dropdown {{ Request::routeIs('loss-profit.index', 'loss-profit.expense', 'loss-profit.income') ? 'active' : '' }}">
                    <a class="{{ Request::routeIs('loss-profit.index') ? 'active' : '' }}" href="{{ route('loss-profit.index') }}">
                        <span class="sidebar-icon">
                            <img src="{{ asset('assets/images/icons/loss-profit.png') }}" alt="item.svg">
                        </span>
                        @lang('Loss Profit')
                    </a>
                </li>
            @endcan

            @canany(['reports-read'])
            <li class="dropdown {{ Request::routeIs('reports.order', 'reports.production', 'reports.collections', 'reports.transactions', 'reports.payable-dues.index') || request('party_type') ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon">
                        <img src="{{ asset('assets/images/icons/report.png') }}" alt="item.svg">
                    </span>
                    {{__('Reports')}}
                </a>
                <ul>
                    <li>
                        <a class="{{ Request::routeIs('reports.order') ? 'active' : '' }}" href="{{ route('reports.order')  }}">{{__('Order')}}</a>
                    </li>
                    <li>
                        <a class="{{ Request::routeIs('reports.transactions') ? 'active' : '' }}" href="{{ route('reports.transactions')  }}">{{__('Transaction')}}</a>
                    </li>
                    <li>
                        <a class="{{ Request::routeIs('reports.production') ? 'active' : '' }}" href="{{ route('reports.production')  }}">{{__('Production')}}</a>
                    </li>
                    <li>
                        <a class="{{ Request::routeIs('reports.collections') ? 'active' : '' }}" href="{{ route('reports.collections')  }}">{{__('Sales Report')}}</a>
                    </li>
                    <li>
                        <a class="{{ Request::routeIs('reports.payable-dues.index') ? 'active' : '' }}" href="{{ route('reports.payable-dues.index')  }}">{{__('Purchase Report')}}</a>
                    </li>
                    <li>
                        <a class="{{ request('party_type') == 'buyer' ? 'active' : '' }}" href="{{ route('reports.party-dues.index', ['party_type' => 'buyer']) }}">{{ __('Buyer Due') }}</a>
                    </li>
                    <li>
                        <a class="{{ request('party_type') == 'supplier' ? 'active' : '' }}" href="{{ route('reports.party-dues.index', ['party_type' => 'supplier']) }}">{{ __('Supplier Due') }}</a>
                    </li>
                </ul>
            </li>
            @endcanany

            @canany(['roles-read', 'permissions-read'])
            <li class="dropdown {{ Request::routeIs('roles.index', 'permissions.index', 'roles.create', 'roles.edit') ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon"><img src="{{ asset('assets/images/icons/permission.png') }}" alt="item.svg"></span>
                    {{__('Roles & Permissions')}}
                </a>
                <ul>
                    @can('roles-read')
                    <li><a class="{{ Request::routeIs('roles.index', 'roles.create', 'roles.edit') ? 'active' : '' }}" href="{{ route('roles.index') }}">{{__('Roles')}}</a></li>
                    @endcan

                    @can('permissions-read')
                    <li><a class="{{ Request::routeIs('permissions.index') ? 'active' : '' }}" href="{{ route('permissions.index') }}">{{__('Permissions')}}</a></li>
                    @endcan
                </ul>
            </li>
            @endcanany

            @canany(['settings-read', 'currencies-read'])
            <li class="dropdown {{ Request::routeIs('settings.index', 'notifications.index', 'currencies.index', 'currencies.create', 'currencies.edit', 'system-settings.index') ? 'active' : '' }}">
                <a href="#">
                    <span class="sidebar-icon"><img src="{{ asset('assets/images/icons/settings.png') }}" alt="item.svg"></span>
                    {{ __('Settings') }}
                </a>
                <ul>
                    @can('currencies-read')
                    <li><a class="{{ Request::routeIs('currencies.index', 'currencies.create', 'currencies.edit') ? 'active' : '' }}" href="{{ route('currencies.index') }}">{{ __('Currency') }}</a></li>
                    @endcan
                    @if(Auth::user()->role == 'superadmin')
                    <li><a class="{{ Request::routeIs('notifications.index') ? 'active' : '' }}" href="{{ route('notifications.index') }}">{{ __('Notifications') }}</a></li>
                    @endif
                    @can('settings-read')
                    <li><a class="{{ Request::routeIs('system-settings.index') ? 'active' : '' }}" href="{{ route('system-settings.index') }}">{{ __('System Settings') }}</a></li>
                    <li><a class="{{ Request::routeIs('settings.index') ? 'active' : '' }}" href="{{ route('settings.index') }}">{{ __('Company Settings') }}</a></li>
                    @endcan
                </ul>
            </li>
            @endcanany
        </ul>
    </div>
</nav>

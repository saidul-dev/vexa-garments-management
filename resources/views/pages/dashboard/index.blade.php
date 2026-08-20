@extends('layouts.master')

@section('title')
    {{__('Dashboard') }}
@endsection

@section('main_content')
    <div class="erp-state-overview-section">
        <div class="container-fluid">
            <div class="erp-state-overview">
                <div class="erp-overview-grid-6">
                    <div class="erp-overview-item">
                        <div class="overview-icon count-content-wrapper">
                            <img src="{{ asset('assets/img/icon/1.svg') }}" alt="">
                        </div>
                        <div>
                            <h6 id="total_order">
                                <img src="{{ asset('assets/images/icons/loader.gif') }}"> {{-- loading icon --}}
                            </h6>
                            <p>Total Order</p>
                        </div>
                    </div>
                    <div class="erp-overview-item">
                        <div class="overview-icon">
                            <img src="{{ asset('assets/img/icon/2.svg') }}" alt="">
                        </div>
                        <div>
                            <h6 id="running_order_qty">
                                <img src="{{ asset('assets/images/icons/loader.gif') }}">
                            </h6>
                            <p>Running Order</p>
                        </div>
                    </div>
                    <div class="erp-overview-item">
                        <div class="overview-icon">
                            <img src="{{ asset('assets/img/icon/3.svg') }}" alt="">
                        </div>
                        <div>
                            <h6 id="pending_order">
                                <img src="{{ asset('assets/images/icons/loader.gif') }}">
                            </h6>
                            <p>Pending Order</p>
                        </div>
                    </div>
                    <div class="erp-overview-item">
                        <div class="overview-icon">
                            <img src="{{ asset('assets/img/icon/4.svg') }}" alt="">
                        </div>
                        <div>
                            <h6><span id="weekly_order_value">
                                    <img src="{{ asset('assets/images/icons/loader.gif') }}">
                                </span>
                            </h6>
                            <p>Weekly Value</p>
                        </div>
                    </div>
                    <div class="erp-overview-item">
                        <div class="overview-icon">
                            <img src="{{ asset('assets/img/icon/5.svg') }}" alt="">
                        </div>
                        <div>
                            <h6><span id="monthly_order_value">
                                    <img src="{{ asset('assets/images/icons/loader.gif') }}">
                                </span>
                            </h6>
                            <p>Monthly Value</p>
                        </div>
                    </div>
                    <div class="erp-overview-item">
                        <div class="overview-icon">
                            <img src="{{ asset('assets/img/icon/6.svg') }}" alt="">
                        </div>
                        <div>
                            <h6><span id="current_year_value">
                                    <img src="{{ asset('assets/images/icons/loader.gif') }}">
                                </span>
                            </h6>
                            <p>Yearly Value</p>
                        </div>
                    </div>
                    <div class="erp-overview-item">
                        <div class="overview-icon">
                            <img src="{{ asset('assets/img/icon/7.svg') }}" alt="">
                        </div>
                        <div>
                            <h6><span id="total_cash">
                                    <img src="{{ asset('assets/images/icons/loader.gif') }}">
                                </span>
                            </h6>
                            <p>Cash Balance</p>
                        </div>
                    </div>
                    <div class="erp-overview-item">
                        <div class="overview-icon">
                            <img src="{{ asset('assets/img/icon/12.svg') }}" alt="">
                        </div>
                        <div>
                            <h6><span id="total_bank_balance">
                                    <img src="{{ asset('assets/images/icons/loader.gif') }}">
                                </span>
                            </h6>
                            <p>Bank Balance</p>
                        </div>
                    </div>
                    <div class="erp-overview-item">
                        <div class="overview-icon">
                            <img src="{{ asset('assets/img/icon/8.svg') }}" alt="">
                        </div>
                        <div>
                            <h6><span id="supplier_due">
                                    <img src="{{ asset('assets/images/icons/loader.gif') }}">
                                </span>
                            </h6>
                            <p>Supplier Due</p>
                        </div>
                    </div>
                    <div class="erp-overview-item">
                        <div class="overview-icon">
                            <img src="{{ asset('assets/img/icon/9.svg') }}" alt="">
                        </div>
                        <div>
                            <h6><span id="monthly_expense">
                                    <img src="{{ asset('assets/images/icons/loader.gif') }}">
                                </span>
                            </h6>
                            <p>Monthly Expense</p>
                        </div>
                    </div>
                    <div class="erp-overview-item">
                        <div class="overview-icon">
                            <img src="{{ asset('assets/img/icon/10.svg') }}" alt="">
                        </div>
                        <div>
                            <h6><span id="debit_transaction">
                                    <img src="{{ asset('assets/images/icons/loader.gif') }}">
                                </span>
                            </h6>
                            <p>Debit Transaction</p>
                        </div>
                    </div>
                    <div class="erp-overview-item">
                        <div class="overview-icon">
                            <img src="{{ asset('assets/img/icon/11.svg') }}" alt="">
                        </div>
                        <div>
                            <h6><span id="credit_transaction">
                                    <img src="{{ asset('assets/images/icons/loader.gif') }}">
                                </span>
                            </h6>
                            <p>Credit Transaction</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="graph-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-8 col-lg-12">
                    <div class="erp-graph-box">
                        <div class="dashboard-card mt-4">
                            <div class="dashboard-card-header income-header">
                                <h4>Income VS Expense</h4>
                                <select class="form-control graph-nice-select earning-expense-month">
                                    @for ($i = date('Y'); $i >= 2022; $i--)
                                        <option @selected($i == date('Y')) value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="dashboard-card-body">
                                <canvas id="monthly-statistics"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-12">
                    <div class="erp-graph-box new-order">
                        <div class="dashboard-card dashboard-order-customer mt-4">
                            <div class="dashboard-card-header new-order-header">
                                <h4>New Order</h4>
                                <form action="{{ route('dashboard.order.filter') }}" method="POST" class="filter-form" table="#dashboard-order-data">
                                    @csrf
                                    <select name="status" class="form-control graph-nice-select">
                                        <option value="all">{{__('Status')}}</option>
                                        <option value="1">{{__('Pending')}}</option>
                                        <option value="2">{{__('Approved')}}</option>
                                        <option value="3">{{__('Completed')}}</option>
                                        <option value="0">{{__('Reject')}}</option>
                                    </select>
                                </form>
                            </div>
                            <div class="dashboard-card-body">
                                <div class="erp-box-content p-0">
                                    <div class="top-customer-table mt-3">
                                        <table class="table" id="dashboard-order-data">
                                            @include('pages.dashboard.order-datas')
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-8">
                    <div class="erp-graph-box yearly-status">
                        <div class="dashboard-card mt-4">
                            <div class="dashboard-card-header sales-ratio-header">
                                <h4>Sales Ratio</h4>
                                <select class="form-control graph-nice-select orders-ratio">
                                    @for ($i = date('Y'); $i >= 2022; $i--)
                                        <option @selected($i == date('Y')) value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="dashboard-card-body">
                                <canvas id="salesRatio"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="erp-graph-box">
                        <div class="dashboard-card mt-4">
                            <div class="dashboard-card-header sales-country-header">
                                <h5>{{ __('Sales By Country') }}</h5>
                                <select class="form-control graph-nice-select yearly-lc">
                                    @for ($i = date('Y'); $i >= 2022; $i--)
                                        <option @selected($i == date('Y')) value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="dashboard-card-body mt-3">
                                <canvas id="sales-by-country"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-md-12 col-lg-4">
                    <div class="erp-graph-box new-order">
                        <div class="dashboard-card dashboard-order-customer mt-4 pb-0">
                            <div class="dashboard-card-header buyer-header">
                                <h4>Top 5 Buyer/Customer</h4>
                                <form action="{{ route('dashboard.party.filter') }}" method="POST" class="filter-form" table="#dashboard-party-data">
                                    @csrf
                                    <select name="selected_year_buyer" class="form-control graph-nice-select">
                                        @for ($i = date('Y'); $i >= 2022; $i--)
                                            <option @selected($i == date('Y')) value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </form>
                            </div>
                            <div class="dashboard-card-body">
                                <div class="erp-box-content p-0">
                                    <div class="top-customer-table mt-3">
                                        <table class="table" id="dashboard-party-data">
                                            @include('pages.dashboard.party-datas')
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>

    <input type="hidden" value="{{ route('orders-ratio') }}" id="orders-ratio">
    <input type="hidden" value="{{ route('dashboard.data') }}" id="get-dashboard">
    <input type="hidden" value="{{ route('yearly-statistics') }}" id="yearly-statistics-url">
    <input type="hidden" value="{{ route('yearly-lc-value') }}" id="yearly-lc-value-url">
@endsection

@push('js')
    <script src="{{ asset('assets/js/chart.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/dashboard.js') }}"></script>
    <script>
        $(document).ready(function () {
            getDashboardData();
            orderRatioChartAjax();
            getYearlyLcValueAjax();
            getYearlyStatistics(year = new Date().getFullYear());
        })
    </script>
@endpush

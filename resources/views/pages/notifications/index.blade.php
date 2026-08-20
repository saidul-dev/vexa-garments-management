@extends('layouts.master')

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid mt-3">
            <div class="tab-content order-summary-tab">
                <div class="tab-pane fade show active" id="user-list">
                    <div class="table-header">
                        <h4>@lang('Notifications list')</h4>
                    </div>
                    <div class="table-top-form daily-transaction-between-wrapper">
                        <form action="{{ route('notifications.filter') }}" method="post" class="filter-form" table="#notification-data">
                            @csrf
                            <div class="grid-5">
                                <select name="days" class="table-select form-control m-0">
                                    <option value="daily">{{__('Today')}}</option>
                                    <option value="weekly">{{__('Last 7 Days')}}</option>
                                    <option value="15_days">{{__('Last 15 Days')}}</option>
                                    <option value="monthly">{{__('Last Month')}}</option>
                                    <option value="yearly">{{__('Last Year')}}</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="responsibe-table">
                        <table class="table" id="erp-table">
                            <thead>
                                <tr>
                                    <th>@lang('SL.')</th>
                                    <th>@lang('Message')</th>
                                    <th>@lang('Type')</th>
                                    <th>@lang('Created At')</th>
                                    <th>@lang('Read At')</th>
                                    <th class="print-d-none">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody id="notification-data">
                            @include('pages.notifications.datas')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



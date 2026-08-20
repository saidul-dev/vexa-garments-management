@extends('layouts.master')

@section('title')
    {{__ ('Currency') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-header">
                        <h4>{{__('Currency List')}}</h4>
                        @can('currencies-create')
                            <a href="{{ route('currencies.create') }}" class="add-order-btn rounded-2"><i class="fas fa-plus-circle"></i> {{__(' Add Currency')}} </a>
                        @endcan
                    </div>
                    <div class="table-top-form daily-transaction-between-wrapper">
                        <form action="{{ route('currency.filter') }}" method="post" class="filter-form" table="#currency_data">
                            @csrf
                            <div class="grid-5">
                                <div class="input-wrapper">
                                    <select name="per_page" class="table-select form-control m-0">
                                        <option value="1000">{{__('All Results')}}</option>
                                        <option value="10">{{__('Per Page- 10')}}</option>
                                        <option value="30">{{__('Per Page- 30')}}</option>
                                        <option value="50">{{__('Per Page- 50')}}</option>
                                        <option value="100">{{__('Per Page- 100')}}</option>
                                    </select>
                                </div>
                                <div class="input-wrapper">
                                    <input type="text" name="search" class="form-control" placeholder="Search...">
                                    <span class="position-absolute">
                                        <img src="{{ asset('assets/images/search.png') }}" alt="">
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="responsive-table">
                        {{-- table start --}}
                        <table class="table" id="erp-table">
                            <thead>
                            <tr>
                                <th>{{__('SL.')}}</th>
                                <th>{{__('name')}}</th>
                                <th>{{__('code')}}</th>
                                <th>{{__('symbol')}}</th>
                                <th>{{__('status')}}</th>
                                <th>{{__('Default')}}</th>
                                <th class="print-d-none">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody id="currency_data">
                            @include('pages.currencies.datas')
                            </tbody>
                        </table>
                        {{-- table end --}}
                    </div>
                    <div>
                        {{ $currencies->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


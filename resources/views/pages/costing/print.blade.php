@extends('layouts.blank')

@section('main_content')
    <div class="section-container print-wrapper p-0 A4-paper">
        <div class="erp-table-section">
            <div class="container-fluid">
                <button class="print-window theme-btn print-btn float-end"><i class="fa fa-print"></i> Print</button>
                <div class="table-header justify-content-center border-0 d-block text-center">
                    @include('pages.invoice.header2')
                    <h4 class="mt-2">{{__('COST BUDGET')}}</h4>
                </div>
                @include('pages.costing.data')
            </div>
        </div>
    </div>
@endsection



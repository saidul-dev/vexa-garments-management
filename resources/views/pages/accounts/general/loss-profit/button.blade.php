<div class="button-group nav">
    <a href="{{ route('loss-profit.index') }}" class="add-report-btn {{ Request::routeIs('loss-profit.index') ? 'active' : '' }}">
        {{ __('Loss / Profit') }}
    </a>
    <a href="{{ route('loss-profit.expense') }}" class="add-report-btn {{ Request::routeIs('loss-profit.expense') ? 'active' : '' }}">
        {{ __('Expense List') }}
    </a>
    <a href="{{ route('loss-profit.income') }}" class="add-report-btn {{ Request::routeIs('loss-profit.income') ? 'active' : '' }}">
        {{ __('Income List') }}
    </a>
</div>

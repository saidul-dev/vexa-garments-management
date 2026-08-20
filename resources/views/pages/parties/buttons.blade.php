<div class="table-header justify-content-end border-0 p-0">
    @if (request('parties') || Route::is('parties.create') && !request()->has('parties-type'))
    <div class="button-group nav">
        @can('parties-read')
        <a href="{{ route('parties.index', ['parties' => 'buyer']) }}" class="add-report-btn {{ request('parties') == 'buyer' ? 'active':'' }}">
            {{__('Buyers List')}}
        </a>
        <a href="{{ route('parties.index', ['parties' => 'customer']) }}" class="add-report-btn {{ request('parties') == 'customer' ? 'active':'' }}">
            {{__('Customers List')}}
        </a>
        <a href="{{ route('parties.index', ['parties' => 'supplier']) }}" class="add-report-btn {{ request('parties') == 'supplier' ? 'active':'' }}">
            {{__('Suppliers List')}}
        </a>
        @endcan
        @can('parties-create')
        <a href="{{ route('parties.create') }}" class="add-report-btn @if(Request::is(['parties/create'])) active @endif">
            {{__('Add New Party')}}
        </a>
        @endcan
    </div>
    @else
    <div class="button-group nav">
        @can('parties-read')
        <a href="{{ route('parties.index', ['parties-type' => request('parties-type')]) }}" class="add-report-btn {{ Route::is('parties.index') ? 'active' : '' }}"><i class="fas fa-list"></i> {{ __(ucfirst(request('parties-type'))) }} {{ __('List') }}</a>
        @endcan
        @can('parties-create')
        <a href="{{ route('parties.create', ['parties-type' => request('parties-type')]) }}" class="add-report-btn {{ Route::is('parties.create') ? 'active' : '' }}"><i class="fas fa-plus-circle"></i> {{ __('Add New') }} {{ __(ucfirst(request('parties-type'))) }}</a>
        @endcan
    </div>
    @endif
</div>

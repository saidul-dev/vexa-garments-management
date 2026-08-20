<div class="erp-state-overview-section">
    <div class="container-fluid">
        <div class="erp-state-overview">
            <div class="erp-state-overview-wrapper">
                <div class="state-overview-box daily-transaction-between-wrapper">
                    <div class="icons">
                        <img src="{{ asset('assets/images/icons/value.svg') }}" alt="{{ asset('assets/images/icons/value.svg') }}">
                    </div>
                    <div class="count-content-wrapper">
                        <h2>{{ currency_format($bank_balance) }}</h2>
                        <p>{{__('Balance')}}</p>
                    </div>
                </div>
                <div class="state-overview-box daily-transaction-between-wrapper">
                    <div class="icons">
                        <img src="{{ asset('assets/images/icons/deposit.png') }}" alt="{{ asset('assets/images/icons/deposit.png') }}">
                    </div>
                    <div class="count-content-wrapper">
                        <h2>{{ currency_format($deposit) }}</h2>
                        <p>{{__('Deposit')}}</p>
                    </div>
                </div>
                <div class="state-overview-box daily-transaction-between-wrapper">
                    <div class="icons">
                        <img src="{{ asset('assets/images/icons/money-withdrawal.png') }}" alt="{{ asset('assets/images/icons/money-withdrawal.png') }}">
                    </div>
                    <div class="count-content-wrapper">
                        <h2>{{ currency_format($withdraw) }}</h2>
                        <p>{{__('Withdraw')}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

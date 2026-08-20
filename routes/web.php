<?php

use App\Http\Controllers as CON;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::redirect('/', 'login');

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [CON\DashboardController::class, 'maanIndex'])->name('dashboard');
    Route::post('dashboard/order-filter', [CON\DashboardController::class, 'maanOrderFilter'])->name('dashboard.order.filter');
    // Route::post('dashboard/party-filter', [CON\DashboardController::class, 'maanPartyFilter'])->name('dashboard.party.filter');

    Route::get('dashboard/get-dashboard', [CON\DashboardController::class, 'getDashboardData'])->name('dashboard.data');
    Route::get('yearly-statistics', [CON\DashboardController::class, 'yearlyStatistics'])->name('yearly-statistics');
    Route::get('yearly-lc-value', [CON\DashboardController::class, 'yearlyLcValue'])->name('yearly-lc-value');
    Route::get('orders-ratio', [CON\DashboardController::class, 'orderRatio'])->name('orders-ratio');

    Route::resource('accessories', CON\MaanAccessoryController::class)->except('create', 'show');
    Route::resource('accessory-orders', CON\MaanAccessoryOrderController::class)->except('create', 'show');
    Route::post('accessories-orders/filter', [CON\MaanAccessoryOrderController::class, 'maanFilter'])->name('accessories-orders.filter');
    Route::resource('units', CON\AcnooUnitController::class)->except('create', 'show');

    // income
    Route::prefix('income')->name('income.')->group(function () {
        Route::get('/', [CON\IncomeController::class, 'maanIncomeIndex'])->name('index');
        Route::post('/store', [CON\IncomeController::class, 'maanIncomeStore'])->name('store');
        Route::get('/edit/{id}', [CON\IncomeController::class, 'maanIncomeEdit'])->name('edit');
        Route::post('/update/{id}', [CON\IncomeController::class, 'maanIncomeUpdate'])->name('update');
        Route::delete('/delete/{id}', [CON\IncomeController::class, 'maanIncomeDelete'])->name('delete');
    });

    // expense
    Route::prefix('expense')->name('expense.')->group(function () {
        Route::get('/', [CON\ExpenseController::class, 'maanExpenseIndex'])->name('index');
        Route::post('/store', [CON\ExpenseController::class, 'maanExpenseStore'])->name('store');
        Route::get('/edit/{id}', [CON\ExpenseController::class, 'maanExpenseEdit'])->name('edit');
        Route::post('/update/{id}', [CON\ExpenseController::class, 'maanExpenseUpdate'])->name('update');
        Route::delete('/delete/{id}', [CON\ExpenseController::class, 'maanExpenseDelete'])->name('delete');
    });

    // bank account
    Route::prefix('banks')->name('banks.')->group(function () {
        Route::get('/', [CON\BankController::class, 'maanIndex'])->name('index');
        Route::post('/store', [CON\BankController::class, 'maanStore'])->name('store');
        Route::post('/edit/{id}', [CON\BankController::class, 'maanEdit'])->name('edit');
        Route::match(['put', 'patch'], '/update/{id}', [CON\BankController::class, 'maanUpdate'])->name('update');
        Route::delete('/delete/{id}', [CON\BankController::class, 'maanDelete'])->name('delete');
    });

    // Transfer
    Route::resource('transfers', CON\MaanTransferController::class)->except('create', 'show', 'destroy');

    // Booking
    Route::resource('bookings', CON\MaanBookingController::class)->except('create');
    Route::get('booking/fabric/{id}', [CON\MaanBookingController::class, 'maanFabric'])->name('booking.fabric');
    Route::get('booking/{id}/collar-cuff', [CON\MaanBookingController::class, 'maanCollarCuff'])->name('booking.collar-cuff');
    Route::post('booking/status/{id}', [CON\MaanBookingController::class, 'status'])->name('booking.status');
    Route::post('booking/filter', [CON\MaanBookingController::class, 'maanFilter'])->name('booking.filter');
    Route::get('booking/order', [CON\MaanBookingController::class, 'maanOrder'])->name('booking.order');

    // Order
    Route::resource('orders', CON\MaanOrderController::class)->except('create');
    Route::post('order/filter', [CON\MaanOrderController::class, 'maanFilter'])->name('order.filter');
    Route::post('order/status/{id}', [CON\MaanOrderController::class, 'status'])->name('order.status');
    Route::get('order-details/{id}', [CON\MaanOrderController::class, 'orderDetails'])->name('order.details');
    Route::get('order/histories/{id}', [CON\MaanOrderController::class, 'orderHistory'])->name('order.history');
    Route::get('order/histories/print/{id}', [CON\MaanOrderController::class, 'orderHistoryPrint'])->name('order.history.print');
    Route::post('order/history/filter', [CON\MaanOrderController::class, 'historyFilter'])->name('order.history.filter');

    // Sample
    Route::resource('samples', CON\MaanSampleController::class)->except('create');
    Route::post('sample/filter', [CON\MaanSampleController::class, 'maanFilter'])->name('sample.filter');
    Route::get('sample/get/order', [CON\MaanSampleController::class, 'getOrder'])->name('sample.get-order');
    Route::post('sample/status/{id}', [CON\MaanSampleController::class, 'status'])->name('sample.status');
    Route::get('sample/print/{id}', [CON\MaanSampleController::class, 'print'])->name('sample.print');

    //costing
    Route::resource('costings', CON\MaanCostingController::class)->except('create');
    Route::get('costing/get/order', [CON\MaanCostingController::class, 'maanOrder'])->name('costing.order');
    Route::post('costings/filter', [CON\MaanCostingController::class, 'filter'])->name('costings.filter');
    Route::get('costing/view/{id}', [CON\MaanCostingController::class, 'maanCostingView'])->name('costing.view');
    Route::post('costing/status/{id}', [CON\MaanCostingController::class, 'status'])->name('costing.status');

    //budget
    Route::resource('budgets', CON\MaanCostingBudgetController::class)->except('create');
    Route::get('budget/order', [CON\MaanCostingBudgetController::class, 'maanOrder'])->name('budget.order');
    Route::post('budget/status/{id}', [CON\MaanCostingBudgetController::class, 'status'])->name('budget.status');
    Route::get('budget/print/{id}', [CON\MaanCostingBudgetController::class, 'maanBudgetPrint'])->name('budget.print');
    Route::post('budget/filter', [CON\MaanCostingBudgetController::class, 'maanFilter'])->name('budget.filter');
    Route::get('budget/view/{id}', [CON\MaanCostingBudgetController::class, 'maanBudgetView'])->name('budget.view');

    //shipment
    Route::resource('shipments', CON\MaanShipmentController::class)->except('create', 'show');
    Route::get('shipment/get/order', [CON\MaanShipmentController::class, 'getOrder'])->name('shipment.get-order');
    Route::post('shipment/filter', [CON\MaanShipmentController::class, 'maanFilter'])->name('shipment.filter');
    Route::get('shipments/{order}/{invoice}', [CON\MaanShipmentController::class, 'print'])->name('shipment.print');

    Route::resource('folders', CON\FolderController::class);
    Route::resource('files-uploads', CON\FileController::class);
    Route::resource('parties', CON\MaanPartyController::class);
    Route::resource('user-profile', CON\MannUserProfileController::class);
    Route::resource('dues', CON\MaanDueController::class)->only('index');
    Route::post('due/filter', [CON\MaanDueController::class, 'maanFilter'])->name('due.filter');
    Route::resource('settings', CON\SettingsController::class)->only('index', 'update');

    Route::resource('cashes', CON\MaanCashController::class)->except('create', 'show');
    Route::post('cash/filter', [CON\MaanCashController::class, 'maanFilter'])->name('cash.filter');

    Route::resource('party-ledger', CON\MaanPartyLedgerController::class)->only('index', 'show');

    Route::resource('cheques', CON\MaanChequeController::class)->only('index', 'update', 'destroy');
    Route::post('cheque/filter', [CON\MaanChequeController::class, 'maanFilter'])->name('cheque.filter');

    Route::resource('debit-vouchers', CON\MaanDebitVoucherController::class)->except('show');
    Route::post('debit-voucher/filter', [CON\MaanDebitVoucherController::class, 'maanFilter'])->name('debit-voucher.filter');
    Route::get('get-expenses/{party_id}', [CON\MaanDebitVoucherController::class, 'getExpenses'])->name('get-expenses');

    Route::resource('credit-vouchers', CON\MaanCreditVoucherController::class)->except('show');
    Route::get('get-invoices/{party_id}', [CON\MaanCreditVoucherController::class, 'getInvoices'])->name('get-invoices');
    Route::post('credit-voucher/filter', [CON\MaanCreditVoucherController::class, 'maanFilter'])->name('credit-voucher.filter');

    Route::resource('due-collections', CON\DueCollectionController::class)->only('store');

    Route::post('party-ledger/filter', [CON\MaanPartyLedgerController::class, 'filter'])->name('party-ledger.filter');
    Route::post('party/filter', [CON\MaanPartyController::class, 'filter'])->name('party.filter');

    Route::resource('users', CON\MaanUserController::class);
    Route::post('user/filter', [CON\MaanUserController::class, 'maanFilter'])->name('user.filter');

    Route::resource('transactions', CON\MaanTransactionController::class)->only('index');
    Route::post('transaction/filter', [CON\MaanTransactionController::class, 'maanFilter'])->name('transaction.filter');
    Route::get('transaction/daily', [CON\MaanTransactionController::class, 'maanDailyTransaction'])->name('transaction.daily');

    Route::resource('productions', CON\MaanProductionController::class);
    Route::post('productions/filter', [CON\MaanProductionController::class, 'maanFilter'])->name('productions.filter');
    Route::get('productions/get/order', [CON\MaanProductionController::class, 'getOrder'])->name('productions.get-order');

    Route::resource('exports', CON\ExportController::class)->only('index');
    Route::resource('loss-profit', CON\LossProfitController::class)->only('index');
    Route::get('get-profit', [CON\LossProfitController::class, 'getLossProfit'])->name('loss-profit.data');
    Route::get('loss-profit/expense', [CON\LossProfitController::class, 'expense'])->name('loss-profit.expense');
    Route::get('loss-profit/expense/filter', [CON\LossProfitController::class, 'expenseFilter'])->name('loss-profit.expense.filter');
    Route::get('loss-profit/income', [CON\LossProfitController::class, 'income'])->name('loss-profit.income');
    Route::get('loss-profit/income/filter', [CON\LossProfitController::class, 'incomeFilter'])->name('loss-profit.income.filter');
    Route::get('loss-profit/income-csv', [CON\LossProfitController::class, 'incomeCsvPrint'])->name('loss-profit.income.csv');

    // Roles & Permissions
    Route::resource('roles', CON\RoleController::class)->except('show');
    Route::resource('permissions', CON\PermissionController::class)->only('index', 'store');

    // HRM
    Route::resource('salaries', CON\AcnooSalaryController::class)->except('show');
    Route::resource('employees', CON\AcnooEmployeeController::class)->except('show');
    Route::resource('designations', CON\AcnooDesignationController::class)->except('create', 'edit', 'show');

    // invoice
    Route::get('invoices/voucher/{id}', [CON\AcnooInvoiceController::class, 'voucherPrint'])->name('invoices.voucher');
    Route::get('invoices/partial-payment', [CON\AcnooInvoiceController::class, 'partialPayment'])->name('partial-payment.voucher');

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/order', [CON\MaanReportController::class, 'order'])->name('order');
        Route::post('/order/filter', [CON\MaanReportController::class, 'orderFilter'])->name('order.filter');
        Route::get('/productions', [CON\MaanReportController::class, 'production'])->name('production');
        Route::post('/productions/filter', [CON\MaanReportController::class, 'productionFilter'])->name('production.filter');
        Route::get('/collections', [CON\MaanReportController::class, 'collection'])->name('collections');
        Route::post('/collections/filter', [CON\MaanReportController::class, 'collectionFilter'])->name('collections.filter');
        Route::get('/payable-dues', [CON\MaanReportController::class, 'payableDues'])->name('payable-dues.index');
        Route::post('/payable-dues/filter', [CON\MaanReportController::class, 'payableDuesFilter'])->name('payable-dues.filter');
        Route::get('/transactions', [CON\MaanReportController::class, 'transaction'])->name('transactions');
        Route::post('/transactions/filter', [CON\MaanReportController::class, 'transactionFilter'])->name('transactions.filter');
        Route::get('/cashbooks', [CON\MaanReportController::class, 'cashbook'])->name('cashbooks');
        Route::post('/cashbooks/filter', [CON\MaanReportController::class, 'cashbookFilter'])->name('cashbooks.filter');

        // Buyers Due
        Route::get('/party-dues', [CON\MaanReportController::class, 'partyDues'])->name('party-dues.index');
        Route::post('/party-dues/filter', [CON\MaanReportController::class, 'partyDuesFilter'])->name('party-dues.filter');
    });

    //Notifications manager
    Route::prefix('notifications')->controller(CON\NotificationController::class)->name('notifications.')->group(function () {
        Route::get('/', 'mtIndex')->name('index');
        Route::post('/filter', 'maanFilter')->name('filter');
        Route::get('/{id}', 'mtView')->name('mtView');
        Route::get('view/all/', 'mtReadAll')->name('mtReadAll');
        Route::get('send/request', 'sendRequest')->name('send-request'); // send permission request.
        Route::get('accept/permissions/{id}', 'acceptPermissions')->name('accept-permissions'); // send permission request.
    });

    Route::resource('currencies', CON\MaanCurrencyController::class)->except('show', 'destroy');
    Route::match(['get', 'post'], 'currencies/default/{id}', [CON\MaanCurrencyController::class, 'default'])->name('currencies.default');
    Route::post('currency/filter', [CON\MaanCurrencyController::class, 'maanFilter'])->name('currency.filter');

    // System Settings
    Route::resource('system-settings', CON\SystemSettingController::class)->only('index', 'store');

    Route::get('/publish/status/ajax', [CON\AjaxController::class, 'publishStatus']);

    // PDF DOWNLOAD
    Route::prefix('pdf')->group(function () {
        Route::get('/order', [CON\PdfGenerateController::class, 'orders'])->name('orders.pdf');
        Route::get('/bookings', [CON\PdfGenerateController::class, 'bookings'])->name('bookings.pdf');
        Route::get('/costings', [CON\PdfGenerateController::class, 'costings'])->name('costings.pdf');
        Route::get('/budgets', [CON\PdfGenerateController::class, 'budgets'])->name('budgets.pdf');
        Route::get('/samples', [CON\PdfGenerateController::class, 'samples'])->name('samples.pdf');
        Route::get('/productions', [CON\PdfGenerateController::class, 'productions'])->name('productions.pdf');
        Route::get('/shipments', [CON\PdfGenerateController::class, 'shipments'])->name('shipments.pdf');
        Route::get('/units', [CON\PdfGenerateController::class, 'units'])->name('units.pdf');
        Route::get('/accessories', [CON\PdfGenerateController::class, 'accessories'])->name('accessories.pdf');
        Route::get('/accessories-orders', [CON\PdfGenerateController::class, 'accessoriesOrders'])->name('accessories-orders.pdf');
        Route::get('/users', [CON\PdfGenerateController::class, 'users'])->name('users.pdf');
        Route::get('/banks', [CON\PdfGenerateController::class, 'banks'])->name('banks.pdf');
        Route::get('/cashes', [CON\PdfGenerateController::class, 'cashes'])->name('cashes.pdf');
        Route::get('/cheques', [CON\PdfGenerateController::class, 'cheques'])->name('cheques.pdf');
        Route::get('/parties', [CON\PdfGenerateController::class, 'parties'])->name('parties.pdf');
        Route::get('/incomes', [CON\PdfGenerateController::class, 'incomes'])->name('incomes.pdf');
        Route::get('/expenses', [CON\PdfGenerateController::class, 'expenses'])->name('expenses.pdf');
        Route::get('/credit-vouchers', [CON\PdfGenerateController::class, 'creditVouchers'])->name('credit-vouchers.pdf');
        Route::get('/debit-vouchers', [CON\PdfGenerateController::class, 'debitVouchers'])->name('debit-vouchers.pdf');
        Route::get('/monthly-transactions', [CON\PdfGenerateController::class, 'monthlyTransactions'])->name('monthly-transactions.pdf');
        Route::get('/party-ledgers', [CON\PdfGenerateController::class, 'partyLedgers'])->name('party-ledgers.pdf');
        Route::get('/daily-cashbooks', [CON\PdfGenerateController::class, 'dailyCashbooks'])->name('daily-cashbooks.pdf');
        Route::get('/party-dues', [CON\PdfGenerateController::class, 'partyDues'])->name('party-dues.pdf');
        Route::get('/loss-profits', [CON\PdfGenerateController::class, 'lossProfits'])->name('loss-profits.pdf');
        Route::get('/order-reports', [CON\PdfGenerateController::class, 'orderReports'])->name('order-reports.pdf');
        Route::get('/transaction-reports', [CON\PdfGenerateController::class, 'transactionReports'])->name('transaction-reports.pdf');
        Route::get('/production-reports', [CON\PdfGenerateController::class, 'productionReports'])->name('production-reports.pdf');
        Route::get('/payable-due-reports', [CON\PdfGenerateController::class, 'payableDueReports'])->name('payable-due-reports.pdf');
        Route::get('/due-collection-reports', [CON\PdfGenerateController::class, 'dueCollectionReports'])->name('due-collection-reports.pdf');
        Route::get('/designations', [CON\PdfGenerateController::class, 'designations'])->name('designations.pdf');
        Route::get('/employees', [CON\PdfGenerateController::class, 'employees'])->name('employees.pdf');
        Route::get('/salaries', [CON\PdfGenerateController::class, 'salaries'])->name('salaries.pdf');
        Route::get('/order-histories', [CON\PdfGenerateController::class, 'orderHistories'])->name('order-histories.pdf');
    });
});

Route::get('/cache-clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    return back()->with('message', __('Cache has been cleared.'));
});

Route::get('/reset-data', function () {
    Artisan::call('migrate:fresh --seed');
    return true;
});

require __DIR__ . '/auth.php';

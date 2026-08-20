<?php

use App\Models\Bank;
use App\Models\Cash;
use App\Models\User;
use App\Models\Option;
use App\Models\History;
use App\Models\Voucher;
use App\Models\Currency;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\File;
use App\Notifications\ErpNotification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;

function cache_remember(string $key, callable $callback, int $ttl = 1800): mixed {
    return cache()->remember($key, env('CACHE_LIFETIME', $ttl), $callback);
}

function get_option($key) {
    return cache_remember($key, function () use ($key) {
        return Option::where('key', $key)->first()->value ?? [];
    });
}

function company_balance() {
    $cash_balance = cash_balance();
    $bank_balance = Bank::sum('balance');
    return $cash_balance + $bank_balance;
}

function formatted_date(string $date = null, string $format = 'd M, Y') : ?string
{
    return !empty($date) ? Date::parse($date)->format($format) : null;
}

function currency_format($amount, $type = "icon", $decimals = 2, $currency = null)
{
    $amount = number_format($amount, $decimals);
    $currency = $currency ?? default_currency();

    if ($type == "icon" || $type == "symbol") {
        if ($currency->position == "right") {
            return $amount . $currency->symbol;
        } else {
            return $currency->symbol . $amount;
        }
    } else {
        if ($currency->position == "right") {
            return $amount . ' ' . $currency->code;
        } else {
            return $currency->code . ' ' . $amount;
        }
    }
}

function default_currency($key = null, Currency $currency = null): object|int|string
{
    $currency = $currency ?? cache_remember('default_currency', function () {
            $currency = Currency::whereIsDefault(1)->first();

            if (!$currency) {
                $currency = (object)['name' => 'US Dollar', 'code' => 'USD', 'rate' => 1, 'symbol' => '$', 'position' => 'left', 'status' => true, 'is_default' => true,];
            }

            return $currency;
        });

    return $key ? $currency->$key : $currency;
}

function currency_rate($key = 'BDT') // will change for codecanyon
{
    return cache_remember('currency_rate-'.$key, function () use ($key) {
        return Currency::where('code', $key)->first()->rate;
    });
}

function cash_balance() // will change for codecanyon
{
    $total = Cash::selectRaw('SUM(CASE WHEN type = "debit" THEN amount ELSE 0 END) as total_debit')
                ->selectRaw('SUM(CASE WHEN type = "credit" THEN amount ELSE 0 END) as total_credit')
                ->first();

    return $total->total_credit - $total->total_debit;
}

function check_permission($date, $permission, $author = false)
{
    $user = auth()->user();

    $hasUpdatePermission = $user->can($permission.'-update');
    $hasDeletePermission = $user->can($permission.'-delete');
    $hasListPermission = $user->can($permission.'-list');

    $isToday = now()->isSameDay($date);
    $isAuthor = auth()->id() == $author;

    $isAdmin = in_array($user->role, ['superadmin', 'admin']);

    return ($hasUpdatePermission || $hasDeletePermission && ($hasListPermission || $isAuthor)) && $isToday || $isAdmin;
}


function check_visibility($date, $permission, $user_id = null)
{
    if ((now()->format('Y-m-d') == $date->format('Y-m-d') && $user_id == auth()->id()) || auth()->user()->can($permission.'-list')) {
        return true;
    } else {
        return false;
    }
}

function createHistory($data, $table = 'orders', $action = 'update') {
    History::create([
        'row_id' => $data->id,
        'action' => $action,
        'table' => $table,
        'datas' => $data,
    ]);
}

function amountInWords($amount) {
    $formatter = new \NumberFormatter('en_US', \NumberFormatter::SPELLOUT);
    $words = $formatter->format($amount);
    return $words;
}

function sendNotification($id, $url, $message, $type = 'action', $user = null, $model = null, $multi_params = false) {
    $notify = [
        'id' => $id,
        'url' => $url,
        'user' => $user,
        'type' => $type,
        'model' => $model,
        'message' => $message,
        'multi_params' => $multi_params,
    ];

    $notify_user = User::where('role', 'superadmin')->first();
    Notification::send($notify_user, new ErpNotification($notify));
}

function createIncomeInvoice($order, $party, $income = null, $prev_income_id = null) {
    Voucher::updateOrCreate(['income_id' => $prev_income_id, 'type' => 'order_invoice'], // Conditions to identify the order
        [
            'is_profit' => 0,
            'date' => today(),
            'amount' => $order->lc,
            'user_id' => auth()->id(),
            'type' => 'order_invoice',
            'remarks' => $party->remarks,
            'party_id' => $order->party_id,
            'voucher_no' => $order->order_no,
            'income_id' => $income->id ?? null,
            'bill_amount' => $party->opening_balance_type == 'due_bill' ? $party->opening_balance : 0,
        ]
    );
}

function expenseInvoice($order, $party, $expenes = null, $prev_expense_id = null) {
    Voucher::updateOrCreate(['expense_id' => $prev_expense_id, 'type' => 'order_invoice'], // Conditions to identify the order
        [
            'is_profit' => 0,
            'user_id' => auth()->id(),
            'type' => 'order_invoice',
            'date' => today(),
            'amount' => $order->ttl_amount,
            'party_id' => $order->party_id,
            'voucher_no' => $order->invoice_no,
            'expense_id' => $expenes->id ?? null,
            'bill_amount' => $order->ttl_amount,
        ]
    );
}

function getMonths()
{
    return ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
}

// File extension
function getIconForFile($extension)
{
    $extension = strtolower($extension);

    $icons = [
        'pdf' => 'far fa-file-pdf',
        'doc' => 'far fa-file-word',
        'docx' => 'far fa-file-word',
        'xls' => 'far fa-file-excel',
        'xlsx' => 'far fa-file-excel',
        'ppt' => 'far fa-file-powerpoint',
        'pptx' => 'far fa-file-powerpoint',
        'jpg' => 'far fa-file-image',
        'jpeg' => 'far fa-file-image',
        'png' => 'far fa-file-image',
        'gif' => 'far fa-file-image',
        'txt' => 'far fa-file-alt',
        'csv' => 'far fa-file-alt',
        'html' => 'far fa-file-code',
        'css' => 'far fa-file-code',
        'js' => 'far fa-file-code',
        'zip' => 'far fa-file-archive',
        'rar' => 'far fa-file-archive',
        'tar' => 'far fa-file-archive',
        'mp3' => 'far fa-file-audio',
        'wav' => 'far fa-file-audio',
        'mp4' => 'far fa-file-video',
        'avi' => 'far fa-file-video',
        'mov' => 'far fa-file-video',
        'psd' => 'far fa-file-photoshop',
        'ai' => 'far fa-file-illustrator',
        'svg' => 'far fa-file-svg',
        'php' => 'fab fa-php',
        'sql' => 'far fa-database',
        'xml' => 'far fa-file-code',
        'json' => 'far fa-file-code',
        'yaml' => 'far fa-file-code',
        'ini' => 'far fa-file-alt',
        'md' => 'far fa-file-alt',
        'markdown' => 'far fa-file-alt',
        'config' => 'far fa-file-alt',
        'log' => 'far fa-file-alt',
        // default
        'default' => 'far fa-file',

    ];

    return $icons[$extension] ?? $icons['default'];
}

function restorePublicImages()
{
    if (!env('DEMO_MODE')) {
        return true;
    }

    Artisan::call('migrate:fresh --seed');

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');

    $sourcePath = public_path('demo_images');
    $destinationPath = public_path('uploads');

    if (File::exists($sourcePath)) {
        File::cleanDirectory($destinationPath);
        File::copyDirectory($sourcePath, $destinationPath);
    }
}

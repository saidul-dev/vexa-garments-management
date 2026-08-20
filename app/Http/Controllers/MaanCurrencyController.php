<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MaanCurrencyController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:currencies-create')->only('create', 'store');
        $this->middleware('permission:currencies-read')->only('index');
        $this->middleware('permission:currencies-update')->only('edit', 'update', 'default');
        $this->middleware('permission:currencies-delete')->only('destroy');
    }

    public function index()
    {
        $currencies = Currency::orderBy('is_default', 'desc')->orderBy('status', 'desc')->paginate(10);

        return view('pages.currencies.index',compact('currencies'));
    }


    public function maanFilter(Request $request)
    {
        $currencies = Currency::orderBy('is_default', 'desc')->orderBy('status', 'desc')
            ->when(request('search'), function($q) use($request) {
                $q->where(function($q) use($request) {
                    $q->where('name', 'like', '%'.$request->search.'%')
                        ->orWhere('code', 'like', '%'.$request->search.'%')
                        ->orWhere('symbol', 'like', '%'.$request->search.'%');
                });
            })
            ->latest()
            ->paginate($request->per_page ?? 10);

        if($request->ajax()){
            return response()->json([
                'data' => view('pages.currencies.datas',compact('currencies'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    public function create()
    {

        return view('pages.currencies.create');

    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:currencies',
            'code' => 'required|string|unique:currencies',
            'rate' => 'nullable|numeric',
            'symbol' => 'nullable|string',
            'position' => 'nullable|string',
            'status' => 'required|integer',
            'is_default' => 'nullable|boolean',
        ]);

        Currency::create($request->all());

        return response()->json([
            'message'   => __('Currency updated successfully'),
            'redirect'  => route('currencies.index')
        ]);
    }


    public function edit(Currency $currency)
    {
        return view('pages.currencies.edit',compact('currency'));
    }

    public function update(Request $request, Currency $currency)
    {
        $request->validate([
            'name' => 'required|string|unique:currencies,name,'.$currency->id,
            'code' => 'required|string|unique:currencies,code,'.$currency->id,
            'rate' => 'nullable|numeric',
            'symbol' => 'nullable|string',
            'position' => 'nullable|string',
            'status' => 'required|integer',
            'is_default' => 'nullable|boolean',
        ]);

        $currency->update($request->all());

        return response()->json([
            'message'   => __('Currency updated successfully'),
            'redirect'  => route('currencies.index')
        ]);
    }

    public function default($id)
    {
        $currency = Currency::find($id);

        if ($currency) {
            Currency::where('id', '!=', $id)->update(['is_default' => 0]);
            $currency->update(['is_default' => 1]);
        }

        Cache::forget('default_currency');

        return redirect()->route('currencies.index')->with('message', __('Default currency activated successfully'));

    }
}

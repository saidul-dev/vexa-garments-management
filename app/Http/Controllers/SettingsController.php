<?php

namespace App\Http\Controllers;

use App\Helpers\HasUploader;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    use HasUploader;

    public function __construct()
    {
        $this->middleware('permission:settings-read')->only('index');
        $this->middleware('permission:settings-update')->only('update');
    }

    public function index()
    {
        $company = Option::where('key','company')->first();
        return view ('pages.setting.company',compact('company'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email',
            'remarks' => 'nullable|string|max:1000',
            'address' => 'required|string|max:100',
            'website' => 'nullable|url',
            'logo' => 'nullable|image',
            'favicon' => 'nullable|image',
        ]);

        $company = Option::findOrFail($id);
        Cache::forget($company->key);
        $company->update([
            'value' => $request->except('_token','method_','logo','favicon') + [
                'logo' => $request->logo ? $this->upload($request, 'logo', $company->logo) : $company->value['logo'],
                'favicon' => $request->favicon ? $this->upload($request, 'favicon', $company->favicon) : $company->value['favicon']
            ]
        ]);

        return response()->json([
            'message'   => __('Company updated successfully'),
            'redirect'  => route('settings.index')
        ]);
    }
}

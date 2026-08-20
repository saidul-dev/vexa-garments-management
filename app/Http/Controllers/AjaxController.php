<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Accessory;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Unit;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function publishStatus(Request $request)
    {
        if ($request->ajax()){
            if ($request->statustext=='Income'){
                Income::where('id',$request->id)->update(['status'=>$request->status]);
            }
            if ($request->statustext=='Expense'){
                Expense::where('id',$request->id)->update(['status'=>$request->status]);
            }
            if ($request->statustext=='Accessories'){
                Accessory::where('id',$request->id)->update(['status'=>$request->status]);
            }
            if ($request->statustext=='Units'){
                Unit::where('id',$request->id)->update(['status'=>$request->status]);
            }
            return $request;
        }
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function mtIndex()
    {
        $notifications = auth()->user()->notifications()
            ->whereDate('created_at', today())
            ->latest()
            ->get();
            
        return view('pages.notifications.index',compact('notifications'));
    }

    public function maanFilter(Request $request)
    {
        $notifications = Notification::when(request('days') == 'daily', function ($q) {
            $q->whereDate('created_at', now()->format('Y-m-d'));
        })
            ->when(request('days') == 'weekly', function ($q) {
                $q->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d'), now()->endOfWeek()->format('Y-m-d')] );
            })
            ->when(request('days') == '15_days', function ($q) {
                $q->whereDate('created_at', '>=', now()->subDays(15)->format('Y-m-d'));
            })
            ->when(request('days') == 'monthly', function ($q) {
                $q->whereMonth('created_at', now()->format('m'));
            })
            ->when(request('days') == 'yearly', function ($q) {
                $q->whereYear('created_at', now()->format('Y'));
            })

            ->latest()
            ->get();
        return response()->json([
            'data' => view('pages.notifications.datas', compact('notifications'))->render()
        ]);
    }


    public function mtView($id)
    {
        $notify = Notification::find($id);
        if ($notify) {
            $notify->read_at = now();
            $notify->save();
            return redirect($notify->data['url'] ?? '/');
        }

        return back()->with('error', __('Premission denied.'));
    }

    public function mtReadAll()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        return back();
    }

    public function sendRequest()
    {
        sendNotification(request('id'), null, __('New '.request('model').' permission request'), 'permissions', auth()->user()->name, request('model'));

        return response()->json([
            'message' => __('Premission request submitted'),
            'redirect' => redirect()->back()->getTargetUrl()
        ]);
    }

    public function acceptPermissions($id)
    {
        $notify = Notification::find($id);
        $notify->read_at = now();
        $notify->save();
        $model = $notify->data['model'] ?? null;
        if ($model) {
            $test = 'App\Models\\'.$model;
            "App\Models\"".$test::where('id', $notify->data['id'] ?? '')->update([
                'created_at' => now()
            ]);

            return response()->json([
                'message' => __('Premission given successfully.'),
                'redirect' => redirect()->back()->getTargetUrl()
            ]);
        }

        return response()->json(__('Premission denied.'), 403);
    }
}

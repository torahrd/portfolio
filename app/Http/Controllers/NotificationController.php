<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    // フォロー申請の承認
    public function accept(Request $request, DatabaseNotification $notification)
    {
        $data = $notification->data;
        $fromUserId = $data['from_user_id'] ?? null;
        $toUserId = Auth::id();
        if ($fromUserId && $toUserId) {
            // followersテーブルのpendingをacceptedに
            DB::table('followers')
                ->where('followed_id', $toUserId)
                ->where('following_id', $fromUserId)
                ->where('status', 'pending')
                ->update(['status' => 'active']);
        }
        $notification->delete();

        return redirect()->back();
    }

    // フォロー申請の拒否
    public function reject(Request $request, DatabaseNotification $notification)
    {
        $data = $notification->data;
        $fromUserId = $data['from_user_id'] ?? null;
        $toUserId = Auth::id();
        if ($fromUserId && $toUserId) {
            // followersテーブルのpendingを削除
            DB::table('followers')
                ->where('followed_id', $toUserId)
                ->where('following_id', $fromUserId)
                ->where('status', 'pending')
                ->delete();
        }
        $notification->delete();

        return redirect()->back();
    }
}

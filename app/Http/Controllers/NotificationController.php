<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use Illuminate\Http\Request;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type');
        $query = AdminNotification::query();

        if ($type) {
            $query->where('type', $type);
        }

        $notifications = $query->paginate(5)->appends(['type' => $type]);

        return view('admin.notifications', [
            'notifications' => $notifications,
            'currentType' => $type
        ]);
    }

    public function markAsRead($id)
    {
        $notification = UserNotification::findOrFail($id);
        $notification->update(['status' => 'read']);

        return redirect()->route('profile.notifications');
    }

    public function markAsRead2($id)
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->update(['status' => 'read']);

        return redirect()->route('admin.notifications');
    }

    public function destroy(UserNotification $notification)
    {
        $notification->delete();

        return redirect(route('profile.notifications'))->with('status', 'Notification deleted!');
    }

    public function destroy2(AdminNotification $notification)
    {
        $notification->delete();

        return redirect(route('admin.notifications'))->with('status', 'Notification deleted!');
    }

    public function user(Request $request)
    {
        $type = $request->input('type');
        $query = UserNotification::where('user_id', Auth::id());

        if ($type) {
            $query->where('type', $type);
        }

        $notifications = $query->paginate(5)->appends(['type' => $type]);

        return view('profile.notifications', [
            'notifications' => $notifications,
            'currentType' => $type,
        ]);
    }
}

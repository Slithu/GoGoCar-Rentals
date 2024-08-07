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

        $notifications = $query->paginate(6)->appends($request->all());

        return view('admin.notifications', [
            'notifications' => $notifications,
            'currentType' => $type
        ]);
    }

    public function markAsRead2(Request $request, $id)
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->update(['status' => 'read']);
        $type = $request->input('type');
        $page = $request->input('page', 1);

        return redirect()->route('admin.notifications', ['type' => $type, 'page' => $page]);
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = UserNotification::findOrFail($id);
        $notification->update(['status' => 'read']);
        $type = $request->input('type');
        $page = $request->input('page', 1);

        return redirect()->route('profile.notifications', ['type' => $type, 'page' => $page]);
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

        $notifications = $query->paginate(6)->appends($request->all());

        return view('profile.notifications', [
            'notifications' => $notifications,
            'currentType' => $type,
        ]);
    }
}

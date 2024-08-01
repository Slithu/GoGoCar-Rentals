<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = User::where('role', 'user');

        if ($search) {
            $searchTerms = explode(' ', $search);

            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->where(function ($query) use ($term) {
                        $query->where('name', 'like', "%{$term}%")
                            ->orWhere('surname', 'like', "%{$term}%")
                            ->orWhere('email', 'like', "%{$term}%");
                    });
                }
            });
        }

        $users = $query->paginate(9);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("profile.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user) : View
    {
        return view('users.edit', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUserRequest $request, User $user): RedirectResponse
    {
        $user->fill($request->validated());
        $user->save();

        if ($user->role === UserRole::ADMIN) {
            return redirect()->route('users.index')->with('status', 'User Profile updated!');
        }
        return redirect()->route('profile.show')->with('status', 'User Profile updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect('users/list')->with('status', 'User deleted!');
    }

    public function profile()
    {
        $users = User::where('id', Auth::id())->get();
        return view('profile.show', compact('users'));
    }

    public function addProfilePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if (!$user instanceof User) {
            return redirect()->back()->with('error', 'User not found or authenticated.');
        }

        if ($user->image_path) {
            Storage::disk('public')->delete($user->image_path);
        }

        $user->image_path = Storage::disk('public')->put('users', $request->file('image'));
        $user->save();

        return redirect()->route('profile.show')->with('status', 'Profile photo updated!');
    }

}

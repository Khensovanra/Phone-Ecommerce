<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): View
    {
        $query = User::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'name',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'email',
                    'like',
                    "%{$search}%"
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Role Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('role')) {
            $query->where(
                'role',
                $request->role
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        if ($request->get('sort') === 'oldest') {

            $query->oldest();

        } elseif ($request->get('sort') === 'name') {

            $query->orderBy('name');

        } elseif ($request->get('sort') === 'admins') {

            $query
                ->orderByRaw(
                    "CASE WHEN role = 'admin' THEN 0 ELSE 1 END"
                )
                ->orderBy('name');

        } else {

            $query->latest();

        }


        $users = $query
            ->paginate(12)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $totalUsers = User::count();

        $totalAdmins = User::where(
            'role',
            'admin'
        )->count();

        $totalCustomers = User::where(
            'role',
            'customer'
        )->count();


        return view(
            'admin.users.index',
            compact(
                'users',
                'totalUsers',
                'totalAdmins',
                'totalCustomers'
            )
        );
    }


    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }


    /**
     * Store a newly created user.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'role' => [
                'required',
                Rule::in([
                    'admin',
                    'customer',
                ]),
            ],
        ]);


        User::create([
            'name' => $validated['name'],

            'email' => $validated['email'],

            'password' => Hash::make(
                $validated['password']
            ),

            'role' => $validated['role'],
        ]);


        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'User created successfully.'
            );
    }


    /**
     * Display the specified user.
     */
    public function show(User $user): View
    {
        $user->load([
            'orders' => function ($query) {
                $query->latest();
            },
        ]);

        return view(
            'admin.users.show',
            compact('user')
        );
    }


    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        return view(
            'admin.users.edit',
            compact('user')
        );
    }


    /**
     * Update the specified user.
     */
    public function update(
        Request $request,
        User $user
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            'role' => [
                'required',
                Rule::in([
                    'admin',
                    'customer',
                ]),
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Prevent Admin From Removing Their Own Admin Role
        |--------------------------------------------------------------------------
        */

        if (
            $user->id === auth()->id() &&
            $validated['role'] !== 'admin'
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'You cannot remove your own admin role.'
                );
        }


        $user->name = $validated['name'];

        $user->email = $validated['email'];

        $user->role = $validated['role'];


        /*
        |--------------------------------------------------------------------------
        | Update Password Only If Provided
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['password'])) {

            $user->password = Hash::make(
                $validated['password']
            );

        }


        $user->save();


        return redirect()
            ->route(
                'admin.users.index'
            )
            ->with(
                'success',
                'User updated successfully.'
            );
    }


    /**
     * Remove the specified user.
     */
    public function destroy(
        User $user
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Prevent Self Deletion
        |--------------------------------------------------------------------------
        */

        if ($user->id === auth()->id()) {

            return back()->with(
                'error',
                'You cannot delete your own account.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Deleting Admin With Orders
        |--------------------------------------------------------------------------
        */

        if (
            $user->role === 'admin' &&
            $user->orders()->exists()
        ) {
            return back()->with(
                'error',
                'This admin account cannot be deleted because it has order history.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Delete User
        |--------------------------------------------------------------------------
        */

        $user->delete();


        return redirect()
            ->route(
                'admin.users.index'
            )
            ->with(
                'success',
                'User deleted successfully.'
            );
    }
}
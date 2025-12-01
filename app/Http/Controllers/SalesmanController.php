<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SalesmanController extends Controller
{
    /**
     * Display a listing of salesmen.
     */
    public function index()
    {
        $salesmanRole = Role::where('name', 'salesman')->first();
        
        if ($salesmanRole) {
            $salesmen = User::role('salesman')->orderBy('created_at', 'desc')->get();
        } else {
            $salesmen = collect();
        }
        
        return view('dashboard.salesmen.index', compact('salesmen'));
    }

    /**
     * Show the form for creating a new salesman.
     */
    public function create()
    {
        $user = null;
        return view('dashboard.salesmen.form', compact('user'));
    }

    /**
     * Store a newly created salesman.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'location' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'location' => $validated['location'] ?? null,
        ]);

        // Assign salesman role
        $salesmanRole = Role::firstOrCreate(['name' => 'salesman', 'guard_name' => 'web']);
        $user->assignRole('salesman');

        return redirect()->route('salesmen.index')
            ->with('success', 'Salesman created successfully.');
    }

    /**
     * Show the form for editing the specified salesman.
     */
    public function edit(User $salesman)
    {
        // Verify user has salesman role
        if (!$salesman->hasRole('salesman')) {
            abort(404);
        }

        $user = $salesman;
        return view('dashboard.salesmen.form', compact('user'));
    }

    /**
     * Update the specified salesman.
     */
    public function update(Request $request, User $salesman)
    {
        // Verify user has salesman role
        if (!$salesman->hasRole('salesman')) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $salesman->id,
            'password' => 'nullable|string|min:8|confirmed',
            'location' => 'nullable|string|max:255',
        ]);

        $salesman->name = $validated['name'];
        $salesman->email = $validated['email'];
        $salesman->location = $validated['location'] ?? null;

        if (!empty($validated['password'])) {
            $salesman->password = Hash::make($validated['password']);
        }

        $salesman->save();

        return redirect()->route('salesmen.index')
            ->with('success', 'Salesman updated successfully.');
    }

    /**
     * Remove the specified salesman.
     */
    public function destroy(User $salesman)
    {
        // Verify user has salesman role
        if (!$salesman->hasRole('salesman')) {
            abort(404);
        }

        $salesman->delete();

        return redirect()->route('salesmen.index')
            ->with('success', 'Salesman deleted successfully.');
    }
}

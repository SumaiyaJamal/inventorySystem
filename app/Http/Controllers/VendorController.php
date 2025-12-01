<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class VendorController extends Controller
{
    /**
     * Display a listing of vendors.
     */
    public function index()
    {
        $vendorRole = Role::where('name', 'vendor')->first();
        
        if ($vendorRole) {
            $vendors = User::role('vendor')->orderBy('created_at', 'desc')->get();
        } else {
            $vendors = collect();
        }
        
        return view('dashboard.vendors.index', compact('vendors'));
    }

    /**
     * Show the form for creating a new vendor.
     */
    public function create()
    {
        $user = null;
        return view('dashboard.vendors.form', compact('user'));
    }

    /**
     * Store a newly created vendor.
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

        // Assign vendor role
        $vendorRole = Role::firstOrCreate(['name' => 'vendor', 'guard_name' => 'web']);
        $user->assignRole('vendor');

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor created successfully.');
    }

    /**
     * Show the form for editing the specified vendor.
     */
    public function edit(User $vendor)
    {
        // Verify user has vendor role
        if (!$vendor->hasRole('vendor')) {
            abort(404);
        }

        $user = $vendor;
        return view('dashboard.vendors.form', compact('user'));
    }

    /**
     * Update the specified vendor.
     */
    public function update(Request $request, User $vendor)
    {
        // Verify user has vendor role
        if (!$vendor->hasRole('vendor')) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $vendor->id,
            'password' => 'nullable|string|min:8|confirmed',
            'location' => 'nullable|string|max:255',
        ]);

        $vendor->name = $validated['name'];
        $vendor->email = $validated['email'];
        $vendor->location = $validated['location'] ?? null;

        if (!empty($validated['password'])) {
            $vendor->password = Hash::make($validated['password']);
        }

        $vendor->save();

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor updated successfully.');
    }

    /**
     * Remove the specified vendor.
     */
    public function destroy(User $vendor)
    {
        // Verify user has vendor role
        if (!$vendor->hasRole('vendor')) {
            abort(404);
        }

        $vendor->delete();

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor deleted successfully.');
    }
}


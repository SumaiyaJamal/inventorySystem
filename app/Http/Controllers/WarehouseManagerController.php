<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class WarehouseManagerController extends Controller
{
    /**
     * Display a listing of warehouse managers.
     */
    public function index()
    {
        $warehouseManagerRole = Role::where('name', 'warehouse_manager')->first();
        
        if ($warehouseManagerRole) {
            $warehouseManagers = User::role('warehouse_manager')->orderBy('created_at', 'desc')->get();
        } else {
            $warehouseManagers = collect();
        }
        
        return view('dashboard.warehouse-managers.index', compact('warehouseManagers'));
    }

    /**
     * Show the form for creating a new warehouse manager.
     */
    public function create()
    {
        $user = null;
        return view('dashboard.warehouse-managers.form', compact('user'));
    }

    /**
     * Store a newly created warehouse manager.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'location' => 'nullable|string|max:255',
            'inventory_type' => 'required|in:spices,led_light',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'location' => $validated['location'] ?? null,
            'inventory_type' => $validated['inventory_type'],
        ]);

        // Assign warehouse_manager role
        $warehouseManagerRole = Role::firstOrCreate(['name' => 'warehouse_manager', 'guard_name' => 'web']);
        $user->assignRole('warehouse_manager');

        return redirect()->route('warehouse-managers.index')
            ->with('success', 'Warehouse Manager created successfully.');
    }

    /**
     * Show the form for editing the specified warehouse manager.
     */
    public function edit(User $warehouseManager)
    {
        // Verify user has warehouse_manager role
        if (!$warehouseManager->hasRole('warehouse_manager')) {
            abort(404);
        }

        $user = $warehouseManager;
        return view('dashboard.warehouse-managers.form', compact('user'));
    }

    /**
     * Update the specified warehouse manager.
     */
    public function update(Request $request, User $warehouseManager)
    {
        // Verify user has warehouse_manager role
        if (!$warehouseManager->hasRole('warehouse_manager')) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $warehouseManager->id,
            'password' => 'nullable|string|min:8|confirmed',
            'location' => 'nullable|string|max:255',
            'inventory_type' => 'required|in:spices,led_light',
        ]);

        $warehouseManager->name = $validated['name'];
        $warehouseManager->email = $validated['email'];
        $warehouseManager->location = $validated['location'] ?? null;
        $warehouseManager->inventory_type = $validated['inventory_type'];

        if (!empty($validated['password'])) {
            $warehouseManager->password = Hash::make($validated['password']);
        }

        $warehouseManager->save();

        return redirect()->route('warehouse-managers.index')
            ->with('success', 'Warehouse Manager updated successfully.');
    }

    /**
     * Remove the specified warehouse manager.
     */
    public function destroy(User $warehouseManager)
    {
        // Verify user has warehouse_manager role
        if (!$warehouseManager->hasRole('warehouse_manager')) {
            abort(404);
        }

        $warehouseManager->delete();

        return redirect()->route('warehouse-managers.index')
            ->with('success', 'Warehouse Manager deleted successfully.');
    }
}

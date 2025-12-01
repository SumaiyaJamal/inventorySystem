<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InventoryController extends Controller
{
    /**
     * Display LED Lights & Bulbs inventory listing
     */
    public function ledLights()
    {
        $user = auth()->user();

        // Check if warehouse manager and restrict access
        if ($user && $user->hasRole('warehouse_manager')) {
            if (!isset($user->inventory_type) || $user->inventory_type !== 'led_light') {
                abort(403, 'You do not have access to this inventory type.');
            }
        }

        $inventories = Inventory::where('type', 'led_light')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.inventory.led-lights', compact('inventories'));
    }

    /**
     * Display Spices inventory listing
     */
    public function spices()
    {
        $user = auth()->user();

        // Check if warehouse manager and restrict access
        if ($user && $user->hasRole('warehouse_manager')) {
            if (!isset($user->inventory_type) || $user->inventory_type !== 'spices') {
                abort(403, 'You do not have access to this inventory type.');
            }
        }

        $inventories = Inventory::where('type', 'spices')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.inventory.spices', compact('inventories'));
    }

    /**
     * Show the form for creating a new LED Light inventory item.
     */
    public function createLedLight()
    {
        $user = auth()->user();

        // Check if warehouse manager and restrict access
        if ($user->hasRole('warehouse_manager')) {
            if ($user->inventory_type !== 'led_light') {
                abort(403, 'You do not have access to this inventory type.');
            }
        }

        $inventory = null;
        return view('dashboard.inventory.led-light-form', compact('inventory'));
    }

    /**
     * Store a newly created LED Light inventory item.
     */
    public function storeLedLight(Request $request)
    {
        $user = auth()->user();

        // Check if warehouse manager and restrict access
        if ($user->hasRole('warehouse_manager')) {
            if ($user->inventory_type !== 'led_light') {
                abort(403, 'You do not have access to this inventory type.');
            }
        }

        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'wattage' => 'nullable|string|max:50',
            'light_type' => 'nullable|string|max:50',
            'color_temperature' => 'nullable|string|max:50',
            'quantity' => 'required|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'supplier_name' => 'required|string|max:255',
            'purchase_date' => 'nullable|date',
            'warranty_months' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['type'] = 'led_light';

        Inventory::create($validated);

        return redirect()->route('inventory.led-lights')
            ->with('success', 'LED Light inventory item created successfully.');
    }

    /**
     * Show the form for editing the specified LED Light inventory item.
     */
    public function editLedLight(Inventory $inventory)
    {
        if ($inventory->type !== 'led_light') {
            abort(404);
        }

        $user = auth()->user();
        if ($user->hasRole('warehouse_manager') && $user->inventory_type !== 'led_light') {
            abort(403, 'You do not have access to this inventory type.');
        }

        return view('dashboard.inventory.led-light-form', compact('inventory'));
    }

    /**
     * Update the specified LED Light inventory item.
     */
    public function updateLedLight(Request $request, Inventory $inventory)
    {
        if ($inventory->type !== 'led_light') {
            abort(404);
        }

        $user = auth()->user();
        if ($user->hasRole('warehouse_manager') && $user->inventory_type !== 'led_light') {
            abort(403, 'You do not have access to this inventory type.');
        }

        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'wattage' => 'nullable|string|max:50',
            'light_type' => 'nullable|string|max:50',
            'color_temperature' => 'nullable|string|max:50',
            'quantity' => 'required|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'supplier_name' => 'required|string|max:255',
            'purchase_date' => 'nullable|date',
            'warranty_months' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $inventory->update($validated);

        return redirect()->route('inventory.led-lights')
            ->with('success', 'LED Light inventory item updated successfully.');
    }

    /**
     * Remove the specified LED Light inventory item.
     */
    public function destroyLedLight(Inventory $inventory)
    {
        if ($inventory->type !== 'led_light') {
            abort(404);
        }

        $user = auth()->user();
        if ($user->hasRole('warehouse_manager') && $user->inventory_type !== 'led_light') {
            abort(403, 'You do not have access to this inventory type.');
        }

        $inventory->delete();

        return redirect()->route('inventory.led-lights')
            ->with('success', 'LED Light inventory item deleted successfully.');
    }

    /**
     * Show the form for creating a new Spice inventory item.
     */
    public function createSpice()
    {
        $user = auth()->user();

        // Check if warehouse manager and restrict access
        if ($user->hasRole('warehouse_manager')) {
            if ($user->inventory_type !== 'spices') {
                abort(403, 'You do not have access to this inventory type.');
            }
        }

        $inventory = null;
        return view('dashboard.inventory.spice-form', compact('inventory'));
    }

    /**
     * Store a newly created Spice inventory item.
     */
    public function storeSpice(Request $request)
    {
        $user = auth()->user();

        // Check if warehouse manager and restrict access
        if ($user->hasRole('warehouse_manager')) {
            if ($user->inventory_type !== 'spices') {
                abort(403, 'You do not have access to this inventory type.');
            }
        }

        $validated = $request->validate([
            'spice_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:50',
            'weight' => 'nullable|string|max:50',
            'quantity' => 'required|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'supplier_name' => 'required|string|max:255',
            'manufactured_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:manufactured_date',
            'storage_instructions' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['type'] = 'spices';

        Inventory::create($validated);

        return redirect()->route('inventory.spices')
            ->with('success', 'Spice inventory item created successfully.');
    }

    /**
     * Show the form for editing the specified Spice inventory item.
     */
    public function editSpice(Inventory $inventory)
    {
        if ($inventory->type !== 'spices') {
            abort(404);
        }

        $user = auth()->user();
        if ($user->hasRole('warehouse_manager') && $user->inventory_type !== 'spices') {
            abort(403, 'You do not have access to this inventory type.');
        }

        return view('dashboard.inventory.spice-form', compact('inventory'));
    }

    /**
     * Update the specified Spice inventory item.
     */
    public function updateSpice(Request $request, Inventory $inventory)
    {
        if ($inventory->type !== 'spices') {
            abort(404);
        }

        $user = auth()->user();
        if ($user->hasRole('warehouse_manager') && $user->inventory_type !== 'spices') {
            abort(403, 'You do not have access to this inventory type.');
        }

        $validated = $request->validate([
            'spice_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:50',
            'weight' => 'nullable|string|max:50',
            'quantity' => 'required|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'supplier_name' => 'required|string|max:255',
            'manufactured_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:manufactured_date',
            'storage_instructions' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $inventory->update($validated);

        return redirect()->route('inventory.spices')
            ->with('success', 'Spice inventory item updated successfully.');
    }

    /**
     * Remove the specified Spice inventory item.
     */
    public function destroySpice(Inventory $inventory)
    {
        if ($inventory->type !== 'spices') {
            abort(404);
        }

        $user = auth()->user();
        if ($user->hasRole('warehouse_manager') && $user->inventory_type !== 'spices') {
            abort(403, 'You do not have access to this inventory type.');
        }

        $inventory->delete();

        return redirect()->route('inventory.spices')
            ->with('success', 'Spice inventory item deleted successfully.');
    }
}


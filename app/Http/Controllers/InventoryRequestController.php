<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryRequest;
use App\Models\InventoryRequestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryRequestController extends Controller
{
    /**
     * Display a listing of inventory requests for salesmen
     */
    public function index()
    {
        $user = auth()->user();
        
        // Only salesmen can see their own requests
        if (!$user->hasRole('salesman')) {
            abort(403, 'Access denied.');
        }

        $requests = InventoryRequest::where('user_id', $user->id)
            ->with(['items.inventory', 'approvedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.inventory-requests.index', compact('requests'));
    }

    /**
     * Show all requests for warehouse managers
     */
    public function allRequests()
    {
        $user = auth()->user();
        
        // Only warehouse managers and admins can see all requests
        if (!$user->hasRole('warehouse_manager') && !$user->hasRole('admin')) {
            abort(403, 'Access denied.');
        }

        $requests = InventoryRequest::with(['user', 'items.inventory', 'approvedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.inventory-requests.all-requests', compact('requests'));
    }

    /**
     * Show the form for creating a new inventory request
     */
    public function create()
    {
        $user = auth()->user();
        
        // Only salesmen can create requests
        if (!$user->hasRole('salesman')) {
            abort(403, 'Access denied.');
        }

        // Get all available inventories
        $inventories = Inventory::where('quantity', '>', 0)
            ->orderBy('type')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.inventory-requests.form', compact('inventories'));
    }

    /**
     * Store a newly created inventory request
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Only salesmen can create requests
        if (!$user->hasRole('salesman')) {
            abort(403, 'Access denied.');
        }

        $validated = $request->validate([
            'inventory_items' => 'required|array|min:1',
            'inventory_items.*.inventory_id' => 'required|exists:inventories,id',
            'inventory_items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // Create the request
            $inventoryRequest = InventoryRequest::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'notes' => $request->notes ?? null,
            ]);

            // Create request items
            foreach ($validated['inventory_items'] as $item) {
                $inventory = Inventory::findOrFail($item['inventory_id']);
                
                // Validate quantity doesn't exceed available
                if ($item['quantity'] > $inventory->quantity) {
                    DB::rollBack();
                    $itemName = $inventory->product_name ?? $inventory->spice_name;
                    return back()->withErrors(['inventory_items' => "Requested quantity for {$itemName} exceeds available quantity ({$inventory->quantity})."])->withInput();
                }

                InventoryRequestItem::create([
                    'inventory_request_id' => $inventoryRequest->id,
                    'inventory_id' => $item['inventory_id'],
                    'requested_quantity' => $item['quantity'],
                ]);
            }

            DB::commit();

            return redirect()->route('inventory-requests.index')
                ->with('success', 'Inventory request created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to create inventory request.'])->withInput();
        }
    }

    /**
     * Display the specified inventory request
     */
    public function show(InventoryRequest $inventoryRequest)
    {
        $user = auth()->user();
        
        // Salesmen can only see their own requests
        if ($user->hasRole('salesman') && $inventoryRequest->user_id !== $user->id) {
            abort(403, 'Access denied.');
        }

        $inventoryRequest->load(['user', 'items.inventory', 'approvedBy']);

        return view('dashboard.inventory-requests.show', compact('inventoryRequest'));
    }

    /**
     * Show the form for editing the specified inventory request
     */
    public function edit(InventoryRequest $inventoryRequest)
    {
        $user = auth()->user();
        
        // Only salesmen can edit their own pending requests
        if (!$user->hasRole('salesman') || $inventoryRequest->user_id !== $user->id) {
            abort(403, 'Access denied.');
        }

        if ($inventoryRequest->status !== 'pending') {
            return redirect()->route('inventory-requests.index')
                ->with('error', 'You can only edit pending requests.');
        }

        $inventories = Inventory::where('quantity', '>', 0)
            ->orderBy('type')
            ->orderBy('created_at', 'desc')
            ->get();

        $inventoryRequest->load('items');

        return view('dashboard.inventory-requests.form', compact('inventoryRequest', 'inventories'));
    }

    /**
     * Update the specified inventory request
     */
    public function update(Request $request, InventoryRequest $inventoryRequest)
    {
        $user = auth()->user();
        
        // Only salesmen can update their own pending requests
        if (!$user->hasRole('salesman') || $inventoryRequest->user_id !== $user->id) {
            abort(403, 'Access denied.');
        }

        if ($inventoryRequest->status !== 'pending') {
            return redirect()->route('inventory-requests.index')
                ->with('error', 'You can only update pending requests.');
        }

        $validated = $request->validate([
            'inventory_items' => 'required|array|min:1',
            'inventory_items.*.inventory_id' => 'required|exists:inventories,id',
            'inventory_items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // Update request notes
            $inventoryRequest->notes = $request->notes ?? null;
            $inventoryRequest->save();

            // Delete existing items
            $inventoryRequest->items()->delete();

            // Create new request items
            foreach ($validated['inventory_items'] as $item) {
                $inventory = Inventory::findOrFail($item['inventory_id']);
                
                // Validate quantity doesn't exceed available
                if ($item['quantity'] > $inventory->quantity) {
                    DB::rollBack();
                    $itemName = $inventory->product_name ?? $inventory->spice_name;
                    return back()->withErrors(['inventory_items' => "Requested quantity for {$itemName} exceeds available quantity ({$inventory->quantity})."])->withInput();
                }

                InventoryRequestItem::create([
                    'inventory_request_id' => $inventoryRequest->id,
                    'inventory_id' => $item['inventory_id'],
                    'requested_quantity' => $item['quantity'],
                ]);
            }

            DB::commit();

            return redirect()->route('inventory-requests.index')
                ->with('success', 'Inventory request updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update inventory request.'])->withInput();
        }
    }

    /**
     * Remove the specified inventory request
     */
    public function destroy(InventoryRequest $inventoryRequest)
    {
        $user = auth()->user();
        
        // Only salesmen can delete their own pending requests
        if (!$user->hasRole('salesman') || $inventoryRequest->user_id !== $user->id) {
            abort(403, 'Access denied.');
        }

        if ($inventoryRequest->status !== 'pending') {
            return redirect()->route('inventory-requests.index')
                ->with('error', 'You can only delete pending requests.');
        }

        $inventoryRequest->delete();

        return redirect()->route('inventory-requests.index')
            ->with('success', 'Inventory request deleted successfully.');
    }

    /**
     * Approve an inventory request (warehouse manager only)
     */
    public function approve(InventoryRequest $inventoryRequest)
    {
        $user = auth()->user();
        
        // Only warehouse managers and admins can approve requests
        if (!$user->hasRole('warehouse_manager') && !$user->hasRole('admin')) {
            abort(403, 'Access denied.');
        }

        // Check if warehouse manager has access to this inventory type
        if ($user->hasRole('warehouse_manager')) {
            $inventoryRequest->load('items.inventory');
            $hasAccess = false;
            foreach ($inventoryRequest->items as $item) {
                if ($user->inventory_type === $item->inventory->type) {
                    $hasAccess = true;
                    break;
                }
            }
            if (!$hasAccess) {
                abort(403, 'You do not have access to this inventory type.');
            }
        }

        if ($inventoryRequest->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        DB::beginTransaction();
        try {
            $inventoryRequest->load('items.inventory');

            // Check if all items have sufficient quantity
            foreach ($inventoryRequest->items as $item) {
                if ($item->requested_quantity > $item->inventory->quantity) {
                    DB::rollBack();
                    $itemName = $item->inventory->product_name ?? $item->inventory->spice_name;
                    return back()->with('error', "Insufficient quantity for {$itemName}. Available: {$item->inventory->quantity}, Requested: {$item->requested_quantity}");
                }
            }

            // Deduct quantities from inventory
            foreach ($inventoryRequest->items as $item) {
                $inventory = $item->inventory;
                $inventory->quantity -= $item->requested_quantity;
                $inventory->save();
            }

            // Update request status
            $inventoryRequest->status = 'approved';
            $inventoryRequest->approved_by = $user->id;
            $inventoryRequest->approved_at = now();
            $inventoryRequest->save();

            DB::commit();

            return back()->with('success', 'Inventory request approved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve inventory request.');
        }
    }

    /**
     * Reject an inventory request (warehouse manager only)
     */
    public function reject(InventoryRequest $inventoryRequest)
    {
        $user = auth()->user();
        
        // Only warehouse managers and admins can reject requests
        if (!$user->hasRole('warehouse_manager') && !$user->hasRole('admin')) {
            abort(403, 'Access denied.');
        }

        // Check if warehouse manager has access to this inventory type
        if ($user->hasRole('warehouse_manager')) {
            $inventoryRequest->load('items.inventory');
            $hasAccess = false;
            foreach ($inventoryRequest->items as $item) {
                if ($user->inventory_type === $item->inventory->type) {
                    $hasAccess = true;
                    break;
                }
            }
            if (!$hasAccess) {
                abort(403, 'You do not have access to this inventory type.');
            }
        }

        if ($inventoryRequest->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $inventoryRequest->status = 'rejected';
        $inventoryRequest->approved_by = $user->id;
        $inventoryRequest->approved_at = now();
        $inventoryRequest->save();

        return back()->with('success', 'Inventory request rejected successfully.');
    }
}

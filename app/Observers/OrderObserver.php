<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\InventoryService;
use Illuminate\Support\Facades\App;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     * Reserve inventory when order is created
     */
    public function created(Order $order)
    {
        $inventoryService = App::make(InventoryService::class);
        
        try {
            $inventoryService->reserveForOrder($order);
        } catch (\Exception $e) {
            \Log::error("Failed to reserve inventory for order {$order->id}: " . $e->getMessage());
            // Consider rolling back the order creation or marking as failed
        }
    }

    /**
     * Handle the Order "updated" event.
     * Process inventory changes when order status changes
     */
    public function updated(Order $order)
    {
        $inventoryService = App::make(InventoryService::class);
        
        // If status changed from pending/cancelled to paid, confirm the reservation
        if ($this->statusChangedToPaid($order)) {
            try {
                $inventoryService->confirmOrder($order);
            } catch (\Exception $e) {
                \Log::error("Failed to confirm inventory for order {$order->id}: " . $e->getMessage());
                
                // Revert the status change or handle the error appropriately
                $order->update(['status' => 'pending']);
                throw $e;
            }
        }
        
        // If status changed to cancelled, cancel the reservation
        if ($this->statusChangedToCancelled($order)) {
            $inventoryService->cancelReservation($order);
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order)
    {
        $inventoryService = App::make(InventoryService::class);
        
        // Release any reservations if order is deleted
        $inventoryService->cancelReservation($order);
    }

    /**
     * Check if order status changed to a paid state
     */
    private function statusChangedToPaid(Order $order)
    {
        return (
            $order->isDirty('status') && 
            in_array($order->status, ['paid', 'confirmed', 'processing'])
        );
    }

    /**
     * Check if order status changed to cancelled
     */
    private function statusChangedToCancelled(Order $order)
    {
        return (
            $order->isDirty('status') && 
            $order->status === 'cancelled' &&
            in_array($order->getOriginal('status'), ['pending', 'paid', 'confirmed', 'processing'])
        );
    }
}
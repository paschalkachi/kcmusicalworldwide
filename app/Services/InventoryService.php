<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Reservation;
use App\Models\Product;

class InventoryService
{

    public function __construct()
    {
        //
    }
    /**
     * Reserve inventory for a pending order
     */


    public function reserveForOrder(Order $order)
    {
        // Determine reservation time based on payment method
        $reservationTimeInMinutes = $this->getReservationTimeForPaymentMethod($order->payment_method);
        
        foreach ($order->items as $item) {
            $product = $item->product;
            
            // Check if enough stock is available
            $availableQuantity = $product->getAvailableQuantityAttribute();
            if ($availableQuantity < $item->quantity) {
                throw new \Exception("Not enough stock available for product: {$product->name}. Available: {$availableQuantity}, Requested: {$item->quantity}");
            }
            
            // Reserve the quantity with appropriate timeout
            $result = $product->reserveForOrder($order->id, $item->quantity, $reservationTimeInMinutes);
            if (!$result) {
                throw new \Exception("Failed to reserve stock for product: {$product->name}");
            }
        }
    }
    
    /**
     * Confirm inventory reservation (reduce actual stock)
     */
    public function confirmOrder(Order $order)
    {
        foreach ($order->items as $item) {
            $product = $item->product;
            
            // Confirm the reservation and reduce actual stock
            $result = $product->confirmReservation($order->id);
            if (!$result) {
                throw new \Exception("Failed to confirm reservation for product: {$product->name}");
            }
        }
    }
    
    /**
     * Cancel inventory reservation (release stock)
     */
    public function cancelReservation(Order $order)
    {
        foreach ($order->items as $item) {
            $product = $item->product;
            
            // Release the reservation
            $product->cancelReservation($order->id);
        }
    }
    
    /**
     * Release expired reservations
     */
    public function releaseExpiredReservations()
    {
        $expiredReservations = Reservation::where('expires_at', '<', now())->get();
        
        foreach ($expiredReservations as $reservation) {
            $product = $reservation->product;
            
            // Release the reservation
            $product->releaseReservation($reservation->order_id);
        }
    }
    
    /**
     * Get reservation time based on payment method
     */
    private function getReservationTimeForPaymentMethod($paymentMethod)
    {
        switch ($paymentMethod) {
            case 'paystack':
                return 30; // 30 minutes for Paystack
            case 'cod':
                return 7 * 24 * 60; // 7 days (in minutes) for COD
            default:
                return 30; // Default to 30 minutes
        }
    }
}
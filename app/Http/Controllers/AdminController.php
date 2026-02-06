<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Role;
use App\Models\State;
use App\Models\Address;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
   
    //Admin Dashboard page
    public function index()
    {
        // Fetch metrics data
        $adminRoleId = Role::where('slug', 'admin')->value('id');
        
        $nonAdminUsersCount = User::whereDoesntHave('roles', function($query) use ($adminRoleId) {
            $query->where('roles.id', $adminRoleId);  // Specify the table name to avoid ambiguity
        })->count();
        
        $totalOrders = Order::count();
        $totalOrdersAmount = Order::sum('total');
        
        $pendingOrders = Order::where('status', 'pending')->count();
        $pendingOrdersAmount = Order::where('status', 'pending')->sum('total');
        
        $paidOrders = Order::whereIn('status', ['paid', 'shipped', 'delivered'])->count();
        $paidOrdersAmount = Order::whereIn('status', ['paid', 'shipped', 'delivered'])->sum('total');
        
        $cancelledOrders = Order::where('status', 'cancelled')->count();
        $cancelledOrdersAmount = Order::where('status', 'cancelled')->sum('total');
        
        $totalProducts = Product::count();
        
        // Get recent orders
        $recentOrders = Order::with(['user', 'transaction'])
            ->latest()
            ->limit(5)
            ->get();
        
        // Get Nigerian states data for customers
        $nigerianStatesData = Address::select('states.name', \DB::raw('COUNT(addresses.id) as customers_count'))
            ->join('states', 'addresses.state_id', '=', 'states.id')
            ->groupBy('states.name')
            ->orderBy('customers_count', 'desc')
            ->limit(5)
            ->get();
        
        // Calculate total customers across all states for percentage calculation
        $totalStateCustomers = $nigerianStatesData->sum('customers_count');
        
        // Format the states data for the component
        $topStates = [];
        foreach ($nigerianStatesData as $stateData) {
            $percentage = $totalStateCustomers > 0 ? 
                round(($stateData->customers_count / $totalStateCustomers) * 100) : 0;
            
            $topStates[] = [
                'name' => $stateData->name,
                'customers' => number_format($stateData->customers_count),
                'percentage' => $percentage
            ];
        }
        
        // If no data, use default values
        if (empty($topStates)) {
            $topStates = [
                ['name' => 'Lagos', 'customers' => '0', 'percentage' => 0],
                ['name' => 'Abuja', 'customers' => '0', 'percentage' => 0],
                ['name' => 'Rivers', 'customers' => '0', 'percentage' => 0],
                ['name' => 'Kano', 'customers' => '0', 'percentage' => 0],
                ['name' => 'Delta', 'customers' => '0', 'percentage' => 0]
            ];
        }
        
        // Calculate monthly sales data for the current year
        $currentYear = Carbon::now()->year;
        $monthlySalesData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $salesForMonth = Order::whereYear('created_at', $currentYear)
                                  ->whereMonth('created_at', $month)
                                  ->where(function($query) {
                                      $query->whereIn('status', ['paid', 'shipped', 'delivered']);
                                  })
                                  ->sum('total');  // Changed from 'total_amount' to 'total'
                                  
            $monthlySalesData[] = (int)$salesForMonth;
        }
        
        return view('admin.pages.dashboard.ecommerce', compact(
            'nonAdminUsersCount',
            'totalOrders',
            'totalOrdersAmount',
            'pendingOrders',
            'pendingOrdersAmount',
            'paidOrders',
            'paidOrdersAmount',
            'cancelledOrders',
            'cancelledOrdersAmount',
            'totalProducts',
            'topStates',
            'recentOrders',
            'monthlySalesData'
        )); // create this blade file
    }
    
    // Method to return sales data for the chart
    public function getSalesData()
    {
        $currentYear = Carbon::now()->year;
        $monthlySalesData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $salesForMonth = Order::whereYear('created_at', $currentYear)
                                  ->whereMonth('created_at', $month)
                                  ->where(function($query) {
                                      $query->whereIn('status', ['paid', 'shipped', 'delivered']);
                                  })
                                  ->sum('total');  // Changed from 'total_amount' to 'total'
                                  
            $monthlySalesData[] = (int)$salesForMonth;
        }
        
        return response()->json([
            'monthlySalesData' => $monthlySalesData,
            'year' => $currentYear
        ]);
    }
}
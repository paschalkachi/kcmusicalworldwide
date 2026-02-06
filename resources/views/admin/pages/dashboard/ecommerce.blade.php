@extends('admin.layouts.app')

@section('content')  
  <div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12 space-y-6">
      <x-ecommerce.ecommerce-metrics 
        :nonAdminUsersCount="$nonAdminUsersCount" 
        :totalOrders="$totalOrders" 
        :totalOrdersAmount="$totalOrdersAmount"
        :pendingOrders="$pendingOrders" 
        :pendingOrdersAmount="$pendingOrdersAmount"
        :paidOrders="$paidOrders" 
        :paidOrdersAmount="$paidOrdersAmount"
        :cancelledOrders="$cancelledOrders" 
        :cancelledOrdersAmount="$cancelledOrdersAmount"
        :totalProducts="$totalProducts" 
      />
      <x-ecommerce.monthly-sale />
    </div>
    {{-- <div class="col-span-12 xl:col-span-5">
        <x-ecommerce.monthly-target />
    </div> --}}

    {{-- Removed statistics chart to keep only the monthly sales chart --}}
    {{-- <div class="col-span-12">
      <x-ecommerce.statistics-chart />
    </div> --}}

    <div class="col-span-12 xl:col-span-5">
      <x-ecommerce.customer-demographic :states="$topStates" />
    </div>

    <div class="col-span-12 xl:col-span-7">
      <x-ecommerce.recent-orders :orders="$recentOrders" />
    </div>
  </div>
@endsection
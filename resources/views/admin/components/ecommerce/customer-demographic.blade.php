@props(['states' => []])

@php
    $defaultStates = [
        ['name' => 'Lagos', 'customers' => '0', 'percentage' => 0],
        ['name' => 'Abuja', 'customers' => '0', 'percentage' => 0],
        ['name' => 'Rivers', 'customers' => '0', 'percentage' => 0],
        ['name' => 'Kano', 'customers' => '0', 'percentage' => 0],
        ['name' => 'Delta', 'customers' => '0', 'percentage' => 0]
    ];
    
    $statesList = !empty($states) ? $states : $defaultStates;
@endphp

<div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] sm:p-6">
    <div class="flex justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Customers Demographic
            </h3>
            <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">
                Number of customer based on Nigerian states
            </p>
        </div>

         <!-- Dropdown Menu -->
         <x-common.dropdown-menu />
         <!-- End Dropdown Menu -->
    </div>

    <div class="space-y-5">
        @foreach($statesList as $state)
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-full max-w-8 items-center rounded-full flex items-center justify-center bg-gray-100 dark:bg-gray-700 h-8 w-8">
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-200">
                            {{ substr($state['name'], 0, 2) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-theme-sm font-semibold text-gray-800 dark:text-white/90">
                            {{ $state['name'] }}
                        </p>
                        <span class="block text-theme-xs text-gray-500 dark:text-gray-400">
                            {{ $state['customers'] }} Customers
                        </span>
                    </div>
                </div>

                <div class="flex w-full max-w-[140px] items-center gap-3">
                    <div class="relative block h-2 w-full max-w-[100px] rounded-sm bg-gray-200 dark:bg-gray-800">
                        <div 
                            class="absolute left-0 top-0 flex h-full items-center justify-center rounded-sm bg-brand-500 text-xs font-medium text-white"
                            style="width: {{ $state['percentage'] }}%"
                        ></div>
                    </div>
                    <p class="text-theme-sm font-medium text-gray-800 dark:text-white/90">
                        {{ $state['percentage'] }}%
                    </p>
                </div>
            </div>
        @endforeach
    </div>
</div>

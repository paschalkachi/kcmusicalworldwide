<div
    class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-5 pt-5 sm:px-6 sm:pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
            Monthly Sales
        </h3>

        <!-- Dropdown Menu -->
        <x-common.dropdown-menu />
        <!-- End Dropdown Menu -->
    </div>

    <div class="max-w-full overflow-x-auto custom-scrollbar">
        <div id="chartOne" class="-ml-5 h-full min-w-[690px] pl-2 xl:min-w-full"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fetch sales data from API endpoint
        fetch('{{ route("admin.dashboard.sales-data") }}')
            .then(response => response.json())
            .then(data => {
                renderChart(data.monthlySalesData);
            })
            .catch(error => {
                console.error('Error fetching sales data:', error);
                
                // Fallback to static data if API fails
                const fallbackData = [168, 385, 201, 298, 187, 195, 291, 110, 215, 390, 280, 112];
                renderChart(fallbackData);
            });
        
        function renderChart(monthlySalesData) {
            const chartElement = document.querySelector('#chartOne');
            if (!chartElement) return;

            const chartOneOptions = {
                series: [{
                    name: "Sales",
                    data: monthlySalesData,
                },],
                colors: ["#ba0001"], // Using your brand color
                chart: {
                    fontFamily: "Outfit, sans-serif",
                    type: "bar",
                    height: 180,
                    toolbar: {
                        show: false,
                    },
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: "39%",
                        borderRadius: 5,
                        borderRadiusApplication: "end",
                    },
                },
                dataLabels: {
                    enabled: false,
                },
                stroke: {
                    show: true,
                    width: 4,
                    colors: ["transparent"],
                },
                xaxis: {
                    categories: [
                        "Jan",
                        "Feb",
                        "Mar",
                        "Apr",
                        "May",
                        "Jun",
                        "Jul",
                        "Aug",
                        "Sep",
                        "Oct",
                        "Nov",
                        "Dec",
                    ],
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false,
                    },
                },
                legend: {
                    show: true,
                    position: "top",
                    horizontalAlign: "left",
                    fontFamily: "Outfit",
                    markers: {
                        radius: 99,
                    },
                },
                yaxis: {
                    title: false,
                },
                grid: {
                    yaxis: {
                        lines: {
                            show: true,
                        },
                    },
                },
                fill: {
                    opacity: 1,
                },

                tooltip: {
                    x: {
                        show: false,
                    },
                    y: {
                        formatter: function (val) {
                            return '₦' + val.toLocaleString();
                        },
                    },
                },
            };

            const chart = new ApexCharts(chartElement, chartOneOptions);
            chart.render();

            // Store chart instance for cleanup if needed
            window.monthlySalesChart = chart;
        }
    });
</script>
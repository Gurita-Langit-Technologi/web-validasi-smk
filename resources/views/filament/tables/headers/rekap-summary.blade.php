<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
    <!-- Card Selesai -->
    <div class="flex flex-col items-start !bg-green-100 !text-green-800 p-4 rounded-3xl shadow">
        <div class="flex items-center gap-2">
            <x-heroicon-m-check-circle class="w-5 h-5 !text-green-600" />
            <span class="text-sm font-medium !text-green-700">Selesai</span>
        </div>
        <div class="text-2xl font-bold mt-1 !text-green-800">{{ $selesai }}</div>
    </div>

    <!-- Card Belum -->
    <div class="flex flex-col items-start !bg-red-100 !text-red-800 p-4 rounded-3xl shadow">
        <div class="flex items-center gap-2">
            <x-heroicon-m-x-circle class="w-5 h-5 !text-red-600" />
            <span class="text-sm font-medium !text-red-700">Belum</span>
        </div>
        <div class="text-2xl font-bold mt-1 !text-red-800">{{ $belum }}</div>
    </div>

    <!-- Card Progress -->
    <div class="flex flex-col !bg-orange-100 !text-orange-800 p-4 rounded-3xl shadow">
        <div class="flex justify-between items-center w-full mb-1">
            <div class="flex items-center gap-2">
                <x-heroicon-m-chart-bar class="w-5 h-5 !text-orange-600" />
                <span class="text-sm font-medium !text-orange-700">Statistic</span>
            </div>
            <span class="text-sm font-semibold !text-orange-700">{{ $persen }}%</span>
        </div>
        <div class="w-full !bg-orange-200 rounded-full h-4">
            <div class="!bg-orange-600 h-2 rounded-full transition-all duration-500"
                style="width: {{ $persen }}%">
            </div>
        </div>
    </div>
</div>

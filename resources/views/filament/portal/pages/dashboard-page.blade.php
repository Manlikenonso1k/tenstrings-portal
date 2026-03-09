<x-filament-panels::page>
    <div class="grid gap-4 md:grid-cols-3">
        <x-filament::section>
            <div class="text-sm text-gray-500">Welcome</div>
            <div class="text-lg font-semibold">{{ auth()->user()->name }}</div>
        </x-filament::section>
        <x-filament::section>
            <div class="text-sm text-gray-500">Matric Number</div>
            <div class="text-lg font-semibold">{{ auth()->user()?->student?->student_number ?? '-' }}</div>
        </x-filament::section>
        <x-filament::section>
            <div class="text-sm text-gray-500">Branch</div>
            <div class="text-lg font-semibold">{{ auth()->user()?->student?->branch ?? '-' }}</div>
        </x-filament::section>
    </div>
</x-filament-panels::page>

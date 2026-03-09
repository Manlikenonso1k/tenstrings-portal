<x-filament-panels::page>
    <x-filament::section>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><dt class="text-sm text-gray-500">Matric Number</dt><dd class="font-medium">{{ $student?->student_number }}</dd></div>
            <div><dt class="text-sm text-gray-500">Full Name</dt><dd class="font-medium">{{ trim(($student?->first_name ?? '') . ' ' . ($student?->last_name ?? '')) }}</dd></div>
            <div><dt class="text-sm text-gray-500">Phone</dt><dd class="font-medium">{{ $student?->phone }}</dd></div>
            <div><dt class="text-sm text-gray-500">Guardian Phone</dt><dd class="font-medium">{{ $student?->guardian_phone }}</dd></div>
            <div><dt class="text-sm text-gray-500">Residential Address</dt><dd class="font-medium">{{ $student?->address }}</dd></div>
            <div><dt class="text-sm text-gray-500">Branch</dt><dd class="font-medium">{{ $student?->branch }}</dd></div>
        </dl>
    </x-filament::section>
</x-filament-panels::page>

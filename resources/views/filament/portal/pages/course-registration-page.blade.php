<x-filament-panels::page>
    <x-filament::section>
        {{ $this->form }}
        <div class="mt-4">
            <x-filament::button wire:click="registerCourses">Register Courses</x-filament::button>
        </div>
    </x-filament::section>

    <x-filament::section heading="Recent Registrations" class="mt-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr><th class="text-left p-2">Enrollment</th><th class="text-left p-2">Intake</th><th class="text-left p-2">Start</th><th class="text-left p-2">Courses</th></tr></thead>
                <tbody>
                    @forelse($recentEnrollments as $enrollment)
                        <tr class="border-t">
                            <td class="p-2">{{ $enrollment->enrollment_number }}</td>
                            <td class="p-2">{{ $enrollment->intake_month }}</td>
                            <td class="p-2">{{ $enrollment->start_date }}</td>
                            <td class="p-2">{{ $enrollment->courses->pluck('name')->join(', ') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="p-2 text-gray-500">No registrations yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-panels::page>

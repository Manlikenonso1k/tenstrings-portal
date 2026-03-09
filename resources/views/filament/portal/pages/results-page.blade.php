<x-filament-panels::page>
    <x-filament::section>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr><th class="text-left p-2">Course</th><th class="text-left p-2">Assessment</th><th class="text-left p-2">Month</th><th class="text-left p-2">Score</th><th class="text-left p-2">Grade</th></tr></thead>
                <tbody>
                    @forelse($results as $result)
                        <tr class="border-t"><td class="p-2">{{ $result->course?->name }}</td><td class="p-2">{{ strtoupper($result->assessment_type) }}</td><td class="p-2">{{ $result->assessment_month }}</td><td class="p-2">{{ $result->score }}/{{ $result->maximum_score }}</td><td class="p-2">{{ $result->grade_letter }}</td></tr>
                    @empty
                        <tr><td colspan="5" class="p-2 text-gray-500">No results yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-panels::page>

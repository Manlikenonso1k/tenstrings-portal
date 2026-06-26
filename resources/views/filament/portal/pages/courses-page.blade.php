<x-filament-panels::page>
    <x-filament::section>
        <div class="space-y-4">
            @forelse($courses as $course)
                <a
                    href="{{ route('courses.index', $course) }}"
                    class="flex items-center justify-between rounded-xl border border-gray-200 px-4 py-3 transition hover:bg-gray-50"
                >
                    <div>
                        <div class="font-semibold text-gray-900">{{ $course->name }}</div>
                        <div class="text-sm text-gray-500">{{ $course->modules_count }} module(s)</div>
                    </div>

                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @empty
                <div class="text-sm text-gray-500">No courses found for your account.</div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-panels::page>

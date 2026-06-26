<x-filament-panels::page>
    <x-filament::section>
        <div class="space-y-3">
            <div class="text-sm text-gray-500">
                {{ $course->name ?? $course->title }}
            </div>

            <h1 class="text-2xl font-semibold text-gray-900">{{ $lesson->title }}</h1>

            <div class="rounded-lg border border-gray-200 p-4">
                Lesson content goes here.
            </div>
        </div>
    </x-filament::section>
</x-filament-panels::page>

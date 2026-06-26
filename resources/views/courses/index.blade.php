<x-filament-panels::page>
    <x-filament::section>
        <div class="space-y-4">
            @foreach($modules as $module)
                @include('courses.partials.module-accordion', [
                    'course' => $course,
                    'module' => $module,
                    'unlockedModuleIds' => $unlockedModuleIds,
                    'completedLessonIds' => $completedLessonIds,
                ])
            @endforeach
        </div>
    </x-filament::section>
</x-filament-panels::page>

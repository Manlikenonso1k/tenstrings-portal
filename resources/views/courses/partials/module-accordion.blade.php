@php
    $moduleIsUnlocked = in_array($module->id, $unlockedModuleIds ?? [], true);
@endphp

<div @class([
    'rounded-xl border border-gray-200 bg-white',
    'opacity-50' => ! $moduleIsUnlocked,
])>
    <button
        type="button"
        @disabled(! $moduleIsUnlocked)
        @class([
            'flex w-full items-center justify-between gap-4 px-4 py-3 text-left',
            'cursor-pointer hover:bg-gray-50' => $moduleIsUnlocked,
            'cursor-not-allowed' => ! $moduleIsUnlocked,
        ])
    >
        <div class="flex items-center gap-3">
            @if($moduleIsUnlocked)
                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-gray-200 text-xs font-semibold text-gray-700">
                    {{ $module->order }}
                </span>
            @else
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 10-8 0v4m-2 0h12a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6a2 2 0 012-2z" />
                </svg>
            @endif

            <span class="font-medium text-gray-900">{{ $module->title }}</span>
        </div>
    </button>

    <div class="space-y-2 border-t border-gray-100 p-4">
        @foreach($module->lessons as $lesson)
            @php($lessonIsCompleted = in_array($lesson->id, $completedLessonIds ?? [], true))

            <div @class([
                'flex items-center gap-3 rounded-lg px-3 py-2',
                'pointer-events-none' => ! $moduleIsUnlocked,
            ])>
                @if($moduleIsUnlocked)
                    <span @class([
                        'h-3 w-3 rounded-full',
                        'bg-emerald-500' => $lessonIsCompleted,
                        'bg-gray-300' => ! $lessonIsCompleted,
                    ])></span>
                @else
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 10-8 0v4m-2 0h12a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6a2 2 0 012-2z" />
                    </svg>
                @endif

                @if($moduleIsUnlocked)
                    <a href="{{ route('courses.lessons.show', [$course, $module, $lesson]) }}" class="flex-1 text-sm font-medium text-gray-800 hover:text-indigo-600">
                        {{ $lesson->title }}
                    </a>
                @else
                    <span class="flex-1 text-sm font-medium text-gray-500">
                        {{ $lesson->title }}
                    </span>
                @endif
            </div>
        @endforeach
    </div>
</div>

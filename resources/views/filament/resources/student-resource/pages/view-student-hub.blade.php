<x-filament-panels::page>
    <div class="mb-2 flex items-center gap-4">
        @php
        $passportUrl = $this->record->avatar_url
            ? asset('uploads/' . ltrim($this->record->avatar_url, '/'))
            : null;
        @endphp
        @if ($passportUrl)
            <img src="{{ $passportUrl }}" alt="Passport"
                 class="w-16 h-16 rounded-full object-cover border-2 border-primary-500 shadow">
        @else
            <div class="w-16 h-16 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center border-2 border-gray-300 dark:border-gray-600">
                <x-heroicon-o-user class="w-8 h-8 text-gray-400"/>
            </div>
        @endif
        <div>
            <div class="text-base font-semibold text-gray-900 dark:text-white">
                {{ $this->record->first_name }} {{ $this->record->last_name }}
                <span class="ml-2 text-xs font-normal text-gray-500 dark:text-gray-400">{{ $this->record->student_number }}</span>
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Select a section to view or edit student details.</div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-4">

        {{-- IDENTITY & PASSPORT --}}
        <a href="{{ \App\Filament\Resources\StudentResource\Pages\EditStudentIdentity::getUrl(['record' => $this->record->getRouteKey()]) }}"
           class="group flex items-center gap-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6 shadow-sm hover:shadow-md hover:border-primary-400 dark:hover:border-primary-500 transition-all">
            <div class="flex-shrink-0 w-14 h-14 rounded-full bg-primary-50 dark:bg-primary-900/30 flex items-center justify-center">
                <x-heroicon-o-user class="w-7 h-7 text-primary-600 dark:text-primary-400"/>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-bold text-gray-900 dark:text-white text-base">IDENTITY & PASSPORT</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Course, matric number, start date & passport photo</div>
            </div>
            <x-heroicon-o-chevron-right class="flex-shrink-0 w-5 h-5 text-gray-400 group-hover:text-primary-500 transition-colors"/>
        </a>

        {{-- CORE INFORMATION --}}
        <a href="{{ \App\Filament\Resources\StudentResource\Pages\EditStudentCore::getUrl(['record' => $this->record->getRouteKey()]) }}"
           class="group flex items-center gap-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6 shadow-sm hover:shadow-md hover:border-primary-400 dark:hover:border-primary-500 transition-all">
            <div class="flex-shrink-0 w-14 h-14 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center">
                <x-heroicon-o-identification class="w-7 h-7 text-blue-600 dark:text-blue-400"/>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-bold text-gray-900 dark:text-white text-base">CORE INFORMATION</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Name, phone, branch, address & guardians</div>
            </div>
            <x-heroicon-o-chevron-right class="flex-shrink-0 w-5 h-5 text-gray-400 group-hover:text-blue-500 transition-colors"/>
        </a>

        {{-- ACADEMIC & PROGRESS --}}
        <a href="{{ \App\Filament\Resources\StudentResource\Pages\ViewStudentAcademic::getUrl(['record' => $this->record->getRouteKey()]) }}"
           class="group flex items-center gap-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6 shadow-sm hover:shadow-md hover:border-primary-400 dark:hover:border-primary-500 transition-all">
            <div class="flex-shrink-0 w-14 h-14 rounded-full bg-green-50 dark:bg-green-900/30 flex items-center justify-center">
                <x-heroicon-o-academic-cap class="w-7 h-7 text-green-600 dark:text-green-400"/>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-bold text-gray-900 dark:text-white text-base">ACADEMIC & PROGRESS</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Assessment stats per quarterly intake</div>
            </div>
            <x-heroicon-o-chevron-right class="flex-shrink-0 w-5 h-5 text-gray-400 group-hover:text-green-500 transition-colors"/>
        </a>

        {{-- DOCUMENT VAULT --}}
        <a href="{{ \App\Filament\Resources\StudentResource\Pages\EditStudentDocuments::getUrl(['record' => $this->record->getRouteKey()]) }}"
           class="group flex items-center gap-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6 shadow-sm hover:shadow-md hover:border-primary-400 dark:hover:border-primary-500 transition-all">
            <div class="flex-shrink-0 w-14 h-14 rounded-full bg-yellow-50 dark:bg-yellow-900/30 flex items-center justify-center">
                <x-heroicon-o-folder-open class="w-7 h-7 text-yellow-600 dark:text-yellow-400"/>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-bold text-gray-900 dark:text-white text-base">DOCUMENT VAULT</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">JAMB, WAEC, NECO & birth certificate</div>
            </div>
            <x-heroicon-o-chevron-right class="flex-shrink-0 w-5 h-5 text-gray-400 group-hover:text-yellow-500 transition-colors"/>
        </a>

    </div>
</x-filament-panels::page>

<x-filament-panels::page>
    <p class="text-sm text-gray-500 dark:text-gray-400 -mt-2 mb-6">Select a section below to view or update your information.</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

        {{-- IDENTITY & PASSPORT --}}
        <a href="{{ \App\Filament\Portal\Pages\StudentIdentityPage::getUrl() }}"
           class="group flex items-center gap-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6 shadow-sm hover:shadow-md hover:border-primary-400 dark:hover:border-primary-500 transition-all">
            <div class="flex-shrink-0 w-14 h-14 rounded-full bg-primary-50 dark:bg-primary-900/30 flex items-center justify-center">
                <x-heroicon-o-user class="w-7 h-7 text-primary-600 dark:text-primary-400"/>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-bold text-gray-900 dark:text-white text-base">IDENTITY & PASSPORT</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Matric number, name, course & passport photo</div>
            </div>
            <x-heroicon-o-chevron-right class="flex-shrink-0 w-5 h-5 text-gray-400 group-hover:text-primary-500 transition-colors"/>
        </a>

        {{-- CORE INFORMATION --}}
        <a href="{{ \App\Filament\Portal\Pages\StudentCoreInfoPage::getUrl() }}"
           class="group flex items-center gap-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6 shadow-sm hover:shadow-md hover:border-primary-400 dark:hover:border-primary-500 transition-all">
            <div class="flex-shrink-0 w-14 h-14 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center">
                <x-heroicon-o-identification class="w-7 h-7 text-blue-600 dark:text-blue-400"/>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-bold text-gray-900 dark:text-white text-base">CORE INFORMATION</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Phone, address & guardian contact</div>
            </div>
            <x-heroicon-o-chevron-right class="flex-shrink-0 w-5 h-5 text-gray-400 group-hover:text-blue-500 transition-colors"/>
        </a>

        {{-- ACADEMIC & PROGRESS --}}
        <a href="{{ \App\Filament\Portal\Pages\StudentAcademicPage::getUrl() }}"
           class="group flex items-center gap-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6 shadow-sm hover:shadow-md hover:border-primary-400 dark:hover:border-primary-500 transition-all">
            <div class="flex-shrink-0 w-14 h-14 rounded-full bg-green-50 dark:bg-green-900/30 flex items-center justify-center">
                <x-heroicon-o-academic-cap class="w-7 h-7 text-green-600 dark:text-green-400"/>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-bold text-gray-900 dark:text-white text-base">ACADEMIC & PROGRESS</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Assessment stats & printable letters</div>
            </div>
            <x-heroicon-o-chevron-right class="flex-shrink-0 w-5 h-5 text-gray-400 group-hover:text-green-500 transition-colors"/>
        </a>

        {{-- DOCUMENT VAULT --}}
        <a href="{{ \App\Filament\Portal\Pages\StudentDocumentsPage::getUrl() }}"
           class="group flex items-center gap-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6 shadow-sm hover:shadow-md hover:border-primary-400 dark:hover:border-primary-500 transition-all">
            <div class="flex-shrink-0 w-14 h-14 rounded-full bg-yellow-50 dark:bg-yellow-900/30 flex items-center justify-center">
                <x-heroicon-o-folder-open class="w-7 h-7 text-yellow-600 dark:text-yellow-400"/>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-bold text-gray-900 dark:text-white text-base">DOCUMENT VAULT</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Upload JAMB, WAEC, NECO & birth certificate</div>
            </div>
            <x-heroicon-o-chevron-right class="flex-shrink-0 w-5 h-5 text-gray-400 group-hover:text-yellow-500 transition-colors"/>
        </a>

        {{-- CHANGE PASSWORD --}}
        <a href="{{ \App\Filament\Portal\Pages\StudentPasswordPage::getUrl() }}"
           class="group flex items-center gap-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6 shadow-sm hover:shadow-md hover:border-primary-400 dark:hover:border-primary-500 transition-all">
            <div class="flex-shrink-0 w-14 h-14 rounded-full bg-rose-50 dark:bg-rose-900/30 flex items-center justify-center">
                <x-heroicon-o-key class="w-7 h-7 text-rose-600 dark:text-rose-400"/>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-bold text-gray-900 dark:text-white text-base">CHANGE PASSWORD</div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Update your portal login password securely</div>
            </div>
            <x-heroicon-o-chevron-right class="flex-shrink-0 w-5 h-5 text-gray-400 group-hover:text-rose-500 transition-colors"/>
        </a>

    </div>
</x-filament-panels::page>

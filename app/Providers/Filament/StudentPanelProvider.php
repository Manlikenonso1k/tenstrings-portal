<?php

namespace App\Providers\Filament;

use App\Filament\Portal\Pages\Auth\StudentLogin;
use App\Filament\Portal\Pages\AccommodationPage;
use App\Filament\Portal\Pages\CourseRegistrationPage;
use App\Filament\Portal\Pages\DashboardPage;
use App\Filament\Portal\Pages\PaymentsPage;
use App\Filament\Portal\Pages\ResultsPage;
use App\Filament\Portal\Pages\StudentDataPage;
use App\Filament\Portal\Pages\StudentIdentityPage;
use App\Filament\Portal\Pages\StudentCoreInfoPage;
use App\Filament\Portal\Pages\StudentAcademicPage;
use App\Filament\Portal\Pages\StudentDocumentsPage;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class StudentPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('portal')
            ->path('portal')
            ->login(StudentLogin::class)
            ->favicon(asset('images/tenstrings-logo.png'))
            ->simplePageMaxContentWidth('7xl')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->resources([])
            ->pages([
                DashboardPage::class,
                StudentDataPage::class,
                StudentIdentityPage::class,
                StudentCoreInfoPage::class,
                StudentAcademicPage::class,
                StudentDocumentsPage::class,
                PaymentsPage::class,
                CourseRegistrationPage::class,
                ResultsPage::class,
                AccommodationPage::class,
            ])
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}

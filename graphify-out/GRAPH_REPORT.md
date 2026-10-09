# Graph Report - tenstrings-portal  (2026-10-09)

## Corpus Check
- 378 files · ~82,430 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 2129 nodes · 4271 edges · 220 communities (88 shown, 50 thin omitted)
- Extraction: 96% EXTRACTED · 4% INFERRED · 0% AMBIGUOUS · INFERRED: 168 edges (avg confidence: 0.86)
- Token cost: 256,299 input · 0 output

## Community Hubs (Navigation)
- Inventory Filament Resources
- Portal & LMS Controllers
- Payments Page & Portal Settings
- User Model & Panel Access
- Payment Gateway Field Notes
- TGIPay & Webhook Controllers
- Inventory Edit & List Pages
- WhatsApp Reminder Stack
- Mobile API Blueprint
- Admin Resource List Pages
- Filament Panel Providers
- Student CSV Import Command
- Checkout Action Wiring
- Inventory Room Model & Policy
- Student Model & Observer
- Payment Gateway Contracts
- Student Portal Pages
- Resource Create Pages
- Inventory Room Resource
- Branch Scoping & Activity Log
- Feature Test Harness
- Student Importer
- Inventory Item Model
- Frontend Build Dependencies
- Payment Knowledge Base
- Item Condition & Status Enums
- Checkout Line Model
- Inventory Item Resource
- Ajah Inventory Importer
- Checkout Resource & Page Tests
- Inventory View Pages
- Enrollment Rules & Policy
- Core Domain Models
- Instructor & Course Seeders
- Checkout Notifications & Overdue
- Grade Resource & Calculator
- Instructor Resource
- Inventory Audit Model & Policy
- Checkout Service Tests
- Branch Model & Test Builders
- User Resource Admin
- Movement & Photo Models
- Checkout Model & Policy
- Item Policy & Branch Access
- Core Laravel Migrations
- Inventory Checkout Service
- Inventory Audit Resource Inventory UI
- Grade Model
- Activity Log Resource Admin UI
- Composer
- Inventory Module Spec Working Note
- Prompt Working Note
- Enrollment Resource Admin UI
- Payment Resource Admin UI
- Lesson Model
- Add Start Date And Duration To Students Table Mi
- Create Activity Log Table Migration
- Inventory Module Guide Doc
- Copy Sqlite To Mysql Command
- Active Import Tracker Dashboard Widget
- List Inventory Items Inventory UI
- Import Students Csv Admin Page
- Branch Resource Admin UI
- Student Resource Admin UI
- Branch Finance Chart Dashboard Widget
- Finance Chart Dashboard Widget
- Finance Ratio Chart Dashboard Widget
- Login Session Model
- Inventory Module Spec Working Note (2)
- Inventory Category Model
- Branch Enrollment Doughnut Dashboard Widget
- Composer (2)
- Inventory Module Spec Working Note (3)
- Inventory Room Photo Test Feature Test
- Send Whats App Message Job
- Inventory Audit Line Model
- Inventory Category Resource Inventory UI
- Courses Page Portal UI
- Course Shortcode Mapping Resource Admin UI
- Course Catalog Support
- Inventory Module Guide Doc (2)
- Aws Evolution Setup Working Note
- Audit Students Csv Import Command
- Inventory Checkout Exception
- Checkout Form Schema Inventory UI
- Image Resizer Support
- Student Login Portal UI
- Debtors List Admin Page
- Course Resource Admin UI
- Branch Enrollment Doughnut Dashboard Widget (2)
- Branch Student Credentials Mail
- Quarter Resolver Service
- Composer (3)
- Mobileapphandoff Agent Working Note
- Checkout Status Enum
- School Stats Overview Dashboard Widget
- Certificate Model
- Composer (4)
- Composer (5)
- Room Type Enum
- List Inventory Checkouts Inventory UI
- Login Session Resource Admin UI
- Debtors Chart Widget Dashboard Widget
- System Activity Line Chart Dashboard Widget
- Student Pdf Controller
- Composer (6)
- Return Status Enum
- Admin Dashboard Admin Page
- Assignment Model
- Attendance Model
- Course Module Model
- App
- Composer (7)
- Logging Config
- User Factory
- Assignment Submission Model
- Calendar Event Model
- Composer (8)
- Composer (9)
- Sanctum Config
- Create Permission Tables Migration
- Routes Public Doc
- Example Test Unit Test
- Output Working Note
- Credit Card
- Tenstrings Logo
- Filament Import Policy
- Composer (10)
- Placeholder Item
- Tenstrings Logo1
- Checkout Form Schema Inventory UI (2)
- Portal Setting Model
- Index.blade Blade View
- Course Registration Page.blade Blade View
- Student Core Info Page.blade Blade View
- Student Documents Page.blade Blade View
- Student Identity Page.blade Blade View
- Student Password Page.blade Blade View

## God Nodes (most connected - your core abstractions)
1. `User` - 137 edges
2. `Student` - 91 edges
3. `InventoryItem` - 66 edges
4. `Course` - 54 edges
5. `Payment` - 49 edges
6. `InventoryCheckout` - 46 edges
7. `InventoryRoom` - 37 edges
8. `InventoryRoomResource` - 34 edges
9. `Controller` - 28 edges
10. `PaymentService` - 28 edges

## Surprising Connections (you probably didn't know these)
- `Ajah inventory CSV import` --semantically_similar_to--> `student_import_template.csv`  [INFERRED] [semantically similar]
  docs/inventory-module-guide.md → prompts/handsoff.md
- `Robots Crawler Policy (Allow All)` --conceptually_related_to--> `Tenstrings Portal`  [AMBIGUOUS]
  public/robots.txt → README.md
- `Laravel Sanctum Bearer Token Auth` --semantically_similar_to--> `Sanctum Authentication Strategy`  [INFERRED] [semantically similar]
  README.md → instructions.md
- `Robots Crawler Policy (Allow All)` --conceptually_related_to--> `API/Filament Guard Separation`  [AMBIGUOUS]
  public/robots.txt → README.md
- `API/Filament Guard Separation` --semantically_similar_to--> `Stateless API vs Stateful Filament Constraint`  [INFERRED] [semantically similar]
  README.md → instructions.md

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Mobile API v1 Endpoint Surface** — readme_endpoint_auth, readme_endpoint_student_profile, readme_endpoint_courses, readme_endpoint_grades, readme_endpoint_attendance, readme_endpoint_payments, readme_endpoint_events [EXTRACTED 1.00]
- **Pricing and Data Integrity Improvements** — readme_dynamic_branch_pricing, readme_course_pricing_updates, readme_userroleseeder_overhashing_fix, readme_import_duplicate_resolution, readme_branch_model [INFERRED 0.75]
- **Stateless Token Auth Coexisting with Filament Sessions** — readme_laravel_sanctum, readme_guard_separation, readme_multi_panel_architecture, instructions_stateless_constraint, instructions_authentication_strategy, instructions_mobile_token_storage [INFERRED 0.85]
- **Payment success reconciliation cascade** — prompts_payment_knowledge_payment_service, prompts_payment_knowledge_payments_table, prompts_payment_knowledge_student_course_fee, prompts_payment_knowledge_payment_advice, prompts_payment_knowledge_document_service, prompts_payment_knowledge_redundant_status_tracking [EXTRACTED 1.00]
- **WhatsApp fee-reminder delivery stack (Evolution API on AWS)** — prompts_automatedmssg_send_fee_reminders_command, prompts_automatedmssg_sendwhatsappmessage_job, prompts_automatedmssg_message_sendtext_endpoint, prompts_automatedmssg_staggered_cumulative_delay, prompts_output_evolution_api_container, prompts_conversation_tenstrings_alerts_instance, prompts_conversation_empty_contact_table [INFERRED 0.85]
- **Inventory CSV import failure flow (import 12)** — prompts_error_livewire_update_endpoint, prompts_error_filament_importcsv_job, prompts_error_filament_importer, prompts_error_tenstrings_office_name_csv_column, prompts_error_inventory_items, prompts_error_failed_import_rows, prompts_error_queryexception_unknown_column_tenstrings_office_name, prompts_error_duplicate_inventory_room_creation [EXTRACTED 1.00]
- **Three-layer inventory officer isolation** — prompts_inventory_module_spec_isolation_strategy, prompts_inventory_module_spec_inventory_panel_provider, prompts_inventory_module_spec_can_access_panel, prompts_inventory_module_spec_scoped_to_branch, prompts_inventory_module_spec_access_matrix_test, docs_inventory_module_guide_branch_restriction [EXTRACTED 1.00]
- **Event check-out and return evidence flow** — prompts_prompt_inventory_checkouts, prompts_prompt_inventory_checkout_items, prompts_prompt_inventory_checkout_photos, prompts_prompt_inventory_checkout_service, prompts_prompt_checkout_flow, prompts_prompt_return_flow, prompts_prompt_photo_evidence_rule, prompts_prompt_availability_rules [EXTRACTED 1.00]
- **Inventory room grid plus transfer logging feature** — prompts_ui_prompt_inventoryitemresource, prompts_ui_prompt_inventoryroomresource, prompts_ui_prompt_content_grid_layout, prompts_ui_prompt_room_grouping_collapsed, prompts_ui_prompt_inventorytransfer, prompts_ui_prompt_transfer_action_modal, prompts_ui_prompt_no_blade_override_constraint [EXTRACTED 1.00]
- **Public Route Request Flow** — docs_graphs_routes_public_routes, docs_graphs_routes_public_client, docs_graphs_routes_public_route__, docs_graphs_routes_public_closure_handler [EXTRACTED 1.00]
- **Payment and Document Icon Set (shared 96x96 circular-badge style)** — public_assets_icons_credit_card_credit_card, public_assets_icons_papers_papers, public_assets_icons_printer_printer, public_assets_icons_receipt_receipt [INFERRED 0.85]

## Communities (220 total, 50 thin omitted)

### Community 0 - "Inventory Filament Resources"
Cohesion: 0.05
Nodes (44): App\Filament\Inventory\Resources\InventoryAuditResource\Pages, App\Filament\Inventory\Resources\InventoryCategoryResource\Pages, App\Filament\Inventory\Resources\InventoryCheckoutResource\Pages, App\Filament\Inventory\Resources\InventoryCheckoutResource\RelationManagers, LinesRelationManager, App\Filament\Inventory\Resources\InventoryItemResource\Pages, App\Filament\Inventory\Resources\InventoryItemResource\RelationManagers, CheckoutHistoryRelationManager (+36 more)

### Community 1 - "Portal & LMS Controllers"
Cohesion: 0.08
Nodes (18): AnnouncementController, AttendanceController, AuthController, CalendarController, CourseController, GradeController, PaymentApiController, StudentProfileController (+10 more)

### Community 2 - "Payments Page & Portal Settings"
Cohesion: 0.07
Nodes (12): PaymentsPage, PortalSettingResource, PaymentController, FeeWorkflowController, PaymentAdvice, PortalSetting, StudentCourseFee, FeeCalculationService (+4 more)

### Community 3 - "User Model & Panel Access"
Cohesion: 0.06
Nodes (11): User, CoursePolicy, InventoryCategoryPolicy, PaymentPolicy, StudentPolicy, Filament\Models\Contracts\FilamentUser, Filament\Models\Contracts\HasAvatar, Illuminate\Foundation\Auth\User (+3 more)

### Community 4 - "Payment Gateway Field Notes"
Cohesion: 0.05
Nodes (49): Beach event ticketing system (target project), config/services.php gateway config keys, Webhook CSRF exclusion and retry gotchas, Dummy-credential rule for extracted config keys, Payment model / payments table, Payment knowledge extraction task (read-only agent brief), PAYMENT_KNOWLEDGE.md deliverable, Payment routes (routes/web.php, routes/api.php) (+41 more)

### Community 5 - "TGIPay & Webhook Controllers"
Cohesion: 0.08
Nodes (11): TgiPayController, WebhookController, Invoice, Payment, DocumentService, PaymentService, Filament\Forms\Components\Textarea, Illuminate\Support\Facades\Hash (+3 more)

### Community 6 - "Inventory Edit & List Pages"
Cohesion: 0.07
Nodes (15): EditInventoryAudit, ListInventoryAudits, EditInventoryCategory, EditAssignment, EditAttendance, EditCourse, EditInstructor, EditPortalSetting (+7 more)

### Community 7 - "WhatsApp Reminder Stack"
Cohesion: 0.06
Nodes (38): Anti-ban messaging architecture, Evolution API docker-compose deployment, Evolution API (unofficial WhatsApp API), Automated fee reminder to students, Filament admin panel (student creation trigger), Evolution API global API token, Graceful try/catch around the Evolution API call, Evolution API /message/sendText endpoint (+30 more)

### Community 8 - "Mobile API Blueprint"
Cohesion: 0.09
Nodes (35): API Integration Blueprint Brief, Eloquent API Resource and Controller Sample, API Route Blueprint, Sanctum Authentication Strategy, Hand-Written Controllers vs Filament REST Plugin, Hostinger Hosting Target, Mobile Token Storage and Fetch Pattern, Stateless API vs Stateful Filament Constraint (+27 more)

### Community 9 - "Admin Resource List Pages"
Cohesion: 0.08
Nodes (12): ListActivityLogs, ListAssignments, ListAttendances, ListBranches, ListCourseShortcodeMappings, ListEnrollments, ListGrades, ListInstructors (+4 more)

### Community 10 - "Filament Panel Providers"
Cohesion: 0.17
Nodes (22): AdminPanelProvider, InstructorPanelProvider, InventoryPanelProvider, StudentPanelProvider, Filament\Http\Middleware\Authenticate, Filament\Http\Middleware\AuthenticateSession, Filament\Http\Middleware\DisableBladeIconComponents, Filament\Http\Middleware\DispatchServingFilamentEvent (+14 more)

### Community 11 - "Student CSV Import Command"
Cohesion: 0.14
Nodes (3): ImportStudentsFromCsv, Illuminate\Support\Carbon, RuntimeException

### Community 12 - "Checkout Action Wiring"
Cohesion: 0.14
Nodes (6): CheckoutActions, Action, CheckoutFormSchema, FileUpload, BulkAction, static

### Community 13 - "Inventory Room Model & Policy"
Cohesion: 0.12
Nodes (7): InventoryRoom, LogOptions, InventoryRoomObserver, InventoryRoomPolicy, AppServiceProvider, Illuminate\Support\Facades\Gate, Illuminate\Support\ServiceProvider

### Community 14 - "Student Model & Observer"
Cohesion: 0.11
Nodes (6): Student, StudentObserver, EventServiceProvider, MatricNumberGenerator, Carbon\Carbon, Illuminate\Foundation\Support\Providers\EventServiceProvider

### Community 15 - "Payment Gateway Contracts"
Cohesion: 0.13
Nodes (5): PaymentGatewayInterface, PaystackTitanGateway, TgiPayGateway, Illuminate\Support\Arr, Illuminate\Support\Facades\Http

### Community 16 - "Student Portal Pages"
Cohesion: 0.13
Nodes (8): CourseRegistrationPage, StudentCoreInfoPage, StudentDocumentsPage, StudentIdentityPage, StudentPasswordPage, Filament\Forms\Components\Select, Filament\Forms\Concerns\InteractsWithForms, Filament\Forms\Contracts\HasForms

### Community 17 - "Resource Create Pages"
Cohesion: 0.12
Nodes (10): CreateInventoryCategory, CreateInventoryItem, CreateAssignment, CreateAttendance, CreateCourse, CreateInstructor, CreatePortalSetting, CreateStudent (+2 more)

### Community 18 - "Inventory Room Resource"
Cohesion: 0.11
Nodes (5): InventoryRoomResource, CreateInventoryRoom, EditInventoryRoom, Action, FileUpload

### Community 19 - "Branch Scoping & Activity Log"
Cohesion: 0.13
Nodes (10): ScopedToBranch, LogOptions, LogOptions, LogOptions, LogOptions, DateTimeInterface, Illuminate\Database\Eloquent\Builder, Illuminate\Database\Eloquent\SoftDeletes (+2 more)

### Community 20 - "Feature Test Harness"
Cohesion: 0.12
Nodes (7): Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Foundation\Testing\TestCase, EnrollmentLimitTest, ExampleTest, InventoryCheckoutPolicyTest, InventoryPanelIsolationTest, TestCase

### Community 22 - "Inventory Item Model"
Cohesion: 0.14
Nodes (3): InventoryItem, InventoryItemObserver, AssetTagGenerator

### Community 23 - "Frontend Build Dependencies"
Cohesion: 0.10
Nodes (20): axios, concurrently, laravel-vite-plugin, devDependencies, axios, concurrently, laravel-vite-plugin, tailwindcss (+12 more)

### Community 24 - "Payment Knowledge Base"
Cohesion: 0.13
Nodes (21): WhatsApp fee reminders agent handoff, Automated WhatsApp fee reminders, /payments mobile endpoints, Payment System Knowledge Base, 200 OK on failed signature, Webhook CSRF exclusion, DocumentService::generateReceiptPdf, FeeWorkflowController (+13 more)

### Community 27 - "Inventory Item Resource"
Cohesion: 0.12
Nodes (5): InventoryItemResource, EditInventoryItem, ItemsRelationManager, Filament\Actions\Action, Livewire\Livewire

### Community 28 - "Ajah Inventory Importer"
Cohesion: 0.14
Nodes (5): AjahInventoryImporter, Filament\Actions\Imports\Exceptions\RowImportFailedException, Filament\Actions\Imports\ImportColumn, Filament\Actions\Imports\Importer, Illuminate\Support\Str

### Community 30 - "Inventory View Pages"
Cohesion: 0.16
Nodes (6): ViewInventoryCheckout, ViewInventoryRoom, ViewActivityLog, Filament\Infolists, Filament\Infolists\Infolist, Filament\Resources\Pages\ViewRecord

### Community 31 - "Enrollment Rules & Policy"
Cohesion: 0.15
Nodes (3): Enrollment, EnrollmentPolicy, EnrollmentLimitService

### Community 32 - "Core Domain Models"
Cohesion: 0.22
Nodes (5): Announcement, CourseShortcodeMapping, Message, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Database\Eloquent\Model

### Community 33 - "Instructor & Course Seeders"
Cohesion: 0.16
Nodes (7): Instructor, CourseSeeder, DatabaseSeeder, DemoSeeder, UserRoleSeeder, Illuminate\Database\Console\Seeds\WithoutModelEvents, Illuminate\Database\Seeder

### Community 34 - "Checkout Notifications & Overdue"
Cohesion: 0.17
Nodes (6): MarkInventoryCheckoutsOverdue, InventoryCheckoutOverdue, InventoryItemReturnedBadly, Illuminate\Bus\Queueable, Illuminate\Notifications\Notification, Illuminate\Support\Facades\Notification

### Community 35 - "Grade Resource & Calculator"
Cohesion: 0.13
Nodes (4): GradeResource, CreateGrade, EditGrade, GradeCalculator

### Community 36 - "Instructor Resource"
Cohesion: 0.13
Nodes (5): InstructorResource, StudentRegistrationController, StudentMatricMailer, Hash, Illuminate\Validation\Rule

### Community 37 - "Inventory Audit Model & Policy"
Cohesion: 0.15
Nodes (3): InventoryAudit, Collection, InventoryAuditPolicy

### Community 39 - "Branch Model & Test Builders"
Cohesion: 0.17
Nodes (3): Branch, self, BuildsInventory

### Community 40 - "User Resource Admin"
Cohesion: 0.12
Nodes (4): EditUser, ListUsers, UserResource, Spatie\Permission\Models\Role

### Community 41 - "Movement & Photo Models"
Cohesion: 0.19
Nodes (3): InventoryCheckoutPhoto, InventoryMovement, Illuminate\Database\Eloquent\Relations\BelongsTo

### Community 45 - "Inventory Checkout Service"
Cohesion: 0.26
Nodes (3): PhotoStage, InventoryCheckoutService, Illuminate\Support\Collection

### Community 47 - "Grade Model"
Cohesion: 0.18
Nodes (4): ResultsPage, StudentAcademicPage, ViewStudentAcademic, Grade

### Community 48 - "Activity Log Resource Admin UI"
Cohesion: 0.14
Nodes (7): ActivityLogResource, App\Filament\Resources\ActivityLogResource\Pages, Filament\Forms\Components\DatePicker, Filament\Infolists\Components\KeyValueEntry, Filament\Infolists\Components\Section, Filament\Infolists\Components\TextEntry, Spatie\Activitylog\Models\Activity

### Community 49 - "Composer"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, extra, laravel, dont-discover, license, minimum-stability (+5 more)

### Community 50 - "Inventory Module Spec Working Note"
Cohesion: 0.21
Nodes (14): Inventory acceptance checklist, Role access matrix feature test, Asset tag generation (TS-{BRANCH}-{CAT}-{0001}), branches table, CategoryResource (Filament), Denormalised branch_id on items, inventory_audit_lines table, inventory_audits table (+6 more)

### Community 51 - "Prompt Working Note"
Cohesion: 0.20
Nodes (14): Webhook verification flaw (hardcoded Paystack signature), Checkout acceptance checklist, Checkout availability rules, checked_out item status, Check-out wizard flow, Item checkout history relation manager, Event checkouts page, inventory_checkout_items table (+6 more)

### Community 52 - "Enrollment Resource Admin UI"
Cohesion: 0.18
Nodes (3): EnrollmentResource, CreateEnrollment, EditEnrollment

### Community 53 - "Payment Resource Admin UI"
Cohesion: 0.18
Nodes (3): CreatePayment, EditPayment, PaymentResource

### Community 54 - "Lesson Model"
Cohesion: 0.21
Nodes (5): EnsureLessonModuleIsUnlocked, Lesson, Closure, Illuminate\Database\Eloquent\Relations\BelongsToMany, Symfony\Component\HttpFoundation\Response

### Community 56 - "Create Activity Log Table Migration"
Cohesion: 0.19
Nodes (4): CreateActivityLogTable, AddEventColumnToActivityLogTable, AddBatchUuidColumnToActivityLogTable, Illuminate\Database\Migrations\Migration

### Community 57 - "Inventory Module Guide Doc"
Cohesion: 0.18
Nodes (13): Inventory Module Implementation Guide, /admin/users/create route, ceo role (read-only, all branches, costs), /inventory/login route, /inventory panel route, super_admin role, Inventory Module + Scoped Inventory Officer Role spec, User::canAccessPanel() (+5 more)

### Community 58 - "Copy Sqlite To Mysql Command"
Cohesion: 0.24
Nodes (4): CopySqliteToMysql, RelocateInventoryPhotos, Illuminate\Console\Command, Illuminate\Support\Facades\Config

### Community 59 - "Active Import Tracker Dashboard Widget"
Cohesion: 0.23
Nodes (4): ActiveImportTracker, ImportProgressWidget, Filament\Actions\Imports\Models\Import, Filament\Widgets\Widget

### Community 60 - "List Inventory Items Inventory UI"
Cohesion: 0.20
Nodes (3): ListInventoryItems, ListInventoryRooms, ViewToggle

### Community 61 - "Import Students Csv Admin Page"
Cohesion: 0.17
Nodes (7): ImportStudentsCsv, Filament\Forms\Components\FileUpload, Filament\Forms\Components\Section, Filament\Forms\Components\Toggle, Illuminate\Foundation\Inspiring, Illuminate\Support\Facades\Artisan, Illuminate\Support\Facades\Schedule

### Community 67 - "Login Session Model"
Cohesion: 0.23
Nodes (5): StoreLoginSession, StoreLogoutSession, LoginSession, Illuminate\Auth\Events\Login, Illuminate\Auth\Events\Logout

### Community 68 - "Inventory Module Spec Working Note (2)"
Cohesion: 0.20
Nodes (12): Activity-log audit trail, InventoryOverview dashboard, Spatie inventory permission set, inventory.view_all_branches permission, inventory.view_costs permission, ItemResource (Filament), needs_attention filter, Permissions deliberately withheld from the officer (+4 more)

### Community 69 - "Inventory Category Model"
Cohesion: 0.24
Nodes (4): InventoryCategory, InventoryPermissionSeeder, Spatie\Permission\Models\Permission, Spatie\Permission\PermissionRegistrar

### Community 71 - "Composer (2)"
Cohesion: 0.18
Nodes (11): scripts, dev, post-update-cmd, pre-package-uninstall, test, Composer\\Config::disableProcessTimeout, Illuminate\\Foundation\\ComposerScripts::prePackageUninstall, npx concurrently -c \"#93c5fd,#c4b5fd,#fb7185,#fdba74\" \"php artisan serve\" \"php artisan queue:listen --tries=1 --timeout=0\" \"php artisan pail --timeout=0\" \"npm run dev\" --names=server,queue,logs,vite --kill-others (+3 more)

### Community 72 - "Inventory Module Spec Working Note (3)"
Cohesion: 0.20
Nodes (11): CSV header mapping (TENSTRINGS OFFICE NAME, ITEM CODE, ITEM), Equipment photo and notes upload, Ajah inventory CSV import, Leading quantity parsing ("6 chairs"), student_import_template.csv, AuditResource (Filament), Anti-duplicate CSV importer pattern, item.import permission (+3 more)

### Community 74 - "Send Whats App Message Job"
Cohesion: 0.29
Nodes (5): SendFeeReminders, SendWhatsAppMessage, Illuminate\Contracts\Queue\ShouldQueue, Illuminate\Foundation\Bus\Dispatchable, Illuminate\Queue\InteractsWithQueue

### Community 77 - "Courses Page Portal UI"
Cohesion: 0.29
Nodes (5): AccommodationPage, CoursesPage, DashboardPage, StudentDataPage, Filament\Pages\Page

### Community 80 - "Inventory Module Guide Doc (2)"
Cohesion: 0.20
Nodes (10): branch_manager role, Three-place branch restriction, inventory_officer role, InventoryPermissionSeeder, Inventory production deploy steps, Pending Hostinger deploy tasks, inventory_checkout.create permission, inventory_checkout.return permission (+2 more)

### Community 81 - "Aws Evolution Setup Working Note"
Cohesion: 0.27
Nodes (10): Evolution API AWS agent setup prompt, Evolution API (Docker, WhatsApp), EVOLUTION_API_URL / EVOLUTION_API_TOKEN, Manager UI QR-scan flow, Port 8080 exposure vs HTTPS reverse proxy, POST /message/sendText/tenstrings-alerts, tenstrings-alerts instance, EC2 t3.small upgrade and redis container (+2 more)

### Community 84 - "Checkout Form Schema Inventory UI"
Cohesion: 0.33
Nodes (4): Filament\Forms\Components\Wizard\Step, Filament\Forms\Get, HtmlString, Illuminate\Support\HtmlString

### Community 86 - "Student Login Portal UI"
Cohesion: 0.31
Nodes (5): AdminLogin, StudentLogin, Filament\Forms\Components\TextInput, Filament\Pages\Auth\Login, TextInput

### Community 87 - "Debtors List Admin Page"
Cohesion: 0.28
Nodes (6): DebtorsList, Filament\Tables\Columns\BadgeColumn, Filament\Tables\Columns\ImageColumn, Filament\Tables\Columns\TextColumn, Filament\Tables\Concerns\InteractsWithTable, Filament\Tables\Contracts\HasTable

### Community 89 - "Branch Enrollment Doughnut Dashboard Widget (2)"
Cohesion: 0.47
Nodes (3): Filament\Widgets\ChartWidget, Illuminate\Contracts\Support\Htmlable, Illuminate\Support\Facades\DB

### Community 90 - "Branch Student Credentials Mail"
Cohesion: 0.28
Nodes (5): BranchStudentCredentialsMail, self, Illuminate\Mail\Mailable, Illuminate\Queue\SerializesModels, Illuminate\Support\Facades\Mail

### Community 91 - "Quarter Resolver Service"
Cohesion: 0.33
Nodes (4): QuarterResolver, Carbon\CarbonImmutable, CarbonImmutable, Illuminate\Database\DatabaseManager

### Community 92 - "Composer (3)"
Cohesion: 0.22
Nodes (9): require, barryvdh/laravel-dompdf, filament/filament, laravel/framework, laravel/sanctum, laravel/tinker, php, spatie/laravel-activitylog (+1 more)

### Community 93 - "Mobileapphandoff Agent Working Note"
Cohesion: 0.22
Nodes (9): Tenstrings Mobile App agent handoff, Academic and learning endpoints, Announcements and calendar endpoints, api/v1 REST surface, /auth endpoints (login, logout, me, refresh), HTTP client interceptors and 401 handling, Bearer-token-only Sanctum rule, Secure token storage directive (+1 more)

### Community 95 - "School Stats Overview Dashboard Widget"
Cohesion: 0.29
Nodes (4): SchoolStatsOverview, StudentImportQuickAction, Filament\Widgets\StatsOverviewWidget, Filament\Widgets\StatsOverviewWidget\Stat

### Community 97 - "Composer (4)"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pint, laravel/sail, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 98 - "Composer (5)"
Cohesion: 0.25
Nodes (8): post-root-package-install, setup, composer install, npm install, npm run build, @php artisan key:generate, @php artisan migrate --force, @php -r \"file_exists('.env') || copy('.env.example', '.env');\

### Community 104 - "Student Pdf Controller"
Cohesion: 0.48
Nodes (3): StudentPdfController, Barryvdh\DomPDF\Facade\Pdf, Illuminate\Http\Response

### Community 105 - "Composer (6)"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 111 - "App"
Cohesion: 0.40
Nodes (3): Illuminate\Foundation\Application, Illuminate\Foundation\Configuration\Exceptions, Illuminate\Foundation\Configuration\Middleware

### Community 112 - "Composer (7)"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 113 - "Logging Config"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 117 - "Composer (8)"
Cohesion: 0.50
Nodes (4): post-autoload-dump, Illuminate\\Foundation\\ComposerScripts::postAutoloadDump, @php artisan filament:upgrade, @php artisan package:discover --ansi

### Community 118 - "Composer (9)"
Cohesion: 0.50
Nodes (4): post-create-project-cmd, @php artisan key:generate --ansi, @php artisan migrate --graceful --ansi, @php -r \"file_exists('database/database.sqlite') || touch('database/database.sqlite');\

### Community 119 - "Sanctum Config"
Cohesion: 0.50
Nodes (3): Illuminate\Foundation\Http\Middleware\ValidateCsrfToken, Laravel\Sanctum\Http\Middleware\AuthenticateSession, Laravel\Sanctum\Sanctum

### Community 120 - "Create Permission Tables Migration"
Cohesion: 0.67
Nodes (3): down(), up(), Exception

### Community 121 - "Routes Public Doc"
Cohesion: 0.67
Nodes (4): Client, Closure Route Handler, GET / (unnamed, Closure), Routes (Public Route Map)

### Community 123 - "Output Working Note"
Cohesion: 0.50
Nodes (4): projectx-app container (PHP-FPM), projectx-db container (mysql:8.0), projectx-nginx container, projectx-redis container

### Community 124 - "Credit Card"
Cohesion: 0.50
Nodes (4): Credit Card Icon, Papers Icon, Printer Icon, Receipt Icon

### Community 125 - "Tenstrings Logo"
Cohesion: 0.83
Nodes (4): Tenstrings Logo Image Asset, Tenstrings Music Institute Brand Identity, Portal UI Branding Asset, Blue Shield and Treble Clef Emblem

### Community 127 - "Composer (10)"
Cohesion: 0.67
Nodes (3): keywords, framework, laravel

### Community 163 - "Placeholder Item"
Cohesion: 1.00
Nodes (3): Placeholder Item Image Asset, Outlined Camera Glyph on Dark Background, Missing Item Photo Fallback in Inventory UI

### Community 164 - "Tenstrings Logo1"
Cohesion: 1.00
Nodes (3): Tenstrings Brand Identity (Navy Shield, Treble Clef, Serif Wordmark), Tenstrings Music Institute Logo (Shield Mark), Portal Public Branding Asset

## Ambiguous Edges - Review These
- `Tenstrings Portal` → `Robots Crawler Policy (Allow All)`  [AMBIGUOUS]
  public/robots.txt · relation: conceptually_related_to
- `API/Filament Guard Separation` → `Robots Crawler Policy (Allow All)`  [AMBIGUOUS]
  public/robots.txt · relation: conceptually_related_to
- `Webhook verification flaw (hardcoded Paystack signature)` → `Photo evidence trail rule`  [AMBIGUOUS]
  prompts/PAYMENT_KNOWLEDGE.md · relation: semantically_similar_to
- `Messaging debtors on the Tenstrings student project` → `Hostinger-hosted portal (unconfirmed from AWS box)`  [AMBIGUOUS]
  prompts/conversation.md · relation: conceptually_related_to
- `Duplicate inventory_rooms rows created per CSV row` → `InventoryTransfer model and migration`  [AMBIGUOUS]
  prompts/ui_prompt.md · relation: conceptually_related_to

## Knowledge Gaps
- **128 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+123 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 695 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **50 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **What is the exact relationship between `Tenstrings Portal` and `Robots Crawler Policy (Allow All)`?**
  _Edge tagged AMBIGUOUS (relation: conceptually_related_to) - confidence is low._
- **What is the exact relationship between `API/Filament Guard Separation` and `Robots Crawler Policy (Allow All)`?**
  _Edge tagged AMBIGUOUS (relation: conceptually_related_to) - confidence is low._
- **What is the exact relationship between `Webhook verification flaw (hardcoded Paystack signature)` and `Photo evidence trail rule`?**
  _Edge tagged AMBIGUOUS (relation: semantically_similar_to) - confidence is low._
- **What is the exact relationship between `Messaging debtors on the Tenstrings student project` and `Hostinger-hosted portal (unconfirmed from AWS box)`?**
  _Edge tagged AMBIGUOUS (relation: conceptually_related_to) - confidence is low._
- **What is the exact relationship between `Duplicate inventory_rooms rows created per CSV row` and `InventoryTransfer model and migration`?**
  _Edge tagged AMBIGUOUS (relation: conceptually_related_to) - confidence is low._
- **Why does `User` connect `User Model & Panel Access` to `Inventory Filament Resources`, `Portal & LMS Controllers`, `TGIPay & Webhook Controllers`, `Student CSV Import Command`, `Inventory Room Model & Policy`, `Student Model & Observer`, `Feature Test Harness`, `Student Importer`, `Ajah Inventory Importer`, `Enrollment Rules & Policy`, `Core Domain Models`, `Instructor & Course Seeders`, `Instructor Resource`, `Inventory Audit Model & Policy`, `Branch Model & Test Builders`, `Checkout Model & Policy`, `Item Policy & Branch Access`, `Inventory Checkout Service`, `Activity Log Resource Admin UI`, `Lesson Model`, `Login Session Model`, `Inventory Category Model`, `Branch Enrollment Doughnut Dashboard Widget (2)`, `Course Module Model`, `Filament Import Policy`?**
  _High betweenness centrality (0.109) - this node is a cross-community bridge._
- **Why does `Student` connect `Student Model & Observer` to `Inventory Filament Resources`, `Payments Page & Portal Settings`, `User Model & Panel Access`, `TGIPay & Webhook Controllers`, `Student CSV Import Command`, `Branch Scoping & Activity Log`, `Feature Test Harness`, `Student Importer`, `Ajah Inventory Importer`, `Enrollment Rules & Policy`, `Core Domain Models`, `Instructor & Course Seeders`, `Instructor Resource`, `Branch Model & Test Builders`, `Grade Model`, `Copy Sqlite To Mysql Command`, `Branch Finance Chart Dashboard Widget`, `Finance Chart Dashboard Widget`, `Finance Ratio Chart Dashboard Widget`, `Branch Enrollment Doughnut Dashboard Widget`, `Send Whats App Message Job`, `Audit Students Csv Import Command`, `Student Login Portal UI`, `Debtors List Admin Page`, `Branch Enrollment Doughnut Dashboard Widget (2)`, `Branch Student Credentials Mail`, `School Stats Overview Dashboard Widget`, `Debtors Chart Widget Dashboard Widget`, `Student Pdf Controller`?**
  _High betweenness centrality (0.083) - this node is a cross-community bridge._
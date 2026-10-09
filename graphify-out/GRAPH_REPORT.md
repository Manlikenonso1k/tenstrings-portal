# Graph Report - tenstrings-portal  (2026-10-09)

## Corpus Check
- 385 files · ~86,482 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 2187 nodes · 4484 edges · 221 communities (92 shown, 44 thin omitted)
- Extraction: 96% EXTRACTED · 4% INFERRED · 0% AMBIGUOUS · INFERRED: 178 edges (avg confidence: 0.86)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `fe6c549e`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Filament\Tables\Table
- Illuminate\Http\Request
- Payment
- User
- Filament\Actions\Imports\Jobs\ImportCsv
- Invoice
- Filament\Resources\Pages\EditRecord
- Evolution API deployment audit on AWS
- Stateless Mobile API v1
- Filament\Resources\Pages\ListRecords
- StudentPanelProvider.php
- .handle
- CheckoutFormSchema
- InventoryRoom
- Student
- PaystackTitanGateway
- Hash
- Filament\Resources\Pages\CreateRecord
- InventoryRoomResource
- HostelPayment
- InventoryCheckoutPolicyTest
- TestCase
- InventoryItem
- devDependencies
- PaymentService
- ItemCondition
- InventoryCheckoutItem
- InventoryItemResource
- StudentImporter.php
- InventoryCheckoutResource
- ActivityLogResource.php
- Enrollment
- Illuminate\Database\Eloquent\Model
- Instructor
- InventoryItemReturnedBadly
- GradeResource
- InstructorResource
- InventoryAudit
- InventoryCheckoutServiceTest
- Branch
- UserResource
- Illuminate\Database\Eloquent\Relations\BelongsTo
- InventoryCheckout
- Course
- Illuminate\Database\Schema\Blueprint
- InventoryCheckoutService
- InventoryAuditResource
- Filament\Pages\Page
- RuntimeException
- composer.json
- inventory_items table
- InventoryCheckoutService
- EnrollmentResource
- Titan webhook/callback handler
- CourseModule
- Illuminate\Database\Migrations\Migration
- Three-layer isolation strategy
- CopySqliteToMysql
- Filament\Widgets\Widget
- ListInventoryItems.php
- StudentResource.php
- BranchResource
- StudentResource
- BranchFinanceChart
- FinanceChart
- FinanceRatioChart
- LoginSession
- Spatie inventory permission set
- InventoryCategory
- BranchEnrollmentDoughnut
- scripts
- Ajah inventory CSV import
- InventoryRoomPhotoTest
- Illuminate\Console\Command
- InventoryAuditLine
- InventoryCategoryResource
- InventoryItemResource (Filament)
- CourseShortcodeMappingResource
- Tenstrings course fee schedule
- Three-place branch restriction
- EVOLUTION_API_URL / EVOLUTION_API_TOKEN
- AuditStudentsCsvImport
- InventoryCheckoutException
- .returnSummary
- ImageResizer
- inventory_items table
- DebtorsList.php
- CourseResource
- Illuminate\Database\Eloquent\Builder
- StudentMatricMailer
- QuarterResolver
- require
- api/v1 REST surface
- CheckoutStatus
- SchoolStatsOverview.php
- Certificate
- require-dev
- setup
- RoomType
- ListInventoryCheckouts.php
- PortalSettingResource
- DebtorsChartWidget
- AssignmentResource
- AttendanceResource
- config
- ReturnStatus
- EventServiceProvider.php
- SendWhatsAppMessage queued job
- Attendance
- AuditStatus
- bootstrap/app.php
- psr-4
- logging.php
- static
- EnsureLessonModuleIsUnlocked.php
- Automated fee reminder to students
- post-autoload-dump
- post-create-project-cmd
- console.php
- 2026_09_04_105417_create_permission_tables.php
- GET / (unnamed, Closure)
- ExampleTest
- projectx-app container (PHP-FPM)
- Receipt Icon
- Tenstrings Logo Image Asset
- keywords
- Illuminate\Support\Facades\Schema
- Placeholder Item Image Asset
- Tenstrings Brand Identity (Navy Shield, Treble Clef, Serif Wordmark)
- CLAUDE.md
- courses.partials.module-accordion
- registerCourses
- student-core-info-page.blade.php
- student-documents-page.blade.php
- student-identity-page.blade.php
- student-password-page.blade.php

## God Nodes (most connected - your core abstractions)
1. `User` - 163 edges
2. `Student` - 109 edges
3. `InventoryItem` - 66 edges
4. `Payment` - 65 edges
5. `Course` - 58 edges
6. `InventoryCheckout` - 46 edges
7. `InventoryRoom` - 37 edges
8. `InventoryRoomResource` - 34 edges
9. `StudentCourseFee` - 33 edges
10. `Controller` - 28 edges

## Surprising Connections (you probably didn't know these)
- `Ajah inventory CSV import` --semantically_similar_to--> `student_import_template.csv`  [INFERRED] [semantically similar]
  docs/inventory-module-guide.md → prompts/handsoff.md
- `Student Profile Endpoints (/student)` --semantically_similar_to--> `Eloquent API Resource and Controller Sample`  [INFERRED] [semantically similar]
  README.md → instructions.md
- `Laravel Sanctum Bearer Token Auth` --semantically_similar_to--> `Sanctum Authentication Strategy`  [INFERRED] [semantically similar]
  README.md → instructions.md
- `Robots Crawler Policy (Allow All)` --conceptually_related_to--> `API/Filament Guard Separation`  [AMBIGUOUS]
  public/robots.txt → README.md
- `Robots Crawler Policy (Allow All)` --conceptually_related_to--> `Tenstrings Portal`  [AMBIGUOUS]
  public/robots.txt → README.md

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Event check-out and return evidence flow** — prompts_prompt_inventory_checkouts, prompts_prompt_inventory_checkout_items, prompts_prompt_inventory_checkout_photos, prompts_prompt_inventory_checkout_service, prompts_prompt_checkout_flow, prompts_prompt_return_flow, prompts_prompt_photo_evidence_rule, prompts_prompt_availability_rules [EXTRACTED 1.00]
- **Inventory CSV import failure flow (import 12)** — prompts_error_livewire_update_endpoint, prompts_error_filament_importcsv_job, prompts_error_filament_importer, prompts_error_tenstrings_office_name_csv_column, prompts_error_inventory_items, prompts_error_failed_import_rows, prompts_error_queryexception_unknown_column_tenstrings_office_name, prompts_error_duplicate_inventory_room_creation [EXTRACTED 1.00]
- **Inventory room grid plus transfer logging feature** — prompts_ui_prompt_inventoryitemresource, prompts_ui_prompt_inventoryroomresource, prompts_ui_prompt_content_grid_layout, prompts_ui_prompt_room_grouping_collapsed, prompts_ui_prompt_inventorytransfer, prompts_ui_prompt_transfer_action_modal, prompts_ui_prompt_no_blade_override_constraint [EXTRACTED 1.00]
- **Three-layer inventory officer isolation** — prompts_inventory_module_spec_isolation_strategy, prompts_inventory_module_spec_inventory_panel_provider, prompts_inventory_module_spec_can_access_panel, prompts_inventory_module_spec_scoped_to_branch, prompts_inventory_module_spec_access_matrix_test, docs_inventory_module_guide_branch_restriction [EXTRACTED 1.00]
- **Mobile API v1 Endpoint Surface** — readme_endpoint_auth, readme_endpoint_student_profile, readme_endpoint_courses, readme_endpoint_grades, readme_endpoint_attendance, readme_endpoint_payments, readme_endpoint_events [EXTRACTED 1.00]
- **Payment success reconciliation cascade** — prompts_payment_knowledge_payment_service, prompts_payment_knowledge_payments_table, prompts_payment_knowledge_student_course_fee, prompts_payment_knowledge_payment_advice, prompts_payment_knowledge_document_service, prompts_payment_knowledge_redundant_status_tracking [EXTRACTED 1.00]
- **Public Route Request Flow** — docs_graphs_routes_public_routes, docs_graphs_routes_public_client, docs_graphs_routes_public_route__, docs_graphs_routes_public_closure_handler [EXTRACTED 1.00]
- **Pricing and Data Integrity Improvements** — readme_dynamic_branch_pricing, readme_course_pricing_updates, readme_userroleseeder_overhashing_fix, readme_import_duplicate_resolution, readme_branch_model [INFERRED 0.75]
- **Payment and Document Icon Set (shared 96x96 circular-badge style)** — public_assets_icons_credit_card_credit_card, public_assets_icons_papers_papers, public_assets_icons_printer_printer, public_assets_icons_receipt_receipt [INFERRED 0.85]
- **Stateless Token Auth Coexisting with Filament Sessions** — readme_laravel_sanctum, readme_guard_separation, readme_multi_panel_architecture, instructions_stateless_constraint, instructions_authentication_strategy, instructions_mobile_token_storage [INFERRED 0.85]
- **WhatsApp fee-reminder delivery stack (Evolution API on AWS)** — prompts_automatedmssg_send_fee_reminders_command, prompts_automatedmssg_sendwhatsappmessage_job, prompts_automatedmssg_message_sendtext_endpoint, prompts_automatedmssg_staggered_cumulative_delay, prompts_output_evolution_api_container, prompts_conversation_tenstrings_alerts_instance, prompts_conversation_empty_contact_table [INFERRED 0.85]

## Communities (221 total, 44 thin omitted)

### Community 0 - "Filament\Tables\Table"
Cohesion: 0.06
Nodes (41): App\Filament\Inventory\Resources\InventoryAuditResource\Pages, App\Filament\Inventory\Resources\InventoryCategoryResource\Pages, LinesRelationManager, App\Filament\Inventory\Resources\InventoryItemResource\Pages, App\Filament\Inventory\Resources\InventoryItemResource\RelationManagers, App\Filament\Inventory\Resources\InventoryRoomResource\Pages, App\Filament\Inventory\Resources\InventoryRoomResource\RelationManagers, StudentCoreInfoPage (+33 more)

### Community 1 - "Illuminate\Http\Request"
Cohesion: 0.09
Nodes (14): AnnouncementController, AttendanceController, AuthController, CalendarController, CourseController, GradeController, PaymentApiController, StudentProfileController (+6 more)

### Community 2 - "Payment"
Cohesion: 0.08
Nodes (13): PaymentsPage, FeeWorkflowController, TgiPayController, WebhookController, Payment, PaymentAdvice, PortalSetting, StudentCourseFee (+5 more)

### Community 3 - "User"
Cohesion: 0.06
Nodes (11): User, CoursePolicy, HostelPaymentPolicy, PaymentPolicy, StudentPolicy, Filament\Models\Contracts\FilamentUser, Filament\Models\Contracts\HasAvatar, Illuminate\Foundation\Auth\User (+3 more)

### Community 4 - "Filament\Actions\Imports\Jobs\ImportCsv"
Cohesion: 0.15
Nodes (15): CSV number import as contact-sourcing fallback, cache_locks queue overlap lock for ImportCsv, CSV helper fields leaking into Eloquent insert, Duplicate login_sessions insert on a single request, failed_import_rows table, Filament\Actions\Imports\Jobs\ImportCsv, Filament\Actions\Imports\Importer, imports table (import id 12) (+7 more)

### Community 5 - "Invoice"
Cohesion: 0.14
Nodes (5): PaymentController, Invoice, DocumentService, Illuminate\Support\Facades\Storage, Symfony\Component\HttpFoundation\BinaryFileResponse

### Community 6 - "Filament\Resources\Pages\EditRecord"
Cohesion: 0.10
Nodes (12): EditInventoryAudit, EditInventoryCategory, EditInventoryItem, EditAssignment, EditAttendance, EditBranch, EditCourse, EditInstructor (+4 more)

### Community 7 - "Evolution API deployment audit on AWS"
Cohesion: 0.21
Nodes (13): Evolution API docker-compose deployment, Evolution API (unofficial WhatsApp API), Evolution API global API token, Evolution API /message/sendText endpoint, WhatsApp QR instance pairing via Manager dashboard, Evolution API deployment audit on AWS, Evolution API Manager UI (127.0.0.1:8080/manager), WhatsApp instance 'tenstrings-01' (+5 more)

### Community 8 - "Stateless Mobile API v1"
Cohesion: 0.09
Nodes (35): API Integration Blueprint Brief, Eloquent API Resource and Controller Sample, API Route Blueprint, Sanctum Authentication Strategy, Hand-Written Controllers vs Filament REST Plugin, Hostinger Hosting Target, Mobile Token Storage and Fetch Pattern, Stateless API vs Stateful Filament Constraint (+27 more)

### Community 9 - "Filament\Resources\Pages\ListRecords"
Cohesion: 0.08
Nodes (10): ListActivityLogs, ListBranches, ListCourseShortcodeMappings, ListEnrollments, ListGrades, LoginSessionResource, ListLoginSessions, ListPayments (+2 more)

### Community 10 - "StudentPanelProvider.php"
Cohesion: 0.07
Nodes (34): AdminDashboard, AdminLogin, AccommodationPage, StudentLogin, DashboardPage, StudentDataPage, AdminPanelProvider, InstructorPanelProvider (+26 more)

### Community 11 - ".handle"
Cohesion: 0.06
Nodes (5): ImportStudentsFromCsv, Carbon, StudentImporter, CourseCatalog, Illuminate\Support\Carbon

### Community 12 - "CheckoutFormSchema"
Cohesion: 0.16
Nodes (5): CheckoutActions, Action, CheckoutFormSchema, Collection, BulkAction

### Community 13 - "InventoryRoom"
Cohesion: 0.14
Nodes (6): InventoryRoom, InventoryRoomObserver, InventoryRoomPolicy, AppServiceProvider, Illuminate\Support\Facades\Gate, Illuminate\Support\ServiceProvider

### Community 14 - "Student"
Cohesion: 0.10
Nodes (9): QuarterlyIntakeAnalytics, StudentPdfController, LogOptions, Student, StudentObserver, MatricNumberGenerator, Barryvdh\DomPDF\Facade\Pdf, Carbon\Carbon (+1 more)

### Community 15 - "PaystackTitanGateway"
Cohesion: 0.13
Nodes (5): PaymentGatewayInterface, PaystackTitanGateway, TgiPayGateway, Illuminate\Support\Arr, Illuminate\Support\Facades\Http

### Community 17 - "Filament\Resources\Pages\CreateRecord"
Cohesion: 0.14
Nodes (9): CreateInventoryCategory, CreateInventoryItem, CreateAssignment, CreateAttendance, CreateCourse, CreateInstructor, CreatePortalSetting, CreateUser (+1 more)

### Community 18 - "InventoryRoomResource"
Cohesion: 0.11
Nodes (5): InventoryRoomResource, CreateInventoryRoom, EditInventoryRoom, Action, FileUpload

### Community 19 - "HostelPayment"
Cohesion: 0.10
Nodes (11): ScopedToBranch, HostelPayment, LogOptions, LogOptions, LogOptions, LogOptions, LogOptions, Illuminate\Database\Eloquent\SoftDeletes (+3 more)

### Community 21 - "TestCase"
Cohesion: 0.16
Nodes (9): Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Foundation\Testing\TestCase, AccountsClerkAccessTest, ExampleTest, HostelPaymentAccessTest, InventoryPanelIsolationTest, PaymentEvidenceApprovalTest, StudentActivityLogTest (+1 more)

### Community 22 - "InventoryItem"
Cohesion: 0.09
Nodes (5): InventoryItem, InventoryItemObserver, InventoryItemPolicy, AssetTagGenerator, Illuminate\Database\Eloquent\Relations\HasMany

### Community 23 - "devDependencies"
Cohesion: 0.10
Nodes (20): axios, concurrently, laravel-vite-plugin, devDependencies, axios, concurrently, laravel-vite-plugin, tailwindcss (+12 more)

### Community 24 - "PaymentService"
Cohesion: 0.13
Nodes (21): WhatsApp fee reminders agent handoff, Automated WhatsApp fee reminders, /payments mobile endpoints, Payment System Knowledge Base, 200 OK on failed signature, Webhook CSRF exclusion, DocumentService::generateReceiptPdf, FeeWorkflowController (+13 more)

### Community 28 - "StudentImporter.php"
Cohesion: 0.09
Nodes (7): AjahInventoryImporter, FilamentImportPolicy, Filament\Actions\Imports\Exceptions\RowImportFailedException, Filament\Actions\Imports\ImportColumn, Filament\Actions\Imports\Importer, Filament\Actions\Imports\Models\Import, Illuminate\Support\Str

### Community 29 - "InventoryCheckoutResource"
Cohesion: 0.10
Nodes (4): InventoryCheckoutResource, CheckoutHistoryRelationManager, Livewire\Livewire, InventoryPanelPagesTest

### Community 30 - "ActivityLogResource.php"
Cohesion: 0.05
Nodes (16): ViewInventoryCheckout, ViewInventoryRoom, ActivityLogResource, App\Filament\Resources\ActivityLogResource\Pages, ViewActivityLog, CreatePayment, EditPayment, PaymentResource (+8 more)

### Community 31 - "Enrollment"
Cohesion: 0.15
Nodes (4): Enrollment, EnrollmentPolicy, EnrollmentLimitService, EnrollmentLimitTest

### Community 32 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.11
Nodes (8): Announcement, Assignment, AssignmentSubmission, CalendarEvent, CourseShortcodeMapping, Message, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Database\Eloquent\Model

### Community 33 - "Instructor"
Cohesion: 0.16
Nodes (7): Instructor, CourseSeeder, DatabaseSeeder, DemoSeeder, UserRoleSeeder, Illuminate\Database\Console\Seeds\WithoutModelEvents, Illuminate\Database\Seeder

### Community 34 - "InventoryItemReturnedBadly"
Cohesion: 0.15
Nodes (9): SendWhatsAppMessage, InventoryCheckoutOverdue, InventoryItemReturnedBadly, Illuminate\Bus\Queueable, Illuminate\Contracts\Queue\ShouldQueue, Illuminate\Foundation\Bus\Dispatchable, Illuminate\Notifications\Notification, Illuminate\Queue\InteractsWithQueue (+1 more)

### Community 35 - "GradeResource"
Cohesion: 0.13
Nodes (4): GradeResource, CreateGrade, EditGrade, GradeCalculator

### Community 37 - "InventoryAudit"
Cohesion: 0.12
Nodes (4): InventoryAudit, Collection, ChecksBranchAccess, InventoryAuditPolicy

### Community 39 - "Branch"
Cohesion: 0.19
Nodes (3): Branch, self, BuildsInventory

### Community 40 - "UserResource"
Cohesion: 0.12
Nodes (4): EditUser, ListUsers, UserResource, Spatie\Permission\Models\Role

### Community 41 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.12
Nodes (4): InventoryCheckoutPhoto, InventoryMovement, InventoryTransfer, Illuminate\Database\Eloquent\Relations\BelongsTo

### Community 43 - "Course"
Cohesion: 0.12
Nodes (6): CourseRegistrationPage, StudentRegistrationController, LessonController, Course, FeeCalculationService, Illuminate\Support\Facades\Route

### Community 45 - "InventoryCheckoutService"
Cohesion: 0.23
Nodes (3): PhotoStage, InventoryCheckoutService, Illuminate\Support\Collection

### Community 47 - "Filament\Pages\Page"
Cohesion: 0.14
Nodes (6): ImportStudentsCsv, CoursesPage, ResultsPage, StudentAcademicPage, Grade, Filament\Pages\Page

### Community 48 - "RuntimeException"
Cohesion: 0.21
Nodes (5): DeletePaymentAndReconcile, PaymentEvidenceApprovalService, Illuminate\Support\Facades\Cache, Illuminate\Support\Facades\Mail, RuntimeException

### Community 49 - "composer.json"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, extra, laravel, dont-discover, license, minimum-stability (+5 more)

### Community 50 - "inventory_items table"
Cohesion: 0.21
Nodes (14): Inventory acceptance checklist, Role access matrix feature test, Asset tag generation (TS-{BRANCH}-{CAT}-{0001}), branches table, CategoryResource (Filament), Denormalised branch_id on items, inventory_audit_lines table, inventory_audits table (+6 more)

### Community 51 - "InventoryCheckoutService"
Cohesion: 0.20
Nodes (14): Webhook verification flaw (hardcoded Paystack signature), Checkout acceptance checklist, Checkout availability rules, checked_out item status, Check-out wizard flow, Item checkout history relation manager, Event checkouts page, inventory_checkout_items table (+6 more)

### Community 52 - "EnrollmentResource"
Cohesion: 0.18
Nodes (3): EnrollmentResource, CreateEnrollment, EditEnrollment

### Community 53 - "Titan webhook/callback handler"
Cohesion: 0.21
Nodes (13): Beach event ticketing system (target project), config/services.php gateway config keys, Webhook CSRF exclusion and retry gotchas, Dummy-credential rule for extracted config keys, Payment model / payments table, PAYMENT_KNOWLEDGE.md deliverable, Payment routes (routes/web.php, routes/api.php), Paystack payment gateway (+5 more)

### Community 54 - "CourseModule"
Cohesion: 0.19
Nodes (4): CourseModule, self, Lesson, Illuminate\Database\Eloquent\Relations\BelongsToMany

### Community 56 - "Illuminate\Database\Migrations\Migration"
Cohesion: 0.19
Nodes (4): CreateActivityLogTable, AddEventColumnToActivityLogTable, AddBatchUuidColumnToActivityLogTable, Illuminate\Database\Migrations\Migration

### Community 57 - "Three-layer isolation strategy"
Cohesion: 0.18
Nodes (13): Inventory Module Implementation Guide, /admin/users/create route, ceo role (read-only, all branches, costs), /inventory/login route, /inventory panel route, super_admin role, Inventory Module + Scoped Inventory Officer Role spec, User::canAccessPanel() (+5 more)

### Community 59 - "Filament\Widgets\Widget"
Cohesion: 0.36
Nodes (3): ActiveImportTracker, ImportProgressWidget, Filament\Widgets\Widget

### Community 60 - "ListInventoryItems.php"
Cohesion: 0.16
Nodes (4): ListInventoryItems, ListInventoryRooms, ViewToggle, Filament\Actions\Action

### Community 61 - "StudentResource.php"
Cohesion: 0.16
Nodes (14): App\Filament\Resources\StudentResource\Pages, Filament\Facades\Filament, Filament\Forms\Components\FileUpload, Filament\Forms\Components\Section, Filament\Forms\Components\Select, Filament\Forms\Components\Textarea, Filament\Forms\Components\Toggle, Filament\Forms\Set (+6 more)

### Community 63 - "StudentResource"
Cohesion: 0.09
Nodes (6): EditStudentCore, EditStudentDocuments, EditStudentIdentity, ViewStudent, ViewStudentAcademic, StudentResource

### Community 67 - "LoginSession"
Cohesion: 0.38
Nodes (3): StoreLogoutSession, LoginSession, Illuminate\Auth\Events\Logout

### Community 68 - "Spatie inventory permission set"
Cohesion: 0.20
Nodes (12): Activity-log audit trail, InventoryOverview dashboard, Spatie inventory permission set, inventory.view_all_branches permission, inventory.view_costs permission, ItemResource (Filament), needs_attention filter, Permissions deliberately withheld from the officer (+4 more)

### Community 69 - "InventoryCategory"
Cohesion: 0.13
Nodes (5): InventoryCategory, InventoryCategoryPolicy, InventoryPermissionSeeder, Spatie\Permission\Models\Permission, Spatie\Permission\PermissionRegistrar

### Community 71 - "scripts"
Cohesion: 0.18
Nodes (11): scripts, dev, post-update-cmd, pre-package-uninstall, test, Composer\\Config::disableProcessTimeout, Illuminate\\Foundation\\ComposerScripts::prePackageUninstall, npx concurrently -c \"#93c5fd,#c4b5fd,#fb7185,#fdba74\" \"php artisan serve\" \"php artisan queue:listen --tries=1 --timeout=0\" \"php artisan pail --timeout=0\" \"npm run dev\" --names=server,queue,logs,vite --kill-others (+3 more)

### Community 72 - "Ajah inventory CSV import"
Cohesion: 0.20
Nodes (11): CSV header mapping (TENSTRINGS OFFICE NAME, ITEM CODE, ITEM), Equipment photo and notes upload, Ajah inventory CSV import, Leading quantity parsing ("6 chairs"), student_import_template.csv, AuditResource (Filament), Anti-duplicate CSV importer pattern, item.import permission (+3 more)

### Community 74 - "Illuminate\Console\Command"
Cohesion: 0.18
Nodes (5): CorrectPaymentAmount, MarkInventoryCheckoutsOverdue, RelocateInventoryPhotos, SendFeeReminders, Illuminate\Console\Command

### Community 77 - "InventoryItemResource (Filament)"
Cohesion: 0.20
Nodes (11): Payment knowledge extraction task (read-only agent brief), Read-only extraction constraint, /inventory/inventory-items admin page, 4-column contentGrid item layout, FilamentView::registerRenderHook() scoped CSS escape hatch, FileUpload::make('image') on room form, InventoryItemResource (Filament), InventoryRoomResource (Filament) (+3 more)

### Community 79 - "Tenstrings course fee schedule"
Cohesion: 0.18
Nodes (11): Advanced diploma programmes (1,800,000), Tenstrings course fee schedule, Course model, Diploma programmes (performance, audio tech & production), Gospel music course (3 / 6 months), Luxury Centre pricing tier, Music performance (6 months), Music production (6 months) (+3 more)

### Community 80 - "Three-place branch restriction"
Cohesion: 0.20
Nodes (10): branch_manager role, Three-place branch restriction, inventory_officer role, InventoryPermissionSeeder, Inventory production deploy steps, Pending Hostinger deploy tasks, inventory_checkout.create permission, inventory_checkout.return permission (+2 more)

### Community 81 - "EVOLUTION_API_URL / EVOLUTION_API_TOKEN"
Cohesion: 0.27
Nodes (10): Evolution API AWS agent setup prompt, Evolution API (Docker, WhatsApp), EVOLUTION_API_URL / EVOLUTION_API_TOKEN, Manager UI QR-scan flow, Port 8080 exposure vs HTTPS reverse proxy, POST /message/sendText/tenstrings-alerts, tenstrings-alerts instance, EC2 t3.small upgrade and redis container (+2 more)

### Community 86 - "inventory_items table"
Cohesion: 0.22
Nodes (11): activity_log table (spatie activitylog), Asset tag scheme TS-<office>-<sequence>, Duplicate inventory_rooms rows created per CSV row, Case-insensitive duplicate item guard (LOWER(name) per room), inventory_categories table (slug lookup), inventory_items table, inventory_rooms table, InventoryRoom model (+3 more)

### Community 87 - "DebtorsList.php"
Cohesion: 0.28
Nodes (6): DebtorsList, Filament\Tables\Columns\BadgeColumn, Filament\Tables\Columns\ImageColumn, Filament\Tables\Columns\TextColumn, Filament\Tables\Concerns\InteractsWithTable, Filament\Tables\Contracts\HasTable

### Community 89 - "Illuminate\Database\Eloquent\Builder"
Cohesion: 0.20
Nodes (6): SystemActivityLineChart, DateTimeInterface, Filament\Widgets\ChartWidget, Illuminate\Contracts\Support\Htmlable, Illuminate\Database\Eloquent\Builder, Illuminate\Support\Facades\DB

### Community 90 - "StudentMatricMailer"
Cohesion: 0.17
Nodes (5): CreateStudent, BranchStudentCredentialsMail, self, StudentMatricMailer, Illuminate\Mail\Mailable

### Community 91 - "QuarterResolver"
Cohesion: 0.33
Nodes (4): QuarterResolver, Carbon\CarbonImmutable, CarbonImmutable, Illuminate\Database\DatabaseManager

### Community 92 - "require"
Cohesion: 0.22
Nodes (9): require, barryvdh/laravel-dompdf, filament/filament, laravel/framework, laravel/sanctum, laravel/tinker, php, spatie/laravel-activitylog (+1 more)

### Community 93 - "api/v1 REST surface"
Cohesion: 0.22
Nodes (9): Tenstrings Mobile App agent handoff, Academic and learning endpoints, Announcements and calendar endpoints, api/v1 REST surface, /auth endpoints (login, logout, me, refresh), HTTP client interceptors and 401 handling, Bearer-token-only Sanctum rule, Secure token storage directive (+1 more)

### Community 94 - "CheckoutStatus"
Cohesion: 0.15
Nodes (4): CheckoutStatus, App\Filament\Inventory\Resources\InventoryCheckoutResource\Pages, App\Filament\Inventory\Resources\InventoryCheckoutResource\RelationManagers, Illuminate\Support\Facades\Notification

### Community 95 - "SchoolStatsOverview.php"
Cohesion: 0.29
Nodes (4): SchoolStatsOverview, StudentImportQuickAction, Filament\Widgets\StatsOverviewWidget, Filament\Widgets\StatsOverviewWidget\Stat

### Community 97 - "require-dev"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pint, laravel/sail, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 98 - "setup"
Cohesion: 0.25
Nodes (8): post-root-package-install, setup, composer install, npm install, npm run build, @php artisan key:generate, @php artisan migrate --force, @php -r \"file_exists('.env') || copy('.env.example', '.env');\

### Community 105 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 107 - "EventServiceProvider.php"
Cohesion: 0.32
Nodes (4): StoreLoginSession, EventServiceProvider, Illuminate\Auth\Events\Login, Illuminate\Foundation\Support\Providers\EventServiceProvider

### Community 108 - "SendWhatsAppMessage queued job"
Cohesion: 0.36
Nodes (8): Anti-ban messaging architecture, Filament admin panel (student creation trigger), Graceful try/catch around the Evolution API call, app:send-fee-reminders Artisan command, SendWhatsAppMessage queued job, Staff registration alert on new student, Staggered cumulative randomized delay (5-10s), Student model

### Community 111 - "bootstrap/app.php"
Cohesion: 0.40
Nodes (3): Illuminate\Foundation\Application, Illuminate\Foundation\Configuration\Exceptions, Illuminate\Foundation\Configuration\Middleware

### Community 112 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 113 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 114 - "static"
Cohesion: 0.14
Nodes (5): FileUpload, self, UserFactory, Illuminate\Database\Eloquent\Factories\Factory, static

### Community 115 - "EnsureLessonModuleIsUnlocked.php"
Cohesion: 0.60
Nodes (3): EnsureLessonModuleIsUnlocked, Closure, Symfony\Component\HttpFoundation\Response

### Community 116 - "Automated fee reminder to students"
Cohesion: 0.40
Nodes (5): Automated fee reminder to students, DATABASE_SAVE_DATA_CONTACTS=false, Messaging debtors on the Tenstrings student project, Empty Contact table (0 stored numbers), Hostinger-hosted portal (unconfirmed from AWS box)

### Community 117 - "post-autoload-dump"
Cohesion: 0.50
Nodes (4): post-autoload-dump, Illuminate\\Foundation\\ComposerScripts::postAutoloadDump, @php artisan filament:upgrade, @php artisan package:discover --ansi

### Community 118 - "post-create-project-cmd"
Cohesion: 0.50
Nodes (4): post-create-project-cmd, @php artisan key:generate --ansi, @php artisan migrate --graceful --ansi, @php -r \"file_exists('database/database.sqlite') || touch('database/database.sqlite');\

### Community 119 - "console.php"
Cohesion: 0.50
Nodes (3): Illuminate\Foundation\Inspiring, Illuminate\Support\Facades\Artisan, Illuminate\Support\Facades\Schedule

### Community 120 - "2026_09_04_105417_create_permission_tables.php"
Cohesion: 0.67
Nodes (3): down(), up(), Exception

### Community 121 - "GET / (unnamed, Closure)"
Cohesion: 0.67
Nodes (4): Client, Closure Route Handler, GET / (unnamed, Closure), Routes (Public Route Map)

### Community 123 - "projectx-app container (PHP-FPM)"
Cohesion: 0.50
Nodes (4): projectx-app container (PHP-FPM), projectx-db container (mysql:8.0), projectx-nginx container, projectx-redis container

### Community 124 - "Receipt Icon"
Cohesion: 0.50
Nodes (4): Credit Card Icon, Papers Icon, Printer Icon, Receipt Icon

### Community 125 - "Tenstrings Logo Image Asset"
Cohesion: 0.83
Nodes (4): Tenstrings Logo Image Asset, Tenstrings Music Institute Brand Identity, Portal UI Branding Asset, Blue Shield and Treble Clef Emblem

### Community 127 - "keywords"
Cohesion: 0.67
Nodes (3): keywords, framework, laravel

### Community 163 - "Placeholder Item Image Asset"
Cohesion: 1.00
Nodes (3): Placeholder Item Image Asset, Outlined Camera Glyph on Dark Background, Missing Item Photo Fallback in Inventory UI

### Community 164 - "Tenstrings Brand Identity (Navy Shield, Treble Clef, Serif Wordmark)"
Cohesion: 1.00
Nodes (3): Tenstrings Brand Identity (Navy Shield, Treble Clef, Serif Wordmark), Tenstrings Music Institute Logo (Shield Mark), Portal Public Branding Asset

## Ambiguous Edges - Review These
- `Duplicate inventory_rooms rows created per CSV row` → `InventoryTransfer model and migration`  [AMBIGUOUS]
  prompts/ui_prompt.md · relation: conceptually_related_to
- `Webhook verification flaw (hardcoded Paystack signature)` → `Photo evidence trail rule`  [AMBIGUOUS]
  prompts/PAYMENT_KNOWLEDGE.md · relation: semantically_similar_to
- `Messaging debtors on the Tenstrings student project` → `Hostinger-hosted portal (unconfirmed from AWS box)`  [AMBIGUOUS]
  prompts/conversation.md · relation: conceptually_related_to
- `Robots Crawler Policy (Allow All)` → `API/Filament Guard Separation`  [AMBIGUOUS]
  public/robots.txt · relation: conceptually_related_to
- `Robots Crawler Policy (Allow All)` → `Tenstrings Portal`  [AMBIGUOUS]
  public/robots.txt · relation: conceptually_related_to

## Knowledge Gaps
- **129 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+124 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 702 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **44 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **What is the exact relationship between `Duplicate inventory_rooms rows created per CSV row` and `InventoryTransfer model and migration`?**
  _Edge tagged AMBIGUOUS (relation: conceptually_related_to) - confidence is low._
- **What is the exact relationship between `Webhook verification flaw (hardcoded Paystack signature)` and `Photo evidence trail rule`?**
  _Edge tagged AMBIGUOUS (relation: semantically_similar_to) - confidence is low._
- **What is the exact relationship between `Messaging debtors on the Tenstrings student project` and `Hostinger-hosted portal (unconfirmed from AWS box)`?**
  _Edge tagged AMBIGUOUS (relation: conceptually_related_to) - confidence is low._
- **What is the exact relationship between `Robots Crawler Policy (Allow All)` and `API/Filament Guard Separation`?**
  _Edge tagged AMBIGUOUS (relation: conceptually_related_to) - confidence is low._
- **What is the exact relationship between `Robots Crawler Policy (Allow All)` and `Tenstrings Portal`?**
  _Edge tagged AMBIGUOUS (relation: conceptually_related_to) - confidence is low._
- **Why does `User` connect `User` to `Filament\Tables\Table`, `Illuminate\Http\Request`, `Payment`, `StudentPanelProvider.php`, `.handle`, `InventoryRoom`, `Student`, `TestCase`, `InventoryItem`, `StudentImporter.php`, `ActivityLogResource.php`, `Enrollment`, `Illuminate\Database\Eloquent\Model`, `Instructor`, `InventoryAudit`, `Branch`, `InventoryCheckout`, `Course`, `InventoryCheckoutService`, `RuntimeException`, `CourseModule`, `StudentResource.php`, `LoginSession`, `InventoryCategory`, `Illuminate\Console\Command`, `Illuminate\Database\Eloquent\Builder`, `EventServiceProvider.php`?**
  _High betweenness centrality (0.115) - this node is a cross-community bridge._
- **Why does `Student` connect `Student` to `Filament\Tables\Table`, `Payment`, `User`, `StudentPanelProvider.php`, `.handle`, `Hash`, `HostelPayment`, `TestCase`, `StudentImporter.php`, `ActivityLogResource.php`, `Enrollment`, `Illuminate\Database\Eloquent\Model`, `Instructor`, `Branch`, `Course`, `Filament\Pages\Page`, `StudentResource.php`, `BranchFinanceChart`, `FinanceChart`, `FinanceRatioChart`, `BranchEnrollmentDoughnut`, `Illuminate\Console\Command`, `AuditStudentsCsvImport`, `DebtorsList.php`, `Illuminate\Database\Eloquent\Builder`, `StudentMatricMailer`, `SchoolStatsOverview.php`, `DebtorsChartWidget`, `EventServiceProvider.php`?**
  _High betweenness centrality (0.087) - this node is a cross-community bridge._
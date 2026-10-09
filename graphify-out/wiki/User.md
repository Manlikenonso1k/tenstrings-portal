# User

> God node · 137 connections · `app/Models/User.php`

**Community:** [User Model & Panel Access](User_Model_&_Panel_Access.md)

## Connections by Relation

### calls
- .handle() `EXTRACTED`
- .store() `EXTRACTED`
- .getHeaderActions() `EXTRACTED`
- .beforeSave() `EXTRACTED`
- .item() `EXTRACTED`
- .login() `EXTRACTED`
- .createUserAccount() `EXTRACTED`
- .run() `EXTRACTED`
- .generateUniquePlaceholderEmail() `EXTRACTED`
- .ensureImportEmail() `EXTRACTED`
- .branchWatchers() `EXTRACTED`
- .run() `EXTRACTED`
- .beforeValidate() `EXTRACTED`
- .userWithRole() `EXTRACTED`
- .test_inventory_officer_is_forbidden_from_admin_and_non_inventory_resources() `EXTRACTED`

### contains
- User.php `EXTRACTED`

### implements
- Filament\Models\Contracts\FilamentUser `EXTRACTED`
- Filament\Models\Contracts\HasAvatar `EXTRACTED`

### imports
- ViewStudent.php `EXTRACTED`
- AppServiceProvider.php `EXTRACTED`
- StudentRegistrationController.php `EXTRACTED`
- StudentImporter.php `EXTRACTED`
- ImportStudentsFromCsv.php `EXTRACTED`
- ActivityLogResource.php `EXTRACTED`
- InventoryCheckoutService.php `EXTRACTED`
- StudentPasswordPage.php `EXTRACTED`
- UserResource.php `EXTRACTED`
- BuildsInventory.php `EXTRACTED`
- LoginSessionResource.php `EXTRACTED`
- SystemActivityLineChart.php `EXTRACTED`
- AuthController.php `EXTRACTED`
- StudentObserver.php `EXTRACTED`
- DemoSeeder.php `EXTRACTED`
- UserRoleSeeder.php `EXTRACTED`
- StoreLoginSession.php `EXTRACTED`
- StoreLogoutSession.php `EXTRACTED`
- InventoryCheckoutPolicy.php `EXTRACTED`
- InventoryPanelIsolationTest.php `EXTRACTED`
- *…and 10 more `imports` connection(s) not listed (lowest-degree first to go)*

### inherits
- Illuminate\Foundation\Auth\User `EXTRACTED`

### method
- .completedLessons() `EXTRACTED`
- .canAccessModule() `EXTRACTED`
- .canAccessLesson() `EXTRACTED`
- .hasCompletedAllLessonsInModule() `EXTRACTED`
- .unlockedModuleIdsForCourse() `EXTRACTED`
- .canAccessPanel() `EXTRACTED`
- .isStudent() `EXTRACTED`
- .student() `EXTRACTED`
- .instructor() `EXTRACTED`
- .loginSessions() `EXTRACTED`
- .payments() `EXTRACTED`
- .casts() `EXTRACTED`
- .getFilamentAvatarUrl() `EXTRACTED`
- .isSuperAdmin() `EXTRACTED`
- .isAdmin() `EXTRACTED`
- .isAccountsClerk() `EXTRACTED`
- .isInstructor() `EXTRACTED`

### mixes_in
- Illuminate\Database\Eloquent\Factories\HasFactory `EXTRACTED`
- Illuminate\Notifications\Notifiable `EXTRACTED`
- Laravel\Sanctum\HasApiTokens `EXTRACTED`
- Spatie\Permission\Traits\HasRoles `EXTRACTED`

### references
- .returnItems() `EXTRACTED`
- .checkout() `EXTRACTED`
- .storePhotos() `EXTRACTED`
- .delete() `EXTRACTED`
- .delete() `EXTRACTED`
- .forceDelete() `EXTRACTED`
- .restore() `EXTRACTED`
- .forceDelete() `EXTRACTED`
- .restore() `EXTRACTED`
- .isUnlockedFor() `EXTRACTED`
- .delete() `EXTRACTED`
- .update() `EXTRACTED`
- .view() `EXTRACTED`
- .delete() `EXTRACTED`
- .update() `EXTRACTED`
- .view() `EXTRACTED`
- .view() `EXTRACTED`
- .complete() `EXTRACTED`
- .delete() `EXTRACTED`
- .update() `EXTRACTED`
- *…and 47 more `references` connection(s) not listed (lowest-degree first to go)*

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*
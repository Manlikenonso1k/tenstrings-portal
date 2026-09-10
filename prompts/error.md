# Illuminate\Database\QueryException - Internal Server Error

SQLSTATE[42S22]: Column not found: 1054 Unknown column 'tenstrings_office_name' in 'INSERT INTO' (Connection: mysql, Host: 127.0.0.1, Port: 3306, Database: u519226541_portal_tenstri, SQL: insert into `inventory_items` (`tenstrings_office_name`, `item_code`, `item`, `branch_id`, `inventory_room_id`, `inventory_category_id`, `name`, `asset_tag`, `quantity`, `unit`, `condition`, `status`, `created_by`, `notes`, `updated_at`, `created_at`) values (LIVE STUDIO, ?, 19 speakers and CD display, 1, 91, 5, speakers and CD display, TS-AJA-AUD-0001, 19, unit, good, in_use, 410, Imported inventory CSV. Source office: LIVE STUDIO, 2026-09-04 13:10:16, 2026-09-04 13:10:16))

PHP 8.3.30
Laravel 12.53.0
portal.tenstrings.org

## Stack Trace

0 - vendor/laravel/framework/src/Illuminate/Database/Connection.php:838
1 - vendor/laravel/framework/src/Illuminate/Database/Connection.php:794
2 - vendor/laravel/framework/src/Illuminate/Database/MySqlConnection.php:42
3 - vendor/laravel/framework/src/Illuminate/Database/Query/Processors/MySqlProcessor.php:35
4 - vendor/laravel/framework/src/Illuminate/Database/Query/Builder.php:4140
5 - vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php:2235
6 - vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php:1436
7 - vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php:1401
8 - vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php:1240
9 - vendor/filament/actions/src/Imports/Importer.php:251
10 - vendor/filament/actions/src/Imports/Importer.php:75
11 - vendor/filament/actions/src/Imports/Jobs/ImportCsv.php:86
12 - vendor/laravel/framework/src/Illuminate/Database/Concerns/ManagesTransactions.php:35
13 - vendor/laravel/framework/src/Illuminate/Database/DatabaseManager.php:491
14 - vendor/laravel/framework/src/Illuminate/Support/Facades/Facade.php:363
15 - vendor/filament/actions/src/Imports/Jobs/ImportCsv.php:86
16 - vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php:36
17 - vendor/laravel/framework/src/Illuminate/Container/Util.php:43
18 - vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php:96
19 - vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php:35
20 - vendor/laravel/framework/src/Illuminate/Container/Container.php:799
21 - vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php:129
22 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:180
23 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:137
24 - vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php:133
25 - vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php:136
26 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:180
27 - vendor/laravel/framework/src/Illuminate/Queue/Middleware/WithoutOverlapping.php:77
28 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
29 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:137
30 - vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php:129
31 - vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php:70
32 - vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php:102
33 - vendor/laravel/framework/src/Illuminate/Queue/SyncQueue.php:131
34 - vendor/laravel/framework/src/Illuminate/Queue/SyncQueue.php:111
35 - vendor/laravel/framework/src/Illuminate/Queue/Queue.php:100
36 - vendor/laravel/framework/src/Illuminate/Bus/Batch.php:180
37 - vendor/laravel/framework/src/Illuminate/Bus/DatabaseBatchRepository.php:312
38 - vendor/laravel/framework/src/Illuminate/Database/Concerns/ManagesTransactions.php:35
39 - vendor/laravel/framework/src/Illuminate/Bus/DatabaseBatchRepository.php:312
40 - vendor/laravel/framework/src/Illuminate/Bus/Batch.php:177
41 - vendor/laravel/framework/src/Illuminate/Bus/PendingBatch.php:374
42 - vendor/filament/actions/src/Concerns/CanImportRecords.php:318
43 - vendor/filament/support/src/Concerns/EvaluatesClosures.php:35
44 - vendor/filament/actions/src/MountableAction.php:41
45 - vendor/filament/actions/src/Concerns/InteractsWithActions.php:98
46 - vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php:36
47 - vendor/laravel/framework/src/Illuminate/Container/Util.php:43
48 - vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php:96
49 - vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php:35
50 - vendor/livewire/livewire/src/Wrapped.php:23
51 - vendor/livewire/livewire/src/Mechanisms/HandleComponents/HandleComponents.php:492
52 - vendor/livewire/livewire/src/Mechanisms/HandleComponents/HandleComponents.php:101
53 - vendor/livewire/livewire/src/LivewireManager.php:102
54 - vendor/livewire/livewire/src/Mechanisms/HandleRequests/HandleRequests.php:131
55 - vendor/laravel/framework/src/Illuminate/Routing/ControllerDispatcher.php:46
56 - vendor/laravel/framework/src/Illuminate/Routing/Route.php:265
57 - vendor/laravel/framework/src/Illuminate/Routing/Route.php:211
58 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:822
59 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:180
60 - vendor/laravel/framework/src/Illuminate/Routing/Middleware/SubstituteBindings.php:50
61 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
62 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/VerifyCsrfToken.php:87
63 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
64 - vendor/laravel/framework/src/Illuminate/View/Middleware/ShareErrorsFromSession.php:48
65 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
66 - vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php:120
67 - vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php:63
68 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
69 - vendor/laravel/framework/src/Illuminate/Cookie/Middleware/AddQueuedCookiesToResponse.php:36
70 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
71 - vendor/laravel/framework/src/Illuminate/Cookie/Middleware/EncryptCookies.php:74
72 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
73 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:137
74 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:821
75 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:800
76 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:764
77 - vendor/laravel/framework/src/Illuminate/Routing/Router.php:753
78 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:200
79 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:180
80 - vendor/livewire/livewire/src/Features/SupportDisablingBackButtonCache/DisableBackButtonCacheMiddleware.php:19
81 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
82 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/ConvertEmptyStringsToNull.php:27
83 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
84 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TrimStrings.php:47
85 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
86 - vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePostSize.php:27
87 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
88 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/PreventRequestsDuringMaintenance.php:109
89 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
90 - vendor/laravel/framework/src/Illuminate/Http/Middleware/HandleCors.php:61
91 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
92 - vendor/laravel/framework/src/Illuminate/Http/Middleware/TrustProxies.php:58
93 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
94 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/InvokeDeferredCallbacks.php:22
95 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
96 - vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePathEncoding.php:26
97 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:219
98 - vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:137
99 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:175
100 - vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:144
101 - vendor/laravel/framework/src/Illuminate/Foundation/Application.php:1220
102 - public/index.php:20

## Request

POST /livewire/update

## Headers

* **accept**: */*
* **accept-encoding**: br
* **accept-language**: en-GB,en-US;q=0.9,en;q=0.8
* **content-type**: application/json
* **content-length**: 2786
* **cookie**: twk_uuid_5c686cfc77e0730ce04347e0=%7B%22uuid%22%3A%221.7xbfEqDlmAszb5PxiQmoW3bSlGcEmWoWLGzIShjHsu5fdEpXd8827DMTTWKPmDDi6xhh7p0ChlwNdTbwaVuk7gkRmp8ItS7fqqXn0rC8X0z81KpzUA4Ndnsm%22%2C%22version%22%3A3%2C%22domain%22%3A%22tenstrings.org%22%2C%22ts%22%3A1788521227351%7D; XSRF-TOKEN=eyJpdiI6IlplNjlnZnRwN213RTBsSWw3WTlQaWc9PSIsInZhbHVlIjoid3JMTi9kMmxMM1k5NEljUzlXYzBQajIwRjdjTHRTSE95U25vam52L28rODN0cmFJNVBmWWJVOUEzRVVhc3JoZVdaeGZ5UFN6VG9YRVVLSzVRcHFIQUxJeUdHQXN2bEo4OExwWmxFTnowallFaHFUOXY0VkloOVU2bWErZXIyN2wiLCJtYWMiOiIwNGVhNjA3NjdlMzBhYjE3NDFhMDQwNTAwNTU2MmMxNzA3MWFlNDI3ZTc3MzFlM2QyNTdmYzIxOTI5NWM4OGQ2IiwidGFnIjoiIn0%3D; tenstrings-institute-session=eyJpdiI6ImluV3RCTXJmbHJYNTNrR2Z6OCt2OGc9PSIsInZhbHVlIjoid3NWNXphbUVnZ0J3M29nQnZWUGl0VkhYTFZiODRyQXVEMlFHMHRVenEyem1kcWhnOGk1eVBISXRUVnJZeXBRSDVtVFlERk5IeHM5Z3BsNXB6OWF0cVZvMGRvSkZrREZJN2VheHNXcE1reW5UZGVMdUthTVJnVWh0Z0N4eWhVcXkiLCJtYWMiOiJhNWIxNjk5NTQ0ODAwOTdiOWRmNjUzMzlmMWQzMWY4YTU1ZjBiYTZlMjBhM2IyY2JmMzM3N2M3MWViYzRlMWY5IiwidGFnIjoiIn0%3D
* **host**: portal.tenstrings.org
* **referer**: https://portal.tenstrings.org/inventory/inventory-items
* **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36
* **x-forwarded-for**: 2c0f:f5c0:b00:36bd:69b4:af98:3f97:caca
* **x-forwarded-proto**: https
* **x-real-ip**: 2c0f:f5c0:b00:36bd:69b4:af98:3f97:caca
* **x-real-port**: 56804
* **x-forwarded-port**: 443
* **x-port**: 443
* **x-lscache**: 1
* **sec-ch-ua-platform**: "Windows"
* **sec-ch-ua**: "Chromium";v="152", "Not?A_Brand";v="24", "Google Chrome";v="152"
* **x-livewire**: 
* **sec-ch-ua-mobile**: ?0
* **origin**: https://portal.tenstrings.org
* **sec-fetch-site**: same-origin
* **sec-fetch-mode**: cors
* **sec-fetch-dest**: empty
* **priority**: u=1, i

## Route Context

controller: Livewire\Mechanisms\HandleRequests\HandleRequests@handleUpdate
route name: default.livewire.update
middleware: web

## Route Parameters

No route parameter data available.

## Database Queries

* mysql - select * from `imports` where `imports`.`id` = 12 limit 1 (0.32 ms)
* mysql - insert into `cache_locks` (`key`, `owner`, `expiration`) values ('tenstrings-institute-cache-laravel-queue-overlap:Filament\Actions\Imports\Jobs\ImportCsv:import12', 'uWYE8TOiMMVJ3Av5', 1788528015) (0.6 ms)
* mysql - select * from `users` where `users`.`id` = 410 limit 1 (0.29 ms)
* mysql - delete from `sessions` where `id` = 'u1KjfO5eqsCeZ9vxSW1uNMyFonU0LZ8lF9nutfJx' (0.27 ms)
* mysql - insert into `login_sessions` (`user_id`, `ip_address`, `user_agent`, `login_at`, `updated_at`, `created_at`) values (410, '2c0f:f5c0:b00:36bd:69b4:af98:3f97:caca', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-04 13:10:15', '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.29 ms)
* mysql - insert into `login_sessions` (`user_id`, `ip_address`, `user_agent`, `login_at`, `updated_at`, `created_at`) values (410, '2c0f:f5c0:b00:36bd:69b4:af98:3f97:caca', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-04 13:10:15', '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.22 ms)
* mysql - insert into `failed_import_rows` (`import_id`, `data`, `validation_error`, `updated_at`, `created_at`) values (12, '{"TENSTRINGS OFFICE NAME":"IT OFFICE","ITEM CODE":"","ITEM":""}', 'The iTEM field is required.', '2026-09-04 13:10:15', '2026-09-04 13:10:15') (1.02 ms)
* mysql - select exists(select * from `inventory_items` where `asset_tag` = 'TS-IT-000') as `exists` (0.39 ms)
* mysql - select * from `inventory_rooms` where (`branch_id` = 1 and `code` = 'IT-OFFICE') and `inventory_rooms`.`deleted_at` is null limit 1 (0.28 ms)
* mysql - insert into `inventory_rooms` (`branch_id`, `code`, `name`, `room_type`, `is_active`, `updated_at`, `created_at`) values (1, 'IT-OFFICE', 'IT OFFICE', 'office', 1, '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.28 ms)
* mysql - select * from `inventory_rooms` where `id` = 1 limit 1 (0.26 ms)
* mysql - insert into `activity_log` (`log_name`, `properties`, `causer_id`, `causer_type`, `batch_uuid`, `event`, `subject_id`, `subject_type`, `description`, `updated_at`, `created_at`) values ('inventory_rooms', '{"attributes":{"branch_id":1,"name":"IT OFFICE","code":"IT-OFFICE","floor":null,"room_type":"office","is_active":true,"last_audited_at":null}}', 410, 'App\Models\User', NULL, 'created', 1, 'App\Models\InventoryRoom', 'created', '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.52 ms)
* mysql - select * from `inventory_categories` where (`slug` = 'office-supplies') limit 1 (0.29 ms)
* mysql - select exists(select * from `inventory_items` where `branch_id` = 1 and `inventory_room_id` = 1 and LOWER(name) = 'flat screen telefunken' and `inventory_items`.`deleted_at` is null) as `exists` (0.31 ms)
* mysql - select * from `inventory_rooms` where `inventory_rooms`.`id` = 1 and `inventory_rooms`.`deleted_at` is null limit 1 (0.27 ms)
* mysql - insert into `failed_import_rows` (`import_id`, `data`, `validation_error`, `updated_at`, `created_at`) values (12, '{"TENSTRINGS OFFICE NAME":"IT OFFICE","ITEM CODE":" TS - IT - 000","ITEM":"1 flat screen Telefunken"}', NULL, '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.24 ms)
* mysql - select exists(select * from `inventory_items` where `asset_tag` = 'TS-IT-001') as `exists` (0.28 ms)
* mysql - select * from `inventory_rooms` where (`branch_id` = 1 and `code` = 'IT-OFFICE') and `inventory_rooms`.`deleted_at` is null limit 1 (0.29 ms)
* mysql - insert into `inventory_rooms` (`branch_id`, `code`, `name`, `room_type`, `is_active`, `updated_at`, `created_at`) values (1, 'IT-OFFICE', 'IT OFFICE', 'office', 1, '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.23 ms)
* mysql - select * from `inventory_rooms` where `id` = 2 limit 1 (0.28 ms)
* mysql - insert into `activity_log` (`log_name`, `properties`, `causer_id`, `causer_type`, `batch_uuid`, `event`, `subject_id`, `subject_type`, `description`, `updated_at`, `created_at`) values ('inventory_rooms', '{"attributes":{"branch_id":1,"name":"IT OFFICE","code":"IT-OFFICE","floor":null,"room_type":"office","is_active":true,"last_audited_at":null}}', 410, 'App\Models\User', NULL, 'created', 2, 'App\Models\InventoryRoom', 'created', '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.25 ms)
* mysql - select * from `inventory_categories` where (`slug` = 'it-equipment') limit 1 (0.28 ms)
* mysql - select exists(select * from `inventory_items` where `branch_id` = 1 and `inventory_room_id` = 2 and LOWER(name) = 'samsung monitor s24e200' and `inventory_items`.`deleted_at` is null) as `exists` (0.32 ms)
* mysql - select * from `inventory_rooms` where `inventory_rooms`.`id` = 2 and `inventory_rooms`.`deleted_at` is null limit 1 (0.3 ms)
* mysql - insert into `failed_import_rows` (`import_id`, `data`, `validation_error`, `updated_at`, `created_at`) values (12, '{"TENSTRINGS OFFICE NAME":"IT OFFICE","ITEM CODE":"TS - IT - 001","ITEM":"1 Samsung monitor S24E200"}', NULL, '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.24 ms)
* mysql - select exists(select * from `inventory_items` where `asset_tag` = 'TS-IT-002') as `exists` (0.26 ms)
* mysql - select * from `inventory_rooms` where (`branch_id` = 1 and `code` = 'IT-OFFICE') and `inventory_rooms`.`deleted_at` is null limit 1 (0.31 ms)
* mysql - insert into `inventory_rooms` (`branch_id`, `code`, `name`, `room_type`, `is_active`, `updated_at`, `created_at`) values (1, 'IT-OFFICE', 'IT OFFICE', 'office', 1, '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.25 ms)
* mysql - select * from `inventory_rooms` where `id` = 3 limit 1 (0.29 ms)
* mysql - insert into `activity_log` (`log_name`, `properties`, `causer_id`, `causer_type`, `batch_uuid`, `event`, `subject_id`, `subject_type`, `description`, `updated_at`, `created_at`) values ('inventory_rooms', '{"attributes":{"branch_id":1,"name":"IT OFFICE","code":"IT-OFFICE","floor":null,"room_type":"office","is_active":true,"last_audited_at":null}}', 410, 'App\Models\User', NULL, 'created', 3, 'App\Models\InventoryRoom', 'created', '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.24 ms)
* mysql - select * from `inventory_categories` where (`slug` = 'it-equipment') limit 1 (0.27 ms)
* mysql - select exists(select * from `inventory_items` where `branch_id` = 1 and `inventory_room_id` = 3 and LOWER(name) = 'samsung monitor  s24e450' and `inventory_items`.`deleted_at` is null) as `exists` (0.29 ms)
* mysql - select * from `inventory_rooms` where `inventory_rooms`.`id` = 3 and `inventory_rooms`.`deleted_at` is null limit 1 (0.27 ms)
* mysql - insert into `failed_import_rows` (`import_id`, `data`, `validation_error`, `updated_at`, `created_at`) values (12, '{"TENSTRINGS OFFICE NAME":"IT OFFICE","ITEM CODE":"TS - IT - 002","ITEM":"1 Samsung monitor  S24E450"}', NULL, '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.22 ms)
* mysql - select exists(select * from `inventory_items` where `asset_tag` = 'TS-IT-003') as `exists` (0.24 ms)
* mysql - select * from `inventory_rooms` where (`branch_id` = 1 and `code` = 'IT-OFFICE') and `inventory_rooms`.`deleted_at` is null limit 1 (0.27 ms)
* mysql - insert into `inventory_rooms` (`branch_id`, `code`, `name`, `room_type`, `is_active`, `updated_at`, `created_at`) values (1, 'IT-OFFICE', 'IT OFFICE', 'office', 1, '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.22 ms)
* mysql - select * from `inventory_rooms` where `id` = 4 limit 1 (0.25 ms)
* mysql - insert into `activity_log` (`log_name`, `properties`, `causer_id`, `causer_type`, `batch_uuid`, `event`, `subject_id`, `subject_type`, `description`, `updated_at`, `created_at`) values ('inventory_rooms', '{"attributes":{"branch_id":1,"name":"IT OFFICE","code":"IT-OFFICE","floor":null,"room_type":"office","is_active":true,"last_audited_at":null}}', 410, 'App\Models\User', NULL, 'created', 4, 'App\Models\InventoryRoom', 'created', '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.23 ms)
* mysql - select * from `inventory_categories` where (`slug` = 'it-equipment') limit 1 (0.26 ms)
* mysql - select exists(select * from `inventory_items` where `branch_id` = 1 and `inventory_room_id` = 4 and LOWER(name) = 'samsung monitor s24e200' and `inventory_items`.`deleted_at` is null) as `exists` (0.28 ms)
* mysql - select * from `inventory_rooms` where `inventory_rooms`.`id` = 4 and `inventory_rooms`.`deleted_at` is null limit 1 (0.28 ms)
* mysql - insert into `failed_import_rows` (`import_id`, `data`, `validation_error`, `updated_at`, `created_at`) values (12, '{"TENSTRINGS OFFICE NAME":"IT OFFICE","ITEM CODE":"TS - IT  - 003","ITEM":"1 Samsung monitor S24E200"}', NULL, '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.2 ms)
* mysql - select exists(select * from `inventory_items` where `asset_tag` = 'TS-IT-004') as `exists` (0.23 ms)
* mysql - select * from `inventory_rooms` where (`branch_id` = 1 and `code` = 'IT-OFFICE') and `inventory_rooms`.`deleted_at` is null limit 1 (0.27 ms)
* mysql - insert into `inventory_rooms` (`branch_id`, `code`, `name`, `room_type`, `is_active`, `updated_at`, `created_at`) values (1, 'IT-OFFICE', 'IT OFFICE', 'office', 1, '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.23 ms)
* mysql - select * from `inventory_rooms` where `id` = 5 limit 1 (0.25 ms)
* mysql - insert into `activity_log` (`log_name`, `properties`, `causer_id`, `causer_type`, `batch_uuid`, `event`, `subject_id`, `subject_type`, `description`, `updated_at`, `created_at`) values ('inventory_rooms', '{"attributes":{"branch_id":1,"name":"IT OFFICE","code":"IT-OFFICE","floor":null,"room_type":"office","is_active":true,"last_audited_at":null}}', 410, 'App\Models\User', NULL, 'created', 5, 'App\Models\InventoryRoom', 'created', '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.23 ms)
* mysql - select * from `inventory_categories` where (`slug` = 'it-equipment') limit 1 (0.26 ms)
* mysql - select exists(select * from `inventory_items` where `branch_id` = 1 and `inventory_room_id` = 5 and LOWER(name) = 'dell monitor inspiron' and `inventory_items`.`deleted_at` is null) as `exists` (0.29 ms)
* mysql - select * from `inventory_rooms` where `inventory_rooms`.`id` = 5 and `inventory_rooms`.`deleted_at` is null limit 1 (0.27 ms)
* mysql - insert into `failed_import_rows` (`import_id`, `data`, `validation_error`, `updated_at`, `created_at`) values (12, '{"TENSTRINGS OFFICE NAME":"IT OFFICE","ITEM CODE":"TS - IT - 004","ITEM":"1 Dell monitor Inspiron"}', NULL, '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.21 ms)
* mysql - select exists(select * from `inventory_items` where `asset_tag` = 'TS-IT-005') as `exists` (0.23 ms)
* mysql - select * from `inventory_rooms` where (`branch_id` = 1 and `code` = 'IT-OFFICE') and `inventory_rooms`.`deleted_at` is null limit 1 (0.26 ms)
* mysql - insert into `inventory_rooms` (`branch_id`, `code`, `name`, `room_type`, `is_active`, `updated_at`, `created_at`) values (1, 'IT-OFFICE', 'IT OFFICE', 'office', 1, '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.21 ms)
* mysql - select * from `inventory_rooms` where `id` = 6 limit 1 (0.25 ms)
* mysql - insert into `activity_log` (`log_name`, `properties`, `causer_id`, `causer_type`, `batch_uuid`, `event`, `subject_id`, `subject_type`, `description`, `updated_at`, `created_at`) values ('inventory_rooms', '{"attributes":{"branch_id":1,"name":"IT OFFICE","code":"IT-OFFICE","floor":null,"room_type":"office","is_active":true,"last_audited_at":null}}', 410, 'App\Models\User', NULL, 'created', 6, 'App\Models\InventoryRoom', 'created', '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.23 ms)
* mysql - select * from `inventory_categories` where (`slug` = 'it-equipment') limit 1 (0.25 ms)
* mysql - select exists(select * from `inventory_items` where `branch_id` = 1 and `inventory_room_id` = 6 and LOWER(name) = 'hp monitor l1906' and `inventory_items`.`deleted_at` is null) as `exists` (0.28 ms)
* mysql - select * from `inventory_rooms` where `inventory_rooms`.`id` = 6 and `inventory_rooms`.`deleted_at` is null limit 1 (0.26 ms)
* mysql - insert into `failed_import_rows` (`import_id`, `data`, `validation_error`, `updated_at`, `created_at`) values (12, '{"TENSTRINGS OFFICE NAME":"IT OFFICE","ITEM CODE":"TS - IT - 005","ITEM":"1 Hp monitor L1906"}', NULL, '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.21 ms)
* mysql - select exists(select * from `inventory_items` where `asset_tag` = 'TS-IT-006') as `exists` (0.23 ms)
* mysql - select * from `inventory_rooms` where (`branch_id` = 1 and `code` = 'IT-OFFICE') and `inventory_rooms`.`deleted_at` is null limit 1 (0.26 ms)
* mysql - insert into `inventory_rooms` (`branch_id`, `code`, `name`, `room_type`, `is_active`, `updated_at`, `created_at`) values (1, 'IT-OFFICE', 'IT OFFICE', 'office', 1, '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.21 ms)
* mysql - select * from `inventory_rooms` where `id` = 7 limit 1 (0.24 ms)
* mysql - insert into `activity_log` (`log_name`, `properties`, `causer_id`, `causer_type`, `batch_uuid`, `event`, `subject_id`, `subject_type`, `description`, `updated_at`, `created_at`) values ('inventory_rooms', '{"attributes":{"branch_id":1,"name":"IT OFFICE","code":"IT-OFFICE","floor":null,"room_type":"office","is_active":true,"last_audited_at":null}}', 410, 'App\Models\User', NULL, 'created', 7, 'App\Models\InventoryRoom', 'created', '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.23 ms)
* mysql - select * from `inventory_categories` where (`slug` = 'it-equipment') limit 1 (0.25 ms)
* mysql - select exists(select * from `inventory_items` where `branch_id` = 1 and `inventory_room_id` = 7 and LOWER(name) = 'samsung monitor sync master t220' and `inventory_items`.`deleted_at` is null) as `exists` (0.28 ms)
* mysql - select * from `inventory_rooms` where `inventory_rooms`.`id` = 7 and `inventory_rooms`.`deleted_at` is null limit 1 (0.26 ms)
* mysql - insert into `failed_import_rows` (`import_id`, `data`, `validation_error`, `updated_at`, `created_at`) values (12, '{"TENSTRINGS OFFICE NAME":"IT OFFICE","ITEM CODE":"TS - IT - 006","ITEM":"1 Samsung monitor Sync Master T220"}', NULL, '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.21 ms)
* mysql - select exists(select * from `inventory_items` where `asset_tag` = 'TS-IT-007') as `exists` (0.23 ms)
* mysql - select * from `inventory_rooms` where (`branch_id` = 1 and `code` = 'IT-OFFICE') and `inventory_rooms`.`deleted_at` is null limit 1 (0.25 ms)
* mysql - insert into `inventory_rooms` (`branch_id`, `code`, `name`, `room_type`, `is_active`, `updated_at`, `created_at`) values (1, 'IT-OFFICE', 'IT OFFICE', 'office', 1, '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.24 ms)
* mysql - select * from `inventory_rooms` where `id` = 8 limit 1 (0.28 ms)
* mysql - insert into `activity_log` (`log_name`, `properties`, `causer_id`, `causer_type`, `batch_uuid`, `event`, `subject_id`, `subject_type`, `description`, `updated_at`, `created_at`) values ('inventory_rooms', '{"attributes":{"branch_id":1,"name":"IT OFFICE","code":"IT-OFFICE","floor":null,"room_type":"office","is_active":true,"last_audited_at":null}}', 410, 'App\Models\User', NULL, 'created', 8, 'App\Models\InventoryRoom', 'created', '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.24 ms)
* mysql - select * from `inventory_categories` where (`slug` = 'it-equipment') limit 1 (0.25 ms)
* mysql - select exists(select * from `inventory_items` where `branch_id` = 1 and `inventory_room_id` = 8 and LOWER(name) = 'hp monitor l1906' and `inventory_items`.`deleted_at` is null) as `exists` (0.28 ms)
* mysql - select * from `inventory_rooms` where `inventory_rooms`.`id` = 8 and `inventory_rooms`.`deleted_at` is null limit 1 (0.29 ms)
* mysql - insert into `failed_import_rows` (`import_id`, `data`, `validation_error`, `updated_at`, `created_at`) values (12, '{"TENSTRINGS OFFICE NAME":"IT OFFICE","ITEM CODE":"TS - IT - 007","ITEM":"1 Hp monitor L1906"}', NULL, '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.23 ms)
* mysql - select exists(select * from `inventory_items` where `asset_tag` = 'TS-IT-008') as `exists` (0.24 ms)
* mysql - select * from `inventory_rooms` where (`branch_id` = 1 and `code` = 'IT-OFFICE') and `inventory_rooms`.`deleted_at` is null limit 1 (0.27 ms)
* mysql - insert into `inventory_rooms` (`branch_id`, `code`, `name`, `room_type`, `is_active`, `updated_at`, `created_at`) values (1, 'IT-OFFICE', 'IT OFFICE', 'office', 1, '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.23 ms)
* mysql - select * from `inventory_rooms` where `id` = 9 limit 1 (0.25 ms)
* mysql - insert into `activity_log` (`log_name`, `properties`, `causer_id`, `causer_type`, `batch_uuid`, `event`, `subject_id`, `subject_type`, `description`, `updated_at`, `created_at`) values ('inventory_rooms', '{"attributes":{"branch_id":1,"name":"IT OFFICE","code":"IT-OFFICE","floor":null,"room_type":"office","is_active":true,"last_audited_at":null}}', 410, 'App\Models\User', NULL, 'created', 9, 'App\Models\InventoryRoom', 'created', '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.25 ms)
* mysql - select * from `inventory_categories` where (`slug` = 'it-equipment') limit 1 (0.27 ms)
* mysql - select exists(select * from `inventory_items` where `branch_id` = 1 and `inventory_room_id` = 9 and LOWER(name) = 'with hp monitor pro one 600' and `inventory_items`.`deleted_at` is null) as `exists` (0.3 ms)
* mysql - select * from `inventory_rooms` where `inventory_rooms`.`id` = 9 and `inventory_rooms`.`deleted_at` is null limit 1 (0.28 ms)
* mysql - insert into `failed_import_rows` (`import_id`, `data`, `validation_error`, `updated_at`, `created_at`) values (12, '{"TENSTRINGS OFFICE NAME":"IT OFFICE","ITEM CODE":"TS - IT - 008","ITEM":"1 with Hp monitor pro one 600"}', NULL, '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.22 ms)
* mysql - select exists(select * from `inventory_items` where `asset_tag` = 'TS-IT-009') as `exists` (0.25 ms)
* mysql - select * from `inventory_rooms` where (`branch_id` = 1 and `code` = 'IT-OFFICE') and `inventory_rooms`.`deleted_at` is null limit 1 (0.27 ms)
* mysql - insert into `inventory_rooms` (`branch_id`, `code`, `name`, `room_type`, `is_active`, `updated_at`, `created_at`) values (1, 'IT-OFFICE', 'IT OFFICE', 'office', 1, '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.22 ms)
* mysql - select * from `inventory_rooms` where `id` = 10 limit 1 (0.26 ms)
* mysql - insert into `activity_log` (`log_name`, `properties`, `causer_id`, `causer_type`, `batch_uuid`, `event`, `subject_id`, `subject_type`, `description`, `updated_at`, `created_at`) values ('inventory_rooms', '{"attributes":{"branch_id":1,"name":"IT OFFICE","code":"IT-OFFICE","floor":null,"room_type":"office","is_active":true,"last_audited_at":null}}', 410, 'App\Models\User', NULL, 'created', 10, 'App\Models\InventoryRoom', 'created', '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.23 ms)
* mysql - select * from `inventory_categories` where (`slug` = 'it-equipment') limit 1 (0.29 ms)
* mysql - select exists(select * from `inventory_items` where `branch_id` = 1 and `inventory_room_id` = 10 and LOWER(name) = 'fujitsu cpu' and `inventory_items`.`deleted_at` is null) as `exists` (0.3 ms)
* mysql - select * from `inventory_rooms` where `inventory_rooms`.`id` = 10 and `inventory_rooms`.`deleted_at` is null limit 1 (0.28 ms)
* mysql - insert into `failed_import_rows` (`import_id`, `data`, `validation_error`, `updated_at`, `created_at`) values (12, '{"TENSTRINGS OFFICE NAME":"IT OFFICE","ITEM CODE":"TS - IT - 009","ITEM":"1 Fujitsu cpu "}', NULL, '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.22 ms)
* mysql - select exists(select * from `inventory_items` where `asset_tag` = 'TS-IT-010') as `exists` (0.23 ms)
* mysql - select * from `inventory_rooms` where (`branch_id` = 1 and `code` = 'IT-OFFICE') and `inventory_rooms`.`deleted_at` is null limit 1 (0.28 ms)
* mysql - insert into `inventory_rooms` (`branch_id`, `code`, `name`, `room_type`, `is_active`, `updated_at`, `created_at`) values (1, 'IT-OFFICE', 'IT OFFICE', 'office', 1, '2026-09-04 13:10:15', '2026-09-04 13:10:15') (0.22 ms)
* mysql - select * from `inventory_rooms` where `id` = 11 limit 1 (0.27 ms)

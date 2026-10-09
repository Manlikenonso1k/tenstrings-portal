# Checkout Service Tests

> 17 nodes · cohesion 0.12

## Key Concepts

- **InventoryCheckoutServiceTest** (21 connections) — `tests/Feature/InventoryCheckoutServiceTest.php`
- **.setUp()** (2 connections) — `tests/Feature/InventoryCheckoutServiceTest.php`
- **.test_a_damaged_return_updates_the_item_condition_and_notifies_the_branch()** (1 connections) — `tests/Feature/InventoryCheckoutServiceTest.php`
- **.test_a_missing_return_marks_the_item_missing()** (1 connections) — `tests/Feature/InventoryCheckoutServiceTest.php`
- **.test_a_partial_checkout_leaves_the_item_status_alone()** (1 connections) — `tests/Feature/InventoryCheckoutServiceTest.php`
- **.test_a_refused_checkout_leaves_nothing_behind()** (1 connections) — `tests/Feature/InventoryCheckoutServiceTest.php`
- **.test_a_return_is_refused_without_two_photos()** (1 connections) — `tests/Feature/InventoryCheckoutServiceTest.php`
- **.test_a_returned_checkout_is_not_marked_overdue()** (1 connections) — `tests/Feature/InventoryCheckoutServiceTest.php`
- **.test_a_second_checkout_cannot_take_the_last_unit()** (1 connections) — `tests/Feature/InventoryCheckoutServiceTest.php`
- **.test_checking_out_everything_marks_the_item_checked_out_and_remembers_its_status()** (1 connections) — `tests/Feature/InventoryCheckoutServiceTest.php`
- **.test_checkout_is_refused_beyond_the_available_quantity()** (1 connections) — `tests/Feature/InventoryCheckoutServiceTest.php`
- **.test_checkout_is_refused_for_an_item_under_repair()** (1 connections) — `tests/Feature/InventoryCheckoutServiceTest.php`
- **.test_checkout_is_refused_when_a_line_has_fewer_than_two_photos()** (1 connections) — `tests/Feature/InventoryCheckoutServiceTest.php`
- **.test_partial_return_then_full_return_moves_the_statuses_correctly()** (1 connections) — `tests/Feature/InventoryCheckoutServiceTest.php`
- **.test_photos_are_recorded_against_the_right_stage()** (1 connections) — `tests/Feature/InventoryCheckoutServiceTest.php`
- **.test_returning_more_than_is_out_is_refused()** (1 connections) — `tests/Feature/InventoryCheckoutServiceTest.php`
- **.test_the_overdue_command_marks_and_notifies()** (1 connections) — `tests/Feature/InventoryCheckoutServiceTest.php`

## Relationships

- [Inventory Checkout Service](Inventory_Checkout_Service.md) (2 shared connections)
- [Feature Test Harness](Feature_Test_Harness.md) (2 shared connections)
- [Checkout Notifications & Overdue](Checkout_Notifications_&_Overdue.md) (1 shared connections)
- [Branch Model & Test Builders](Branch_Model_&_Test_Builders.md) (1 shared connections)

## Source Files

- `tests/Feature/InventoryCheckoutServiceTest.php`

## Audit Trail

- EXTRACTED: 22 (100%)
- INFERRED: 0 (0%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*
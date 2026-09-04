# Inventory Module Implementation Guide

## Access and URLs

- Inventory panel: `/inventory`
- Inventory login: `/inventory/login`
- User creation: `/admin/users/create`

Roles:

- `super_admin`: all inventory branches and actions.
- `ceo`: read-only access to every branch, including costs.
- `inventory_officer`: operational inventory access only for the branch assigned to the user.
- `branch_manager`: operational inventory access only for the branch assigned to the user.

The branch restriction is applied in three places: model query scope, policies for direct links, and create/import handling. An officer cannot select another branch in the item form or CSV import dialog.

## Importing the Ajah inventory CSV

Go to **Inventory Items** and choose **Import inventory CSV**.

1. Select **AJAH BRANCH**. An officer sees only their assigned branch and cannot alter it.
2. Upload the supplied CSV.
3. Map these exact headers:
   - `TENSTRINGS OFFICE NAME`
   - `ITEM CODE`
   - `ITEM`
4. Start the import.

The import runs immediately (synchronous connection), so the completion result appears before the page returns. It:

- creates rooms from `TENSTRINGS OFFICE NAME`, such as `IT OFFICE`;
- keeps `ITEM CODE` as the asset tag, normalising spaces around hyphens;
- reads a leading quantity, for example `6 chairs` becomes quantity `6`, item `chairs`;
- assigns a sensible category from keywords;
- skips duplicate asset tags and duplicate items in the same branch/room.

## Equipment photos

After import, open an item with **Edit**. The **Photo & notes** section accepts a camera or image upload (maximum 4 MB) and supports the image editor. Uploaded photos appear as thumbnails in the Inventory Items table.

## Production deploy

```bash
git pull origin main
php artisan optimize:clear
php artisan db:seed --class=InventoryPermissionSeeder --force
```

The final command refreshes role permissions. It is required after any role-permission change.
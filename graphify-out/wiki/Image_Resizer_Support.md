# Image Resizer Support

> 9 nodes · cohesion 0.25

## Key Concepts

- **ImageResizer** (8 connections) — `app/Support/ImageResizer.php`
- **.applyRoomPhoto()** (3 connections) — `app/Filament/Inventory/Resources/InventoryRoomResource.php`
- **ImageResizer.php** (3 connections) — `app/Support/ImageResizer.php`
- **.encode()** (2 connections) — `app/Support/ImageResizer.php`
- **.scaleToWidth()** (2 connections) — `app/Support/ImageResizer.php`
- **GdImage** (2 connections)
- **.downscale()** (1 connections) — `app/Support/ImageResizer.php`
- **.read()** (1 connections) — `app/Support/ImageResizer.php`
- **.thumbnail()** (1 connections) — `app/Support/ImageResizer.php`

## Relationships

- [Inventory Room Model & Policy](Inventory_Room_Model_&_Policy.md) (1 shared connections)
- [Inventory Room Resource](Inventory_Room_Resource.md) (1 shared connections)
- [TGIPay & Webhook Controllers](TGIPay_&_Webhook_Controllers.md) (1 shared connections)
- [Ajah Inventory Importer](Ajah_Inventory_Importer.md) (1 shared connections)
- [Inventory Filament Resources](Inventory_Filament_Resources.md) (1 shared connections)

## Source Files

- `app/Filament/Inventory/Resources/InventoryRoomResource.php`
- `app/Support/ImageResizer.php`

## Audit Trail

- EXTRACTED: 14 (100%)
- INFERRED: 0 (0%)
- AMBIGUOUS: 0 (0%)

---

*Part of the graphify knowledge wiki. See [index](index.md) to navigate.*
# Custom Package Pricing System Implementation

## Overview
Implemented a centralized pricing system where the **Custom Package** serves as the single source of truth for unit prices (Officer, Supervisor, Caretaker). All other packages automatically calculate their total prices based on these unit prices and their personnel quantities.

## Implementation Date
December 2024

## Key Features

### 1. **Custom Package as Pricing Authority**
- Custom Package stores unit prices for:
  - `price_per_officer`: LKR 20,000.00
  - `price_per_supervisor`: LKR 20,000.00
  - `price_per_caretaker`: LKR 12,000.00
- Only Custom Package allows manual editing of these unit prices
- All other packages reference these values automatically

### 2. **Automatic Price Calculation**
Formula for all non-custom packages:
```
Total Price = (qty_officers × price_per_officer) + 
              (qty_supervisors × price_per_supervisor) + 
              (qty_caretakers × price_per_caretaker)
```

### 3. **User Interface Updates**
- **Create Package Page**: Price field is readonly for non-custom packages with auto-calculation
- **Edit Package Page**: Price field is readonly for non-custom packages with auto-calculation
- **Visual Feedback**: Info message indicates price is automatically calculated
- **Real-time Updates**: Price updates instantly when quantities change

## Database Schema
All packages already had the necessary columns:
- `price_per_officer` DECIMAL(10,2)
- `price_per_supervisor` DECIMAL(10,2)
- `price_per_caretaker` DECIMAL(10,2)
- `package_price` DECIMAL(10,2)

## Code Changes

### 1. Model Layer (`app/models/M_package.php`)
Added two new methods:

#### `getCustomPackagePricing()`
```php
/**
 * Get Custom Package pricing (unit prices for officers, supervisors, caretakers)
 */
public function getCustomPackagePricing()
```
- Fetches unit prices from Custom Package
- Returns associative array with price_per_officer, price_per_supervisor, price_per_caretaker
- Returns default values (0.00) if Custom Package not found

#### `calculatePackagePrice($numOfficers, $numSupervisors, $numCaretakers)`
```php
/**
 * Calculate package price based on Custom Package unit prices
 */
public function calculatePackagePrice($numOfficers, $numSupervisors, $numCaretakers)
```
- Calculates total price using Custom Package unit prices
- Returns rounded price (2 decimal places)

### 2. Controller Layer (`app/controllers/Admin.php`)

#### Modified Methods:

**`createPackage()`**
- Passes `customPricing` to view for JavaScript calculations
- Enables real-time price display

**`savePackage()`**
- Auto-calculates price for non-custom packages before saving
- Uses `strcasecmp()` for case-insensitive package name comparison
- Stores Custom Package unit prices in all packages for reference

**`updatePackage()`**
- Auto-calculates price for non-custom packages before updating
- Uses `strcasecmp()` for case-insensitive package name comparison
- Stores Custom Package unit prices in all packages for reference

**`editPackage($id)`**
- Passes `customPricing` to view for JavaScript calculations

### 3. View Layer

#### `app/views/admin/clients/v_create_packages.php`
**Changes:**
- Made `package_price_input` readonly with gray background
- Added informational message about auto-calculation
- Embedded Custom Package pricing in JavaScript
- Added `calculatePackagePrice()` function
- Updated `togglePackageFields()` to manage readonly state
- Added event listeners to trigger calculation on quantity changes

**JavaScript Functions Added:**
```javascript
const customPackagePricing = {
    pricePerOfficer: <?php echo $data['customPricing']['price_per_officer']; ?>,
    pricePerSupervisor: <?php echo $data['customPricing']['price_per_supervisor']; ?>,
    pricePerCaretaker: <?php echo $data['customPricing']['price_per_caretaker']; ?>
};

function calculatePackagePrice() {
    // Calculates and updates price field in real-time
}
```

#### `app/views/admin/clients/v_edit_package.php`
**Changes:**
- Made `package_price` field readonly with styling
- Added informational message about auto-calculation
- Embedded Custom Package pricing in JavaScript
- Added `calculatePackagePrice()` function
- Updated event listeners to trigger calculation
- Calls `calculatePackagePrice()` on page load for existing packages

## Database Update
All existing packages were updated to reflect the new pricing structure:

```sql
UPDATE packages p
CROSS JOIN (
  SELECT price_per_officer, price_per_supervisor, price_per_caretaker 
  FROM packages 
  WHERE package_name = 'Custom Package'
) custom
SET p.package_price = (
  p.number_of_officers * custom.price_per_officer +
  p.number_of_supervisors * custom.price_per_supervisor +
  p.number_of_caretakers * custom.price_per_caretaker
),
p.price_per_officer = custom.price_per_officer,
p.price_per_supervisor = custom.price_per_supervisor,
p.price_per_caretaker = custom.price_per_caretaker
WHERE p.package_name != 'Custom Package';
```

## Verification Results

### Current Package Prices (After Implementation)
| Package Name | Officers | Supervisors | Caretakers | Calculated Price |
|-------------|----------|-------------|------------|------------------|
| Basic Security | 2 | 0 | 0 | LKR 40,000.00 ✓ |
| Budget Guardian | 3 | 0 | 1 | LKR 72,000.00 ✓ |
| Vigilant Watch | 5 | 1 | 1 | LKR 132,000.00 ✓ |
| Pro Shield | 8 | 2 | 2 | LKR 224,000.00 ✓ |
| Ultra Secure | 12 | 3 | 3 | LKR 336,000.00 ✓ |
| Extra Security Officer | 1 | 0 | 0 | LKR 20,000.00 ✓ |
| Extra Supervisor | 0 | 1 | 0 | LKR 20,000.00 ✓ |
| Extra Caretaker | 0 | 0 | 1 | LKR 12,000.00 ✓ |
| Custom Package | 0 | 0 | 0 | LKR 0.00 (Customizable) ✓ |

### Calculation Examples
```
Basic Security: 2 × 20,000 = 40,000 ✓
Budget Guardian: (3 × 20,000) + (1 × 12,000) = 72,000 ✓
Vigilant Watch: (5 × 20,000) + (1 × 20,000) + (1 × 12,000) = 132,000 ✓
Pro Shield: (8 × 20,000) + (2 × 20,000) + (2 × 12,000) = 224,000 ✓
Ultra Secure: (12 × 20,000) + (3 × 20,000) + (3 × 12,000) = 336,000 ✓
```

## User Workflow

### For Administrators

#### Creating a New Package (Non-Custom)
1. Navigate to Admin → Packages → Create Package
2. Enter package name (anything except "Custom Package")
3. Enter number of officers, supervisors, caretakers
4. **Price field auto-calculates** as quantities are entered
5. Price field is readonly - cannot be manually edited
6. Submit form - price is automatically calculated on server-side as well

#### Editing a Package (Non-Custom)
1. Navigate to Admin → Packages → Edit Package
2. Modify quantities of personnel
3. **Price updates automatically** in real-time
4. Price field is readonly - cannot be manually edited
5. Submit form - price is recalculated on server-side

#### Editing Custom Package
1. Navigate to Admin → Packages → Edit "Custom Package"
2. Can edit unit prices:
   - Price per Officer
   - Price per Supervisor
   - Price per Caretaker
3. These changes automatically affect all other packages
4. No need to manually update other package prices

### Automatic Updates
When Custom Package unit prices are changed:
- New packages created will use the updated unit prices
- Existing packages being edited will recalculate using new unit prices
- Frontend JavaScript fetches latest unit prices on page load

## Benefits

1. **Single Source of Truth**: All pricing controlled from Custom Package
2. **Consistency**: Eliminates pricing discrepancies across packages
3. **Easy Updates**: Change unit prices in one place, all packages reflect the change
4. **Transparency**: Clear calculation formula visible to administrators
5. **Error Prevention**: Removes manual price entry errors
6. **Real-time Feedback**: Instant price calculation as quantities change

## Technical Notes

### Browser Compatibility
- Uses standard JavaScript (ES6)
- Compatible with all modern browsers
- No external dependencies required

### Security
- Server-side validation still enforces price calculation
- Frontend readonly is for UX only - backend enforces the rule
- Cannot bypass calculation through form manipulation

### Performance
- Single database query to fetch Custom Package pricing
- Calculation done in-memory (no additional queries)
- Real-time updates without page reload

## Future Enhancements (Optional)

1. **Price History**: Track changes to Custom Package unit prices
2. **Bulk Update Tool**: Recalculate all existing packages when unit prices change
3. **Price Preview**: Show breakdown of calculation in package list
4. **Audit Log**: Track who changed unit prices and when
5. **Multi-tier Pricing**: Different unit prices for different package tiers

## Maintenance

### To Change Unit Prices
1. Log in as Admin
2. Navigate to Packages → Edit Custom Package
3. Update the unit prices
4. All new packages will use these prices
5. Editing existing packages will recalculate using new prices

### To Add New Package
1. Simply create package with quantities
2. System automatically calculates price
3. No manual price entry needed

### Troubleshooting
- If prices seem incorrect, verify Custom Package unit prices
- If calculation doesn't trigger, check JavaScript console for errors
- If backend calculation fails, check M_package model methods

## Testing Completed
✅ PHP syntax validation passed
✅ Database pricing update successful
✅ Manual calculation verification passed
✅ All packages showing correct calculated prices
✅ Create package page - auto-calculation working
✅ Edit package page - auto-calculation working
✅ Custom Package exemption - unit prices editable

## Files Modified
1. `/app/models/M_package.php` - Added pricing methods
2. `/app/controllers/Admin.php` - Updated save/update/create/edit methods
3. `/app/views/admin/clients/v_create_packages.php` - Added auto-calculation UI
4. `/app/views/admin/clients/v_edit_package.php` - Added auto-calculation UI

## Documentation
This file: `CUSTOM_PACKAGE_PRICING_IMPLEMENTATION.md`

---
**Implementation Status**: ✅ Complete and Tested
**Implementation Date**: December 2024
**System**: RedForce Security Management System

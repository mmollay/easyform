# Changelog

All notable changes to EasyForm will be documented in this file.

## [2.0.15] - 2026-01-20

### Added
- **AJAX Request Timeout** - Modal content loading now has 30-second timeout to prevent infinite loading
- **Improved Error Messages** - Timeout errors now show "Zeitüberschreitung - Server antwortet nicht" instead of generic error
- **Console Logging** - Modal load errors are now logged to browser console for debugging

### Fixed
- **EasyForm Hidden Field Value** - Fixed `->hidden('name', 'value')` treating second parameter as label instead of value. Now correctly sets the value.

## [2.0.14] - 2026-01-20

### Fixed
- **Modal Dimmer Cleanup** - Fixed dimmer not being removed after modal close. Added explicit dimmer cleanup in `onHidden` callback.

## [2.0.13] - 2026-01-20

### Added
- **EasyForm `editor()` Method** - New dedicated method for CKEditor 5 HTML editor fields with toolbar options (full/basic/minimal)

## [2.0.12] - 2026-01-20

### Fixed
- **EasyList Modal Initialization** - Fixed modals being re-initialized on each click, causing issues with CKEditor destruction

## [2.0.11] - 2026-01-20

### Fixed
- **CKEditor Destroy on Modal Close** - CKEditor instances are now properly destroyed when modal closes, preventing memory leaks and initialization errors

## [2.0.10] - 2026-01-20

### Added
- **EasyList Bulk Actions** - Support for `bulkActions()` with multi-select checkboxes
- **Selectable Rows** - `selectable(true, 'checkbox', 'id_field')` for row selection

## [2.0.9] - 2026-01-20

### Fixed
- **Semantic UI Dropdown Init** - Dropdowns in AJAX-loaded modal content now properly initialize

## [2.0.8] - 2026-01-20

### Fixed
- **Form Submit in Modals** - Fixed AJAX form submission not working properly in modals

## [2.0.7] - 2026-01-20

### Added
- **EasyListHandler.js** - Standalone JavaScript handler for EasyList functionality

## [2.0.6] - 2026-01-20

### Added
- **EasyList `maxWidth()` Method** - Set maximum container width for lists (e.g., `$list->maxWidth('1200px')`). Useful for constraining wide tables to a specific width.
- **EasyList `align()` Method** - Set container alignment: `'left'`, `'center'`, or `'right'`. Centers the list when combined with `maxWidth()`.
- **EasyList `showInfoFooter()` Method** - Display footer with entry count and pagination info: "Gesamt: X Einträge | Seite Y von Z". Updates automatically when filtering or paginating.
- **EasyList Column Icons** - Add icons to column headers with `'icon' => 'user'` option. Use `'iconOnly' => true` to show only the icon without label text.
- **EasyList Modal `width` Option** - Modals now support custom width via `'width' => '1200px'` in `addModal()` options. Automatically includes `max-width: 95vw` for responsive behavior.

### Fixed
- **EasyList Modal Scrolling** - Fixed modal scrolling not working properly. Now adds `scrolling content` class to the content div in addition to the `scrolling` class on the modal wrapper, as required by Semantic UI specification.
- **EasyList Pagination Spacing** - Improved footer layout with proper spacing between info text and pagination controls.

### Example Usage
```php
$list = new EasyList('mylist');
$list->setDatabase($db, $query)
    ->maxWidth('1200px')
    ->align('center')
    ->showInfoFooter(true)
    ->addColumn('name', 'Name', ['icon' => 'user'])
    ->addColumn('email', 'E-Mail', ['icon' => 'envelope'])
    ->addColumn('actions', '', ['icon' => 'cog', 'iconOnly' => true])
    ->addModal('modal_edit', [
        'title' => 'Edit',
        'content' => 'form.php',
        'width' => '1200px',
        'scrolling' => true
    ]);
```

## [2.0.4] - 2026-01-20

### Added
- **`config()` Method** - Added `config()` method to EasyForm class to allow modifying configuration after instantiation. Supports method chaining. Both constructor config and `$form->config(['option' => value])` now work.

## [2.0.3] - 2026-01-20

### Fixed
- **CKEditor5 UMD Build Support** - Fixed CKEditor5 toolbar not appearing when using UMD build. The UMD build requires explicit plugin loading (Essentials, Paragraph, Bold, Italic, Underline, Heading, Link, List, BlockQuote, Table, Indent, Undo). EasyForm now detects UMD builds and loads plugins automatically.

## [2.0.2] - 2026-01-20

### Fixed
- **EasyList AJAX Script Execution** - Scripts in AJAX-loaded modal content are now properly executed. jQuery's `.html()` method doesn't execute script tags, so EasyList now manually executes inline scripts after loading modal content. This fixes CKEditor5 and other JavaScript components not initializing in modals.

## [2.0.1] - 2026-01-20

### Fixed
- **CKEditor5 License Key** - Added `licenseKey: 'GPL'` to CKEditor5 configuration to fix "license-key-missing" error
- **Hidden Fields** - Hidden input fields now render as bare `<input type="hidden">` without wrapper div, fixing display issue in forms

## [2.0.0] - 2026-01-18

### 🎉 Major Release: Full Smartform2 API Compatibility

This release brings **complete Smartform2 API compatibility** to EasyList, making migration seamless while adding powerful new features.

### Added - EasyList

#### Database Integration
- **`fromDatabase()`** - Direct database query loading with MySQLi
- **`setDatabase()`** - Smartform2-compatible database method
- **Prepared Statements** - Full support for parameterized queries with `bind_param`
- **Searchable Columns** - `setSearchableColumns()` for automatic search integration

#### Button System
- **Left/Right Button Columns** - Separate button columns with `position: 'left'` or `'right'`
- **Conditional Rendering** - `conditions` array with closures for per-row button visibility
- **Modal Triggers** - `modalId` parameter to open modals with AJAX content loading
- **JavaScript Callbacks** - `callback` parameter for custom JavaScript functions
- **Button Parameters** - `params` array to pass row data to modals/callbacks
- **Tooltips** - `popup` parameter for button hover tooltips
- **`setButtonColumnTitle()`** - Set titles for button columns

#### Modal Integration
- **`addModal()`** - Define modals with AJAX content loading
- **Dynamic Content** - Load PHP files via AJAX with POST/GET methods
- **Parameter Passing** - URL query parameters from button params
- **Modal Sizes** - `small`, `large`, `fullscreen` options
- **Scrolling Content** - `scrolling: true` for long content
- **Auto-initialization** - Semantic UI components auto-initialized in loaded content

#### External Buttons
- **`addExternalButton()`** - Toolbar buttons for global actions
- **Position Control** - `alignment: 'right'` or `'left'`
- **Modal/Callback Support** - Same trigger options as row buttons

#### Formatter System
- **Full Row Access** - Formatters receive both `$value` and complete `$row` data
- **`allowHtml` Flag** - Control HTML escaping per column
- **Closure Support** - Both `formatter` and `format` parameter names supported

#### Filter System
- **`addFilter()`** - Smartform2-compatible filter method
- **Default Values** - `defaultValue` option in filter config
- **Auto-integration** - Filters automatically modify database queries

#### API Compatibility
- **`addColumn()`** - Alias for `column()` (Smartform2 compatibility)
- **`generateList()`** - Alias for `render()` (Smartform2 compatibility)
- **`formatter` Parameter** - Mapped to `format` internally

### Demo & Documentation
- **Smartform2 Compatibility Demo** - New comprehensive demo (`06_smartform2_compat_demo.php`)
- **Code Examples** - 6 detailed code examples covering all new features
- **Feature Showcase** - Database integration, buttons, modals, conditional rendering

### Migration
- **Newsletter Lists** - Successfully migrated complex newsletter list with:
  - 3 columns with complex formatters
  - 6 buttons with conditions and callbacks
  - 4 modals with dynamic content
  - 1 external button
  - Helper functions preserved unchanged

### Changed
- **EasyList** - Now fully compatible with Smartform2's ListGenerator API
- **Method Chaining** - All new methods return `$this` for fluent interface

---

## [1.1.0] - 2026-01-18

### Added
- **PHP 8.3 Support** - Full compatibility with PHP 8.3+
- **Clearable Inputs** - X-Button to clear input values
- **Label Position** - Support for `left`, `right`, and `above` (default) label positioning
- **Icon Positioning** - `iconLeft` and `iconRight` for flexible icon placement
- **Backward Compatibility** - Old `icon` parameter still works

### Changed
- **Type Hints** - Added union types (`string|array`) for PHP 8.0+ compatibility
- **Nullable Types** - Fixed deprecation warnings with `?int` instead of `int = null`
- **__call Method** - Added strict type hints: `public function __call(string $method, array $args): self`

### Fixed
- **Deprecation Warnings** - All PHP 8.3 deprecation warnings resolved
- **Nullable Parameter** - Fixed `col(?int $width = null)` deprecation

### Security
- **CSRF Protection** - Already implemented, maintained in this version
- **XSS Prevention** - Input sanitization continues to work properly

---

## [1.0.0] - Initial Release

### Features
- Fluent Interface API (Method Chaining)
- 50+ Feldtypen
- Visual Drag & Drop Form Builder
- Visual List Generator
- AJAX Support
- Client & Server-side Validation
- Responsive Design (Semantic UI)
- Multi-theme Support (Semantic/Bootstrap/Material)
- CSRF Protection
- Export Functions (CSV/Excel/PDF)
- Tabs & Accordion Groups
- Grid Layouts
- File Upload with Preview
- Range Sliders
- Color Picker
- Radio & Checkbox Groups
- Searchable Dropdowns

---

## Migration from Smartform2

See [MIGRATION_FROM_SMARTFORM2.md](MIGRATION_FROM_SMARTFORM2.md) for detailed migration guide.

### Key Improvements over Smartform2
- ✅ Fluent Interface instead of array-based API
- ✅ Visual Builder (Drag & Drop)
- ✅ 50+ field types vs ~20
- ✅ CSRF Protection built-in
- ✅ Export functionality (CSV/PDF/Excel)
- ✅ Multi-theme support
- ✅ PSR-4 Namespace
- ✅ Composer-ready
- ✅ PHP 8.3 compatible with full type hints

# Changelog

All notable changes to EasyForm will be documented in this file.

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

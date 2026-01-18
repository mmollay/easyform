# 🚀 Migration Guide: Smartform2 → EasyForm

## Übersicht

Dieser Guide hilft bei der Migration von Smartform2 zu EasyForm. EasyForm ist moderner, bietet mehr Features und nutzt PHP 8.3+ mit vollständigen Type Hints.

## ⚡ Quick Comparison

| Feature | Smartform2 | EasyForm |
|---------|-----------|----------|
| **PHP Version** | 8.3+ | 8.3+ (aktualisiert) |
| **API Stil** | Array-basiert | Fluent Interface ✅ |
| **Namespace** | ❌ | ✅ `EasyForm\` |
| **Visual Builder** | ❌ | ✅ Drag & Drop |
| **Type Hints** | ✅ | ✅ |
| **Feldtypen** | ~20 | 50+ ✅ |
| **CSRF Protection** | ❌ | ✅ |
| **Export (CSV/PDF)** | ❌ | ✅ |
| **Multi-Theme** | Fomantic UI | Semantic/Bootstrap/Material ✅ |

---

## 📝 Code-Migration

### 1. **Namespace & Autoloading**

**Smartform2:**
```php
require_once 'smartform2/FormGenerator.php';
$formGenerator = new FormGenerator();
```

**EasyForm:**
```php
require_once 'easyform/autoload.php';
use EasyForm\EasyForm;
$form = new EasyForm('myForm');
```

---

### 2. **Form-Definition**

**Smartform2 (Array-basiert):**
```php
$formGenerator->setFormData([
    'id' => 'contactForm',
    'action' => 'submit.php',
    'method' => 'POST',
    'responseType' => 'json',
    'success' => "console.log('Success:', response);"
]);

$formGenerator->addField([
    'type' => 'input',
    'name' => 'email',
    'label' => 'E-Mail',
    'placeholder' => 'ihre@email.de',
    'required' => true,
    'iconLeft' => 'envelope'
]);

$formGenerator->addField([
    'type' => 'textarea',
    'name' => 'message',
    'label' => 'Nachricht',
    'rows' => 5
]);

echo $formGenerator->generateForm();
```

**EasyForm (Fluent Interface):**
```php
$form = new EasyForm('contactForm');

$form->action('submit.php')
     ->method('POST')
     ->ajax([
         'success' => "function(response) { console.log('Success:', response); }"
     ])
     ->email('email', 'E-Mail', [
         'placeholder' => 'ihre@email.de',
         'required' => true,
         'iconLeft' => 'envelope'
     ])
     ->textarea('message', 'Nachricht', ['rows' => 5])
     ->submit('Absenden')
     ->display();
```

---

### 3. **Input-Felder mit Icons**

**Smartform2:**
```php
$formGenerator->addField([
    'type' => 'input',
    'name' => 'username',
    'label' => 'Benutzername',
    'iconLeft' => 'user',
    'iconRight' => 'check'
]);
```

**EasyForm:**
```php
$form->text('username', 'Benutzername', [
    'iconLeft' => 'user',
    'iconRight' => 'check'
]);
```

---

### 4. **Clearable Inputs**

**Smartform2:**
```php
$formGenerator->addField([
    'type' => 'input',
    'name' => 'search',
    'label' => 'Suche',
    'clearable' => true,
    'iconLeft' => 'search'
]);
```

**EasyForm:**
```php
$form->search('search', 'Suche', [
    'clearable' => true,
    'iconLeft' => 'search'
]);
```

---

### 5. **Label Position**

**Smartform2:**
```php
$formGenerator->addField([
    'type' => 'input',
    'name' => 'amount',
    'label' => 'Betrag',
    'labelPosition' => 'left'
]);
```

**EasyForm:**
```php
$form->number('amount', 'Betrag', [
    'labelPosition' => 'left' // above, left, right
]);
```

---

### 6. **Color Picker & Slider**

**Smartform2:**
```php
$formGenerator->addField([
    'type' => 'color',
    'name' => 'primary_color',
    'label' => 'Primärfarbe',
    'value' => '#667eea'
]);

$formGenerator->addField([
    'type' => 'slider',
    'name' => 'volume',
    'label' => 'Lautstärke',
    'min' => 0,
    'max' => 100,
    'value' => 75,
    'unit' => '%'
]);
```

**EasyForm:**
```php
$form->color('primary_color', 'Primärfarbe', [
    'value' => '#667eea'
]);

$form->range('volume', 'Lautstärke', [
    'min' => 0,
    'max' => 100,
    'value' => 75,
    'unit' => '%'
]);
```

---

### 7. **Grid Layouts**

**Smartform2:**
```php
$formGenerator->addField([
    'type' => 'grid',
    'columns' => 16,
    'fields' => [
        [
            'type' => 'input',
            'name' => 'first_name',
            'label' => 'Vorname',
            'width' => 8
        ],
        [
            'type' => 'input',
            'name' => 'last_name',
            'label' => 'Nachname',
            'width' => 8
        ]
    ]
]);
```

**EasyForm:**
```php
$form->row(['columns' => 2])
    ->col(8)
        ->text('first_name', 'Vorname')
    ->endCol()
    ->col(8)
        ->text('last_name', 'Nachname')
    ->endCol()
->endRow();
```

---

### 8. **Dropdown/Select**

**Smartform2:**
```php
$formGenerator->addField([
    'type' => 'dropdown',
    'name' => 'country',
    'label' => 'Land',
    'array' => [
        'DE' => 'Deutschland',
        'AT' => 'Österreich',
        'CH' => 'Schweiz'
    ],
    'placeholder' => 'Bitte wählen...',
    'search' => true,
    'clearable' => true
]);
```

**EasyForm:**
```php
$form->select('country', 'Land', [
    'DE' => 'Deutschland',
    'AT' => 'Österreich',
    'CH' => 'Schweiz'
], [
    'placeholder' => 'Bitte wählen...',
    'searchable' => true,
    'clearable' => true
]);
```

---

### 9. **Validierung**

**Smartform2:**
```php
$formGenerator->setFormData([
    'id' => 'myForm',
    'rules' => [
        'email' => [
            ['type' => 'empty', 'prompt' => 'E-Mail erforderlich'],
            ['type' => 'email', 'prompt' => 'Ungültige E-Mail']
        ],
        'password' => [
            ['type' => 'minLength[8]', 'prompt' => 'Mindestens 8 Zeichen']
        ]
    ]
]);
```

**EasyForm:**
```php
$form->email('email', 'E-Mail', [
    'required' => true,
    'rules' => ['email']
]);

$form->password('password', 'Passwort', [
    'required' => true,
    'rules' => ['minLength[8]']
]);
```

---

### 10. **Tabs & Gruppen**

**Smartform2:**
```php
// Nicht direkt unterstützt - manuelles HTML nötig
```

**EasyForm:**
```php
$form->group('personal', 'Persönliche Daten', ['type' => 'tab', 'icon' => 'user'])
    ->text('first_name', 'Vorname')
    ->text('last_name', 'Nachname')
->endGroup();

$form->group('contact', 'Kontakt', ['type' => 'tab', 'icon' => 'envelope'])
    ->email('email', 'E-Mail')
    ->tel('phone', 'Telefon')
->endGroup();
```

---

## 📊 ListGenerator Migration

### Smartform2 ListGenerator

```php
$listGenerator = new ListGenerator([
    'listId' => 'userList',
    'itemsPerPage' => 10,
    'sortColumn' => 'id',
    'sortDirection' => 'ASC'
]);

$listGenerator->setData($users);
$listGenerator->setSearchableColumns(['name', 'email']);
$listGenerator->addColumn('id', 'ID');
$listGenerator->addColumn('name', 'Name');
$listGenerator->addButton('edit', [
    'icon' => 'edit',
    'class' => 'ui blue button',
    'modalId' => 'editModal'
]);

echo $listGenerator->generateList();
```

### EasyForm EasyList

```php
use EasyForm\EasyList;

$list = new EasyList('userList');
$list->data($users)
    ->column('id', 'ID', ['type' => 'number', 'width' => '60px'])
    ->column('name', 'Name')
    ->column('email', 'Email')
    ->searchable(true, 'Suchen...')
    ->sortable(true)
    ->paginate(true, 10)
    ->exportable(true) // ✅ Bonus: CSV/Excel/PDF Export
    ->display();
```

---

## ✨ Neue Features in EasyForm

### 1. **Visual Builder**

EasyForm bietet einen visuellen Drag & Drop Builder:

```
http://localhost:8081/form_builder.php
http://localhost:8081/list_generator.php
```

### 2. **CSRF Protection**

Automatisch aktiviert wenn Session aktiv:

```php
$form = new EasyForm('secureForm');
// CSRF Token wird automatisch hinzugefügt
```

### 3. **Export-Funktionen**

```php
$list->exportable(true); // CSV, Excel, PDF
```

### 4. **Multi-Theme Support**

```php
$form = new EasyForm('myForm', [
    'theme' => 'semantic', // semantic, bootstrap, material
    'size' => 'large',
    'width' => 800
]);
```

### 5. **Mehr Feldtypen**

```php
$form->tel('phone', 'Telefon');
$form->url('website', 'Website');
$form->date('birthday', 'Geburtstag');
$form->time('appointment', 'Termin');
$form->month('period', 'Zeitraum');
$form->week('calendar_week', 'Kalenderwoche');
$form->color('brand_color', 'Markenfarbe');
$form->search('query', 'Suche');
$form->file('upload', 'Datei', [
    'accept' => 'image/*',
    'multiple' => true,
    'preview' => true,
    'dragDrop' => true
]);
```

---

## 🎯 Best Practices

### 1. **Immer Fluent Interface verwenden**

❌ **Schlecht:**
```php
$form = new EasyForm('myForm');
$form->text('name', 'Name');
$form->email('email', 'E-Mail');
$form->submit('Absenden');
$form->display();
```

✅ **Gut:**
```php
$form = new EasyForm('myForm');
$form->text('name', 'Name')
     ->email('email', 'E-Mail')
     ->submit('Absenden')
     ->display();
```

### 2. **AJAX mit Callbacks**

```php
$form->ajax([
    'url' => 'process.php',
    'method' => 'POST',
    'success' => "function(response) {
        if (response.success) {
            alert('Formular erfolgreich gesendet!');
        }
    }",
    'error' => "function(error) {
        console.error('Fehler:', error);
    }"
]);
```

### 3. **Validierung kombinieren**

```php
$form->email('email', 'E-Mail', [
    'required' => true,
    'rules' => ['email', 'maxLength[100]']
]);
```

---

## 🔧 Troubleshooting

### Deprecation Warnings (PHP 8.3)

✅ **Alle gefixed!** EasyForm nutzt jetzt:
- Nullable Type Hints: `?int $width = null`
- Union Types: `string|array $rules`
- Strict Type Declarations

### jQuery nicht gefunden

```html
<!-- Stelle sicher jQuery VOR EasyForm geladen wird -->
<script src="jquery/jquery.min.js"></script>
<script src="semantic/dist/semantic.min.js"></script>
<!-- Form HTML hier -->
```

### Autoload Path

```php
// Relativ zum Projekt-Root
require_once __DIR__ . '/easyform/autoload.php';

// Oder mit Composer
// require_once __DIR__ . '/vendor/autoload.php';
```

---

## 📦 Deployment

### 1. **EasyForm Installation**

```bash
cd /path/to/project
git clone https://github.com/mmollay/easyform.git
```

### 2. **Dependencies**

```bash
# Falls Composer verwendet wird
cd easyform
composer install
```

### 3. **Integration**

```php
// In deinem Projekt
require_once __DIR__ . '/easyform/autoload.php';
use EasyForm\EasyForm;
use EasyForm\EasyList;
```

---

## 🎓 Weitere Ressourcen

- **Live Demo:** http://localhost:8081/
- **Visual Form Builder:** http://localhost:8081/form_builder.php
- **List Generator:** http://localhost:8081/list_generator.php
- **Dokumentation:** http://localhost:8081/docs/
- **Beispiele:** `/easyform/tests/`

---

## ✅ Checkliste für Migration

- [ ] EasyForm Repository klonen
- [ ] Autoload einbinden
- [ ] Namespace `use EasyForm\EasyForm;` hinzufügen
- [ ] Array-basierte Syntax zu Fluent Interface umstellen
- [ ] `setFormData()` durch `action()`, `method()` ersetzen
- [ ] `addField()` durch spezifische Methoden ersetzen (`text()`, `email()`, etc.)
- [ ] `generateForm()` durch `display()` ersetzen
- [ ] Visual Builder für neue Formulare testen
- [ ] CSRF Protection verifizieren
- [ ] Tests durchführen

---

## 🚀 Support

Bei Fragen oder Problemen:
- GitHub Issues: https://github.com/mmollay/easyform/issues
- Dokumentation: `/easyform/docs/`

---

**Happy Coding! 🎉**

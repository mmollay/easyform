# EasyForm Konfiguration

## Basis-Konfiguration

```php
use EasyForm\EasyForm;

$form = new EasyForm('my_form', [
    // Konfigurationsoptionen hier
]);
```

## Verfügbare Optionen

### Layout & Design

| Option | Typ | Standard | Beschreibung | Mögliche Werte |
|--------|-----|----------|--------------|----------------|
| `theme` | string | 'semantic' | UI Framework Theme | 'semantic', 'bootstrap', 'material' |
| `size` | string | 'medium' | Formular-Größe | 'mini', 'tiny', 'small', 'medium', 'large', 'huge' |
| `width` | int/null | null | Maximale Breite in Pixeln | z.B. 600, 800, null (100%) |
| `class` | string | '' | Zusätzliche CSS-Klassen | Beliebige CSS-Klassen |

### Verhalten

| Option | Typ | Standard | Beschreibung |
|--------|-----|----------|--------------|
| `autocomplete` | bool | true | Browser-Autovervollständigung aktivieren |
| `showErrors` | bool | true | Fehler anzeigen |
| `liveValidation` | bool | true | Live-Validierung während der Eingabe |
| `submitButton` | bool | true | Submit-Button automatisch hinzufügen |
| `resetButton` | bool | false | Reset-Button automatisch hinzufügen |

### Sprache

| Option | Typ | Standard | Beschreibung | Mögliche Werte |
|--------|-----|----------|--------------|----------------|
| `language` | string | 'de' | Sprache für Meldungen | 'de', 'en', 'fr', etc. |

## Beispiele

### Einfaches Formular mit Breite

```php
$form = new EasyForm('contact_form', [
    'width' => 600
]);
```

### Großes Formular mit Bootstrap Theme

```php
$form = new EasyForm('registration', [
    'theme' => 'bootstrap',
    'size' => 'large',
    'width' => 800,
    'resetButton' => true
]);
```

### Formular ohne automatische Buttons

```php
$form = new EasyForm('custom_form', [
    'submitButton' => false,
    'resetButton' => false,
    'liveValidation' => false
]);
```

### Mehrsprachiges Formular

```php
$form = new EasyForm('international', [
    'language' => 'en',
    'showErrors' => true,
    'autocomplete' => false
]);
```

## Erweiterte Konfiguration

### AJAX-Konfiguration

```php
$form->ajax([
    'url' => 'process.php',
    'method' => 'POST',
    'dataType' => 'json',
    'success' => 'function(response) { ... }',
    'error' => 'function(xhr) { ... }',
    'beforeSend' => 'function() { ... }',
    'complete' => 'function() { ... }'
]);
```

### Method & Action

```php
$form->method('POST')
     ->action('submit.php')
     ->enctype('multipart/form-data'); // Für Datei-Uploads
```

## Feldspezifische Optionen

Jedes Feld kann eigene Optionen haben:

```php
$form->text('username', 'Benutzername', [
    'placeholder' => 'Ihr Benutzername',
    'required' => true,
    'icon' => 'user',
    'help' => 'Mindestens 3 Zeichen',
    'pattern' => '[a-zA-Z0-9]{3,}',
    'class' => 'special-field',
    'disabled' => false,
    'readonly' => false,
    'value' => 'Standard-Wert'
]);
```

## Styling-Klassen

### Semantic UI Größen
- `mini` - Sehr klein
- `tiny` - Klein
- `small` - Etwas kleiner
- `medium` - Standard
- `large` - Groß
- `huge` - Sehr groß
- `massive` - Riesig

### Zusätzliche Klassen
```php
$form = new EasyForm('styled', [
    'class' => 'ui segment padded raised'
]);
```

## Validierungsregeln

```php
$form->rule('email', 'required|email')
     ->rule('age', 'required|number|min:18|max:99')
     ->rule('username', 'required|minlength:3|maxlength:20')
     ->rule('password', 'required|minlength:8')
     ->rule('password_confirm', 'required|match:password');
```

## Gruppen und Layouts

```php
// Gruppe erstellen
$form->group('personal', 'Persönliche Daten', [
    'type' => 'segment', // oder 'tab', 'accordion'
    'icon' => 'user'
]);

// Grid-Layout
$form->row(['columns' => 2])
     ->col()
         ->text('firstname', 'Vorname')
     ->endCol()
     ->col()
         ->text('lastname', 'Nachname')
     ->endCol()
->endRow();
```

## Conditional Fields

```php
$form->select('has_car', 'Auto vorhanden?', [
    'yes' => 'Ja',
    'no' => 'Nein'
]);

$form->text('car_brand', 'Automarke', [
    'showIf' => 'has_car:yes'
]);
```

## Custom HTML

```php
$form->html('<div class="ui message info">
    <p>Wichtiger Hinweis hier</p>
</div>');
```

## Events

```php
$form->on('submit', 'function(e) {
    console.log("Form submitted");
}');

$form->on('change', '#field_name', 'function() {
    console.log("Field changed");
}');
```

## Lokalisierung

Erstellen Sie eine Sprachdatei:

```php
// lang/de.php
return [
    'required' => 'Dieses Feld ist erforderlich',
    'email' => 'Bitte geben Sie eine gültige E-Mail-Adresse ein',
    'minlength' => 'Mindestens {0} Zeichen erforderlich',
    // ...
];
```

## Performance-Optimierung

```php
$form = new EasyForm('optimized', [
    'liveValidation' => false, // Deaktiviert Live-Validierung
    'showErrors' => false,     // Keine sofortigen Fehler
    'autocomplete' => false    // Kein Autocomplete
]);
```

## Debug-Modus

```php
$form->debug(true); // Zeigt Debug-Informationen
```

---

Weitere Informationen finden Sie in der [Dokumentation](docs/) oder den [Beispielen](examples/).
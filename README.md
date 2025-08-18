# 🚀 EasyForm & EasyList - Moderner PHP Form & Data Table Generator

<div align="center">

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D7.4-blue.svg)](https://php.net)
[![Semantic UI](https://img.shields.io/badge/Semantic%20UI-2.9.3-teal.svg)](https://semantic-ui.com)
[![Made with Love](https://img.shields.io/badge/Made%20with-❤️-red.svg)]()

**Erstellen Sie professionelle Formulare und Datentabellen in Sekunden - ohne HTML/CSS schreiben zu müssen!**

[🎮 Live Demo](http://localhost/easy_form) | [📚 Dokumentation](http://localhost/easy_form/docs) | [🎨 Form Builder](http://localhost/easy_form/form_builder.php) | [📊 List Generator](http://localhost/easy_form/list_generator.php)

</div>

---

EasyForm ist ein leistungsstarker und intuitiver PHP Form Generator mit Fluent Interface, visuellen Buildern und über 50 Feldtypen. EasyList ergänzt das System mit einem mächtigen Datentabellen-Generator.

## 🚀 Features

- **Fluent Interface** - Elegante Method Chaining API
- **50+ Feldtypen** - Von einfachen Inputs bis zu komplexen Komponenten
- **Visual Builder** - Drag & Drop Form Builder
- **AJAX Support** - Nahtlose Integration für moderne Apps
- **Validierung** - Client- und serverseitige Validierung
- **Responsive** - Mobile-first Design mit Semantic UI
- **Mehrsprachig** - Vollständig lokalisierbar
- **Sicher** - CSRF-Schutz und XSS-Prevention

## 📦 Installation

### Voraussetzungen

- PHP >= 7.4
- Webserver (Apache/Nginx)
- Modern Browser (Chrome, Firefox, Safari, Edge)

### Quick Start

1. Repository klonen:
```bash
git clone https://github.com/mmollay/easyform.git
cd easyform
```

2. In Ihr Webserver-Verzeichnis verschieben:
```bash
cp -r easyform /pfad/zu/ihrem/webroot/
```

3. Im Browser öffnen:
```
http://localhost/easyform/
```

## 🎯 Verwendung

### Einfaches Beispiel

```php
<?php
use EasyForm\EasyForm;

$form = new EasyForm('contact');

$form->heading('Kontaktformular')
     ->text('name', 'Ihr Name', ['required' => true])
     ->email('email', 'E-Mail', ['required' => true])
     ->textarea('message', 'Nachricht', ['rows' => 5])
     ->submit('Senden');

$form->display();
```

### Mit AJAX

```php
$form->ajax([
    'url' => 'process.php',
    'success' => 'function(response) { 
        alert("Formular erfolgreich gesendet!"); 
    }'
]);
```

### Visual Form Builder

Nutzen Sie den visuellen Form Builder für die Erstellung ohne Code:

```
http://localhost/easyform/form_builder.php
```

## 📖 Dokumentation

Ausführliche Dokumentation und Beispiele finden Sie unter:
- [Live Demos](http://localhost/easyform/examples/)
- [API Dokumentation](http://localhost/easyform/docs/)
- [Form Builder](http://localhost/easyform/form_builder.php)
- [List Generator](http://localhost/easyform/list_generator.php)

## 🏗️ Projektstruktur

```
easyform/
├── src/                 # PHP Klassen
│   ├── EasyForm.php    # Hauptklasse
│   ├── EasyList.php    # Datentabellen
│   └── ...
├── examples/           # Beispiele und Demos
├── docs/              # Dokumentation
├── assets/            # CSS, JS, Bilder
├── semantic/          # Semantic UI Framework
└── jquery/            # jQuery Library
```

## 🔧 Konfiguration

### Basis-Konfiguration

```php
$form = new EasyForm('my_form', [
    'width' => 600,
    'theme' => 'semantic',
    'language' => 'de',
    'ajax' => true
]);
```

### Verfügbare Optionen

- `width` - Formularbreite in Pixel
- `theme` - UI Theme (semantic, bootstrap, material)
- `size` - Größe (mini, tiny, small, medium, large, huge)
- `language` - Sprache (de, en, fr, etc.)
- `ajax` - AJAX-Submit aktivieren
- `liveValidation` - Live-Validierung aktivieren

## 🎨 Themes

EasyForm unterstützt verschiedene UI Themes:
- Semantic UI (Standard)
- Bootstrap (in Entwicklung)
- Material Design (geplant)

## 🔒 Sicherheit

EasyForm implementiert mehrere Sicherheitsmaßnahmen:
- CSRF Token Protection
- XSS Prevention
- SQL Injection Prevention (bei Datenbankoperationen)
- Input Sanitization

## 🤝 Beitragen

Contributions sind willkommen! Bitte lesen Sie [CONTRIBUTING.md](CONTRIBUTING.md) für Details.

1. Fork das Projekt
2. Erstellen Sie einen Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit Ihre Änderungen (`git commit -m 'Add some AmazingFeature'`)
4. Push zum Branch (`git push origin feature/AmazingFeature`)
5. Öffnen Sie einen Pull Request

## 📝 Lizenz

Dieses Projekt ist unter der MIT-Lizenz lizenziert - siehe [LICENSE](LICENSE) für Details.

## 👏 Credits

- Entwickelt mit ❤️ von der EasyForm Community
- Basiert auf [Semantic UI](https://semantic-ui.com/)
- Verwendet [jQuery](https://jquery.com/)
- Icons von [Font Awesome](https://fontawesome.com/)

## 📧 Support

Bei Fragen oder Problemen:
- 🐛 [Issue erstellen](https://github.com/mmollay/easyform/issues)
- 📧 Email: support@easyform.com
- 💬 [Discussions](https://github.com/mmollay/easyform/discussions)

## 🚦 Status

![Build Status](https://img.shields.io/badge/build-passing-brightgreen)
![Tests](https://img.shields.io/badge/tests-passing-brightgreen)
![Coverage](https://img.shields.io/badge/coverage-85%25-yellow)

---

Made with ❤️ for modern PHP developers
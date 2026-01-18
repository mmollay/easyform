/**
 * EasyForm i18n System
 * Zentrales Übersetzungssystem für alle Seiten
 */

class EasyFormI18n {
  constructor() {
    this.currentLang = localStorage.getItem("easyform_lang") || "de";
    this.translations = this.loadTranslations();
    this.init();
  }

  loadTranslations() {
    return {
      de: {
        // Global
        "global.save": "Speichern",
        "global.load": "Laden",
        "global.clear": "Leeren",
        "global.delete": "Löschen",
        "global.cancel": "Abbrechen",
        "global.close": "Schließen",
        "global.back": "Zurück",
        "global.next": "Weiter",
        "global.preview": "Vorschau",
        "global.export": "Exportieren",
        "global.copy": "Kopieren",
        "global.config": "Konfiguration",

        // Navigation
        "nav.home": "Startseite",
        "nav.formbuilder": "Form Builder",
        "nav.listgenerator": "List Generator",
        "nav.docs": "Dokumentation",
        "nav.examples": "Beispiele",
        "nav.health": "Status",
        "nav.features": "Features",
        "nav.tools": "Tools",

        // Form Builder
        "builder.title": "EasyForm Builder - Drag & Drop Formular Generator",
        "builder.fields": "Formularfelder",
        "builder.properties": "Eigenschaften",
        "builder.preview": "Vorschau",
        "builder.code": "Generierter Code",
        "builder.templates": "Vorlagen",
        "builder.save_template": "Vorlage speichern",
        "builder.load_template": "Vorlage laden",
        "builder.clear_form": "Formular leeren",
        "builder.config_form": "Formular konfigurieren",

        // Configuration Modal
        "config.title": "Formular-Konfiguration",
        "config.basic_settings": "Basis-Einstellungen",
        "config.form_id": "Formular ID",
        "config.theme": "Theme",
        "config.width": "Breite (px)",
        "config.size": "Größe",
        "config.size.mini": "Mini",
        "config.size.tiny": "Klein",
        "config.size.small": "Klein-Mittel",
        "config.size.medium": "Mittel",
        "config.size.large": "Groß",
        "config.size.huge": "Sehr groß",
        "config.behavior": "Verhalten",
        "config.autocomplete": "Autovervollständigung",
        "config.show_errors": "Fehler anzeigen",
        "config.live_validation": "Live-Validierung",
        "config.submit_button": "Submit Button",
        "config.reset_button": "Reset Button",
        "config.form_action": "Formular Aktion",
        "config.action_url": "Action URL",
        "config.method": "Method",
        "config.language": "Sprache",
        "config.additional_css": "Zusätzliche CSS-Klassen",
        "builder.preview_form": "Formular Vorschau",
        "builder.copy_code": "Code kopieren",
        "builder.drop_here": "Felder hier ablegen",
        "builder.no_fields": "Noch keine Felder vorhanden",
        "builder.components": "Komponenten",
        "builder.drag_components": "Komponenten hier hineinziehen",
        "builder.drag_info":
          "Ziehen Sie Felder aus der linken Sidebar in diesen Bereich",
        "builder.formbuilder": "Formular Builder",

        // Component Groups
        "components.input_fields": "Eingabefelder",
        "components.selection": "Auswahl",
        "components.layout": "Layout",
        "components.actions": "Aktionen",

        // Components
        "components.textfield": "Textfeld",
        "components.email": "E-Mail",
        "components.password": "Passwort",
        "components.phone": "Telefon",
        "components.number": "Zahl",
        "components.url": "URL",
        "components.date": "Datum",
        "components.textarea": "Textarea",
        "components.dropdown": "Dropdown",
        "components.checkbox": "Checkbox",
        "components.radio": "Radio Button",
        "components.range": "Range Slider",
        "components.heading": "Überschrift",
        "components.divider": "Trennlinie",
        "components.html": "HTML Content",
        "components.gridrow": "Grid Row",
        "components.submit": "Submit Button",
        "components.reset": "Reset Button",

        // Field Types
        "fields.basic": "Basis Felder",
        "fields.advanced": "Erweiterte Felder",
        "fields.layout": "Layout",
        "fields.text": "Text",
        "fields.email": "E-Mail",
        "fields.password": "Passwort",
        "fields.textarea": "Textbereich",
        "fields.select": "Auswahl",
        "fields.checkbox": "Checkbox",
        "fields.radio": "Radio",
        "fields.date": "Datum",
        "fields.time": "Zeit",
        "fields.file": "Datei",
        "fields.number": "Zahl",
        "fields.url": "URL",
        "fields.tel": "Telefon",
        "fields.color": "Farbe",
        "fields.range": "Bereich",
        "fields.button": "Button",
        "fields.heading": "Überschrift",
        "fields.paragraph": "Absatz",
        "fields.divider": "Trennlinie",
        "fields.group": "Gruppe",
        "fields.html": "HTML",

        // Properties
        "props.label": "Beschriftung",
        "props.name": "Name",
        "props.id": "ID",
        "props.placeholder": "Platzhalter",
        "props.required": "Erforderlich",
        "props.readonly": "Nur lesen",
        "props.disabled": "Deaktiviert",
        "props.value": "Wert",
        "props.options": "Optionen",
        "props.rows": "Zeilen",
        "props.cols": "Spalten",
        "props.min": "Minimum",
        "props.max": "Maximum",
        "props.step": "Schrittweite",
        "props.pattern": "Muster",
        "props.accept": "Akzeptieren",
        "props.multiple": "Mehrfach",
        "props.autocomplete": "Autovervollständigung",

        // List Generator
        "list.title": "EasyList Generator - Datentabellen Builder",
        "list.columns": "Spalten",
        "list.addColumn": "Spalte hinzufügen",
        "list.add_column": "Spalte hinzufügen",
        "list.remove_column": "Spalte entfernen",
        "list.column_name": "Spaltenname",
        "list.column_label": "Spaltenbeschriftung",
        "list.data_type": "Datentyp",
        "list.sortable": "Sortierbar",
        "list.searchable": "Durchsuchbar",
        "list.filterable": "Filterbar",
        "list.actions": "Aktionen",
        "list.pagination": "Seitennummerierung",
        "list.export_csv": "CSV Export",
        "list.export_excel": "Excel Export",
        "list.export_pdf": "PDF Export",
        "list.styling": "Tabellen-Styling",
        "list.designOptions": "Design-Optionen",
        "list.striped": "Gestreift",
        "list.celled": "Mit Rahmen (Celled)",
        "list.compact": "Kompakt",
        "list.hoverEffect": "Hover-Effekt",
        "list.layout": "Layout",
        "list.fixed": "Fixiert",
        "list.singleLine": "Einzeilig",
        "list.collapsing": "Kollabierend",
        "list.responsive": "Responsive",
        "list.color": "Farbe",
        "list.size": "Größe",
        "list.cellPadding": "Zellenabstand",
        "list.standard": "Standard",
        "list.small": "Klein",
        "list.large": "Groß",
        "list.red": "Rot",
        "list.orange": "Orange",
        "list.yellow": "Gelb",
        "list.green": "Grün",
        "list.blue": "Blau",
        "list.violet": "Violett",
        "list.purple": "Lila",
        "list.pink": "Pink",
        "list.teal": "Teal",
        "list.grey": "Grau",
        "list.black": "Schwarz",
        "list.features": "Features",
        "list.searchField": "Suchfeld",
        "list.sorting": "Sortierung",
        "list.filter": "Filter",
        "list.export": "Export (CSV/Excel)",
        "list.rowSelection": "Zeilen-Auswahl",
        "list.actionButtons": "Action-Buttons",
        "list.actionsPosition": "Position der Aktionen:",

        // Messages
        "msg.success": "Erfolgreich gespeichert!",
        "msg.error": "Ein Fehler ist aufgetreten",
        "msg.confirm": "Sind Sie sicher?",
        "msg.loading": "Wird geladen...",
        "msg.copied": "In Zwischenablage kopiert!",
        "msg.template_saved": "Vorlage wurde gespeichert",
        "msg.template_loaded": "Vorlage wurde geladen",
        "msg.form_cleared": "Formular wurde geleert",
        "msg.no_fields": "Bitte fügen Sie mindestens ein Feld hinzu",

        // Examples Page
        "examples.title": "EasyForm Beispiele",
        "examples.subtitle":
          "Entdecken Sie die Vielfalt und Leistungsfähigkeit von EasyForm anhand unserer interaktiven Demos",
        "examples.forms": "📝 Formular Beispiele",
        "examples.lists": "📊 Listen & Tabellen",
        "examples.builders": "🛠️ Visual Builder",
        "examples.resources": "📚 Weitere Ressourcen",
        "examples.contact.title": "Einfaches Kontaktformular",
        "examples.contact.desc":
          "Grundlegendes Kontaktformular mit den wichtigsten Feldtypen und AJAX-Submit",
        "examples.application.title": "Bewerbungsformular mit Tabs",
        "examples.application.desc":
          "Komplexes Formular mit Tabs, Gruppen, bedingten Feldern und Datei-Upload",
        "examples.registration.title": "AJAX Registrierung",
        "examples.registration.desc":
          "Dynamisches Registrierungsformular mit Live-Validierung und AJAX-Processing",
        "examples.datalist.title": "Einfache Datenliste",
        "examples.datalist.desc":
          "Grundlegende Datentabelle mit Sortierung, Suche und Pagination",
        "examples.advancedlist.title": "Erweiterte Datentabelle",
        "examples.advancedlist.desc":
          "Professionelle Tabelle mit Filtern, Export-Funktionen und Gruppierung",
        "examples.formbuilder.title": "Form Builder",
        "examples.formbuilder.desc":
          "Visueller Drag & Drop Form Builder für die schnelle Formularerstellung ohne Code",
        "examples.listgen.title": "List Generator",
        "examples.listgen.desc":
          "Visueller Generator für dynamische Datentabellen mit allen Features",
        "examples.docs.title": "Dokumentation",
        "examples.docs.desc":
          "Ausführliche Dokumentation mit API-Referenz und Tutorials",
        "examples.github.title": "GitHub Repository",
        "examples.github.desc":
          "Source Code, Issues und Contributions auf GitHub",
        "examples.level.beginner": "Anfänger",
        "examples.level.advanced": "Fortgeschritten",
        "examples.tag.tool": "Tool",
        "examples.tag.dragdrop": "Drag & Drop",
        "examples.tag.visual": "Visual",
        "examples.tag.ajax": "AJAX",
        "examples.tag.validation": "Validierung",
        "examples.tag.tabs": "Tabs",
        "examples.tag.fileupload": "File Upload",
        "examples.tag.livevalidation": "Live Validation",
        "examples.tag.password": "Passwort",
        "examples.tag.sorting": "Sortierung",
        "examples.tag.search": "Suche",
        "examples.tag.export": "Export",
        "examples.tag.filter": "Filter",
        "examples.tag.generator": "Generator",
        "examples.tag.api": "API",
        "examples.tag.tutorial": "Tutorial",
        "examples.tag.reference": "Reference",
        "examples.tag.opensource": "Open Source",
        "examples.tag.github": "GitHub",
        "examples.tag.license": "MIT License",

        // Health Check
        "health.title": "🏥 EasyForm Health Check",
        "health.subtitle": "Systemdiagnose und Überprüfung",
        "health.detailedResults": "Detaillierte Ergebnisse",
        "health.systemInfo": "System Information",
        "health.nextSteps": "Nächste Schritte",
        "health.successful": "Erfolgreich",
        "health.warnings": "Warnungen",
        "health.errors": "Fehler",

        // Footer
        "footer.docs": "Dokumentation",
        "footer.examples": "Beispiele",
        "footer.github": "GitHub",
        "footer.license": "Lizenz",
        "footer.copyright": "© 2026 EasyForm. Alle Rechte vorbehalten.",
      },
      en: {
        // Global
        "global.save": "Save",
        "global.load": "Load",
        "global.clear": "Clear",
        "global.delete": "Delete",
        "global.cancel": "Cancel",
        "global.close": "Close",
        "global.back": "Back",
        "global.next": "Next",
        "global.preview": "Preview",
        "global.export": "Export",
        "global.copy": "Copy",
        "global.config": "Configuration",

        // Navigation
        "nav.home": "Home",
        "nav.formbuilder": "Form Builder",
        "nav.listgenerator": "List Generator",
        "nav.docs": "Documentation",
        "nav.examples": "Examples",
        "nav.health": "Status",
        "nav.features": "Features",
        "nav.tools": "Tools",

        // Form Builder
        "builder.title": "EasyForm Builder - Drag & Drop Form Generator",
        "builder.fields": "Form Fields",
        "builder.properties": "Properties",
        "builder.preview": "Preview",
        "builder.code": "Generated Code",
        "builder.templates": "Templates",
        "builder.save_template": "Save Template",
        "builder.load_template": "Load Template",
        "builder.clear_form": "Clear Form",
        "builder.config_form": "Configure Form",

        // Configuration Modal
        "config.title": "Form Configuration",
        "config.basic_settings": "Basic Settings",
        "config.form_id": "Form ID",
        "config.theme": "Theme",
        "config.width": "Width (px)",
        "config.size": "Size",
        "config.size.mini": "Mini",
        "config.size.tiny": "Tiny",
        "config.size.small": "Small",
        "config.size.medium": "Medium",
        "config.size.large": "Large",
        "config.size.huge": "Huge",
        "config.behavior": "Behavior",
        "config.autocomplete": "Autocomplete",
        "config.show_errors": "Show Errors",
        "config.live_validation": "Live Validation",
        "config.submit_button": "Submit Button",
        "config.reset_button": "Reset Button",
        "config.form_action": "Form Action",
        "config.action_url": "Action URL",
        "config.method": "Method",
        "config.language": "Language",
        "config.additional_css": "Additional CSS Classes",
        "builder.preview_form": "Preview Form",
        "builder.copy_code": "Copy Code",
        "builder.drop_here": "Drop fields here",
        "builder.no_fields": "No fields yet",
        "builder.components": "Components",
        "builder.drag_components": "Drag components here",
        "builder.drag_info": "Drag fields from the left sidebar into this area",
        "builder.formbuilder": "Form Builder",

        // Component Groups
        "components.input_fields": "Input Fields",
        "components.selection": "Selection",
        "components.layout": "Layout",
        "components.actions": "Actions",

        // Components
        "components.textfield": "Text Field",
        "components.email": "Email",
        "components.password": "Password",
        "components.phone": "Phone",
        "components.number": "Number",
        "components.url": "URL",
        "components.date": "Date",
        "components.textarea": "Textarea",
        "components.dropdown": "Dropdown",
        "components.checkbox": "Checkbox",
        "components.radio": "Radio Button",
        "components.range": "Range Slider",
        "components.heading": "Heading",
        "components.divider": "Divider",
        "components.html": "HTML Content",
        "components.gridrow": "Grid Row",
        "components.submit": "Submit Button",
        "components.reset": "Reset Button",

        // Field Types
        "fields.basic": "Basic Fields",
        "fields.advanced": "Advanced Fields",
        "fields.layout": "Layout",
        "fields.text": "Text",
        "fields.email": "Email",
        "fields.password": "Password",
        "fields.textarea": "Textarea",
        "fields.select": "Select",
        "fields.checkbox": "Checkbox",
        "fields.radio": "Radio",
        "fields.date": "Date",
        "fields.time": "Time",
        "fields.file": "File",
        "fields.number": "Number",
        "fields.url": "URL",
        "fields.tel": "Phone",
        "fields.color": "Color",
        "fields.range": "Range",
        "fields.button": "Button",
        "fields.heading": "Heading",
        "fields.paragraph": "Paragraph",
        "fields.divider": "Divider",
        "fields.group": "Group",
        "fields.html": "HTML",

        // Properties
        "props.label": "Label",
        "props.name": "Name",
        "props.id": "ID",
        "props.placeholder": "Placeholder",
        "props.required": "Required",
        "props.readonly": "Read Only",
        "props.disabled": "Disabled",
        "props.value": "Value",
        "props.options": "Options",
        "props.rows": "Rows",
        "props.cols": "Columns",
        "props.min": "Minimum",
        "props.max": "Maximum",
        "props.step": "Step",
        "props.pattern": "Pattern",
        "props.accept": "Accept",
        "props.multiple": "Multiple",
        "props.autocomplete": "Autocomplete",

        // List Generator
        "list.title": "EasyList Generator - Data Table Builder",
        "list.columns": "Columns",
        "list.addColumn": "Add Column",
        "list.add_column": "Add Column",
        "list.remove_column": "Remove Column",
        "list.column_name": "Column Name",
        "list.column_label": "Column Label",
        "list.data_type": "Data Type",
        "list.sortable": "Sortable",
        "list.searchable": "Searchable",
        "list.filterable": "Filterable",
        "list.actions": "Actions",
        "list.pagination": "Pagination",
        "list.export_csv": "CSV Export",
        "list.export_excel": "Excel Export",
        "list.export_pdf": "PDF Export",
        "list.styling": "Table Styling",
        "list.designOptions": "Design Options",
        "list.striped": "Striped",
        "list.celled": "With Border (Celled)",
        "list.compact": "Compact",
        "list.hoverEffect": "Hover Effect",
        "list.layout": "Layout",
        "list.fixed": "Fixed",
        "list.singleLine": "Single Line",
        "list.collapsing": "Collapsing",
        "list.responsive": "Responsive",
        "list.color": "Color",
        "list.size": "Size",
        "list.cellPadding": "Cell Padding",
        "list.standard": "Standard",
        "list.small": "Small",
        "list.large": "Large",
        "list.red": "Red",
        "list.orange": "Orange",
        "list.yellow": "Yellow",
        "list.green": "Green",
        "list.blue": "Blue",
        "list.violet": "Violet",
        "list.purple": "Purple",
        "list.pink": "Pink",
        "list.teal": "Teal",
        "list.grey": "Grey",
        "list.black": "Black",
        "list.features": "Features",
        "list.searchField": "Search Field",
        "list.sorting": "Sorting",
        "list.filter": "Filter",
        "list.export": "Export (CSV/Excel)",
        "list.rowSelection": "Row Selection",
        "list.actionButtons": "Action Buttons",
        "list.actionsPosition": "Actions Position:",

        // Messages
        "msg.success": "Successfully saved!",
        "msg.error": "An error occurred",
        "msg.confirm": "Are you sure?",
        "msg.loading": "Loading...",
        "msg.copied": "Copied to clipboard!",
        "msg.template_saved": "Template has been saved",
        "msg.template_loaded": "Template has been loaded",
        "msg.form_cleared": "Form has been cleared",
        "msg.no_fields": "Please add at least one field",

        // Examples Page
        "examples.title": "EasyForm Examples",
        "examples.subtitle":
          "Discover the variety and power of EasyForm through our interactive demos",
        "examples.forms": "📝 Form Examples",
        "examples.lists": "📊 Lists & Tables",
        "examples.builders": "🛠️ Visual Builder",
        "examples.resources": "📚 More Resources",
        "examples.contact.title": "Simple Contact Form",
        "examples.contact.desc":
          "Basic contact form with essential field types and AJAX submit",
        "examples.application.title": "Application Form with Tabs",
        "examples.application.desc":
          "Complex form with tabs, groups, conditional fields and file upload",
        "examples.registration.title": "AJAX Registration",
        "examples.registration.desc":
          "Dynamic registration form with live validation and AJAX processing",
        "examples.datalist.title": "Simple Data List",
        "examples.datalist.desc":
          "Basic data table with sorting, search and pagination",
        "examples.advancedlist.title": "Advanced Data Table",
        "examples.advancedlist.desc":
          "Professional table with filters, export functions and grouping",
        "examples.formbuilder.title": "Form Builder",
        "examples.formbuilder.desc":
          "Visual drag & drop form builder for quick form creation without code",
        "examples.listgen.title": "List Generator",
        "examples.listgen.desc":
          "Visual generator for dynamic data tables with all features",
        "examples.docs.title": "Documentation",
        "examples.docs.desc":
          "Comprehensive documentation with API reference and tutorials",
        "examples.github.title": "GitHub Repository",
        "examples.github.desc":
          "Source code, issues and contributions on GitHub",
        "examples.level.beginner": "Beginner",
        "examples.level.advanced": "Advanced",
        "examples.tag.tool": "Tool",
        "examples.tag.dragdrop": "Drag & Drop",
        "examples.tag.visual": "Visual",
        "examples.tag.ajax": "AJAX",
        "examples.tag.validation": "Validation",
        "examples.tag.tabs": "Tabs",
        "examples.tag.fileupload": "File Upload",
        "examples.tag.livevalidation": "Live Validation",
        "examples.tag.password": "Password",
        "examples.tag.sorting": "Sorting",
        "examples.tag.search": "Search",
        "examples.tag.export": "Export",
        "examples.tag.filter": "Filter",
        "examples.tag.generator": "Generator",
        "examples.tag.api": "API",
        "examples.tag.tutorial": "Tutorial",
        "examples.tag.reference": "Reference",
        "examples.tag.opensource": "Open Source",
        "examples.tag.github": "GitHub",
        "examples.tag.license": "MIT License",

        // Health Check
        "health.title": "🏥 EasyForm Health Check",
        "health.subtitle": "System Diagnostics and Verification",
        "health.detailedResults": "Detailed Results",
        "health.systemInfo": "System Information",
        "health.nextSteps": "Next Steps",
        "health.successful": "Successful",
        "health.warnings": "Warnings",
        "health.errors": "Errors",

        // Footer
        "footer.docs": "Documentation",
        "footer.examples": "Examples",
        "footer.github": "GitHub",
        "footer.license": "License",
        "footer.copyright": "© 2026 EasyForm. All rights reserved.",
      },
    };
  }

  init() {
    // Create language switcher if not exists
    if (!document.querySelector(".formwerk-lang-switcher")) {
      this.createLanguageSwitcher();
    }

    // Apply current language
    this.applyLanguage(this.currentLang);

    // Listen for language changes from other tabs/windows
    window.addEventListener("storage", (e) => {
      if (e.key === "easyform_lang") {
        this.currentLang = e.newValue;
        this.applyLanguage(this.currentLang);
        this.updateSwitcherButtons();
      }
    });
  }

  createLanguageSwitcher() {
    // Check if nav menu exists, then integrate into it
    const navMenu = document.querySelector(".nav-menu");
    if (navMenu && !document.querySelector(".nav-lang-switcher")) {
      const langSwitcher = document.createElement("li");
      langSwitcher.className = "nav-lang-switcher";
      langSwitcher.innerHTML = `
                <div class="lang-switcher-inline">
                    <button class="lang-btn ${this.currentLang === "de" ? "active" : ""}" data-lang="de" title="Deutsch">
                        <span>🇩🇪</span>
                        <span>DE</span>
                    </button>
                    <button class="lang-btn ${this.currentLang === "en" ? "active" : ""}" data-lang="en" title="English">
                        <span>🇬🇧</span>
                        <span>EN</span>
                    </button>
                </div>
            `;
      navMenu.appendChild(langSwitcher);

      // Add click handlers
      langSwitcher.addEventListener("click", (e) => {
        const btn = e.target.closest(".lang-btn");
        if (btn) {
          const lang = btn.dataset.lang;
          this.switchLanguage(lang);
        }
      });
    } else if (!document.querySelector(".formwerk-lang-switcher")) {
      // Fallback to floating switcher if no nav menu
      const switcher = document.createElement("div");
      switcher.className = "formwerk-lang-switcher";
      switcher.innerHTML = `
                <button class="lang-btn ${this.currentLang === "de" ? "active" : ""}" data-lang="de" title="Deutsch">
                    <span>🇩🇪</span>
                    <span>DE</span>
                </button>
                <button class="lang-btn ${this.currentLang === "en" ? "active" : ""}" data-lang="en" title="English">
                    <span>🇬🇧</span>
                    <span>EN</span>
                </button>
            `;
      document.body.appendChild(switcher);

      // Add click handlers
      switcher.addEventListener("click", (e) => {
        const btn = e.target.closest(".lang-btn");
        if (btn) {
          const lang = btn.dataset.lang;
          this.switchLanguage(lang);
        }
      });
    }

    // Add CSS if not exists
    if (!document.querySelector("#formwerk-lang-styles")) {
      const styles = document.createElement("style");
      styles.id = "formwerk-lang-styles";
      styles.textContent = `
                /* Inline Navigation Switcher */
                .nav-lang-switcher {
                    margin-left: auto;
                    padding-left: 20px;
                }
                
                .lang-switcher-inline {
                    display: flex;
                    gap: 2px;
                    background: rgba(148, 163, 184, 0.1);
                    padding: 3px;
                    border-radius: 25px;
                    border: 1px solid rgba(148, 163, 184, 0.2);
                }
                
                .lang-switcher-inline .lang-btn {
                    display: flex;
                    align-items: center;
                    gap: 5px;
                    padding: 7px 14px;
                    border: none;
                    background: transparent;
                    border-radius: 20px;
                    cursor: pointer;
                    font-weight: 600;
                    font-size: 13px;
                    transition: all 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
                    color: #64748b;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                }
                
                .lang-switcher-inline .lang-btn:hover {
                    background: rgba(148, 163, 184, 0.15);
                    color: #475569;
                }
                
                .lang-switcher-inline .lang-btn.active {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
                }
                
                /* Fallback Floating Switcher */
                .formwerk-lang-switcher {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    z-index: 99999;
                    display: flex;
                    gap: 2px;
                    background: rgba(255, 255, 255, 0.95);
                    backdrop-filter: blur(10px);
                    padding: 4px;
                    border-radius: 30px;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.08);
                    border: 1px solid rgba(255,255,255,0.8);
                }
                
                .formwerk-lang-switcher .lang-btn {
                    display: flex;
                    align-items: center;
                    gap: 6px;
                    padding: 10px 18px;
                    border: none;
                    background: transparent;
                    border-radius: 24px;
                    cursor: pointer;
                    font-weight: 600;
                    font-size: 14px;
                    transition: all 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
                    color: #64748b;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                    position: relative;
                    overflow: hidden;
                }
                
                .formwerk-lang-switcher .lang-btn::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    opacity: 0;
                    transition: opacity 0.3s ease;
                    border-radius: 24px;
                }
                
                .formwerk-lang-switcher .lang-btn:hover {
                    transform: scale(1.05);
                    color: #475569;
                    background: rgba(148, 163, 184, 0.1);
                }
                
                .formwerk-lang-switcher .lang-btn.active {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    transform: scale(1.05);
                    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
                }
                
                .formwerk-lang-switcher .lang-btn.active:hover {
                    transform: scale(1.08);
                    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
                }
                
                .formwerk-lang-switcher .lang-btn span {
                    position: relative;
                    z-index: 1;
                }
                
                /* Responsive adjustments */
                @media (max-width: 768px) {
                    .formwerk-lang-switcher {
                        top: 10px;
                        right: 10px;
                        transform: scale(0.9);
                    }
                }
                
                /* Animation for initial appearance */
                @keyframes slideInRight {
                    from {
                        opacity: 0;
                        transform: translateX(20px);
                    }
                    to {
                        opacity: 1;
                        transform: translateX(0);
                    }
                }
                
                .formwerk-lang-switcher {
                    animation: slideInRight 0.4s ease-out;
                }
            `;
      document.head.appendChild(styles);
    }
  }

  switchLanguage(lang) {
    if (lang === this.currentLang) return;

    this.currentLang = lang;
    localStorage.setItem("easyform_lang", lang);

    this.updateSwitcherButtons();
    this.applyLanguage(lang);

    // Trigger custom event
    window.dispatchEvent(
      new CustomEvent("formwerk:languageChanged", {
        detail: { language: lang },
      }),
    );
  }

  updateSwitcherButtons() {
    document.querySelectorAll(".lang-btn").forEach((btn) => {
      btn.classList.toggle("active", btn.dataset.lang === this.currentLang);
    });
  }

  applyLanguage(lang) {
    const translations = this.translations[lang];
    if (!translations) return;

    // Update all elements with data-i18n attribute
    document.querySelectorAll("[data-i18n]").forEach((element) => {
      const key = element.dataset.i18n;
      if (translations[key]) {
        if (element.tagName === "INPUT" || element.tagName === "TEXTAREA") {
          if (element.placeholder !== undefined) {
            element.placeholder = translations[key];
          }
          if (
            (element.value && element.type === "button") ||
            element.type === "submit"
          ) {
            element.value = translations[key];
          }
        } else {
          element.textContent = translations[key];
        }
      }
    });

    // Update HTML lang attribute
    document.documentElement.lang = lang;

    // Update title if it contains EasyForm or EasyForm
    if (
      document.title.includes("EasyForm") ||
      document.title.includes("EasyForm")
    ) {
      const titles = {
        de: {
          form_builder: "EasyForm - Visueller Formular Builder",
          list_generator: "EasyForm - Datentabellen Generator",
          default: "EasyForm - Professioneller Form & List Generator",
        },
        en: {
          form_builder: "EasyForm - Visual Form Builder",
          list_generator: "EasyForm - Data Table Generator",
          default: "EasyForm - Professional Form & List Generator",
        },
      };

      if (window.location.pathname.includes("form_builder")) {
        document.title = titles[lang].form_builder;
      } else if (window.location.pathname.includes("list_generator")) {
        document.title = titles[lang].list_generator;
      } else {
        document.title = titles[lang].default;
      }
    }
  }

  // Get translation
  t(key) {
    return this.translations[this.currentLang][key] || key;
  }

  // Get current language
  getCurrentLanguage() {
    return this.currentLang;
  }
}

// Initialize on DOM ready
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", () => {
    window.formwerkI18n = new EasyFormI18n();
  });
} else {
  window.formwerkI18n = new EasyFormI18n();
}

// Export for use in other scripts
if (typeof module !== "undefined" && module.exports) {
  module.exports = EasyFormI18n;
}

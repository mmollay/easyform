/**
 * FormWerk Language Switcher
 * Professional bilingual support for DE/EN
 */

class LanguageSwitcher {
    constructor() {
        this.currentLang = localStorage.getItem('formwerk_language') || 'de';
        this.translations = {};
        this.init();
    }

    init() {
        // Load translations
        this.loadTranslations();
        
        // Apply current language
        this.applyLanguage(this.currentLang);
        
        // Setup language switcher UI
        this.setupSwitcher();
        
        // Listen for language changes
        this.bindEvents();
    }

    loadTranslations() {
        // German translations (default)
        this.translations.de = {
            // Navigation
            'nav.home': 'Startseite',
            'nav.formbuilder': 'Formular-Builder',
            'nav.listgenerator': 'Listen-Generator',
            'nav.documentation': 'Dokumentation',
            'nav.examples': 'Beispiele',
            'nav.contact': 'Kontakt',
            
            // Hero Section
            'hero.title': 'Professionelle Formulare & Datentabellen',
            'hero.subtitle': 'Erstellen Sie beeindruckende Formulare und Datentabellen ohne Code',
            'hero.cta.primary': 'Jetzt starten',
            'hero.cta.secondary': 'Live Demo',
            
            // Features
            'features.title': 'Leistungsstarke Funktionen',
            'features.dragdrop': 'Drag & Drop Builder',
            'features.responsive': 'Vollständig Responsiv',
            'features.validation': 'Intelligente Validierung',
            'features.ajax': 'AJAX Support',
            'features.export': 'Code Export',
            'features.themes': 'Multiple Themes',
            
            // Form Builder
            'builder.title': 'Visueller Formular-Builder',
            'builder.fields': 'Formularfelder',
            'builder.properties': 'Eigenschaften',
            'builder.preview': 'Vorschau',
            'builder.code': 'Generierter Code',
            'builder.save': 'Speichern',
            'builder.load': 'Laden',
            'builder.clear': 'Leeren',
            'builder.export': 'Exportieren',
            
            // Common
            'common.yes': 'Ja',
            'common.no': 'Nein',
            'common.save': 'Speichern',
            'common.cancel': 'Abbrechen',
            'common.delete': 'Löschen',
            'common.edit': 'Bearbeiten',
            'common.close': 'Schließen',
            'common.back': 'Zurück',
            'common.next': 'Weiter',
            'common.finish': 'Fertig',
            'common.required': 'Erforderlich',
            'common.optional': 'Optional',
            
            // Messages
            'msg.success': 'Erfolgreich gespeichert!',
            'msg.error': 'Ein Fehler ist aufgetreten',
            'msg.confirm': 'Sind Sie sicher?',
            'msg.loading': 'Wird geladen...',
            
            // Footer
            'footer.about': 'Über uns',
            'footer.privacy': 'Datenschutz',
            'footer.terms': 'Nutzungsbedingungen',
            'footer.imprint': 'Impressum',
            'footer.copyright': '© 2024 FormWerk. Alle Rechte vorbehalten.'
        };

        // English translations
        this.translations.en = {
            // Navigation
            'nav.home': 'Home',
            'nav.formbuilder': 'Form Builder',
            'nav.listgenerator': 'List Generator',
            'nav.documentation': 'Documentation',
            'nav.examples': 'Examples',
            'nav.contact': 'Contact',
            
            // Hero Section
            'hero.title': 'Professional Forms & Data Tables',
            'hero.subtitle': 'Create stunning forms and data tables without writing code',
            'hero.cta.primary': 'Get Started',
            'hero.cta.secondary': 'Live Demo',
            
            // Features
            'features.title': 'Powerful Features',
            'features.dragdrop': 'Drag & Drop Builder',
            'features.responsive': 'Fully Responsive',
            'features.validation': 'Smart Validation',
            'features.ajax': 'AJAX Support',
            'features.export': 'Code Export',
            'features.themes': 'Multiple Themes',
            
            // Form Builder
            'builder.title': 'Visual Form Builder',
            'builder.fields': 'Form Fields',
            'builder.properties': 'Properties',
            'builder.preview': 'Preview',
            'builder.code': 'Generated Code',
            'builder.save': 'Save',
            'builder.load': 'Load',
            'builder.clear': 'Clear',
            'builder.export': 'Export',
            
            // Common
            'common.yes': 'Yes',
            'common.no': 'No',
            'common.save': 'Save',
            'common.cancel': 'Cancel',
            'common.delete': 'Delete',
            'common.edit': 'Edit',
            'common.close': 'Close',
            'common.back': 'Back',
            'common.next': 'Next',
            'common.finish': 'Finish',
            'common.required': 'Required',
            'common.optional': 'Optional',
            
            // Messages
            'msg.success': 'Successfully saved!',
            'msg.error': 'An error occurred',
            'msg.confirm': 'Are you sure?',
            'msg.loading': 'Loading...',
            
            // Footer
            'footer.about': 'About',
            'footer.privacy': 'Privacy',
            'footer.terms': 'Terms of Service',
            'footer.imprint': 'Imprint',
            'footer.copyright': '© 2024 FormWerk. All rights reserved.'
        };
    }

    setupSwitcher() {
        // Create language switcher HTML if not exists
        if (!document.querySelector('.language-switcher')) {
            const switcherHTML = `
                <div class="language-switcher">
                    <button class="lang-btn ${this.currentLang === 'de' ? 'active' : ''}" data-lang="de">
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 5 3'%3E%3Cpath d='M0 0h5v1H0z'/%3E%3Cpath fill='%23D00' d='M0 1h5v1H0z'/%3E%3Cpath fill='%23FFCE00' d='M0 2h5v1H0z'/%3E%3C/svg%3E" alt="DE">
                        DE
                    </button>
                    <button class="lang-btn ${this.currentLang === 'en' ? 'active' : ''}" data-lang="en">
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 60 30'%3E%3CclipPath id='a'%3E%3Cpath d='M0 0v30h60V0z'/%3E%3C/clipPath%3E%3CclipPath id='b'%3E%3Cpath d='M30 15h30v15zv15H0zH0V0zV0h30z'/%3E%3C/clipPath%3E%3Cg clip-path='url(%23a)'%3E%3Cpath d='M0 0v30h60V0z' fill='%23012169'/%3E%3Cpath d='M0 0l60 30m0-30L0 30' stroke='%23fff' stroke-width='6'/%3E%3Cpath d='M0 0l60 30m0-30L0 30' clip-path='url(%23b)' stroke='%23C8102E' stroke-width='4'/%3E%3Cpath d='M30 0v30M0 15h60' stroke='%23fff' stroke-width='10'/%3E%3Cpath d='M30 0v30M0 15h60' stroke='%23C8102E' stroke-width='6'/%3E%3C/g%3E%3C/svg%3E" alt="EN">
                        EN
                    </button>
                </div>
            `;
            
            // Add to navigation or header
            const nav = document.querySelector('.main-nav, .nav-container, header');
            if (nav) {
                nav.insertAdjacentHTML('beforeend', switcherHTML);
            }
        }

        // Add CSS for language switcher
        if (!document.querySelector('#language-switcher-styles')) {
            const styles = `
                <style id="language-switcher-styles">
                    .language-switcher {
                        position: fixed;
                        top: 20px;
                        right: 20px;
                        z-index: 9999;
                        display: flex;
                        gap: 5px;
                        background: white;
                        padding: 5px;
                        border-radius: 25px;
                        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    }
                    
                    .lang-btn {
                        display: flex;
                        align-items: center;
                        gap: 5px;
                        padding: 8px 15px;
                        border: none;
                        background: transparent;
                        border-radius: 20px;
                        cursor: pointer;
                        font-weight: 500;
                        transition: all 0.3s ease;
                        color: #666;
                    }
                    
                    .lang-btn img {
                        width: 20px;
                        height: 15px;
                        border-radius: 2px;
                    }
                    
                    .lang-btn:hover {
                        background: #f0f0f0;
                    }
                    
                    .lang-btn.active {
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white;
                    }
                    
                    /* Mobile responsive */
                    @media (max-width: 768px) {
                        .language-switcher {
                            top: 10px;
                            right: 10px;
                        }
                    }
                </style>
            `;
            document.head.insertAdjacentHTML('beforeend', styles);
        }
    }

    bindEvents() {
        // Language button clicks
        document.addEventListener('click', (e) => {
            if (e.target.closest('.lang-btn')) {
                const btn = e.target.closest('.lang-btn');
                const lang = btn.dataset.lang;
                this.switchLanguage(lang);
            }
        });
    }

    switchLanguage(lang) {
        if (lang === this.currentLang) return;
        
        // Update current language
        this.currentLang = lang;
        localStorage.setItem('formwerk_language', lang);
        
        // Update button states
        document.querySelectorAll('.lang-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.lang === lang);
        });
        
        // Apply new language
        this.applyLanguage(lang);
        
        // Trigger custom event
        window.dispatchEvent(new CustomEvent('languageChanged', { detail: { language: lang } }));
    }

    applyLanguage(lang) {
        const translations = this.translations[lang];
        if (!translations) return;
        
        // Update all elements with data-i18n attribute
        document.querySelectorAll('[data-i18n]').forEach(element => {
            const key = element.dataset.i18n;
            if (translations[key]) {
                if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
                    element.placeholder = translations[key];
                } else {
                    element.textContent = translations[key];
                }
            }
        });
        
        // Update document language
        document.documentElement.lang = lang;
        
        // Update page title if needed
        if (document.title.includes('FormWerk') || document.title.includes('EasyForm')) {
            document.title = lang === 'de' 
                ? 'FormWerk - Professioneller Formular & Listen Generator'
                : 'FormWerk - Professional Form & List Generator';
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
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.languageSwitcher = new LanguageSwitcher();
    });
} else {
    window.languageSwitcher = new LanguageSwitcher();
}

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = LanguageSwitcher;
}
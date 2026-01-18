<!-- Navigation -->
<nav class="main-nav">
    <div class="nav-container">
        <a href="<?php echo isset($baseUrl) ? $baseUrl : '/easy_form/'; ?>" class="nav-logo">EasyForm</a>
        <ul class="nav-menu">
            <li><a href="<?php echo isset($baseUrl) ? $baseUrl : '/easy_form/'; ?>" data-i18n="nav.home" <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php' && !strpos($_SERVER['REQUEST_URI'], 'examples')) ? 'class="active"' : ''; ?>>Startseite</a></li>
            <li><a href="<?php echo isset($baseUrl) ? $baseUrl . 'form_builder.php' : '/easy_form/form_builder.php'; ?>" data-i18n="nav.formbuilder" <?php echo basename($_SERVER['PHP_SELF']) == 'form_builder.php' ? 'class="active"' : ''; ?>>Form Builder</a></li>
            <li><a href="<?php echo isset($baseUrl) ? $baseUrl . 'list_generator.php' : '/easy_form/list_generator.php'; ?>" data-i18n="nav.listgenerator" <?php echo basename($_SERVER['PHP_SELF']) == 'list_generator.php' ? 'class="active"' : ''; ?>>List Generator</a></li>
            <li><a href="<?php echo isset($baseUrl) ? $baseUrl . 'docs/' : '/easy_form/docs/'; ?>" data-i18n="nav.docs" <?php echo strpos($_SERVER['REQUEST_URI'], '/docs/') !== false ? 'class="active"' : ''; ?>>Dokumentation</a></li>
            <li><a href="<?php echo isset($baseUrl) ? $baseUrl . 'examples/' : '/easy_form/examples/'; ?>" data-i18n="nav.examples" <?php echo strpos($_SERVER['REQUEST_URI'], '/examples/') !== false ? 'class="active"' : ''; ?>>Beispiele</a></li>
            <li><a href="<?php echo isset($baseUrl) ? $baseUrl . 'health-check.php' : '/easy_form/health-check.php'; ?>" data-i18n="nav.health" <?php echo basename($_SERVER['PHP_SELF']) == 'health-check.php' ? 'class="active"' : ''; ?>>Status</a></li>
        </ul>
    </div>
</nav>

<style>
/* Navigation Styles */
.main-nav {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(10px);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    padding: 15px 30px;
}

.nav-container {
    max-width: 1400px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-logo {
    font-size: 1.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-decoration: none;
}

.nav-menu {
    display: flex;
    gap: 30px;
    list-style: none;
    margin: 0;
    padding: 0;
    align-items: center;
}

.nav-menu a {
    color: #4a5568;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
    padding: 5px 10px;
    border-radius: 6px;
}

.nav-menu a:hover {
    color: #667eea;
    background: rgba(102, 126, 234, 0.08);
}

.nav-menu a.active {
    color: #667eea;
    background: rgba(102, 126, 234, 0.12);
}

/* Responsive */
@media (max-width: 768px) {
    .nav-menu {
        gap: 15px;
        font-size: 14px;
    }
    
    .nav-container {
        padding: 0 15px;
    }
}
</style>
<?php
/**
 * EasyList Generator - Visual List Builder
 * Create data lists with filters, search, sorting and export
 */
session_start();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyForm - Data Table Generator</title>

    <!-- Semantic UI CSS -->
    <link rel="stylesheet" href="semantic/dist/semantic.min.css">
    <script src="assets/js/i18n.js"></script>
    
    <!-- Code highlighting -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            overflow-x: hidden;
            padding-top: 70px;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #667eea;
        }

        /* Drag & Drop Styles */
        .action-element {
            transition: all 0.2s ease;
        }
        
        .action-element.dragging {
            opacity: 0.5;
            transform: rotate(5deg) scale(1.05);
        }
        
        .actions-list:hover {
            border-color: #2185d0 !important;
            background: #f8f9ff !important;
        }
        
        .placeholder-hint {
            transition: opacity 0.3s ease;
        }
        
        /* Column Sortable Styles */
        .column-item:hover {
            background: #f8f9fa;
            transform: translateX(2px);
        }
        
        .column-item.chosen {
            background: #e6f3ff;
            border-left: 4px solid #2185d0;
        }
        
        .column-item.drag {
            opacity: 0.8;
            transform: rotate(3deg) scale(1.02);
        }
        
        /* Action Sortable for Single View */
        #actions-single .action-element {
            width: 100%;
            margin: 8px 0;
            justify-content: space-between;
        }
        
        #actions-single .action-element:hover {
            transform: translateX(3px);
        }
        
        /* Theme-specific styles */
        .bootstrap-theme {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        
        .bootstrap-theme .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }
        
        .bootstrap-theme .table th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            background-color: #f8f9fa;
        }
        
        .bootstrap-theme .table td {
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }
        
        .material-theme {
            font-family: Roboto, sans-serif;
        }
        
        .material-theme table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .material-theme table.highlight > tbody > tr:hover {
            background-color: #f5f5f5;
        }
        
        .material-theme table.striped > tbody > tr:nth-child(odd) {
            background-color: rgba(242, 242, 242, 0.5);
        }
        
        .material-theme table.bordered {
            border: 1px solid #d0d0d0;
        }
        
        .material-theme table.bordered > thead > tr,
        .material-theme table.bordered > tbody > tr {
            border-bottom: 1px solid #d0d0d0;
        }
        
        /* Navigation */
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
            transition: all 0.3s ease;
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
        
        .main-header {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            padding: 8px 20px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 70px;
            z-index: 100;
        }
        
        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-title {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .header-title h1 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2d3748;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .header-title h1 i {
            font-size: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .header-actions {
            display: flex;
            gap: 6px;
        }
        
        /* Moderne Button-Stile - Kompakt */
        .header-actions .ui.button {
            border-radius: 6px;
            font-weight: 500;
            padding: 6px 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid transparent;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: white !important;
            color: #4a5568 !important;
            border-color: #e2e8f0 !important;
        }
        
        .header-actions .ui.button i {
            font-size: 11px !important;
        }
        
        .header-actions .ui.button span {
            font-size: 12px;
        }
        
        .header-actions .ui.button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
            border-color: #667eea !important;
            color: #667eea !important;
        }
        
        /* Template Button - Primär Style */
        .template-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            border: none !important;
        }
        
        .template-button:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b3d8f 100%) !important;
            color: white !important;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.35) !important;
        }
        
        /* Config Button */
        .config-button {
            background: white !important;
            color: #667eea !important;
            border-color: #667eea !important;
        }
        
        .config-button:hover {
            background: rgba(102, 126, 234, 0.1) !important;
            color: #5a67d8 !important;
        }
        
        /* Preview Button */
        .preview-button {
            background: white !important;
            color: #48bb78 !important;
            border-color: #48bb78 !important;
        }
        
        .preview-button:hover {
            background: rgba(72, 187, 120, 0.1) !important;
            color: #38a169 !important;
            border-color: #38a169 !important;
        }
        
        /* Clear/Reset Button */
        .clear-button {
            background: white !important;
            color: #e53e3e !important;
            border-color: #feb2b2 !important;
        }
        
        .clear-button:hover {
            background: rgba(229, 62, 62, 0.1) !important;
            color: #c53030 !important;
            border-color: #e53e3e !important;
        }
        
        .main-container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: 15px;
            grid-template-areas: 
                "sidebar preview"
                "code code";
            min-height: calc(100vh - 150px);
        }
        
        /* Sidebar Panel */
        .sidebar-panel {
            grid-area: sidebar;
            background: white;
            border-radius: 10px;
            padding: 12px;
            height: fit-content;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }
        
        .panel-section {
            margin-bottom: 16px;
        }
        
        .panel-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .panel-title i {
            color: #667eea;
            font-size: 0.85rem;
        }
        
        /* Data Source Section */
        .data-source-tabs {
            display: flex;
            gap: 5px;
            margin-bottom: 15px;
        }
        
        .data-source-tab {
            flex: 1;
            padding: 8px;
            background: #f7fafc;
            border: 2px solid transparent;
            border-radius: 6px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .data-source-tab.active {
            background: white;
            border-color: #667eea;
            color: #667eea;
        }
        
        .data-source-content {
            display: none;
        }
        
        .data-source-content.active {
            display: block;
        }
        
        /* Column Builder */
        .column-list {
            max-height: 250px;
            overflow-y: auto;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 6px;
        }
        
        .column-item {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 6px 8px;
            margin-bottom: 5px;
            cursor: move;
            transition: all 0.3s ease;
            font-size: 0.85rem;
        }
        
        .column-item:hover {
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .column-item.dragging {
            opacity: 0.5;
        }
        
        .column-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }
        
        .column-name {
            font-weight: 500;
            color: #2d3748;
        }
        
        .column-actions {
            display: flex;
            gap: 5px;
        }
        
        .column-action {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .column-action:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        
        /* Preview Panel */
        .preview-panel {
            grid-area: preview;
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            max-height: calc(100vh - 200px);
        }
        
        .preview-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .preview-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2d3748;
        }
        
        .preview-actions {
            display: flex;
            gap: 10px;
        }
        
        /* Ensure inverted table styles are visible */
        .easylist-preview .ui.inverted.table {
            background: #1b1c1d !important;
            color: rgba(255, 255, 255, 0.9) !important;
        }
        
        .easylist-preview .ui.inverted.table thead {
            background: transparent !important;
        }
        
        .easylist-preview .ui.inverted.table thead th {
            background-color: rgba(255, 255, 255, 0.08) !important;
            color: rgba(255, 255, 255, 0.9) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        .easylist-preview .ui.inverted.table tbody tr {
            background: transparent !important;
        }
        
        .easylist-preview .ui.inverted.table tbody tr td {
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: rgba(255, 255, 255, 0.9) !important;
        }
        
        .easylist-preview .ui.inverted.table tbody tr:hover td {
            background: rgba(255, 255, 255, 0.08) !important;
            color: rgba(255, 255, 255, 0.9) !important;
        }
        
        /* Ensure celled table borders are visible */
        .ui.celled.table tr th,
        .ui.celled.table tr td {
            border-left: 1px solid rgba(34, 36, 38, 0.1) !important;
        }
        
        .ui.celled.table tr th:first-child,
        .ui.celled.table tr td:first-child {
            border-left: none !important;
        }
        
        .ui.celled.table tr td {
            border-top: 1px solid rgba(34, 36, 38, 0.1) !important;
        }
        
        /* Ensure padding styles are visible */
        .ui.padded.table th,
        .ui.padded.table td {
            padding: 1em 1em !important;
        }
        
        .ui.relaxed.table th,
        .ui.relaxed.table td {
            padding: 1.5em 1em !important;
        }
        
        .ui.very.relaxed.table th,
        .ui.very.relaxed.table td {
            padding: 2em 1em !important;
        }
        
        .preview-content {
            flex: 1;
            overflow: auto;
            background: #f7fafc;
            border-radius: 8px;
            padding: 20px;
        }
        
        /* Code Panel */
        .code-panel {
            grid-area: code;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-top: 20px;
        }
        
        .code-tabs {
            display: flex;
            background: #2d3748;
            border-bottom: 1px solid #4a5568;
        }
        
        .code-tab {
            padding: 12px 20px;
            color: #cbd5e0;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            background: none;
            font-weight: 500;
        }
        
        .code-tab:hover {
            background: #4a5568;
            color: white;
        }
        
        .code-tab.active {
            background: #1e1e1e;
            color: white;
            border-bottom: 2px solid #667eea;
        }
        
        .code-content {
            background: #1e1e1e;
            padding: 20px;
            height: 400px;
            overflow: auto;
            position: relative;
        }
        
        .copy-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 8px 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.3s ease;
            z-index: 10;
        }
        
        .copy-btn:hover {
            background: #5a6fd8;
        }
        
        .copy-btn.copied {
            background: #48bb78;
        }
        
        .code-content pre {
            margin: 0;
            color: #d4d4d4;
        }
        
        .code-content pre code {
            font-family: 'SF Mono', Monaco, 'Courier New', monospace;
            font-size: 13px;
            line-height: 1.6;
            color: #d4d4d4;
            display: block;
            padding: 0;
            background: transparent;
        }
        
        /* Prism.js Dark Theme - Vereinheitlicht mit Form Builder */
        .code-content .token.keyword {
            color: #569cd6;
        }
        
        .code-content .token.string {
            color: #ce9178;
        }
        
        .code-content .token.variable {
            color: #9cdcfe;
        }
        
        .code-content .token.function {
            color: #dcdcaa;
        }
        
        .code-content .token.comment {
            color: #6a9955;
        }
        
        .code-content .token.operator {
            color: #d4d4d4;
        }
        
        .code-content .token.punctuation {
            color: #808080;
        }
        
        .code-content .token.class-name {
            color: #4ec9b0;
        }
        
        /* Options Forms */
        .option-group {
            margin-bottom: 10px;
        }
        
        .option-label {
            font-size: 0.85rem;
            color: #4a5568;
            margin-bottom: 3px;
        }
        
        .option-checkbox {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 5px;
        }
        
        .option-checkbox input {
            width: 14px;
            height: 14px;
        }
        
        .option-checkbox label {
            font-size: 0.85rem;
        }
        
        .style-dropdown-group {
            margin-bottom: 10px;
        }
        
        .style-dropdown-group label {
            display: block;
            font-size: 0.85rem;
            color: #555;
            margin-bottom: 4px;
            font-weight: 500;
        }
        
        .style-dropdown-group .ui.dropdown {
            min-height: 32px;
            font-size: 0.85rem;
        }
        
        /* Sample Data */
        .sample-data-select {
            width: 100%;
            padding: 8px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            background: white;
        }
        
        /* Add Column Button */
        .add-column-btn {
            width: 100%;
            padding: 7px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: 500;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .add-column-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(102, 126, 234, 0.25);
        }
        
        /* Modal for column settings */
        .column-modal,
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }
        
        .column-modal.active,
        .modal.active {
            display: flex;
        }
        
        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .modal-header {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2d3748;
        }
        
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        
        /* Responsive */
        @media (max-width: 1400px) {
            .main-container {
                grid-template-columns: 280px 1fr;
            }
        }
        
        @media (max-width: 1200px) {
            .main-container {
                grid-template-columns: 250px 1fr;
            }
            
            .panel-section {
                margin-bottom: 20px;
            }
            
            .sidebar-panel {
                padding: 15px;
            }
        }
        
        @media (max-width: 992px) {
            .main-container {
                grid-template-columns: 1fr;
                grid-template-areas: 
                    "sidebar"
                    "preview"
                    "code";
            }
            
            .sidebar-panel {
                max-height: none;
                margin-bottom: 20px;
            }
            
            .preview-panel {
                max-height: none;
                min-height: 400px;
            }
            
            .header-actions {
                flex-direction: column;
                gap: 5px;
            }
            
            .header-actions .ui.button {
                width: 100%;
                margin: 0;
            }
        }
        
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            
            .header-title h1 {
                font-size: 1.4rem;
            }
            
            .header-actions {
                margin-top: 15px;
                width: 100%;
            }
            
            .column-list {
                max-height: 200px;
            }
            
            .code-tabs {
                overflow-x: auto;
                overflow-y: hidden;
                white-space: nowrap;
            }
            
            .code-content {
                height: 300px;
            }
            
            .modal-content {
                margin: 20px;
                width: calc(100% - 40px);
            }
        }
        
        @media (max-width: 480px) {
            .main-container {
                padding: 0 10px;
                margin: 15px auto;
            }
            
            .data-source-tabs {
                font-size: 0.9rem;
            }
            
            .feature-highlights {
                flex-direction: column;
            }
            
            .feature-highlight {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="main-nav">
        <div class="nav-container">
            <a href="/easy_form/" class="nav-logo">EasyForm</a>
            <ul class="nav-menu">
                <li><a href="/easy_form/" data-i18n="nav.home">Startseite</a></li>
                <li><a href="form_builder.php" data-i18n="nav.formbuilder">Form Builder</a></li>
                <li><a href="list_generator.php" data-i18n="nav.listgenerator" class="active">List Generator</a></li>
                <li><a href="docs/" data-i18n="nav.docs">Dokumentation</a></li>
                <li><a href="examples/" data-i18n="nav.examples">Beispiele</a></li>
                <li><a href="health-check.php" data-i18n="nav.health">Status</a></li>
            </ul>
        </div>
    </nav>
    
    <!-- Header -->
    <div class="main-header">
        <div class="header-content">
            <div class="header-title">
                <h1><i class="list icon"></i> <span data-i18n="nav.listgenerator">List Generator</span></h1>
            </div>
            <div class="header-actions">
                <button class="ui button template-button" onclick="showTemplateManager()">
                    <i class="save icon"></i> <span data-i18n="builder.templates">Templates</span>
                </button>
                <button class="ui button config-button" onclick="showListConfig()">
                    <i class="cog icon"></i> <span data-i18n="builder.config_form">Konfiguration</span>
                </button>
                <button class="ui button preview-button" onclick="generateCode()">
                    <i class="eye icon"></i> <span data-i18n="builder.preview">Vorschau</span>
                </button>
                <button class="ui button clear-button" onclick="resetBuilder()">
                    <i class="trash icon"></i> <span data-i18n="builder.clear_form">Leeren</span>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Main Container -->
    <div class="main-container">
        <!-- Sidebar -->
        <div class="sidebar-panel">
            <!-- Styling -->
            <div class="panel-section">
                <div class="panel-title">
                    <i class="paint brush icon"></i> <span data-i18n="list.styling">Tabellen-Styling</span>
                </div>
                <div class="ui form">
                    <!-- Grunddesign -->
                    <div class="field">
                        <label data-i18n="list.designOptions">Design-Optionen</label>
                        <div class="ui segment">
                            <div class="option-checkbox">
                                <input type="checkbox" id="style-striped" onchange="updateTableStyles()">
                                <label for="style-striped" data-i18n="list.striped">Gestreift</label>
                            </div>
                            <div class="option-checkbox">
                                <input type="checkbox" id="style-celled" onchange="updateTableStyles()">
                                <label for="style-celled" data-i18n="list.celled">Mit Rahmen (Celled)</label>
                            </div>
                            <div class="option-checkbox">
                                <input type="checkbox" id="style-compact" onchange="updateTableStyles()">
                                <label for="style-compact" data-i18n="list.compact">Kompakt</label>
                            </div>
                            <div class="option-checkbox">
                                <input type="checkbox" id="style-hover" onchange="updateTableStyles()" checked>
                                <label for="style-hover" data-i18n="list.hoverEffect">Hover-Effekt</label>
                            </div>
                            <div class="option-checkbox">
                                <input type="checkbox" id="style-basic" onchange="updateTableStyles()">
                                <label for="style-basic">Basic</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Layout -->
                    <div class="field">
                        <label data-i18n="list.layout">Layout</label>
                        <div class="ui segment">
                            <div class="option-checkbox">
                                <input type="checkbox" id="layout-fixed" onchange="updateTableStyles()">
                                <label for="layout-fixed" data-i18n="list.fixed">Fixiert</label>
                            </div>
                            <div class="option-checkbox">
                                <input type="checkbox" id="layout-single-line" onchange="updateTableStyles()">
                                <label for="layout-single-line" data-i18n="list.singleLine">Einzeilig</label>
                            </div>
                            <div class="option-checkbox">
                                <input type="checkbox" id="layout-collapsing" onchange="updateTableStyles()">
                                <label for="layout-collapsing" data-i18n="list.collapsing">Kollabierend</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Responsive -->
                    <div class="field">
                        <label data-i18n="list.responsive">Responsive</label>
                        <select id="responsive-mode" class="ui dropdown" onchange="updateTableStyles()">
                            <option value="">Standard</option>
                            <option value="stackable">Stackable (Mobile)</option>
                            <option value="unstackable">Unstackable</option>
                            <option value="tablet stackable">Tablet Stackable</option>
                        </select>
                    </div>
                    
                    <!-- Farbe -->
                    <div class="field">
                        <label data-i18n="list.color">Farbe</label>
                        <select id="color-scheme" class="ui dropdown" onchange="updateTableStyles()">
                            <option value="" data-i18n="list.standard">Standard</option>
                            <option value="red" data-i18n="list.red">Rot</option>
                            <option value="orange" data-i18n="list.orange">Orange</option>
                            <option value="yellow" data-i18n="list.yellow">Gelb</option>
                            <option value="green" data-i18n="list.green">Grün</option>
                            <option value="blue" data-i18n="list.blue">Blau</option>
                            <option value="violet" data-i18n="list.violet">Violett</option>
                            <option value="purple" data-i18n="list.purple">Lila</option>
                            <option value="pink" data-i18n="list.pink">Pink</option>
                            <option value="teal" data-i18n="list.teal">Teal</option>
                            <option value="grey" data-i18n="list.grey">Grau</option>
                            <option value="black" data-i18n="list.black">Schwarz</option>
                        </select>
                    </div>
                    
                    <!-- Größe -->
                    <div class="field">
                        <label data-i18n="list.size">Größe</label>
                        <select id="size-select" class="ui dropdown" onchange="updateTableStyles()">
                            <option value="" data-i18n="list.standard">Standard</option>
                            <option value="small" data-i18n="list.small">Klein</option>
                            <option value="large" data-i18n="list.large">Groß</option>
                        </select>
                    </div>
                    
                    <!-- Abstände & Linien -->
                    <div class="field">
                        <label data-i18n="list.cellPadding">Zellenabstand</label>
                        <select id="padding-select" class="ui dropdown" onchange="updateTableStyles()">
                            <option value="">Standard</option>
                            <option value="padded">Padded</option>
                            <option value="very padded">Very Padded</option>
                            <option value="compact">Compact</option>
                            <option value="very compact">Very Compact</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Data Source -->
            <div class="panel-section">
                <div class="panel-title">
                    <i class="database icon"></i> Datenquelle
                </div>
                <div class="data-source-tabs">
                    <div class="data-source-tab active" onclick="switchDataSource('sample', event)">
                        Sample
                    </div>
                    <div class="data-source-tab" onclick="switchDataSource('api', event)">
                        API
                    </div>
                    <div class="data-source-tab" onclick="switchDataSource('database', event)">
                        Database
                    </div>
                </div>
                
                <div class="data-source-content active" id="source-sample">
                    <select class="sample-data-select" onchange="loadSampleData(this.value)">
                        <option value="users">Benutzerliste</option>
                        <option value="products">Produktkatalog</option>
                        <option value="orders">Bestellungen</option>
                        <option value="employees">Mitarbeiter</option>
                    </select>
                </div>
                
                <div class="data-source-content" id="source-api">
                    <div class="ui form">
                        <div class="field">
                            <input type="text" placeholder="API URL eingeben...">
                        </div>
                    </div>
                </div>
                
                <div class="data-source-content" id="source-database">
                    <div class="ui form">
                        <div class="field">
                            <textarea placeholder="SQL Query eingeben..." rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Columns -->
            <div class="panel-section">
                <div class="panel-title">
                    <i class="columns icon"></i> <span data-i18n="list.columns">Spalten</span>
                </div>
                <div class="column-list" id="column-list">
                    <!-- Columns will be added here -->
                </div>
                <button class="add-column-btn" onclick="addColumn()">
                    <i class="plus icon"></i> <span data-i18n="list.addColumn">Spalte hinzufügen</span>
                </button>
            </div>
            
            <!-- Features -->
            <div class="panel-section">
                <div class="panel-title">
                    <i class="settings icon"></i> <span data-i18n="list.features">Features</span>
                </div>
                <div class="option-group">
                    <div class="option-checkbox">
                        <input type="checkbox" id="feature-search" checked>
                        <label for="feature-search" data-i18n="list.searchField">Suchfeld</label>
                    </div>
                    <div class="option-checkbox">
                        <input type="checkbox" id="feature-sort" checked>
                        <label for="feature-sort" data-i18n="list.sorting">Sortierung</label>
                    </div>
                    <div class="option-checkbox">
                        <input type="checkbox" id="feature-filter" checked>
                        <label for="feature-filter" data-i18n="list.filter">Filter</label>
                    </div>
                    <div class="option-checkbox">
                        <input type="checkbox" id="feature-pagination" checked>
                        <label for="feature-pagination" data-i18n="list.pagination">Pagination</label>
                    </div>
                    <div class="option-checkbox">
                        <input type="checkbox" id="feature-export" checked>
                        <label for="feature-export" data-i18n="list.export">Export (CSV/Excel)</label>
                    </div>
                    <div class="option-checkbox">
                        <input type="checkbox" id="feature-selection">
                        <label for="feature-selection" data-i18n="list.rowSelection">Zeilen-Auswahl</label>
                    </div>
                    <div class="option-checkbox">
                        <input type="checkbox" id="feature-actions" checked>
                        <label for="feature-actions" data-i18n="list.actionButtons">Action-Buttons</label>
                    </div>
                </div>
            </div>
            
            <!-- Actions Configuration -->
            <div class="panel-section" id="actions-section">
                <div class="panel-title">
                    <i class="bolt icon"></i> <span data-i18n="list.actions">Aktionen</span>
                </div>
                <div class="option-group" style="margin-bottom: 15px;">
                    <label style="font-size: 0.9rem; color: #4a5568; margin-bottom: 5px;" data-i18n="list.actionsPosition">Position der Aktionen:</label>
                    <select id="actions-position" class="ui dropdown" style="width: 100%;" onchange="toggleActionsSplit(); refreshPreview(); generateCode();">
                        <option value="right">Rechts (am Ende)</option>
                        <option value="left">Links (am Anfang)</option>
                        <option value="both">Beidseitig (Drag & Drop)</option>
                    </select>
                </div>
                
                <!-- Single list for left/right position -->
                <div class="ui info message" style="margin: 10px 0; padding: 8px 12px; font-size: 0.9em;">
                    <i class="info circle icon"></i>
                    <strong>Drag & Drop:</strong> Ziehen Sie die Aktionen um ihre Reihenfolge zu ändern
                </div>
                <div id="actions-single" class="actions-list" style="margin-bottom: 10px; min-height: 60px; background: #f8f9fa; border: 2px dashed #cbd5e0; border-radius: 6px; padding: 8px;">
                    <!-- Actions will be added here -->
                </div>
                
                <!-- Split view for both position -->
                <div id="actions-split" style="display: none;">
                    <div class="ui info message" style="margin: 15px 0;">
                        <i class="info circle icon"></i>
                        <strong>Drag & Drop:</strong> Ziehen Sie die Buttons in die gewünschte Spalte. Die Buttons werden entsprechend in der Tabelle positioniert.
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 15px; margin: 20px 0;">
                        <div class="ui segment" style="background: #f0f8ff; padding: 15px; border: 2px solid #2185d0;">
                            <h4 class="ui header" style="color: #2185d0; margin-bottom: 10px; font-size: 1.1em;">
                                <i class="arrow left icon"></i> Linke Spalte
                            </h4>
                            <div id="actions-left" class="actions-list" style="min-height: 80px; background: white; border: 2px dashed #90cdf4; border-radius: 6px; padding: 12px; display: flex; flex-wrap: wrap; gap: 6px; align-content: flex-start;">
                                <!-- Left actions -->
                            </div>
                            <div class="placeholder-hint" style="color: #999; text-align: center; margin-top: 8px; font-size: 0.85em;">
                                <i class="hand point up outline icon"></i> Buttons hierher ziehen
                            </div>
                        </div>
                        <div class="ui segment" style="background: #f0fff4; padding: 15px; border: 2px solid #21ba45;">
                            <h4 class="ui header" style="color: #21ba45; margin-bottom: 10px; font-size: 1.1em;">
                                Rechte Spalte <i class="arrow right icon"></i>
                            </h4>
                            <div id="actions-right" class="actions-list" style="min-height: 80px; background: white; border: 2px dashed #86efac; border-radius: 6px; padding: 12px; display: flex; flex-wrap: wrap; gap: 6px; align-content: flex-start;">
                                <!-- Right actions -->
                            </div>
                            <div class="placeholder-hint" style="color: #999; text-align: center; margin-top: 8px; font-size: 0.85em;">
                                <i class="hand point up outline icon"></i> Buttons hierher ziehen
                            </div>
                        </div>
                    </div>
                </div>
                
                <button class="add-column-btn" onclick="addAction()">
                    <i class="plus icon"></i> Aktion hinzufügen
                </button>
            </div>
            
        </div>
        
        <!-- Preview Panel -->
        <div class="preview-panel">
            <div class="preview-header">
                <div class="preview-title">
                    <i class="eye icon"></i> Live Preview
                </div>
                <div class="preview-actions">
                    <button class="ui small button" onclick="refreshPreview()">
                        <i class="sync icon"></i> Aktualisieren
                    </button>
                </div>
            </div>
            <div class="preview-content" id="preview-content">
                <!-- Preview will be rendered here -->
            </div>
        </div>
        
        <!-- Code Panel -->
        <div class="code-panel">
            <div class="code-tabs">
                <button class="code-tab active" onclick="showCodeTab('php')">PHP</button>
                <button class="code-tab" onclick="showCodeTab('javascript')">JavaScript</button>
                <button class="code-tab" onclick="showCodeTab('html')">HTML</button>
            </div>
            <div class="code-content">
                <button class="copy-btn" onclick="copyCode()">
                    <i class="copy icon"></i> Kopieren
                </button>
                <pre><code id="code-output" class="language-php">// Code wird hier generiert...</code></pre>
            </div>
        </div>
    </div>
    
    <!-- Column Settings Modal -->
    <div class="column-modal" id="column-modal">
        <div class="modal-content">
            <div class="modal-header">Spalten-Einstellungen</div>
            <div class="ui form">
                <div class="field">
                    <label>Spaltenname (Key)</label>
                    <input type="text" id="column-key" placeholder="z.B. name, email, status">
                </div>
                <div class="field">
                    <label>Label</label>
                    <input type="text" id="column-label" placeholder="z.B. Name, E-Mail, Status">
                </div>
                <div class="field">
                    <label>Datentyp</label>
                    <select id="column-type">
                        <option value="text">Text</option>
                        <option value="number">Zahl</option>
                        <option value="date">Datum</option>
                        <option value="email">E-Mail</option>
                        <option value="status">Status</option>
                        <option value="image">Bild</option>
                    </select>
                </div>
                <div class="field">
                    <label>Breite</label>
                    <input type="text" id="column-width" placeholder="z.B. 150px, 20%, auto">
                </div>
                <div class="field">
                    <label>Ausrichtung</label>
                    <select id="column-align">
                        <option value="left">Links</option>
                        <option value="center">Zentriert</option>
                        <option value="right">Rechts</option>
                    </select>
                </div>
                <div class="field">
                    <div class="ui checkbox">
                        <input type="checkbox" id="column-sortable" checked>
                        <label>Sortierbar</label>
                    </div>
                </div>
                <div class="field">
                    <div class="ui checkbox">
                        <input type="checkbox" id="column-searchable" checked>
                        <label>Durchsuchbar</label>
                    </div>
                </div>
                <div class="field">
                    <label>Filter-Typ</label>
                    <select id="column-filter">
                        <option value="">Kein Filter</option>
                        <option value="text">Text</option>
                        <option value="select">Dropdown</option>
                        <option value="date">Datum</option>
                        <option value="range">Bereich</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="ui button" onclick="closeColumnModal()">Abbrechen</button>
                <button class="ui primary button" onclick="saveColumnSettings()">Speichern</button>
            </div>
        </div>
    </div>
    
    <!-- Action Modal -->
    <div class="modal" id="action-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Aktion konfigurieren</h3>
                <span class="modal-close" onclick="closeActionModal()">&times;</span>
            </div>
            <div class="ui form modal-body">
                <div class="field">
                    <label>Aktionstyp</label>
                    <select id="action-type" onchange="updateActionFields()">
                        <option value="edit">Bearbeiten</option>
                        <option value="delete">Löschen</option>
                        <option value="view">Anzeigen</option>
                        <option value="modal">Modal öffnen</option>
                        <option value="custom">Benutzerdefiniert</option>
                    </select>
                </div>
                <div class="field">
                    <label>Label</label>
                    <input type="text" id="action-label" placeholder="z.B. Bearbeiten, Details">
                </div>
                <div class="field">
                    <label>Icon</label>
                    <input type="text" id="action-icon" placeholder="z.B. edit, trash, eye">
                </div>
                <div class="field">
                    <label>Button-Farbe</label>
                    <select id="action-color">
                        <option value="">Standard</option>
                        <option value="primary">Blau (Primary)</option>
                        <option value="green">Grün</option>
                        <option value="red">Rot</option>
                        <option value="orange">Orange</option>
                        <option value="purple">Lila</option>
                    </select>
                </div>
                <div class="field">
                    <label>Tooltip/Erklärtext</label>
                    <input type="text" id="action-tooltip" placeholder="z.B. Benutzer bearbeiten und Daten ändern">
                    <small>Wird als Popup beim Hover über dem Button angezeigt</small>
                </div>
                <div class="field">
                    <label>Tooltip-Position</label>
                    <select id="action-tooltip-position">
                        <option value="top">Oben</option>
                        <option value="bottom">Unten</option>
                        <option value="left">Links</option>
                        <option value="right">Rechts</option>
                    </select>
                </div>
                
                <!-- Conditional Display -->
                <div class="ui divider"></div>
                <h4>Bedingte Anzeige</h4>
                <div class="field">
                    <div class="ui checkbox">
                        <input type="checkbox" id="action-conditional" onchange="toggleConditionalFields()">
                        <label>Button nur unter bestimmten Bedingungen anzeigen</label>
                    </div>
                </div>
                <div id="conditional-fields" style="display: none;">
                    <div class="field">
                        <label>Bedingungsfeld</label>
                        <select id="action-condition-field">
                            <option value="">Spalte wählen...</option>
                        </select>
                        <small>Wählen Sie die Spalte, deren Wert geprüft werden soll</small>
                    </div>
                    <div class="field">
                        <label>Bedingungstyp</label>
                        <select id="action-condition-type">
                            <option value="equals">Ist gleich</option>
                            <option value="not_equals">Ist ungleich</option>
                            <option value="contains">Enthält</option>
                            <option value="greater">Größer als</option>
                            <option value="less">Kleiner als</option>
                            <option value="empty">Ist leer</option>
                            <option value="not_empty">Ist nicht leer</option>
                        </select>
                    </div>
                    <div class="field" id="condition-value-field">
                        <label>Bedingungswert</label>
                        <input type="text" id="action-condition-value" placeholder="z.B. aktiv, 1, true">
                        <small>Der Wert, mit dem verglichen werden soll</small>
                    </div>
                </div>
                
                <!-- Modal-specific fields -->
                <div id="modal-fields" style="display: none;">
                    <div class="ui divider"></div>
                    <h4>Modal-Einstellungen</h4>
                    <div class="field">
                        <label>Modal-Titel</label>
                        <input type="text" id="modal-title" placeholder="z.B. Benutzer bearbeiten">
                    </div>
                    <div class="field">
                        <label>Modal-Größe</label>
                        <select id="modal-size">
                            <option value="small">Klein</option>
                            <option value="">Standard</option>
                            <option value="large">Groß</option>
                            <option value="fullscreen">Vollbild</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Inhalt-Typ</label>
                        <select id="modal-content-type" onchange="updateModalContent()">
                            <option value="form">Formular einbinden</option>
                            <option value="iframe">iFrame</option>
                            <option value="ajax">AJAX-Content</option>
                            <option value="custom">Custom HTML</option>
                        </select>
                    </div>
                    <div class="field" id="modal-form-field" style="display: none;">
                        <label>Formular-Datei</label>
                        <input type="text" id="modal-form-path" placeholder="z.B. forms/edit_user.php?id={id}">
                        <small>Verwende {id} für die Zeilen-ID, {name} für Name, etc.</small>
                    </div>
                    <div class="field" id="modal-url-field" style="display: none;">
                        <label>URL</label>
                        <input type="text" id="modal-url" placeholder="z.B. details.php?id={id}">
                    </div>
                </div>
                
                <!-- Custom action fields -->
                <div id="custom-fields" style="display: none;">
                    <div class="ui divider"></div>
                    <div class="field">
                        <label>JavaScript-Funktion</label>
                        <input type="text" id="action-function" placeholder="z.B. handleCustomAction">
                    </div>
                    <div class="field">
                        <label>URL (optional)</label>
                        <input type="text" id="action-url" placeholder="z.B. /api/action/{id}">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="ui button" onclick="closeActionModal()">Abbrechen</button>
                <button class="ui primary button" onclick="saveActionSettings()">Speichern</button>
            </div>
        </div>
    </div>
    
    <script src="jquery/jquery.min.js"></script>
    <script src="semantic/dist/semantic.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-markup.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-markup-templating.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-json.min.js"></script>
    <script src="js/easylist.js"></script>
    <script>
        // Toast Notification Function
        function showSuccessToast(message) {
            showToast(message, 'success', 'check circle');
        }
        
        function showErrorToast(message) {
            showToast(message, 'error', 'exclamation circle');
        }
        
        function showToast(message, type = 'success', icon = 'check circle') {
            // Remove any existing toasts
            $('.toast-notification').remove();
            
            // Create toast element with inline styles
            const toast = $(`
                <div class="toast-notification ${type}" style="
                    position: fixed;
                    top: 80px;
                    right: 20px;
                    z-index: 10000;
                    min-width: 300px;
                    max-width: 500px;
                    padding: 16px 20px;
                    background: white;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    animation: slideInRight 0.3s ease-out;
                    border-left: 4px solid ${type === 'success' ? '#21ba45' : type === 'error' ? '#db2828' : '#2185d0'};
                ">
                    <i class="${icon} icon" style="font-size: 20px; color: ${type === 'success' ? '#21ba45' : type === 'error' ? '#db2828' : '#2185d0'};"></i>
                    <span style="flex: 1; font-size: 14px; font-weight: 500; color: #2c3e50;">${message}</span>
                    <span class="close" style="cursor: pointer; color: #999; font-size: 18px; line-height: 1;">&times;</span>
                </div>
            `);
            
            // Add animation styles if not exists
            if (!$('#toast-animations').length) {
                $('head').append(`
                    <style id="toast-animations">
                        @keyframes slideInRight {
                            from { transform: translateX(100%); opacity: 0; }
                            to { transform: translateX(0); opacity: 1; }
                        }
                        @keyframes slideOutRight {
                            from { transform: translateX(0); opacity: 1; }
                            to { transform: translateX(100%); opacity: 0; }
                        }
                    </style>
                `);
            }
            
            // Add to body
            $('body').append(toast);
            
            // Add close handler
            toast.find('.close').on('click', function() {
                toast.css('animation', 'slideOutRight 0.3s ease-out');
                setTimeout(() => toast.remove(), 300);
            });
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                if (toast.length) {
                    toast.css('animation', 'slideOutRight 0.3s ease-out');
                    setTimeout(() => toast.remove(), 300);
                }
            }, 3000);
        }
        
        // ========================================
        // GLOBAL STATE MANAGEMENT
        // ========================================
        let columns = [];
        let actions = [];
        let actionsLeft = [];
        let actionsRight = [];
        let sampleData = {};
        let currentEditColumn = null;
        let currentEditAction = null;
        let sortableLeft = null;
        let sortableRight = null;
        let sortableSingle = null;
        
        // List configuration object
        let listConfig = {
            tableName: 'data_list',
            description: '',
            itemsPerPage: 25,
            theme: 'semantic',
            searchPlaceholder: 'Suchen...',
            exportFormats: ['csv', 'excel'],
            dateFormat: 'DD.MM.YYYY'
        };
        
        // Using Semantic UI only
        const currentTheme = 'semantic';
        
        // ========================================
        // SAMPLE DATA SETS
        // ========================================
        const sampleDataSets = {
            users: {
                columns: [
                    { key: 'id', label: 'ID', type: 'number', width: '80px' },
                    { key: 'name', label: 'Name', type: 'text' },
                    { key: 'email', label: 'E-Mail', type: 'email' },
                    { key: 'role', label: 'Rolle', type: 'text', filter: 'select' },
                    { key: 'status', label: 'Status', type: 'status', filter: 'select' },
                    { key: 'created', label: 'Erstellt', type: 'date' }
                ],
                data: [
                    { id: 1, name: 'Max Mustermann', email: 'max@example.com', role: 'Admin', status: 'Aktiv', created: '2024-01-15' },
                    { id: 2, name: 'Anna Schmidt', email: 'anna@example.com', role: 'User', status: 'Aktiv', created: '2024-02-20' },
                    { id: 3, name: 'Tom Weber', email: 'tom@example.com', role: 'Editor', status: 'Inaktiv', created: '2024-03-10' }
                ]
            },
            products: {
                columns: [
                    { key: 'sku', label: 'SKU', type: 'text', width: '120px' },
                    { key: 'name', label: 'Produkt', type: 'text' },
                    { key: 'category', label: 'Kategorie', type: 'text', filter: 'select' },
                    { key: 'price', label: 'Preis', type: 'number', align: 'right' },
                    { key: 'stock', label: 'Lager', type: 'number', align: 'center' },
                    { key: 'status', label: 'Status', type: 'status' }
                ],
                data: [
                    { sku: 'PRD001', name: 'Laptop Pro', category: 'Elektronik', price: 1299.99, stock: 15, status: 'Verfügbar' },
                    { sku: 'PRD002', name: 'Wireless Mouse', category: 'Zubehör', price: 29.99, stock: 142, status: 'Verfügbar' },
                    { sku: 'PRD003', name: 'USB-C Kabel', category: 'Zubehör', price: 9.99, stock: 0, status: 'Ausverkauft' }
                ]
            },
            orders: {
                columns: [
                    { key: 'order_id', label: 'Bestell-Nr.', type: 'text', width: '120px' },
                    { key: 'customer', label: 'Kunde', type: 'text' },
                    { key: 'date', label: 'Datum', type: 'date' },
                    { key: 'total', label: 'Gesamt', type: 'number', align: 'right' },
                    { key: 'payment', label: 'Zahlung', type: 'text', filter: 'select' },
                    { key: 'status', label: 'Status', type: 'status', filter: 'select' }
                ],
                data: [
                    { order_id: 'ORD-2024-001', customer: 'Max Mustermann', date: '2024-11-01', total: 299.90, payment: 'Kreditkarte', status: 'Versendet' },
                    { order_id: 'ORD-2024-002', customer: 'Anna Schmidt', date: '2024-11-02', total: 149.50, payment: 'PayPal', status: 'In Bearbeitung' },
                    { order_id: 'ORD-2024-003', customer: 'Tom Weber', date: '2024-11-03', total: 599.00, payment: 'Rechnung', status: 'Bezahlt' }
                ]
            },
            employees: {
                columns: [
                    { key: 'emp_id', label: 'Mitarbeiter-Nr.', type: 'text', width: '130px' },
                    { key: 'name', label: 'Name', type: 'text' },
                    { key: 'department', label: 'Abteilung', type: 'text', filter: 'select' },
                    { key: 'position', label: 'Position', type: 'text' },
                    { key: 'salary', label: 'Gehalt', type: 'number', align: 'right' },
                    { key: 'start_date', label: 'Eintrittsdatum', type: 'date' }
                ],
                data: [
                    { emp_id: 'EMP001', name: 'Maria Müller', department: 'IT', position: 'Senior Developer', salary: 75000, start_date: '2020-03-15' },
                    { emp_id: 'EMP002', name: 'Klaus Fischer', department: 'Vertrieb', position: 'Sales Manager', salary: 65000, start_date: '2019-07-01' },
                    { emp_id: 'EMP003', name: 'Lisa Wagner', department: 'Marketing', position: 'Marketing Lead', salary: 60000, start_date: '2021-01-10' }
                ]
            }
        };
        
        // Initialize with users data
        window.addEventListener('DOMContentLoaded', () => {
            loadSampleData('users');
            initializeSortable();
            
            // Initialize all Semantic UI dropdowns with consistent behavior
            $('.ui.dropdown').dropdown({
                onChange: function(value, text, $selectedItem) {
                    setTimeout(() => {
                        refreshPreview();
                        generateCode();
                    }, 10);
                }
            });
            
            // Add event listeners to all feature checkboxes
            const featureCheckboxes = document.querySelectorAll('#feature-search, #feature-sort, #feature-filter, #feature-pagination, #feature-export, #feature-selection, #feature-actions');
            featureCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    refreshPreview();
                    generateCode();
                });
            });
            
            // Add event listeners to all style checkboxes
            const styleCheckboxes = document.querySelectorAll('#style-striped, #style-compact, #style-celled, #style-basic, #style-collapsing, #style-fixed, #style-single-line, #style-left-aligned, #style-center-aligned, #style-right-aligned, #style-selectable, #style-sortable, #style-scrolling');
            styleCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    refreshPreview();
                    generateCode();
                });
            });

            // Style dropdowns are already handled by Semantic UI dropdown initialization above
        });
        
        // ========================================
        // DATA MANAGEMENT FUNCTIONS
        // ========================================
        function loadSampleData(dataSet) {
            const data = sampleDataSets[dataSet];
            columns = [...data.columns];
            sampleData = data.data;
            renderColumns();
            refreshPreview();
            generateCode();
        }
        
        function initializeSortable() {
            // Initialize columns sortable
            initializeSortableColumns();
            
            // Initialize actions sortable
            initializeSortableActions();
        }
        
        function renderColumns() {
            const columnList = document.getElementById('column-list');
            columnList.innerHTML = '';
            
            columns.forEach((col, index) => {
                const columnEl = document.createElement('div');
                columnEl.className = 'column-item';
                columnEl.dataset.columnIndex = index; // Add index for sorting
                columnEl.dataset.columnData = JSON.stringify(col); // Store column data for drag & drop
                columnEl.style.cursor = 'move'; // Visual indicator for drag & drop
                columnEl.innerHTML = `
                    <div class="column-header">
                        <span class="column-name">
                            <i class="bars icon" style="color: #999; margin-right: 6px;"></i>
                            ${col.label}
                        </span>
                        <div class="column-actions">
                            <span class="column-action" onclick="editColumn(${index})" title="Bearbeiten">
                                <i class="edit icon"></i>
                            </span>
                            <span class="column-action" onclick="removeColumn(${index})" title="Löschen">
                                <i class="trash icon"></i>
                            </span>
                        </div>
                    </div>
                    <div style="font-size: 0.85rem; color: #718096;">
                        ${col.key} | ${col.type}
                        ${col.filter ? ' | Filter: ' + col.filter : ''}
                    </div>
                `;
                columnList.appendChild(columnEl);
            });
        }
        
        function addColumn() {
            currentEditColumn = null;
            document.getElementById('column-key').value = '';
            document.getElementById('column-label').value = '';
            document.getElementById('column-type').value = 'text';
            document.getElementById('column-width').value = '';
            document.getElementById('column-align').value = 'left';
            document.getElementById('column-sortable').checked = true;
            document.getElementById('column-searchable').checked = true;
            document.getElementById('column-filter').value = '';
            document.getElementById('column-modal').classList.add('active');
        }
        
        function editColumn(index) {
            currentEditColumn = index;
            const col = columns[index];
            document.getElementById('column-key').value = col.key;
            document.getElementById('column-label').value = col.label;
            document.getElementById('column-type').value = col.type || 'text';
            document.getElementById('column-width').value = col.width || '';
            document.getElementById('column-align').value = col.align || 'left';
            document.getElementById('column-sortable').checked = col.sortable !== false;
            document.getElementById('column-searchable').checked = col.searchable !== false;
            document.getElementById('column-filter').value = col.filter || '';
            document.getElementById('column-modal').classList.add('active');
        }
        
        function removeColumn(index) {
            columns.splice(index, 1);
            renderColumns();
            refreshPreview();
            generateCode();
        }
        
        function saveColumnSettings() {
            const columnData = {
                key: document.getElementById('column-key').value,
                label: document.getElementById('column-label').value,
                type: document.getElementById('column-type').value,
                width: document.getElementById('column-width').value,
                align: document.getElementById('column-align').value,
                sortable: document.getElementById('column-sortable').checked,
                searchable: document.getElementById('column-searchable').checked,
                filter: document.getElementById('column-filter').value
            };
            
            if (!columnData.key || !columnData.label) {
                alert('Bitte füllen Sie Key und Label aus');
                return;
            }
            
            if (currentEditColumn !== null) {
                columns[currentEditColumn] = columnData;
            } else {
                columns.push(columnData);
            }
            
            closeColumnModal();
            renderColumns();
            refreshPreview();
            generateCode();
        }
        
        function closeColumnModal() {
            document.getElementById('column-modal').classList.remove('active');
        }
        
        function switchDataSource(source, evt) {
            // Get the event from parameter or window.event
            const e = evt || window.event;
            
            document.querySelectorAll('.data-source-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.data-source-content').forEach(content => {
                content.classList.remove('active');
            });
            
            if (e && e.target) {
                e.target.classList.add('active');
            }
            document.getElementById('source-' + source).classList.add('active');
        }
        
        function refreshPreview() {
            const preview = document.getElementById('preview-content');
            
            // Build preview HTML
            let html = '<div class="easylist-preview">';
            
            // Get feature states
            const features = {
                search: document.getElementById('feature-search')?.checked || false,
                sort: document.getElementById('feature-sort')?.checked || false,
                filter: document.getElementById('feature-filter')?.checked || false,
                pagination: document.getElementById('feature-pagination')?.checked || false,
                export: document.getElementById('feature-export')?.checked || false,
                selection: document.getElementById('feature-selection')?.checked || false,
                actions: document.getElementById('feature-actions')?.checked || false
            };
            
            // Get dropdown values from new reorganized controls
            const paddingStyle = $('#padding-select').val() || '';
            const responsiveStyle = $('#responsive-mode').val() || '';
            
            const styles = {
                striped: document.getElementById('style-striped')?.checked || false,
                compact: document.getElementById('style-compact')?.checked || false,
                celled: document.getElementById('style-celled')?.checked || false,
                collapsing: document.getElementById('layout-collapsing')?.checked || false,
                fixed: document.getElementById('layout-fixed')?.checked || false,
                singleLine: document.getElementById('layout-single-line')?.checked || false,
                stackable: responsiveStyle === 'stackable',
                unstackable: responsiveStyle === 'unstackable',
                basic: document.getElementById('style-basic')?.checked || false,
                leftAligned: document.getElementById('style-left-aligned')?.checked || false,
                centerAligned: document.getElementById('style-center-aligned')?.checked || false,
                rightAligned: document.getElementById('style-right-aligned')?.checked || false,
                selectable: document.getElementById('style-selectable')?.checked || false,
                sortable: document.getElementById('style-sortable')?.checked || false,
                scrolling: document.getElementById('style-scrolling')?.checked || false,
                padded: paddingStyle === 'padded',
                relaxed: paddingStyle === 'relaxed',
                veryRelaxed: paddingStyle === 'very relaxed',
                color: $('#color-scheme').val() || '',
                size: $('#size-select').val() || ''
            };
            
            // Toolbar with search and export
            if (features.search || features.export) {
                html += '<div class="ui secondary menu" style="margin-bottom: 20px;">';
                
                if (features.search) {
                    html += `
                        <div class="item">
                            <div class="ui icon input">
                                <input type="text" id="preview-search" placeholder="Suchen..." onkeyup="previewSearch(this.value)">
                                <i class="search icon"></i>
                            </div>
                        </div>
                    `;
                }
                
                if (features.selection) {
                    html += `
                        <div class="item">
                            <div class="ui dropdown button">
                                <i class="dropdown icon"></i>
                                <div class="text">Bulk-Aktionen</div>
                                <div class="menu">
                                    <div class="item"><i class="trash icon"></i> Löschen</div>
                                    <div class="item"><i class="check icon"></i> Aktivieren</div>
                                    <div class="item"><i class="ban icon"></i> Deaktivieren</div>
                                </div>
                            </div>
                        </div>
                    `;
                }
                
                if (features.export) {
                    html += `
                        <div class="right menu">
                            <a class="item"><i class="file excel icon"></i> CSV</a>
                            <a class="item"><i class="file excel icon"></i> Excel</a>
                            <a class="item"><i class="file pdf icon"></i> PDF</a>
                        </div>
                    `;
                }
                
                html += '</div>';
            }
            
            // Filters - matching the actual EasyList compact style
            if (features.filter) {
                html += '<div class="ui form segment" style="padding: 10px 12px; margin-bottom: 15px; background: #f8f9fa; width: 100%;">';
                html += '<div class="inline fields" style="margin: 0; display: flex; flex-wrap: wrap; align-items: center; gap: 12px; width: 100%; justify-content: space-between;">';
                html += '<div style="display: flex; flex-wrap: wrap; align-items: center; gap: 12px; flex: 1;">';
                
                // Add filter inputs for columns that have filters
                columns.forEach(col => {
                    if (col.filter) {
                        html += '<div class="field" style="margin: 0; display: flex; align-items: center; gap: 6px;">';
                        html += `<label style="font-size: 0.85em; color: #555; white-space: nowrap;">${col.label}:</label>`;
                        
                        if (col.filter === 'select') {
                            html += `
                                <select class="ui compact dropdown" style="min-width: 140px; font-size: 0.9em;">
                                    <option value="">Alle</option>
                                    <option>Option 1</option>
                                    <option>Option 2</option>
                                </select>
                            `;
                        } else if (col.filter === 'date') {
                            html += `
                                <input type="date" style="padding: 7px 10px; border: 1px solid #d4d4d5; border-radius: 4px; font-size: 0.9em; height: 38px;">
                            `;
                        } else if (col.filter === 'range') {
                            html += `
                                <div style="display: flex; align-items: center; gap: 4px;">
                                    <input type="number" placeholder="Min" style="width: 70px; padding: 7px 8px; border: 1px solid #d4d4d5; border-radius: 4px; font-size: 0.9em; height: 38px;">
                                    <span style="color: #999; font-size: 0.9em;">-</span>
                                    <input type="number" placeholder="Max" style="width: 70px; padding: 7px 8px; border: 1px solid #d4d4d5; border-radius: 4px; font-size: 0.9em; height: 38px;">
                                </div>
                            `;
                        } else {
                            html += `
                                <input type="text" placeholder="Filter..." style="padding: 7px 10px; border: 1px solid #d4d4d5; border-radius: 4px; width: 140px; font-size: 0.9em; height: 38px;">
                            `;
                        }
                        html += '</div>'; // Close field
                    }
                });
                
                html += '</div>'; // Close filter fields container
                
                // Add reset button
                html += '<div class="field" style="margin: 0;">';
                html += '<button class="ui small basic button" style="padding: 8px 12px;"><i class="eraser icon"></i> Reset</button>';
                html += '</div>';
                
                html += '</div>'; // Close inline fields
                html += '</div>'; // Close form segment
            }
            
            // Table with responsive wrapper - matching actual EasyList
            html += '<div class="table-responsive" style="border: 1px solid #d4d4d5; border-radius: 8px; overflow: hidden; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">';
            
            // Use the getTableClasses function which includes theme settings
            let tableClasses = getTableClasses();
            
            // Also apply the style checkboxes if they're still needed
            let additionalClasses = [];
            
            // Other design options from checkboxes
            if (styles.striped) additionalClasses.push('striped');
            if (styles.collapsing) additionalClasses.push('collapsing');
            if (styles.fixed) additionalClasses.push('fixed');
            if (styles.singleLine) additionalClasses.push('single line');
            if (styles.compact) additionalClasses.push('compact');
            if (styles.selectable) additionalClasses.push('selectable');
            if (styles.celled) additionalClasses.push('celled');
            
            // Lines style
            if (styles.basic) {
                additionalClasses.push('basic');
            }
            
            // Alignment options
            if (styles.leftAligned) additionalClasses.push('left aligned');
            if (styles.centerAligned) additionalClasses.push('center aligned');
            if (styles.rightAligned) additionalClasses.push('right aligned');
            
            // Stacking (mutually exclusive)
            if (styles.unstackable) {
                additionalClasses.push('unstackable');
            } else if (styles.stackable) {
                additionalClasses.push('stackable');
            }
            
            // Special effects
            if (styles.sortable) additionalClasses.push('sortable');
            if (styles.scrolling) additionalClasses.push('scrolling');
            
            // Padding style (padded, relaxed, or very relaxed - mutually exclusive)
            if (styles.veryRelaxed) {
                additionalClasses.push('very relaxed');
            } else if (styles.relaxed) {
                additionalClasses.push('relaxed');
            } else if (styles.padded) {
                additionalClasses.push('padded');
            }
            
            // Color scheme
            if (styles.color) additionalClasses.push(styles.color);
            
            // Size (only if not already set by style)
            if (styles.size) additionalClasses.push(styles.size);
            
            // Combine classes
            const finalClasses = tableClasses + (additionalClasses.length > 0 ? ' ' + additionalClasses.join(' ') : '');
            
            // Build table HTML based on theme
            const theme = document.getElementById('theme-select')?.value || 'semantic';
            
            if (theme === 'bootstrap') {
                // Bootstrap needs container for proper styling
                html += '<div class="bootstrap-container" style="padding: 15px;">';
                const responsive = document.getElementById('responsive-mode')?.value;
                if (responsive && responsive.includes('stackable')) {
                    html += '<div class="table-responsive">';
                }
                html += `<table class="${finalClasses}">`;
            } else if (theme === 'material') {
                // Material Design responsive table
                html += '<div class="material-container" style="padding: 15px;">';
                html += '<div class="responsive-table">';
                html += `<table class="${finalClasses}">`;
            } else {
                // Semantic UI
                html += `<table class="${finalClasses}" style="margin: 0; border-radius: 0;">`;
            }
            
            // Header
            html += '<thead><tr>';
            
            const actionsPosition = document.getElementById('actions-position')?.value || 'right';
            
            // Add actions header on left if position is left or both
            if (features.actions && actions.length > 0 && (actionsPosition === 'left' || actionsPosition === 'both')) {
                html += '<th class="center aligned" style="width: 150px;">Aktionen</th>';
            }
            
            if (features.selection) {
                html += '<th style="width: 50px;"><input type="checkbox" onclick="toggleAllCheckboxes(this)"></th>';
            }
            
            columns.forEach(col => {
                const align = col.align ? ` class="${col.align} aligned"` : '';
                const width = col.width ? ` style="width: ${col.width}"` : '';
                html += `<th${align}${width}>`;
                if (features.sort && col.sortable !== false) {
                    html += `<a href="#" onclick="return false;" style="color: inherit;">${col.label} <i class="sort icon"></i></a>`;
                } else {
                    html += col.label;
                }
                html += '</th>';
            });
            
            // Add actions header on right if position is right or both
            if (features.actions && actions.length > 0 && (actionsPosition === 'right' || actionsPosition === 'both')) {
                html += '<th class="center aligned" style="width: 150px;">Aktionen</th>';
            }
            
            html += '</tr></thead>';
            
            // Body
            html += '<tbody>';
            if (sampleData && sampleData.length > 0) {
                sampleData.slice(0, 5).forEach((row, index) => {
                    html += '<tr>';
                    
                    // Helper function to check if action should be displayed
                    const shouldShowAction = (action, rowData) => {
                        if (!action.conditional) return true;
                        
                        const fieldValue = rowData[action.conditionField];
                        const conditionValue = action.conditionValue;
                        
                        switch (action.conditionType) {
                            case 'equals':
                                return fieldValue == conditionValue;
                            case 'not_equals':
                                return fieldValue != conditionValue;
                            case 'contains':
                                return String(fieldValue).includes(conditionValue);
                            case 'greater':
                                return parseFloat(fieldValue) > parseFloat(conditionValue);
                            case 'less':
                                return parseFloat(fieldValue) < parseFloat(conditionValue);
                            case 'empty':
                                return !fieldValue || fieldValue === '';
                            case 'not_empty':
                                return fieldValue && fieldValue !== '';
                            default:
                                return true;
                        }
                    };
                    
                    // Helper function to render action buttons
                    const renderActionButtons = (buttonActions, rowData) => {
                        let actionsHtml = '<td class="center aligned">';
                        
                        if (theme === 'bootstrap') {
                            // Bootstrap button group
                            actionsHtml += '<div class="btn-group btn-group-sm" role="group">';
                            buttonActions.forEach(action => {
                                if (action && shouldShowAction(action, rowData)) {
                                    const tooltipAttr = `title="${action.label}" data-bs-toggle="tooltip"`;
                                    
                                    // Bootstrap button classes
                                    let buttonClass = 'btn btn-sm';
                                    if (action.color) {
                                        const bsColors = {
                                            'red': 'btn-danger',
                                            'green': 'btn-success', 
                                            'blue': 'btn-primary',
                                            'yellow': 'btn-warning',
                                            'orange': 'btn-warning',
                                            'purple': 'btn-secondary',
                                            'grey': 'btn-secondary',
                                            'black': 'btn-dark'
                                        };
                                        buttonClass += ' ' + (bsColors[action.color] || 'btn-outline-secondary');
                                    } else {
                                        buttonClass += ' btn-outline-secondary';
                                    }
                                    
                                    const iconMap = {
                                        'edit': '✏️',
                                        'trash': '🗑️',
                                        'eye': '👁️',
                                        'download': '⬇️',
                                        'plus': '➕',
                                        'copy': '📋',
                                        'share': '🔗'
                                    };
                                    
                                    actionsHtml += `<button class="${buttonClass}" ${tooltipAttr}>`;
                                    actionsHtml += iconMap[action.icon] || action.label.charAt(0);
                                    actionsHtml += '</button>';
                                }
                            });
                            actionsHtml += '</div>';
                        } else if (theme === 'material') {
                            // Material Design buttons
                            actionsHtml += '<div style="display: flex; gap: 4px;">';
                            buttonActions.forEach(action => {
                                if (action && shouldShowAction(action, rowData)) {
                                    const tooltipAttr = `title="${action.label}"`;
                                    
                                    let buttonClass = 'btn-floating btn-small waves-effect waves-light';
                                    if (action.color) {
                                        buttonClass += ' ' + action.color;
                                    }
                                    
                                    const iconMap = {
                                        'edit': 'edit',
                                        'trash': 'delete',
                                        'eye': 'visibility',
                                        'download': 'download',
                                        'plus': 'add'
                                    };
                                    
                                    actionsHtml += `<a class="${buttonClass}" ${tooltipAttr}>`;
                                    actionsHtml += `<i class="material-icons tiny">${iconMap[action.icon] || 'more_horiz'}</i>`;
                                    actionsHtml += '</a>';
                                }
                            });
                            actionsHtml += '</div>';
                        } else {
                            // Semantic UI (default)
                            actionsHtml += '<div class="ui small icon buttons">';
                            buttonActions.forEach(action => {
                                if (action && shouldShowAction(action, rowData)) {
                                    const iconClass = action.icon || 'ellipsis horizontal';
                                    
                                    const tooltipAttr = action.tooltip ? 
                                        `data-tooltip="${action.tooltip}" data-position="${action.tooltipPosition || 'top'} center" data-inverted=""` : 
                                        `title="${action.label}"`;
                                    
                                    let buttonClass = 'ui button';
                                    if (action.color) {
                                        buttonClass = `ui ${action.color} button`;
                                    } else {
                                        buttonClass = 'ui basic button';
                                    }
                                    
                                    actionsHtml += `<button class="${buttonClass}" ${tooltipAttr}>`;
                                    actionsHtml += `<i class="${iconClass} icon"></i>`;
                                    actionsHtml += '</button>';
                                }
                            });
                            actionsHtml += '</div>';
                        }
                        
                        actionsHtml += '</td>';
                        return actionsHtml;
                    };
                    
                    // Determine which buttons to show where
                    let leftButtonActions = [];
                    let rightButtonActions = [];
                    
                    if (actionsPosition === 'both') {
                        // Use the drag & drop distributed actions
                        if (actionsLeft.length > 0 || actionsRight.length > 0) {
                            // Use distributed actions from drag & drop
                            leftButtonActions = actionsLeft;
                            rightButtonActions = actionsRight;
                        } else {
                            // Initial distribution - alternate actions
                            actions.forEach((action, index) => {
                                if (index % 2 === 0) {
                                    leftButtonActions.push(action);
                                } else {
                                    rightButtonActions.push(action);
                                }
                            });
                        }
                    } else if (actionsPosition === 'left') {
                        leftButtonActions = actions;
                    } else {
                        rightButtonActions = actions;
                    }
                    
                    // Add action buttons on left
                    if (features.actions && actions.length > 0 && leftButtonActions.length > 0) {
                        html += renderActionButtons(leftButtonActions, row);
                    }
                    
                    if (features.selection) {
                        html += '<td><input type="checkbox" class="row-checkbox"></td>';
                    }
                    
                    columns.forEach(col => {
                        const align = col.align ? ` class="${col.align} aligned"` : '';
                        let value = row[col.key] || '';
                        
                        // Format based on type
                        if (col.type === 'status') {
                            if (theme === 'bootstrap') {
                                const bsColor = value === 'Aktiv' || value === 'Verfügbar' ? 'success' : 
                                              value === 'Inaktiv' || value === 'Ausverkauft' ? 'danger' : 'warning';
                                value = `<span class="badge bg-${bsColor}">${value}</span>`;
                            } else if (theme === 'material') {
                                const mdColor = value === 'Aktiv' || value === 'Verfügbar' ? 'green' : 
                                              value === 'Inaktiv' || value === 'Ausverkauft' ? 'red' : 'orange';
                                value = `<span class="chip ${mdColor}">${value}</span>`;
                            } else {
                                const color = value === 'Aktiv' || value === 'Verfügbar' ? 'green' : 
                                            value === 'Inaktiv' || value === 'Ausverkauft' ? 'red' : 'yellow';
                                value = `<span class="ui ${color} label">${value}</span>`;
                            }
                        } else if (col.type === 'number' && (col.key.includes('price') || col.key.includes('salary'))) {
                            value = '€ ' + parseFloat(value).toFixed(2);
                        }
                        
                        html += `<td${align}>${value}</td>`;
                    });
                    
                    // Add action buttons on right
                    if (features.actions && actions.length > 0 && rightButtonActions.length > 0) {
                        html += renderActionButtons(rightButtonActions, row);
                    }
                    
                    html += '</tr>';
                });
            } else {
                let colspan = columns.length + (features.selection ? 1 : 0);
                if (features.actions && actions.length > 0) {
                    colspan += actionsPosition === 'both' ? 2 : 1;
                }
                html += '<tr><td colspan="' + colspan + 
                       '" class="center aligned">Keine Daten vorhanden</td></tr>';
            }
            html += '</tbody>';
            
            html += '</table>';
            
            // Close theme-specific wrappers
            if (theme === 'bootstrap') {
                if (document.getElementById('responsive-mode')?.value?.includes('stackable')) {
                    html += '</div>'; // Close table-responsive
                }
                html += '</div>'; // Close bootstrap-container
            } else if (theme === 'material') {
                html += '</div>'; // Close responsive-table wrapper
                html += '</div>'; // Close material-container
            } else {
                html += '</div>'; // Close table-responsive for Semantic
            }
            
            // Pagination - theme-specific
            if (features.pagination) {
                if (theme === 'bootstrap') {
                    html += `
                    <nav aria-label="Page navigation" style="margin-top: 20px;">
                        <ul class="pagination justify-content-center">
                            <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </nav>`;
                } else if (theme === 'material') {
                    html += `
                    <div class="pagination" style="margin-top: 20px; text-align: center;">
                        <a href="#" class="waves-effect">←</a>
                        <a href="#" class="active">1</a>
                        <a href="#" class="waves-effect">2</a>
                        <a href="#" class="waves-effect">3</a>
                        <a href="#" class="waves-effect">→</a>
                    </div>`;
                } else {
                    html += `
                    <div class="ui pagination menu" style="justify-content: center; margin-top: 20px;">
                        <a class="icon item"><i class="left chevron icon"></i></a>
                        <a class="item active">1</a>
                        <a class="item">2</a>
                        <a class="item">3</a>
                        <a class="icon item"><i class="right chevron icon"></i></a>
                    </div>
                `;
                }
            }
            
            html += '</div>';
            
            preview.innerHTML = html;
            
            // Initialize Semantic UI tooltips for action buttons
            setTimeout(() => {
                if (typeof $ !== 'undefined') {
                    $('#preview-content [data-tooltip]').popup({
                        hoverable: true,
                        variation: 'inverted'
                    });
                }
            }, 100);
        }
        
        // ========================================
        // THEME FUNCTIONS
        // ========================================
        // Theme function removed - using only Semantic UI
        
        function updateTableStyles() {
            updatePreview();
            generateCode();
        }
        
        function updatePreview() {
            // Simply refresh the preview with new theme settings
            refreshPreview();
        }
        
        function getTableClasses() {
            let classes = [];
            
            // Get current theme
            const theme = document.getElementById('theme-select')?.value || 'semantic';
            
            if (theme === 'semantic') {
                classes.push('ui', 'table');
                
                // Grunddesign
                if (document.getElementById('style-striped')?.checked) classes.push('striped');
                if (document.getElementById('style-celled')?.checked) classes.push('celled');
                if (document.getElementById('style-basic')?.checked) classes.push('basic');
                
                // Layout
                if (document.getElementById('layout-fixed')?.checked) classes.push('fixed');
                if (document.getElementById('layout-single-line')?.checked) classes.push('single', 'line');
                if (document.getElementById('layout-collapsing')?.checked) classes.push('collapsing');
                
                // Responsive
                const responsive = document.getElementById('responsive-mode')?.value;
                if (responsive) classes.push(...responsive.split(' '));
                
                // Color
                const color = document.getElementById('color-scheme')?.value;
                if (color) classes.push(color);
                
                // Size
                const size = document.getElementById('size-select')?.value;
                if (size) classes.push(size);
                
                // Padding
                const padding = document.getElementById('padding-select')?.value;
                if (padding) {
                    if (padding.includes('very')) {
                        classes.push('very', padding.split(' ')[1]);
                    } else {
                        classes.push(padding);
                    }
                }
                
                // Always add selectable for better UX
                classes.push('selectable');
                
            } else if (theme === 'bootstrap') {
                classes.push('table');
                if (document.getElementById('style-striped')?.checked) classes.push('table-striped');
                if (document.getElementById('style-celled')?.checked) classes.push('table-bordered');
                if (document.getElementById('style-hover')?.checked) classes.push('table-hover');
                if (document.getElementById('style-compact')?.checked) classes.push('table-sm');
                
                const color = document.getElementById('color-scheme')?.value;
                if (color) {
                    const bsColors = {
                        'red': 'table-danger',
                        'green': 'table-success',
                        'blue': 'table-primary',
                        'yellow': 'table-warning',
                        'grey': 'table-secondary',
                        'black': 'table-dark'
                    };
                    if (bsColors[color]) classes.push(bsColors[color]);
                }
                
                const responsive = document.getElementById('responsive-mode')?.value;
                if (responsive && responsive.includes('stackable')) {
                    classes.push('table-responsive');
                }
                
            } else if (theme === 'material') {
                classes.push('highlight', 'responsive-table');
                if (document.getElementById('style-striped')?.checked) classes.push('striped');
                if (document.getElementById('style-hover')?.checked) classes.push('hoverable');
                if (document.getElementById('style-celled')?.checked) classes.push('bordered');
                if (document.getElementById('style-compact')?.checked) classes.push('compact');
            }
            
            // These old theme checks are no longer needed - handled by getTableClasses()
            
            return classes.join(' ');
        }
        
        function generateCode() {
            const features = {
                search: document.getElementById('feature-search')?.checked || false,
                sort: document.getElementById('feature-sort')?.checked || false,
                filter: document.getElementById('feature-filter')?.checked || false,
                pagination: document.getElementById('feature-pagination')?.checked || false,
                export: document.getElementById('feature-export')?.checked || false,
                selection: document.getElementById('feature-selection')?.checked || false,
                actions: document.getElementById('feature-actions')?.checked || false
            };
            
            // Get dropdown values from new reorganized controls
            const paddingStyle = $('#padding-select').val() || '';
            const responsiveStyle = $('#responsive-mode').val() || '';
            
            const styles = {
                striped: document.getElementById('style-striped')?.checked || false,
                compact: document.getElementById('style-compact')?.checked || false,
                celled: document.getElementById('style-celled')?.checked || false,
                collapsing: document.getElementById('layout-collapsing')?.checked || false,
                fixed: document.getElementById('layout-fixed')?.checked || false,
                singleLine: document.getElementById('layout-single-line')?.checked || false,
                stackable: responsiveStyle === 'stackable',
                unstackable: responsiveStyle === 'unstackable',
                basic: document.getElementById('style-basic')?.checked || false,
                leftAligned: document.getElementById('style-left-aligned')?.checked || false,
                centerAligned: document.getElementById('style-center-aligned')?.checked || false,
                rightAligned: document.getElementById('style-right-aligned')?.checked || false,
                selectable: document.getElementById('style-selectable')?.checked || false,
                sortable: document.getElementById('style-sortable')?.checked || false,
                scrolling: document.getElementById('style-scrolling')?.checked || false,
                padded: paddingStyle === 'padded',
                relaxed: paddingStyle === 'relaxed',
                veryRelaxed: paddingStyle === 'very relaxed',
                color: $('#color-scheme').val() || '',
                size: $('#size-select').val() || ''
            };
            
            generatePHPCode(features, styles);
        }
        
        function generatePHPCode(features, styles) {
            let codeLines = [];
            
            // Opening PHP tag
            codeLines.push('<' + '?php');
            codeLines.push("require_once 'easy_form/autoload.php';");
            codeLines.push('use EasyForm\\\\EasyList;');
            codeLines.push('');
            codeLines.push('// Erstelle eine neue Liste');
            let listOptions = [];
            // Table style is now built from multiple options
            const tableClasses = getTableClasses();
            if (tableClasses.length > 2) { // More than just 'ui table'
                listOptions.push("'classes' => '" + tableClasses + "'");
            }
            
            if (listOptions.length > 0) {
                codeLines.push("$list = new EasyList('data_list', [");
                listOptions.forEach((opt, index) => {
                    codeLines.push("    " + opt + (index < listOptions.length - 1 ? ',' : ''));
                });
                codeLines.push("]);");
            } else {
                codeLines.push("$list = new EasyList('data_list');");
            }
            codeLines.push('');
            codeLines.push('// Datenquelle festlegen');
            codeLines.push('$list->data($data); // $data = Array mit Ihren Daten');
            codeLines.push('');
            codeLines.push('// Spalten definieren');
            
            columns.forEach(col => {
                let columnCode = "$list->column('" + col.key + "', '" + col.label + "'";
                let options = [];
                
                if (col.type !== 'text') options.push("    'type' => '" + col.type + "'");
                if (col.width) options.push("    'width' => '" + col.width + "'");
                if (col.align && col.align !== 'left') options.push("    'align' => '" + col.align + "'");
                if (col.sortable === false) options.push("    'sortable' => false");
                if (col.searchable === false) options.push("    'searchable' => false");
                if (col.filter) options.push("    'filter' => '" + col.filter + "'");
                
                if (options.length > 0) {
                    columnCode += ', [';
                    codeLines.push(columnCode);
                    options.forEach((opt, index) => {
                        codeLines.push(opt + (index < options.length - 1 ? ',' : ''));
                    });
                    codeLines.push(']);');
                } else {
                    codeLines.push(columnCode + ');');
                }
            });
            
            // Actions
            if (features.actions && actions.length > 0) {
                codeLines.push('');
                codeLines.push('// Action-Buttons konfigurieren');
                codeLines.push('$list->actions([');
                
                actions.forEach((action, index) => {
                    codeLines.push('    [');
                    codeLines.push("        'label' => '" + action.label + "',");
                    codeLines.push("        'icon' => '" + action.icon + "',");
                    codeLines.push("        'url' => '#', // Ihre URL hier");
                    
                    if (action.color) {
                        codeLines.push("        'class' => '" + action.color + "',");
                    }
                    
                    if (action.type === 'modal') {
                        codeLines.push("        'data-action' => 'modal',");
                        codeLines.push("        'data-id' => '{id}'");
                    } else {
                        codeLines.push("        'data-action' => '" + action.type + "',");
                        codeLines.push("        'data-id' => '{id}'");
                    }
                    
                    const isLast = index === actions.length - 1;
                    codeLines.push('    ]' + (isLast ? '' : ','));
                });
                
                codeLines.push(']);');
            }

            // Features
            if (features.search || features.sort || features.pagination || features.export || features.selection) {
                codeLines.push('');
                codeLines.push('// Features konfigurieren');
                
                if (features.search) {
                    codeLines.push("$list->searchable(true, 'Suchen...');");
                }
                if (features.sort) {
                    codeLines.push('$list->sortable(true);');
                }
                if (features.pagination) {
                    codeLines.push('$list->paginate(true, ' + listConfig.itemsPerPage + ');');
                }
                if (features.export) {
                    codeLines.push("$list->exportable(true, ['csv', 'excel']);");
                }
                if (features.selection) {
                    codeLines.push('$list->selectable(true);');
                    codeLines.push('');
                    codeLines.push('// Bulk-Aktionen hinzufügen');
                    codeLines.push('$list->bulkActions([');
                    codeLines.push("    'delete' => ['label' => 'Löschen', 'icon' => 'trash'],");
                    codeLines.push("    'activate' => ['label' => 'Aktivieren', 'icon' => 'check'],");
                    codeLines.push("    'deactivate' => ['label' => 'Deaktivieren', 'icon' => 'ban']");
                    codeLines.push(']);');
                }
            }
            
            // Styling
            const hasAnyStyle = styles.striped || styles.compact || styles.hover || styles.celled || styles.basic || styles.veryBasic || styles.leftAligned || styles.centerAligned || styles.rightAligned || styles.stackable || styles.selectable || styles.sortable || styles.padded || styles.relaxed || styles.veryRelaxed || styles.color || styles.size;
            
            if (hasAnyStyle) {
                codeLines.push('');
                codeLines.push('// Styling');
                
                // Build table class string - celled must come before table for Semantic UI
                let tableClass = 'ui';
                if (styles.celled) tableClass += ' celled';
                tableClass += ' table';
                if (styles.striped) tableClass += ' striped';
                if (styles.collapsing) tableClass += ' collapsing';
                if (styles.fixed) tableClass += ' fixed';
                if (styles.singleLine) tableClass += ' single line';
                if (styles.compact) tableClass += ' compact';
                if (styles.selectable) tableClass += ' selectable';
                // Lines style
                if (styles.basic) {
                    tableClass += ' basic';
                }
                if (styles.leftAligned) tableClass += ' left aligned';
                if (styles.centerAligned) tableClass += ' center aligned';
                if (styles.rightAligned) tableClass += ' right aligned';
                // Stacking
                if (styles.unstackable) {
                    tableClass += ' unstackable';
                } else if (styles.stackable) {
                    tableClass += ' stackable';
                }
                if (styles.sortable) tableClass += ' sortable';
                if (styles.scrolling) tableClass += ' scrolling';
                // Padding style
                if (styles.veryRelaxed) {
                    tableClass += ' very relaxed';
                } else if (styles.relaxed) {
                    tableClass += ' relaxed';
                } else if (styles.padded) {
                    tableClass += ' padded';
                }
                if (styles.color) tableClass += ' ' + styles.color;
                if (styles.size) tableClass += ' ' + styles.size;
                
                let styleCode = "$list->style('" + tableClass + "'";
                const styleOptions = [];
                
                if (styles.striped) styleOptions.push("    'striped' => true");
                if (styles.compact) styleOptions.push("    'compact' => true");
                if (styles.hover) styleOptions.push("    'hover' => true");
                if (styles.selectable) styleOptions.push("    'selectable' => true");
                
                if (styleOptions.length > 0) {
                    styleCode += ', [';
                    codeLines.push(styleCode);
                    styleOptions.forEach((opt, index) => {
                        codeLines.push(opt + (index < styleOptions.length - 1 ? ',' : ''));
                    });
                    codeLines.push(']);');
                } else {
                    codeLines.push(styleCode + ');');
                }
            }
            
            codeLines.push('');
            codeLines.push('// Liste anzeigen');
            codeLines.push('$list->display();');
            codeLines.push('?' + '>');
            
            // Add JavaScript examples if actions are enabled
            if (features.actions && actions.length > 0) {
                codeLines.push('');
                codeLines.push('<!-- JavaScript für Action-Handler -->');
                codeLines.push('<' + 'script>');
                codeLines.push('document.addEventListener("DOMContentLoaded", function() {');
                codeLines.push('    // Action-Button Event Handler');
                codeLines.push('    $(document).on("click", ".ui.button[data-action]", function(e) {');
                codeLines.push('        e.preventDefault();');
                codeLines.push('        const action = $(this).data("action");');
                codeLines.push('        const id = $(this).data("id");');
                codeLines.push('        ');
                codeLines.push('        switch(action) {');
                
                const uniqueActions = [...new Set(actions.map(a => a.type))];
                uniqueActions.forEach(actionType => {
                    codeLines.push(`            case "${actionType}":`);
                    if (actionType === 'modal') {
                        codeLines.push('                // Modal öffnen');
                        codeLines.push('                $("#your-modal").modal("show");');
                    } else if (actionType === 'edit') {
                        codeLines.push('                // Bearbeiten-Logic');
                        codeLines.push('                window.location.href = `/edit/${id}`;');
                    } else if (actionType === 'delete') {
                        codeLines.push('                // Lösch-Bestätigung');
                        codeLines.push('                if (confirm("Eintrag wirklich löschen?")) {');
                        codeLines.push('                    // Lösch-Request senden');
                        codeLines.push('                }');
                    } else {
                        codeLines.push('                // Ihre Logic hier');
                    }
                    codeLines.push('                break;');
                });
                
                codeLines.push('        }');
                codeLines.push('    });');
                codeLines.push('});');
                codeLines.push('</' + 'script>');
            }
            
            const code = codeLines.join('\n');
            
            // Set the code content
            const codeElement = document.getElementById('code-output');
            codeElement.textContent = code;
            codeElement.className = 'language-php';
            
            // Apply syntax highlighting if Prism is loaded
            if (typeof Prism !== 'undefined') {
                Prism.highlightElement(codeElement);
            }
        }
        
        function copyCode() {
            const code = document.getElementById('code-output').textContent;
            navigator.clipboard.writeText(code).then(() => {
                const btn = document.querySelector('.copy-btn');
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<i class="check icon"></i> Kopiert!';
                btn.classList.add('copied');
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.classList.remove('copied');
                }, 2000);
            }).catch(err => {
                console.error('Fehler beim Kopieren:', err);
            });
        }
        
        function showCodeTab(tab) {
            document.querySelectorAll('.code-tab').forEach(t => {
                t.classList.remove('active');
            });
            event.target.classList.add('active');
            
            if (tab === 'javascript') {
                generateJavaScriptCode();
            } else if (tab === 'html') {
                generateHTMLCode();
            } else {
                generateCode();
            }
        }
        
        function generateJavaScriptCode() {
            const code = '// EasyList JavaScript Integration\n' +
'<' + 'script src="easy_form/js/easylist.js"><' + '/script>\n\n' +
'<' + 'script>\n' +
'// Initialisiere die Liste\n' +
'document.addEventListener(\'DOMContentLoaded\', function() {\n' +
'    // Event Handler für Bulk Actions\n' +
'    document.addEventListener(\'easylist:bulkaction\', function(e) {\n' +
'        // Bulk action: e.detail.action\n' +
'        // Selected IDs: e.detail.ids\n' +
'        \n' +
'        // Ihre custom Logic hier\n' +
'        switch(e.detail.action) {\n' +
'            case \'delete\':\n' +
'                if (confirm(\'Ausgewählte Einträge löschen?\')) {\n' +
'                    // Delete logic\n' +
'                }\n' +
'                break;\n' +
'            case \'export\':\n' +
'                // Export logic\n' +
'                break;\n' +
'        }\n' +
'    });\n' +
'});\n' +
'<' + '/script>';
            
            document.getElementById('code-output').textContent = code;
            document.getElementById('code-output').className = 'language-javascript';
            Prism.highlightElement(document.getElementById('code-output'));
        }
        
        function generateHTMLCode() {
            const code = '<!DOCTYPE html>\n' +
'<html>\n' +
'<head>\n' +
'    <title>EasyList Demo</title>\n' +
'    <link rel="stylesheet" href="semantic/dist/semantic.min.css">\n' +
'    <style>\n' +
'        /* Custom Styles für EasyList */\n' +
'        .easylist-container {\n' +
'            padding: 20px;\n' +
'        }\n' +
'        \n' +
'        .easylist-toolbar {\n' +
'            margin-bottom: 20px;\n' +
'        }\n' +
'        \n' +
'        .table-responsive {\n' +
'            overflow-x: auto;\n' +
'        }\n' +
'    </style>\n' +
'</head>\n' +
'<body>\n' +
'    <div class="ui container">\n' +
'        <h1>Datenliste</h1>\n' +
'        \n' +
'        <!-- PHP Code einbinden -->\n' +
'        <' + '?php include \'list.php\'; ?' + '>\n' +
'    </div>\n' +
'    \n' +
'    <' + 'script src="jquery/jquery.min.js"><' + '/script>\n' +
'    <' + 'script src="semantic/dist/semantic.min.js"><' + '/script>\n' +
'    <' + 'script src="js/easylist.js"><' + '/script>\n' +
'</body>\n' +
'</html>';
            
            document.getElementById('code-output').textContent = code;
            document.getElementById('code-output').className = 'language-html';
            Prism.highlightElement(document.getElementById('code-output'));
        }
        
        function resetBuilder() {
            if (confirm('Möchten Sie wirklich alle Einstellungen zurücksetzen?')) {
                loadSampleData('users');
            }
        }
        
        // Preview search function
        function previewSearch(searchTerm) {
            const rows = document.querySelectorAll('#preview-content tbody tr');
            const term = searchTerm.toLowerCase();
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(term)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        // Toggle all checkboxes
        function toggleAllCheckboxes(masterCheckbox) {
            const checkboxes = document.querySelectorAll('#preview-content .row-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = masterCheckbox.checked;
            });
        }
        
        // Action functions
        function addAction() {
            currentEditAction = null;
            document.getElementById('action-type').value = 'edit';
            document.getElementById('action-label').value = '';
            document.getElementById('action-icon').value = 'edit';
            document.getElementById('action-color').value = '';
            updateActionFields();
            document.getElementById('action-modal').classList.add('active');
        }
        
        function editAction(index) {
            currentEditAction = index;
            const action = actions[index];
            document.getElementById('action-type').value = action.type || 'edit';
            document.getElementById('action-label').value = action.label || '';
            document.getElementById('action-icon').value = action.icon || '';
            document.getElementById('action-color').value = action.color || '';
            document.getElementById('action-tooltip').value = action.tooltip || '';
            document.getElementById('action-tooltip-position').value = action.tooltipPosition || 'top';
            
            // Load conditional settings
            document.getElementById('action-conditional').checked = action.conditional || false;
            if (action.conditional) {
                document.getElementById('conditional-fields').style.display = 'block';
                document.getElementById('action-condition-field').value = action.conditionField || '';
                document.getElementById('action-condition-type').value = action.conditionType || 'equals';
                document.getElementById('action-condition-value').value = action.conditionValue || '';
            }
            
            if (action.type === 'modal') {
                document.getElementById('modal-title').value = action.modalTitle || '';
                document.getElementById('modal-size').value = action.modalSize || '';
                document.getElementById('modal-content-type').value = action.modalContentType || 'form';
                document.getElementById('modal-form-path').value = action.modalFormPath || '';
                document.getElementById('modal-url').value = action.modalUrl || '';
            } else if (action.type === 'custom') {
                document.getElementById('action-function').value = action.function || '';
                document.getElementById('action-url').value = action.url || '';
            }
            
            updateActionFields();
            updateConditionalFieldOptions();
            document.getElementById('action-modal').classList.add('active');
        }
        
        function removeAction(index) {
            actions.splice(index, 1);
            renderActions();
            refreshPreview();
            generateCode();
        }
        
        function saveActionSettings() {
            const actionData = {
                type: document.getElementById('action-type').value,
                label: document.getElementById('action-label').value,
                icon: document.getElementById('action-icon').value,
                color: document.getElementById('action-color').value,
                tooltip: document.getElementById('action-tooltip').value,
                tooltipPosition: document.getElementById('action-tooltip-position').value
            };
            
            // Save conditional settings
            if (document.getElementById('action-conditional').checked) {
                actionData.conditional = true;
                actionData.conditionField = document.getElementById('action-condition-field').value;
                actionData.conditionType = document.getElementById('action-condition-type').value;
                actionData.conditionValue = document.getElementById('action-condition-value').value;
            }
            
            if (actionData.type === 'modal') {
                actionData.modalTitle = document.getElementById('modal-title').value;
                actionData.modalSize = document.getElementById('modal-size').value;
                actionData.modalContentType = document.getElementById('modal-content-type').value;
                actionData.modalFormPath = document.getElementById('modal-form-path').value;
                actionData.modalUrl = document.getElementById('modal-url').value;
            } else if (actionData.type === 'custom') {
                actionData.function = document.getElementById('action-function').value;
                actionData.url = document.getElementById('action-url').value;
            }
            
            if (!actionData.label) {
                alert('Bitte geben Sie ein Label ein');
                return;
            }
            
            if (currentEditAction !== null) {
                actions[currentEditAction] = actionData;
            } else {
                actions.push(actionData);
            }
            
            closeActionModal();
            renderActions();
            refreshPreview();
            generateCode();
        }
        
        function closeActionModal() {
            document.getElementById('action-modal').classList.remove('active');
        }
        
        function updateActionFields() {
            const type = document.getElementById('action-type').value;
            const modalFields = document.getElementById('modal-fields');
            const customFields = document.getElementById('custom-fields');
            
            modalFields.style.display = type === 'modal' ? 'block' : 'none';
            customFields.style.display = type === 'custom' ? 'block' : 'none';
            
            if (type === 'modal') {
                updateModalContent();
            }
        }
        
        function updateModalContent() {
            const contentType = document.getElementById('modal-content-type').value;
            document.getElementById('modal-form-field').style.display = contentType === 'form' ? 'block' : 'none';
            document.getElementById('modal-url-field').style.display = (contentType === 'iframe' || contentType === 'ajax') ? 'block' : 'none';
        }
        
        function toggleConditionalFields() {
            const isChecked = document.getElementById('action-conditional').checked;
            document.getElementById('conditional-fields').style.display = isChecked ? 'block' : 'none';
            if (isChecked) {
                updateConditionalFieldOptions();
            }
        }
        
        function updateConditionalFieldOptions() {
            const select = document.getElementById('action-condition-field');
            select.innerHTML = '<option value="">Spalte wählen...</option>';
            columns.forEach(col => {
                const option = document.createElement('option');
                option.value = col.key;
                option.textContent = col.label;
                select.appendChild(option);
            });
        }
        
        function toggleActionsSplit() {
            const position = document.getElementById('actions-position')?.value || 'right';
            const singleView = document.getElementById('actions-single');
            const splitView = document.getElementById('actions-split');
            
            if (position === 'both') {
                singleView.style.display = 'none';
                splitView.style.display = 'block';
                
                // Initialize split view if not already done
                if (!sortableLeft) {
                    initializeSortableActions();
                }
                
                // Distribute actions if switching to split view
                distributeActions();
            } else {
                singleView.style.display = 'block';
                splitView.style.display = 'none';
                
                // Merge actions back to single list
                mergeActions();
            }
        }
        
        function initializeSortableActions() {
            // Initialize sortable for left actions
            sortableLeft = new Sortable(document.getElementById('actions-left'), {
                group: 'actions',
                animation: 150,
                ghostClass: 'dragging',
                chosenClass: 'chosen',
                dragClass: 'drag',
                onEnd: function(evt) {
                    updateActionsFromDOM();
                    refreshPreview();
                    generateCode();
                }
            });
            
            // Initialize sortable for right actions
            sortableRight = new Sortable(document.getElementById('actions-right'), {
                group: 'actions',
                animation: 150,
                ghostClass: 'dragging',
                chosenClass: 'chosen',
                dragClass: 'drag',
                onEnd: function(evt) {
                    updateActionsFromDOM();
                    refreshPreview();
                    generateCode();
                }
            });
            
            // Initialize sortable for single view
            sortableSingle = new Sortable(document.getElementById('actions-single'), {
                animation: 150,
                ghostClass: 'dragging',
                chosenClass: 'chosen',
                dragClass: 'drag',
                onEnd: function(evt) {
                    updateActionsFromSingle();
                    refreshPreview();
                    generateCode();
                }
            });
        }
        
        function initializeSortableColumns() {
            // Initialize sortable for columns
            sortableColumns = new Sortable(document.getElementById('column-list'), {
                animation: 150,
                ghostClass: 'dragging',
                chosenClass: 'chosen',
                dragClass: 'drag',
                onEnd: function(evt) {
                    updateColumnsOrder();
                    refreshPreview();
                    generateCode();
                }
            });
        }
        
        function updateActionsFromSingle() {
            const singleContainer = document.getElementById('actions-single');
            actions = [];
            
            singleContainer.querySelectorAll('.action-element').forEach(item => {
                const actionData = item.dataset.actionData;
                if (actionData) {
                    try {
                        const action = JSON.parse(actionData);
                        actions.push(action);
                    } catch (e) {
                        // Fallback to index method
                        const actionIndex = parseInt(item.dataset.actionIndex);
                        if (!isNaN(actionIndex) && actions[actionIndex]) {
                            actions.push(actions[actionIndex]);
                        }
                    }
                }
            });
        }
        
        function updateColumnsOrder() {
            const columnList = document.getElementById('column-list');
            const newColumns = [];
            
            columnList.querySelectorAll('.column-item').forEach(item => {
                const columnData = item.dataset.columnData;
                if (columnData) {
                    try {
                        const column = JSON.parse(columnData);
                        newColumns.push(column);
                    } catch (e) {
                        // Fallback to index method
                        const columnIndex = parseInt(item.dataset.columnIndex);
                        if (!isNaN(columnIndex) && columns[columnIndex]) {
                            newColumns.push(columns[columnIndex]);
                        }
                    }
                }
            });
            
            columns = newColumns;
            
            // Re-render columns to update indices and actions
            setTimeout(() => {
                renderColumns();
            }, 100);
        }
        
        function distributeActions() {
            const leftContainer = document.getElementById('actions-left');
            const rightContainer = document.getElementById('actions-right');
            
            // Clear containers
            leftContainer.innerHTML = '';
            rightContainer.innerHTML = '';
            
            // Hide placeholder hints when there are actions
            const leftPlaceholder = leftContainer.parentElement.querySelector('.placeholder-hint');
            const rightPlaceholder = rightContainer.parentElement.querySelector('.placeholder-hint');
            if (leftPlaceholder) leftPlaceholder.style.display = actions.length > 0 ? 'none' : 'block';
            if (rightPlaceholder) rightPlaceholder.style.display = actions.length > 0 ? 'none' : 'block';
            
            // Distribute existing actions (alternating by default)
            actions.forEach((action, index) => {
                const container = index % 2 === 0 ? leftContainer : rightContainer;
                container.appendChild(createActionElement(action, index));
            });
            
            updateActionsFromDOM();
        }
        
        function mergeActions() {
            const singleContainer = document.getElementById('actions-single');
            singleContainer.innerHTML = '';
            
            // Merge all actions back to single list preserving order
            const allActions = [...actionsLeft, ...actionsRight];
            actions = allActions; // Update the global actions array
            
            allActions.forEach((action, index) => {
                const actionEl = createActionElement(action, index);
                // Style for single container - block layout with drag handle
                actionEl.style.cssText = 'display: flex; width: 100%; margin: 6px 0; padding: 12px 16px; background: white; border: 2px solid #e0e1e2; border-radius: 6px; cursor: move; align-items: center; gap: 12px; transition: all 0.2s;';
                
                // Add drag handle at the beginning
                const dragHandle = document.createElement('i');
                dragHandle.className = 'bars icon';
                dragHandle.style.cssText = 'color: #999; cursor: grab;';
                actionEl.insertBefore(dragHandle, actionEl.firstChild);
                
                singleContainer.appendChild(actionEl);
            });
        }
        
        function updateActionsFromDOM() {
            const leftContainer = document.getElementById('actions-left');
            const rightContainer = document.getElementById('actions-right');
            
            actionsLeft = [];
            actionsRight = [];
            
            // Get actions from left container - preserve order
            leftContainer.querySelectorAll('.action-element').forEach(item => {
                const actionData = item.dataset.actionData;
                if (actionData) {
                    try {
                        const action = JSON.parse(actionData);
                        actionsLeft.push(action);
                    } catch (e) {
                        // Fallback to index method
                        const actionIndex = parseInt(item.dataset.actionIndex);
                        if (!isNaN(actionIndex) && actions[actionIndex]) {
                            actionsLeft.push(actions[actionIndex]);
                        }
                    }
                }
            });
            
            // Get actions from right container - preserve order
            rightContainer.querySelectorAll('.action-element').forEach(item => {
                const actionData = item.dataset.actionData;
                if (actionData) {
                    try {
                        const action = JSON.parse(actionData);
                        actionsRight.push(action);
                    } catch (e) {
                        // Fallback to index method
                        const actionIndex = parseInt(item.dataset.actionIndex);
                        if (!isNaN(actionIndex) && actions[actionIndex]) {
                            actionsRight.push(actions[actionIndex]);
                        }
                    }
                }
            });
            
            refreshPreview();
        }
        
        function createActionElement(action, index) {
            const actionEl = document.createElement('div');
            actionEl.className = 'ui label action-element';
            actionEl.dataset.actionIndex = index;
            actionEl.style.cssText = 'padding: 12px 16px; margin: 5px; cursor: move; background: white; border: 2px solid #e0e1e2; font-size: 0.95em; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;';
            
            // Store the action data in the element
            actionEl.dataset.actionData = JSON.stringify(action);
            
            // Apply color styling
            let elementColor = 'white';
            let borderColor = '#e0e1e2';
            let textColor = '#333';
            
            if (action.color) {
                switch(action.color) {
                    case 'red':
                        elementColor = '#ffebee';
                        borderColor = '#f44336';
                        textColor = '#c62828';
                        break;
                    case 'green':
                        elementColor = '#e8f5e8';
                        borderColor = '#4caf50';
                        textColor = '#2e7d32';
                        break;
                    case 'blue':
                        elementColor = '#e3f2fd';
                        borderColor = '#2196f3';
                        textColor = '#1565c0';
                        break;
                    case 'orange':
                        elementColor = '#fff3e0';
                        borderColor = '#ff9800';
                        textColor = '#e65100';
                        break;
                    case 'purple':
                        elementColor = '#f3e5f5';
                        borderColor = '#9c27b0';
                        textColor = '#6a1b9a';
                        break;
                }
            }
            
            actionEl.style.cssText = `padding: 12px 16px; margin: 5px; cursor: move; background: ${elementColor}; border: 2px solid ${borderColor}; font-size: 0.95em; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; color: ${textColor};`;
            
            actionEl.innerHTML = `
                <i class="${action.icon} icon" style="margin: 0; color: ${textColor};"></i>
                <span style="flex: 1; color: ${textColor};">${action.label}</span>
                <div style="display: inline-flex; gap: 4px; opacity: 0.7;">
                    <i class="edit outline icon" onclick="editAction(${index})" style="cursor: pointer;"></i>
                    <i class="trash alternate outline icon" onclick="removeAction(${index})" style="cursor: pointer; color: #db2828;"></i>
                </div>
            `;
            
            // Add hover effect
            actionEl.onmouseenter = function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
                this.style.borderColor = '#2185d0';
            };
            actionEl.onmouseleave = function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
                this.style.borderColor = '#e0e1e2';
            };
            
            return actionEl;
        }
        
        function renderActions() {
            if (actions.length === 0) {
                // Add default actions with tooltips
                actions = [
                    { 
                        type: 'edit', 
                        label: 'Bearbeiten', 
                        icon: 'edit', 
                        color: '', 
                        tooltip: 'Eintrag bearbeiten und Änderungen speichern',
                        tooltipPosition: 'top'
                    },
                    { 
                        type: 'delete', 
                        label: 'Löschen', 
                        icon: 'trash', 
                        color: 'red',
                        tooltip: 'Diesen Eintrag unwiderruflich löschen',
                        tooltipPosition: 'top'
                    }
                ];
            }
            
            const position = document.getElementById('actions-position')?.value || 'right';
            
            if (position === 'both') {
                // Render in split view
                const leftContainer = document.getElementById('actions-left');
                const rightContainer = document.getElementById('actions-right');
                
                leftContainer.innerHTML = '';
                rightContainer.innerHTML = '';
                
                actions.forEach((action, index) => {
                    const container = index % 2 === 0 ? leftContainer : rightContainer;
                    container.appendChild(createActionElement(action, index));
                });
                
                updateActionsFromDOM();
            } else {
                // Render in single view
                const singleContainer = document.getElementById('actions-single');
                singleContainer.innerHTML = '';
                
                actions.forEach((action, index) => {
                    const actionEl = createActionElement(action, index);
                    // Style for single container - block layout with drag handle
                    actionEl.style.cssText = 'display: flex; width: 100%; margin: 6px 0; padding: 12px 16px; background: white; border: 2px solid #e0e1e2; border-radius: 6px; cursor: move; align-items: center; gap: 12px; transition: all 0.2s;';
                    
                    // Add drag handle at the beginning
                    const dragHandle = document.createElement('i');
                    dragHandle.className = 'bars icon';
                    dragHandle.style.cssText = 'color: #999; cursor: grab;';
                    actionEl.insertBefore(dragHandle, actionEl.firstChild);
                    
                    singleContainer.appendChild(actionEl);
                });
            }
        }
        
        // Initialize actions on load
        renderActions();
        
        // Modal close on outside click
        document.getElementById('column-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeColumnModal();
            }
        });
        
        document.getElementById('action-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeActionModal();
            }
        });
        
        // Make functions globally available
        window.addColumn = addColumn;
        window.editColumn = editColumn;
        window.removeColumn = removeColumn;
        window.saveColumnSettings = saveColumnSettings;
        window.closeColumnModal = closeColumnModal;
        window.switchDataSource = switchDataSource;
        window.refreshPreview = refreshPreview;
        window.showCodeTab = showCodeTab;
        window.resetBuilder = resetBuilder;
        window.generateCode = generateCode;
        window.loadSampleData = loadSampleData;
        window.previewSearch = previewSearch;
        window.toggleAllCheckboxes = toggleAllCheckboxes;
        window.addAction = addAction;
        window.editAction = editAction;
        window.removeAction = removeAction;
        window.saveActionSettings = saveActionSettings;
        window.closeActionModal = closeActionModal;
        window.updateActionFields = updateActionFields;
        window.updateModalContent = updateModalContent;
        window.toggleConditionalFields = toggleConditionalFields;
        window.updateConditionalFieldOptions = updateConditionalFieldOptions;
        
        // Template Functions
        function saveTemplate() {
            $('#template-modal').modal('show');
        }
        
        function confirmSaveTemplate() {
            const templateName = document.getElementById('template-name').value;
            const templateDescription = document.getElementById('template-description').value;
            
            if (!templateName) {
                alert('Bitte geben Sie einen Template-Namen ein');
                return;
            }
            
            const templateData = {
                name: templateName,
                description: templateDescription,
                date: new Date().toISOString(),
                columns: columns,
                features: {
                    search: document.getElementById('feature-search')?.checked || false,
                    sort: document.getElementById('feature-sort')?.checked || false,
                    filter: document.getElementById('feature-filter')?.checked || false,
                    pagination: document.getElementById('feature-pagination')?.checked || false,
                    export: document.getElementById('feature-export')?.checked || false,
                    selection: document.getElementById('feature-selection')?.checked || false,
                    actions: document.getElementById('feature-actions')?.checked || false
                },
                styles: {
                    striped: document.getElementById('style-striped')?.checked || false,
                    compact: document.getElementById('style-compact')?.checked || false,
                    celled: document.getElementById('style-celled')?.checked || false,
                    basic: document.getElementById('style-basic')?.checked || false,
                    hover: document.getElementById('style-hover')?.checked || false,
                    fixed: document.getElementById('layout-fixed')?.checked || false,
                    singleLine: document.getElementById('layout-single-line')?.checked || false,
                    collapsing: document.getElementById('layout-collapsing')?.checked || false,
                    color: $('#color-scheme').val() || '',
                    size: $('#size-select').val() || '',
                    padding: $('#padding-select').val() || '',
                    responsive: $('#responsive-mode').val() || ''
                },
                actions: actions,
                actionsPosition: document.getElementById('actions-position')?.value || 'right'
            };
            
            // Save to localStorage
            let templates = JSON.parse(localStorage.getItem('easylist_templates') || '[]');
            templates.push(templateData);
            localStorage.setItem('easylist_templates', JSON.stringify(templates));
            
            // Clear form
            document.getElementById('template-name').value = '';
            document.getElementById('template-description').value = '';
            
            $('#template-modal').modal('hide');
            
            // Show success message
            alert('Template erfolgreich gespeichert!');
        }
        
        function loadTemplate() {
            const templates = JSON.parse(localStorage.getItem('easylist_templates') || '[]');
            const templateList = document.getElementById('template-list');
            
            if (templates.length === 0) {
                templateList.innerHTML = '<div class="ui message">Keine Templates vorhanden</div>';
            } else {
                templateList.innerHTML = templates.map((template, index) => `
                    <div class="item">
                        <div class="content">
                            <div class="header">${template.name}</div>
                            <div class="meta">
                                <span>${new Date(template.date).toLocaleDateString('de-DE')}</span>
                            </div>
                            <div class="description">${template.description || 'Keine Beschreibung'}</div>
                            <div class="extra">
                                <button class="ui primary button" onclick="applyTemplate(${index})">
                                    <i class="check icon"></i> Anwenden
                                </button>
                                <button class="ui red button" onclick="deleteTemplate(${index})">
                                    <i class="trash icon"></i> Löschen
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');
            }
            
            $('#template-load-modal').modal('show');
        }
        
        function applyTemplate(index) {
            const templates = JSON.parse(localStorage.getItem('easylist_templates') || '[]');
            const template = templates[index];
            
            if (!template) return;
            
            // Apply columns
            columns = template.columns || [];
            renderColumns();
            
            // Apply features
            if (template.features) {
                document.getElementById('feature-search').checked = template.features.search;
                document.getElementById('feature-sort').checked = template.features.sort;
                document.getElementById('feature-filter').checked = template.features.filter;
                document.getElementById('feature-pagination').checked = template.features.pagination;
                document.getElementById('feature-export').checked = template.features.export;
                document.getElementById('feature-selection').checked = template.features.selection;
                document.getElementById('feature-actions').checked = template.features.actions;
            }
            
            // Apply styles
            if (template.styles) {
                document.getElementById('style-striped').checked = template.styles.striped || false;
                document.getElementById('style-compact').checked = template.styles.compact || false;
                document.getElementById('style-celled').checked = template.styles.celled !== undefined ? template.styles.celled : true;
                document.getElementById('style-basic').checked = template.styles.basic || false;
                document.getElementById('style-hover').checked = template.styles.hover !== undefined ? template.styles.hover : true;
                
                document.getElementById('layout-fixed').checked = template.styles.fixed || false;
                document.getElementById('layout-single-line').checked = template.styles.singleLine || false;
                document.getElementById('layout-collapsing').checked = template.styles.collapsing || false;
                
                $('#color-scheme').val(template.styles.color || '').trigger('change');
                $('#size-select').val(template.styles.size || '').trigger('change');
                $('#padding-select').val(template.styles.padding || '').trigger('change');
                $('#responsive-mode').val(template.styles.responsive || '').trigger('change');
            }
            
            // Apply actions
            if (template.actions) {
                actions = template.actions;
                renderActions();
            }
            
            if (template.actionsPosition) {
                document.getElementById('actions-position').value = template.actionsPosition;
                toggleActionsSplit();
            }
            
            $('#template-load-modal').modal('hide');
            
            refreshPreview();
            generateCode();
        }
        
        function deleteTemplate(index) {
            if (confirm('Möchten Sie dieses Template wirklich löschen?')) {
                let templates = JSON.parse(localStorage.getItem('easylist_templates') || '[]');
                templates.splice(index, 1);
                localStorage.setItem('easylist_templates', JSON.stringify(templates));
                loadTemplate(); // Reload the list
            }
        }
        
        window.saveTemplate = saveTemplate;
        window.confirmSaveTemplate = confirmSaveTemplate;
        // Template Manager Function
        function showTemplateManager() {
            // Show unified template manager modal
            $('#template-manager-modal').modal('show');
        }
        
        // List Config Function
        function showListConfig() {
            // Load current config into modal
            $('#table-name').val(listConfig.tableName);
            $('#table-description').val(listConfig.description);
            $('#items-per-page').val(listConfig.itemsPerPage);
            
            // Show list configuration modal
            $('#config-modal').modal('show');
        }
        
        // Save List Config Function
        function saveListConfig() {
            // Save configuration from modal
            listConfig.tableName = $('#table-name').val() || 'data_list';
            listConfig.description = $('#table-description').val();
            listConfig.itemsPerPage = parseInt($('#items-per-page').val()) || 25;
            
            // Close modal
            $('#config-modal').modal('hide');
            
            // Show success toast notification
            showSuccessToast('Konfiguration gespeichert!');
            
            // Refresh preview and code with new config
            refreshPreview();
            generateCode();
        }
        
        window.saveListConfig = saveListConfig;
        
        window.saveTemplate = saveTemplate;
        window.loadTemplate = loadTemplate;
        window.applyTemplate = applyTemplate;
        window.deleteTemplate = deleteTemplate;
        window.showTemplateManager = showTemplateManager;
        window.showListConfig = showListConfig;
        
        // Initialize on page load
        $(document).ready(function() {
            // Initialize Semantic UI dropdowns
            $('.ui.dropdown').dropdown();
            
            // Initialize with default data
            loadSampleData('users');
            
            // Initial preview refresh
            refreshPreview();
        });
    </script>
    
    <!-- Template Manager Modal -->
    <div id="template-manager-modal" class="ui modal">
        <div class="header">
            <i class="folder icon"></i> Template Manager
        </div>
        <div class="content">
            <div class="ui two buttons">
                <button class="ui button primary" onclick="saveTemplate()">
                    <i class="save icon"></i> Neues Template speichern
                </button>
                <button class="ui button" onclick="loadTemplate()">
                    <i class="folder open icon"></i> Template laden
                </button>
            </div>
        </div>
        <div class="actions">
            <div class="ui cancel button">Schließen</div>
        </div>
    </div>
    
    <!-- Config Modal -->
    <div id="config-modal" class="ui modal">
        <div class="header">
            <i class="cog icon"></i> Listen-Konfiguration
        </div>
        <div class="content">
            <div class="ui form">
                <div class="field">
                    <label>Tabellenname</label>
                    <input type="text" id="table-name" placeholder="z.B. Benutzerliste">
                </div>
                <div class="field">
                    <label>Beschreibung</label>
                    <textarea id="table-description" rows="2" placeholder="Beschreibung der Tabelle..."></textarea>
                </div>
                <div class="field">
                    <label>Einträge pro Seite</label>
                    <select id="items-per-page" class="ui dropdown">
                        <option value="10">10</option>
                        <option value="25" selected>25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="actions">
            <div class="ui cancel button" data-i18n="global.cancel">Abbrechen</div>
            <div class="ui primary button" onclick="saveListConfig()">
                <span data-i18n="global.save">Speichern</span>
            </div>
        </div>
    </div>
    
    <!-- Template Modals -->
    <div id="template-modal" class="ui modal">
        <div class="header">
            <i class="save outline icon"></i> Template speichern
        </div>
        <div class="content">
            <div class="ui form">
                <div class="field">
                    <label>Template Name</label>
                    <input type="text" id="template-name" placeholder="z.B. Benutzerliste mit Aktionen">
                </div>
                <div class="field">
                    <label>Beschreibung</label>
                    <textarea id="template-description" rows="3" placeholder="Beschreibung des Templates..."></textarea>
                </div>
            </div>
        </div>
        <div class="actions">
            <div class="ui cancel button">Abbrechen</div>
            <div class="ui primary approve button" onclick="confirmSaveTemplate()">Speichern</div>
        </div>
    </div>
    
    <div id="template-load-modal" class="ui modal large">
        <div class="header">
            <i class="folder open icon"></i> Template laden
        </div>
        <div class="content">
            <div class="ui divided items" id="template-list">
                <!-- Templates will be loaded here -->
            </div>
            <div class="ui info message">
                <i class="info icon"></i>
                Templates werden im Browser gespeichert (localStorage).
            </div>
        </div>
        <div class="actions">
            <div class="ui cancel button">Schließen</div>
        </div>
    </div>
</body>
</html>
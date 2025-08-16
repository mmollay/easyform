<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyForm Builder - Drag & Drop Form Generator</title>
    <link rel="stylesheet" href="semantic/dist/semantic.min.css">
    <link rel="stylesheet" href="js/sortable.min.css">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(120deg, #f6f8fb 0%, #e9ecf0 100%);
            margin: 0;
            padding: 20px;
        }

        .builder-container {
            display: grid;
            grid-template-columns: 300px 1fr;
            grid-template-rows: 1fr auto;
            gap: 20px;
            max-width: 1600px;
            margin: 0 auto;
            height: calc(100vh - 40px);
        }

        .top-row {
            grid-column: 1 / -1;
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 20px;
            height: 70vh; /* Fixed height for top section */
            min-height: 500px; /* Minimum height to prevent too small */
        }

        .code-panel {
            grid-column: 1 / -1;
        }

        .builder-panel {
            background: white;
            border-radius: 15px;
            padding: 0;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
        }

        .panel-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 20px 20px 15px 20px;
            border-bottom: 2px solid #f0f0f0;
            flex-shrink: 0; /* Prevent header from shrinking */
        }

        .panel-header h2 {
            margin: 0;
            font-size: 1.3rem;
            color: #2d3748;
        }

        .panel-header i {
            color: #667eea;
            font-size: 1.5rem;
        }

        /* Panel Content Areas */
        .panel-content {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }
        
        /* Components Panel */
        .components-panel .panel-content {
            padding: 15px;
        }

        .component-group {
            margin-bottom: 25px;
        }

        .component-group h3 {
            font-size: 1rem;
            color: #4a5568;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .component-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 10px;
            background: #f7fafc;
            border-radius: 6px;
            margin-bottom: 6px;
            cursor: grab;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            font-size: 13px;
        }

        .component-item:hover {
            background: #edf2f7;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .component-item:active {
            cursor: grabbing;
        }

        .component-item i {
            color: #667eea;
            width: 20px;
            text-align: center;
        }

        .component-item span {
            font-weight: 500;
            color: #2d3748;
        }

        /* Form Builder Area */
        .builder-area {
            position: relative;
            overflow-y: auto;
        }

        .drop-zone {
            flex: 1;
            min-height: 400px;
            border: 3px dashed #cbd5e0;
            border-radius: 10px;
            padding: 20px;
            position: relative;
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        .drop-zone.drag-over {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.05);
        }

        .drop-zone-placeholder {
            text-align: center;
            padding: 60px 20px;
            color: #a0aec0;
        }

        .drop-zone-placeholder i {
            font-size: 3rem;
            margin-bottom: 15px;
            display: block;
        }

        .form-element {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            position: relative;
            transition: all 0.3s ease;
        }

        /* Grid rows should span full width */
        .form-element .row-container.ui.grid {
            margin: 0 -15px;  /* Extend to edges of parent */
            padding: 15px;
            border-radius: 8px;
            background: #fafbfc;
            border: 1px solid #e2e8f0;
            width: calc(100% + 30px);  /* Full width plus parent padding */
        }
        
        /* Nested grids in columns */
        .form-element.nested-grid {
            background: transparent;
            border: none;
            padding: 0;
        }
        
        .form-element.nested-grid .row-container.ui.grid {
            margin: 0;
            width: 100%;
        }

        .form-element:hover {
            border-color: #667eea;
            box-shadow: 0 2px 10px rgba(102, 126, 234, 0.1);
        }

        .form-element.selected {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }

        .element-controls {
            position: absolute;
            top: -6px;
            right: -6px;
            display: flex;
            gap: 3px;
            opacity: 1;
            transition: opacity 0.2s ease;
            z-index: 10;
            pointer-events: auto;
        }

        .form-element:hover .element-controls {
            opacity: 1;
        }

        .control-btn {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }
        
        .control-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .control-btn:hover::before {
            width: 50px;
            height: 50px;
        }

        .control-btn.edit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .control-btn.delete {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .control-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0,0,0,0.12);
        }
        
        .control-btn:active {
            transform: translateY(0);
            box-shadow: 0 1px 2px rgba(0,0,0,0.08);
        }
        
        .control-btn i {
            position: relative;
            z-index: 1;
        }

        .drag-handle {
            position: absolute;
            left: -8px;
            top: 50%;
            transform: translateY(-50%);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 18px;
            height: 36px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: grab;
            opacity: 0;
            transition: all 0.2s ease;
            box-shadow: 0 1px 4px rgba(102, 126, 234, 0.2);
            font-size: 10px;
        }
        
        .drag-handle:hover {
            transform: translateY(-50%) scale(1.05);
            box-shadow: 0 2px 6px rgba(102, 126, 234, 0.3);
        }

        .form-element:hover .drag-handle {
            opacity: 1;
        }

        .drag-handle:active {
            cursor: grabbing;
        }

        /* Code Output Panel */
        .code-panel {
            display: flex;
            flex-direction: column;
        }

        .code-tabs {
            display: flex;
            margin-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .code-tab {
            padding: 8px 15px;
            background: none;
            border: none;
            color: #718096;
            cursor: pointer;
            font-weight: 500;
            position: relative;
            transition: color 0.3s ease;
        }

        .code-tab.active {
            color: #667eea;
        }

        .code-tab.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: #667eea;
        }

        .code-output {
            flex: 1;
            background: #1a202c;
            color: #e2e8f0;
            padding: 20px;
            border-radius: 8px;
            font-family: 'SF Mono', Monaco, monospace;
            font-size: 0.9rem;
            line-height: 1.6;
            overflow-y: auto;
            position: relative;
        }

        .code-output pre {
            margin: 0;
            white-space: pre-wrap;
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
        }

        .copy-btn:hover {
            background: #5a6fd8;
        }

        .copy-btn.copied {
            background: #48bb78;
        }

        /* Field Configuration Modal */
        .config-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .config-modal {
            background: white;
            border-radius: 15px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }

        .config-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .config-form .field {
            margin-bottom: 15px;
        }

        .config-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #2d3748;
        }

        .config-form input,
        .config-form textarea,
        .config-form select {
            width: 100%;
            padding: 10px;
            border: 2px solid #e2e8f0;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        .config-form input:focus,
        .config-form textarea:focus,
        .config-form select:focus {
            outline: none;
            border-color: #667eea;
        }

        .config-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #f0f0f0;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5a6fd8;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #4a5568;
        }

        .btn-secondary:hover {
            background: #cbd5e0;
        }

        /* Syntax highlighting for code output */
        .code-output .keyword { color: #ed64a6; }
        .code-output .string { color: #68d391; }
        .code-output .variable { color: #63b3ed; }
        .code-output .method { color: #fbb040; }
        .code-output .comment { color: #718096; }

        /* Responsive */
        @media (max-width: 1200px) {
            .builder-container {
                grid-template-columns: 1fr;
                grid-template-rows: auto 1fr;
                height: auto;
                min-height: 100vh;
            }
            
            .top-row {
                grid-template-columns: 1fr;
                height: auto;
                min-height: auto;
                max-height: none;
            }
            
            .components-panel {
                max-height: 40vh;
                margin-bottom: 20px;
            }
        }
        
        @media (max-width: 768px) {
            .builder-container {
                padding: 10px;
                gap: 15px;
            }
            
            .components-panel {
                max-height: 35vh;
            }
            
            .panel-header {
                padding: 15px 15px 10px 15px;
            }
            
            .panel-content {
                padding: 15px;
            }
        }

        /* Grid Row Styles */
        .row-container {
            border: 2px dashed #e2e8f0;
            border-radius: 8px;
            margin: 10px 0;
            padding: 15px;
            background: #f8f9fa;
        }

        .row-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            color: #4a5568;
            font-weight: 600;
        }

        .row-header i {
            color: #667eea;
        }

        .column-drop-zone {
            min-height: 80px;
            border: 2px dashed #cbd5e0;
            border-radius: 6px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #a0aec0;
            font-size: 0.9rem;
            background: white;
            transition: all 0.3s ease;
            width: 100%; /* Take full width of parent column */
            box-sizing: border-box;
        }

        .column-drop-zone:hover,
        .column-drop-zone.drag-over {
            border-color: #667eea;
            background: #f7fafc;
            color: #4a5568;
        }

        .column-drop-zone small {
            margin-top: 5px;
            color: #718096;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .column {
            padding: 0 8px;
        }

        .sortable-ghost {
            opacity: 0.5;
        }

        /* Grid Layout Styles - Kompakt = Inline nebeneinander */
        .ui.grid.compact-content {
            display: flex !important;
            flex-wrap: wrap !important;
            align-items: center !important;
            gap: 0.5rem !important;
        }
        
        .ui.grid.compact-content .column {
            width: auto !important;
            flex: 0 0 auto !important;
            padding: 0 !important;
        }
        
        .ui.grid.compact-content .field {
            margin-bottom: 0 !important;
            display: inline-block !important;
        }
        
        .ui.grid.compact-content .form-element {
            display: inline-block !important;
            width: auto !important;
            min-width: auto !important;
            margin: 0 !important;
            position: relative !important;
            vertical-align: middle !important;
        }
        
        .ui.grid.compact-content .form-element .button {
            margin: 0 !important;
            white-space: nowrap !important;
        }
        
        /* In der Vorschau auch */
        .preview-content .ui.grid.compact-content {
            display: inline-flex !important;
            gap: 0.5rem !important;
        }
        
        .preview-content .ui.grid.compact-content .column {
            width: auto !important;
            flex: none !important;
        }
        
        .ui.grid.compact-content .column-drop-zone {
            width: 100% !important;
            margin-top: 0.5rem !important;
        }
        
        /* Adjust drag handle and controls for compact view */
        .ui.grid.compact-content .drag-handle {
            width: 20px !important;
            left: -20px !important;
        }
        
        .ui.grid.compact-content .element-controls {
            top: -8px !important;
            right: -8px !important;
        }
        
        /* Ensure proper spacing for compact buttons */
        .ui.grid.compact-content .form-element + .form-element {
            margin-left: 0 !important;
        }
        
        /* Specific element styles in compact mode */
        .ui.grid.compact-content .ui.input {
            width: auto !important;
            min-width: 150px !important;
            max-width: 250px !important;
        }
        
        .ui.grid.compact-content .ui.selection.dropdown {
            min-width: 150px !important;
            max-width: 250px !important;
        }
        
        .ui.grid.compact-content .ui.checkbox {
            margin-right: 1rem !important;
        }
        
        .ui.grid.compact-content textarea {
            min-width: 200px !important;
            max-width: 100% !important;
        }
        
        /* Keep text areas and textareas full width */
        .ui.grid.compact-content .form-element:has(textarea),
        .ui.grid.compact-content .form-element:has(.ui.form .field:has(textarea)) {
            width: 100% !important;
            display: block !important;
        }
        
        /* Header elements should be full width */
        .ui.grid.compact-content .form-element:has(.ui.header),
        .ui.grid.compact-content .form-element:has(.ui.divider),
        .ui.grid.compact-content .form-element:has(p) {
            width: 100% !important;
            display: block !important;
        }
        
        /* Custom width grid responsiveness */
        @media (max-width: 768px) {
            .ui.grid > .column {
                width: 100% !important;
                margin: 0 !important;
            }
        }

        /* Row header inline styling */
        .row-header-inline {
            background: #f8f9fa !important;
            border-bottom: 1px solid #dee2e6 !important;
            margin-bottom: 10px !important;
            padding: 8px 12px !important;
            border-radius: 4px 4px 0 0 !important;
        }
        
        .row-header-inline .header {
            margin: 0 !important;
            color: #495057 !important;
        }
        
        .row-header-inline .icon {
            color: #667eea !important;
        }

        /* Nested grid styling */
        .nested-grid-element {
            border: 2px dashed #e0e0e0;
            background: rgba(102, 126, 234, 0.03);
            padding: 5px;
            margin: 5px 0;
            border-radius: 4px;
        }
        
        .nested-grid-element .row-container {
            margin-top: 5px;
        }
        
        .nested-grid-element .row-header-inline {
            font-size: 0.9em;
            padding: 5px 8px !important;
            background: #fafafa !important;
            border-left: 3px solid #9ca3af;
        }
        
        .nested-grid-element .column-drop-zone {
            min-height: 60px;
            padding: 10px;
            font-size: 0.9em;
        }
        
        /* Inline editing styles */
        .editable-label {
            cursor: text !important;
            position: relative;
            padding: 2px 4px;
            border-radius: 3px;
            transition: background-color 0.2s ease;
        }

        .editable-label:hover {
            background-color: #f0f4ff;
            outline: 1px dashed #667eea;
        }

        .inline-edit-input {
            padding: 2px 4px !important;
            border: 2px solid #667eea !important;
            border-radius: 3px !important;
            background: white !important;
            outline: none !important;
            font-family: inherit !important;
        }

        .inline-edit-input:focus {
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2) !important;
        }

        /* Header Buttons Styling - Modern Beautiful Design */
        .header-buttons {
            display: flex;
            gap: 12px;
            margin-left: auto;
            align-items: center;
        }

        .header-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border: none;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            backdrop-filter: blur(10px);
        }

        .header-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .header-btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .header-btn::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 12px;
            padding: 2px;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .header-btn:hover::after {
            opacity: 1;
        }

        .header-btn span {
            position: relative;
            z-index: 1;
            font-size: 11px;
            font-weight: 600;
        }

        .header-btn i {
            position: relative;
            z-index: 1;
            font-size: 16px;
            transition: transform 0.3s;
        }

        .header-btn:hover i {
            transform: scale(1.1) rotate(5deg);
        }

        /* Config Button - Elegant Gray */
        .header-btn.config-btn {
            background: linear-gradient(135deg, #718096 0%, #4a5568 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(71, 80, 96, 0.3), inset 0 1px 0 rgba(255,255,255,0.2);
        }

        .header-btn.config-btn:hover {
            background: linear-gradient(135deg, #5a6578 0%, #3d4452 100%);
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 6px 20px rgba(71, 80, 96, 0.4), inset 0 1px 0 rgba(255,255,255,0.3);
        }

        /* Save Button - Success Green */
        .header-btn.save-btn {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3), inset 0 1px 0 rgba(255,255,255,0.2);
        }

        .header-btn.save-btn:hover {
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 6px 20px rgba(72, 187, 120, 0.4), inset 0 1px 0 rgba(255,255,255,0.3);
        }

        /* Load Button - Info Blue */
        .header-btn.load-btn {
            background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(66, 153, 225, 0.3), inset 0 1px 0 rgba(255,255,255,0.2);
        }

        .header-btn.load-btn:hover {
            background: linear-gradient(135deg, #3182ce 0%, #2c5282 100%);
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 6px 20px rgba(66, 153, 225, 0.4), inset 0 1px 0 rgba(255,255,255,0.3);
        }

        /* Preview Button - Premium Purple */
        .header-btn.preview-btn {
            background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(159, 122, 234, 0.3), inset 0 1px 0 rgba(255,255,255,0.2);
        }

        .header-btn.preview-btn:hover {
            background: linear-gradient(135deg, #805ad5 0%, #6b46c1 100%);
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 6px 20px rgba(159, 122, 234, 0.4), inset 0 1px 0 rgba(255,255,255,0.3);
        }

        /* Clear Button - Danger Red with caution */
        .header-btn.clear-btn {
            background: linear-gradient(135deg, #fc8181 0%, #f56565 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(252, 129, 129, 0.3), inset 0 1px 0 rgba(255,255,255,0.2);
        }

        .header-btn.clear-btn:hover {
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 6px 20px rgba(252, 129, 129, 0.4), inset 0 1px 0 rgba(255,255,255,0.3);
            animation: shake 0.5s;
        }

        @keyframes shake {
            0%, 100% { transform: translateY(-2px) scale(1.02) translateX(0); }
            25% { transform: translateY(-2px) scale(1.02) translateX(-2px); }
            75% { transform: translateY(-2px) scale(1.02) translateX(2px); }
        }

        /* Active state for all buttons */
        .header-btn:active {
            transform: translateY(0) scale(0.98);
            transition: transform 0.1s;
        }

        /* Pulse animation on hover for some buttons */
        @keyframes pulse {
            0% { box-shadow: 0 4px 15px rgba(159, 122, 234, 0.3), inset 0 1px 0 rgba(255,255,255,0.2); }
            50% { box-shadow: 0 4px 25px rgba(159, 122, 234, 0.5), inset 0 1px 0 rgba(255,255,255,0.3); }
            100% { box-shadow: 0 4px 15px rgba(159, 122, 234, 0.3), inset 0 1px 0 rgba(255,255,255,0.2); }
        }

        .header-btn.preview-btn:hover {
            animation: pulse 2s infinite;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .header-buttons {
                gap: 8px;
            }
            
            .header-btn {
                padding: 8px 12px;
            }
            
            .header-btn span {
                display: none;
            }
            
            .header-btn i {
                font-size: 18px;
            }
        }

        /* Beautiful form buttons - compact */
        .ui.button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(102, 126, 234, 0.15);
            border: none;
            position: relative;
            overflow: hidden;
            font-size: 13px;
        }
        
        .ui.button:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 6px rgba(102, 126, 234, 0.2);
        }
        
        .ui.button:active {
            transform: translateY(0);
            box-shadow: 0 1px 3px rgba(102, 126, 234, 0.15);
        }
        
        .ui.secondary.button {
            background: linear-gradient(135deg, #a8a8a8 0%, #6c757d 100%);
        }
        
        .ui.positive.button {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        }
        
        .ui.negative.button {
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
        }

        /* Component panel item improvements */
        .component-item {
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            border: 1px solid #e2e8f0;
        }
        
        .component-item:hover {
            background: linear-gradient(135deg, #edf2f7 0%, #e2e8f0 100%);
            border-color: #667eea;
            transform: translateX(4px);
        }
        
        .component-item i {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="builder-container">
        <!-- Top Row: Components and Form Builder -->
        <div class="top-row">
            <!-- Components Panel -->
            <div class="builder-panel components-panel">
                <div class="panel-header" style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="puzzle piece icon"></i>
                        <h2>Komponenten</h2>
                    </div>
                    <a href="index.php" style="color: #667eea; text-decoration: none; font-size: 14px; display: flex; align-items: center; gap: 5px; padding: 5px 10px; border-radius: 5px; transition: background 0.2s;">
                        <i class="arrow left icon"></i> Zurück zur Startseite
                    </a>
                </div>
                
                <div class="panel-content">
                    <div class="component-group">
                <h3>Eingabefelder</h3>
                <div class="component-item" draggable="true" data-type="text">
                    <i class="font icon"></i>
                    <span>Textfeld</span>
                </div>
                <div class="component-item" draggable="true" data-type="email">
                    <i class="mail icon"></i>
                    <span>E-Mail</span>
                </div>
                <div class="component-item" draggable="true" data-type="password">
                    <i class="lock icon"></i>
                    <span>Passwort</span>
                </div>
                <div class="component-item" draggable="true" data-type="tel">
                    <i class="phone icon"></i>
                    <span>Telefon</span>
                </div>
                <div class="component-item" draggable="true" data-type="number">
                    <i class="hashtag icon"></i>
                    <span>Zahl</span>
                </div>
                <div class="component-item" draggable="true" data-type="url">
                    <i class="linkify icon"></i>
                    <span>URL</span>
                </div>
                <div class="component-item" draggable="true" data-type="date">
                    <i class="calendar icon"></i>
                    <span>Datum</span>
                </div>
                <div class="component-item" draggable="true" data-type="textarea">
                    <i class="align left icon"></i>
                    <span>Textarea</span>
                </div>
            </div>

            <div class="component-group">
                <h3>Auswahl</h3>
                <div class="component-item" draggable="true" data-type="select">
                    <i class="dropdown icon"></i>
                    <span>Dropdown</span>
                </div>
                <div class="component-item" draggable="true" data-type="checkbox">
                    <i class="check square icon"></i>
                    <span>Checkbox</span>
                </div>
                <div class="component-item" draggable="true" data-type="radio">
                    <i class="dot circle icon"></i>
                    <span>Radio Button</span>
                </div>
                <div class="component-item" draggable="true" data-type="range">
                    <i class="sliders horizontal icon"></i>
                    <span>Range Slider</span>
                </div>
            </div>

            <div class="component-group">
                <h3>Layout</h3>
                <div class="component-item" draggable="true" data-type="heading">
                    <i class="heading icon"></i>
                    <span>Überschrift</span>
                </div>
                <div class="component-item" draggable="true" data-type="divider">
                    <i class="minus icon"></i>
                    <span>Trennlinie</span>
                </div>
                <div class="component-item" draggable="true" data-type="html">
                    <i class="code icon"></i>
                    <span>HTML Content</span>
                </div>
                <div class="component-item" draggable="true" data-type="row">
                    <i class="columns icon"></i>
                    <span>Grid Row</span>
                </div>
            </div>

            <div class="component-group">
                <h3>Aktionen</h3>
                <div class="component-item" draggable="true" data-type="submit">
                    <i class="paper plane icon"></i>
                    <span>Submit Button</span>
                </div>
                <div class="component-item" draggable="true" data-type="reset">
                    <i class="undo icon"></i>
                    <span>Reset Button</span>
                </div>
            </div>
                </div> <!-- Close panel-content -->
            </div> <!-- Close components-panel -->

            <!-- Form Builder Area -->
            <div class="builder-panel builder-area">
                <div class="panel-header">
                    <i class="wpforms icon"></i>
                    <h2>Formular Builder</h2>
                    <div class="header-buttons">
                        <button class="header-btn config-btn" onclick="showFormConfig()" title="Formular konfigurieren">
                            <i class="cog icon"></i>
                            <span>Konfig</span>
                        </button>
                        <button class="header-btn save-btn" onclick="saveTemplate()" title="Vorlage speichern">
                            <i class="save icon"></i>
                            <span>Speichern</span>
                        </button>
                        <button class="header-btn load-btn" onclick="loadTemplate()" title="Vorlage laden">
                            <i class="folder open icon"></i>
                            <span>Laden</span>
                        </button>
                        <button class="header-btn preview-btn" onclick="previewForm()" title="Formular-Vorschau">
                            <i class="eye icon"></i>
                            <span>Vorschau</span>
                        </button>
                        <button class="header-btn clear-btn" onclick="clearForm()" title="Alle Felder löschen">
                            <i class="trash alternate outline icon"></i>
                            <span>Leeren</span>
                        </button>
                    </div>
                </div> <!-- Close panel-header -->

                <div class="panel-content">
                    <div class="drop-zone" id="dropZone">
                        <div class="drop-zone-placeholder">
                            <i class="plus icon"></i>
                            <h3>Komponenten hier hineinziehen</h3>
                            <p>Ziehen Sie Felder aus der linken Sidebar in diesen Bereich</p>
                        </div>
                    </div>
                </div> <!-- Close panel-content -->
            </div> <!-- Close builder-area -->
        </div> <!-- Close top-row -->

        <!-- Code Output Panel -->
        <div class="builder-panel code-panel">
            <div class="panel-header">
                <i class="code icon"></i>
                <h2>Generierter Code</h2>
            </div>

            <div class="panel-content">
                <div class="code-tabs">
                <button class="code-tab active" data-tab="php">PHP Code</button>
                <button class="code-tab" data-tab="html">HTML</button>
                <button class="code-tab" data-tab="json">JSON Config</button>
            </div>

            <div class="code-output" id="codeOutput">
                <button class="copy-btn" onclick="copyCode()">Kopieren</button>
                <pre id="codeContent"><?php echo htmlspecialchars('// Ziehen Sie Komponenten in den Builder
// Der generierte Code erscheint hier

use EasyForm\\EasyForm;

$form = new EasyForm(\'my_form\', [
    \'width\' => 600
]);

// Ihre Felder werden hier erscheinen...

$form->display();'); ?></pre>
                </div>
            </div> <!-- Close panel-content -->
        </div> <!-- Close code-panel -->
    </div> <!-- Close builder-container -->

    <!-- Field Configuration Modal -->
    <div class="config-overlay" id="configOverlay">
        <div class="config-modal">
            <div class="config-header">
                <i class="cog icon"></i>
                <h3 id="configTitle">Feld konfigurieren</h3>
            </div>
            
            <form class="config-form" id="configForm">
                <!-- Dynamic content will be inserted here -->
            </form>
            
            <div class="config-actions">
                <button type="button" class="btn btn-secondary" onclick="closeConfig()">Abbrechen</button>
                <button type="button" class="btn btn-primary" onclick="saveConfig()">Speichern</button>
            </div>
        </div>
    </div>

    <script src="jquery/jquery.min.js"></script>
    <script src="semantic/dist/semantic.min.js"></script>
    <script src="js/sortable.min.js"></script>
    
    <script>
        // Global variables
        let formElements = [];
        let currentEditingElement = null;
        let elementCounter = 0;
        
        // Form Configuration
        let formConfig = {
            id: 'my_form',
            theme: 'semantic',
            size: 'medium',
            width: null,
            class: '',
            autocomplete: true,
            showErrors: true,
            liveValidation: true,
            submitButton: true,
            resetButton: false,
            language: 'de',
            method: 'POST',
            action: '',
            ajax: false,
            ajaxUrl: 'process.php'
        };

        // Cleanup function to prevent memory leaks and TypeError
        function cleanupAllSortables() {
            console.log('Cleaning up all sortable instances...');
            
            // Cleanup main drop zone sortable
            if (dropZoneSortable) {
                try {
                    if (typeof dropZoneSortable.destroy === 'function') {
                        dropZoneSortable.destroy();
                    }
                } catch (e) {
                    console.warn('Error destroying drop zone sortable:', e);
                } finally {
                    dropZoneSortable = null;
                    sortableInitialized = false; // Reset initialization flag
                }
            }
            
            // Cleanup column sortables
            $('.column[data-column]').each(function() {
                if (this.sortableInstance) {
                    try {
                        this.sortableInstance.destroy();
                    } catch(e) {
                        console.warn('Error destroying column sortable during cleanup:', e);
                    } finally {
                        this.sortableInstance = null;
                    }
                }
            });
            
            // Remove all delegated event handlers with namespaces
            try {
                $(document).off('dragover.columnDrop dragleave.columnDrop drop.columnDrop');
                $(document).off('click.formBuilder');
                $('.component-item').off('dragstart.formBuilder dragend.formBuilder');
            } catch (e) {
                console.warn('Error removing event handlers:', e);
            }
            
            console.log('Cleanup completed');
        }
        
        // Function to ensure nested grids are properly initialized
        function ensureNestedGridsInitialized() {
            // Check for uninitialized nested grid columns
            const uninitializedColumns = $('.nested-grid-element .column[data-column]').filter(function() {
                return !this.sortableInstance;
            });
            
            if (uninitializedColumns.length > 0) {
                console.log('Found', uninitializedColumns.length, 'uninitialized nested columns, reinitializing...');
                setTimeout(function() {
                    initializeColumnDropZones();
                }, 100);
            }
        }
        
        // Initialize drag and drop
        $(document).ready(function() {
            console.log('Form Builder initializing...');
            
            try {
                // Check if required elements exist
                if (!document.getElementById('dropZone')) {
                    console.error('Drop zone not found!');
                    return;
                }
                
                if (typeof Sortable === 'undefined') {
                    console.error('Sortable.js not loaded!');
                    return;
                }
                
                initializeDragDrop();
                generateCode();
                
                // Periodically check for uninitialized nested grids
                setInterval(ensureNestedGridsInitialized, 1500);
                
                console.log('Form Builder initialized successfully');
            } catch (e) {
                console.error('Error initializing form builder:', e);
            }
        });
        
        // Cleanup when page is about to unload to prevent TypeError
        $(window).on('beforeunload', function() {
            cleanupAllSortables();
        });
        
        // Also cleanup when page becomes hidden (mobile/tab switching)
        if (typeof document.addEventListener === 'function') {
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    cleanupAllSortables();
                }
            });
        }

        let dropZoneSortable = null;
        let sortableInitialized = false;
        let dropHandledByColumn = false; // Flag to prevent double handling

        function initializeDragDrop() {
            // Initialize drop zone sortable (for reordering existing elements)
            initializeDropZoneSortable();
            
            // Initialize native HTML5 drag and drop for adding new elements
            initializeComponentDragDrop();

            // Visual feedback for drag and drop (optional)
            $('.component-item').on('mousedown', function() {
                $(this).addClass('dragging');
            });

            $('.component-item').on('mouseup', function() {
                $(this).removeClass('dragging');
            });
        }

        function reinitializeDragDrop() {
            console.log('Reinitializing drag & drop system...');
            
            try {
                // Clean up existing handlers
                cleanupAllSortables();
                
                // Reset all flags
                sortableInitialized = false;
                dropHandledByColumn = false;
                
                // Reinitialize everything
                setTimeout(function() {
                    initializeDragDrop();
                    console.log('Drag & drop reinitialized successfully');
                }, 100);
                
            } catch (error) {
                console.error('Error reinitializing drag & drop:', error);
            }
        }

        function initializeDropZoneSortable() {
            const dropZone = document.getElementById('dropZone');
            
            if (!dropZone) {
                console.warn('Drop zone not found');
                return;
            }
            
            // Prevent multiple initializations
            if (sortableInitialized && dropZoneSortable) {
                console.log('Sortable already initialized, skipping...');
                return;
            }
            
            // Verify Sortable library is loaded
            if (typeof Sortable === 'undefined') {
                console.error('Sortable.js library not loaded');
                return;
            }
            
            // Destroy existing sortable instance safely with comprehensive cleanup
            if (dropZoneSortable) {
                try {
                    if (typeof dropZoneSortable.destroy === 'function') {
                        dropZoneSortable.destroy();
                    }
                } catch (e) {
                    console.warn('Error destroying sortable:', e);
                    // More aggressive cleanup - remove all Sortable-related data
                    try {
                        dropZone.removeAttribute('data-sortable');
                        // Remove any Sortable-added classes or attributes
                        $(dropZone).removeClass('sortable-initialized sortable-enabled');
                        $(dropZone).find('.sortable-ghost, .sortable-chosen, .sortable-drag').removeClass('sortable-ghost sortable-chosen sortable-drag');
                    } catch (cleanupError) {
                        console.warn('Error during aggressive cleanup:', cleanupError);
                    }
                } finally {
                    dropZoneSortable = null;
                    sortableInitialized = false;
                }
            }
            
            // Create new sortable instance only if not already initialized
            if (!sortableInitialized) {
                try {
                    dropZoneSortable = new Sortable(dropZone, {
                        group: 'form-elements', // Same group as columns to allow cross-movement
                        animation: 150,
                        ghostClass: 'sortable-ghost',
                        handle: '.drag-handle',
                        disabled: false,
                        // Prevent interference with native HTML5 drag & drop
                        dataIdAttr: 'data-sortable-id',
                        // Add more robust event handlers
                        onStart: function(evt) {
                            console.log('Main area drag started:', evt.oldIndex);
                            // Mark this as an internal reorder, not a new element addition
                            if (evt.item) {
                                evt.item.setAttribute('data-sorting', 'true');
                            }
                        },
                        onEnd: function(evt) {
                            // Element was reordered within main area
                            try {
                                if (evt.item) {
                                    evt.item.removeAttribute('data-sorting');
                                }
                                if (evt && typeof evt.oldIndex === 'number' && typeof evt.newIndex === 'number' && evt.to === evt.from) {
                                    console.log('Main area reorder:', evt.oldIndex, '->', evt.newIndex);
                                    reorderElements(evt.oldIndex, evt.newIndex);
                                }
                            } catch (e) {
                                console.error('Error during main area reorder:', e);
                            }
                        },
                        onAdd: function(evt) {
                            // Element was moved TO main area FROM a column
                            const element = evt.item;
                            const elementId = element.dataset.id;
                            const newIndex = evt.newIndex;
                            
                            console.log('Moving element FROM column TO main area at position', newIndex);
                            moveElementToMainArea(elementId, newIndex);
                        },
                        onRemove: function(evt) {
                            // Element was moved FROM main area TO a column
                            console.log('Element removed from main area');
                            // Will be handled by the column's onAdd
                        },
                        // Prevent errors by ensuring elements exist
                        onMove: function(evt) {
                            return evt.related && evt.related.parentNode;
                        }
                    });
                    sortableInitialized = true;
                    console.log('Drop zone sortable initialized successfully');
                } catch (e) {
                    console.error('Error creating sortable:', e);
                    dropZoneSortable = null;
                    sortableInitialized = false;
                }
            }
        }

        function initializeComponentDragDrop() {
            // Clean up existing handlers first
            $('.component-item').off('dragstart.formBuilder');
            $('#dropZone').off('dragover.formBuilder dragleave.formBuilder drop.formBuilder');
            
            // Component drag events
            $('.component-item').on('dragstart.formBuilder', function(e) {
                const type = $(this).data('type');
                if (e.originalEvent && e.originalEvent.dataTransfer) {
                    e.originalEvent.dataTransfer.setData('text/plain', type);
                    e.originalEvent.dataTransfer.setData('application/x-drag-source', 'component-panel');
                    e.originalEvent.dataTransfer.effectAllowed = 'copy';
                    console.log('Drag started from component panel for type:', type);
                }
            });

            // Drop zone events for adding new elements (main drop zone)
            const dropZone = $('#dropZone');
            
            if (dropZone.length > 0) {
                dropZone.on('dragover.formBuilder', function(e) {
                    // Don't show drop zone feedback for internal reordering
                    const target = e.target;
                    if (target && (target.closest('.form-element[data-sorting="true"]') || target.closest('.drag-handle'))) {
                        return;
                    }
                    
                    e.preventDefault();
                    $(this).addClass('drag-over');
                });

                dropZone.on('dragleave.formBuilder', function(e) {
                    if (e.currentTarget && e.relatedTarget && !e.currentTarget.contains(e.relatedTarget)) {
                        $(this).removeClass('drag-over');
                    }
                });

                dropZone.on('drop.formBuilder', function(e) {
                    e.preventDefault();
                    $(this).removeClass('drag-over');
                    
                    // Don't handle if already handled by column drop
                    if (dropHandledByColumn) {
                        console.log('Drop already handled by column, ignoring main drop');
                        return;
                    }
                    
                    if (e.originalEvent && e.originalEvent.dataTransfer) {
                        const type = e.originalEvent.dataTransfer.getData('text/plain');
                        const dragSource = e.originalEvent.dataTransfer.getData('application/x-drag-source');
                        
                        // Only add new element if the drag came from component panel
                        if (type && dragSource === 'component-panel') {
                            // Check if the target is actually the main drop zone, not a nested element
                            if (e.target === this || $(e.target).closest('.column-drop-zone').length === 0) {
                                console.log('Adding new element from component panel to main area:', type);
                                addElement(type);
                            } else {
                                console.log('Drop target is nested element, ignoring main drop');
                            }
                        } else {
                            console.log('Ignoring drop - internal reorder or invalid source');
                        }
                    }
                });
            }

            // Initialize column drop zones
            initializeColumnDropZones();
        }

        function initializeColumnDropZones() {
            console.log('Initializing all column drop zones (including nested)...');
            
            // Clean up existing event handlers first
            $(document).off('dragover.columnDrop dragleave.columnDrop drop.columnDrop', '.column-drop-zone');
            
            // Destroy ALL existing column sortables (including nested ones) more thoroughly
            $('.column[data-column]').each(function() {
                if (this.sortableInstance) {
                    try {
                        this.sortableInstance.destroy();
                    } catch(e) {
                        console.warn('Error destroying existing column sortable:', e);
                    }
                    this.sortableInstance = null;
                }
                // Also remove any Sortable attributes
                $(this).removeAttr('data-sortable');
            });
            
            // Make each grid column sortable (including nested grids)
            $('.column[data-column]').each(function() {
                const columnElement = this;
                const columnIndex = parseInt($(columnElement).data('column'));
                const rowContainer = $(columnElement).closest('.row-container');
                const rowId = rowContainer.data('row-id');
                
                // Check if this is a nested grid
                const isNested = rowContainer.closest('.column[data-column]').length > 0;
                
                console.log('Initializing column:', columnIndex, 'in row:', rowId, 'Nested:', isNested);
                
                // Create sortable for the entire column (including existing elements)
                try {
                    columnElement.sortableInstance = new Sortable(columnElement, {
                        group: 'form-elements', // Allow moving between main area and columns
                        animation: 150,
                        handle: '.drag-handle',
                        ghostClass: 'sortable-ghost',
                        filter: '.column-drop-zone', // Don't drag the drop zone itself
                        onStart: function(evt) {
                            console.log('Column drag started for element:', evt.item.dataset.id);
                        },
                        onAdd: function(evt) {
                            // Element was moved TO this column
                            const element = evt.item;
                            const elementId = element.dataset.id;
                            
                            console.log('Moving element', elementId, 'to column', columnIndex, 'of row', rowId, 'Nested:', isNested);
                            moveElementToColumn(elementId, rowId, columnIndex);
                        },
                        onRemove: function(evt) {
                            // Element was moved FROM this column
                            console.log('Element removed from column');
                            // Will be handled by the destination's onAdd
                        },
                        onUpdate: function(evt) {
                            // Element was reordered within the same column
                            console.log('Element reordered within column');
                            // Could add column-internal reordering logic here if needed
                        }
                    });
                } catch(e) {
                    console.error('Error creating column sortable:', e);
                }
            });
            
            // Column drop zone events for NEW elements from component panel
            $(document).on('dragover.columnDrop', '.column-drop-zone', function(e) {
                // Only show drop feedback for new elements from component panel
                const dragSource = e.originalEvent?.dataTransfer?.getData('application/x-drag-source');
                if (dragSource === 'component-panel') {
                    e.preventDefault();
                    e.stopPropagation();
                    $(this).addClass('drag-over');
                }
            });

            $(document).on('dragleave.columnDrop', '.column-drop-zone', function(e) {
                if (!e.currentTarget.contains(e.relatedTarget)) {
                    $(this).removeClass('drag-over');
                }
            });

            // Use global flag to prevent double handling
            
            $(document).on('drop.columnDrop', '.column-drop-zone', function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation(); // Prevent other drop handlers
                $(this).removeClass('drag-over');
                
                const type = e.originalEvent.dataTransfer.getData('text/plain');
                const dragSource = e.originalEvent.dataTransfer.getData('application/x-drag-source');
                const columnIndex = parseInt($(this).data('column'));
                const rowElement = $(this).closest('.row-container');
                const rowId = rowElement.data('row-id');
                
                // Only add NEW element if it comes from the component panel
                if (type && dragSource === 'component-panel' && !isNaN(columnIndex) && rowId) {
                    console.log('Adding NEW element from component panel:', type, 'to column:', columnIndex, 'of row:', rowId);
                    
                    // Set flag to prevent main drop zone from also handling it
                    dropHandledByColumn = true;
                    
                    addElementToColumn(type, rowId, columnIndex);
                    
                    // Reset flag after a brief delay
                    setTimeout(() => {
                        dropHandledByColumn = false;
                    }, 50);
                    
                    return false;
                } else {
                    console.log('Ignoring column drop - not from component panel or invalid data');
                }
            });
        }

        function addElement(type) {
            console.log('Adding element of type:', type);
            const element = createFormElement(type);
            console.log('Created element:', element);
            formElements.push(element);
            renderFormBuilder();
            generateCode();
        }
        
        function findNestedElement(elementId) {
            // Recursive function to find an element in nested structures
            function searchInElement(element, depth = 0) {
                if (depth > 10) {
                    console.warn('Max depth reached while searching for element');
                    return null;
                }
                
                if (element.id === elementId) {
                    return element;
                }
                
                if (element.type === 'row' && element.children) {
                    for (let col of element.children) {
                        if (col && Array.isArray(col)) {
                            for (let child of col) {
                                if (child) {
                                    const found = searchInElement(child, depth + 1);
                                    if (found) return found;
                                }
                            }
                        }
                    }
                }
                
                return null;
            }
            
            // Search in all top-level elements
            for (let element of formElements) {
                const found = searchInElement(element, 0);
                if (found) return found;
            }
            
            return null;
        }

        function addElementToColumn(type, rowId, columnIndex) {
            console.log('Adding NEW element to column:', type, rowId, columnIndex);
            
            // Find the row element (could be nested)
            let rowElement = formElements.find(el => el.id === rowId);
            
            // If not found in main array, search in nested structures
            if (!rowElement) {
                rowElement = findNestedElement(rowId);
            }
            
            if (!rowElement) {
                console.error('Row element not found:', rowId);
                return;
            }
            
            // Initialize children array if not exists
            if (!rowElement.children) {
                rowElement.children = [];
            }
            
            // Initialize column array if not exists
            if (!rowElement.children[columnIndex]) {
                rowElement.children[columnIndex] = [];
            }
            
            // Create new element
            const element = createFormElement(type);
            console.log('Created NEW element for column:', element);
            
            // Add to column
            rowElement.children[columnIndex].push(element);
            
            // Re-render and generate code
            renderFormBuilder();
            generateCode();
            
            // If the new element is a grid, we need to reinitialize drop zones for its columns
            if (type === 'row') {
                setTimeout(function() {
                    console.log('New grid added to column, reinitializing nested drop zones...');
                    initializeColumnDropZones();
                }, 300);
            }
        }

        function moveElementToColumn(elementId, rowId, columnIndex) {
            console.log('Moving EXISTING element to column:', elementId, rowId, columnIndex);
            
            // Find the row element
            const rowElement = formElements.find(el => el.id === rowId);
            if (!rowElement) {
                console.error('Row element not found:', rowId);
                return;
            }
            
            // Initialize children array if not exists
            if (!rowElement.children) {
                rowElement.children = [];
            }
            
            // Initialize column array if not exists  
            if (!rowElement.children[columnIndex]) {
                rowElement.children[columnIndex] = [];
            }
            
            // Remove element from main formElements array or from another column
            let movedElement = null;
            
            // Check if element is in main array
            const mainIndex = formElements.findIndex(el => el.id === elementId);
            if (mainIndex !== -1) {
                movedElement = formElements.splice(mainIndex, 1)[0];
                console.log('Moved element from main array:', movedElement);
            } else {
                // Element might be in another column, find it
                for (let row of formElements) {
                    if (row.type === 'row' && row.children) {
                        for (let colIndex = 0; colIndex < row.children.length; colIndex++) {
                            const colElements = row.children[colIndex] || [];
                            const elIndex = colElements.findIndex(el => el.id === elementId);
                            if (elIndex !== -1) {
                                movedElement = colElements.splice(elIndex, 1)[0];
                                console.log('Moved element from column:', movedElement);
                                break;
                            }
                        }
                        if (movedElement) break;
                    }
                }
            }
            
            if (movedElement) {
                // Add to target column
                rowElement.children[columnIndex].push(movedElement);
                console.log('Element moved to column successfully');
                
                // Re-render and generate code
                renderFormBuilder();
                generateCode();
                
                // If the moved element is a grid, reinitialize its drop zones
                if (movedElement.type === 'row') {
                    setTimeout(function() {
                        console.log('Grid moved to column, reinitializing nested drop zones...');
                        initializeColumnDropZones();
                    }, 300);
                }
            } else {
                console.error('Could not find element to move:', elementId);
            }
        }

        function moveElementToMainArea(elementId, newIndex) {
            console.log('Moving element FROM column TO main area:', elementId, newIndex);
            
            let movedElement = null;
            
            // Find element in columns
            for (let row of formElements) {
                if (row.type === 'row' && row.children) {
                    for (let colIndex = 0; colIndex < row.children.length; colIndex++) {
                        const colElements = row.children[colIndex] || [];
                        const elIndex = colElements.findIndex(el => el.id === elementId);
                        if (elIndex !== -1) {
                            movedElement = colElements.splice(elIndex, 1)[0];
                            console.log('Found element in column, moving to main area:', movedElement);
                            break;
                        }
                    }
                    if (movedElement) break;
                }
            }
            
            if (movedElement) {
                // Insert into main array at specified position
                formElements.splice(newIndex, 0, movedElement);
                console.log('Element moved to main area successfully');
                
                // Re-render and generate code
                renderFormBuilder();
                generateCode();
            } else {
                console.error('Could not find element to move to main area:', elementId);
            }
        }

        function createFormElement(type) {
            elementCounter++;
            const id = `element_${elementCounter}`;
            
            const baseElement = {
                id: id,
                type: type,
                name: `field_${elementCounter}`,
                label: getDefaultLabel(type),
                required: false,
                placeholder: '',
                help: '',
                icon: '',
                class: ''
            };

            // Type-specific defaults
            switch(type) {
                case 'select':
                case 'radio':
                    baseElement.options = {
                        'option1': 'Option 1',
                        'option2': 'Option 2'
                    };
                    break;
                case 'checkbox':
                    baseElement.checked = false;
                    baseElement.toggle = false;
                    break;
                case 'number':
                case 'range':
                    baseElement.min = 0;
                    baseElement.max = 100;
                    baseElement.value = 0;
                    break;
                case 'textarea':
                    baseElement.rows = 4;
                    break;
                case 'heading':
                    baseElement.text = 'Überschrift';
                    baseElement.level = 2;
                    break;
                case 'html':
                    baseElement.content = '<p>HTML Content</p>';
                    break;
                case 'submit':
                    baseElement.text = 'Senden';
                    baseElement.buttonClass = 'primary';
                    break;
                case 'reset':
                    baseElement.text = 'Zurücksetzen';
                    baseElement.buttonClass = 'secondary';
                    break;
                case 'row':
                    baseElement.columns = 2;
                    baseElement.children = [];
                    baseElement.layoutStyle = 'normal';
                    baseElement.columnWidths = '';
                    break;
            }

            return baseElement;
        }

        function getDefaultLabel(type) {
            const labels = {
                'text': 'Textfeld',
                'email': 'E-Mail-Adresse',
                'password': 'Passwort',
                'tel': 'Telefonnummer',
                'number': 'Zahl',
                'url': 'Website',
                'date': 'Datum',
                'textarea': 'Nachricht',
                'select': 'Auswahl',
                'checkbox': 'Checkbox',
                'radio': 'Radio Button',
                'range': 'Slider',
                'heading': 'Überschrift',
                'divider': 'Trennlinie',
                'html': 'HTML Content',
                'submit': 'Submit Button',
                'reset': 'Reset Button',
                'row': 'Grid Row'
            };
            return labels[type] || 'Feld';
        }

        function renderFormBuilder() {
            const dropZone = $('#dropZone');
            
            if (formElements.length === 0) {
                dropZone.html(`
                    <div class="drop-zone-placeholder">
                        <i class="plus icon"></i>
                        <h3>Komponenten hier hineinziehen</h3>
                        <p>Ziehen Sie Felder aus der linken Sidebar in diesen Bereich</p>
                    </div>
                `);
                // Reinitialize sortable for empty drop zone
                initializeDropZoneSortable();
                
                // Reinitialize column drop zones
                initializeColumnDropZones();
                return;
            }

            let html = '';
            formElements.forEach((element, index) => {
                html += renderFormElement(element, index);
            });
            
            dropZone.html(html);
            
            // Only initialize sortable if not already initialized
            if (!sortableInitialized) {
                initializeDropZoneSortable();
            }
            
            // Always reinitialize column drop zones as they may be dynamic
            // Use a slight delay to ensure DOM is updated
            setTimeout(function() {
                console.log('Reinitializing column drop zones after render...');
                console.log('Found columns:', $('.column[data-column]').length);
                console.log('Found nested columns:', $('.column[data-column] .column[data-column]').length);
                initializeColumnDropZones();
            }, 300); // Increased delay to ensure complete DOM rendering including nested grids
            
            // Reinitialize Semantic UI components
            $('.ui.dropdown').dropdown();
            $('.ui.checkbox').checkbox();
            
            // Make labels editable
            setTimeout(function() {
                $('.editable-label').each(function() {
                    const elementId = $(this).data('element-id');
                    makeInlineEditable(this, elementId);
                });
            }, 100);
        }

        function renderFormElement(element, index) {
            const preview = generateElementPreview(element);
            console.log('Rendering element:', element.type, 'Preview:', preview);
            
            return `
                <div class="form-element" data-index="${index}" data-id="${element.id}">
                    <div class="drag-handle">
                        <i class="grip lines icon"></i>
                    </div>
                    <div class="element-controls">
                        <button class="control-btn edit" onclick="editElement(${index})" title="Bearbeiten">
                            <i class="edit icon"></i>
                        </button>
                        <button class="control-btn delete" onclick="deleteElement(${index})" title="Löschen">
                            <i class="trash icon"></i>
                        </button>
                    </div>
                    ${preview}
                </div>
            `;
        }

        function generateElementPreview(element) {
            switch(element.type) {
                case 'text':
                case 'email':
                case 'password':
                case 'tel':
                case 'number':
                case 'url':
                case 'date':
                    return `
                        <div class="ui form">
                            <div class="field ${element.required ? 'required' : ''}">
                                <label class="editable-label" data-element-id="${element.id}">${element.label}</label>
                                <div class="ui ${element.icon ? 'left icon' : ''} input">
                                    ${element.icon ? `<i class="${element.icon} icon"></i>` : ''}
                                    <input type="${element.type}" placeholder="${element.placeholder}" disabled>
                                </div>
                                ${element.help ? `<small>${element.help}</small>` : ''}
                            </div>
                        </div>
                    `;
                case 'textarea':
                    return `
                        <div class="ui form">
                            <div class="field ${element.required ? 'required' : ''}">
                                <label class="editable-label" data-element-id="${element.id}">${element.label}</label>
                                <textarea rows="${element.rows}" placeholder="${element.placeholder}" disabled></textarea>
                                ${element.help ? `<small>${element.help}</small>` : ''}
                            </div>
                        </div>
                    `;
                case 'select':
                    let selectOptions = '';
                    Object.entries(element.options).forEach(([value, label]) => {
                        selectOptions += `<option value="${value}">${label}</option>`;
                    });
                    return `
                        <div class="ui form">
                            <div class="field ${element.required ? 'required' : ''}">
                                <label class="editable-label" data-element-id="${element.id}">${element.label}</label>
                                <select class="ui dropdown" disabled>
                                    <option value="">Bitte wählen...</option>
                                    ${selectOptions}
                                </select>
                                ${element.help ? `<small>${element.help}</small>` : ''}
                            </div>
                        </div>
                    `;
                case 'checkbox':
                    return `
                        <div class="ui form">
                            <div class="field">
                                <div class="ui ${element.toggle ? 'toggle' : ''} checkbox">
                                    <input type="checkbox" ${element.checked ? 'checked' : ''} disabled>
                                    <label class="editable-label" data-element-id="${element.id}">${element.label}</label>
                                </div>
                                ${element.help ? `<small>${element.help}</small>` : ''}
                            </div>
                        </div>
                    `;
                case 'radio':
                    let radioOptions = '';
                    Object.entries(element.options).forEach(([value, label]) => {
                        radioOptions += `
                            <div class="field">
                                <div class="ui radio checkbox">
                                    <input type="radio" name="${element.name}" value="${value}" disabled>
                                    <label>${label}</label>
                                </div>
                            </div>
                        `;
                    });
                    return `
                        <div class="ui form">
                            <div class="field">
                                <label class="editable-label" data-element-id="${element.id}">${element.label}</label>
                                <div class="grouped fields">
                                    ${radioOptions}
                                </div>
                                ${element.help ? `<small>${element.help}</small>` : ''}
                            </div>
                        </div>
                    `;
                case 'range':
                    return `
                        <div class="ui form">
                            <div class="field">
                                <label class="editable-label" data-element-id="${element.id}">${element.label}: ${element.value}</label>
                                <input type="range" min="${element.min}" max="${element.max}" value="${element.value}" disabled>
                                ${element.help ? `<small>${element.help}</small>` : ''}
                            </div>
                        </div>
                    `;
                case 'heading':
                    return `<h${element.level}>${element.text}</h${element.level}>`;
                case 'divider':
                    return `<div class="ui divider"></div>`;
                case 'html':
                    return `<div class="ui segment">${element.content}</div>`;
                case 'submit':
                    return `
                        <button class="ui ${element.buttonClass} button" disabled>
                            ${element.icon ? `<i class="${element.icon} icon"></i>` : ''}
                            ${element.text}
                        </button>
                    `;
                case 'reset':
                    return `
                        <button type="reset" class="ui ${element.buttonClass} button" disabled>
                            ${element.icon ? `<i class="${element.icon} icon"></i>` : ''}
                            ${element.text}
                        </button>
                    `;
                case 'row':
                    let columnsHtml = '';
                    
                    // Determine grid classes based on layout style
                    let gridClasses = 'ui grid';
                    let layoutStyle = element.layoutStyle || 'normal';
                    
                    if (layoutStyle === 'normal' || layoutStyle === 'compact') {
                        gridClasses += ' equal width';  // Both normal and compact use equal width columns
                    }
                    
                    // Add compact class for styling the content, not the grid structure
                    if (layoutStyle === 'compact') {
                        gridClasses += ' compact-content';
                    }
                    
                    // Parse custom column widths
                    let columnWidths = [];
                    if (layoutStyle === 'custom' && element.columnWidths) {
                        columnWidths = element.columnWidths.split(',').map(w => parseInt(w.trim()));
                    }
                    
                    for (let i = 0; i < element.columns; i++) {
                        const columnFields = element.children && element.children[i] ? element.children[i] : [];
                        let columnContent = '';
                        
                        // Determine column class and info for drop zone FIRST
                        let columnClass = 'column';
                        let columnInfo = '';
                        
                        if (layoutStyle === 'custom' && columnWidths[i]) {
                            const widthNames = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 
                                             'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen'];
                            if (columnWidths[i] >= 1 && columnWidths[i] <= 16) {
                                columnClass = `${widthNames[columnWidths[i]]} wide column`;
                                const percentage = Math.round((columnWidths[i] / 16) * 100);
                                columnInfo = ` (${columnWidths[i]}/16 - ${percentage}%)`;
                            }
                        } else if (layoutStyle === 'compact') {
                            columnInfo = ' (Kompakt)';
                        } else {
                            const percentage = Math.round(100 / element.columns);
                            columnInfo = ` (${percentage}%)`;
                        }
                        
                        if (columnFields.length > 0) {
                            columnFields.forEach((field, fieldIndex) => {
                                // Special handling for nested grids
                                if (field.type === 'row') {
                                    // For nested grids, we need to render them specially to maintain functionality
                                    const nestedGridHtml = generateElementPreview(field);
                                    columnContent += `
                                        <div class="form-element nested-grid-element" data-index="${fieldIndex}" data-id="${field.id}">
                                            <div class="drag-handle">
                                                <i class="grip lines icon"></i>
                                            </div>
                                            <div class="element-controls">
                                                <button class="control-btn edit" onclick="editElementInColumn('${element.id}', ${i}, ${fieldIndex})" title="Bearbeiten">
                                                    <i class="edit icon"></i>
                                                </button>
                                                <button class="control-btn delete" onclick="deleteElementFromColumn('${element.id}', ${i}, ${fieldIndex})" title="Löschen">
                                                    <i class="trash icon"></i>
                                                </button>
                                            </div>
                                            ${nestedGridHtml}
                                        </div>
                                    `;
                                } else {
                                    // Regular fields
                                    const fieldPreview = generateElementPreview(field);
                                    columnContent += `
                                        <div class="form-element" data-index="${fieldIndex}" data-id="${field.id}">
                                            <div class="drag-handle">
                                                <i class="grip lines icon"></i>
                                            </div>
                                            <div class="element-controls">
                                                <button class="control-btn edit" onclick="editElementInColumn('${element.id}', ${i}, ${fieldIndex})" title="Bearbeiten">
                                                    <i class="edit icon"></i>
                                                </button>
                                                <button class="control-btn delete" onclick="deleteElementFromColumn('${element.id}', ${i}, ${fieldIndex})" title="Löschen">
                                                    <i class="trash icon"></i>
                                                </button>
                                            </div>
                                            ${fieldPreview}
                                        </div>
                                    `;
                                }
                            });
                            // Add drop zone at the end of column for adding more elements
                            columnContent += `
                                <div class="column-drop-zone" data-column="${i}">
                                    <i class="plus icon"></i>
                                    <p>Weitere Felder hinzufügen</p>
                                    <small>Spalte ${i + 1}${columnInfo}</small>
                                </div>
                            `;
                        } else {
                            columnContent = `
                                <div class="column-drop-zone" data-column="${i}">
                                    <i class="plus icon"></i>
                                    <p>Felder hier hineinziehen</p>
                                    <small>Spalte ${i + 1}${columnInfo}</small>
                                </div>
                            `;
                        }
                        
                        columnsHtml += `
                            <div class="${columnClass}" data-column="${i}">
                                ${columnContent}
                            </div>
                        `;
                    }
                    
                    // Display info about layout style
                    let layoutInfo = '';
                    if (layoutStyle === 'compact') {
                        layoutInfo = ' - Kompakt';
                    } else if (layoutStyle === 'custom') {
                        layoutInfo = ` - Custom (${element.columnWidths})`;
                    }
                    
                    return `
                        <div class="${gridClasses} row-container" data-row-id="${element.id}">
                            <div class="sixteen wide column row-header-inline">
                                <div class="ui small header">
                                    <i class="grid layout icon"></i>
                                    Grid Row (${element.columns} Spalten${layoutInfo})
                                </div>
                            </div>
                            ${columnsHtml}
                        </div>
                    `;
                default:
                    console.log('Unbekannter Feldtyp:', element.type, element);
                    return `<p>Unbekannter Feldtyp: ${element.type}</p>`;
            }
        }

        function editElement(index) {
            currentEditingElement = index;
            const element = formElements[index];
            showConfigModal(element);
        }

        function makeInlineEditable(labelElement, elementId, isColumnElement = false, rowId = null, columnIndex = null, fieldIndex = null) {
            // Prevent double initialization
            if (labelElement.dataset.editable === 'true') return;
            labelElement.dataset.editable = 'true';
            
            labelElement.style.cursor = 'text';
            labelElement.title = 'Klicken zum Bearbeiten';
            
            labelElement.addEventListener('click', function(e) {
                e.stopPropagation();
                
                // Get current text
                const currentText = this.textContent;
                
                // Create input element
                const input = document.createElement('input');
                input.type = 'text';
                input.value = currentText;
                input.className = 'inline-edit-input';
                input.style.width = '100%';
                input.style.fontSize = window.getComputedStyle(this).fontSize;
                input.style.fontWeight = window.getComputedStyle(this).fontWeight;
                
                // Replace label with input
                this.style.display = 'none';
                this.parentNode.insertBefore(input, this);
                input.focus();
                input.select();
                
                // Save on Enter or blur
                const saveEdit = () => {
                    const newValue = input.value.trim();
                    if (newValue && newValue !== currentText) {
                        // Update the element data
                        if (isColumnElement && rowId !== null) {
                            const rowElement = formElements.find(el => el.id === rowId);
                            if (rowElement && rowElement.children[columnIndex] && rowElement.children[columnIndex][fieldIndex]) {
                                rowElement.children[columnIndex][fieldIndex].label = newValue;
                            }
                        } else {
                            const element = formElements.find(el => el.id === elementId);
                            if (element) {
                                element.label = newValue;
                            }
                        }
                        
                        // Update display
                        labelElement.textContent = newValue;
                        
                        // Regenerate code
                        generateCode();
                    } else {
                        labelElement.textContent = currentText;
                    }
                    
                    // Remove input and show label
                    input.remove();
                    labelElement.style.display = '';
                };
                
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        saveEdit();
                    } else if (e.key === 'Escape') {
                        e.preventDefault();
                        labelElement.textContent = currentText;
                        input.remove();
                        labelElement.style.display = '';
                    }
                });
                
                input.addEventListener('blur', saveEdit);
            });
        }

        function deleteElement(index) {
            if (confirm('Möchten Sie dieses Element wirklich löschen?')) {
                formElements.splice(index, 1);
                renderFormBuilder();
                generateCode();
            }
        }

        function editElementInColumn(rowId, columnIndex, fieldIndex) {
            const rowElement = formElements.find(el => el.id === rowId);
            if (rowElement && rowElement.children && rowElement.children[columnIndex] && rowElement.children[columnIndex][fieldIndex]) {
                const element = rowElement.children[columnIndex][fieldIndex];
                currentEditingElement = {type: 'column', rowId, columnIndex, fieldIndex};
                showConfigModal(element);
            }
        }

        function deleteElementFromColumn(rowId, columnIndex, fieldIndex) {
            if (confirm('Möchten Sie dieses Element wirklich löschen?')) {
                const rowElement = formElements.find(el => el.id === rowId);
                if (rowElement && rowElement.children && rowElement.children[columnIndex]) {
                    rowElement.children[columnIndex].splice(fieldIndex, 1);
                    renderFormBuilder();
                    generateCode();
                }
            }
        }

        function showConfigModal(element) {
            const form = $('#configForm');
            const title = $('#configTitle');
            
            title.text(`${getDefaultLabel(element.type)} konfigurieren`);
            
            let formHTML = generateConfigForm(element);
            form.html(formHTML);
            
            // Initialize Semantic UI dropdowns
            setTimeout(() => {
                $('.ui.dropdown').dropdown();
            }, 100);
            
            $('#configOverlay').css('display', 'flex');
        }

        function generateConfigForm(element) {
            let html = `
                <div class="field">
                    <label>Name</label>
                    <input type="text" name="name" value="${element.name}" placeholder="Feldname für PHP">
                </div>
            `;

            if (['text', 'email', 'password', 'tel', 'number', 'url', 'date', 'textarea', 'select', 'checkbox', 'radio', 'range'].includes(element.type)) {
                html += `
                    <div class="field">
                        <label>Label</label>
                        <input type="text" name="label" value="${element.label}" placeholder="Angezeigter Text">
                    </div>
                    <div class="field">
                        <label>Platzhalter</label>
                        <input type="text" name="placeholder" value="${element.placeholder}" placeholder="Platzhalter Text">
                    </div>
                    <div class="field">
                        <label>Hilfetext</label>
                        <input type="text" name="help" value="${element.help}" placeholder="Kleiner Hilfetext unter dem Feld">
                    </div>
                    <div class="field">
                        <label>Icon (Semantic UI)</label>
                        <input type="text" name="icon" value="${element.icon}" placeholder="z.B. user, mail, phone">
                    </div>
                    <div class="field">
                        <div class="ui checkbox">
                            <input type="checkbox" name="required" ${element.required ? 'checked' : ''}>
                            <label>Pflichtfeld</label>
                        </div>
                    </div>
                `;
            }

            // Type-specific fields
            switch(element.type) {
                case 'select':
                case 'radio':
                    html += `
                        <div class="field">
                            <label>Optionen (eine pro Zeile: wert|Label)</label>
                            <textarea name="options" rows="4" placeholder="option1|Option 1\noption2|Option 2">${Object.entries(element.options).map(([k,v]) => k+'|'+v).join('\n')}</textarea>
                        </div>
                    `;
                    break;
                case 'checkbox':
                    html += `
                        <div class="field">
                            <div class="ui checkbox">
                                <input type="checkbox" name="checked" ${element.checked ? 'checked' : ''}>
                                <label>Standard-aktiviert</label>
                            </div>
                        </div>
                        <div class="field">
                            <div class="ui checkbox">
                                <input type="checkbox" name="toggle" ${element.toggle ? 'checked' : ''}>
                                <label>Als Toggle-Switch anzeigen</label>
                            </div>
                        </div>
                    `;
                    break;
                case 'number':
                case 'range':
                    html += `
                        <div class="field">
                            <label>Minimum</label>
                            <input type="number" name="min" value="${element.min}">
                        </div>
                        <div class="field">
                            <label>Maximum</label>
                            <input type="number" name="max" value="${element.max}">
                        </div>
                        <div class="field">
                            <label>Standardwert</label>
                            <input type="number" name="value" value="${element.value}">
                        </div>
                    `;
                    break;
                case 'textarea':
                    html += `
                        <div class="field">
                            <label>Zeilen</label>
                            <input type="number" name="rows" value="${element.rows}" min="2" max="20">
                        </div>
                    `;
                    break;
                case 'heading':
                    html = `
                        <div class="field">
                            <label>Text</label>
                            <input type="text" name="text" value="${element.text}">
                        </div>
                        <div class="field">
                            <label>Level</label>
                            <select name="level" class="ui dropdown">
                                <option value="1" ${element.level == 1 ? 'selected' : ''}>H1</option>
                                <option value="2" ${element.level == 2 ? 'selected' : ''}>H2</option>
                                <option value="3" ${element.level == 3 ? 'selected' : ''}>H3</option>
                                <option value="4" ${element.level == 4 ? 'selected' : ''}>H4</option>
                            </select>
                        </div>
                    `;
                    break;
                case 'html':
                    html = `
                        <div class="field">
                            <label>HTML Content</label>
                            <textarea name="content" rows="6">${element.content}</textarea>
                        </div>
                    `;
                    break;
                case 'submit':
                case 'reset':
                    html = `
                        <div class="field">
                            <label>Button Text</label>
                            <input type="text" name="text" value="${element.text}">
                        </div>
                        <div class="field">
                            <label>Icon (optional)</label>
                            <input type="text" name="icon" value="${element.icon}" placeholder="z.B. send, save">
                        </div>
                        <div class="field">
                            <label>Button Style</label>
                            <select name="buttonClass" class="ui dropdown">
                                <option value="primary" ${element.buttonClass === 'primary' ? 'selected' : ''}>Primary</option>
                                <option value="secondary" ${element.buttonClass === 'secondary' ? 'selected' : ''}>Secondary</option>
                                <option value="positive" ${element.buttonClass === 'positive' ? 'selected' : ''}>Positive</option>
                                <option value="negative" ${element.buttonClass === 'negative' ? 'selected' : ''}>Negative</option>
                            </select>
                        </div>
                    `;
                    break;
                case 'row':
                    html = `
                        <div class="field">
                            <label>Anzahl Spalten</label>
                            <input type="number" name="columns" value="${element.columns}" min="1" max="12">
                        </div>
                        <div class="field">
                            <label>Layout Style</label>
                            <select name="layoutStyle" class="ui dropdown">
                                <option value="normal" ${(element.layoutStyle || 'normal') === 'normal' ? 'selected' : ''}>Normal - Vollbreite Felder</option>
                                <option value="compact" ${element.layoutStyle === 'compact' ? 'selected' : ''}>Kompakt - Für Buttons und kurze Felder</option>
                                <option value="custom" ${element.layoutStyle === 'custom' ? 'selected' : ''}>Benutzerdefiniert - Eigene Spaltenbreiten</option>
                            </select>
                        </div>
                        <div class="field" id="customWidthsField" style="display: ${element.layoutStyle === 'custom' ? 'block' : 'none'}">
                            <label>Spaltenbreiten (16 = Vollbreite)</label>
                            <div class="ui info message">
                                <p>Geben Sie die Breite für jede Spalte ein (1-16). Beispiel: "8,4,4" für 50%, 25%, 25%</p>
                            </div>
                            <input type="text" name="columnWidths" value="${element.columnWidths || ''}" placeholder="z.B. 8,4,4 oder 10,3,3">
                        </div>
                        <div class="ui info message">
                            <p><strong>Normal:</strong> Felder nehmen volle Spaltenbreite ein<br>
                            <strong>Kompakt:</strong> Felder werden enger zusammengerückt (gut für Buttons)<br>
                            <strong>Benutzerdefiniert:</strong> Sie bestimmen die exakte Breite jeder Spalte</p>
                        </div>
                    `;
                    break;
            }

            return html;
        }

        function saveConfig() {
            const form = $('#configForm');
            let element;
            
            // Determine if we're editing a main element or column element
            if (typeof currentEditingElement === 'object' && currentEditingElement.type === 'column') {
                const rowElement = formElements.find(el => el.id === currentEditingElement.rowId);
                element = rowElement.children[currentEditingElement.columnIndex][currentEditingElement.fieldIndex];
            } else {
                element = formElements[currentEditingElement];
            }
            
            // Get all form values
            const formData = new FormData(form[0]);
            
            // Update element with form values
            for (let [key, value] of formData.entries()) {
                if (key === 'options') {
                    // Parse options format: key|label
                    const options = {};
                    value.split('\n').forEach(line => {
                        if (line.trim()) {
                            const [k, v] = line.split('|');
                            if (k && v) {
                                options[k.trim()] = v.trim();
                            }
                        }
                    });
                    element.options = options;
                } else if (key === 'required' || key === 'checked' || key === 'toggle') {
                    element[key] = true; // Checkbox values
                } else {
                    element[key] = value;
                }
            }
            
            // Handle unchecked checkboxes
            ['required', 'checked', 'toggle'].forEach(field => {
                if (!formData.has(field)) {
                    element[field] = false;
                }
            });
            
            closeConfig();
            renderFormBuilder();
            generateCode();
        }

        function closeConfig() {
            $('#configOverlay').hide();
            currentEditingElement = null;
        }

        function generateCode() {
            const activeTab = $('.code-tab.active').data('tab');
            
            switch(activeTab) {
                case 'php':
                    generatePHPCode();
                    break;
                case 'html':
                    generateHTMLCode();
                    break;
                case 'json':
                    generateJSONCode();
                    break;
            }
        }

        function generatePHPCode() {
            var phpTemplate = document.getElementById('php-template').innerHTML;
            
            // Decode HTML entities
            phpTemplate = phpTemplate.replace(/&lt;/g, '<').replace(/&gt;/g, '>');
            
            var elementsCode = '';
            
            formElements.forEach(function(element, index) {
                if (index > 0) elementsCode += '\n    ';
                elementsCode += generateElementPHPCode(element);
            });
            
            var hasAjax = hasAjaxCapableFields();
            var ajaxCode = hasAjax ? `->ajax([
        'success' => 'function(response) {
            if(response.success) {
                $("#result").html("<div class=\\"ui success message\\">" + response.message + "</div>");
            }
        }'
    ])` : '';

            var finalCode = phpTemplate
                .replace('{{AJAX_CODE}}', ajaxCode ? '\n    ' + ajaxCode : '')
                .replace('{{ELEMENTS_CODE}}', elementsCode);
            
            // Clean up extra whitespace and format nicely
            finalCode = finalCode
                .replace(/\n{3,}/g, '\n\n')  // Replace 3+ newlines with 2
                .replace(/\n\s*\n\s*\n/g, '\n\n')  // Clean up empty lines with spaces
                .replace(/\s+$/gm, '')  // Remove trailing spaces from each line
                .trim();

            document.getElementById('codeContent').textContent = finalCode;
        }

        function generateElementPHPCode(element) {
            const indent = '    ';
            
            switch(element.type) {
                case 'text':
                case 'email':
                case 'password':
                case 'tel':
                case 'number':
                case 'url':
                case 'date':
                    return generateInputFieldCode(element);
                
                case 'textarea':
                    return generateTextareaCode(element);
                
                case 'select':
                    return generateSelectCode(element);
                
                case 'checkbox':
                    return generateCheckboxCode(element);
                
                case 'radio':
                    return generateRadioCode(element);
                
                case 'range':
                    return generateRangeCode(element);
                
                case 'heading':
                    var q = String.fromCharCode(39);
                    return '$form->heading(' + q + element.text + q + ', ' + element.level + ')';
                
                case 'divider':
                    return '$form->divider()';
                
                case 'html':
                    var q = String.fromCharCode(39);
                    var content = element.content.replace(/'/g, "\\'");
                    return '$form->html(' + q + content + q + ')';
                
                case 'submit':
                    return generateSubmitCode(element);
                
                case 'reset':
                    return generateResetCode(element);
                
                case 'row':
                    return generateRowCode(element);
                
                default:
                    return '// Unknown field type: ' + element.type;
            }
        }

        function generateInputFieldCode(element) {
            var options = [];
            var q = String.fromCharCode(39); // Single quote
            
            if (element.placeholder) options.push(q + 'placeholder' + q + ' => ' + q + element.placeholder + q);
            if (element.required) options.push(q + 'required' + q + ' => true');
            if (element.icon) options.push(q + 'icon' + q + ' => ' + q + element.icon + q);
            if (element.help) options.push(q + 'help' + q + ' => ' + q + element.help + q);
            if (element.min !== undefined && element.type === 'number') options.push(q + 'min' + q + ' => ' + element.min);
            if (element.max !== undefined && element.type === 'number') options.push(q + 'max' + q + ' => ' + element.max);
            if (element.value !== undefined && element.type === 'number') options.push(q + 'value' + q + ' => ' + element.value);
            
            var optionsStr = options.length > 0 ? ', [\n        ' + options.join(',\n        ') + '\n    ]' : '';
            
            return '$form->' + element.type + '(' + q + element.name + q + ', ' + q + element.label + q + optionsStr + ')';
        }

        function generateTextareaCode(element) {
            let options = [];
            
            if (element.placeholder) options.push(`'placeholder' => '${element.placeholder}'`);
            if (element.required) options.push(`'required' => true`);
            if (element.help) options.push(`'help' => '${element.help}'`);
            if (element.rows) options.push(`'rows' => ${element.rows}`);
            
            const optionsStr = options.length > 0 ? ', [\n        ' + options.join(',\n        ') + '\n    ]' : '';
            
            return `$form->textarea('${element.name}', '${element.label}'${optionsStr})`;
        }

        function generateSelectCode(element) {
            let options = [];
            let selectOptions = [];
            
            Object.entries(element.options).forEach(([key, value]) => {
                selectOptions.push(`'${key}' => '${value}'`);
            });
            
            const selectOptionsStr = '[\n        ' + selectOptions.join(',\n        ') + '\n    ]';
            
            if (element.required) options.push(`'required' => true`);
            if (element.help) options.push(`'help' => '${element.help}'`);
            
            const optionsStr = options.length > 0 ? ', [\n        ' + options.join(',\n        ') + '\n    ]' : '';
            
            return `$form->select('${element.name}', '${element.label}', ${selectOptionsStr}${optionsStr})`;
        }

        function generateCheckboxCode(element) {
            let options = [];
            
            if (element.checked) options.push(`'checked' => true`);
            if (element.toggle) options.push(`'toggle' => true`);
            if (element.help) options.push(`'help' => '${element.help}'`);
            
            const optionsStr = options.length > 0 ? ', [\n        ' + options.join(',\n        ') + '\n    ]' : '';
            
            return `$form->checkbox('${element.name}', '${element.label}'${optionsStr})`;
        }

        function generateRadioCode(element) {
            let options = [];
            let radioOptions = [];
            
            Object.entries(element.options).forEach(([key, value]) => {
                radioOptions.push(`'${key}' => '${value}'`);
            });
            
            const radioOptionsStr = '[\n        ' + radioOptions.join(',\n        ') + '\n    ]';
            
            if (element.help) options.push(`'help' => '${element.help}'`);
            
            const optionsStr = options.length > 0 ? ', [\n        ' + options.join(',\n        ') + '\n    ]' : '';
            
            return `$form->radio('${element.name}', '${element.label}', ${radioOptionsStr}${optionsStr})`;
        }

        function generateRangeCode(element) {
            let options = [];
            
            if (element.min !== undefined) options.push(`'min' => ${element.min}`);
            if (element.max !== undefined) options.push(`'max' => ${element.max}`);
            if (element.value !== undefined) options.push(`'value' => ${element.value}`);
            if (element.help) options.push(`'help' => '${element.help}'`);
            
            const optionsStr = options.length > 0 ? ', [\n        ' + options.join(',\n        ') + '\n    ]' : '';
            
            return `$form->range('${element.name}', '${element.label}'${optionsStr})`;
        }

        function generateSubmitCode(element) {
            let options = [];
            
            if (element.icon) options.push(`'icon' => '${element.icon}'`);
            if (element.buttonClass) options.push(`'class' => '${element.buttonClass}'`);
            
            const optionsStr = options.length > 0 ? ', [\n        ' + options.join(',\n        ') + '\n    ]' : '';
            
            return `$form->submit('${element.text}'${optionsStr})`;
        }

        function generateResetCode(element) {
            let options = [];
            
            if (element.icon) options.push(`'icon' => '${element.icon}'`);
            if (element.buttonClass) options.push(`'class' => '${element.buttonClass}'`);
            
            const optionsStr = options.length > 0 ? ', [\n        ' + options.join(',\n        ') + '\n    ]' : '';
            
            return `$form->reset('${element.text}'${optionsStr})`;
        }

        function generateRowCode(element) {
            let options = [];
            
            if (element.columns) options.push(`'columns' => ${element.columns}`);
            
            const optionsStr = options.length > 0 ? '[\n        ' + options.join(',\n        ') + '\n    ]' : '[]';
            
            return `$form->row(${optionsStr})`;
        }

        function hasAjaxCapableFields() {
            return formElements.some(el => ['text', 'email', 'password', 'textarea', 'select'].includes(el.type));
        }

        function generateHTMLCode() {
            var htmlTemplate = document.getElementById('html-template').innerHTML;
            
            // Decode HTML entities
            htmlTemplate = htmlTemplate.replace(/&lt;/g, '<').replace(/&gt;/g, '>');
            
            document.getElementById('codeContent').textContent = htmlTemplate;
        }

        function generateJSONCode() {
            const config = {
                form: {
                    id: 'my_form',
                    width: 600,
                    method: 'POST',
                    action: 'process.php'
                },
                fields: formElements
            };

            $('#codeContent').text(JSON.stringify(config, null, 2));
        }

        function syntaxHighlight(code) {
            return code
                .replace(/(&lt;\?php|&lt;\?)/g, '<span class="keyword">$1</span>')
                .replace(/(\$\w+)/g, '<span class="variable">$1</span>')
                .replace(/(new|use|function|return|if|else|foreach|while|for|class|public|private|protected)/g, '<span class="keyword">$1</span>')
                .replace(/('([^'\\\\]|\\\\.)*')/g, '<span class="string">$1</span>')
                .replace(/(-&gt;\w+)/g, '<span class="method">$1</span>')
                .replace(/(\/\/.*$)/gm, '<span class="comment">$1</span>');
        }

        // Tab switching
        $('.code-tab').on('click', function() {
            $('.code-tab').removeClass('active');
            $(this).addClass('active');
            generateCode();
        });

        // Utility functions
        function clearForm() {
            if (confirm('Möchten Sie wirklich alle Elemente löschen?')) {
                formElements = [];
                renderFormBuilder();
                generateCode();
            }
        }
        
        // Template-Funktionen für Vorlagen speichern/laden
        function saveTemplate() {
            if (formElements.length === 0) {
                alert('Das Formular ist leer. Fügen Sie erst Elemente hinzu.');
                return;
            }
            
            const templateName = prompt('Geben Sie einen Namen für die Vorlage ein:');
            if (!templateName) return;
            
            try {
                // Vorlagen im localStorage speichern
                let templates = JSON.parse(localStorage.getItem('easyform_templates') || '{}');
                
                // Erstelle eine Kopie der formElements für sicheres Speichern
                const elementsToSave = JSON.parse(JSON.stringify(formElements));
                
                templates[templateName] = {
                    name: templateName,
                    date: new Date().toISOString(),
                    elements: elementsToSave // Speichere direkt als Array, nicht als String
                };
                
                localStorage.setItem('easyform_templates', JSON.stringify(templates));
                console.log('Template gespeichert:', templates[templateName]);
                alert(`Vorlage "${templateName}" wurde gespeichert!`);
            } catch (error) {
                console.error('Fehler beim Speichern der Vorlage:', error);
                alert('Fehler beim Speichern der Vorlage.');
            }
        }
        
        // Form Configuration Functions
        function showFormConfig() {
            // Load current config into modal
            $('#config_id').val(formConfig.id);
            $('#config_theme').val(formConfig.theme);
            $('#config_size').val(formConfig.size);
            $('#config_width').val(formConfig.width || '');
            $('#config_class').val(formConfig.class);
            $('#config_language').val(formConfig.language);
            $('#config_autocomplete').prop('checked', formConfig.autocomplete);
            $('#config_showErrors').prop('checked', formConfig.showErrors);
            $('#config_liveValidation').prop('checked', formConfig.liveValidation);
            $('#config_submitButton').prop('checked', formConfig.submitButton);
            $('#config_resetButton').prop('checked', formConfig.resetButton);
            $('#config_method').val(formConfig.method);
            $('#config_action').val(formConfig.action);
            $('#config_ajax').prop('checked', formConfig.ajax);
            $('#config_ajaxUrl').val(formConfig.ajaxUrl);
            
            // Show/hide AJAX URL field
            $('#ajaxUrlField').toggle(formConfig.ajax);
            
            // Show modal
            $('#formConfigModal').modal('show');
        }
        
        function saveFormConfig() {
            // Save configuration from modal
            formConfig.id = $('#config_id').val() || 'my_form';
            formConfig.theme = $('#config_theme').val();
            formConfig.size = $('#config_size').val();
            formConfig.width = $('#config_width').val() ? parseInt($('#config_width').val()) : null;
            formConfig.class = $('#config_class').val();
            formConfig.language = $('#config_language').val();
            formConfig.autocomplete = $('#config_autocomplete').is(':checked');
            formConfig.showErrors = $('#config_showErrors').is(':checked');
            formConfig.liveValidation = $('#config_liveValidation').is(':checked');
            formConfig.submitButton = $('#config_submitButton').is(':checked');
            formConfig.resetButton = $('#config_resetButton').is(':checked');
            formConfig.method = $('#config_method').val();
            formConfig.action = $('#config_action').val();
            formConfig.ajax = $('#config_ajax').is(':checked');
            formConfig.ajaxUrl = $('#config_ajaxUrl').val();
            
            // Update code generation
            generateCode();
            
            // Close modal
            $('#formConfigModal').modal('hide');
            
            // Show success message
            alert('Konfiguration gespeichert!');
        }
        
        function loadTemplate() {
            let templates = JSON.parse(localStorage.getItem('easyform_templates') || '{}');
            
            if (Object.keys(templates).length === 0) {
                alert('Keine gespeicherten Vorlagen vorhanden.');
                return;
            }
            
            // Template-Auswahl Dialog erstellen
            let templateList = 'Wählen Sie eine Vorlage:\n\n';
            let templateNames = Object.keys(templates);
            templateNames.forEach((name, index) => {
                const date = new Date(templates[name].date).toLocaleDateString('de-DE');
                templateList += `${index + 1}. ${name} (${date})\n`;
            });
            
            const choice = prompt(templateList + '\nGeben Sie die Nummer der Vorlage ein:');
            if (!choice) return;
            
            const index = parseInt(choice) - 1;
            if (index >= 0 && index < templateNames.length) {
                const selectedTemplate = templates[templateNames[index]];
                
                if (formElements.length > 0) {
                    if (!confirm('Das aktuelle Formular wird überschrieben. Fortfahren?')) {
                        return;
                    }
                }
                
                try {
                    // Elements ist jetzt direkt ein Array, nicht mehr ein String
                    if (typeof selectedTemplate.elements === 'string') {
                        // Alte Vorlagen (falls vorhanden) - kompatibel bleiben
                        formElements = JSON.parse(selectedTemplate.elements);
                    } else {
                        // Neue Vorlagen
                        formElements = selectedTemplate.elements;
                    }
                    
                    console.log('Geladene Elemente:', formElements);
                    
                    // UI aktualisieren
                    renderFormBuilder();
                    generateCode();
                    previewForm(); // Preview auch aktualisieren
                    
                    alert(`Vorlage "${selectedTemplate.name}" wurde geladen!`);
                } catch (error) {
                    console.error('Fehler beim Laden der Vorlage:', error);
                    alert('Fehler beim Laden der Vorlage. Die Vorlage könnte beschädigt sein.');
                }
            } else {
                alert('Ungültige Auswahl.');
            }
        }

        function generateFullPHPCode() {
            var phpTemplate = document.getElementById('php-template').innerHTML;
            
            // Decode HTML entities
            phpTemplate = phpTemplate.replace(/&lt;/g, '<').replace(/&gt;/g, '>');
            
            var elementsCode = '';
            
            formElements.forEach(function(element, index) {
                if (index > 0) elementsCode += '\n    ';
                elementsCode += generateElementPHPCode(element);
            });
            
            var hasAjax = hasAjaxCapableFields();
            var ajaxCode = hasAjax ? `->ajax([
        'success' => 'function(response) {
            if(response.success) {
                $("#result").html("<div class=\\"ui success message\\">" + response.message + "</div>");
            }
        }'
    ])` : '';

            var finalCode = phpTemplate
                .replace('{{AJAX_CODE}}', ajaxCode)
                .replace('{{ELEMENTS_CODE}}', elementsCode);

            return finalCode;
        }

        function previewForm() {
            try {
                // Create preview HTML
                const previewHTML = `
                    <!DOCTYPE html>
                    <html lang="de">
                    <head>
                        <meta charset="utf-8">
                        <title>Form Preview</title>
                        <link rel="stylesheet" href="semantic/dist/semantic.min.css">
                        <style>
                            body { padding: 40px; background: #f8f9fa; }
                            .container { max-width: 800px; margin: 0 auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <h2>Form Preview</h2>
                            <div id="result"></div>
                            <p><em>Dies ist eine Vorschau. Das Formular ist nicht funktional.</em></p>
                            ${generatePreviewHTML()}
                        </div>
                        
                        <script src="jquery/jquery.min.js"><\/script>
                        <script src="semantic/dist/semantic.min.js"><\/script>
                        <script>
                            $(document).ready(function() {
                                // Initialize Semantic UI components
                                $('.ui.dropdown').dropdown();
                                $('.ui.checkbox').checkbox();
                            });
                        <\/script>
                    </body>
                    </html>
                `;
                
                const previewWindow = window.open('', '_blank', 'width=900,height=700,scrollbars=yes,resizable=yes');
                if (previewWindow) {
                    previewWindow.document.write(previewHTML);
                    previewWindow.document.close();
                    
                    // Ensure focus returns to main window and reinitialize drag & drop
                    setTimeout(function() {
                        window.focus();
                        // Reinitialize drag & drop in case it got disrupted
                        reinitializeDragDrop();
                    }, 200);
                } else {
                    alert('Popup wurde blockiert. Bitte erlauben Sie Popups für diese Seite.');
                }
            } catch (error) {
                console.error('Error opening preview:', error);
                alert('Fehler beim Öffnen der Vorschau');
            }
        }

        function generatePreviewHTML() {
            let html = '<form class="ui form">';
            
            formElements.forEach(element => {
                html += generateCleanPreview(element);
            });
            
            html += '</form>';
            return html;
        }

        function generateCleanPreview(element) {
            // Generate clean form preview without builder elements
            switch(element.type) {
                case 'text':
                case 'email':
                case 'password':
                case 'tel':
                case 'number':
                case 'url':
                case 'date':
                    return `
                        <div class="field ${element.required ? 'required' : ''}">
                            <label>${element.label}</label>
                            <div class="ui ${element.icon ? 'left icon' : ''} input">
                                ${element.icon ? `<i class="${element.icon} icon"></i>` : ''}
                                <input type="${element.type}" placeholder="${element.placeholder || ''}" ${element.required ? 'required' : ''}>
                            </div>
                            ${element.help ? `<small>${element.help}</small>` : ''}
                        </div>
                    `;
                case 'textarea':
                    return `
                        <div class="field ${element.required ? 'required' : ''}">
                            <label>${element.label}</label>
                            <textarea rows="${element.rows || 4}" placeholder="${element.placeholder || ''}" ${element.required ? 'required' : ''}></textarea>
                            ${element.help ? `<small>${element.help}</small>` : ''}
                        </div>
                    `;
                case 'select':
                    let selectOptions = '';
                    Object.entries(element.options || {}).forEach(([key, value]) => {
                        selectOptions += `<option value="${key}">${value}</option>`;
                    });
                    return `
                        <div class="field ${element.required ? 'required' : ''}">
                            <label>${element.label}</label>
                            <select class="ui dropdown" ${element.required ? 'required' : ''}>
                                <option value="">Wählen Sie...</option>
                                ${selectOptions}
                            </select>
                            ${element.help ? `<small>${element.help}</small>` : ''}
                        </div>
                    `;
                case 'checkbox':
                    return `
                        <div class="field">
                            <div class="ui ${element.toggle ? 'toggle' : ''} checkbox">
                                <input type="checkbox" ${element.checked ? 'checked' : ''}>
                                <label>${element.label}</label>
                            </div>
                            ${element.help ? `<small>${element.help}</small>` : ''}
                        </div>
                    `;
                case 'radio':
                    let radioOptions = '';
                    Object.entries(element.options || {}).forEach(([key, value]) => {
                        radioOptions += `
                            <div class="field">
                                <div class="ui radio checkbox">
                                    <input type="radio" name="${element.name}" value="${key}">
                                    <label>${value}</label>
                                </div>
                            </div>
                        `;
                    });
                    return `
                        <div class="field">
                            <label>${element.label}</label>
                            <div class="grouped fields">
                                ${radioOptions}
                            </div>
                            ${element.help ? `<small>${element.help}</small>` : ''}
                        </div>
                    `;
                case 'range':
                    return `
                        <div class="field">
                            <label>${element.label}: <span id="${element.name}_value">${element.value || 0}</span></label>
                            <input type="range" min="${element.min || 0}" max="${element.max || 100}" value="${element.value || 0}" 
                                   onchange="document.getElementById('${element.name}_value').innerText = this.value">
                            ${element.help ? `<small>${element.help}</small>` : ''}
                        </div>
                    `;
                case 'heading':
                    return `<h${element.level || 2}>${element.text || 'Heading'}</h${element.level || 2}>`;
                case 'divider':
                    return `<div class="ui divider"></div>`;
                case 'html':
                    return `<div class="ui segment">${element.content || ''}</div>`;
                case 'submit':
                    return `
                        <button type="submit" class="ui ${element.buttonClass || 'primary'} button">
                            ${element.icon ? `<i class="${element.icon} icon"></i>` : ''}
                            ${element.text || 'Submit'}
                        </button>
                    `;
                case 'reset':
                    return `
                        <button type="reset" class="ui ${element.buttonClass || 'secondary'} button">
                            ${element.icon ? `<i class="${element.icon} icon"></i>` : ''}
                            ${element.text || 'Reset'}
                        </button>
                    `;
                case 'row':
                    let columnsHtml = '';
                    let layoutStyle = element.layoutStyle || 'normal';
                    
                    // Determine grid classes
                    let gridClasses = 'ui grid';
                    if (layoutStyle === 'normal' || layoutStyle === 'compact') {
                        gridClasses += ' equal width';  // Both normal and compact use equal width columns
                    }
                    
                    // Add compact class for styling the content, not the grid structure
                    if (layoutStyle === 'compact') {
                        gridClasses += ' compact-content';
                    }
                    
                    // Parse custom column widths
                    let columnWidths = [];
                    if (layoutStyle === 'custom' && element.columnWidths) {
                        columnWidths = element.columnWidths.split(',').map(w => parseInt(w.trim()));
                    }
                    
                    for (let i = 0; i < (element.columns || 2); i++) {
                        const columnFields = element.children && element.children[i] ? element.children[i] : [];
                        let columnContent = '';
                        
                        columnFields.forEach(field => {
                            columnContent += generateCleanPreview(field);
                        });
                        
                        // Determine column class
                        let columnClass = 'column';
                        if (layoutStyle === 'custom' && columnWidths[i]) {
                            const widthNames = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 
                                             'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen'];
                            if (columnWidths[i] >= 1 && columnWidths[i] <= 16) {
                                columnClass = `${widthNames[columnWidths[i]]} wide column`;
                            }
                        }
                        
                        columnsHtml += `
                            <div class="${columnClass}">
                                ${columnContent}
                            </div>
                        `;
                    }
                    
                    return `
                        <div class="${gridClasses}">
                            ${columnsHtml}
                        </div>
                    `;
                default:
                    return `<!-- Unknown field type: ${element.type} -->`;
            }
        }

        function copyCode() {
            const code = $('#codeContent').text();
            navigator.clipboard.writeText(code).then(() => {
                const btn = $('.copy-btn');
                const originalText = btn.text();
                btn.text('Kopiert!').addClass('copied');
                setTimeout(() => {
                    btn.text(originalText).removeClass('copied');
                }, 2000);
            });
        }

        function reorderElements(oldIndex, newIndex) {
            const element = formElements.splice(oldIndex, 1)[0];
            formElements.splice(newIndex, 0, element);
            generateCode();
        }

        // Close modal on overlay click
        $('#configOverlay').on('click', function(e) {
            if (e.target === this) {
                closeConfig();
            }
        });

        // Keyboard shortcuts
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                closeConfig();
            }
        });

        // Row configuration dropdown handler
        $(document).on('change', 'select[name="layoutStyle"]', function() {
            const customWidthsField = $('#customWidthsField');
            if ($(this).val() === 'custom') {
                customWidthsField.show();
            } else {
                customWidthsField.hide();
            }
        });
    </script>
    
    <!-- Hidden PHP Template -->
    <script type="text/template" id="php-template">&lt;?php
use EasyForm\EasyForm;

$form = new EasyForm('my_form', [
    'width' => 600
]);

$form->action('process.php')
     ->method('POST')
     {{AJAX_CODE}};

{{ELEMENTS_CODE}}

$form->display();
?&gt;</script>

    <!-- Hidden HTML Template -->
    <script type="text/template" id="html-template"><!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>Generated Form</title>
    <link rel="stylesheet" href="semantic/dist/semantic.min.css">
</head>
<body>
    <div class="ui container" style="padding: 40px;">
        &lt;?php
        require_once 'autoload.php';
        use EasyForm\EasyForm;
        
        // Generated form code here
        ?&gt;
    </div>
    
    <script src="jquery/jquery.min.js"><\/script>
    <script src="semantic/dist/semantic.min.js"><\/script>
</body>
</html></script>

    <!-- Form Configuration Modal -->
    <div id="formConfigModal" class="ui modal">
        <i class="close icon"></i>
        <div class="header">
            <i class="cog icon"></i>
            Formular Konfiguration
        </div>
        <div class="content">
            <form class="ui form">
                <h4 class="ui dividing header">Basis-Einstellungen</h4>
                <div class="two fields">
                    <div class="field">
                        <label>Formular ID</label>
                        <input type="text" id="config_id" placeholder="my_form">
                    </div>
                    <div class="field">
                        <label>Theme</label>
                        <select id="config_theme" class="ui dropdown">
                            <option value="semantic">Semantic UI</option>
                            <option value="bootstrap">Bootstrap</option>
                            <option value="material">Material Design</option>
                        </select>
                    </div>
                </div>
                
                <div class="two fields">
                    <div class="field">
                        <label>Breite (px)</label>
                        <input type="number" id="config_width" placeholder="600">
                    </div>
                    <div class="field">
                        <label>Größe</label>
                        <select id="config_size" class="ui dropdown">
                            <option value="mini">Mini</option>
                            <option value="tiny">Klein</option>
                            <option value="small">Klein-Mittel</option>
                            <option value="medium">Mittel</option>
                            <option value="large">Groß</option>
                            <option value="huge">Sehr groß</option>
                        </select>
                    </div>
                </div>
                
                <h4 class="ui dividing header">Verhalten</h4>
                <div class="inline fields">
                    <div class="field">
                        <div class="ui checkbox">
                            <input type="checkbox" id="config_autocomplete" checked>
                            <label>Autovervollständigung</label>
                        </div>
                    </div>
                    <div class="field">
                        <div class="ui checkbox">
                            <input type="checkbox" id="config_showErrors" checked>
                            <label>Fehler anzeigen</label>
                        </div>
                    </div>
                    <div class="field">
                        <div class="ui checkbox">
                            <input type="checkbox" id="config_liveValidation" checked>
                            <label>Live-Validierung</label>
                        </div>
                    </div>
                </div>
                
                <div class="inline fields">
                    <div class="field">
                        <div class="ui checkbox">
                            <input type="checkbox" id="config_submitButton" checked>
                            <label>Submit Button</label>
                        </div>
                    </div>
                    <div class="field">
                        <div class="ui checkbox">
                            <input type="checkbox" id="config_resetButton">
                            <label>Reset Button</label>
                        </div>
                    </div>
                </div>
                
                <h4 class="ui dividing header">Formular Aktion</h4>
                <div class="two fields">
                    <div class="field">
                        <label>Action URL</label>
                        <input type="text" id="config_action" placeholder="process.php">
                    </div>
                    <div class="field">
                        <label>Method</label>
                        <select id="config_method" class="ui dropdown">
                            <option value="POST">POST</option>
                            <option value="GET">GET</option>
                        </select>
                    </div>
                </div>
                
                <div class="field">
                    <label>Sprache</label>
                    <select id="config_language" class="ui dropdown">
                        <option value="de">Deutsch</option>
                        <option value="en">English</option>
                        <option value="fr">Français</option>
                        <option value="es">Español</option>
                    </select>
                </div>
                
                <div class="field">
                    <label>Zusätzliche CSS-Klassen</label>
                    <input type="text" id="config_class" placeholder="ui segment padded">
                </div>
            </form>
        </div>
        <div class="actions">
            <div class="ui black deny button">
                Abbrechen
            </div>
            <div class="ui positive right labeled icon button" onclick="saveFormConfig()">
                Speichern
                <i class="checkmark icon"></i>
            </div>
        </div>
    </div>

</body>
</html>
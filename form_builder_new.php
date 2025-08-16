<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyForm Builder - Visual Form Creator</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.5.0/dist/semantic.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .header {
            background: white;
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .builder-container {
            display: grid;
            grid-template-columns: 250px 1fr 300px;
            gap: 20px;
            margin-top: 20px;
            height: calc(100vh - 100px);
        }
        
        .panel {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow-y: auto;
        }
        
        .field-palette h3, .properties-panel h3 {
            margin-bottom: 15px;
            color: #333;
        }
        
        .field-item {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 10px;
            cursor: move;
            transition: all 0.3s;
        }
        
        .field-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }
        
        .form-preview {
            min-height: 400px;
            border: 2px dashed #dee2e6;
            border-radius: 4px;
            padding: 20px;
        }
        
        .form-preview.drag-over {
            background: #f0f8ff;
            border-color: #667eea;
        }
        
        .preview-field {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 15px;
            position: relative;
        }
        
        .preview-field.selected {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .field-actions {
            position: absolute;
            top: 5px;
            right: 5px;
            display: none;
        }
        
        .preview-field:hover .field-actions {
            display: block;
        }
        
        .btn-icon {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 3px;
            padding: 5px 8px;
            cursor: pointer;
            margin-left: 5px;
        }
        
        .btn-icon:hover {
            background: #f8f9fa;
        }
        
        .property-group {
            margin-bottom: 20px;
        }
        
        .property-field {
            margin-bottom: 10px;
        }
        
        .property-field label {
            display: block;
            margin-bottom: 5px;
            font-size: 13px;
            color: #666;
        }
        
        .property-field input, .property-field select, .property-field textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }
        
        .toolbar {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5a67d8;
        }
        
        .btn-success {
            background: #48bb78;
            color: white;
        }
        
        .btn-danger {
            background: #f56565;
            color: white;
        }
        
        .code-output {
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 20px;
            border-radius: 4px;
            font-family: 'Monaco', 'Courier New', monospace;
            font-size: 13px;
            white-space: pre-wrap;
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1 style="color: #333;">
                <i class="wpforms icon"></i> EasyForm Builder
                <small style="color: #666; font-size: 14px; margin-left: 10px;">Visual Form Creator</small>
            </h1>
        </div>
    </div>
    
    <div class="container">
        <div class="toolbar">
            <div>
                <button class="btn btn-primary" onclick="saveForm()">
                    <i class="save icon"></i> Save Form
                </button>
                <button class="btn btn-success" onclick="previewForm()">
                    <i class="eye icon"></i> Preview
                </button>
                <button class="btn btn-danger" onclick="clearForm()">
                    <i class="trash icon"></i> Clear
                </button>
            </div>
            <div>
                <button class="btn btn-primary" onclick="exportCode()">
                    <i class="code icon"></i> Export Code
                </button>
                <button class="btn btn-primary" onclick="loadTemplate()">
                    <i class="clone icon"></i> Templates
                </button>
            </div>
        </div>
        
        <div class="builder-container">
            <!-- Field Palette -->
            <div class="panel field-palette">
                <h3><i class="th icon"></i> Field Types</h3>
                
                <div class="field-item" draggable="true" data-type="text">
                    <i class="font icon"></i> Text Input
                </div>
                
                <div class="field-item" draggable="true" data-type="email">
                    <i class="mail icon"></i> Email
                </div>
                
                <div class="field-item" draggable="true" data-type="password">
                    <i class="lock icon"></i> Password
                </div>
                
                <div class="field-item" draggable="true" data-type="textarea">
                    <i class="align left icon"></i> Textarea
                </div>
                
                <div class="field-item" draggable="true" data-type="select">
                    <i class="dropdown icon"></i> Dropdown
                </div>
                
                <div class="field-item" draggable="true" data-type="checkbox">
                    <i class="check square icon"></i> Checkbox
                </div>
                
                <div class="field-item" draggable="true" data-type="radio">
                    <i class="dot circle icon"></i> Radio
                </div>
                
                <div class="field-item" draggable="true" data-type="date">
                    <i class="calendar icon"></i> Date
                </div>
                
                <div class="field-item" draggable="true" data-type="file">
                    <i class="file icon"></i> File Upload
                </div>
                
                <div class="field-item" draggable="true" data-type="rating">
                    <i class="star icon"></i> Rating
                </div>
                
                <div class="field-item" draggable="true" data-type="tags">
                    <i class="tags icon"></i> Tags
                </div>
                
                <div class="field-item" draggable="true" data-type="slider">
                    <i class="sliders horizontal icon"></i> Slider
                </div>
            </div>
            
            <!-- Form Canvas -->
            <div class="panel">
                <h3><i class="edit icon"></i> Form Canvas</h3>
                <div class="form-preview" id="formCanvas">
                    <p style="text-align: center; color: #999;">
                        Drag fields here to start building your form
                    </p>
                </div>
            </div>
            
            <!-- Properties Panel -->
            <div class="panel properties-panel">
                <h3><i class="cog icon"></i> Properties</h3>
                <div id="propertiesContent">
                    <p style="color: #999;">Select a field to edit its properties</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Code Export Modal -->
    <div class="ui modal" id="codeModal">
        <i class="close icon"></i>
        <div class="header">
            Generated PHP Code
        </div>
        <div class="content">
            <div class="code-output" id="codeOutput"></div>
        </div>
        <div class="actions">
            <button class="ui button" onclick="copyCode()">Copy Code</button>
            <button class="ui positive button" onclick="downloadCode()">Download</button>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.5.0/dist/semantic.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    
    <script>
        let formFields = [];
        let selectedField = null;
        let fieldIdCounter = 1;
        
        // Initialize drag and drop
        document.addEventListener('DOMContentLoaded', function() {
            initializeDragDrop();
            initializeSortable();
        });
        
        function initializeDragDrop() {
            const fieldItems = document.querySelectorAll('.field-item');
            const formCanvas = document.getElementById('formCanvas');
            
            fieldItems.forEach(item => {
                item.addEventListener('dragstart', handleDragStart);
            });
            
            formCanvas.addEventListener('dragover', handleDragOver);
            formCanvas.addEventListener('drop', handleDrop);
            formCanvas.addEventListener('dragleave', handleDragLeave);
        }
        
        function initializeSortable() {
            new Sortable(document.getElementById('formCanvas'), {
                animation: 150,
                ghostClass: 'sortable-ghost',
                onEnd: function() {
                    updateFieldOrder();
                }
            });
        }
        
        function handleDragStart(e) {
            e.dataTransfer.effectAllowed = 'copy';
            e.dataTransfer.setData('fieldType', e.target.dataset.type);
        }
        
        function handleDragOver(e) {
            e.preventDefault();
            e.currentTarget.classList.add('drag-over');
        }
        
        function handleDragLeave(e) {
            e.currentTarget.classList.remove('drag-over');
        }
        
        function handleDrop(e) {
            e.preventDefault();
            e.currentTarget.classList.remove('drag-over');
            
            const fieldType = e.dataTransfer.getData('fieldType');
            if (fieldType) {
                addField(fieldType);
            }
        }
        
        function addField(type) {
            const field = {
                id: 'field_' + fieldIdCounter++,
                type: type,
                label: 'Field Label',
                name: 'field_name',
                placeholder: '',
                required: false,
                options: []
            };
            
            formFields.push(field);
            renderFormCanvas();
            selectField(field.id);
        }
        
        function renderFormCanvas() {
            const canvas = document.getElementById('formCanvas');
            
            if (formFields.length === 0) {
                canvas.innerHTML = '<p style="text-align: center; color: #999;">Drag fields here to start building your form</p>';
                return;
            }
            
            canvas.innerHTML = formFields.map(field => `
                <div class="preview-field" data-field-id="\${field.id}" onclick="selectField('\${field.id}')">
                    <div class="field-actions">
                        <button class="btn-icon" onclick="duplicateField('\${field.id}'); event.stopPropagation();">
                            <i class="copy icon"></i>
                        </button>
                        <button class="btn-icon" onclick="deleteField('\${field.id}'); event.stopPropagation();">
                            <i class="trash icon"></i>
                        </button>
                    </div>
                    \${renderFieldPreview(field)}
                </div>
            `).join('');
        }
        
        function renderFieldPreview(field) {
            switch(field.type) {
                case 'text':
                case 'email':
                case 'password':
                    return `
                        <label>\${field.label} \${field.required ? '<span style="color: red;">*</span>' : ''}</label>
                        <input type="\${field.type}" placeholder="\${field.placeholder}" disabled>
                    `;
                case 'textarea':
                    return `
                        <label>\${field.label} \${field.required ? '<span style="color: red;">*</span>' : ''}</label>
                        <textarea placeholder="\${field.placeholder}" disabled></textarea>
                    `;
                case 'select':
                    return `
                        <label>\${field.label} \${field.required ? '<span style="color: red;">*</span>' : ''}</label>
                        <select disabled>
                            <option>Select an option</option>
                        </select>
                    `;
                case 'checkbox':
                    return `
                        <label>
                            <input type="checkbox" disabled> \${field.label}
                        </label>
                    `;
                case 'radio':
                    return `
                        <label>\${field.label}</label>
                        <div>
                            <label><input type="radio" name="\${field.name}" disabled> Option 1</label>
                        </div>
                    `;
                case 'date':
                    return `
                        <label>\${field.label} \${field.required ? '<span style="color: red;">*</span>' : ''}</label>
                        <input type="date" disabled>
                    `;
                case 'file':
                    return `
                        <label>\${field.label} \${field.required ? '<span style="color: red;">*</span>' : ''}</label>
                        <input type="file" disabled>
                    `;
                case 'rating':
                    return `
                        <label>\${field.label}</label>
                        <div>⭐⭐⭐⭐⭐</div>
                    `;
                case 'tags':
                    return `
                        <label>\${field.label}</label>
                        <input type="text" placeholder="Add tags..." disabled>
                    `;
                case 'slider':
                    return `
                        <label>\${field.label}</label>
                        <input type="range" disabled>
                    `;
                default:
                    return `<p>Unknown field type</p>`;
            }
        }
        
        function selectField(fieldId) {
            document.querySelectorAll('.preview-field').forEach(el => {
                el.classList.remove('selected');
            });
            
            const fieldElement = document.querySelector(`[data-field-id="\${fieldId}"]`);
            if (fieldElement) {
                fieldElement.classList.add('selected');
            }
            
            selectedField = formFields.find(f => f.id === fieldId);
            renderProperties();
        }
        
        function renderProperties() {
            const propertiesContent = document.getElementById('propertiesContent');
            
            if (!selectedField) {
                propertiesContent.innerHTML = '<p style="color: #999;">Select a field to edit its properties</p>';
                return;
            }
            
            propertiesContent.innerHTML = `
                <div class="property-group">
                    <div class="property-field">
                        <label>Label</label>
                        <input type="text" value="\${selectedField.label}" 
                               onchange="updateFieldProperty('label', this.value)">
                    </div>
                    <div class="property-field">
                        <label>Name</label>
                        <input type="text" value="\${selectedField.name}" 
                               onchange="updateFieldProperty('name', this.value)">
                    </div>
                    <div class="property-field">
                        <label>Placeholder</label>
                        <input type="text" value="\${selectedField.placeholder || ''}" 
                               onchange="updateFieldProperty('placeholder', this.value)">
                    </div>
                    <div class="property-field">
                        <label>
                            <input type="checkbox" \${selectedField.required ? 'checked' : ''} 
                                   onchange="updateFieldProperty('required', this.checked)">
                            Required
                        </label>
                    </div>
                </div>
            `;
        }
        
        function updateFieldProperty(property, value) {
            if (selectedField) {
                selectedField[property] = value;
                renderFormCanvas();
                selectField(selectedField.id);
            }
        }
        
        function deleteField(fieldId) {
            formFields = formFields.filter(f => f.id !== fieldId);
            renderFormCanvas();
            selectedField = null;
            renderProperties();
        }
        
        function duplicateField(fieldId) {
            const field = formFields.find(f => f.id === fieldId);
            if (field) {
                const newField = {
                    ...field,
                    id: 'field_' + fieldIdCounter++,
                    name: field.name + '_copy'
                };
                formFields.push(newField);
                renderFormCanvas();
            }
        }
        
        function clearForm() {
            if (confirm('Are you sure you want to clear all fields?')) {
                formFields = [];
                selectedField = null;
                renderFormCanvas();
                renderProperties();
            }
        }
        
        function exportCode() {
            const code = generatePHPCode();
            document.getElementById('codeOutput').textContent = code;
            $('#codeModal').modal('show');
        }
        
        function generatePHPCode() {
            let code = '<' + '?php\n';
            code += 'require_once \'easy_form/EasyForm.php\';\n\n';
            code += 'use EasyForm\\EasyForm;\n\n';
            code += '// Create form instance\n';
            code += '$form = new EasyForm(\'myForm\', [\n';
            code += '    \'theme\' => \'semantic\',\n';
            code += '    \'ajax\' => true\n';
            code += ']);\n\n';
            code += '// Add fields\n';
            
            formFields.forEach(field => {
                const options = {
                    required: field.required,
                    placeholder: field.placeholder
                };
                
                code += '$form->' + field.type + '(\'' + field.name + '\', \'' + field.label + '\', ' + JSON.stringify(options) + ');\n';
            });
            
            code += '\n// Add submit button\n';
            code += '$form->submit(\'Submit\', [\'class\' => \'primary\']);\n\n';
            code += '// Render form\n';
            code += 'echo $form->render();\n';
            code += '?' + '>';
            
            return code;
        }
        
        function copyCode() {
            const code = document.getElementById('codeOutput').textContent;
            navigator.clipboard.writeText(code).then(() => {
                alert('Code copied to clipboard!');
            });
        }
        
        function downloadCode() {
            const code = document.getElementById('codeOutput').textContent;
            const blob = new Blob([code], {type: 'text/php'});
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'form.php';
            a.click();
        }
        
        function updateFieldOrder() {
            const fieldElements = document.querySelectorAll('.preview-field');
            const newOrder = [];
            
            fieldElements.forEach(el => {
                const fieldId = el.dataset.fieldId;
                const field = formFields.find(f => f.id === fieldId);
                if (field) {
                    newOrder.push(field);
                }
            });
            
            formFields = newOrder;
        }
        
        function saveForm() {
            localStorage.setItem('formBuilder_fields', JSON.stringify(formFields));
            alert('Form saved successfully!');
        }
        
        function loadTemplate() {
            // Load contact form template
            formFields = [
                {id: 'field_1', type: 'text', label: 'Name', name: 'name', required: true},
                {id: 'field_2', type: 'email', label: 'Email', name: 'email', required: true},
                {id: 'field_3', type: 'textarea', label: 'Message', name: 'message', required: true}
            ];
            fieldIdCounter = 4;
            renderFormCanvas();
            alert('Contact form template loaded!');
        }
        
        function previewForm() {
            const formHtml = formFields.map(field => renderFieldPreview(field)).join('<br>');
            const previewWindow = window.open('', '_blank');
            previewWindow.document.write(`
                <html>
                <head>
                    <title>Form Preview</title>
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.5.0/dist/semantic.min.css">
                    <style>
                        body { padding: 40px; }
                        .field { margin-bottom: 20px; }
                    </style>
                </head>
                <body>
                    <h2>Form Preview</h2>
                    <form class="ui form">
                        \${formHtml}
                        <button class="ui primary button">Submit</button>
                    </form>
                </body>
                </html>
            `);
        }
    </script>
</body>
</html>
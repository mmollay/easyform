<?php
/**
 * EasyForm - Der einfache Form Generator
 * 
 * Ein moderner, objektorientierter Form Generator
 * mit intuitiver API und automatischer Validierung
 * 
 * @version 1.0
 * @author Smart-Form Team
 */

namespace EasyForm;

class EasyForm 
{
    private string $id;
    private string $method = 'POST';
    private string $action = '';
    private array $fields = [];
    private array $buttons = [];
    private array $rules = [];
    private array $config = [];
    private bool $ajax = false;
    private array $ajaxCallbacks = [];
    private string $currentGroup = '';
    private array $groups = [];
    private bool $inRow = false;
    private bool $inColumn = false;
    
    /**
     * Konstruktor
     * 
     * @param string $id Form ID
     * @param array $config Optionale Konfiguration
     */
    public function __construct(string $id = 'easyform', array $config = [])
    {
        $this->id = $id;
        $this->config = array_merge([
            'theme' => 'semantic', // semantic, bootstrap, material
            'size' => 'medium',     // mini, tiny, small, medium, large, huge
            'width' => null,        // Max width in px
            'class' => '',          // Additional CSS classes
            'autocomplete' => true,
            'showErrors' => true,
            'liveValidation' => true,
            'submitButton' => true,
            'resetButton' => false,
            'language' => 'de'
        ], $config);
    }
    
    /**
     * Magische Methode für einfache Feldtypen
     * Ermöglicht: $form->text('name', 'Ihr Name')
     */
    public function __call($method, $args)
    {
        $fieldTypes = [
            'text', 'email', 'password', 'number', 'tel', 'url',
            'date', 'time', 'datetime', 'month', 'week', 'color',
            'search', 'file', 'hidden'
        ];
        
        if (in_array($method, $fieldTypes)) {
            $name = $args[0] ?? '';
            $label = $args[1] ?? '';
            $options = $args[2] ?? [];
            
            return $this->input($name, $label, array_merge(['type' => $method], $options));
        }
        
        throw new \BadMethodCallException("Method {$method} does not exist");
    }
    
    /**
     * Standard Input-Feld
     */
    public function input(string $name, string $label = '', array $options = []): self
    {
        $field = [
            'type' => $options['type'] ?? 'text',
            'name' => $name,
            'label' => $label,
            'id' => $options['id'] ?? $name,
            'placeholder' => $options['placeholder'] ?? '',
            'value' => $options['value'] ?? '',
            'required' => $options['required'] ?? false,
            'readonly' => $options['readonly'] ?? false,
            'disabled' => $options['disabled'] ?? false,
            'icon' => $options['icon'] ?? '',
            'help' => $options['help'] ?? '',
            'class' => $options['class'] ?? '',
            'attributes' => $options['attributes'] ?? [],
            'group' => $this->currentGroup
        ];
        
        // Validierungsregeln
        if (isset($options['rules'])) {
            $this->rules[$name] = $options['rules'];
        }
        
        // Auto-Validierung basierend auf Typ
        if ($field['type'] === 'email' && !isset($this->rules[$name])) {
            $this->rules[$name] = ['email'];
        }
        
        $this->fields[] = $field;
        return $this;
    }
    
    /**
     * Textarea
     */
    public function textarea(string $name, string $label = '', array $options = []): self
    {
        $field = [
            'type' => 'textarea',
            'name' => $name,
            'label' => $label,
            'id' => $options['id'] ?? $name,
            'placeholder' => $options['placeholder'] ?? '',
            'value' => $options['value'] ?? '',
            'rows' => $options['rows'] ?? 4,
            'required' => $options['required'] ?? false,
            'readonly' => $options['readonly'] ?? false,
            'disabled' => $options['disabled'] ?? false,
            'help' => $options['help'] ?? '',
            'class' => $options['class'] ?? '',
            'maxlength' => $options['maxlength'] ?? null,
            'group' => $this->currentGroup
        ];
        
        $this->fields[] = $field;
        return $this;
    }
    
    /**
     * Select/Dropdown
     */
    public function select(string $name, string $label = '', array $options = [], array $config = []): self
    {
        $field = [
            'type' => 'select',
            'name' => $name,
            'label' => $label,
            'id' => $config['id'] ?? $name,
            'options' => $options,
            'value' => $config['value'] ?? '',
            'placeholder' => $config['placeholder'] ?? '-- Bitte wählen --',
            'required' => $config['required'] ?? false,
            'disabled' => $config['disabled'] ?? false,
            'multiple' => $config['multiple'] ?? false,
            'searchable' => $config['searchable'] ?? false,
            'clearable' => $config['clearable'] ?? false,
            'help' => $config['help'] ?? '',
            'class' => $config['class'] ?? '',
            'group' => $this->currentGroup
        ];
        
        $this->fields[] = $field;
        return $this;
    }
    
    /**
     * Checkbox
     */
    public function checkbox(string $name, string $label = '', array $options = []): self
    {
        $field = [
            'type' => 'checkbox',
            'name' => $name,
            'label' => $label,
            'id' => $options['id'] ?? $name,
            'value' => $options['value'] ?? '1',
            'checked' => $options['checked'] ?? false,
            'required' => $options['required'] ?? false,
            'disabled' => $options['disabled'] ?? false,
            'help' => $options['help'] ?? '',
            'toggle' => $options['toggle'] ?? false,
            'group' => $this->currentGroup
        ];
        
        $this->fields[] = $field;
        return $this;
    }
    
    /**
     * Radio Buttons
     */
    public function radio(string $name, string $label = '', array $options = [], array $config = []): self
    {
        $field = [
            'type' => 'radio',
            'name' => $name,
            'label' => $label,
            'id' => $config['id'] ?? $name,
            'options' => $options,
            'value' => $config['value'] ?? '',
            'required' => $config['required'] ?? false,
            'disabled' => $config['disabled'] ?? false,
            'inline' => $config['inline'] ?? false,
            'help' => $config['help'] ?? '',
            'group' => $this->currentGroup
        ];
        
        $this->fields[] = $field;
        return $this;
    }
    
    /**
     * Range Slider
     */
    public function range(string $name, string $label = '', array $options = []): self
    {
        $field = [
            'type' => 'range',
            'name' => $name,
            'label' => $label,
            'id' => $options['id'] ?? $name,
            'min' => $options['min'] ?? 0,
            'max' => $options['max'] ?? 100,
            'step' => $options['step'] ?? 1,
            'value' => $options['value'] ?? 50,
            'showValue' => $options['showValue'] ?? true,
            'unit' => $options['unit'] ?? '',
            'help' => $options['help'] ?? '',
            'group' => $this->currentGroup
        ];
        
        $this->fields[] = $field;
        return $this;
    }
    
    /**
     * Datei-Upload
     */
    public function file(string $name, string $label = '', array $options = []): self
    {
        $field = [
            'type' => 'file',
            'name' => $name,
            'label' => $label,
            'id' => $options['id'] ?? $name,
            'accept' => $options['accept'] ?? '',
            'multiple' => $options['multiple'] ?? false,
            'maxSize' => $options['maxSize'] ?? '5MB',
            'required' => $options['required'] ?? false,
            'help' => $options['help'] ?? '',
            'preview' => $options['preview'] ?? true,
            'dragDrop' => $options['dragDrop'] ?? true,
            'group' => $this->currentGroup
        ];
        
        $this->fields[] = $field;
        return $this;
    }
    
    /**
     * HTML Content
     */
    public function html(string $content): self
    {
        $this->fields[] = [
            'type' => 'html',
            'content' => $content,
            'group' => $this->currentGroup
        ];
        return $this;
    }
    
    /**
     * Trennlinie
     */
    public function divider(string $text = ''): self
    {
        $this->fields[] = [
            'type' => 'divider',
            'text' => $text,
            'group' => $this->currentGroup
        ];
        return $this;
    }
    
    /**
     * Überschrift
     */
    public function heading(string $text, int $level = 3): self
    {
        $this->fields[] = [
            'type' => 'heading',
            'text' => $text,
            'level' => $level,
            'group' => $this->currentGroup
        ];
        return $this;
    }
    
    /**
     * Feldgruppe starten (für Tabs, Accordion, etc.)
     */
    public function group(string $name, string $label = '', array $options = []): self
    {
        $this->currentGroup = $name;
        $this->groups[$name] = [
            'label' => $label,
            'type' => $options['type'] ?? 'fieldset', // fieldset, tab, accordion
            'active' => $options['active'] ?? false,
            'icon' => $options['icon'] ?? '',
            'description' => $options['description'] ?? ''
        ];
        return $this;
    }
    
    /**
     * Gruppe beenden
     */
    public function endGroup(): self
    {
        $this->currentGroup = '';
        return $this;
    }
    
    /**
     * Grid-Row starten (für mehrspaltige Layouts)
     */
    public function row(array $options = []): self
    {
        $this->fields[] = [
            'type' => 'grid_row_start',
            'columns' => $options['columns'] ?? null,
            'stackable' => $options['stackable'] ?? true,
            'divided' => $options['divided'] ?? false,
            'padded' => $options['padded'] ?? false,
            'group' => $this->currentGroup
        ];
        $this->inRow = true;
        return $this;
    }
    
    /**
     * Grid-Row beenden
     */
    public function endRow(): self
    {
        $this->fields[] = [
            'type' => 'grid_row_end',
            'group' => $this->currentGroup
        ];
        $this->inRow = false;
        return $this;
    }
    
    /**
     * Grid-Column starten
     */
    public function col(int $width = null, array $options = []): self
    {
        $this->fields[] = [
            'type' => 'grid_col_start',
            'width' => $width,
            'mobile' => $options['mobile'] ?? null,
            'tablet' => $options['tablet'] ?? null,
            'computer' => $options['computer'] ?? null,
            'class' => $options['class'] ?? '',
            'group' => $this->currentGroup
        ];
        $this->inColumn = true;
        return $this;
    }
    
    /**
     * Grid-Column beenden
     */
    public function endCol(): self
    {
        $this->fields[] = [
            'type' => 'grid_col_end',
            'group' => $this->currentGroup
        ];
        $this->inColumn = false;
        return $this;
    }
    
    /**
     * Button hinzufügen
     */
    public function button(string $text, array $options = []): self
    {
        $this->buttons[] = [
            'text' => $text,
            'type' => $options['type'] ?? 'button', // submit, reset, button
            'name' => $options['name'] ?? '',
            'id' => $options['id'] ?? '',
            'class' => $options['class'] ?? 'primary',
            'icon' => $options['icon'] ?? '',
            'onclick' => $options['onclick'] ?? '',
            'position' => $options['position'] ?? 'right'
        ];
        return $this;
    }
    
    /**
     * Submit Button (Shortcut)
     */
    public function submit(string $text = 'Absenden', array $options = []): self
    {
        return $this->button($text, array_merge(['type' => 'submit'], $options));
    }
    
    /**
     * Reset Button (Shortcut)
     */
    public function reset(string $text = 'Zurücksetzen', array $options = []): self
    {
        return $this->button($text, array_merge(['type' => 'reset', 'class' => 'secondary'], $options));
    }
    
    /**
     * Validierungsregel hinzufügen
     */
    public function rule(string $field, $rules): self
    {
        if (is_string($rules)) {
            $rules = explode('|', $rules);
        }
        $this->rules[$field] = $rules;
        return $this;
    }
    
    /**
     * AJAX aktivieren
     */
    public function ajax(array $callbacks = []): self
    {
        $this->ajax = true;
        $this->ajaxCallbacks = array_merge([
            'url' => $this->action ?: $_SERVER['PHP_SELF'],
            'method' => $this->method,
            'success' => 'function(response) { console.log(response); }',
            'error' => 'function(error) { console.error(error); }',
            'beforeSend' => '',
            'complete' => ''
        ], $callbacks);
        return $this;
    }
    
    /**
     * Action URL setzen
     */
    public function action(string $url): self
    {
        $this->action = $url;
        return $this;
    }
    
    /**
     * Method setzen
     */
    public function method(string $method): self
    {
        $this->method = strtoupper($method);
        return $this;
    }
    
    /**
     * Form als HTML rendern
     */
    public function render(): string
    {
        $html = $this->renderFormOpen();
        
        // Gruppen rendern
        if (!empty($this->groups)) {
            $html .= $this->renderGroups();
        } else {
            // Felder ohne Gruppen rendern
            foreach ($this->fields as $field) {
                if (empty($field['group'])) {
                    $html .= $this->renderField($field);
                }
            }
        }
        
        // Buttons
        if (!empty($this->buttons) || $this->config['submitButton']) {
            $html .= $this->renderButtons();
        }
        
        $html .= $this->renderFormClose();
        $html .= $this->renderJavaScript(); // JavaScript wieder hier, aber mit jQuery check
        
        return $html;
    }
    
    /**
     * Form-Tag öffnen
     */
    private function renderFormOpen(): string
    {
        $classes = ['ui', 'form', $this->config['size'], $this->config['class']];
        $style = $this->config['width'] ? "style='max-width: {$this->config['width']}px;'" : '';
        
        $html = "<form ";
        $html .= "id='{$this->id}' ";
        $html .= "class='" . implode(' ', array_filter($classes)) . "' ";
        $html .= "method='{$this->method}' ";
        $html .= "action='{$this->action}' ";
        $html .= $style;
        $html .= ">\n";
        
        // CSRF Token (wenn Session aktiv)
        if (session_status() === PHP_SESSION_ACTIVE) {
            $token = bin2hex(random_bytes(32));
            $_SESSION['csrf_token_' . $this->id] = $token;
            $html .= "<input type='hidden' name='csrf_token' value='{$token}'>\n";
        }
        
        return $html;
    }
    
    /**
     * Form-Tag schließen
     */
    private function renderFormClose(): string
    {
        return "</form>\n";
    }
    
    /**
     * Einzelnes Feld rendern
     */
    private function renderField(array $field): string
    {
        // Grid-Elemente
        if ($field['type'] === 'grid_row_start') {
            return $this->renderGridRowStart($field);
        }
        if ($field['type'] === 'grid_row_end') {
            return "</div>\n";
        }
        if ($field['type'] === 'grid_col_start') {
            return $this->renderGridColStart($field);
        }
        if ($field['type'] === 'grid_col_end') {
            $this->inColumn = false;
            return "</div>\n";
        }
        
        // Map specific input types to the general input renderer
        $inputTypes = ['text', 'email', 'password', 'tel', 'url', 'number', 'date', 'time', 'datetime', 'month', 'week', 'color', 'search', 'hidden'];
        
        if (in_array($field['type'], $inputTypes)) {
            return $this->renderInputField($field);
        }
        
        $method = 'render' . ucfirst($field['type']) . 'Field';
        if (method_exists($this, $method)) {
            return $this->$method($field);
        }
        return '';
    }
    
    /**
     * Input-Feld rendern
     */
    private function renderInputField(array $field): string
    {
        $required = $field['required'] ? 'required' : '';
        $readonly = $field['readonly'] ? 'readonly' : '';
        $disabled = $field['disabled'] ? 'disabled' : '';
        
        $html = "<div class='field {$required}'>\n";
        
        if ($field['label']) {
            $html .= "<label for='{$field['id']}'>{$field['label']}</label>\n";
        }
        
        if ($field['icon']) {
            $html .= "<div class='ui icon input'>\n";
        }
        
        $html .= "<input ";
        $html .= "type=\"{$field['type']}\" ";
        $html .= "name=\"{$field['name']}\" ";
        $html .= "id=\"{$field['id']}\" ";
        $html .= "placeholder=\"{$field['placeholder']}\" ";
        $html .= "value=\"{$field['value']}\" ";
        if ($field['required']) {
            $html .= "required ";
        }
        $html .= "{$readonly} {$disabled} ";
        
        // Zusätzliche Attribute
        foreach ($field['attributes'] ?? [] as $key => $value) {
            $html .= "{$key}='{$value}' ";
        }
        
        $html .= ">\n";
        
        if ($field['icon']) {
            $html .= "<i class='{$field['icon']} icon'></i>\n";
            $html .= "</div>\n";
        }
        
        if ($field['help']) {
            $html .= "<small class='help-text'>{$field['help']}</small>\n";
        }
        
        $html .= "</div>\n";
        
        return $html;
    }
    
    /**
     * Textarea rendern
     */
    private function renderTextareaField(array $field): string
    {
        $required = $field['required'] ? 'required' : '';
        $readonly = $field['readonly'] ? 'readonly' : '';
        $disabled = $field['disabled'] ? 'disabled' : '';
        
        $html = "<div class='field {$required}'>\n";
        
        if ($field['label']) {
            $html .= "<label for='{$field['id']}'>{$field['label']}</label>\n";
        }
        
        $html .= "<textarea ";
        $html .= "name=\"{$field['name']}\" ";
        $html .= "id=\"{$field['id']}\" ";
        $html .= "placeholder=\"{$field['placeholder']}\" ";
        $html .= "rows=\"{$field['rows']}\" ";
        if ($field['required']) {
            $html .= "required ";
        }
        $html .= "{$readonly} {$disabled}";
        
        if ($field['maxlength']) {
            $html .= " maxlength='{$field['maxlength']}'";
        }
        
        $html .= ">{$field['value']}</textarea>\n";
        
        if ($field['help']) {
            $html .= "<small class='help-text'>{$field['help']}</small>\n";
        }
        
        $html .= "</div>\n";
        
        return $html;
    }
    
    /**
     * Select/Dropdown rendern
     */
    private function renderSelectField(array $field): string
    {
        $required = $field['required'] ? 'required' : '';
        $disabled = $field['disabled'] ? 'disabled' : '';
        $multiple = $field['multiple'] ? 'multiple' : '';
        $searchable = $field['searchable'] ? 'search' : '';
        
        $html = "<div class='field {$required}'>\n";
        
        if ($field['label']) {
            $html .= "<label for='{$field['id']}'>{$field['label']}</label>\n";
        }
        
        $classes = ['ui', 'dropdown', $searchable, $field['class']];
        
        $html .= "<select ";
        $html .= "name=\"{$field['name']}" . ($field['multiple'] ? '[]' : '') . "\" ";
        $html .= "id=\"{$field['id']}\" ";
        $html .= "class=\"" . implode(' ', array_filter($classes)) . "\" ";
        if ($field['required']) {
            $html .= "required ";
        }
        $html .= "{$multiple} {$disabled}>\n";
        
        if ($field['placeholder'] && !$field['multiple']) {
            $html .= "<option value=''>{$field['placeholder']}</option>\n";
        }
        
        foreach ($field['options'] as $value => $label) {
            $selected = ($field['value'] == $value) ? 'selected' : '';
            $html .= "<option value='{$value}' {$selected}>{$label}</option>\n";
        }
        
        $html .= "</select>\n";
        
        if ($field['help']) {
            $html .= "<small class='help-text'>{$field['help']}</small>\n";
        }
        
        $html .= "</div>\n";
        
        return $html;
    }
    
    /**
     * Checkbox rendern
     */
    private function renderCheckboxField(array $field): string
    {
        $required = $field['required'] ? 'required' : '';
        $disabled = $field['disabled'] ? 'disabled' : '';
        $checked = $field['checked'] ? 'checked' : '';
        $toggle = $field['toggle'] ? 'toggle' : '';
        
        $html = "<div class='field {$required}'>\n";
        $html .= "<div class='ui {$toggle} checkbox'>\n";
        
        $html .= "<input ";
        $html .= "type='checkbox' ";
        $html .= "name='{$field['name']}' ";
        $html .= "id='{$field['id']}' ";
        $html .= "value='{$field['value']}' ";
        $html .= "{$checked} {$disabled}>\n";
        
        $html .= "<label for='{$field['id']}'>{$field['label']}</label>\n";
        $html .= "</div>\n";
        
        if ($field['help']) {
            $html .= "<small class='help-text'>{$field['help']}</small>\n";
        }
        
        $html .= "</div>\n";
        
        return $html;
    }
    
    /**
     * Radio Buttons rendern
     */
    private function renderRadioField(array $field): string
    {
        $required = $field['required'] ? 'required' : '';
        $inline = $field['inline'] ? 'inline' : '';
        
        $html = "<div class='field {$required}'>\n";
        
        if ($field['label']) {
            $html .= "<label>{$field['label']}</label>\n";
        }
        
        if ($field['inline']) {
            $html .= "<div class='inline fields'>\n";
        }
        
        foreach ($field['options'] as $value => $label) {
            $checked = ($field['value'] == $value) ? 'checked' : '';
            $fieldId = $field['id'] . '_' . $value;
            
            if (!$field['inline']) {
                $html .= "<div class='field'>\n";
            }
            
            $html .= "<div class='ui radio checkbox'>\n";
            $html .= "<input ";
            $html .= "type='radio' ";
            $html .= "name='{$field['name']}' ";
            $html .= "id='{$fieldId}' ";
            $html .= "value='{$value}' ";
            $html .= "{$checked}>\n";
            $html .= "<label for='{$fieldId}'>{$label}</label>\n";
            $html .= "</div>\n";
            
            if (!$field['inline']) {
                $html .= "</div>\n";
            }
        }
        
        if ($field['inline']) {
            $html .= "</div>\n";
        }
        
        if ($field['help']) {
            $html .= "<small class='help-text'>{$field['help']}</small>\n";
        }
        
        $html .= "</div>\n";
        
        return $html;
    }
    
    /**
     * Range Slider rendern
     */
    private function renderRangeField(array $field): string
    {
        $html = "<div class='field'>\n";
        
        if ($field['label']) {
            $html .= "<label for='{$field['id']}'>{$field['label']}";
            if ($field['showValue']) {
                $html .= " <span id='{$field['id']}_value'>{$field['value']}</span>";
                if ($field['unit']) {
                    $html .= " {$field['unit']}";
                }
            }
            $html .= "</label>\n";
        }
        
        $html .= "<input ";
        $html .= "type='range' ";
        $html .= "name='{$field['name']}' ";
        $html .= "id='{$field['id']}' ";
        $html .= "min='{$field['min']}' ";
        $html .= "max='{$field['max']}' ";
        $html .= "step='{$field['step']}' ";
        $html .= "value='{$field['value']}' ";
        $html .= "oninput='document.getElementById(\"{$field['id']}_value\").innerText = this.value'>\n";
        
        if ($field['help']) {
            $html .= "<small class='help-text'>{$field['help']}</small>\n";
        }
        
        $html .= "</div>\n";
        
        return $html;
    }
    
    /**
     * File Upload rendern
     */
    private function renderFileField(array $field): string
    {
        $required = $field['required'] ? 'required' : '';
        $multiple = $field['multiple'] ? 'multiple' : '';
        
        $html = "<div class='field {$required}'>\n";
        
        if ($field['label']) {
            $html .= "<label for='{$field['id']}'>{$field['label']}</label>\n";
        }
        
        if ($field['dragDrop']) {
            $html .= "<div class='ui segment' style='border: 2px dashed #ccc; padding: 20px; text-align: center;'>\n";
            $html .= "<i class='cloud upload icon' style='font-size: 3em; color: #ccc;'></i>\n";
            $html .= "<p>Dateien hier ablegen oder klicken zum Auswählen</p>\n";
        }
        
        $html .= "<input ";
        $html .= "type='file' ";
        $html .= "name='{$field['name']}" . ($field['multiple'] ? '[]' : '') . "' ";
        $html .= "id='{$field['id']}' ";
        $html .= "accept='{$field['accept']}' ";
        $html .= "{$multiple}";
        
        if ($field['dragDrop']) {
            $html .= " style='display: none;'";
        }
        
        $html .= ">\n";
        
        if ($field['dragDrop']) {
            $html .= "</div>\n";
        }
        
        if ($field['help']) {
            $html .= "<small class='help-text'>{$field['help']}</small>\n";
        }
        
        if ($field['preview']) {
            $html .= "<div id='{$field['id']}_preview' class='file-preview'></div>\n";
        }
        
        $html .= "</div>\n";
        
        return $html;
    }
    
    /**
     * HTML Content rendern
     */
    private function renderHtmlField(array $field): string
    {
        return $field['content'] . "\n";
    }
    
    /**
     * Divider rendern
     */
    private function renderDividerField(array $field): string
    {
        if ($field['text']) {
            return "<div class='ui horizontal divider'>{$field['text']}</div>\n";
        }
        return "<div class='ui divider'></div>\n";
    }
    
    /**
     * Heading rendern
     */
    private function renderHeadingField(array $field): string
    {
        return "<h{$field['level']} class='ui header'>{$field['text']}</h{$field['level']}>\n";
    }
    
    /**
     * Gruppen rendern
     */
    private function renderGroups(): string
    {
        $html = '';
        
        // Tab-Gruppen
        $tabGroups = array_filter($this->groups, function($g) { return $g['type'] === 'tab'; });
        if (!empty($tabGroups)) {
            $html .= $this->renderTabGroups($tabGroups);
        }
        
        // Accordion-Gruppen
        $accordionGroups = array_filter($this->groups, function($g) { return $g['type'] === 'accordion'; });
        if (!empty($accordionGroups)) {
            $html .= $this->renderAccordionGroups($accordionGroups);
        }
        
        // Fieldset-Gruppen
        $fieldsetGroups = array_filter($this->groups, function($g) { return $g['type'] === 'fieldset'; });
        foreach ($fieldsetGroups as $name => $group) {
            $html .= $this->renderFieldsetGroup($name, $group);
        }
        
        // Felder ohne Gruppe
        foreach ($this->fields as $field) {
            if (empty($field['group'])) {
                $html .= $this->renderField($field);
            }
        }
        
        return $html;
    }
    
    /**
     * Tab-Gruppen rendern
     */
    private function renderTabGroups(array $groups): string
    {
        $tabId = $this->id . '_tabs';
        $html = "<div class=\"ui top attached tabular menu\" id=\"{$tabId}\">\n";
        
        $activeTab = null;
        $first = true;
        foreach ($groups as $name => $group) {
            if ($group['active'] || ($first && !$activeTab)) {
                $activeTab = $name;
            }
            $active = ($name === $activeTab) ? 'active' : '';
            $icon = $group['icon'] ? "<i class=\"{$group['icon']} icon\"></i> " : '';
            $html .= "<a class=\"item {$active}\" data-tab=\"{$this->id}_{$name}\">{$icon}{$group['label']}</a>\n";
            $first = false;
        }
        
        $html .= "</div>\n";
        
        foreach ($groups as $name => $group) {
            $active = ($name === $activeTab) ? 'active' : '';
            $html .= "<div class=\"ui bottom attached tab segment {$active}\" data-tab=\"{$this->id}_{$name}\">\n";
            
            if ($group['description']) {
                $html .= "<p>{$group['description']}</p>\n";
            }
            
            // Render fields for this tab
            $fieldsInTab = 0;
            foreach ($this->fields as $field) {
                if (isset($field['group']) && $field['group'] === $name) {
                    $fieldsInTab++;
                    $renderedField = $this->renderField($field);
                    $html .= $renderedField;
                }
            }
            // Debug: Show if no fields were found
            if ($fieldsInTab === 0 && isset($field['type']) && $field['type'] !== 'html') {
                $html .= "<!-- No fields found for tab: {$name} -->\n";
            }
            
            $html .= "</div>\n";
        }
        
        return $html;
    }
    
    /**
     * Accordion-Gruppen rendern
     */
    private function renderAccordionGroups(array $groups): string
    {
        $html = "<div class='ui accordion'>\n";
        
        foreach ($groups as $name => $group) {
            $active = $group['active'] ? 'active' : '';
            $icon = $group['icon'] ? "<i class='{$group['icon']} icon'></i> " : '';
            
            $html .= "<div class='title {$active}'>\n";
            $html .= "<i class='dropdown icon'></i>\n";
            $html .= "{$icon}{$group['label']}\n";
            $html .= "</div>\n";
            
            $html .= "<div class='content {$active}'>\n";
            
            if ($group['description']) {
                $html .= "<p>{$group['description']}</p>\n";
            }
            
            foreach ($this->fields as $field) {
                if ($field['group'] === $name) {
                    $html .= $this->renderField($field);
                }
            }
            
            $html .= "</div>\n";
        }
        
        $html .= "</div>\n";
        
        return $html;
    }
    
    /**
     * Fieldset-Gruppe rendern
     */
    private function renderFieldsetGroup(string $name, array $group): string
    {
        $html = "<div class='ui segment'>\n";
        
        if ($group['label']) {
            $icon = $group['icon'] ? "<i class='{$group['icon']} icon'></i> " : '';
            $html .= "<h3 class='ui header'>{$icon}{$group['label']}</h3>\n";
        }
        
        if ($group['description']) {
            $html .= "<p>{$group['description']}</p>\n";
        }
        
        foreach ($this->fields as $field) {
            if ($field['group'] === $name) {
                $html .= $this->renderField($field);
            }
        }
        
        $html .= "</div>\n";
        
        return $html;
    }
    
    /**
     * Grid Row Start rendern
     */
    private function renderGridRowStart(array $field): string
    {
        // WICHTIG: 'fields' statt 'grid' für korrekte Semantic UI Form-Struktur
        $classes = ['fields'];
        
        if ($field['stackable']) {
            $classes[] = 'stackable';
        }
        
        if ($field['columns']) {
            if (is_numeric($field['columns'])) {
                $numberWords = [
                    1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four',
                    5 => 'five', 6 => 'six', 7 => 'seven', 8 => 'eight'
                ];
                $classes[] = ($numberWords[$field['columns']] ?? '');
            } else if ($field['columns'] === 'equal width') {
                $classes[] = 'equal width';
            }
        }
        
        return "<div class='" . implode(' ', $classes) . "'>\n";
    }
    
    /**
     * Grid Column Start rendern
     */
    private function renderGridColStart(array $field): string
    {
        $classes = [];
        
        // Standard-Breite
        if ($field['width']) {
            $widthNames = [
                1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four',
                5 => 'five', 6 => 'six', 7 => 'seven', 8 => 'eight',
                9 => 'nine', 10 => 'ten', 11 => 'eleven', 12 => 'twelve',
                13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen', 16 => 'sixteen'
            ];
            $classes[] = ($widthNames[$field['width']] ?? '') . ' wide';
        }
        
        // Responsive Breiten
        if ($field['mobile']) {
            $classes[] = $field['mobile'] . ' wide mobile';
        }
        if ($field['tablet']) {
            $classes[] = $field['tablet'] . ' wide tablet';
        }
        if ($field['computer']) {
            $classes[] = $field['computer'] . ' wide computer';
        }
        
        // WICHTIG: 'field' für korrekte Form-Struktur in Semantic UI
        $classes[] = 'field';
        
        if ($field['class']) {
            $classes[] = $field['class'];
        }
        
        $this->inColumn = true;
        
        return "<div class='" . implode(' ', array_filter($classes)) . "'>\n";
    }
    
    /**
     * Buttons rendern
     */
    private function renderButtons(): string
    {
        $html = "<div class='field' style='text-align: right;'>\n";
        
        // Standard Submit-Button wenn keine anderen definiert
        if (empty($this->buttons) && $this->config['submitButton']) {
            $html .= "<button type='submit' class='ui primary button'>Absenden</button>\n";
            
            if ($this->config['resetButton']) {
                $html .= "<button type='reset' class='ui button'>Zurücksetzen</button>\n";
            }
        }
        
        // Custom Buttons
        foreach ($this->buttons as $button) {
            $icon = $button['icon'] ? "<i class='{$button['icon']} icon'></i> " : '';
            $onclick = $button['onclick'] ? "onclick=\"{$button['onclick']}\"" : '';
            
            $html .= "<button ";
            $html .= "type='{$button['type']}' ";
            $html .= "class='ui {$button['class']} button' ";
            
            if ($button['name']) {
                $html .= "name='{$button['name']}' ";
            }
            if ($button['id']) {
                $html .= "id='{$button['id']}' ";
            }
            
            $html .= "{$onclick}>";
            $html .= "{$icon}{$button['text']}";
            $html .= "</button>\n";
        }
        
        $html .= "</div>\n";
        
        return $html;
    }
    
    /**
     * JavaScript für Form rendern
     */
    private function renderJavaScript(): string
    {
        $html = "<script>\n";
        $html .= "// Define initialization function\n";
        $html .= "function initEasyForm_{$this->id}() {\n";
        $html .= "  if (typeof jQuery === 'undefined') {\n";
        $html .= "    console.error('jQuery is required for EasyForm');\n";
        $html .= "    return;\n";
        $html .= "  }\n";
        $html .= "  var $ = jQuery;\n";
        
        // Semantic UI Komponenten initialisieren
        $html .= "  // Initialize Semantic UI components (nur für dieses Formular)\n";
        $html .= "  $('#{$this->id} .ui.dropdown').dropdown();\n";
        $html .= "  $('#{$this->id} .ui.checkbox').checkbox();\n";
        $html .= "  $('#{$this->id} .ui.accordion').accordion();\n";
        $html .= "  \n";
        // Nur Tab-Initialisierung wenn das Formular tatsächlich Tabs hat
        $tabGroups = array_filter($this->groups, function($g) { return $g['type'] === 'tab'; });
        if (!empty($tabGroups)) {
            $html .= "  // Initialize tabs (nur wenn Tabs vorhanden)\n";
            $html .= "  if ($('#{$this->id}_tabs').length > 0) {\n";
            $html .= "    // Semantic UI Tab-Initialisierung\n";
            $html .= "    $('#{$this->id}_tabs .item').tab();\n";
            $html .= "    \n";
            $html .= "    // Fallback: Manual tab switching\n";
            $html .= "    $('#{$this->id}_tabs .item').off('click.manual').on('click.manual', function(e) {\n";
            $html .= "      e.preventDefault();\n";
            $html .= "      var tabName = $(this).attr('data-tab');\n";
            $html .= "      if (!tabName) return;\n";
            $html .= "      \n";
            $html .= "      // Remove active from all tabs and segments\n";
            $html .= "      $('#{$this->id}_tabs .item').removeClass('active');\n";
            $html .= "      $('#{$this->id} .tab.segment').removeClass('active');\n";
            $html .= "      \n";
            $html .= "      // Add active to clicked tab and corresponding segment\n";
            $html .= "      $(this).addClass('active');\n";
            $html .= "      $('#{$this->id} .tab.segment[data-tab=\"' + tabName + '\"]').addClass('active');\n";
            $html .= "    });\n";
            $html .= "  }\n";
            $html .= "  \n";
        }
        
        // Validierung
        if ($this->config['liveValidation'] && !empty($this->rules)) {
            $html .= $this->renderValidationJS();
        }
        
        // AJAX Submit
        if ($this->ajax) {
            $html .= $this->renderAjaxJS();
        }
        
        // WICHTIG: Submit-Button Fix - Funktioniert auch mit Icons im Button
        $html .= "  // Submit Button Fix (mit Icon-Support)\n";
        $html .= "  if (!$('#{$this->id}').data('submitFixed')) {\n";
        $html .= "    $('#{$this->id}').data('submitFixed', true);\n";
        $html .= "    \n";
        $html .= "    // Use event delegation and handle icon clicks\n";
        $html .= "    $(document).on('click', '#{$this->id} button[type=\"submit\"], #{$this->id} button[type=\"submit\"] *', function(e) {\n";
        $html .= "      console.log('Submit button or child clicked');\n";
        $html .= "      \n";
        $html .= "      // Find the actual button (in case an icon was clicked)\n";
        $html .= "      var button = $(this).closest('button[type=\"submit\"]');\n";
        $html .= "      if (button.length === 0) return;\n";
        $html .= "      \n";
        $html .= "      // Prüfe ob bereits ein Submit läuft\n";
        $html .= "      if (button.hasClass('loading')) {\n";
        $html .= "        console.log('Submit already in progress');\n";
        $html .= "        return false;\n";
        $html .= "      }\n";
        $html .= "      \n";
        $html .= "      var form = $('#{$this->id}')[0];\n";
        $html .= "      if (form && !e.isDefaultPrevented()) {\n";
        $html .= "        // Check HTML5 validation\n";
        $html .= "        if (!form.checkValidity()) {\n";
        $html .= "          e.preventDefault();\n";
        $html .= "          e.stopPropagation();\n";
        $html .= "          form.reportValidity();\n";
        $html .= "          return false;\n";
        $html .= "        }\n";
        $html .= "        // Handle submission\n";
        if (!$this->ajax) {
            $html .= "        // No AJAX - let form submit normally\n";
            $html .= "        return true;\n";
        } else {
            $html .= "        // AJAX form - prevent default and trigger submit event\n";
            $html .= "        e.preventDefault();\n";
            $html .= "        $('#{$this->id}').trigger('submit.ajax');\n";
        }
        $html .= "      }\n";
        $html .= "      return false;\n";
        $html .= "    });\n";
        $html .= "  }\n";
        
        // File Upload Preview
        $html .= "  $('input[type=file]').on('change', function() {\n";
        $html .= "    var files = this.files;\n";
        $html .= "    var preview = $('#' + this.id + '_preview');\n";
        $html .= "    preview.empty();\n";
        $html .= "    for (var i = 0; i < files.length; i++) {\n";
        $html .= "      preview.append('<div class=\"ui label\">' + files[i].name + '</div>');\n";
        $html .= "    }\n";
        $html .= "  });\n";
        
        $html .= "}\n"; // End of initEasyForm function
        $html .= "\n";
        $html .= "// Call initialization when jQuery is ready\n";
        $html .= "(function() {\n";
        $html .= "  var initFunction = function() {\n";
        $html .= "    console.log('Initializing form: {$this->id}');\n";
        $html .= "    initEasyForm_{$this->id}();\n";
        $html .= "  };\n";
        $html .= "  \n";
        $html .= "  if (typeof jQuery !== 'undefined') {\n";
        $html .= "    jQuery(document).ready(initFunction);\n";
        $html .= "  } else if (typeof $ !== 'undefined') {\n";
        $html .= "    $(document).ready(initFunction);\n";
        $html .= "  } else {\n";
        $html .= "    document.addEventListener('DOMContentLoaded', function() {\n";
        $html .= "      var attempts = 0;\n";
        $html .= "      var checkJQuery = setInterval(function() {\n";
        $html .= "        attempts++;\n";
        $html .= "        if (typeof jQuery !== 'undefined' || typeof $ !== 'undefined') {\n";
        $html .= "          clearInterval(checkJQuery);\n";
        $html .= "          initFunction();\n";
        $html .= "        } else if (attempts > 50) {\n";
        $html .= "          clearInterval(checkJQuery);\n";
        $html .= "          console.error('jQuery not found after 5 seconds');\n";
        $html .= "        }\n";
        $html .= "      }, 100);\n";
        $html .= "    });\n";
        $html .= "  }\n";
        $html .= "})();\n";
        $html .= "</script>\n";
        
        return $html;
    }
    
    /**
     * Validierungs-JavaScript
     */
    private function renderValidationJS(): string
    {
        $rules = [];
        
        foreach ($this->rules as $field => $fieldRules) {
            $semanticRules = [];
            
            foreach ($fieldRules as $rule) {
                if (is_string($rule)) {
                    switch ($rule) {
                        case 'required':
                            $semanticRules[] = ['type' => 'empty', 'prompt' => 'Dieses Feld ist erforderlich'];
                            break;
                        case 'email':
                            $semanticRules[] = ['type' => 'email', 'prompt' => 'Bitte geben Sie eine gültige E-Mail-Adresse ein'];
                            break;
                        case 'number':
                            $semanticRules[] = ['type' => 'number', 'prompt' => 'Bitte geben Sie eine Zahl ein'];
                            break;
                        case 'url':
                            $semanticRules[] = ['type' => 'url', 'prompt' => 'Bitte geben Sie eine gültige URL ein'];
                            break;
                    }
                }
            }
            
            if (!empty($semanticRules)) {
                $rules[$field] = ['identifier' => $field, 'rules' => $semanticRules];
            }
        }
        
        $js = "  $('#{$this->id}').form({\n";
        $js .= "    fields: " . json_encode($rules, JSON_PRETTY_PRINT) . "\n";
        $js .= "  });\n";
        
        return $js;
    }
    
    /**
     * AJAX JavaScript
     */
    private function renderAjaxJS(): string
    {
        $js = "  // AJAX Form Submit\n";
        $js .= "  console.log('Attaching AJAX handler to form: {$this->id}');\n";
        $js .= "  \n";
        $js .= "  // Entferne alte Handler und füge neuen hinzu\n";
        $js .= "  $('#{$this->id}').off('submit.ajax').on('submit.ajax', function(e) {\n";
        $js .= "    e.preventDefault();\n";
        $js .= "    console.log('AJAX submit triggered for form: {$this->id}');\n";
        $js .= "    \n";
        $js .= "    var form = $(this);\n";
        $js .= "    var formData = new FormData(this);\n";
        $js .= "    \n";
        
        if ($this->ajaxCallbacks['beforeSend']) {
            $js .= "    // Before send callback\n";
            $js .= "    " . $this->ajaxCallbacks['beforeSend'] . ";\n";
            $js .= "    \n";
        }
        
        $js .= "    $.ajax({\n";
        $js .= "      url: '{$this->ajaxCallbacks['url']}',\n";
        $js .= "      type: '{$this->ajaxCallbacks['method']}',\n";
        $js .= "      data: formData,\n";
        $js .= "      processData: false,\n";
        $js .= "      contentType: false,\n";
        $js .= "      dataType: 'json',\n";
        $js .= "      success: {$this->ajaxCallbacks['success']},\n";
        $js .= "      error: {$this->ajaxCallbacks['error']}";
        
        if ($this->ajaxCallbacks['complete']) {
            $js .= ",\n      complete: {$this->ajaxCallbacks['complete']}";
        }
        
        $js .= "\n    });\n";
        $js .= "    \n";
        $js .= "    return false; // Prevent default form submission\n";
        $js .= "  });\n";
        $js .= "  \n";
        $js .= "  // Zusätzlicher Handler für Submit-Button\n";
        $js .= "  $('#{$this->id} button[type=\"submit\"]').on('click', function(e) {\n";
        $js .= "    console.log('Submit button clicked in form {$this->id}');\n";
        $js .= "    // Let the form handle the submission\n";
        $js .= "  });\n";
        $js .= "  \n";
        
        return $js;
    }
    
    /**
     * Shortcut zum direkten Ausgeben
     */
    public function display(): void
    {
        echo $this->render();
    }
    
    /**
     * Form als Array (für JSON API)
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'method' => $this->method,
            'action' => $this->action,
            'fields' => $this->fields,
            'buttons' => $this->buttons,
            'rules' => $this->rules,
            'config' => $this->config,
            'groups' => $this->groups
        ];
    }
    
    /**
     * Form als JSON
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }
}
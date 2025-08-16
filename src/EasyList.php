<?php
/**
 * EasyList - Modern List Generator with Fluent Interface
 * Version 1.0
 * 
 * Features:
 * - Fluent Interface / Method Chaining
 * - Live Search
 * - Column Filters
 * - Sorting
 * - Pagination
 * - Export (CSV, JSON, Excel)
 * - Responsive Design
 * - AJAX Support
 */

namespace EasyForm;

class EasyList {
    private $id;
    private $data = [];
    private $columns = [];
    private $filters = [];
    private $searchable = true;
    private $sortable = true;
    private $paginate = true;
    private $pageSize = 25;
    private $exportable = true;
    private $exportFormats = ['csv', 'json', 'excel'];
    private $ajax = false;
    private $ajaxUrl = '';
    private $classes = 'ui celled table';
    private $striped = true;
    private $selectable = false;
    private $actions = [];
    private $bulkActions = [];
    private $emptyMessage = 'Keine Daten vorhanden';
    private $searchPlaceholder = 'Suchen...';
    private $responsive = true;
    private $compact = false;
    private $color = '';
    private $customStyles = '';
    private $rowClick = null;
    private $rowClass = null;
    private $summaryRow = false;
    private $groupBy = null;
    
    /**
     * Constructor
     */
    public function __construct($id = 'easylist') {
        $this->id = $id;
    }
    
    /**
     * Set data source
     */
    public function data($data) {
        $this->data = $data;
        return $this;
    }
    
    /**
     * Load data from database
     */
    public function fromDatabase($query, $connection = null) {
        // Implement database loading
        return $this;
    }
    
    /**
     * Load data from API
     */
    public function fromApi($url, $options = []) {
        $this->ajax = true;
        $this->ajaxUrl = $url;
        return $this;
    }
    
    /**
     * Add a column
     */
    public function column($key, $label, $options = []) {
        $this->columns[] = array_merge([
            'key' => $key,
            'label' => $label,
            'sortable' => true,
            'searchable' => true,
            'type' => 'text',
            'width' => null,
            'align' => 'left',
            'format' => null,
            'template' => null,
            'class' => '',
            'headerClass' => '',
            'filter' => null
        ], $options);
        return $this;
    }
    
    /**
     * Add multiple columns at once
     */
    public function columns($columns) {
        foreach ($columns as $key => $config) {
            if (is_string($config)) {
                $this->column($key, $config);
            } else {
                $this->column($key, $config['label'] ?? $key, $config);
            }
        }
        return $this;
    }
    
    /**
     * Add filter
     */
    public function filter($key, $type = 'text', $options = []) {
        $this->filters[$key] = array_merge([
            'type' => $type,
            'placeholder' => 'Filter...',
            'options' => [],
            'multiple' => false
        ], $options);
        return $this;
    }
    
    /**
     * Enable/disable search
     */
    public function searchable($enabled = true, $placeholder = null) {
        $this->searchable = $enabled;
        if ($placeholder) {
            $this->searchPlaceholder = $placeholder;
        }
        return $this;
    }
    
    /**
     * Enable/disable sorting
     */
    public function sortable($enabled = true) {
        $this->sortable = $enabled;
        return $this;
    }
    
    /**
     * Configure pagination
     */
    public function paginate($enabled = true, $pageSize = 25) {
        $this->paginate = $enabled;
        $this->pageSize = $pageSize;
        return $this;
    }
    
    /**
     * Configure export
     */
    public function exportable($enabled = true, $formats = ['csv', 'json', 'excel']) {
        $this->exportable = $enabled;
        $this->exportFormats = $formats;
        return $this;
    }
    
    /**
     * Enable row selection
     */
    public function selectable($enabled = true, $type = 'checkbox') {
        $this->selectable = $enabled;
        if ($enabled) {
            // Check if select column already exists
            $hasSelectColumn = false;
            foreach ($this->columns as $column) {
                if ($column['key'] === '_select') {
                    $hasSelectColumn = true;
                    break;
                }
            }
            
            // Only add if not already present
            if (!$hasSelectColumn) {
                array_unshift($this->columns, [
                    'key' => '_select',
                    'label' => '<input type="checkbox" class="select-all">',
                    'sortable' => false,
                    'searchable' => false,
                    'width' => '50px',
                    'align' => 'center',
                    'template' => '<input type="' . $type . '" class="row-select" value="{id}">'
                ]);
            }
        }
        return $this;
    }
    
    /**
     * Add action column
     */
    public function actions($actions) {
        $this->actions = $actions;
        $this->columns[] = [
            'key' => '_actions',
            'label' => 'Aktionen',
            'sortable' => false,
            'searchable' => false,
            'width' => '150px',
            'align' => 'center',
            'template' => $this->generateActionsTemplate($actions)
        ];
        return $this;
    }
    
    /**
     * Add bulk actions
     */
    public function bulkActions($actions) {
        $this->bulkActions = $actions;
        $this->selectable(true);
        return $this;
    }
    
    /**
     * Set table styling
     */
    public function style($classes = '', $options = []) {
        if ($classes) {
            $this->classes = $classes;
        }
        $this->striped = $options['striped'] ?? $this->striped;
        $this->compact = $options['compact'] ?? $this->compact;
        $this->color = $options['color'] ?? $this->color;
        return $this;
    }
    
    /**
     * Row click handler
     */
    public function onRowClick($callback) {
        $this->rowClick = $callback;
        return $this;
    }
    
    /**
     * Custom row class
     */
    public function rowClass($callback) {
        $this->rowClass = $callback;
        return $this;
    }
    
    /**
     * Group by column
     */
    public function groupBy($column) {
        $this->groupBy = $column;
        return $this;
    }
    
    /**
     * Add summary row
     */
    public function summary($enabled = true) {
        $this->summaryRow = $enabled;
        return $this;
    }
    
    /**
     * Generate actions template
     */
    private function generateActionsTemplate($actions) {
        $template = '<div class="ui small basic icon buttons">';
        foreach ($actions as $action) {
            $icon = $action['icon'] ?? 'ellipsis horizontal';
            $title = $action['label'] ?? '';
            $class = $action['class'] ?? '';
            $url = $action['url'] ?? '#';
            
            $template .= '<a class="ui button ' . $class . '" href="' . $url . '" title="' . $title . '">';
            $template .= '<i class="' . $icon . ' icon"></i>';
            $template .= '</a>';
        }
        $template .= '</div>';
        return $template;
    }
    
    /**
     * Generate HTML
     */
    public function render() {
        // Extract filters from column definitions
        foreach ($this->columns as $column) {
            if (isset($column['filter']) && $column['filter']) {
                $this->filters[$column['key']] = $column['filter'];
            }
        }
        
        // Add custom CSS for better styling
        $html = '<style>
            .easylist-container { margin: 20px 0; }
            .easylist-container .ui.menu { 
                margin-bottom: 20px; 
                border-radius: 8px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }
            .easylist-container .ui.menu .item { 
                padding: 10px 15px; 
            }
            .easylist-container .ui.form.easylist-filters { 
                margin-bottom: 15px; 
                background: #f8f9fa; 
                border-radius: 6px;
                border: 1px solid #e2e8f0;
            }
            .easylist-container .ui.form.easylist-filters .inline.fields {
                flex-wrap: wrap;
                gap: 10px;
            }
            .easylist-container .ui.form.easylist-filters .field {
                margin: 0 !important;
                display: flex;
                align-items: center;
            }
            .easylist-container .easylist-filters .ui.dropdown.filter-input {
                min-width: 140px !important;
                width: 140px !important;
            }
            .easylist-container .ui.button.compact {
                padding: 9px 15px;
            }
            .easylist-container .table-responsive { 
                border: 1px solid #d4d4d5; 
                border-radius: 8px; 
                overflow: hidden; 
                margin-bottom: 20px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            }
            .easylist-container .ui.table { 
                margin: 0; 
                border-radius: 0; 
            }
            .easylist-container .ui.table thead th { 
                background: #f0f1f2; 
                font-weight: 600;
                border-bottom: 2px solid #d4d4d5;
            }
            .easylist-container .ui.table th.sortable { 
                cursor: pointer; 
                user-select: none;
                transition: background 0.2s;
            }
            .easylist-container .ui.table th.sortable:hover { 
                background: #e8e9ea; 
            }
            .easylist-container .ui.pagination.menu { 
                justify-content: center; 
                margin-top: 20px; 
            }
        </style>';
        
        $html .= '<div class="easylist-container" id="' . $this->id . '_container">';
        
        // Toolbar
        $html .= $this->renderToolbar();
        
        // Filters
        if (!empty($this->filters)) {
            $html .= $this->renderFilters();
        }
        
        // Table
        $html .= '<div class="table-responsive">';
        $html .= '<table id="' . $this->id . '" class="' . $this->classes . '">';
        
        // Header
        $html .= $this->renderHeader();
        
        // Body
        $html .= '<tbody>';
        if ($this->ajax) {
            $html .= '<tr><td colspan="' . count($this->columns) . '" class="center aligned">Lade Daten...</td></tr>';
        } else {
            $html .= $this->renderRows();
        }
        $html .= '</tbody>';
        
        // Footer
        if ($this->summaryRow) {
            $html .= $this->renderFooter();
        }
        
        $html .= '</table>';
        $html .= '</div>';
        
        // Pagination
        if ($this->paginate) {
            $html .= $this->renderPagination();
        }
        
        $html .= '</div>';
        
        // JavaScript
        $html .= $this->renderScript();
        
        return $html;
    }
    
    /**
     * Display the list
     */
    public function display() {
        echo $this->render();
    }
    
    /**
     * Render toolbar
     */
    private function renderToolbar() {
        $html = '<div class="ui secondary menu easylist-toolbar">';
        
        // Search
        if ($this->searchable) {
            $html .= '<div class="item">';
            $html .= '<div class="ui icon input">';
            $html .= '<input type="text" id="' . $this->id . '_search" placeholder="' . $this->searchPlaceholder . '">';
            $html .= '<i class="search icon"></i>';
            $html .= '</div>';
            $html .= '</div>';
        }
        
        // Bulk actions
        if (!empty($this->bulkActions)) {
            $html .= '<div class="item">';
            $html .= '<div class="ui compact form">';
            $html .= '<div class="inline fields" style="margin: 0;">';
            $html .= '<select class="ui dropdown compact" id="' . $this->id . '_bulk_action" style="min-width: 150px;">';
            $html .= '<option value="">Bulk-Aktionen</option>';
            foreach ($this->bulkActions as $key => $action) {
                $icon = isset($action['icon']) ? '<i class="' . $action['icon'] . ' icon"></i> ' : '';
                $html .= '<option value="' . $key . '">' . $icon . $action['label'] . '</option>';
            }
            $html .= '</select>';
            $html .= '<button class="ui primary button compact" id="' . $this->id . '_bulk_apply" style="margin-left: 10px;" disabled>';
            $html .= '<i class="check icon"></i> Anwenden';
            $html .= '</button>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }
        
        // Export buttons
        if ($this->exportable) {
            $html .= '<div class="right menu">';
            foreach ($this->exportFormats as $format) {
                $icon = [
                    'csv' => 'file excel',
                    'json' => 'code',
                    'excel' => 'file excel',
                    'pdf' => 'file pdf'
                ][$format] ?? 'download';
                
                $html .= '<a class="item export-btn" data-format="' . $format . '">';
                $html .= '<i class="' . $icon . ' icon"></i> ' . strtoupper($format);
                $html .= '</a>';
            }
            $html .= '</div>';
        }
        
        $html .= '</div>';
        return $html;
    }
    
    /**
     * Render filters
     */
    private function renderFilters() {
        $html = '<div class="ui form segment easylist-filters" style="padding: 10px 12px; margin-bottom: 15px; background: #f8f9fa; width: 100%;">';
        $html .= '<div class="inline fields" style="margin: 0; display: flex; flex-wrap: wrap; align-items: center; gap: 12px; width: 100%; justify-content: space-between;">';
        
        // Create filter fields container
        $html .= '<div style="display: flex; flex-wrap: wrap; align-items: center; gap: 12px; flex: 1;">';
        
        foreach ($this->filters as $key => $filter) {
            // Find column label  
            $columnLabel = $key;
            foreach ($this->columns as $column) {
                if ($column['key'] === $key) {
                    $columnLabel = strip_tags($column['label']); // Remove HTML from label
                    break;
                }
            }
            
            $html .= '<div class="field" style="margin: 0; display: flex; align-items: center; gap: 6px;">';
            $html .= '<label style="font-size: 0.85em; color: #555; white-space: nowrap;">' . $columnLabel . ':</label>';
            
            if ($filter['type'] === 'select') {
                $html .= '<select class="ui compact dropdown filter-input" data-column="' . $key . '" style="min-width: 140px; font-size: 0.9em;">';
                // Check if options are associative array or simple array
                if (isset($filter['options']) && is_array($filter['options'])) {
                    // If first element is associative
                    if (array_keys($filter['options']) !== range(0, count($filter['options']) - 1)) {
                        foreach ($filter['options'] as $value => $label) {
                            $html .= '<option value="' . htmlspecialchars($value) . '">' . htmlspecialchars($label) . '</option>';
                        }
                    } else {
                        $html .= '<option value="">Alle</option>';
                        foreach ($filter['options'] as $option) {
                            $html .= '<option value="' . htmlspecialchars($option) . '">' . htmlspecialchars($option) . '</option>';
                        }
                    }
                }
                $html .= '</select>';
            } elseif ($filter['type'] === 'date') {
                $html .= '<input type="date" class="filter-input" data-column="' . $key . '" style="padding: 7px 10px; border: 1px solid #d4d4d5; border-radius: 4px; font-size: 0.9em; height: 38px;">';
            } elseif ($filter['type'] === 'range') {
                $html .= '<div style="display: flex; align-items: center; gap: 4px;">';
                $html .= '<input type="number" class="filter-input filter-min" data-column="' . $key . '" placeholder="Min" style="width: 70px; padding: 7px 8px; border: 1px solid #d4d4d5; border-radius: 4px; font-size: 0.9em; height: 38px;">';
                $html .= '<span style="color: #999; font-size: 0.9em;">-</span>';
                $html .= '<input type="number" class="filter-input filter-max" data-column="' . $key . '" placeholder="Max" style="width: 70px; padding: 7px 8px; border: 1px solid #d4d4d5; border-radius: 4px; font-size: 0.9em; height: 38px;">';
                $html .= '</div>';
            } else {
                $html .= '<input type="text" class="filter-input" data-column="' . $key . '" placeholder="' . ($filter['placeholder'] ?? 'Filter...') . '" style="padding: 7px 10px; border: 1px solid #d4d4d5; border-radius: 4px; width: 140px; font-size: 0.9em; height: 38px;">';
            }
            
            $html .= '</div>';
        }
        
        $html .= '</div>'; // Close filter fields container
        
        if (!empty($this->filters)) {
            $html .= '<div class="field" style="margin: 0;">';
            $html .= '<button class="ui small basic button" id="' . $this->id . '_clear_filters" style="padding: 8px 12px;" title="Filter zurücksetzen"><i class="eraser icon"></i> Reset</button>';
            $html .= '</div>';
        }
        
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Render table header
     */
    private function renderHeader() {
        $html = '<thead><tr>';
        
        foreach ($this->columns as $column) {
            $classes = [];
            if (isset($column['headerClass'])) {
                $classes[] = $column['headerClass'];
            }
            if ($column['sortable'] && $this->sortable) {
                $classes[] = 'sortable';
            }
            if ($column['align']) {
                $classes[] = $column['align'] . ' aligned';
            }
            
            $html .= '<th class="' . implode(' ', $classes) . '" data-column="' . $column['key'] . '">';
            $html .= $column['label'];
            if ($column['sortable'] && $this->sortable) {
                $html .= ' <i class="sort icon"></i>';
            }
            $html .= '</th>';
        }
        
        $html .= '</tr></thead>';
        return $html;
    }
    
    /**
     * Render table rows
     */
    private function renderRows() {
        $html = '';
        
        if (empty($this->data)) {
            $html .= '<tr><td colspan="' . count($this->columns) . '" class="center aligned">' . $this->emptyMessage . '</td></tr>';
            return $html;
        }
        
        // Apply pagination if enabled
        $dataToRender = $this->data;
        if ($this->paginate) {
            $start = 0;  // First page
            $end = min($this->pageSize, count($this->data));
            $dataToRender = array_slice($this->data, $start, $end);
        }
        
        foreach ($dataToRender as $row) {
            $rowClass = '';
            if ($this->rowClass && is_callable($this->rowClass)) {
                $rowClass = call_user_func($this->rowClass, $row);
            }
            
            $html .= '<tr class="' . $rowClass . '" data-id="' . ($row['id'] ?? '') . '">';
            
            foreach ($this->columns as $column) {
                $rawValue = $row[$column['key']] ?? '';
                $displayValue = $rawValue;
                
                // Apply format for display
                if (isset($column['format']) && is_callable($column['format'])) {
                    $displayValue = call_user_func($column['format'], $rawValue, $row);
                } elseif (isset($column['template'])) {
                    $displayValue = $this->parseTemplate($column['template'], $row);
                }
                
                $classes = [];
                if (isset($column['class'])) {
                    $classes[] = $column['class'];
                }
                if ($column['align']) {
                    $classes[] = $column['align'] . ' aligned';
                }
                
                // Store raw value as data attribute for JavaScript to use
                $dataAttr = '';
                if ($column['key'] !== '_select' && $column['key'] !== '_actions') {
                    $dataAttr = ' data-value="' . htmlspecialchars($rawValue, ENT_QUOTES) . '"';
                }
                
                $html .= '<td class="' . implode(' ', $classes) . '"' . $dataAttr . '>' . $displayValue . '</td>';
            }
            
            $html .= '</tr>';
        }
        
        return $html;
    }
    
    /**
     * Render footer
     */
    private function renderFooter() {
        $html = '<tfoot><tr>';
        
        foreach ($this->columns as $column) {
            $html .= '<th>';
            // Calculate summaries if needed
            $html .= '</th>';
        }
        
        $html .= '</tr></tfoot>';
        return $html;
    }
    
    /**
     * Render pagination
     */
    private function renderPagination() {
        $html = '<div class="ui pagination menu" id="' . $this->id . '_pagination">';
        $html .= '<a class="icon item" data-page="prev"><i class="left chevron icon"></i></a>';
        // Page numbers will be generated by JavaScript
        $html .= '<a class="icon item" data-page="next"><i class="right chevron icon"></i></a>';
        $html .= '</div>';
        return $html;
    }
    
    /**
     * Parse template
     */
    private function parseTemplate($template, $data) {
        return preg_replace_callback('/\{(\w+)\}/', function($matches) use ($data) {
            return $data[$matches[1]] ?? '';
        }, $template);
    }
    
    /**
     * Render JavaScript
     */
    private function renderScript() {
        // Filter out non-serializable parts like PHP callbacks
        $cleanColumns = array_map(function($column) {
            // Handle HTML in labels by escaping it properly
            $label = $column['label'];
            // Remove problematic HTML from labels for JavaScript
            if (strpos($label, '<') !== false) {
                $label = strip_tags($label);
            }
            
            $clean = [
                'key' => $column['key'],
                'label' => $label,
                'type' => $column['type'] ?? 'text',
                'align' => $column['align'] ?? null,
                'sortable' => $column['sortable'] ?? true,
                'searchable' => $column['searchable'] ?? true,
                'width' => $column['width'] ?? null
            ];
            
            // Only include filter if it's serializable
            if (isset($column['filter']) && is_array($column['filter'])) {
                $clean['filter'] = $column['filter'];
            }
            
            return $clean;
        }, $this->columns);
        
        // Prepare data for JavaScript with both raw and formatted values
        $cleanData = [];
        if (!$this->ajax && $this->data) {
            foreach ($this->data as $row) {
                $cleanRow = [];
                foreach ($this->columns as $column) {
                    $key = $column['key'];
                    if ($key === '_select' || $key === '_actions') continue;
                    
                    $rawValue = $row[$key] ?? '';
                    $cleanRow[$key] = $rawValue;
                    
                    // Also store formatted value if format function exists
                    if (isset($column['format']) && is_callable($column['format'])) {
                        $cleanRow[$key . '_formatted'] = call_user_func($column['format'], $rawValue, $row);
                    } elseif (isset($column['template'])) {
                        $cleanRow[$key . '_formatted'] = $this->parseTemplate($column['template'], $row);
                    }
                }
                // Always include ID
                if (isset($row['id'])) {
                    $cleanRow['id'] = $row['id'];
                }
                $cleanData[] = $cleanRow;
            }
        }
        
        $config = json_encode([
            'id' => $this->id,
            'ajax' => $this->ajax,
            'ajaxUrl' => $this->ajaxUrl,
            'searchable' => $this->searchable,
            'sortable' => $this->sortable,
            'paginate' => $this->paginate,
            'pageSize' => $this->pageSize,
            'selectable' => $this->selectable,
            'columns' => $cleanColumns,
            'filters' => $this->filters,
            'exportable' => $this->exportable,
            'exportFormats' => $this->exportFormats,
            'actions' => $this->actions,  // Include actions configuration
            'data' => $cleanData  // Include all data for JavaScript
        ]);
        
        $html = "\n<script>\n";
        $html .= "document.addEventListener('DOMContentLoaded', function() {\n";
        $html .= "  // Initialize EasyListHandler first\n";
        $html .= "  if (typeof EasyListHandler !== 'undefined') {\n";
        $html .= "    window.easyList_" . $this->id . " = new EasyListHandler(" . $config . ");\n";
        $html .= "  }\n";
        $html .= "  \n";
        $html .= "  // Then initialize Semantic UI dropdowns (handler will set up onChange)\n";
        $html .= "  if (typeof $ !== 'undefined' && $.fn.dropdown) {\n";
        $html .= "    setTimeout(function() {\n";
        $html .= "      $('#" . $this->id . "_container .ui.dropdown.filter-input').each(function() {\n";
        $html .= "        var select = $(this).find('select')[0];\n";
        $html .= "        if (select && select.dataset.column) {\n";
        $html .= "          $(this).dropdown({\n";
        $html .= "            onChange: function(value) {\n";
        $html .= "              if (window.easyList_" . $this->id . ") {\n";
        $html .= "                window.easyList_" . $this->id . ".setFilter(select.dataset.column, value);\n";
        $html .= "              }\n";
        $html .= "            }\n";
        $html .= "          });\n";
        $html .= "        }\n";
        $html .= "      });\n";
        $html .= "    }, 100);\n";
        $html .= "  }\n";
        $html .= "});\n";
        $html .= "</script>\n";
        
        return $html;
    }
}
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

    // Smartform2 compatibility
    private $db = null;
    private $query = '';
    private $buttons = [];
    private $buttonColumns = [
        'left' => ['title' => '', 'alignment' => 'left'],
        'right' => ['title' => '', 'alignment' => 'right']
    ];
    private $modals = [];
    private $externalButtons = [];
    private $searchableColumns = [];
    private $preparedStatementParams = [];
    private $preparedStatementTypes = '';
    
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
     *
     * @param string $query SQL query to execute
     * @param object|null $connection MySQLi connection (optional if setDatabase was called)
     * @param bool $enableSearch Enable automatic search conditions (default: false)
     * @param string $types Prepared statement types (e.g. "si" for string, int)
     * @param array $params Prepared statement parameters
     * @return $this
     */
    public function fromDatabase($query, $connection = null, $enableSearch = false, $types = '', $params = []) {
        if ($connection) {
            $this->db = $connection;
        }

        $this->query = $query;
        $this->preparedStatementTypes = $types;
        $this->preparedStatementParams = $params;

        // Execute query and load data
        if ($this->db) {
            $this->loadDataFromDatabase($enableSearch);
        }

        return $this;
    }

    /**
     * Set database connection and query (Smartform2 compatibility)
     *
     * @param object $db MySQLi database connection
     * @param string $query SQL query
     * @param bool $enableSearch Enable search functionality
     * @param string $types Prepared statement parameter types
     * @param array $params Prepared statement parameters
     * @return $this
     */
    public function setDatabase($db, $query, $enableSearch = false, $types = '', $params = []) {
        return $this->fromDatabase($query, $db, $enableSearch, $types, $params);
    }

    /**
     * Load data from database
     */
    private function loadDataFromDatabase($enableSearch = false) {
        if (!$this->db || !$this->query) {
            return;
        }

        $query = $this->query;

        // Add search conditions if enabled
        if ($enableSearch && $this->searchableColumns && !empty($_GET['search'])) {
            $searchTerm = $_GET['search'];
            $searchConditions = [];

            foreach ($this->searchableColumns as $column) {
                $searchConditions[] = "$column LIKE '%" . $this->db->real_escape_string($searchTerm) . "%'";
            }

            if (!empty($searchConditions)) {
                // Check if query already has WHERE clause
                if (stripos($query, 'WHERE') !== false) {
                    $query .= ' AND (' . implode(' OR ', $searchConditions) . ')';
                } else {
                    $query .= ' WHERE ' . implode(' OR ', $searchConditions);
                }
            }
        }

        // Execute query
        if (!empty($this->preparedStatementTypes) && !empty($this->preparedStatementParams)) {
            $stmt = $this->db->prepare($query);
            $stmt->bind_param($this->preparedStatementTypes, ...$this->preparedStatementParams);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $this->db->query($query);
        }

        if ($result) {
            $this->data = [];
            while ($row = $result->fetch_assoc()) {
                $this->data[] = $row;
            }
        }
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
        // Smartform2 compatibility: 'formatter' is alias for 'format'
        if (isset($options['formatter']) && !isset($options['format'])) {
            $options['format'] = $options['formatter'];
            unset($options['formatter']);
        }

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
            'filter' => null,
            'allowHtml' => false
        ], $options);
        return $this;
    }

    /**
     * Add a column (Smartform2 compatibility)
     *
     * @param string $key Column key in data
     * @param string $label Column label/header
     * @param array $options Column options (formatter, allowHtml, width, etc.)
     * @return $this
     */
    public function addColumn($key, $label, $options = []) {
        return $this->column($key, $label, $options);
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
            'multiple' => false,
            'defaultValue' => null
        ], $options);
        return $this;
    }

    /**
     * Add filter (Smartform2 compatibility)
     *
     * @param string $key Filter key
     * @param string $label Filter label
     * @param array $options Filter options (for select type)
     * @param array $config Additional configuration (defaultValue, etc.)
     * @return $this
     */
    public function addFilter($key, $label, $options = [], $config = []) {
        $this->filters[$key] = array_merge([
            'type' => 'select',
            'label' => $label,
            'options' => $options,
            'defaultValue' => $config['defaultValue'] ?? null
        ], $config);
        return $this;
    }

    /**
     * Set searchable columns
     *
     * @param array $columns Array of column names that should be searchable
     * @return $this
     */
    public function setSearchableColumns($columns) {
        $this->searchableColumns = $columns;
        return $this;
    }

    /**
     * Add button (Smartform2 compatibility)
     *
     * @param string $id Button ID
     * @param array $config Button configuration
     * @return $this
     */
    public function addButton($id, $config) {
        $this->buttons[$id] = array_merge([
            'icon' => 'edit',
            'position' => 'left',
            'class' => 'ui mini button',
            'modalId' => null,
            'callback' => null,
            'popup' => null,
            'params' => [],
            'conditions' => []
        ], $config);
        return $this;
    }

    /**
     * Add external button
     *
     * @param string $id Button ID
     * @param array $config Button configuration
     * @return $this
     */
    public function addExternalButton($id, $config) {
        $this->externalButtons[$id] = array_merge([
            'icon' => 'plus',
            'class' => 'ui button',
            'position' => 'inline',
            'alignment' => 'right',
            'title' => 'Button',
            'modalId' => null,
            'onclick' => null,
            'popup' => null
        ], $config);
        return $this;
    }

    /**
     * Add modal
     *
     * @param string $id Modal ID
     * @param array $config Modal configuration
     * @return $this
     */
    public function addModal($id, $config) {
        $this->modals[$id] = array_merge([
            'title' => 'Modal',
            'content' => '',
            'size' => 'small',
            'method' => 'GET',
            'buttons' => [],
            'scrolling' => false
        ], $config);
        return $this;
    }

    /**
     * Set button column title
     *
     * @param string $position 'left' or 'right'
     * @param string $title Column title
     * @param string $alignment Column alignment
     * @return $this
     */
    public function setButtonColumnTitle($position, $title = '', $alignment = 'center') {
        if (isset($this->buttonColumns[$position])) {
            $this->buttonColumns[$position] = [
                'title' => $title,
                'alignment' => $alignment
            ];
        }
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

        // Modals
        if (!empty($this->modals)) {
            $html .= $this->renderModals();
        }

        // JavaScript
        $html .= $this->renderScript();

        return $html;
    }

    /**
     * Alias for render() - Smartform2 compatibility
     */
    public function generateList() {
        return $this->render();
    }

    /**
     * Render modals
     */
    private function renderModals() {
        $html = '';

        foreach ($this->modals as $modalId => $modal) {
            $size = $modal['size'] ?? 'small';
            $scrolling = ($modal['scrolling'] ?? false) ? ' scrolling' : '';

            $html .= '<div class="ui ' . $size . ' modal' . $scrolling . '" id="' . $modalId . '">';
            $html .= '<i class="close icon"></i>';
            $html .= '<div class="header">' . $modal['title'] . '</div>';
            $html .= '<div class="content" id="' . $modalId . '_content">';
            $html .= '<div class="ui active centered inline loader"></div>';
            $html .= '</div>';

            if (!empty($modal['buttons'])) {
                $html .= '<div class="actions">';
                foreach ($modal['buttons'] as $buttonKey => $button) {
                    $class = $button['class'] ?? 'basic';
                    $icon = isset($button['icon']) ? '<i class="' . $button['icon'] . ' icon"></i> ' : '';
                    $text = $button['text'] ?? ucfirst($buttonKey);

                    $html .= '<div class="ui ' . $class . ' button">';
                    $html .= $icon . $text;
                    $html .= '</div>';
                }
                $html .= '</div>';
            }

            $html .= '</div>';
        }

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

        $html .= '<div class="right menu">';

        // External buttons
        if (!empty($this->externalButtons)) {
            foreach ($this->externalButtons as $buttonId => $button) {
                $popupAttr = '';
                if (isset($button['popup'])) {
                    $popupText = is_array($button['popup']) ? ($button['popup']['content'] ?? '') : $button['popup'];
                    $popupAttr = ' data-tooltip="' . htmlspecialchars($popupText) . '"';
                }

                if ($button['modalId']) {
                    $html .= '<a class="item" onclick="openModal(\'' . $button['modalId'] . '\')"' . $popupAttr . '>';
                } elseif ($button['onclick']) {
                    $html .= '<a class="item" onclick="' . $button['onclick'] . '"' . $popupAttr . '>';
                } else {
                    $html .= '<a class="item"' . $popupAttr . '>';
                }

                $html .= '<i class="' . $button['icon'] . ' icon"></i> ';
                $html .= $button['title'];
                $html .= '</a>';
            }
        }

        // Export buttons
        if ($this->exportable) {
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
        }

        $html .= '</div>'; // Close right menu
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

        // Left button column
        if ($this->hasButtonsInPosition('left')) {
            $config = $this->buttonColumns['left'];
            $html .= '<th class="' . $config['alignment'] . ' aligned button-cell">';
            $html .= $config['title'];
            $html .= '</th>';
        }

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

        // Right button column
        if ($this->hasButtonsInPosition('right')) {
            $config = $this->buttonColumns['right'];
            $html .= '<th class="' . $config['alignment'] . ' aligned button-cell">';
            $html .= $config['title'];
            $html .= '</th>';
        }

        $html .= '</tr></thead>';
        return $html;
    }

    /**
     * Check if there are buttons in the given position
     */
    private function hasButtonsInPosition($position) {
        foreach ($this->buttons as $button) {
            if ($button['position'] === $position) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Render table rows
     */
    private function renderRows() {
        $html = '';

        $totalColumns = count($this->columns);
        if ($this->hasButtonsInPosition('left')) $totalColumns++;
        if ($this->hasButtonsInPosition('right')) $totalColumns++;

        if (empty($this->data)) {
            $html .= '<tr><td colspan="' . $totalColumns . '" class="center aligned">' . $this->emptyMessage . '</td></tr>';
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

            // Left button column
            if ($this->hasButtonsInPosition('left')) {
                $html .= '<td class="button-cell">';
                $html .= $this->renderButtonsForRow($row, 'left');
                $html .= '</td>';
            }

            foreach ($this->columns as $column) {
                $rawValue = $row[$column['key']] ?? '';
                $displayValue = $rawValue;

                // Apply format for display
                if (isset($column['format']) && is_callable($column['format'])) {
                    $displayValue = call_user_func($column['format'], $rawValue, $row);
                } elseif (isset($column['template'])) {
                    $displayValue = $this->parseTemplate($column['template'], $row);
                }

                // Escape HTML unless allowHtml is true
                if (!($column['allowHtml'] ?? false) && $column['key'] !== '_select' && $column['key'] !== '_actions') {
                    $displayValue = htmlspecialchars($displayValue, ENT_QUOTES);
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

            // Right button column
            if ($this->hasButtonsInPosition('right')) {
                $html .= '<td class="button-cell">';
                $html .= $this->renderButtonsForRow($row, 'right');
                $html .= '</td>';
            }

            $html .= '</tr>';
        }

        return $html;
    }

    /**
     * Render buttons for a specific row
     */
    private function renderButtonsForRow($row, $position) {
        $html = '';

        foreach ($this->buttons as $buttonId => $button) {
            if ($button['position'] !== $position) {
                continue;
            }

            // Check conditions
            $show = true;
            if (!empty($button['conditions'])) {
                foreach ($button['conditions'] as $condition) {
                    if (is_callable($condition) && !$condition($row)) {
                        $show = false;
                        break;
                    }
                }
            }

            if (!$show) {
                continue;
            }

            // Build button attributes
            $classes = $button['class'];
            $icon = $button['icon'];
            $popup = $button['popup'];

            // Build data attributes from params
            $dataAttrs = '';
            if (!empty($button['params'])) {
                foreach ($button['params'] as $paramKey => $rowKey) {
                    $value = $row[$rowKey] ?? '';
                    $dataAttrs .= ' data-' . str_replace('_', '-', $paramKey) . '="' . htmlspecialchars($value, ENT_QUOTES) . '"';
                }
            }

            // Button HTML
            if ($button['modalId']) {
                // Build params string for modal
                $params = [];
                if (!empty($button['params'])) {
                    foreach ($button['params'] as $paramKey => $rowKey) {
                        $params[] = $paramKey . '=' . urlencode($row[$rowKey] ?? '');
                    }
                }
                $paramString = !empty($params) ? '&' . implode('&', $params) : '';

                $popupAttr = $popup ? ' data-tooltip="' . (is_array($popup) ? htmlspecialchars($popup['content'] ?? '') : htmlspecialchars($popup)) . '"' : '';

                $html .= '<button class="' . $classes . '" ';
                $html .= 'onclick="openModal(\'' . $button['modalId'] . '\', \'' . $paramString . '\')"';
                $html .= $dataAttrs . $popupAttr . '>';
                $html .= '<i class="' . $icon . ' icon"></i>';
                $html .= '</button>';
            } elseif ($button['callback']) {
                // JavaScript callback
                $params = [];
                if (!empty($button['params'])) {
                    foreach ($button['params'] as $paramKey => $rowKey) {
                        $params[$paramKey] = $row[$rowKey] ?? '';
                    }
                }
                $paramsJson = json_encode($params);

                $popupAttr = $popup ? ' data-tooltip="' . (is_array($popup) ? htmlspecialchars($popup['content'] ?? '') : htmlspecialchars($popup)) . '"' : '';

                $html .= '<button class="' . $classes . '" ';
                $html .= 'onclick="' . $button['callback'] . '(' . $paramsJson . ')"';
                $html .= $dataAttrs . $popupAttr . '>';
                $html .= '<i class="' . $icon . ' icon"></i>';
                $html .= '</button>';
            }
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

        // openModal function for modal support
        if (!empty($this->modals)) {
            $html .= "// Modal handling function\n";
            $html .= "function openModal(modalId, params) {\n";
            $html .= "  var modal = $('#' + modalId);\n";
            $html .= "  if (!modal.length) return;\n";
            $html .= "  \n";
            $html .= "  // Find modal config\n";
            $html .= "  var modalConfig = " . json_encode($this->modals) . ";\n";
            $html .= "  var config = modalConfig[modalId];\n";
            $html .= "  if (!config) return;\n";
            $html .= "  \n";
            $html .= "  // Load content\n";
            $html .= "  var contentUrl = config.content;\n";
            $html .= "  if (params) {\n";
            $html .= "    contentUrl += (contentUrl.indexOf('?') > -1 ? '&' : '?') + params;\n";
            $html .= "  }\n";
            $html .= "  \n";
            $html .= "  modal.modal({\n";
            $html .= "    onShow: function() {\n";
            $html .= "      $('#' + modalId + '_content').html('<div class=\"ui active centered inline loader\"></div>');\n";
            $html .= "      $.ajax({\n";
            $html .= "        url: contentUrl,\n";
            $html .= "        method: config.method || 'GET',\n";
            $html .= "        success: function(response) {\n";
            $html .= "          $('#' + modalId + '_content').html(response);\n";
            $html .= "          // Initialize Semantic UI components in loaded content\n";
            $html .= "          $('#' + modalId + '_content .ui.dropdown').dropdown();\n";
            $html .= "          $('#' + modalId + '_content .ui.checkbox').checkbox();\n";
            $html .= "        },\n";
            $html .= "        error: function() {\n";
            $html .= "          $('#' + modalId + '_content').html('<div class=\"ui negative message\">Fehler beim Laden des Inhalts</div>');\n";
            $html .= "        }\n";
            $html .= "      });\n";
            $html .= "    }\n";
            $html .= "  }).modal('show');\n";
            $html .= "}\n\n";
        }

        $html .= "document.addEventListener('DOMContentLoaded', function() {\n";
        $html .= "  // Initialize EasyListHandler first\n";
        $html .= "  if (typeof EasyListHandler !== 'undefined') {\n";
        $html .= "    window.easyList_" . $this->id . " = new EasyListHandler(" . $config . ");\n";
        $html .= "  }\n";
        $html .= "  \n";
        $html .= "  // Initialize Semantic UI components\n";
        $html .= "  if (typeof $ !== 'undefined') {\n";
        $html .= "    // Initialize tooltips\n";
        $html .= "    $('[data-tooltip]').popup();\n";
        $html .= "    \n";
        $html .= "    // Initialize dropdowns with onChange\n";
        $html .= "    if ($.fn.dropdown) {\n";
        $html .= "      setTimeout(function() {\n";
        $html .= "        $('#" . $this->id . "_container .ui.dropdown.filter-input').each(function() {\n";
        $html .= "          var select = $(this).find('select')[0];\n";
        $html .= "          if (select && select.dataset.column) {\n";
        $html .= "            $(this).dropdown({\n";
        $html .= "              onChange: function(value) {\n";
        $html .= "                if (window.easyList_" . $this->id . ") {\n";
        $html .= "                  window.easyList_" . $this->id . ".setFilter(select.dataset.column, value);\n";
        $html .= "                }\n";
        $html .= "              }\n";
        $html .= "            });\n";
        $html .= "          }\n";
        $html .= "        });\n";
        $html .= "      }, 100);\n";
        $html .= "    }\n";
        $html .= "  }\n";
        $html .= "});\n";
        $html .= "</script>\n";

        return $html;
    }
}
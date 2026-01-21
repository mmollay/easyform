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
    private $exportable = false;
    private $exportFormats = ['csv', 'json', 'excel'];
    private $ajax = false;
    private $ajaxUrl = '';
    private $classes = 'ui celled striped table';
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
    private $hideToolbar = false;
    private $searchFieldStyle = [];
    private $tableBorderStyle = 'default';
    private $buttonGrouping = false;

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
    private $maxWidth = null;  // Container max-width (e.g., '1200px')
    private $containerAlign = null;  // Container alignment: 'left', 'center', 'right'
    private $showInfoFooter = false;  // Show footer with entry count and page info

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
            'allowHtml' => false,
            'icon' => null,         // Icon class (e.g., 'user', 'envelope', 'building')
            'iconOnly' => false     // Show only icon without label text
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
        // Support both 'callback' (smartform2 compat) and 'onclick'
        if (isset($config['callback']) && !isset($config['onclick'])) {
            $config['onclick'] = $config['callback'];
        }
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
     * Set maximum container width
     *
     * @param string $width Max width (e.g., '1200px', '80%', '60em')
     * @return $this
     */
    public function maxWidth($width) {
        $this->maxWidth = $width;
        return $this;
    }

    /**
     * Set container alignment
     *
     * @param string $align Alignment: 'left', 'center', 'right'
     * @return $this
     */
    public function align($align) {
        $validAligns = ['left', 'center', 'right'];
        if (in_array($align, $validAligns)) {
            $this->containerAlign = $align;
        }
        return $this;
    }

    /**
     * Show info footer with entry count and page info
     *
     * @param bool $show Enable/disable info footer
     * @return $this
     */
    public function showInfoFooter($show = true) {
        $this->showInfoFooter = $show;
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
     * Enable row selection with checkboxes or radio buttons
     *
     * @param bool $enabled Enable selection
     * @param string $type 'checkbox' or 'radio'
     * @param string $idField The data field to use as row ID (default: 'id')
     * @return $this
     */
    public function selectable($enabled = true, $type = 'checkbox', $idField = 'id') {
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
                    'template' => '<input type="' . $type . '" class="row-select" value="{' . $idField . '}">'
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
     * Hide toolbar completely
     */
    public function hideToolbar($hide = true) {
        $this->hideToolbar = $hide;
        return $this;
    }

    /**
     * Set search field styling
     * @param array $styles Array of CSS properties (e.g., ['border-radius' => '20px'])
     */
    public function searchFieldStyle($styles = []) {
        $this->searchFieldStyle = $styles;
        return $this;
    }

    /**
     * Set table border style
     * @param string $style 'default', 'thin', or 'none'
     */
    public function tableBorderStyle($style = 'default') {
        $this->tableBorderStyle = $style;
        return $this;
    }

    /**
     * Enable button grouping (visually group left/right buttons)
     */
    public function buttonGrouping($enabled = true) {
        $this->buttonGrouping = $enabled;
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
        
        $html = '';

        // Inline CSS for consistent toolbar styling
        $html .= '<style>
            .easylist-toolbar .ui.dropdown {
                min-height: 38px !important;
                padding: 0 12px !important;
                display: flex !important;
                align-items: center !important;
            }
            .easylist-toolbar .ui.dropdown > .text {
                line-height: 38px !important;
            }
            .easylist-toolbar .ui.button {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
            .easylist-toolbar .ui.icon.input > input {
                box-sizing: border-box !important;
            }
            .easylist-toolbar .ui.icon.input > i.icon {
                pointer-events: none;
            }
        </style>';

        // Container with optional max-width and alignment
        $styles = [];
        if ($this->maxWidth) {
            $styles[] = 'max-width: ' . $this->maxWidth;
        }
        if ($this->containerAlign === 'center') {
            $styles[] = 'margin-left: auto';
            $styles[] = 'margin-right: auto';
        } elseif ($this->containerAlign === 'right') {
            $styles[] = 'margin-left: auto';
        }
        $containerStyle = !empty($styles) ? ' style="' . implode('; ', $styles) . ';"' : '';
        $html .= '<div class="easylist-container" id="' . $this->id . '_container"' . $containerStyle . '>';

        // Toolbar (only if not hidden)
        if (!$this->hideToolbar) {
            $html .= $this->renderToolbar();
        }
        
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

        // Footer area (pagination + info footer)
        if ($this->paginate || $this->showInfoFooter) {
            $html .= '<div class="easylist-footer" style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">';

            // Info footer (left side)
            if ($this->showInfoFooter) {
                $totalEntries = count($this->data);
                $html .= '<div class="easylist-info" id="' . $this->id . '_info" style="color: #666; font-size: 0.9em;">';
                $html .= 'Gesamt: <strong>' . $totalEntries . '</strong> Einträge';
                if ($this->paginate && $totalEntries > 0) {
                    $totalPages = ceil($totalEntries / $this->pageSize);
                    $html .= ' | Seite <span id="' . $this->id . '_current_page">1</span> von <span id="' . $this->id . '_total_pages">' . $totalPages . '</span>';
                }
                $html .= '</div>';
            } else {
                $html .= '<div></div>'; // Spacer for flex alignment
            }

            // Pagination (right side)
            if ($this->paginate) {
                $html .= $this->renderPagination();
            }

            $html .= '</div>';
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
            $isScrolling = $modal['scrolling'] ?? false;
            $scrollingModal = $isScrolling ? ' scrolling' : '';
            $scrollingContent = $isScrolling ? 'scrolling content' : 'content';
            $width = $modal['width'] ?? null;
            $style = $width ? ' style="width: ' . $width . '; max-width: 95vw;"' : '';

            $html .= '<div class="ui ' . $size . ' modal' . $scrollingModal . '" id="' . $modalId . '"' . $style . '>';
            $html .= '<i class="close icon"></i>';
            $html .= '<div class="header">' . $modal['title'] . '</div>';
            $html .= '<div class="' . $scrollingContent . '" id="' . $modalId . '_content">';
            $html .= '<div class="ui active centered inline loader"></div>';
            $html .= '</div>';

            if (!empty($modal['buttons'])) {
                $html .= '<div class="actions">';
                foreach ($modal['buttons'] as $buttonKey => $button) {
                    $class = $button['class'] ?? 'basic';
                    $icon = isset($button['icon']) ? '<i class="' . $button['icon'] . ' icon"></i> ' : '';
                    $label = $button['label'] ?? $button['text'] ?? ucfirst($buttonKey);
                    $action = $button['action'] ?? null;

                    // Determine onclick handler based on action
                    $onclick = '';
                    if ($action === 'submit') {
                        // Submit the form inside the modal
                        $onclick = ' onclick="var form = document.querySelector(\'#' . $modalId . '_content form\'); if(form) { form.requestSubmit ? form.requestSubmit() : form.submit(); }"';
                    } elseif ($action === 'close') {
                        // Close the modal
                        $onclick = ' onclick="$(\'#' . $modalId . '\').modal(\'hide\')"';
                    } elseif (!empty($button['onclick'])) {
                        // Custom onclick
                        $onclick = ' onclick="' . htmlspecialchars($button['onclick']) . '"';
                    }

                    $html .= '<div class="ui ' . $class . ' button"' . $onclick . '>';
                    $html .= $icon . $label;
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
        // Consistent height for all toolbar elements
        $toolbarHeight = '38px';

        $html = '<div class="ui secondary menu easylist-toolbar" style="display: flex; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 1em;">';

        // Search
        if ($this->searchable) {
            $html .= '<div class="item" style="margin: 0;">';
            $html .= '<div class="ui icon input" style="height: ' . $toolbarHeight . ';">';
            $html .= '<input type="text" id="' . $this->id . '_search" placeholder="' . $this->searchPlaceholder . '" style="border-radius: 20px; height: ' . $toolbarHeight . '; padding: 0 35px 0 14px;">';
            $html .= '<i class="search icon" style="height: ' . $toolbarHeight . '; display: flex; align-items: center;"></i>';
            $html .= '</div>';
            $html .= '</div>';
        }

        // Bulk actions
        if (!empty($this->bulkActions)) {
            $html .= '<div class="item" style="margin: 0; display: flex; align-items: center; gap: 8px;">';
            $html .= '<select class="ui dropdown compact" id="' . $this->id . '_bulk_action" style="min-width: 150px; height: ' . $toolbarHeight . ';">';
            $html .= '<option value="">Bulk-Aktionen</option>';
            foreach ($this->bulkActions as $key => $action) {
                $icon = isset($action['icon']) ? '<i class="' . $action['icon'] . ' icon"></i> ' : '';
                $html .= '<option value="' . $key . '">' . $icon . $action['label'] . '</option>';
            }
            $html .= '</select>';
            $html .= '<button class="ui primary button compact" id="' . $this->id . '_bulk_apply" style="height: ' . $toolbarHeight . '; margin: 0;" disabled>';
            $html .= '<i class="check icon"></i> Anwenden';
            $html .= '</button>';
            $html .= '</div>';
        }

        // Spacer to push right menu to the end
        $html .= '<div style="flex: 1;"></div>';

        // Right side elements (external buttons + export)
        $html .= '<div class="right-toolbar" style="display: flex; align-items: center; gap: 8px; margin: 0;">';

        // External buttons
        if (!empty($this->externalButtons)) {
            foreach ($this->externalButtons as $buttonId => $button) {
                $popupAttr = '';
                if (isset($button['popup'])) {
                    $popupText = is_array($button['popup']) ? ($button['popup']['content'] ?? '') : $button['popup'];
                    $popupAttr = ' data-tooltip="' . htmlspecialchars($popupText) . '"';
                }

                if ($button['modalId']) {
                    $html .= '<button class="' . $button['class'] . '" onclick="openModal(\'' . $button['modalId'] . '\')"' . $popupAttr . ' style="height: ' . $toolbarHeight . '; margin: 0;">';
                } elseif ($button['onclick']) {
                    // Handle function callbacks - wrap with IIFE if it's a function definition
                    $onclick = $button['onclick'];
                    if (strpos($onclick, 'function') === 0) {
                        $onclick = '(' . $onclick . ')()';
                    }
                    $html .= '<button class="' . $button['class'] . '" onclick="' . htmlspecialchars($onclick) . '"' . $popupAttr . ' style="height: ' . $toolbarHeight . '; margin: 0;">';
                } else {
                    $html .= '<button class="' . $button['class'] . '"' . $popupAttr . ' style="height: ' . $toolbarHeight . '; margin: 0;">';
                }

                $html .= '<i class="' . $button['icon'] . ' icon"></i> ';
                $html .= $button['title'];
                $html .= '</button>';
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

                $html .= '<a class="ui button basic compact export-btn" data-format="' . $format . '" style="height: ' . $toolbarHeight . '; margin: 0; display: flex; align-items: center;">';
                $html .= '<i class="' . $icon . ' icon"></i> ' . strtoupper($format);
                $html .= '</a>';
            }
        }

        $html .= '</div>'; // Close right-toolbar
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
            // Use filter label if provided, otherwise find column label or use key
            $columnLabel = $filter['label'] ?? null;
            if (!$columnLabel) {
                foreach ($this->columns as $column) {
                    if ($column['key'] === $key) {
                        $columnLabel = strip_tags($column['label']); // Remove HTML from label
                        break;
                    }
                }
            }
            if (!$columnLabel) {
                $columnLabel = $key;
            }

            // Use contentId from GET (smartform2 listGenerator.js compatibility)
            $contentId = $_GET['contentId'] ?? $this->id;
            $filterId = 'filter_' . $contentId . '_' . $key;

            // Read filter value from filters array (smartform2 format) or direct GET param
            $currentValue = '';
            if (isset($_GET['filters']) && is_array($_GET['filters']) && isset($_GET['filters'][$key])) {
                $currentValue = $_GET['filters'][$key];
            } elseif (isset($_GET[$key])) {
                $currentValue = $_GET[$key];
            }
            $placeholder = $filter['placeholder'] ?? 'Bitte auswählen';

            $html .= '<div class="field" style="margin: 0; display: flex; align-items: center; gap: 6px;">';
            $html .= '<label style="font-size: 0.85em; color: #555; white-space: nowrap;">' . htmlspecialchars($columnLabel) . ':</label>';

            if ($filter['type'] === 'select') {
                // Fomantic UI Dropdown (clearable) - smartform2-compatible ID format
                $html .= '<div class="ui selection dropdown clearable" id="' . $filterId . '" style="min-width: 160px;">';
                $html .= '<input type="hidden" name="' . $key . '" value="' . htmlspecialchars($currentValue) . '">';
                $html .= '<i class="dropdown icon"></i>';
                $html .= '<i class="remove icon"></i>';

                // Check if HTML is allowed in this filter (default: true for dropdowns)
                $allowHtml = $filter['allowHtml'] ?? true;

                // Default text or selected value
                if ($currentValue !== '' && isset($filter['options'][$currentValue])) {
                    $displayText = $allowHtml ? $filter['options'][$currentValue] : htmlspecialchars($filter['options'][$currentValue]);
                    $html .= '<div class="text">' . $displayText . '</div>';
                } else {
                    $html .= '<div class="default text">' . htmlspecialchars($placeholder) . '</div>';
                }

                $html .= '<div class="menu">';
                if (isset($filter['options']) && is_array($filter['options'])) {
                    foreach ($filter['options'] as $value => $optLabel) {
                        $selected = ($currentValue !== '' && $currentValue == $value) ? 'active selected' : '';
                        $displayLabel = $allowHtml ? $optLabel : htmlspecialchars($optLabel);
                        $html .= '<div class="item ' . $selected . '" data-value="' . htmlspecialchars($value) . '">' . $displayLabel . '</div>';
                    }
                }
                $html .= '</div>'; // End menu
                $html .= '</div>'; // End dropdown
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
            // Use contentId from GET (smartform2 listGenerator.js compatibility)
            $resetContentId = $_GET['contentId'] ?? $this->id;
            $html .= '<div class="field" style="margin: 0;">';
            $html .= '<button class="ui small basic button" id="clear_filters_' . $resetContentId . '" style="padding: 8px 12px;" title="Filter zurücksetzen"><i class="eraser icon"></i> Reset</button>';
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

            // Build header content with optional icon
            $headerContent = '';
            if (!empty($column['icon'])) {
                $headerContent .= '<i class="' . $column['icon'] . ' icon"></i>';
                if (!($column['iconOnly'] ?? false)) {
                    $headerContent .= ' ';  // Space between icon and label
                }
            }
            if (!($column['iconOnly'] ?? false)) {
                $headerContent .= $column['label'];
            }
            $html .= $headerContent;

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
            $html .= '<tr class="easylist-empty-row"><td colspan="' . $totalColumns . '" class="center aligned" style="color: #666; padding: 30px;">';
            $html .= '<i class="inbox icon" style="margin-right: 6px; opacity: 0.6;"></i>';
            $html .= htmlspecialchars($this->emptyMessage);
            $html .= '</td></tr>';
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

        // Start Semantic UI button group if enabled
        if ($this->buttonGrouping) {
            $html .= '<div class="ui mini icon buttons">';
        }

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
                // Escape JSON for HTML attribute (double quotes become &quot;)
                $paramsJson = htmlspecialchars(json_encode($params), ENT_QUOTES);

                $popupAttr = $popup ? ' data-tooltip="' . (is_array($popup) ? htmlspecialchars($popup['content'] ?? '') : htmlspecialchars($popup)) . '"' : '';

                $html .= '<button class="' . $classes . '" ';
                $html .= 'onclick="' . $button['callback'] . '(' . $paramsJson . ')"';
                $html .= $dataAttrs . $popupAttr . '>';
                $html .= '<i class="' . $icon . ' icon"></i>';
                $html .= '</button>';
            }
        }

        // Close button group if enabled
        if ($this->buttonGrouping) {
            $html .= '</div>';
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
            'bulkActions' => $this->bulkActions,  // Include bulk actions for multi-select
            'data' => $cleanData,  // Include all data for JavaScript
            'showInfoFooter' => $this->showInfoFooter  // Enable info footer updates
        ]);
        
        $html = "\n<script>\n";

        // openModal function for modal support
        if (!empty($this->modals)) {
            $html .= "// Track pending AJAX requests and state per modal\n";
            $html .= "var modalAjaxRequests = {};\n";
            $html .= "var modalInitialized = {};\n";
            $html .= "var easylistModalConfig = " . json_encode($this->modals) . ";\n\n";

            $html .= "// Modal handling function - completely rewritten for reliability\n";
            $html .= "function openModal(modalId, params) {\n";
            $html .= "  var modal = $('#' + modalId);\n";
            $html .= "  if (!modal.length) { console.error('Modal not found:', modalId); return; }\n";
            $html .= "  \n";
            $html .= "  var config = easylistModalConfig[modalId];\n";
            $html .= "  if (!config) { console.error('Modal config not found:', modalId); return; }\n";
            $html .= "  \n";
            $html .= "  // Abort any pending AJAX request for this modal\n";
            $html .= "  if (modalAjaxRequests[modalId]) {\n";
            $html .= "    modalAjaxRequests[modalId].abort();\n";
            $html .= "    modalAjaxRequests[modalId] = null;\n";
            $html .= "  }\n";
            $html .= "  \n";
            $html .= "  // Build URL and data\n";
            $html .= "  var contentUrl = config.content;\n";
            $html .= "  var method = config.method || 'GET';\n";
            $html .= "  var ajaxData = null;\n";
            $html .= "  \n";
            $html .= "  if (params) {\n";
            $html .= "    params = String(params).replace(/^&/, '');\n";
            $html .= "    if (method === 'POST') {\n";
            $html .= "      ajaxData = {};\n";
            $html .= "      params.split('&').forEach(function(pair) {\n";
            $html .= "        var parts = pair.split('=');\n";
            $html .= "        if (parts.length === 2) {\n";
            $html .= "          ajaxData[decodeURIComponent(parts[0])] = decodeURIComponent(parts[1]);\n";
            $html .= "        }\n";
            $html .= "      });\n";
            $html .= "    } else {\n";
            $html .= "      contentUrl += (contentUrl.indexOf('?') > -1 ? '&' : '?') + params;\n";
            $html .= "    }\n";
            $html .= "  }\n";
            $html .= "  \n";
            $html .= "  // ALWAYS reset content to loader first - this is crucial!\n";
            $html .= "  destroyCKEditorInModal(modalId);\n";
            $html .= "  $('#' + modalId + '_content').html('<div class=\"ui active centered inline loader\" style=\"margin: 50px auto;\"></div>');\n";
            $html .= "  \n";
            $html .= "  // Function to load modal content via AJAX\n";
            $html .= "  function loadContent() {\n";
            $html .= "    modalAjaxRequests[modalId] = $.ajax({\n";
            $html .= "      url: contentUrl,\n";
            $html .= "      method: method,\n";
            $html .= "      data: ajaxData,\n";
            $html .= "      cache: false,\n";
            $html .= "      timeout: 30000,\n";
            $html .= "      success: function(response) {\n";
            $html .= "        modalAjaxRequests[modalId] = null;\n";
            $html .= "        $('#' + modalId + '_content').html(response);\n";
            $html .= "        // Execute inline scripts\n";
            $html .= "        $('#' + modalId + '_content script').each(function() {\n";
            $html .= "          var scriptContent = $(this).text();\n";
            $html .= "          if (scriptContent) {\n";
            $html .= "            try {\n";
            $html .= "              var script = document.createElement('script');\n";
            $html .= "              script.text = scriptContent;\n";
            $html .= "              document.head.appendChild(script).parentNode.removeChild(script);\n";
            $html .= "            } catch(e) { console.error('Script execution error:', e); }\n";
            $html .= "          }\n";
            $html .= "        });\n";
            $html .= "        $('#' + modalId + '_content .ui.dropdown').dropdown();\n";
            $html .= "        $('#' + modalId + '_content .ui.checkbox').checkbox();\n";
            $html .= "      },\n";
            $html .= "      error: function(xhr, status, error) {\n";
            $html .= "        modalAjaxRequests[modalId] = null;\n";
            $html .= "        if (status !== 'abort') {\n";
            $html .= "          var errorMsg = status === 'timeout' ? 'Zeitüberschreitung - Server antwortet nicht' : 'Fehler beim Laden des Inhalts';\n";
            $html .= "          if (xhr.responseText && xhr.responseText.length < 500) errorMsg += ': ' + xhr.responseText;\n";
            $html .= "          $('#' + modalId + '_content').html('<div class=\"ui negative message\"><i class=\"exclamation triangle icon\"></i> ' + errorMsg + '</div>');\n";
            $html .= "          console.error('Modal load error:', status, error, xhr.responseText);\n";
            $html .= "        }\n";
            $html .= "      }\n";
            $html .= "    });\n";
            $html .= "  }\n";
            $html .= "  \n";
            $html .= "  // Initialize modal only once with proper settings\n";
            $html .= "  if (!modalInitialized[modalId]) {\n";
            $html .= "    modal.modal({\n";
            $html .= "      detachable: false,\n";
            $html .= "      observeChanges: true,\n";
            $html .= "      autofocus: false,\n";
            $html .= "      closable: true,\n";
            $html .= "      onHidden: function() {\n";
            $html .= "        destroyCKEditorInModal(modalId);\n";
            $html .= "        // Abort any pending request when modal is closed\n";
            $html .= "        if (modalAjaxRequests[modalId]) {\n";
            $html .= "          modalAjaxRequests[modalId].abort();\n";
            $html .= "          modalAjaxRequests[modalId] = null;\n";
            $html .= "        }\n";
            $html .= "        // Ensure dimmer is removed (fallback for edge cases)\n";
            $html .= "        setTimeout(function() {\n";
            $html .= "          $('.ui.dimmer.modals.visible').removeClass('visible active');\n";
            $html .= "          $('body').removeClass('dimmed dimmable scrolling');\n";
            $html .= "        }, 100);\n";
            $html .= "      }\n";
            $html .= "    });\n";
            $html .= "    modalInitialized[modalId] = true;\n";
            $html .= "  }\n";
            $html .= "  \n";
            $html .= "  // Show modal and load content\n";
            $html .= "  modal.modal('show');\n";
            $html .= "  loadContent();\n";
            $html .= "}\n\n";

            // Helper function to destroy CKEditor instances
            $html .= "// Helper function to destroy CKEditor instances in a modal\n";
            $html .= "function destroyCKEditorInModal(modalId) {\n";
            $html .= "  var modalContent = document.getElementById(modalId + '_content');\n";
            $html .= "  if (!modalContent) return;\n";
            $html .= "  \n";
            $html .= "  // CKEditor 5: Find all editor instances and destroy them\n";
            $html .= "  if (typeof window.ckEditorInstances !== 'undefined') {\n";
            $html .= "    var editors = modalContent.querySelectorAll('.ck-editor, .ckeditor-container, [data-ckeditor-instance]');\n";
            $html .= "    editors.forEach(function(editorElement) {\n";
            $html .= "      var editorId = editorElement.id || editorElement.dataset.ckeditorInstance;\n";
            $html .= "      if (editorId && window.ckEditorInstances[editorId]) {\n";
            $html .= "        try {\n";
            $html .= "          window.ckEditorInstances[editorId].destroy();\n";
            $html .= "          delete window.ckEditorInstances[editorId];\n";
            $html .= "          console.log('Destroyed CKEditor instance:', editorId);\n";
            $html .= "        } catch (e) {\n";
            $html .= "          console.warn('Error destroying CKEditor:', e);\n";
            $html .= "        }\n";
            $html .= "      }\n";
            $html .= "    });\n";
            $html .= "  }\n";
            $html .= "  \n";
            $html .= "  // Fallback: Try to find CKEditor instances by checking for editorInstance property\n";
            $html .= "  var textareas = modalContent.querySelectorAll('textarea.ckeditor-field');\n";
            $html .= "  textareas.forEach(function(textarea) {\n";
            $html .= "    if (textarea.ckeditorInstance) {\n";
            $html .= "      try {\n";
            $html .= "        textarea.ckeditorInstance.destroy();\n";
            $html .= "        textarea.ckeditorInstance = null;\n";
            $html .= "        console.log('Destroyed CKEditor from textarea:', textarea.id);\n";
            $html .= "      } catch (e) {\n";
            $html .= "        console.warn('Error destroying CKEditor from textarea:', e);\n";
            $html .= "      }\n";
            $html .= "    }\n";
            $html .= "  });\n";
            $html .= "  \n";
            $html .= "  // Also clear the modal content to ensure clean state\n";
            $html .= "  // This removes any orphaned CKEditor DOM elements\n";
            $html .= "  var ckElements = modalContent.querySelectorAll('.ck, .ck-editor');\n";
            $html .= "  ckElements.forEach(function(el) {\n";
            $html .= "    el.remove();\n";
            $html .= "  });\n";
            $html .= "}\n\n";
        }

        // Use IIFE that works both on initial load and AJAX load
        $html .= "(function initEasyList_" . $this->id . "() {\n";
        $html .= "  function doInit() {\n";
        $html .= "    // Initialize EasyListHandler first\n";
        $html .= "    if (typeof EasyListHandler !== 'undefined') {\n";
        $html .= "      try {\n";
        $html .= "        // Destroy existing instance if it exists (for AJAX reloads)\n";
        $html .= "        if (window.easyList_" . $this->id . ") {\n";
        $html .= "          console.log('Reinitializing EasyList: " . $this->id . "');\n";
        $html .= "        }\n";
        $html .= "        window.easyList_" . $this->id . " = new EasyListHandler(" . $config . ");\n";
        $html .= "        console.log('EasyListHandler initialized: " . $this->id . "');\n";
        $html .= "      } catch (e) {\n";
        $html .= "        console.error('EasyListHandler initialization error:', e);\n";
        $html .= "      }\n";
        $html .= "    } else {\n";
        $html .= "      console.warn('EasyListHandler class not found');\n";
        $html .= "    }\n";
        $html .= "    \n";
        $html .= "    // Initialize Semantic UI components\n";
        $html .= "    if (typeof $ !== 'undefined') {\n";
        $html .= "      // Initialize tooltips\n";
        $html .= "      $('[data-tooltip]').popup();\n";
        $html .= "      \n";
        $html .= "      // Initialize dropdowns (filter dropdowns are handled by listGenerator.js)\n";
        $html .= "      if ($.fn.dropdown) {\n";
        $html .= "        setTimeout(function() {\n";
        $html .= "          // Initialize bulk action dropdown\n";
        $html .= "          var bulkDropdown = $('#" . $this->id . "_bulk_action');\n";
        $html .= "          if (bulkDropdown.length) {\n";
        $html .= "            bulkDropdown.dropdown();\n";
        $html .= "          }\n";
        $html .= "        }, 100);\n";
        $html .= "      }\n";
        $html .= "    }\n";
        $html .= "  }\n";
        $html .= "  \n";
        $html .= "  // Check if DOM is already ready (for AJAX loaded content)\n";
        $html .= "  if (document.readyState === 'loading') {\n";
        $html .= "    document.addEventListener('DOMContentLoaded', doInit);\n";
        $html .= "  } else {\n";
        $html .= "    // DOM already loaded - run immediately (AJAX load case)\n";
        $html .= "    doInit();\n";
        $html .= "  }\n";
        $html .= "})();\n";
        $html .= "</script>\n";

        return $html;
    }
}
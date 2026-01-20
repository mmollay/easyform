/**
 * EasyListHandler - JavaScript handler for EasyList
 * Handles multi-select, bulk actions, filtering, sorting, pagination
 */
class EasyListHandler {
  constructor(config) {
    this.config = config;
    this.id = config.id;
    this.data = config.data || [];
    this.filteredData = [...this.data];
    this.currentPage = 1;
    this.selectedRows = new Set();
    this.filters = {};
    this.allRows = []; // Store all table rows
    this.currentSearchTerm = ""; // Track current search term
    this.emptyStateRow = null; // Reference to empty state row

    this.init();
  }

  init() {
    // Cache all table rows
    this.cacheRows();

    // Initialize search
    if (this.config.searchable) {
      this.initSearch();
    }

    // Initialize sorting
    if (this.config.sortable) {
      this.initSorting();
    }

    // Initialize pagination
    if (this.config.paginate) {
      this.initPagination();
      this.renderPaginationButtons();
      this.applyPagination();
    }

    // Initialize selection
    if (this.config.selectable) {
      this.initSelection();
    }

    // Initialize filters
    if (this.config.filters) {
      this.initFilters();
    }

    // Initialize export
    if (this.config.exportable) {
      this.initExport();
    }

    // Initialize bulk actions
    if (this.config.selectable) {
      this.initBulkActions();
    }
  }

  cacheRows() {
    const table = $("#" + this.id);
    this.allRows = table.find("tbody tr").toArray();
  }

  initSelection() {
    const self = this;
    const table = $("#" + this.id);

    // Select all checkbox
    table.find("thead .select-all").on("change", function () {
      const isChecked = $(this).is(":checked");
      self.selectAll(isChecked);
    });

    // Individual row checkboxes
    table.on("change", "tbody .row-select", function () {
      const checkbox = $(this);
      const rowId = checkbox.val();

      if (checkbox.is(":checked")) {
        self.selectedRows.add(rowId);
      } else {
        self.selectedRows.delete(rowId);
      }

      self.updateSelectAllCheckbox();
      self.updateBulkActionButton();
    });
  }

  selectAll(checked) {
    const table = $("#" + this.id);
    const checkboxes = table.find("tbody tr:visible .row-select");

    checkboxes.each((index, checkbox) => {
      const $checkbox = $(checkbox);
      const rowId = $checkbox.val();

      $checkbox.prop("checked", checked);

      if (checked) {
        this.selectedRows.add(rowId);
      } else {
        this.selectedRows.delete(rowId);
      }
    });

    this.updateBulkActionButton();
  }

  updateSelectAllCheckbox() {
    const table = $("#" + this.id);
    const selectAll = table.find("thead .select-all");
    const checkboxes = table.find("tbody tr:visible .row-select");
    const checkedCount = checkboxes.filter(":checked").length;
    const totalCount = checkboxes.length;

    selectAll.prop("checked", checkedCount === totalCount && totalCount > 0);
    selectAll.prop(
      "indeterminate",
      checkedCount > 0 && checkedCount < totalCount,
    );
  }

  updateBulkActionButton() {
    const bulkButton = $("#" + this.id + "_bulk_apply");
    const bulkSelect = $("#" + this.id + "_bulk_action");

    if (bulkButton.length) {
      bulkButton.prop("disabled", this.selectedRows.size === 0);
    }

    // Auto-select first action if only one exists and items are selected
    if (bulkSelect.length && this.selectedRows.size > 0) {
      const options = bulkSelect.find("option").not('[value=""]');
      if (options.length === 1 && !bulkSelect.val()) {
        bulkSelect.val(options.first().val());
        // Trigger change for Semantic UI dropdown if used
        if (
          bulkSelect.parent().hasClass("ui") &&
          bulkSelect.parent().hasClass("dropdown")
        ) {
          bulkSelect.parent().dropdown("set selected", options.first().val());
        }
      }
    }
  }

  initBulkActions() {
    const self = this;
    const bulkButton = $("#" + this.id + "_bulk_apply");
    const bulkSelect = $("#" + this.id + "_bulk_action");

    bulkButton.on("click", function () {
      const action = bulkSelect.val();
      if (!action || self.selectedRows.size === 0) {
        return;
      }

      self.executeBulkAction(action);
    });
  }

  executeBulkAction(actionKey) {
    const bulkActions = this.config.bulkActions || {};
    const action = bulkActions[actionKey];

    if (!action) {
      console.error("Bulk action not found:", actionKey);
      return;
    }

    // Get selected IDs as array
    const selectedIds = Array.from(this.selectedRows);

    // If action has modalId, open modal instead of executing directly
    if (action.modalId) {
      // Pass IDs as parameter to the modal
      const params = "ids=" + encodeURIComponent(JSON.stringify(selectedIds));
      if (typeof openModal === "function") {
        openModal(action.modalId, params);
      } else {
        console.error("openModal function not found");
      }
      return;
    }

    // Show confirmation if needed (legacy support)
    if (action.confirm) {
      if (!confirm(action.confirm)) {
        return;
      }
    }

    // Execute action directly via AJAX
    const method = action.method || "POST";
    const url = action.url;

    $.ajax({
      url: url,
      method: method,
      contentType: "application/json",
      data: JSON.stringify({ ids: selectedIds }),
      success: (response) => {
        if (response.success) {
          $("body").toast({
            class: "success",
            message: response.message || "Aktion erfolgreich ausgeführt",
          });

          // Remove deleted rows from DOM with animation
          this.removeRowsById(selectedIds);
        } else {
          $("body").toast({
            class: "error",
            message: response.message || "Fehler beim Ausführen der Aktion",
          });
        }
      },
      error: (xhr) => {
        $("body").toast({
          class: "error",
          message: "Fehler beim Ausführen der Aktion",
        });
        console.error("Bulk action error:", xhr);
      },
    });
  }

  removeRowsById(ids) {
    const table = $("#" + this.id);
    const idsSet = new Set(ids.map(String));

    // Find and remove rows with animation
    table.find("tbody tr").each((index, row) => {
      const $row = $(row);
      const checkbox = $row.find(".row-select");
      const rowId = checkbox.val();

      if (rowId && idsSet.has(String(rowId))) {
        // Animate row removal
        $row.addClass("negative").fadeOut(300, () => {
          // Remove from allRows array
          this.allRows = this.allRows.filter((r) => r !== row);
          // Remove from filteredRows if exists
          if (this.filteredRows) {
            this.filteredRows = this.filteredRows.filter((r) => r !== row);
          }
          // Remove from DOM
          $row.remove();

          // Update display after all removals
          this.afterRowsRemoved();
        });
      }
    });

    // Clear selection
    this.selectedRows.clear();
    this.updateSelectAllCheckbox();
    this.updateBulkActionButton();
  }

  afterRowsRemoved() {
    // Adjust current page if needed
    const totalPages = this.getTotalPages();
    if (this.currentPage > totalPages && totalPages > 0) {
      this.currentPage = totalPages;
    }

    // Re-render table (pagination, footer)
    this.renderTable();
  }

  initSearch() {
    const self = this;
    const searchInput = $("#" + this.id + "_search");

    // Debounced search
    let searchTimeout;
    searchInput.on("input", function () {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        self.search($(this).val());
      }, 300);
    });
  }

  search(term) {
    const rows = this.allRows;
    this.currentSearchTerm = term || "";

    if (!term) {
      // Show all rows, then apply pagination
      this.filteredRows = rows;
    } else {
      const lowerTerm = term.toLowerCase();
      this.filteredRows = rows.filter((row) => {
        const text = $(row).text().toLowerCase();
        return text.includes(lowerTerm);
      });
    }

    this.currentPage = 1;
    this.renderTable();
  }

  setFilter(column, value) {
    if (value === "" || value === null) {
      delete this.filters[column];
    } else {
      this.filters[column] = value;
    }

    this.applyFiltersAndRender();
  }

  initFilters() {
    const self = this;

    // Text and date filters
    $("#" + this.id + "_container").on(
      "input change",
      ".filter-input",
      function () {
        const column = $(this).data("column");
        const value = $(this).val();

        self.setFilter(column, value);
      },
    );

    // Clear filters button
    $("#" + this.id + "_clear_filters").on("click", function () {
      self.clearFilters();
    });
  }

  clearFilters() {
    this.filters = {};
    $("#" + this.id + "_container .filter-input")
      .val("")
      .trigger("change");
    $("#" + this.id + "_container .ui.dropdown").dropdown("clear");
    this.applyFiltersAndRender();
  }

  applyFiltersAndRender() {
    // For now, reset to all rows and apply pagination
    this.filteredRows = this.allRows;
    this.currentPage = 1;
    this.renderTable();
  }

  initSorting() {
    const self = this;
    const table = $("#" + this.id);

    table.find("thead th.sortable").on("click", function () {
      const column = $(this).data("column");
      self.sort(column);
    });
  }

  sort(columnKey) {
    // Implementation for sorting
    this.renderTable();
  }

  initPagination() {
    const self = this;
    const pagination = $("#" + this.id + "_pagination");

    pagination.on("click", ".item:not(.disabled)", function (e) {
      e.preventDefault();
      const $item = $(this);
      const page = $item.data("page");

      if (page === "prev") {
        if (self.currentPage > 1) {
          self.currentPage--;
        }
      } else if (page === "next") {
        const maxPage = self.getTotalPages();
        if (self.currentPage < maxPage) {
          self.currentPage++;
        }
      } else if (typeof page === "number") {
        self.currentPage = page;
      }

      self.renderTable();
    });

    // Initialize filtered rows
    this.filteredRows = this.allRows;
  }

  getTotalPages() {
    const totalRows = this.filteredRows
      ? this.filteredRows.length
      : this.allRows.length;
    return Math.ceil(totalRows / this.config.pageSize) || 1;
  }

  renderPaginationButtons() {
    const pagination = $("#" + this.id + "_pagination");
    const totalPages = this.getTotalPages();

    // Clear existing page numbers (keep prev/next)
    pagination.find(".page-number").remove();

    // Find the next button to insert before it
    const nextBtn = pagination.find('[data-page="next"]');

    // Generate page number buttons
    for (let i = 1; i <= totalPages; i++) {
      const activeClass = i === this.currentPage ? " active" : "";
      const pageBtn = $(
        `<a class="item page-number${activeClass}" data-page="${i}">${i}</a>`,
      );
      nextBtn.before(pageBtn);
    }

    // Update prev/next disabled state
    pagination
      .find('[data-page="prev"]')
      .toggleClass("disabled", this.currentPage <= 1);
    pagination
      .find('[data-page="next"]')
      .toggleClass("disabled", this.currentPage >= totalPages);
  }

  applyPagination() {
    const rows = this.filteredRows || this.allRows;
    const pageSize = this.config.pageSize;
    const startIndex = (this.currentPage - 1) * pageSize;
    const endIndex = startIndex + pageSize;

    // Hide all rows first
    $(this.allRows).hide();

    // Show only rows for current page
    rows.slice(startIndex, endIndex).forEach((row) => {
      $(row).show();
    });
  }

  initExport() {
    const self = this;

    $(".export-btn").on("click", function () {
      const format = $(this).data("format");
      self.export(format);
    });
  }

  export(format) {
    // Export functionality
    console.log("Export to", format);
  }

  renderTable() {
    // Apply pagination to show/hide rows
    this.applyPagination();

    // Update pagination buttons
    this.renderPaginationButtons();

    // Handle empty state
    this.updateEmptyState();

    // Update info footer if enabled
    if (this.config.showInfoFooter) {
      this.updateInfoFooter();
    }

    // Update select all checkbox state
    if (this.config.selectable) {
      this.updateSelectAllCheckbox();
    }
  }

  updateEmptyState() {
    const table = $("#" + this.id);
    const tbody = table.find("tbody");
    const totalRows = this.filteredRows
      ? this.filteredRows.length
      : this.allRows.length;
    const colCount = table.find("thead th").length;

    // Remove existing empty state row if any
    tbody.find(".easylist-empty-state").remove();

    if (totalRows === 0) {
      let message, icon, style;

      if (this.currentSearchTerm) {
        // Search returned no results - subtle inline message
        message = `Keine Treffer für "<em>${this.escapeHtml(
          this.currentSearchTerm,
        )}</em>"`;
        icon = "search";
        style = "color: #888; font-size: 0.95em; padding: 20px;";
      } else {
        // No data at all - slightly more prominent
        message = "Keine Einträge vorhanden";
        icon = "inbox";
        style = "color: #666; font-size: 1em; padding: 30px;";
      }

      const emptyRow = $(`
        <tr class="easylist-empty-state">
          <td colspan="${colCount}" class="center aligned" style="${style}">
            <i class="${icon} icon" style="margin-right: 6px; opacity: 0.6;"></i>${message}
          </td>
        </tr>
      `);

      tbody.append(emptyRow);
    }
  }

  escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
  }

  updateInfoFooter() {
    const totalRows = this.filteredRows
      ? this.filteredRows.length
      : this.allRows.length;
    const totalPages = this.getTotalPages();

    // Update total entries count
    const totalEntriesSpan = $("#" + this.id + "_info strong");
    if (totalEntriesSpan.length) {
      totalEntriesSpan.text(totalRows);
    }

    // Update current page
    const currentPageSpan = $("#" + this.id + "_current_page");
    if (currentPageSpan.length) {
      currentPageSpan.text(this.currentPage);
    }

    // Update total pages
    const totalPagesSpan = $("#" + this.id + "_total_pages");
    if (totalPagesSpan.length) {
      totalPagesSpan.text(totalPages);
    }
  }
}

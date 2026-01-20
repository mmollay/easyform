/**
 * EasyList JavaScript Handler
 * Handles all client-side functionality for EasyList
 */

// Define EasyListHandler class
class EasyListHandler {
  constructor(config) {
    this.config = config;
    this.container = document.getElementById(config.id + "_container");
    this.table = document.getElementById(config.id);
    this.data = [];
    this.filteredData = [];
    this.currentPage = 1;
    this.sortColumn = null;
    this.sortDirection = "asc";
    this.filters = {};
    this.selectedRows = new Set();

    this.init();
  }

  init() {
    // Load data
    if (this.config.ajax) {
      this.loadData();
    } else if (this.config.data) {
      // Use data provided in config
      this.data = this.config.data;
      this.filteredData = [...this.data];
    } else {
      // Fallback: extract from HTML
      this.initializeData();
    }

    // Initialize search
    if (this.config.searchable) {
      this.initSearch();
    }

    // Initialize sorting
    if (this.config.sortable) {
      this.initSorting();
    }

    // Initialize filters
    this.initFilters();

    // Initialize selection
    if (this.config.selectable) {
      this.initSelection();
    }

    // Initialize pagination
    if (this.config.paginate) {
      this.initPagination();
    }

    // Initialize export
    if (this.config.exportable) {
      this.initExport();
    }

    // Initialize bulk actions
    this.initBulkActions();
  }

  loadData() {
    fetch(this.config.ajaxUrl)
      .then((response) => response.json())
      .then((data) => {
        this.data = data;
        this.filteredData = [...data];
        this.renderTable();
      })
      .catch((error) => {
        console.error("Error loading data:", error);
        this.showError("Fehler beim Laden der Daten");
      });
  }

  initializeData() {
    // Extract data from existing table rows
    const rows = this.table.querySelectorAll("tbody tr");
    this.data = Array.from(rows).map((row) => {
      const rowData = {};
      const cells = row.querySelectorAll("td");
      this.config.columns.forEach((column, index) => {
        if (cells[index]) {
          // Try to get raw value from data attribute, fallback to text content
          rowData[column.key] =
            cells[index].dataset.value || cells[index].textContent.trim();
        }
      });
      // Add ID if row has data-id
      if (row.dataset.id) {
        rowData.id = row.dataset.id;
      }
      return rowData;
    });
    this.filteredData = [...this.data];
  }

  initSearch() {
    const searchInput = document.getElementById(this.config.id + "_search");
    if (!searchInput) return;

    let searchTimer;
    searchInput.addEventListener("input", (e) => {
      clearTimeout(searchTimer);
      searchTimer = setTimeout(() => {
        this.search(e.target.value);
      }, 300);
    });
  }

  search(query) {
    if (!query) {
      this.filteredData = [...this.data];
    } else {
      const searchColumns = this.config.columns.filter(
        (c) => c.searchable !== false,
      );
      const lowerQuery = query.toLowerCase();

      this.filteredData = this.data.filter((row) => {
        return searchColumns.some((column) => {
          const value = String(row[column.key] || "").toLowerCase();
          return value.includes(lowerQuery);
        });
      });
    }

    this.currentPage = 1;
    this.renderTable();
  }

  initSorting() {
    const headers = this.table.querySelectorAll("thead th.sortable");
    headers.forEach((header) => {
      header.style.cursor = "pointer";
      header.addEventListener("click", () => {
        const column = header.dataset.column;
        this.sort(column);
      });
    });
  }

  sort(column) {
    if (this.sortColumn === column) {
      this.sortDirection = this.sortDirection === "asc" ? "desc" : "asc";
    } else {
      this.sortColumn = column;
      this.sortDirection = "asc";
    }

    const columnConfig = this.config.columns.find((c) => c.key === column);
    const type = columnConfig?.type || "text";

    this.filteredData.sort((a, b) => {
      let valA = a[column];
      let valB = b[column];

      // Handle different data types
      if (type === "number") {
        valA = parseFloat(valA) || 0;
        valB = parseFloat(valB) || 0;
      } else if (type === "date") {
        valA = new Date(valA);
        valB = new Date(valB);
      } else {
        valA = String(valA || "").toLowerCase();
        valB = String(valB || "").toLowerCase();
      }

      if (valA < valB) return this.sortDirection === "asc" ? -1 : 1;
      if (valA > valB) return this.sortDirection === "asc" ? 1 : -1;
      return 0;
    });

    this.updateSortIndicators(column);
    this.renderTable();
  }

  updateSortIndicators(column) {
    // Remove all sort indicators
    this.table.querySelectorAll("thead th i.sort").forEach((icon) => {
      icon.className = "sort icon";
    });

    // Add indicator to current column
    const header = this.table.querySelector(
      `thead th[data-column="${column}"]`,
    );
    if (header) {
      const icon = header.querySelector("i.sort");
      if (icon) {
        icon.className =
          this.sortDirection === "asc" ? "sort up icon" : "sort down icon";
      }
    }
  }

  initFilters() {
    const filterInputs = this.container.querySelectorAll(".filter-input");
    filterInputs.forEach((input) => {
      const column = input.dataset.column;

      if (input.classList.contains("filter-min")) {
        input.addEventListener("input", () => {
          this.setRangeFilter(column, "min", input.value);
        });
      } else if (input.classList.contains("filter-max")) {
        input.addEventListener("input", () => {
          this.setRangeFilter(column, "max", input.value);
        });
      } else if (input.tagName === "SELECT") {
        // Handle select/dropdown filters
        // Use both change event and Semantic UI dropdown onChange
        input.addEventListener("change", () => {
          this.setFilter(column, input.value);
        });

        // Also setup Semantic UI dropdown if available
        if (typeof $ !== "undefined" && $.fn.dropdown) {
          const $dropdown = $(input).closest(".ui.dropdown");
          if ($dropdown.length) {
            $dropdown.dropdown({
              onChange: (value) => {
                this.setFilter(column, value);
              },
            });
          }
        }
      } else {
        input.addEventListener("input", () => {
          this.setFilter(column, input.value);
        });
      }
    });

    // Clear filters button
    const clearBtn = document.getElementById(this.config.id + "_clear_filters");
    if (clearBtn) {
      clearBtn.addEventListener("click", (e) => {
        e.preventDefault();
        this.clearFilters();
      });
    }
  }

  setFilter(column, value) {
    if (value) {
      this.filters[column] = value;
    } else {
      delete this.filters[column];
    }
    this.applyFilters();
  }

  setRangeFilter(column, type, value) {
    if (!this.filters[column]) {
      this.filters[column] = {};
    }
    if (value) {
      this.filters[column][type] = parseFloat(value);
    } else {
      delete this.filters[column][type];
      if (Object.keys(this.filters[column]).length === 0) {
        delete this.filters[column];
      }
    }
    this.applyFilters();
  }

  applyFilters() {
    this.filteredData = this.data.filter((row) => {
      for (const [column, filter] of Object.entries(this.filters)) {
        const value = row[column];

        if (typeof filter === "object") {
          // Range filter
          const numValue = parseFloat(value) || 0;
          if (filter.min !== undefined && numValue < filter.min) return false;
          if (filter.max !== undefined && numValue > filter.max) return false;
        } else {
          // Text filter
          const strValue = String(value || "").toLowerCase();
          const strFilter = String(filter).toLowerCase();
          if (!strValue.includes(strFilter)) return false;
        }
      }
      return true;
    });

    // Re-apply search if active
    const searchInput = document.getElementById(this.config.id + "_search");
    if (searchInput && searchInput.value) {
      this.search(searchInput.value);
    } else {
      this.currentPage = 1;
      this.renderTable();
    }
  }

  clearFilters() {
    this.filters = {};
    this.container.querySelectorAll(".filter-input").forEach((input) => {
      if (input.tagName === "SELECT") {
        input.selectedIndex = 0; // Reset to first option
        // Trigger Semantic UI dropdown update if available
        if (typeof $ !== "undefined" && $.fn.dropdown) {
          const $dropdown = $(input).closest(".ui.dropdown");
          if ($dropdown.length) {
            // Set to empty value (first option)
            $dropdown.dropdown("set selected", "");
            // Or clear completely
            $dropdown.dropdown("clear");
          }
        }
      } else {
        input.value = "";
      }
    });

    // Also clear min/max range inputs specifically
    this.container
      .querySelectorAll(".filter-min, .filter-max")
      .forEach((input) => {
        input.value = "";
      });

    this.applyFilters();
  }

  initSelection() {
    // Use event delegation on container for BOTH select-all AND row checkboxes
    // This ensures events work even if DOM is re-rendered
    this.container.addEventListener("change", (e) => {
      // Select-all checkbox (in thead)
      if (e.target.classList.contains("select-all")) {
        this.selectAll(e.target.checked);
      }
      // Individual row selection
      else if (e.target.classList.contains("row-select")) {
        this.selectRow(e.target.value, e.target.checked);
      }
    });

    // Also handle click on the th cell containing the select-all checkbox
    // (in case the checkbox itself is hard to click)
    this.container.addEventListener("click", (e) => {
      const th = e.target.closest("th");
      if (
        th &&
        th.querySelector(".select-all") &&
        !e.target.classList.contains("select-all")
      ) {
        const checkbox = th.querySelector(".select-all");
        checkbox.checked = !checkbox.checked;
        this.selectAll(checkbox.checked);
      }
    });

    // Initialize button state
    this.updateSelectionInfo();
  }

  selectAll(checked) {
    const checkboxes = this.table.querySelectorAll(".row-select");
    checkboxes.forEach((cb) => {
      cb.checked = checked;
      if (checked) {
        this.selectedRows.add(cb.value);
      } else {
        this.selectedRows.delete(cb.value);
      }
    });
    this.updateSelectionInfo();
  }

  selectRow(id, checked) {
    if (checked) {
      this.selectedRows.add(id);
    } else {
      this.selectedRows.delete(id);
    }
    this.updateSelectionInfo();

    // Update select all checkbox
    const selectAll = this.table.querySelector(".select-all");
    if (selectAll) {
      const allBoxes = this.table.querySelectorAll(".row-select");
      const checkedBoxes = this.table.querySelectorAll(".row-select:checked");
      selectAll.checked = allBoxes.length === checkedBoxes.length;
      selectAll.indeterminate =
        checkedBoxes.length > 0 && checkedBoxes.length < allBoxes.length;
    }
  }

  updateSelectionInfo() {
    // Update bulk action button text and state
    const bulkBtn = document.getElementById(this.config.id + "_bulk_apply");
    if (bulkBtn) {
      const count = this.selectedRows.size;
      // Keep the icon and update text
      const icon = '<i class="check icon"></i> ';
      const text = count > 0 ? `Anwenden (${count})` : "Anwenden";
      bulkBtn.innerHTML = icon + text;

      // Enable/disable button
      if (count > 0) {
        bulkBtn.disabled = false;
        bulkBtn.classList.remove("disabled");
      } else {
        bulkBtn.disabled = true;
        bulkBtn.classList.add("disabled");
      }
    }
  }

  initBulkActions() {
    const bulkBtn = document.getElementById(this.config.id + "_bulk_apply");
    const bulkSelect = document.getElementById(this.config.id + "_bulk_action");

    if (bulkBtn && bulkSelect) {
      bulkBtn.addEventListener("click", () => {
        const action = bulkSelect.value;
        if (action && this.selectedRows.size > 0) {
          this.executeBulkAction(action);
        }
      });
    }
  }

  executeBulkAction(action) {
    const ids = Array.from(this.selectedRows);
    console.log(`Executing bulk action: ${action} on IDs:`, ids);

    // Get bulk action configuration
    const bulkActions = this.config.bulkActions || {};
    const actionConfig = bulkActions[action];

    if (!actionConfig) {
      console.error("Bulk action not found:", action);
      return;
    }

    // Show confirmation if required
    if (actionConfig.confirm) {
      if (!confirm(actionConfig.confirm)) {
        return;
      }
    }

    // Execute AJAX request
    const url = actionConfig.url;
    const method = actionConfig.method || "POST";

    fetch(url, {
      method: method,
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ ids: ids }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          // Show success message
          if (typeof $.fn.toast !== "undefined") {
            $("body").toast({
              class: "success",
              message: data.message || "Aktion erfolgreich ausgeführt",
            });
          } else {
            alert(data.message || "Aktion erfolgreich ausgeführt");
          }

          // Clear selection
          this.selectedRows.clear();
          this.updateSelectionInfo();

          // Reload page after short delay
          setTimeout(() => {
            window.location.reload();
          }, 1000);
        } else {
          // Show error message
          if (typeof $.fn.toast !== "undefined") {
            $("body").toast({
              class: "error",
              message: data.message || "Fehler beim Ausführen der Aktion",
            });
          } else {
            alert(data.message || "Fehler beim Ausführen der Aktion");
          }
        }
      })
      .catch((error) => {
        console.error("Bulk action error:", error);
        if (typeof $.fn.toast !== "undefined") {
          $("body").toast({
            class: "error",
            message: "Fehler beim Ausführen der Aktion",
          });
        } else {
          alert("Fehler beim Ausführen der Aktion");
        }
      });

    // Trigger custom event for extensibility
    const event = new CustomEvent("easylist:bulkaction", {
      detail: { action, ids, listId: this.config.id },
    });
    document.dispatchEvent(event);
  }

  initPagination() {
    const pagination = document.getElementById(this.config.id + "_pagination");
    if (!pagination) return;

    pagination.addEventListener("click", (e) => {
      const page = e.target.closest("[data-page]")?.dataset.page;
      if (page) {
        if (page === "prev") {
          this.goToPage(this.currentPage - 1);
        } else if (page === "next") {
          this.goToPage(this.currentPage + 1);
        } else {
          this.goToPage(parseInt(page));
        }
      }
    });
  }

  goToPage(page) {
    const totalPages = Math.ceil(
      this.filteredData.length / this.config.pageSize,
    );
    if (page < 1 || page > totalPages) return;

    this.currentPage = page;
    this.renderTable();
    this.renderPagination();
  }

  renderPagination() {
    const pagination = document.getElementById(this.config.id + "_pagination");
    if (!pagination) return;

    const totalPages = Math.ceil(
      this.filteredData.length / this.config.pageSize,
    );

    // Clear existing page numbers only (not prev/next buttons)
    const existingPages = pagination.querySelectorAll(
      '.item[data-page]:not([data-page="prev"]):not([data-page="next"])',
    );
    existingPages.forEach((p) => p.remove());

    // Find or create navigation buttons
    let prevBtn = pagination.querySelector('[data-page="prev"]');
    let nextBtn = pagination.querySelector('[data-page="next"]');

    // If buttons don't exist, create them
    if (!prevBtn) {
      prevBtn = document.createElement("a");
      prevBtn.className = "icon item";
      prevBtn.dataset.page = "prev";
      prevBtn.innerHTML = '<i class="left chevron icon"></i>';
      pagination.appendChild(prevBtn);
    }

    if (!nextBtn) {
      nextBtn = document.createElement("a");
      nextBtn.className = "icon item";
      nextBtn.dataset.page = "next";
      nextBtn.innerHTML = '<i class="right chevron icon"></i>';
      pagination.appendChild(nextBtn);
    }

    prevBtn.classList.toggle("disabled", this.currentPage === 1);
    nextBtn.classList.toggle("disabled", this.currentPage === totalPages);

    // Add page numbers
    const maxVisible = 7;
    let start = Math.max(1, this.currentPage - Math.floor(maxVisible / 2));
    let end = Math.min(totalPages, start + maxVisible - 1);

    if (end - start < maxVisible - 1) {
      start = Math.max(1, end - maxVisible + 1);
    }

    for (let i = start; i <= end; i++) {
      const pageLink = document.createElement("a");
      pageLink.className = "item" + (i === this.currentPage ? " active" : "");
      pageLink.dataset.page = i;
      pageLink.textContent = i;
      pagination.insertBefore(pageLink, nextBtn);
    }
  }

  initExport() {
    const exportBtns = this.container.querySelectorAll(".export-btn");
    exportBtns.forEach((btn) => {
      btn.addEventListener("click", () => {
        this.export(btn.dataset.format);
      });
    });
  }

  export(format) {
    const data = this.filteredData;

    switch (format) {
      case "csv":
        this.exportCSV(data);
        break;
      case "json":
        this.exportJSON(data);
        break;
      case "excel":
        this.exportExcel(data);
        break;
      default:
        console.error("Unknown export format:", format);
    }
  }

  exportCSV(data) {
    const headers = this.config.columns
      .filter((c) => c.key !== "_select" && c.key !== "_actions")
      .map((c) => c.label);

    const rows = data.map((row) => {
      return this.config.columns
        .filter((c) => c.key !== "_select" && c.key !== "_actions")
        .map((c) => {
          let value = row[c.key] || "";
          // Convert to string
          value = String(value);
          // Escape CSV values
          if (
            value.includes(",") ||
            value.includes('"') ||
            value.includes("\n")
          ) {
            return `"${value.replace(/"/g, '""')}"`;
          }
          return value;
        })
        .join(",");
    });

    const csv = [headers.join(","), ...rows].join("\n");
    this.downloadFile(csv, "data.csv", "text/csv");
  }

  exportJSON(data) {
    const json = JSON.stringify(data, null, 2);
    this.downloadFile(json, "data.json", "application/json");
  }

  exportExcel(data) {
    // For Excel export, we'll create a simple HTML table
    // that Excel can open
    let html = "<table>";
    html += "<thead><tr>";

    this.config.columns
      .filter((c) => c.key !== "_select" && c.key !== "_actions")
      .forEach((c) => {
        html += `<th>${c.label}</th>`;
      });

    html += "</tr></thead><tbody>";

    data.forEach((row) => {
      html += "<tr>";
      this.config.columns
        .filter((c) => c.key !== "_select" && c.key !== "_actions")
        .forEach((c) => {
          const value = String(row[c.key] || "");
          html += `<td>${value}</td>`;
        });
      html += "</tr>";
    });

    html += "</tbody></table>";

    this.downloadFile(html, "data.xls", "application/vnd.ms-excel");
  }

  downloadFile(content, filename, mimeType) {
    const blob = new Blob([content], { type: mimeType });
    const url = URL.createObjectURL(blob);
    const link = document.createElement("a");
    link.href = url;
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
  }

  renderTable() {
    const tbody = this.table.querySelector("tbody");
    if (!tbody) return;

    // Clear existing rows
    tbody.innerHTML = "";

    if (this.filteredData.length === 0) {
      const row = tbody.insertRow();
      const cell = row.insertCell();
      cell.colSpan = this.config.columns.length;
      cell.className = "center aligned";
      cell.textContent = "Keine Daten gefunden";
      return;
    }

    // Get page data
    const start = (this.currentPage - 1) * this.config.pageSize;
    const end = Math.min(
      start + this.config.pageSize,
      this.filteredData.length,
    );
    const pageData = this.filteredData.slice(start, end);

    // Render rows
    pageData.forEach((rowData) => {
      const row = tbody.insertRow();
      if (rowData.id) {
        row.dataset.id = rowData.id;
      }

      this.config.columns.forEach((column) => {
        const cell = row.insertCell();

        if (column.align) {
          cell.className = column.align + " aligned";
        }

        if (column.key === "_select") {
          const checkbox = document.createElement("input");
          checkbox.type = "checkbox";
          checkbox.className = "row-select";
          checkbox.value = rowData.id || "";
          checkbox.checked = this.selectedRows.has(checkbox.value);
          cell.appendChild(checkbox);
        } else if (column.key === "_actions") {
          // Recreate action buttons
          if (this.config.actions) {
            let actionsHtml = '<div class="ui small basic icon buttons">';
            this.config.actions.forEach((action) => {
              const url = action.url
                ? action.url.replace("{id}", rowData.id)
                : "#";
              const cls = action.class || "";
              actionsHtml += `<a class="ui button ${cls}" href="${url}" title="${action.label}">`;
              actionsHtml += `<i class="${action.icon} icon"></i></a>`;
            });
            actionsHtml += "</div>";
            cell.innerHTML = actionsHtml;
          } else if (column.template) {
            cell.innerHTML = this.parseTemplate(column.template, rowData);
          }
        } else if (column.template) {
          cell.innerHTML = this.parseTemplate(column.template, rowData);
        } else {
          // Use formatted value if available, otherwise raw value
          const formattedKey = column.key + "_formatted";
          if (rowData[formattedKey]) {
            cell.innerHTML = rowData[formattedKey];
          } else {
            cell.textContent = rowData[column.key] || "";
          }
        }
      });
    });

    // Update pagination
    if (this.config.paginate) {
      this.renderPagination();
    }

    // Update info
    this.updateInfo();

    // Re-check selected items if they are on this page
    if (this.config.selectable) {
      const checkboxes = this.table.querySelectorAll(".row-select");
      checkboxes.forEach((cb) => {
        if (this.selectedRows.has(cb.value)) {
          cb.checked = true;
        }
      });

      // Update select all checkbox state
      const selectAll = this.table.querySelector(".select-all");
      if (selectAll) {
        const allBoxes = this.table.querySelectorAll(".row-select");
        const checkedBoxes = this.table.querySelectorAll(".row-select:checked");
        selectAll.checked =
          allBoxes.length > 0 && allBoxes.length === checkedBoxes.length;
        selectAll.indeterminate =
          checkedBoxes.length > 0 && checkedBoxes.length < allBoxes.length;
      }
    }
  }

  parseTemplate(template, data) {
    if (!template) return "";
    return template.replace(/\{(\w+)\}/g, (match, key) => {
      return data[key] || "";
    });
  }

  updateInfo() {
    // Show current data info
    const infoText = `Zeige ${(this.currentPage - 1) * this.config.pageSize + 1} bis ${Math.min(
      this.currentPage * this.config.pageSize,
      this.filteredData.length,
    )} von ${this.filteredData.length} Einträgen`;

    if (this.filteredData.length < this.data.length) {
      const filterText = ` (gefiltert von ${this.data.length} gesamt)`;
      console.log(infoText + filterText);
    }
  }

  showError(message) {
    const tbody = this.table.querySelector("tbody");
    tbody.innerHTML = `
            <tr>
                <td colspan="${this.config.columns.length}" class="center aligned">
                    <div class="ui negative message">
                        <i class="exclamation triangle icon"></i> ${message}
                    </div>
                </td>
            </tr>
        `;
  }
}

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
  // Auto-init if global config exists
  if (window.EasyListConfigs) {
    window.EasyListConfigs.forEach((config) => {
      new EasyListHandler(config);
    });
  }
});

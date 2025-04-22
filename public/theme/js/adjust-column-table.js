class TableColumnManager {
    constructor(tableId, pageKey) {
        this.tableId = tableId;
        this.pageKey = pageKey;
        this.init();
    }

    init() {
        // Wait for document ready
        $(document).ready(() => {
            // Initialize dropdown
            $('.dropdown-toggle').dropdown();

            // Load saved states first
            this.loadColumnState();
            
            // Then bind events
            this.bindEvents();
            
            // Setup observer for dynamic content
            this.setupMutationObserver();
        });
    }

    bindEvents() {
        // Handle checkbox changes
        $('.dropdown-menu input[type="checkbox"]').on('change', (e) => {
            const column = $(e.target).data('column');
            const isChecked = $(e.target).prop('checked');
            this.toggleColumn(column, isChecked);
            this.saveColumnState();
            e.stopPropagation();
        });

        // Keep dropdown open when clicking inside
        $('.dropdown-menu').on('click', (e) => {
            e.stopPropagation();
        });
    }

    saveColumnState() {
        const columnStates = {};
        $('.dropdown-menu input[type="checkbox"]').each(function(){
            const column = $(this).data('column');
            columnStates[column] = $(this).prop('checked');
        });
        localStorage.setItem(this.pageKey, JSON.stringify(columnStates));
        window.location.reload(); // Reload to apply changes
    }

    loadColumnState() {
        const savedStates = localStorage.getItem(this.pageKey);
        if (savedStates) {
            const columnStates = JSON.parse(savedStates);
            $('.dropdown-menu input[type="checkbox"]').each((index, element) => {
                const column = $(element).data('column');
                if (columnStates.hasOwnProperty(column)) {
                    $(element).prop('checked', columnStates[column]);
                }
            });
            // Apply visibility immediately after loading states
            this.applyCurrentVisibility();
        }
    }

    resetColumnState() {
        localStorage.removeItem(this.pageKey);
        $('.dropdown-menu input[type="checkbox"]').prop('checked', true);
        this.initializeColumnVisibility();
    }

    initializeColumnVisibility() {
        $('.dropdown-menu input[type="checkbox"]').each((index, element) => {
            const column = $(element).data('column');
            const isChecked = $(element).prop('checked');
            this.toggleColumn(column, isChecked);
        });
    }

    setupMutationObserver() {
        // Create an observer instance
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'childList' && 
                    (mutation.target.nodeName === 'TBODY' || mutation.target.nodeName === 'TABLE')) {
                    this.applyCurrentVisibility();
                }
            });
        });

        // Start observing the table for changes
        const table = $(`#${this.tableId}`)[0];
        if (table) {
            observer.observe(table, {
                childList: true,
                subtree: true
            });
        }
    }

    applyCurrentVisibility() {
        const table = $(`#${this.tableId}`);
        if (table.length === 0) return; // Exit if table not found

        $('.dropdown-menu input[type="checkbox"]').each((index, element) => {
            const column = $(element).data('column');
            const isChecked = $(element).prop('checked');
            
            // Apply to both current and future content
            this.toggleColumn(column, isChecked, true);
            
            // Add CSS rule for persistent hiding
            const styleId = `column-style-${this.pageKey}-${column}`;
            let style = document.getElementById(styleId);
            
            if (!isChecked) {
                if (!style) {
                    style = document.createElement('style');
                    style.id = styleId;
                    document.head.appendChild(style);
                }
                style.innerHTML = `
                    #${this.tableId} td:nth-child(${column + 1}),
                    #${this.tableId} th:nth-child(${column + 1}) {
                        display: none !important;
                    }
                `;
            } else if (style) {
                style.remove();
            }
        });
    }

    toggleColumn(column, show, skipSave = false) {
        const table = $(`#${this.tableId}`);
        
        // Hide both header and body cells
        table.find('thead tr').children(`th:nth-child(${column + 1})`).toggle(show);
        table.find('tbody tr').each(function() {
            $(this).children(`td:nth-child(${column + 1})`).toggle(show);
        });

        if (!skipSave) {
            this.saveColumnState();
        }
    }
}
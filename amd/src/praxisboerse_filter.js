// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Praxisbörse data filter module.
 *
 * Provides client-side filtering, searching, and sorting functionality
 * for Praxisbörse data entries.
 *
 * @module     theme_nwverkehrserziehung/praxisboerse_filter
 * @copyright  2024
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

const SELECTORS = {
    ITEM: '[data-item-id]',
    SEARCH: '.praxisboerse-search',
    FILTER: '.praxisboerse-filter',
    SORT: '.praxisboerse-sort',
    RESET: '.praxisboerse-reset',
    COUNT: '.praxisboerse-item-count'
};

/**
 * PraxisboerseFilter class.
 *
 * Manages filtering, searching, and sorting of praxisbörse entries.
 */
class PraxisboerseFilter {

    /**
     * Constructor.
     *
     * @param {HTMLElement} root - The root container element
     */
    constructor(root) {
        this.root = root;
        this.originalItems = [];
        this.filteredItems = [];
        this.activeFilters = {
            institution: '',
            targetgroup: '',
            duration: '',
            material: ''
        };

        this.storeOriginalItems();
        this.registerEvents();
        this.updateItemCount();
    }

    /**
     * Store original items for filtering.
     *
     * @returns {void}
     */
    storeOriginalItems() {
        const items = this.root.querySelectorAll(SELECTORS.ITEM);

        items.forEach(item => {
            this.originalItems.push({
                element: item,
                id: item.dataset.itemId,
                text: item.textContent.toLowerCase(),
                author: (item.dataset.author || '').toLowerCase(),
                timestamp: item.dataset.timestamp || '',
                filters: {
                    institution: item.dataset.institution || '',
                    targetgroup: item.dataset.targetgroup || '',
                    duration: item.dataset.duration || '',
                    material: item.dataset.material || ''
                }
            });
        });

        this.filteredItems = [...this.originalItems];
    }

    /**
     * Register event listeners for filter controls.
     *
     * @returns {void}
     */
    registerEvents() {
        const search = this.root.querySelector(SELECTORS.SEARCH);
        const filters = this.root.querySelectorAll(SELECTORS.FILTER);
        const sort = this.root.querySelector(SELECTORS.SORT);
        const reset = this.root.querySelector(SELECTORS.RESET);

        if (search) {
            search.addEventListener('input', e => {
                this.handleSearch(e.target.value);
            });
        }

        filters.forEach(filter => {
            filter.addEventListener('change', e => {
                const filterType = e.target.dataset.filter;
                this.activeFilters[filterType] = e.target.value;
                this.applyFilters();
            });
        });

        if (sort) {
            sort.addEventListener('change', e => {
                this.handleSort(e.target.value);
            });
        }

        if (reset) {
            reset.addEventListener('click', e => {
                e.preventDefault();
                this.resetFilters();
            });
        }
    }

    /**
     * Handle search input.
     *
     * @returns {void}
     */
    handleSearch() {
        this.applyFilters();
    }

    /**
     * Apply all active filters.
     *
     * @returns {void}
     */
    applyFilters() {
        const search = this.root.querySelector(SELECTORS.SEARCH);
        const q = (search ? search.value.trim().toLowerCase() : '');

        this.filteredItems = this.originalItems.filter(item => {
            // Check search term
            if (q && !item.text.includes(q) && !item.author.includes(q)) {
                return false;
            }

            // Check category filters with case-insensitive substring matching
            for (const [filterType, filterValue] of Object.entries(this.activeFilters)) {
                if (filterValue) {
                    const itemValue = (item.filters && item.filters[filterType]) ?
                        item.filters[filterType].toLowerCase() : '';
                    const compareValue = filterValue.toLowerCase();

                    // Check if item value contains or equals the filter value
                    if (!itemValue.includes(compareValue) && compareValue !== itemValue) {
                        return false;
                    }
                }
            }

            return true;
        });

        this.render();
    }

    /**
     * Handle sorting.
     *
     * @param {string} type - The sort type (newest, oldest, author)
     * @returns {void}
     */
    handleSort(type) {
        if (type === 'oldest') {
            this.filteredItems.sort(
                (a, b) =>
                parseInt(a.timestamp) - parseInt(b.timestamp)
            );
        } else if (type === 'author') {
            this.filteredItems.sort(
                (a, b) =>
                a.author.localeCompare(b.author)
            );
        } else {
            this.filteredItems.sort(
                (a, b) =>
                parseInt(b.timestamp) - parseInt(a.timestamp)
            );
        }

        this.render();
    }

    /**
     * Reset filters.
     *
     * @returns {void}
     */
    resetFilters() {
        const search = this.root.querySelector(SELECTORS.SEARCH);
        const filters = this.root.querySelectorAll(SELECTORS.FILTER);
        const sort = this.root.querySelector(SELECTORS.SORT);

        if (search) {
            search.value = '';
        }

        filters.forEach(filter => {
            filter.value = '';
        });

        if (sort) {
            sort.value = 'newest';
        }

        this.activeFilters = {
            institution: '',
            targetgroup: '',
            duration: '',
            material: ''
        };

        this.filteredItems = [...this.originalItems];
        this.handleSort('newest');
    }

    /**
     * Render filtered items.
     *
     * @returns {void}
     */
    render() {
        const visible = new Set(this.filteredItems.map(i => i.id));

        this.originalItems.forEach(item => {
            item.element.hidden = !visible.has(item.id);
        });

        this.updateItemCount();
    }

    /**
     * Update item count display.
     *
     * @returns {void}
     */
    updateItemCount() {
        const el = this.root.querySelector(SELECTORS.COUNT);

        if (!el) {
            return;
        }

        const count = this.filteredItems.length;
        const total = this.originalItems.length;

        el.textContent = count === total
            ? `Showing all ${total} entries`
            : `Showing ${count} of ${total} entries`;
    }
}

/**
 * Initialize the filter.
 *
 * @param {string} selector - The container selector
 * @returns {void}
 */
export const init = (selector) => {
    const root = document.querySelector(selector);

    if (!root) {
        return;
    }

    new PraxisboerseFilter(root);
};

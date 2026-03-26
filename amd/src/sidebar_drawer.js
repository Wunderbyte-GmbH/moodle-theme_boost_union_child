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
 * Sidebar drawer module for responsive theme navigation.
 *
 * Provides mobile burger menu and desktop sidebar drawer functionality.
 *
 * @module     theme_nwverkehrserziehung/sidebar_drawer
 * @copyright  2026
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

const SELECTORS = {
    TOGGLE: '#sidebarToggle',
    CLOSE: '#sidebarClose',
    DRAWER: '#sidebarDrawer',
    OVERLAY: '#sidebarOverlay',
    NAV_LINK: '.nav-link'
};

/**
 * SidebarDrawer class.
 *
 * Manages the responsive sidebar drawer with mobile burger menu.
 */
class SidebarDrawer {

    /**
     * Constructor.
     */
    constructor() {
        this.toggle = document.querySelector(SELECTORS.TOGGLE);
        this.close = document.querySelector(SELECTORS.CLOSE);
        this.drawer = document.querySelector(SELECTORS.DRAWER);
        this.overlay = document.querySelector(SELECTORS.OVERLAY);

        if (this.toggle && this.drawer) {
            this.registerEvents();
        }
    }

    /**
     * Register event listeners.
     *
     * @returns {void}
     */
    registerEvents() {
        // Open drawer on toggle click
        if (this.toggle) {
            this.toggle.addEventListener('click', (e) => {
                e.preventDefault();
                this.openDrawer();
            });
        }

        // Close drawer on close button click
        if (this.close) {
            this.close.addEventListener('click', (e) => {
                e.preventDefault();
                this.closeDrawer();
            });
        }

        // Close drawer on overlay click
        if (this.overlay) {
            this.overlay.addEventListener('click', () => {
                this.closeDrawer();
            });
        }

        // Close drawer when clicking a link
        const navLinks = this.drawer.querySelectorAll(SELECTORS.NAV_LINK);
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                this.closeDrawer();
            });
        });

        // Close drawer on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.drawer.classList.contains('active')) {
                this.closeDrawer();
            }
        });
    }

    /**
     * Open the drawer.
     *
     * @returns {void}
     */
    openDrawer() {
        this.drawer.classList.add('active');
        if (this.overlay) {
            this.overlay.classList.add('active');
        }
    }

    /**
     * Close the drawer.
     *
     * @returns {void}
     */
    closeDrawer() {
        this.drawer.classList.remove('active');
        if (this.overlay) {
            this.overlay.classList.remove('active');
        }
    }
}

export const init = () => {
    new SidebarDrawer();
};

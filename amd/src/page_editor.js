/**
 * AMD module for launching the CMS page editor modal.
 *
 * @module     theme_nwverkehrserziehung/page_editor
 * @copyright  2026
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import ModalForm from 'core_form/modalform';
import {get_string as getString} from 'core/str';

const SELECTORS = {
    editButton: '[data-action="nwv-page-edit"]',
    addButton: '[data-action="nwv-page-add"]',
};

/**
 * Initialise page editor modal triggers.
 */
export const init = () => {
    document.addEventListener('click', async(e) => {
        const editBtn = e.target.closest(SELECTORS.editButton);
        const addBtn = e.target.closest(SELECTORS.addButton);

        if (editBtn) {
            e.preventDefault();
            const pageId = editBtn.dataset.pageid;
            await openPageModal(pageId);
        } else if (addBtn) {
            e.preventDefault();
            await openPageModal(0);
        }
    });
};

/**
 * Open the page editor modal.
 *
 * @param {Number} pageId The page ID to edit, or 0 for a new page.
 */
const openPageModal = async(pageId) => {
    const titleKey = pageId ? 'page_edit' : 'page_add';
    const titleStr = await getString(titleKey, 'theme_nwverkehrserziehung');

    const modalForm = new ModalForm({
        formClass: 'theme_nwverkehrserziehung\\forms\\page_form',
        args: {id: pageId},
        modalConfig: {
            title: titleStr,
            large: true,
        },
        saveButtonText: await getString('savechanges'),
        returnFocus: document.activeElement,
    });

    modalForm.addEventListener(modalForm.events.FORM_SUBMITTED, (event) => {
        const data = event.detail;
        // If we're on the page itself, reload to show updated content.
        // If we're on the admin listing, also reload.
        if (data.slug && window.location.pathname.includes('/page.php')) {
            window.location.href = M.cfg.wwwroot + '/theme/nwverkehrserziehung/page.php?slug=' + data.slug;
        } else {
            window.location.reload();
        }
    });

    modalForm.show();
};

<?php
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
 * Dynamic modal form for creating/editing CMS pages.
 *
 * @package    theme_nwverkehrserziehung
 * @copyright  2026
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_nwverkehrserziehung\forms;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');
require_once($CFG->libdir . '/filelib.php');

use core_form\dynamic_form;
use theme_nwverkehrserziehung\page_manager;
use context;
use context_system;
use moodle_url;

/**
 * Dynamic Moodle form for creating/editing a CMS page.
 *
 * Can be loaded as a modal via core_form/modalform JS or rendered
 * inline as a fallback on admin/page_edit.php.
 */
class page_form extends dynamic_form {

    /**
     * Form definition.
     */
    protected function definition() {
        $mform = $this->_form;
        $pageid = $this->optional_param('id', 0, PARAM_INT);

        // Hidden ID field.
        $mform->addElement('hidden', 'id', $pageid);
        $mform->setType('id', PARAM_INT);

        // Title.
        $mform->addElement('text', 'title', get_string('page_title', 'theme_nwverkehrserziehung'), ['size' => 60]);
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', get_string('required'), 'required', null, 'client');

        // Nav title (short title for menus).
        $mform->addElement('text', 'nav_title', get_string('page_nav_title', 'theme_nwverkehrserziehung'), ['size' => 40]);
        $mform->setType('nav_title', PARAM_TEXT);
        $mform->addHelpButton('nav_title', 'page_nav_title', 'theme_nwverkehrserziehung');

        // Slug.
        $mform->addElement('text', 'slug', get_string('page_slug', 'theme_nwverkehrserziehung'), ['size' => 60]);
        $mform->setType('slug', PARAM_RAW_TRIMMED);
        $mform->addRule('slug', get_string('required'), 'required', null, 'client');
        $mform->addHelpButton('slug', 'page_slug', 'theme_nwverkehrserziehung');

        // Section.
        $sections = [
            'grundlagen' => get_string('grundlagen', 'theme_nwverkehrserziehung'),
            'netzwerk' => get_string('netzwerk', 'theme_nwverkehrserziehung'),
            'kontakt' => get_string('kontakt', 'theme_nwverkehrserziehung'),
            'footer' => get_string('page_section_footer', 'theme_nwverkehrserziehung'),
        ];
        $mform->addElement('select', 'section', get_string('page_section', 'theme_nwverkehrserziehung'), $sections);
        $mform->setType('section', PARAM_ALPHA);
        $mform->addRule('section', get_string('required'), 'required', null, 'client');

        // Parent page.
        $parentoptions = page_manager::get_parent_options($pageid);
        $mform->addElement('select', 'parent_id', get_string('page_parent', 'theme_nwverkehrserziehung'), $parentoptions);
        $mform->setType('parent_id', PARAM_INT);

        // Content (HTML editor with file handling).
        $editoroptions = self::get_editor_options();
        $mform->addElement('editor', 'content_editor', get_string('page_content', 'theme_nwverkehrserziehung'), null, $editoroptions);
        $mform->setType('content_editor', PARAM_RAW);

        // Sort order.
        $mform->addElement('text', 'sortorder', get_string('page_sortorder', 'theme_nwverkehrserziehung'), ['size' => 5]);
        $mform->setType('sortorder', PARAM_INT);
        $mform->setDefault('sortorder', 0);

        // Visible.
        $mform->addElement('advcheckbox', 'visible', get_string('page_visible', 'theme_nwverkehrserziehung'));
        $mform->setDefault('visible', 1);

        // No action buttons — the modal provides Save/Cancel.
    }

    /**
     * Return the context for dynamic submission.
     *
     * @return context
     */
    protected function get_context_for_dynamic_submission(): context {
        return context_system::instance();
    }

    /**
     * Check permissions for dynamic submission.
     *
     * @return void
     */
    protected function check_access_for_dynamic_submission(): void {
        require_capability('theme/nwverkehrserziehung:managepages', context_system::instance());
    }

    /**
     * Load existing page data for editing, or defaults for a new page.
     *
     * @return void
     */
    public function set_data_for_dynamic_submission(): void {
        $pageid = $this->optional_param('id', 0, PARAM_INT);

        $formdata = new \stdClass();
        $formdata->id = $pageid;

        if ($pageid) {
            $pagerecord = page_manager::get_by_id($pageid);
            if ($pagerecord) {
                $formdata->title = $pagerecord->title;
                $formdata->nav_title = $pagerecord->nav_title;
                $formdata->slug = $pagerecord->slug;
                $formdata->section = $pagerecord->section;
                $formdata->parent_id = $pagerecord->parent_id;
                $formdata->sortorder = $pagerecord->sortorder;
                $formdata->visible = $pagerecord->visible;

                // Prepare editor content with file handling.
                $formdata->content_editor = [
                    'text' => $pagerecord->content ?? '',
                    'format' => $pagerecord->contentformat ?? FORMAT_HTML,
                ];
                $formdata = file_prepare_standard_editor(
                    $formdata,
                    'content',
                    self::get_editor_options(),
                    context_system::instance(),
                    'theme_nwverkehrserziehung',
                    'pagecontent',
                    $pageid
                );
            }
        } else {
            $formdata->content_editor = ['text' => '', 'format' => FORMAT_HTML];
        }

        $this->set_data($formdata);
    }

    /**
     * Process form submission — save the page and handle files.
     *
     * @return array Result data (JSON-encodable).
     */
    public function process_dynamic_submission() {
        $data = $this->get_data();

        $record = new \stdClass();
        $record->id = !empty($data->id) ? $data->id : null;
        $record->title = $data->title;
        $record->nav_title = $data->nav_title ?? '';
        $record->slug = $data->slug;
        $record->section = $data->section;
        $record->parent_id = $data->parent_id;
        $record->sortorder = $data->sortorder;
        $record->visible = $data->visible;

        // Save the record first to get an ID for new pages.
        $savedid = page_manager::save($record);

        // Process editor content with file handling.
        $data->id = $savedid;
        $data = file_postupdate_standard_editor(
            $data,
            'content',
            self::get_editor_options(),
            context_system::instance(),
            'theme_nwverkehrserziehung',
            'pagecontent',
            $savedid
        );

        // Update content and format after file processing.
        $updaterecord = new \stdClass();
        $updaterecord->id = $savedid;
        $updaterecord->content = $data->content;
        $updaterecord->contentformat = $data->contentformat;
        page_manager::save($updaterecord);

        // Return the saved page data so JS can react.
        $saved = page_manager::get_by_id($savedid);
        return [
            'id' => (int)$saved->id,
            'slug' => $saved->slug,
            'title' => $saved->title,
        ];
    }

    /**
     * URL for the page — needed for editor autosave.
     *
     * @return moodle_url
     */
    protected function get_page_url_for_dynamic_submission(): moodle_url {
        $pageid = $this->optional_param('id', 0, PARAM_INT);
        return new moodle_url('/theme/nwverkehrserziehung/admin/page_edit.php', ['id' => $pageid]);
    }

    /**
     * Validate the form data.
     *
     * @param array $data Form data.
     * @param array $files Form files.
     * @return array Validation errors.
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        // Validate slug uniqueness.
        $existing = page_manager::get_by_slug($data['slug']);
        if ($existing && (int)$existing->id !== (int)$data['id']) {
            $errors['slug'] = get_string('page_slug_exists', 'theme_nwverkehrserziehung');
        }

        // Validate slug format (only lowercase, digits, hyphens, slashes).
        if (!preg_match('/^[a-z0-9\-\/]+$/', $data['slug'])) {
            $errors['slug'] = get_string('page_slug_invalid', 'theme_nwverkehrserziehung');
        }

        // Prevent self-parenting.
        if (!empty($data['id']) && (int)$data['parent_id'] === (int)$data['id']) {
            $errors['parent_id'] = get_string('page_parent_self', 'theme_nwverkehrserziehung');
        }

        return $errors;
    }

    /**
     * Get editor options for the content field.
     *
     * @return array
     */
    public static function get_editor_options(): array {
        $context = context_system::instance();
        return [
            'maxfiles' => EDITOR_UNLIMITED_FILES,
            'maxbytes' => 0,
            'noclean' => true,
            'context' => $context,
            'subdirs' => true,
        ];
    }
}

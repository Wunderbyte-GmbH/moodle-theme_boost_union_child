<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Form for managing subpages in theme nwverkehrserziehung.
 *
 * @package   theme_nwverkehrserziehung
 * @copyright 2026
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');
require_once($CFG->dirroot . '/theme/nwverkehrserziehung/classes/subpage_manager.php');

/**
 * Subpage edit form.
 */
class theme_nwverkehrserziehung_subpage_form extends moodleform {
    /**
     * Define the form.
     */
    public function definition() {
        global $CFG;
        $mform = $this->_form;
        $context = context_system::instance();
        $subpage = $this->_customdata['subpage'] ?? null;
        $tileid = $this->_customdata['tileid'] ?? null;

        // Add existing subpages links info.
        if ($tileid) {
            $mform->addElement('header', 'existingsubpages', 'Existing Subpages');

            // Get all subpages for this tile.
            $allsubpages = \theme_nwverkehrserziehung\subpage_manager::get_subpage_data($tileid);

            if (!empty($allsubpages)) {
                $links = [];
                foreach ($allsubpages as $existingpage) {
                    $url = new moodle_url(
                        '/theme/nwverkehrserziehung/page.php',
                        ['id' => $existingpage['id'], 'tile' => $tileid]
                    );
                    $linktext = $existingpage['title'] . ' (' . $existingpage['id'] . ')';
                    if ($subpage && $subpage['id'] === $existingpage['id']) {
                        $linktext .= ' [CURRENT]';
                    }
                    $links[] = html_writer::link($url, $linktext, ['target' => '_blank']);
                }
                $linkshtml = '<div class="alert alert-info">' . implode('<br>', $links) . '</div>';
                $mform->addElement('static', 'subpagelinks', '', $linkshtml);
            } else {
                $nosubpageshtml = '<div class="alert alert-warning">No other subpages exist for this tile yet.</div>';
                $mform->addElement('static', 'nosubpages', '', $nosubpageshtml);
            }
        }

        // Hidden field for subpage ID (when editing).
        $mform->addElement('hidden', 'subpageid', $subpage['id'] ?? uniqid('subpage_'));
        $mform->setType('subpageid', PARAM_ALPHANUM);

        // Hidden field for tile ID.
        $mform->addElement('hidden', 'tileid', $this->_customdata['tileid']);
        $mform->setType('tileid', PARAM_ALPHANUM);

        // Subpage ID (unique identifier).
        $mform->addElement(
            'text',
            'id',
            get_string('subpageid', 'theme_nwverkehrserziehung'),
            ['size' => '50']
        );
        $mform->setType('id', PARAM_ALPHANUM);
        $mform->addRule('id', get_string('required'), 'required', null, 'client');
        $mform->addRule('id', get_string('invalidalphanum', 'moodle'), 'alphanumeric', null, 'server');
        $mform->setDefault('id', $subpage['id'] ?? '');
        if ($subpage) {
            $mform->hardFreeze('id');
        }

        // Subpage title.
        $mform->addElement(
            'text',
            'title',
            get_string('title', 'moodle'),
            ['size' => '50']
        );
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', get_string('required'), 'required', null, 'client');
        $mform->setDefault('title', $subpage['title'] ?? '');

        // Subpage content (HTML editor).
        $mform->addElement(
            'editor',
            'content',
            get_string('content', 'moodle'),
            null,
            ['subdirs' => 1, 'maxbytes' => 0, 'noclean' => 1, 'context' => $context]
        );
        $mform->setType('content', PARAM_RAW);
        $mform->addRule('content', get_string('required'), 'required', null, 'client');
        $mform->setDefault('content', [
            'text' => $subpage['content'] ?? '',
        ]);

        // Navigation items JSON.
        $mform->addElement(
            'textarea',
            'navigation_json',
            get_string('navigation', 'moodle'),
            ['rows' => '6', 'cols' => '50']
        );
        $mform->setType('navigation_json', PARAM_RAW);
        $mform->addHelpButton('navigation_json', 'subpage_navigation_help', 'theme_nwverkehrserziehung');

        if ($subpage && isset($subpage['navigation']) && is_array($subpage['navigation'])) {
            $mform->setDefault('navigation_json', json_encode($subpage['navigation'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        } else {
            $mform->setDefault('navigation_json', json_encode([], JSON_PRETTY_PRINT));
        }

        // Buttons.
        $this->add_action_buttons(true, get_string('savechanges'));
    }

    /**
     * Validate form data.
     *
     * @param array $data Form data.
     * @param array $files Form files.
     * @return array Validation errors.
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        // Validate navigation JSON.
        if (!empty($data['navigation_json'])) {
            $navjson = trim($data['navigation_json']);
            if ($navjson && $navjson !== '[]') {
                try {
                    $nav = json_decode($navjson, true);
                    if (!is_array($nav)) {
                        $errors['navigation_json'] = get_string('invalidentrynavjson', 'moodle');
                    }
                } catch (\Exception $e) {
                    $errors['navigation_json'] = get_string('invalidentrynavjson', 'moodle');
                }
            }
        }

        return $errors;
    }
}

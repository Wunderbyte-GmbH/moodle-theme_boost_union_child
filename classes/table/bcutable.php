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
 * Search results for managers are shown in a table (student search results use the template searchresults_student).
 *
 * @package theme_boost_union_child
 * @copyright 2025 Wunderbyte GmbH
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace theme_boost_union_child\table;

use moodle_url;
use html_writer;
defined('MOODLE_INTERNAL') || die();

/**
 * Class to handle search results for managers are shown in a table.
 *
 * @package mod_booking
 * @copyright 2023 Wunderbyte GmbH
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class bcutable extends \mod_booking\table\bookingoptions_wbtable {
    /**
     *
     * @param object $values Contains object with all the values of record.
     * @return string $invisible Returns visibility of the booking option as string.
     * @throws coding_exception
     */
    public function col_progress($values) {
        global $USER;
        if ($values->courseid) {
            $completion = \core_completion\progress::get_course_progress_percentage(get_course($values->courseid), $USER->id);
            return ($completion === null) ? '' : '| ' . $completion . '% completado';
        }
    }

    /**
     *
     * @param object $values Contains object with all the values of record.
     * @return string $invisible Returns visibility of the booking option as string.
     * @throws coding_exception
     */
    public function col_durata($values) {
        global $USER;
        if ($values->durata) {
           
        }
    }

    /**
     *
     * @param object $values Contains object with all the values of record.
     * @return string $invisible Returns visibility of the booking option as string.
     * @throws coding_exception
     */
    public function col_zoom($values) {
        global $USER;
        $id = 5;
        $btntext = get_string('join_meeting', 'mod_zoom');
        $buttonhtml = html_writer::tag('button', $btntext, ['type' => 'submit', 'class' => 'btn btn-primary']);
        $aurl = new moodle_url('/mod/zoom/loadmeeting.php', ['id' => $id]);
        $buttonhtml .= html_writer::input_hidden_params($aurl);
        $link = html_writer::tag('form', $buttonhtml, ['action' => $aurl->out_omit_querystring(), 'target' => '_blank']);
        return $link;
    }
}

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
    public function col_zoom($values) {
        global $USER, $DB;
        $course = $DB->get_record('course', array('id'=>$values->courseid), '*', MUST_EXIST);
        $zooms = get_all_instances_in_course('zoom', $course);
        if (empty($zooms)) {
            return '';
        }
        $z = array_shift($zooms);
        [$inprogress, $available, $finished] = zoom_get_state($z);
        if (!$available) {
            return '';
        }
        $btntext = get_string('join_meeting', 'mod_zoom');
        $buttonhtml = html_writer::tag('button', $btntext, ['type' => 'submit', 'class' => 'btn btn-primary']);
        $aurl = new moodle_url('/mod/zoom/loadmeeting.php', ['id' => $z->id]);
        $buttonhtml .= html_writer::input_hidden_params($aurl);
        $link = html_writer::tag('form', $buttonhtml, ['action' => $aurl->out_omit_querystring(), 'target' => '_blank']);
        return $link;
    }

    public function col_starting($values) {
        if (!empty($values->coursestarttime)) {
            $icon = html_writer::tag('i', '', ['class' => 'far fa-calendar fa-fw']);
            return $icon . ' ' . date('d.m.Y H:i', $values->coursestarttime);
        }
    }
}

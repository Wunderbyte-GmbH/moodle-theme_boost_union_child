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
 * My Courses page with custom theme and block regions.
 *
 * @package     theme_boost_union_child
 * @copyright   2025 Wunderbyte GmbH <info@wunderbyte.at>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_login();

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
$PAGE->set_url('/theme/boost_union_child/home.php');

echo $OUTPUT->header();

global $USER, $DB;

// If you expect a courseid coming from the URL or form, you need this:
$values = new stdClass();
$values->courseid = optional_param('courseid', 0, PARAM_INT);

var_dump($values); // --- DUMP #1 ---

if (empty($values->courseid)) {
    echo '';
    echo $OUTPUT->footer();
    exit;
}

$course = $DB->get_record('course', ['id' => $values->courseid], '*');
var_dump($course); // --- DUMP #2 ---

if (empty($course)) {
    echo '';
    echo $OUTPUT->footer();
    exit;
}

$zooms = get_all_instances_in_course('zoom', $course);
var_dump($zooms); // --- DUMP #3 ---

if (empty($zooms)) {
    echo '';
    echo $OUTPUT->footer();
    exit;
}

$z = array_shift($zooms);
var_dump($z); // --- DUMP #4 ---

[$inprogress, $available, $finished] = zoom_get_state($z);
var_dump($inprogress, $available, $finished); // --- DUMP #5 ---

if (!$available) {
    echo 'Not available';
    echo $OUTPUT->footer();
    exit;
}

$btntext = get_string('join_meeting', 'mod_zoom');
$aurl = new moodle_url('/mod/zoom/loadmeeting.php', ['id' => $z->id]);
var_dump($aurl); // --- DUMP #6 ---

$buttonhtml = html_writer::tag('button', 'Participa', ['type' => 'submit', 'class' => 'btn btn-primary']);
$buttonhtml .= html_writer::input_hidden_params($aurl);
$link = html_writer::tag('form', $buttonhtml, ['action' => $aurl->out_omit_querystring(), 'target' => '_blank']);
echo $link;

echo $OUTPUT->footer();


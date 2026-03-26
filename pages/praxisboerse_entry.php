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
 * Praxisbörse single entry view page.
 *
 * @package   theme_nwverkehrserziehung
 * @copyright 2024
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');

use theme_nwverkehrserziehung\praxisboerse_manager;


$CFG->theme = 'nwverkehrserziehung';

// Get the record ID parameter.
$recordid = required_param('id', PARAM_INT);

// Check if user is logged in.
require_login();

// Set up the page.
$PAGE->set_url(new moodle_url('/theme/nwverkehrserziehung/pages/praxisboerse_entry.php', ['id' => $recordid]));
$PAGE->set_context(\context_system::instance());

// Get the entry data.
$entry = praxisboerse_manager::get_entry($recordid);

if (!$entry) {
    throw new moodle_exception('notfound', 'error');
}

$PAGE->set_title($entry->title);

// Convert moodle_url to string for template
if (isset($entry->entryurl) && is_object($entry->entryurl)) {
    $entry->entryurl = $entry->entryurl->out(false);
}

// Output the page.
echo $OUTPUT->header();

// Render the template.
echo $OUTPUT->render_from_template('theme_nwverkehrserziehung/praxisboerse_entry2', (array)$entry);
echo $OUTPUT->footer();

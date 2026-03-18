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
 * Kontakt page for NWV theme.
 *
 * @package   theme_nwverkehrserziehung
 * @copyright 2026
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');

// Force load NWV theme for this page.
$CFG->theme = 'nwverkehrserziehung';
$PAGE->set_context(context_system::instance());

$PAGE->set_url(new moodle_url('/theme/nwverkehrserziehung/Kontakt.php'));
$PAGE->set_pagelayout('frontpage');
$PAGE->set_title(get_string('kontakt', 'theme_nwverkehrserziehung'));
// $PAGE->set_heading(get_string('kontakt', 'theme_nwverkehrserziehung'));

// Get fullpage content from tile3.
$config = get_config('theme_nwverkehrserziehung');

// Build pix path URL.
$pixurl = new moodle_url('/theme/nwverkehrserziehung/pix');

$content = [
    'pix_path' => $pixurl->out(false),
];

// Output page using standard Moodle renderer.
echo $OUTPUT->header();
echo $OUTPUT->render_from_template('theme_nwverkehrserziehung/logoarc', $content);
echo $OUTPUT->footer();

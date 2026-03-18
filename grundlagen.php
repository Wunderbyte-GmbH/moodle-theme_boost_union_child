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
 * Grundlagen page for NWV theme.
 *
 * @package   theme_nwverkehrserziehung
 * @copyright 2026
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');

// Force load NWV theme for this page.
$CFG->theme = 'nwverkehrserziehung';
$PAGE->set_context(context_system::instance());

$PAGE->set_url(new moodle_url('/theme/nwverkehrserziehung/Grundlagen.php'));
$PAGE->set_pagelayout('frontpage');
$PAGE->set_title(get_string('grundlagen', 'theme_nwverkehrserziehung'));
$PAGE->set_heading(get_string('grundlagen', 'theme_nwverkehrserziehung'));

// Get fullpage content from tile1.
$config = get_config('theme_nwverkehrserziehung');
$content = $config->tile1fullpage_content ?? '';
$context = context_system::instance();

// Get tile1 image if it exists.
$imageurl = null;
if (!empty($config->tile1image)) {
    $imageurl = moodle_url::make_pluginfile_url(
        $context->id,
        'theme_nwverkehrserziehung',
        'tile1image',
        0,
        '/',
        $config->tile1image
    )->out();
}

// Prepare data for template.
$data = [
    'title' => get_string('grundlagen', 'theme_nwverkehrserziehung'),
    'content' => $content,
    'imageurl' => $imageurl,
];


// Output page using standard Moodle renderer.
echo $OUTPUT->header();

// Display with image on left and text wrapped around it.
if ($imageurl) {
    $imageattrs = [
        'class' => 'fullpage-image',
        'style' => 'float: left; margin-right: 20px; margin-bottom: 15px; max-width: 300px; height: auto;',
    ];
    $imagehtml = html_writer::img($imageurl, 'Grundlagen', $imageattrs);
    echo html_writer::div($imagehtml . $content, 'fullpage-content container my-5', ['style' => 'overflow: auto;']);
} else {
    echo html_writer::div($content, 'fullpage-content container my-5');
}

// Navigation to subsites.
$navhtml = '<div class="container my-5"><h3>Weitere Themen</h3><div class="row">';
$navhtml .= '<div class="col-md-6 mb-3"><a href="grundlagen_sicherheit.php" class="btn btn-primary btn-block btn-lg">';
$navhtml .= '<strong>Sicherheit im Straßenverkehr</strong></a></div>';
$navhtml .= '<div class="col-md-6 mb-3"><a href="grundlagen_regeln.php" class="btn btn-primary btn-block btn-lg">';
$navhtml .= '<strong>Verkehrsregeln und Vorschriften</strong></a></div>';
$navhtml .= '<div class="col-md-6 mb-3"><a href="grundlagen_verhalten.php" class="btn btn-primary btn-block btn-lg">';
$navhtml .= '<strong>Fahrtechniken und Fahrtverhalten</strong></a></div>';
$navhtml .= '<div class="col-md-6 mb-3"><a href="grundlagen_umwelt.php" class="btn btn-primary btn-block btn-lg">';
$navhtml .= '<strong>Umweltschonung und Nachhaltigkeit</strong></a></div>';
$navhtml .= '</div></div>';
echo $navhtml;

echo $OUTPUT->footer();

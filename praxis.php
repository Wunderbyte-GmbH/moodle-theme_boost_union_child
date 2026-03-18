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
 * Praxisbörse page for NWV theme - Austrian traffic education network.
 *
 * This page displays the Praxisbörse data entries sourced from a configured
 * mod/data activity. It includes client-side filtering via AMD modules
 * and server-side caching for performance optimization.
 *
 * @package   theme_nwverkehrserziehung
 * @copyright 2024
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/datalib.php');

use theme_nwverkehrserziehung\praxisboerse_manager;

// Force load NWV theme for this page.
$CFG->theme = 'nwverkehrserziehung';
$PAGE->set_context(context_system::instance());

$PAGE->set_url(new moodle_url('/theme/nwverkehrserziehung/praxisbörse.php'));
$PAGE->set_pagelayout('frontpage');
$PAGE->set_title(get_string('praxisboerse', 'theme_nwverkehrserziehung'));
$PAGE->set_heading(get_string('praxisboerse', 'theme_nwverkehrserziehung'));

// Get Praxisbörse data from manager (with caching).
$dataitems = praxisboerse_manager::get_data();

// Get available filter values
$filtervalues = praxisboerse_manager::get_filter_values();

// Get Praxisbörse description from theme settings.
$config = get_config('theme_nwverkehrserziehung');
$description = $config->praxisboerse_description ?? '';

// Convert filter values to template format
$institutionsoptions = [];
foreach ($filtervalues['institutions'] as $inst) {
    $institutionsoptions[] = [
        'value' => strtolower(str_replace(' ', '-', $inst)),
        'label' => $inst,
    ];
}

$targetgroupsoptions = [];
foreach ($filtervalues['targetgroups'] as $tg) {
    $targetgroupsoptions[] = [
        'value' => strtolower(str_replace(' ', '-', $tg)),
        'label' => $tg,
    ];
}

$durationsoptions = [];
foreach ($filtervalues['durations'] as $dur) {
    $durationsoptions[] = [
        'value' => strtolower(str_replace(' ', '-', $dur)),
        'label' => $dur,
    ];
}

$materialsoptions = [];
foreach ($filtervalues['materials'] as $mat) {
    $materialsoptions[] = [
        'value' => strtolower(str_replace(' ', '-', $mat)),
        'label' => $mat,
    ];
}

// Prepare data for template.
$data = [
    'praxisboerse_title' => get_string('praxisboerse', 'theme_nwverkehrserziehung'),
    'items' => $dataitems,
    'has_items' => !empty($dataitems),
    'institutions_options' => $institutionsoptions,
    'targetgroups_options' => $targetgroupsoptions,
    'durations_options' => $durationsoptions,
    'materials_options' => $materialsoptions,
];
$context = context_system::instance();

// Get tile3 image if it exists.
$imageurl = null;
if (!empty($config->tile3image)) {
    $imageurl = moodle_url::make_pluginfile_url(
        $context->id,
        'theme_nwverkehrserziehung',
        'tile3image',
        0,
        '/',
        $config->tile3image
    )->out();
}


// Output page using standard Moodle renderer.
echo $OUTPUT->header();

// Display with image on left and text wrapped around it.
if ($imageurl) {
    $imageattrs = [
        'class' => 'fullpage-image',
        'style' => 'float: left; margin-right: 20px; margin-bottom: 15px; max-width: 300px; height: auto;',
    ];
    $imagehtml = html_writer::img($imageurl, 'Praxisbörse', $imageattrs);
    echo html_writer::div($imagehtml . $description, 'fullpage-content container my-5', ['style' => 'overflow: auto;']);
} else {
    echo html_writer::div($description, 'fullpage-content container my-5');
}
echo $OUTPUT->render_from_template('theme_nwverkehrserziehung/praxisboerse', $data);

echo $OUTPUT->footer();

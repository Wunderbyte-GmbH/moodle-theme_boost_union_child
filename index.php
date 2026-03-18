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
 * Frontpage for NWV theme - Austrian traffic education network.
 *
 * @package   theme_nwverkehrserziehung
 * @copyright 2024
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');

// Force load NWV theme for this page
$CFG->theme = 'nwverkehrserziehung';

$PAGE->set_context(context_system::instance());

$PAGE->set_url(new moodle_url('/theme/nwverkehrserziehung/index.php'));
$PAGE->set_pagelayout('frontpage');
$PAGE->set_title(get_string('frontpage', 'theme_nwverkehrserziehung'));

// Get welcome text and banner config
$welcometext = get_config('theme_nwverkehrserziehung', 'welcometext')
    ?? get_string('welcometextdefault', 'theme_nwverkehrserziehung');
$bannertitle = get_config('theme_nwverkehrserziehung', 'bannertitle') ?? 'Aktuelle Meldungen';

// Get tile settings
$tiles = [];
for ($i = 1; $i <= 4; $i++) {
    $tileimage = '';
    $tilefilearea = get_config('theme_nwverkehrserziehung', 'tile' . $i . 'image');
    if ($tilefilearea) {
        $context = context_system::instance();
        $tileimage = moodle_url::make_pluginfile_url(
            $context->id,
            'theme_nwverkehrserziehung',
            'tile' . $i . 'image',
            0,
            '/',
            $tilefilearea
        )->out();
    }
    $tiles[$i] = [
        'title' => get_config('theme_nwverkehrserziehung', 'tile' . $i . 'title')
            ?? get_string('tile' . $i . 'titlesetting', 'theme_nwverkehrserziehung'),
        'text' => get_config('theme_nwverkehrserziehung', 'tile' . $i . 'text')
            ?? get_string('tile' . $i . 'textdefault', 'theme_nwverkehrserziehung'),
        'image' => $tileimage,
        'link' => get_config('theme_nwverkehrserziehung', 'tile' . $i . 'link') ?? '#',
    ];
}

// Prepare data for template
$data = [
    'welcometext' => $welcometext,
    'bannertitle' => $bannertitle,
    'tile1title' => $tiles[1]['title'],
    'tile1text' => $tiles[1]['text'],
    'tile1image' => $tiles[1]['image'],
    'tile1link' => $tiles[1]['link'],
    'tile2title' => $tiles[2]['title'],
    'tile2text' => $tiles[2]['text'],
    'tile2image' => $tiles[2]['image'],
    'tile2link' => $tiles[2]['link'],
    'tile3title' => $tiles[3]['title'],
    'tile3text' => $tiles[3]['text'],
    'tile3image' => $tiles[3]['image'],
    'tile3link' => $tiles[3]['link'],
    'tile4title' => $tiles[4]['title'],
    'tile4text' => $tiles[4]['text'],
    'tile4image' => $tiles[4]['image'],
    'tile4link' => $tiles[4]['link'],
];

// Output page using standard Moodle renderer
echo $OUTPUT->header();
echo $OUTPUT->render_from_template('theme_nwverkehrserziehung/frontpage', $data);
echo $OUTPUT->footer();

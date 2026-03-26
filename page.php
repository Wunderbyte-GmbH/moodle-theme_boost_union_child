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
 * Generic dynamic page loader for tile-based subpages.
 *
 * @package   theme_nwverkehrserziehung
 * @copyright 2026
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/theme/nwverkehrserziehung/classes/subpage_manager.php');

use theme_nwverkehrserziehung\subpage_manager;

// No login required for public pages.

// Force load NWV theme for this page.
$CFG->theme = 'nwverkehrserziehung';

// Get required parameters.
$pageid = required_param('id', PARAM_ALPHANUM);
$tileid = optional_param('tile', 'tile1', PARAM_ALPHANUM);

// Setup page context.
$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/theme/nwverkehrserziehung/page.php', ['id' => $pageid, 'tile' => $tileid]));
$PAGE->set_pagelayout('incourse');

// Get subpage data.
$subpage = subpage_manager::get_subpage($tileid, $pageid);

if (!$subpage) {
    throw new moodle_exception('pagenotfound', 'error');
}

// Set page title.
$PAGE->set_title($subpage['title']);

// Get all subpages for this tile as navigation.
$navitems = subpage_manager::get_navigation($tileid, $pageid);

// If subpage has its own navigation items, use those instead.
if (!empty($subpage['navigation'])) {
    $navitems = $subpage['navigation'];
}

echo $OUTPUT->header();

// Build layout context.
$layoutcontext = [
    'sidebar_title' => $subpage['title'],
    'sidebar_items' => $navitems,
    'content' => $subpage['content'],
];

// Render the sidebar layout template.
echo $OUTPUT->render_from_template('theme_nwverkehrserziehung/sidebar_layout', $layoutcontext);

echo $OUTPUT->footer();

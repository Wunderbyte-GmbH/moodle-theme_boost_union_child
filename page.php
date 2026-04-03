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
 * Dynamic content page loader for the NWV theme CMS.
 *
 * Loads a page from the theme_nwv_pages table by slug and renders it
 * with the content_page template, including breadcrumbs and sidebar nav.
 *
 * @package   theme_nwverkehrserziehung
 * @copyright 2026
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Load Moodle bootstrap.
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/filelib.php');

// Force NWV theme for this request (after config.php, before any output).
//s$CFG->theme = 'nwverkehrserziehung';

use theme_nwverkehrserziehung\page_manager;

// Get slug parameter.
$slug = required_param('slug', PARAM_RAW_TRIMMED);

// Sanitise slug — only allow lowercase alphanumeric, hyphens, slashes.
$slug = preg_replace('/[^a-z0-9\-\/]/', '', strtolower($slug));

// Load page from DB.
$pagerecord = page_manager::get_by_slug($slug);

if (!$pagerecord || !$pagerecord->visible) {
    throw new moodle_exception('pagenotfound', 'error');
}

// Setup page context.
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/theme/nwverkehrserziehung/page.php', ['slug' => $slug]));
$PAGE->set_pagelayout('frontpage');
$PAGE->set_title($pagerecord->title);
$PAGE->set_heading($pagerecord->title);

// Rewrite pluginfile URLs in content for proper image serving.
$content = file_rewrite_pluginfile_urls(
    $pagerecord->content ?? '',
    'pluginfile.php',
    $context->id,
    'theme_nwverkehrserziehung',
    'pagecontent',
    $pagerecord->id
);

// Build breadcrumbs.
$breadcrumbs = page_manager::get_breadcrumbs($pagerecord);

// Build section sidebar navigation.
$sidebarnav = page_manager::get_section_nav($pagerecord->section, (int)$pagerecord->id);

// Section display title.
$sectiontitles = [
    'grundlagen' => get_string('grundlagen', 'theme_nwverkehrserziehung'),
    'netzwerk' => get_string('netzwerk', 'theme_nwverkehrserziehung'),
    'kontakt' => get_string('kontakt', 'theme_nwverkehrserziehung'),
    'footer' => get_string('page_section_footer', 'theme_nwverkehrserziehung'),
];
$sectiontitle = $sectiontitles[$pagerecord->section] ?? $pagerecord->section;

// Check if user can edit pages.
$canadmin = isloggedin() && !isguestuser() && has_capability(
    'theme/nwverkehrserziehung:managepages',
    $context
);
echo $OUTPUT->header();

// Load the modal form JS if user can edit.
if ($canadmin) {
    $PAGE->requires->js_call_amd('theme_nwverkehrserziehung/page_editor', 'init');
}

// NOTE: Bootstrap is already loaded by the boost drawers.mustache layout template
// via require(['theme_boost/loader', ...]). Do NOT call js_call_amd on it — the
// loader module has no init() export and would cause a JS error.

// Build template context.
$templatecontext = [
    'title' => format_string($pagerecord->title),
    'content' => format_text($content, $pagerecord->contentformat, ['context' => $context]),
    'breadcrumbs' => $breadcrumbs,
    'has_sidebar' => !empty($sidebarnav),
    'sidebar_items' => $sidebarnav,
    'section_title' => $sectiontitle,
    'wwwroot' => $CFG->wwwroot,
    'canadmin' => $canadmin,
    'pageid' => (int)$pagerecord->id,
];

echo $OUTPUT->render_from_template('theme_nwverkehrserziehung/content_page', $templatecontext);
echo $OUTPUT->footer();

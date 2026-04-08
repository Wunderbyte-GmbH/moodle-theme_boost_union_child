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
 * A drawer based layout for the boost theme.
 *
 * @package   theme_nwverkehrserziehung
 * @copyright 2021 Bas Brands
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/behat/lib.php');
require_once($CFG->dirroot . '/course/lib.php');

// Add block button in editing mode.
$addblockbutton = $OUTPUT->addblockbutton();

if (isloggedin()) {
    $courseindexopen = (get_user_preferences('drawer-open-index', true) == true);
    $blockdraweropen = (get_user_preferences('drawer-open-block') == true);
} else {
    $courseindexopen = false;
    $blockdraweropen = false;
}

if (defined('BEHAT_SITE_RUNNING') && get_user_preferences('behat_keep_drawer_closed') != 1) {
    $blockdraweropen = true;
}

$extraclasses = ['uses-drawers'];
if ($courseindexopen) {
    $extraclasses[] = 'drawer-open-index';
}

$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = (strpos($blockshtml, 'data-block=') !== false || !empty($addblockbutton));
if (!$hasblocks) {
    $blockdraweropen = false;
}
$courseindex = core_course_drawer();
if (!$courseindex) {
    $courseindexopen = false;
}

$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$forceblockdraweropen = $OUTPUT->firstview_fakeblocks();
$customnavigation = get_custom_navigation();
$secondarynavigation = false;
$overflow = '';
if ($PAGE->has_secondary_navigation()) {
    $tablistnav = $PAGE->has_tablist_secondary_navigation();
    $moremenu = new \core\navigation\output\more_menu($PAGE->secondarynav, 'nav-tabs', true, $tablistnav);
    $secondarynavigation = $moremenu->export_for_template($OUTPUT);
    $overflowdata = $PAGE->secondarynav->get_overflow_menu_data();
    if (!is_null($overflowdata)) {
        $overflow = $overflowdata->export_for_template($OUTPUT);
    }
}

$primary = new core\navigation\output\primary($PAGE);
$renderer = $PAGE->get_renderer('core');
$primarymenu = $primary->export_for_template($renderer);
$buildregionmainsettings = !$PAGE->include_region_main_settings_in_header_actions() && !$PAGE->has_secondary_navigation();
// If the settings menu will be included in the header then don't add it here.
$regionmainsettingsmenu = $buildregionmainsettings ? $OUTPUT->region_main_settings_menu() : false;

$header = $PAGE->activityheader;
$headercontent = $header->export_for_template($renderer);

// Get logo URL if uploaded.
$logofile = get_config('theme_nwverkehrserziehung', 'logo');
$logourl = null;
if ($logofile) {
    $context = context_system::instance();
    $logourl = moodle_url::make_pluginfile_url(
        $context->id,
        'theme_nwverkehrserziehung',
        'logo',
        0,
        '/',
        $logofile
    );
}

// Get sidebar navigation items from page custom data.
$sidebaritems = [];
if (isset($PAGE->custom_data) && is_array($PAGE->custom_data) && isset($PAGE->custom_data['sidebar_items'])) {
    $sidebaritems = $PAGE->custom_data['sidebar_items'];
}

// Prepare sidebar context if items are provided.
$sidebarcontext = null;
if (!empty($sidebaritems)) {
    $sidebarcontext = [
        'title' => $PAGE->custom_data['sidebar_title'] ?? 'Navigation',
        'items' => $sidebaritems,
    ];
}

// Check if user can manage pages (for admin cog in navbar).
$canadmin = isloggedin() && !isguestuser() && has_capability(
    'theme/nwverkehrserziehung:managepages',
    context_system::instance()
);

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'courseindexopen' => $courseindexopen,
    'blockdraweropen' => $blockdraweropen,
    'courseindex' => $courseindex,
    'usermenu' => $primarymenu['user'],
    'langmenu' => $primarymenu['lang'],
    'forceblockdraweropen' => $forceblockdraweropen,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    'overflow' => $overflow,
    'headercontent' => $headercontent,
    'addblockbutton' => $addblockbutton,
    'customnavigation' => $customnavigation,
    'logourl' => $logourl ? $logourl->out() : null,
    'canadmin' => $canadmin,
];

echo $OUTPUT->render_from_template('theme_boost/drawers', $templatecontext);

/**
 * Get custom navigation menu from theme settings
 *
 * @return array Navigation menu array for template
 */
function get_custom_navigation() {
    $navjson = get_config('theme_nwverkehrserziehung', 'customnavigation');

    if (empty($navjson)) {
        return [];
    }

    try {
        $navigation = json_decode($navjson, true);
        if (!is_array($navigation)) {
            return [];
        }
        return $navigation;
    } catch (\Exception $e) {
        return [];
    }
}

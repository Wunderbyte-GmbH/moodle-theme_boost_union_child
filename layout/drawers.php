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
 * Theme Boost Union Child - Drawers page layout.
 *
 * Extends boost_union's drawers layout. On the front page, injects the custom
 * theme_boost_union_child/frontpage template into the template context so that
 * drawers.mustache can render it via {{> theme_boost_union_child/frontpage}}.
 *
 * @package   theme_boost_union_child
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// All boost_union includes live in this directory.
$budir = $CFG->dirroot . '/theme/boost_union/layout';

require_once($CFG->libdir . '/behat/lib.php');
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->dirroot . '/theme/boost_union/locallib.php');

// Add activity navigation if the feature is enabled.
$activitynavigation = get_config('theme_boost_union', 'activitynavigation');
if ($activitynavigation == THEME_BOOST_UNION_SETTING_SELECT_YES) {
    $PAGE->theme->usescourseindex = false;
}

// Add block button in editing mode.
$addblockbutton = $OUTPUT->addblockbutton();

if (isloggedin()) {
    $courseindexopen = (get_user_preferences('drawer-open-index', true) == true);

    if (isguestuser()) {
        $sitehomerighthandblockdrawerserverconfig = get_config('theme_boost_union', 'showsitehomerighthandblockdraweronguestlogin');
    } else {
        $sitehomerighthandblockdrawerserverconfig = get_config('theme_boost_union', 'showsitehomerighthandblockdraweronfirstlogin');
    }

    $isadminsettingyes = ($sitehomerighthandblockdrawerserverconfig == THEME_BOOST_UNION_SETTING_SELECT_YES);
    $blockdraweropen = (get_user_preferences('drawer-open-block', $isadminsettingyes)) == true;
} else {
    $courseindexopen = false;
    $blockdraweropen = false;

    if (get_config('theme_boost_union', 'showsitehomerighthandblockdraweronvisit') == THEME_BOOST_UNION_SETTING_SELECT_YES) {
        $blockdraweropen = true;
    }
}

if (defined('BEHAT_SITE_RUNNING') && get_user_preferences('behat_keep_drawer_closed') != 1) {
    try {
        if (
            get_config('theme_boost_union', 'showsitehomerighthandblockdraweronvisit') === false &&
            get_config('theme_boost_union', 'showsitehomerighthandblockdraweronguestlogin') === false &&
            get_config('theme_boost_union', 'showsitehomerighthandblockdraweronfirstlogin') === false
        ) {
            $blockdraweropen = true;
        }
    } catch (Exception $e) {
        echo $e->getMessage();
        $blockdraweropen = true;
    }
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

$forceblockdraweropen = $OUTPUT->firstview_fakeblocks();

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

$primary = new theme_boost_union\output\navigation\primary($PAGE);
$renderer = $PAGE->get_renderer('core');
$primarymenu = $primary->export_for_template($renderer);

if (isset($primarymenu['includesmartmenu']) && $primarymenu['includesmartmenu'] == true) {
    $extraclasses[] = 'theme-boost-union-smartmenu';
}

if (!empty($primarymenu['bottombar']) && !empty($primarymenu['bottombar']['drawer']) && !empty($primarymenu['includesmartmenu'])) {
    $extraclasses[] = 'theme-boost-union-bottombar';
}

require_once($budir . '/includes/courseindex.php');

$buildregionmainsettings = !$PAGE->include_region_main_settings_in_header_actions() && !$PAGE->has_secondary_navigation();
$regionmainsettingsmenu = $buildregionmainsettings ? $OUTPUT->region_main_settings_menu() : false;

$bodyattributes = $OUTPUT->body_attributes($extraclasses);

$header = $PAGE->activityheader;
$headercontent = $header->export_for_template($renderer);

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'courseindexopen' => $courseindexopen,
    'blockdraweropen' => $blockdraweropen,
    'courseindex' => $courseindex,
    'primarymoremenu' => $primarymenu['moremenu'],
    'secondarymoremenu' => $secondarynavigation ?: false,
    'mobileprimarynav' => $primarymenu['mobileprimarynav'],
    'usermenu' => $primarymenu['user'],
    'langmenu' => $primarymenu['lang'],
    'forceblockdraweropen' => $forceblockdraweropen,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    'overflow' => $overflow,
    'headercontent' => $headercontent,
    'addblockbutton' => $addblockbutton,
];

require_once($budir . '/includes/courserelatedhints.php');
require_once($budir . '/includes/blockregions.php');
require_once($budir . '/includes/backtotopbutton.php');
require_once($budir . '/includes/footerbuttons.php');
require_once($budir . '/includes/scrollspy.php');
require_once($budir . '/includes/footnote.php');
require_once($budir . '/includes/staticpages.php');
require_once($budir . '/includes/accessibilitypages.php');
require_once($budir . '/includes/footer.php');
require_once($budir . '/includes/javascriptdisabledhint.php');
require_once($budir . '/includes/infobanners.php');
require_once($budir . '/includes/navbar.php');

if ($PAGE->pagelayout == 'frontpage') {
    require_once($budir . '/includes/advertisementtiles.php');
    require_once($budir . '/includes/slider.php');
}

require_once($budir . '/includes/smartmenus.php');

// ============================================================
// Boost Union Child: inject custom frontpage template data.
// ============================================================
if ($PAGE->pagelayout == 'frontpage') {
    $templatecontext['isfrontpage'] = true;
    $templatecontext['haslogin'] = isloggedin() && !isguestuser();
    $templatecontext['config'] = ['wwwroot' => $CFG->wwwroot];

    // Load top-level course categories for the cards section.
    $categories = [];
    try {
        $topcats = core_course_category::get(0)->get_children();
        foreach ($topcats as $cat) {
            $categories[] = [
                'name'        => format_string($cat->get_formatted_name()),
                'description' => format_text($cat->description, $cat->descriptionformat,
                    ['context' => context_coursecat::instance($cat->id)]),
                'viewurl'     => (new moodle_url('/course/index.php', ['categoryid' => $cat->id]))->out(false),
            ];
        }
    } catch (Exception $e) {
        // Silently ignore if categories can't be loaded.
        debugging('boost_union_child frontpage: could not load categories: ' . $e->getMessage(), DEBUG_DEVELOPER);
    }
    $templatecontext['categories'] = $categories;

    // All booking options list (card layout).
    $bookingcmid = (int) get_config('theme_boost_union_child', 'frontpagebookingcmid');
    $allbookinghtml = '';
    if ($bookingcmid > 0 && class_exists('\\mod_booking\\shortcodes')) {
        try {
            $allbookinghtml = \mod_booking\shortcodes::allbookingoptions(
                'allbookingoptions',
                ['type' => 'cards', 'cmid' => $bookingcmid, 'all' => '1'],
                null,
                (object)[],
                function($c) { return $c; }
            );
        } catch (Throwable $e) {
            debugging('boost_union_child frontpage: allbookingoptions render error: ' . $e->getMessage(), DEBUG_DEVELOPER);
        }
    }
    $templatecontext['allbookingoptionshtml'] = $allbookinghtml;
    $templatecontext['hasallbookingoptions'] = !empty($allbookinghtml);

    // AI instructions panel: render shortcode output if a booking cmid is configured.
    $aihtml = '';
    if ($bookingcmid > 0 && class_exists('\\mod_booking\\shortcodes')) {
        try {
            // Build a minimal shortcode-style args array and call the handler directly.
            $aihtml = \mod_booking\shortcodes::aiinstructions(
                'aiinstructions',
                ['cmid' => $bookingcmid],
                null,
                (object)[],
                function($c) { return $c; }
            );
        } catch (Throwable $e) {
            debugging('boost_union_child frontpage: aiinstructions render error: ' . $e->getMessage(), DEBUG_DEVELOPER);
        }
    }
    $templatecontext['aiinstructionshtml'] = $aihtml;
    $templatecontext['hasaiinstructions'] = !empty($aihtml);
}

echo $OUTPUT->render_from_template('theme_boost/drawers', $templatecontext);

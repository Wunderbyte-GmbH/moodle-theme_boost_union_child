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
 * Theme Boost Union Child - Theme config
 *
 * @package    theme_boost_union_child
 * @copyright  2024 Alexander Bias <bias@alexanderbias.de>
 *             based on code by Lars Bonczek
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Let codechecker ignore some sniffs for this file as we do not need a login check here..
// phpcs:disable moodle.Files.RequireLogin.Missing

// As a start, inherit the whole theme config from Boost Union.
// This move will save us from duplicating all lines from Boost Union's config.php into Boost Union Child's config.php.
// This statement uses require (and not require_once) by purpose to make sure that all Boost Union settings are added
// to the $THEME object even if the Boost Union config was already included in some other place.
require($CFG->dirroot . '/theme/boost_union/config.php');

// Then, we require Boost Union Child's locallib.php to make sure that it's always loaded.
require_once($CFG->dirroot . '/theme/boost_union_child/locallib.php');

// Next, we overwrite only the settings which differ between Boost Union and Boost Union Child.
$THEME->name = 'boost_union_child';
$THEME->scss = function($theme) {
    return theme_boost_union_child_get_main_scss_content($theme);
};
$THEME->parents = ['boost_union', 'boost'];
$THEME->extrascsscallback = 'theme_boost_union_child_get_extra_scss';
$THEME->prescsscallback = 'theme_boost_union_child_get_pre_scss';

// We need to duplicate the rendererfactory even if it is set to the same value as in Boost Union.
// The theme_config::get_renderer() method needs it to be directly in the theme_config object.
$THEME->rendererfactory = 'theme_overridden_renderer_factory';
$THEME->layouts = [
    // Most backwards compatible layout without the blocks.
    'base' => [
        'file' => 'drawers.php',
        'regions' => [],
    ],
    // Standard layout with blocks.
    'standard' => [
        'file' => 'drawers.php',
        'regions' => array_merge(theme_boost_union_get_block_regions('standard'), ['header', 'content-upper']),
        'defaultregion' => 'side-pre',
    ],
    // Main course page.
    'course' => [
        'file' => 'drawers.php',
        'regions' => theme_boost_union_get_block_regions('course'),
        'defaultregion' => 'side-pre',
        'options' => ['langmenu' => true],
    ],
    'coursecategory' => [
        'file' => 'drawers.php',
        'regions' => theme_boost_union_get_block_regions('coursecategory'),
        'defaultregion' => 'side-pre',
    ],
    // Part of course, typical for modules - default page layout if $cm specified in require_login().
    'incourse' => [
        'file' => 'drawers.php',
        'regions' => theme_boost_union_get_block_regions('incourse'),
        'defaultregion' => 'side-pre',
    ],
    // The site home page.
    'frontpage' => [
        'file' => 'drawers.php',
        'regions' => theme_boost_union_get_block_regions('frontpage'),
        'defaultregion' => 'side-pre',
        'options' => ['nonavbar' => true],
    ],
    // Server administration scripts.
    'admin' => [
        'file' => 'drawers.php',
        'regions' => theme_boost_union_get_block_regions('admin'),
        'defaultregion' => 'side-pre',
    ],
    // My courses page.
    'mycourses' => [
        'file' => 'drawers.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
        'options' => ['nonavbar' => true],
    ],
    // My dashboard page.
    'mydashboard' => [
        'file' => 'drawers.php',
        'regions' => theme_boost_union_get_block_regions('mydashboard'),
        'defaultregion' => 'side-pre',
        'options' => ['nonavbar' => true, 'langmenu' => true],
    ],
    // My public page.
    'mypublic' => [
        'file' => 'drawers.php',
        'regions' => theme_boost_union_get_block_regions('mypublic'),
        'defaultregion' => 'side-pre',
    ],
    'login' => [
        'file' => 'login.php',
        'regions' => [],
        'options' => ['langmenu' => true],
    ],

    // Pages that appear in pop-up windows - no navigation, no blocks, no header and bare activity header.
    'popup' => [
        'file' => 'columns1.php',
        'regions' => [],
        'options' => [
            'nofooter' => true,
            'nonavbar' => true,
            'activityheader' => [
                'notitle' => true,
                'nocompletion' => true,
                'nodescription' => true
            ]
        ]
    ],
    // No blocks and minimal footer - used for legacy frame layouts only!
    'frametop' => [
        'file' => 'columns1.php',
        'regions' => [],
        'options' => [
            'nofooter' => true,
            'nocoursefooter' => true,
            'activityheader' => [
                'nocompletion' => true
            ]
        ],
    ],
    // Embeded pages, like iframe/object embeded in moodleform - it needs as much space as possible.
    'embedded' => [
        'file' => 'embedded.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
    ],
    // Used during upgrade and install, and for the 'This site is undergoing maintenance' message.
    // This must not have any blocks, links, or API calls that would lead to database or cache interaction.
    // Please be extremely careful if you are modifying this layout.
    'maintenance' => [
        'file' => 'maintenance.php',
        'regions' => [],
    ],
    // Should display the content and basic headers only.
    'print' => [
        'file' => 'columns1.php',
        'regions' => [],
        'options' => ['nofooter' => true, 'nonavbar' => false, 'noactivityheader' => true],
    ],
    // The pagelayout used when a redirection is occuring.
    'redirect' => [
        'file' => 'embedded.php',
        'regions' => [],
    ],
    // The pagelayout used for reports.
    'report' => [
        'file' => 'drawers.php',
        'regions' => theme_boost_union_get_block_regions('report'),
        'defaultregion' => 'side-pre',
    ],
    // The pagelayout used for safebrowser and securewindow.
    'secure' => [
        'file' => 'secure.php',
        'regions' => ['side-pre'],
        'defaultregion' => 'side-pre',
        'options' => [
            'activityheader' => [
                'notitle' => false,
            ],
        ],
    ]
];

// Lastly, we replicate some settings from Boost Union at runtime into Boost Union Child's settings.
// This becomes necessary if Moodle core code accesses a theme setting at $this->page->theme->settings->*.
// In this case, the setting must exist in the currently active theme, otherwise it won't be found.
// While Boost Union duplicates all settings from Boost Core and does not suffer from this issue,
// it would be quite ugly to duplicate all of these settings again to Boost Union Child.
// Currently, this affects these Boost Core settings:
// unaddableblocks - called from blocklib.php.
$unaddableblocks = get_config('theme_boost_union', 'unaddableblocks');
if (!empty($unaddableblocks)) {
    $THEME->settings->unaddableblocks = $unaddableblocks;
}
unset($unaddableblocks);
// SCSS - called in theme_boost_get_extra_scss.
$scss = get_config('theme_boost_union', 'scss');
if (!empty($scss)) {
    $THEME->settings->scss = $scss;
}
unset($scss);
// SCSSpre - called in theme_boost_get_pre_scss.
$scsspre = get_config('theme_boost_union', 'scsspre');
if (!empty($scsspre)) {
    $THEME->settings->scsspre = $scsspre;
}
unset($scsspre);

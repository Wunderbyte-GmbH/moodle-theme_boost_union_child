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
 * Theme NWV - Library
 *
 * @package    theme_nwverkehrserziehung
 * @copyright  2023 Daniel Poggenpohl <daniel.poggenpohl@fernuni-hagen.de> and Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Constants which are use throughout this theme.
define('THEME_NWVERKEHRSERZIEHUNG_SETTING_INHERITANCE_INHERIT', 0);
define('THEME_NWVERKEHRSERZIEHUNG_SETTING_INHERITANCE_DUPLICATE', 1);

/**
 * Returns the main SCSS content.
 *
 * @param \core\output\theme_config $theme The theme config object.
 * @return string
 */
function theme_nwverkehrserziehung_get_main_scss_content($theme) {
    global $CFG;

    // Require the necessary libraries.
    require_once($CFG->dirroot . '/theme/boost_union/lib.php');

    // As a start, get the compiled main SCSS from Boost Union.
    // This way, NWV will ship the same SCSS code as Boost Union itself.
    $scss = theme_boost_union_get_main_scss_content(\core\output\theme_config::load('boost_union'));

    // And add NWV's main SCSS file to the stack if it exists.
    if (file_exists($CFG->dirroot . '/theme/nwverkehrserziehung/scss/post.scss')) {
        $scss .= file_get_contents($CFG->dirroot . '/theme/nwverkehrserziehung/scss/post.scss');
    }

    return $scss;
}

/**
 * Get SCSS to prepend.
 *
 * @param \core\output\theme_config $theme The theme config object.
 * @return string
 */
function theme_nwverkehrserziehung_get_pre_scss($theme) {
    global $CFG;

    // Require the necessary libraries.
    require_once($CFG->dirroot . '/theme/boost_union/lib.php');

    // As a start, initialize the Pre SCSS code with an empty string.
    $scss = '';

    // Then, if configured, get the compiled pre SCSS code from Boost Union.
    // This should not be necessary as Moodle core calls the *_get_pre_scss() functions from all parent themes as well.
    // However, as soon as Boost Union would use $theme->settings in this function, $theme would be this theme here and
    // not Boost Union. The Boost Union developers are aware of this topic, but faults can always happen.
    // If such a fault happens, the NWV administrator can switch the inheritance to 'Duplicate'.
    // This way, we will add the pre SCSS code with the explicit use of the Boost Union configuration to the stack.
    $inheritanceconfig = get_config('theme_nwverkehrserziehung', 'prescssinheritance');
    if ($inheritanceconfig == THEME_NWVERKEHRSERZIEHUNG_SETTING_INHERITANCE_DUPLICATE) {
        $scss .= theme_boost_union_get_pre_scss(\core\output\theme_config::load('boost_union'));
    }

    // And add NWV's pre SCSS file to the stack if it exists.
    if (file_exists($CFG->dirroot . '/theme/nwverkehrserziehung/scss/pre.scss')) {
        $scss .= file_get_contents($CFG->dirroot . '/theme/nwverkehrserziehung/scss/pre.scss');
    }

    /**********************************************************
     * EXTENSION POINT:
     * Compose and add additional pre-SCSS code here.
     * It will be added on top of Boost Union's pre-SCSS code.
     *********************************************************/

    return $scss;
}

/**
 * Inject additional SCSS.
 *
 * @param \core\output\theme_config $theme The theme config object.
 * @return string
 */
function theme_nwverkehrserziehung_get_extra_scss($theme) {
    global $CFG;

    // Require the necessary libraries.
    require_once($CFG->dirroot . '/theme/boost_union/lib.php');

    // As a start, initialize the Extra SCSS code with an empty string.
    $scss = '';

    // Then, if configured, get the compiled extra SCSS code from Boost Union.
    // This should not be necessary as Moodle core calls the *_get_extra_scss() functions from all parent themes as well.
    // However, as soon as Boost Union would use $theme->settings in this function, $theme would be this theme here and
    // not Boost Union. The Boost Union developers are aware of this topic, but faults can always happen.
    // If such a fault happens, the NWV administrator can switch the inheritance to 'Duplicate'.
    // This way, we will add the extra SCSS code with the explicit use of the Boost Union configuration to the stack.
    $inheritanceconfig = get_config('theme_nwverkehrserziehung', 'extrascssinheritance');
    if ($inheritanceconfig == THEME_NWVERKEHRSERZIEHUNG_SETTING_INHERITANCE_DUPLICATE) {
        $scss .= theme_boost_union_get_extra_scss(\core\output\theme_config::load('boost_union'));
    }

    // Add NWV-specific navbar positioning CSS
    $scss .= '
    /* NWV Theme - Navbar positioning adjustments */
    #nwv-navbar-top {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1030;
        height: 38px;
    }
    
    .navbar.fixed-top.nwv-themed-navbar {
        top: 38px;
    }
    
    body {
        padding-top: 102px;
    }
    
    @media (max-width: 768px) {
        body {
            padding-top: 100px;
        }
    }
    ';

    /**********************************************************
     * EXTENSION POINT:
     * Compose and add additional SCSS code here.
     * It will be added on top of Boost Union\'s SCSS code.
     *********************************************************/

    return $scss;
}

/**
 * Callback function for theme_boost_union to allow NWV to add cards to the Boost Union settings overview page.
 * This function is expected to return an array of arrays containing values with the keys 'label', 'desc', 'btn' and 'url'.
 *
 * @return array
 */
function theme_nwverkehrserziehung_extend_busettingsoverview() {

    $cards[] = [
        'label' => get_string('pluginname', 'theme_nwverkehrserziehung'),
        'desc' => get_string('settingsoverview_nwv_desc', 'theme_nwverkehrserziehung'),
        'btn' => 'primary',
        'url' => new \core\url('/admin/settings.php', ['section' => 'theme_nwverkehrserziehung']),
    ];

    return $cards;
}

/**
 * Callback function which allows themes to alter the CSS URLs.
 * We use this function to change the CSS URL to the flavour CSS URL if a flavour applies to the current page.
 *
 * @copyright 2024 Alexander Bias <bias@alexanderbias.de>
 *
 * @param mixed $urls The CSS URLs (passed as reference).
 */
function theme_nwverkehrserziehung_alter_css_urls(&$urls) {
    global $CFG;

    // Require Boost Union library.
    require_once($CFG->dirroot . '/theme/boost_union/lib.php');

    // Call Boost Union's theme_boost_union_alter_css_urls() function which implements the logic to change the CSS URL for flavours.
    theme_boost_union_alter_css_urls($urls);
}

/**
 * Get custom navigation menu from theme settings
 *
 * @return array Navigation menu array for template
 */
function theme_nwverkehrserziehung_get_custom_navigation() {
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

/**
 * Hook callbacks for data module events.
 *
 * Registers callbacks for data record events to handle cache invalidation
 * for Praxisbörse data.
 *
 * @return void
 */
function theme_nwverkehrserziehung_after_config() {
    global $CFG;

    // Include hook callbacks.
    require_once($CFG->dirroot . '/theme/nwverkehrserziehung/lib/hooks.php');
}

// Initialize hooks if we're in Moodle context.
if (defined('MOODLE_INTERNAL')) {
    // Hook into mod_data events for cache invalidation.
    // This will be handled through Moodle's event system.
}

/**
 * Serve plugin file for logo.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_nwverkehrserziehung_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    $tileimageareas = ['tile1image', 'tile2image', 'tile3image', 'tile4image'];

    if (($filearea === 'logo' || in_array($filearea, $tileimageareas)) && $context->contextlevel == CONTEXT_SYSTEM) {
        $itemid = array_shift($args);
        $filename = array_pop($args);
        $filepath = $args ? '/' . implode('/', $args) . '/' : '/';

        $fs = get_file_storage();
        $file = $fs->get_file($context->id, 'theme_nwverkehrserziehung', $filearea, $itemid, $filepath, $filename);

        if ($file) {
            send_stored_file($file, 0, 0, $forcedownload, $options);
            return true;
        }
    }

    return false;
}

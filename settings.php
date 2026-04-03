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
 * Theme NWV - Settings file
 *
 * @package    theme_nwverkehrserziehung
 * @copyright  2023 Daniel Poggenpohl <daniel.poggenpohl@fernuni-hagen.de> and Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use theme_boost_union\admin_settingspage_tabs_with_tertiary;

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig || has_capability('theme/boost_union:configure', context_system::instance())) {
    // How this file works:
    // Boost Union's settings are divided into multiple settings pages which resides in its own settings category.
    // You will understand it as soon as you look at /theme/boost_union/settings.php.
    // This settings file here is built in a way that it adds another settings page to this existing settings
    // category. You can add all child-theme-specific settings to this settings page here.

    // However, there is still the $settings variable which is expected by Moodle core to be filled with the theme
    // settings and which is automatically linked from the theme selector page.
    // To avoid that there appears a broken "NWV" settings page, we redirect the user to a settings
    // overview page if he opens this page.
    $mainsettingspageurl = new \core\url('/admin/settings.php', ['section' => 'themesettingnwverkehrserziehung']);
    if ($ADMIN->fulltree && $PAGE->has_set_url() && $PAGE->url->compare($mainsettingspageurl)) {
        redirect(new \core\url('/admin/settings.php', ['section' => 'theme_nwverkehrserziehung']));
    }

    // Create empty settings page structure to make the site administration work on non-admin pages.
    if (!$ADMIN->fulltree) {
        // Create NWV settings page
        // (and allow users with the theme/boost_union:configure capability to access it).
        $tab = new admin_settingpage(
            'theme_nwverkehrserziehung',
            get_string('configtitle', 'theme_nwverkehrserziehung', null, true),
            'theme/boost_union:configure'
        );
        $ADMIN->add('theme_boost_union', $tab);

        // Create full settings page structure.
        // phpcs:disable moodle.ControlStructures.ControlSignature.Found
    } else if ($ADMIN->fulltree) {
        // Require the necessary libraries.
        require_once($CFG->dirroot . '/theme/boost_union/lib.php');
        require_once($CFG->dirroot . '/theme/boost_union/locallib.php');
        require_once($CFG->dirroot . '/theme/nwverkehrserziehung/lib.php');
        require_once($CFG->dirroot . '/theme/nwverkehrserziehung/locallib.php');

        // Prepare options array for select settings.
        // Due to MDL-58376, we will use binary select settings instead of checkbox settings throughout this theme.
        $yesnooption = [THEME_BOOST_UNION_SETTING_SELECT_YES => get_string('yes'),
                THEME_BOOST_UNION_SETTING_SELECT_NO => get_string('no'), ];


        // Create NWV settings page with tabs and tertiary navigation
        // (and allow users with the theme/boost_union:configure capability to access it).
        $page = new admin_settingspage_tabs_with_tertiary(
            'theme_nwverkehrserziehung',
            get_string('configtitle', 'theme_nwverkehrserziehung', null, true),
            'theme/boost_union:configure'
        );


        // Create general settings tab.
        $tab = new admin_settingpage(
            'theme_nwverkehrserziehung_general',
            get_string('generalsettings', 'theme_boost', null, true)
        );

        // Create Logo heading.
        $name = 'theme_nwverkehrserziehung/logoheading';
        $title = get_string('logoheading', 'theme_nwverkehrserziehung', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Logo upload.
        $name = 'theme_nwverkehrserziehung/logo';
        $title = get_string('logo', 'theme_nwverkehrserziehung', null, true);
        $description = get_string('logo_desc', 'theme_nwverkehrserziehung', null, true);
        $setting = new admin_setting_configstoredfile(
            $name,
            $title,
            $description,
            'logo',
            0,
            ['subdirs' => 0, 'maxbytes' => 0, 'accepted_types' => ['image']]
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Create inheritance heading.
        $name = 'theme_nwverkehrserziehung/inheritanceheading';
        $title = get_string('inheritanceheading', 'theme_nwverkehrserziehung', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Prepare inheritance options.
        $inheritanceoptions = [
                THEME_NWVERKEHRSERZIEHUNG_SETTING_INHERITANCE_INHERIT =>
                        get_string('inheritanceinherit', 'theme_nwverkehrserziehung'),
                THEME_NWVERKEHRSERZIEHUNG_SETTING_INHERITANCE_DUPLICATE =>
                        get_string('inheritanceduplicate', 'theme_nwverkehrserziehung'),
        ];

        // Setting: Pre SCSS inheritance setting.
        $name = 'theme_nwverkehrserziehung/prescssinheritance';
        $title = get_string('prescssinheritancesetting', 'theme_nwverkehrserziehung', null, true);
        $description = get_string('prescssinheritancesetting_desc', 'theme_nwverkehrserziehung', null, true) . '<br />' .
                get_string('inheritanceoptionsexplanation', 'theme_nwverkehrserziehung', null, true);
        $setting = new admin_setting_configselect(
            $name,
            $title,
            $description,
            THEME_NWVERKEHRSERZIEHUNG_SETTING_INHERITANCE_INHERIT,
            $inheritanceoptions
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Extra SCSS inheritance setting.
        $name = 'theme_nwverkehrserziehung/extrascssinheritance';
        $title = get_string('extrascssinheritancesetting', 'theme_nwverkehrserziehung', null, true);
        $description = get_string('extrascssinheritancesetting_desc', 'theme_nwverkehrserziehung', null, true) . '<br />' .
                get_string('inheritanceoptionsexplanation', 'theme_nwverkehrserziehung', null, true);
        $setting = new admin_setting_configselect(
            $name,
            $title,
            $description,
            THEME_NWVERKEHRSERZIEHUNG_SETTING_INHERITANCE_INHERIT,
            $inheritanceoptions
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Add general tab to settings page.
        $page->add($tab);

        // Create navigation tab.
        $tab = new admin_settingpage(
            'theme_nwverkehrserziehung_navigation',
            get_string('navigationheading', 'theme_nwverkehrserziehung', null, true)
        );

        // Create navigation heading.
        $name = 'theme_nwverkehrserziehung/navigationheading';
        $title = get_string('navigationheading', 'theme_nwverkehrserziehung', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Custom Navigation JSON.
        // LEGACY: This JSON textarea is kept for backwards compatibility during migration.
        // Navigation is now auto-generated from the theme_nwv_pages table.
        // This setting will only be used as a fallback if no pages exist in the DB yet.
        // It can be removed once all content is migrated to the pages table.
        $name = 'theme_nwverkehrserziehung/customnavigation';
        $title = get_string('customnavigation', 'theme_nwverkehrserziehung', null, true);
        $description = get_string('customnavigation_desc', 'theme_nwverkehrserziehung', null, true);
        $setting = new admin_setting_configtextarea(
            $name,
            $title,
            $description,
            ''
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Add navigation tab to settings page.
        $page->add($tab);

        // Create Praxisbörse tab.
        $tab = new admin_settingpage(
            'theme_nwverkehrserziehung_praxisboerse',
            get_string('praxisboerse', 'theme_nwverkehrserziehung', null, true)
        );

        // Setting: Praxisbörse data activity ID.
        $name = 'theme_nwverkehrserziehung/praxisboerse_dataid';
        $title = get_string('praxisboerse_dataid', 'theme_nwverkehrserziehung', null, true);
        $description = get_string('praxisboerse_dataid_desc', 'theme_nwverkehrserziehung', null, true);
        $setting = new admin_setting_configtext(
            $name,
            $title,
            $description,
            '',
            PARAM_INT
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Praxisbörse description.
        $name = 'theme_nwverkehrserziehung/praxisboerse_description';
        $title = get_string('praxisboersedescsetting', 'theme_nwverkehrserziehung', null, true);
        $description = '';
        $setting = new admin_setting_confightmleditor(
            $name,
            $title,
            $description,
            get_string('praxisboersedescdefault', 'theme_nwverkehrserziehung', null, true)
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Add tab to settings page.
        $page->add($tab);

        /**********************************************************
         * EXTENSION POINT:
         * Add your NWV settings here.
         *********************************************************/

        // Add settings page to the admin settings category.
        $ADMIN->add('theme_boost_union', $page);
    }
}

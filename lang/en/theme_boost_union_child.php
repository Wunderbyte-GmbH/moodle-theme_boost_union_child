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
 * Theme Boost Union Child - Language pack
 *
 * @package    theme_boost_union_child
 * @copyright  2023 Daniel Poggenpohl <daniel.poggenpohl@fernuni-hagen.de> and Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Let codechecker ignore some sniffs for this file as it is perfectly well ordered, just not alphabetically.
// phpcs:disable moodle.Files.LangFilesOrdering.UnexpectedComment
// phpcs:disable moodle.Files.LangFilesOrdering.IncorrectOrder

// General.
$string['pluginname'] = 'Boost Union Child';
$string['choosereadme'] = 'This plugin is just a boilerplate template one can use to develop Boost Union child themes.';
$string['configtitle'] = 'Boost Union Child';
$string['settingsoverview_buc_desc'] = 'With Boost Union Child, you can customize Boost Union to your own local needs.';

// Settings: General settings tab.
// ... Section: Inheritance.
$string['inheritanceheading'] = 'Inheritance';
$string['inheritanceinherit'] = 'Inherit';
$string['inheritanceduplicate'] = 'Duplicate';
$string['inheritanceoptionsexplanation'] = 'Most of the time, inheriting will be perfectly fine. However, it may happen that imperfect code is integrated into Boost Union which prevents simple SCSS inheritance for particular Boost Union features. If you encounter any issues with Boost Union features which seem not to work in Boost Union Child as well, try to switch this setting to \'Dupliate\' and, if this solves the problem, report an issue on Github (see the README.md file for details how to report an issue).';
// ... ... Setting: Pre SCSS inheritance setting.
$string['prescssinheritancesetting'] = 'Pre SCSS inheritance';
$string['prescssinheritancesetting_desc'] = 'With this setting, you control if the pre SCSS code from Boost Union should be inherited or duplicated.';
// ... ... Setting: Extra SCSS inheritance setting.
$string['extrascssinheritancesetting'] = 'Extra SCSS inheritance';
$string['extrascssinheritancesetting_desc'] = 'With this setting, you control if the extra SCSS code from Boost Union should be inherited or duplicated.';

/**************************************************************
 * EXTENSION POINT:
 * Add your language strings for your settings here.
 *************************************************************/

// Settings: Quick Login tab.
$string['quicklogintab'] = 'Quick Login';
$string['quickloginheading'] = 'Quick Login';
$string['quickloginheading_desc'] = 'Configure the Quick Login role selector shown on the login page. This feature is intended for development and demo environments only. Never enable it on a production site.';
// ... ... Setting: Enable quick login.
$string['quickloginenabledsetting'] = 'Enable Quick Login';
$string['quickloginenabledsetting_desc'] = 'If enabled, three clickable circles (Admin, Manager, User) are displayed on the login page, allowing instant login with the configured accounts.';
// ... ... Admin account settings.
$string['quickloginadminheading'] = 'Admin account';
// ... ... Manager account settings.
$string['quickloginmanagerheading'] = 'Manager account';
// ... ... User account settings.
$string['quickloginuserheading'] = 'User account';
// ... ... Shared field labels.
$string['quickloginusernamesetting'] = 'Username';
$string['quickloginusernamesetting_desc'] = 'The Moodle username of the account that will be used for this role.';
$string['quickloginpasswordsetting'] = 'Password';
$string['quickloginpasswordsetting_desc'] = 'The password for the above account.';

// Login page quick login UI strings.
$string['quickloginhint'] = 'Quick login — select a role';
$string['quickloginadmin'] = 'Admin';
$string['quickloginmanager'] = 'Manager';
$string['quickloginuser'] = 'User';

// Quick login error / status strings.
$string['quicklogindisabled'] = 'Quick Login is not enabled on this site.';
$string['quicklogincredentialsmissing'] = 'Quick Login credentials are not configured for this role. Please configure them in the theme settings.';
$string['quickloginfailed'] = 'Quick Login failed. Please check the configured credentials in the theme settings.';

// Frontpage settings.
$string['frontpagetab'] = 'Frontpage';
$string['frontpageheading'] = 'Frontpage settings';
$string['frontpageheading_desc'] = 'Configure what is shown on the site front page.';
$string['frontpagebookingcmidsetting'] = 'Booking activity (cmid)';
$string['frontpagebookingcmidsetting_desc'] = 'Enter the course-module ID (cmid) of the booking activity whose AI instructions panel should be embedded on the front page. Leave empty to hide the panel.';

// Privacy API.
$string['privacy:metadata'] = 'The Boost Union Child theme does not store any personal data about any user.';

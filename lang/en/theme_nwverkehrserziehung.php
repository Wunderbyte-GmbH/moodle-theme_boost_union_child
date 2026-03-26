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
 * Theme NWV - Language pack
 *
 * @package    theme_nwverkehrserziehung
 * @copyright  2023 Daniel Poggenpohl <daniel.poggenpohl@fernuni-hagen.de> and Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['frontpagesettings'] = 'Frontpage Tiles Settings';
$string['choosereadme'] = 'This theme is a Boost Union child theme for the Austrian traffic education network.';
$string['configtitle'] = 'Theme NWV';
$string['pluginname'] = 'Theme NWV';
$string['settingsoverview_nwv_desc'] = 'With Theme NWV, you can customize Boost Union to your local Austrian traffic education network needs.';
$string['logoheading'] = 'Logo';
$string['logo'] = 'Logo';
$string['logo_desc'] = 'Upload a logo image to display in the theme header. The logo will be displayed next to the site name.';
$string['inheritanceheading'] = 'Inheritance';
$string['inheritanceinherit'] = 'Inherit';
$string['inheritanceduplicate'] = 'Duplicate';
$string['inheritanceoptionsexplanation'] = 'Most of the time, inheriting will be perfectly fine. However, it may happen that imperfect code is integrated into Boost Union which prevents simple SCSS inheritance for particular Boost Union features. If you encounter any issues with Boost Union features which seem not to work in Theme NWV as well, try to switch this setting to \'Duplicate\' and, if this solves the problem, report an issue on Github (see the README.md file for details how to report an issue).';
$string['prescssinheritancesetting'] = 'Pre SCSS inheritance';
$string['prescssinheritancesetting_desc'] = 'With this setting, you control if the pre SCSS code from Boost Union should be inherited or duplicated.';
$string['extrascssinheritancesetting'] = 'Extra SCSS inheritance';
$string['extrascssinheritancesetting_desc'] = 'With this setting, you control if the extra SCSS code from Boost Union should be inherited or duplicated.';
$string['navigationheading'] = 'Navigation';
$string['customnavigation'] = 'Custom Navigation Menu';
$string['customnavigation_desc'] = 'Configure the main navigation menu by entering valid JSON. Each menu item must have "id", "text", "url", and optional "children" array for dropdown items. Example with dropdowns: [{"id": 1, "text": "In der Schule", "url": "#", "children": [{"id": 11, "text": "1. und 2. Schulstufe", "url": "/class-1-2"}, {"id": 12, "text": "3. und 4. Schulstufe", "url": "/class-3-4"}]}, {"id": 2, "text": "Verkehrssicherheit", "url": "/security", "children": []}]';
$string['praxisboerse'] = 'Praxisbörse';
$string['praxisboerse_dataid'] = 'Praxisbörse Data Activity ID';
$string['praxisboerse_dataid_desc'] = 'Set the ID of the Data Activity that contains Praxisbörse entries. You can find this ID in the URL when viewing the data activity (e.g., id=42).';
$string['praxisboersetitle'] = 'Praxisbörse Description';
$string['praxisboersedescsetting'] = 'Praxisbörse Description Text';
$string['praxisboersedescdefault'] = 'In der Praxisbörse finden Sie eine umfangreiche Sammlung an Unterrichtsmaterialien, die speziell für die Verkehrs- und Mobilitätsbildung in verschiedenen Schulstufen entwickelt wurden. Unsere Materialien wurden von Expertinnen und Experten aus unterschiedlichen Bereichen zusammengestellt und bieten praxisnahe Unterstützung für die Verkehrs- und Mobilitätsbildung im Unterricht sowie für die Begleitung daheim. Sie erhalten anschauliche Arbeitsblätter, interaktive Übungen sowie abwechslungsreiche und lebendige Lehrvideos und Präsentationen.

Ob zur Vorbereitung einzelner Unterrichtseinheiten, zur Vertiefung spezieller Themen oder für Projekte und fächerübergreifende Aktivitäten – die Unterrichtsmaterialien auf unserer Seite sind vielfältig einsetzbar und orientieren sich an aktuellen pädagogischen und gesetzlichen Vorgaben. Unsere Angebote unterstützen Sie dabei, Kinder und Jugendliche nachhaltig für sichere und verantwortungsvolle Mobilität zu sensibilisieren und ihnen wichtige Kompetenzen für den Alltag im Straßenverkehr zu vermitteln.

Anhand der Volltextsuche und der vielfältigen Filteroptionen finden Sie schnell zum passenden Material!';
$string['backtomoodle'] = 'Back to Moodle';
$string['aboutus'] = 'About Us';
$string['footertext'] = 'The Austrian Network for Traffic Education and Mobility Education';
$string['quicklinks'] = 'Quick Links';
$string['home'] = 'Home';
$string['courses'] = 'Courses';
$string['myprofile'] = 'My Profile';
$string['contact'] = 'Contact';
$string['footercontact'] = 'For more information, contact our team.';
$string['copyrighttext'] = 'Netzwerk Verkehrserziehung (NWV). All rights reserved.';
$string['frontpage'] = 'Frontpage Tiles';
$string['slider'] = 'Slider';
$string['slidertitle'] = 'Slider Slide';
$string['sliderimage'] = 'Slide Image';
$string['slidertext'] = 'Slide Text';
$string['tile1titlesetting'] = 'Tile 1: Title';
$string['tile2titlesetting'] = 'Tile 2: Title';
$string['tile3titlesetting'] = 'Tile 3: Title';
$string['tile4titlesetting'] = 'Tile 4: Title';
$string['tile1imagesetting'] = 'Tile 1: Image';
$string['tile2imagesetting'] = 'Tile 2: Image';
$string['tile3imagesetting'] = 'Tile 3: Image';
$string['tile4imagesetting'] = 'Tile 4: Image';
$string['tile1textsetting'] = 'Tile 1: Text';
$string['tile2textsetting'] = 'Tile 2: Text';
$string['tile3textsetting'] = 'Tile 3: Text';
$string['tile4textsetting'] = 'Tile 4: Text';
$string['tile1textdefault'] = 'Tile 1 content';
$string['tile2textdefault'] = 'Tile 2 content';
$string['tile3textdefault'] = 'Tile 3 content';
$string['tile4textdefault'] = 'Tile 4 content';
$string['tile1fullpagecontentsetting'] = 'Tile 1: Full Page Content';
$string['tile2fullpagecontentsetting'] = 'Tile 2: Full Page Content';
$string['tile3fullpagecontentsetting'] = 'Tile 3: Full Page Content';
$string['tile4fullpagecontentsetting'] = 'Tile 4: Full Page Content';
$string['tile1linksetting'] = 'Tile 1: Link URL';
$string['tile2linksetting'] = 'Tile 2: Link URL';
$string['tile3linksetting'] = 'Tile 3: Link URL';
$string['tile4linksetting'] = 'Tile 4: Link URL';
$string['grundlagen'] = 'Grundlagen';
$string['netzwerk'] = 'Netzwerk';
$string['kontakt'] = 'Kontakt';
$string['subpageid'] = 'Subpage ID';
$string['subpage_navigation_help'] = 'Enter navigation items as JSON array. Example: [{"label": "Home", "url": "page.php?id=home", "active": false}]';

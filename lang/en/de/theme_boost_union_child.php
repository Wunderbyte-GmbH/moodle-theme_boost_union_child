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
 * @copyright  2026 Wunderbyte
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Let codechecker ignore some sniffs for this file as it is perfectly well ordered, just not alphabetically.
// phpcs:disable moodle.Files.LangFilesOrdering.UnexpectedComment
// phpcs:disable moodle.Files.LangFilesOrdering.IncorrectOrder

// General.
$string['pluginname'] = 'Boost Union Child';
$string['choosereadme'] = 'Dieses Plugin ist eine reine Vorlagenbasis, die zur Entwicklung von Boost-Union-Child-Themes verwendet werden kann.';
$string['configtitle'] = 'Boost Union Child';
$string['settingsoverview_buc_desc'] = 'Mit Boost Union Child können Sie Boost Union an Ihre lokalen Anforderungen anpassen.';

// Settings: General settings tab.
// ... Section: Inheritance.
$string['inheritanceheading'] = 'Vererbung';
$string['inheritanceinherit'] = 'Vererben';
$string['inheritanceduplicate'] = 'Duplizieren';
$string['inheritanceoptionsexplanation'] = 'In den meisten Fällen ist die Vererbung vollkommen ausreichend. Es kann jedoch vorkommen, dass fehlerhafter Code in Boost Union integriert ist, der eine einfache SCSS-Vererbung für bestimmte Boost-Union-Funktionen verhindert. Sollten Sie Probleme mit Boost-Union-Funktionen feststellen, die auch in Boost Union Child nicht korrekt funktionieren, versuchen Sie, diese Einstellung auf „Duplizieren“ zu setzen. Wenn dies das Problem behebt, melden Sie bitte ein Issue auf GitHub (siehe README.md für Hinweise zur Fehlerberichterstattung).';
// ... ... Setting: Pre SCSS inheritance setting.
$string['prescssinheritancesetting'] = 'Pre-SCSS-Vererbung';
$string['prescssinheritancesetting_desc'] = 'Mit dieser Einstellung legen Sie fest, ob der Pre-SCSS-Code von Boost Union vererbt oder dupliziert werden soll.';
// ... ... Setting: Extra SCSS inheritance setting.
$string['extrascssinheritancesetting'] = 'Extra-SCSS-Vererbung';
$string['extrascssinheritancesetting_desc'] = 'Mit dieser Einstellung legen Sie fest, ob der zusätzliche SCSS-Code von Boost Union vererbt oder dupliziert werden soll.';
// ... ... Setting: Display of elements in securedrawers template.
$string['displaysecondarynavigation'] = 'Sekundäre Navigation im SEB anzeigen';
$string['displaysecondarynavigation_desc'] = 'Aktivieren Sie diese Option, um das sekundäre Navigationsmenü anzuzeigen während ein Quiz im Safe Exam Browser absolviert wird.';

$string['displaytopnav'] = 'Obere Navigation im SEB anzeigen';
$string['displaytopnav_desc'] = 'Aktivieren Sie diese Option, um die obere Navigationsleiste anzuzeigen, die in der Regel Breadcrumb-Navigation enthält.';

$string['displayfullheader'] = 'Vollständigen Header im SEB anzeigen';
$string['displayfullheader_desc'] = 'Aktivieren Sie diese Option, um den vollständigen Headerbereich mit allen standardmäßigen Header-Elementen anzuzeigen.';

// Privacy API.
$string['privacy:metadata'] = 'Das Theme Boost Union Child speichert keine personenbezogenen Daten von Benutzerinnen oder Benutzern.';


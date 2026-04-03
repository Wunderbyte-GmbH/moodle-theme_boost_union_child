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
 * Theme NWV - Sprachpaket Deutsch
 *
 * @package    theme_nwverkehrserziehung
 * @copyright  2024
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['frontpagesettings'] = 'Frontpage Tiles Settings';

// Allgemein.
$string['choosereadme'] = 'Dieses Theme ist ein Boost Union-Child-Theme für das österreichische Netzwerk Verkehrserziehung.';
$string['configtitle'] = 'Theme NWV';
$string['pluginname'] = 'Theme NWV';
$string['settingsoverview_nwv_desc'] = 'Mit Theme NWV können Sie Boost Union an Ihre lokalen Anforderungen des österreichischen Netzwerks Verkehrserziehung anpassen.';

// Einstellungen: Allgemeine Einstellungen Tab.
// ... Abschnitt: Logo.
$string['logoheading'] = 'Logo';
$string['logo'] = 'Logo';
$string['logo_desc'] = 'Laden Sie ein Logobild hoch, das im Theme-Header angezeigt wird. Das Logo wird neben dem Sitenamen angezeigt.';
// ... Abschnitt: Vererbung.
$string['inheritanceheading'] = 'Vererbung';
$string['inheritanceinherit'] = 'Erben';
$string['inheritanceduplicate'] = 'Duplizieren';
$string['inheritanceoptionsexplanation'] = 'In den meisten Fällen wird die Vererbung perfekt funktionieren. Es kann jedoch vorkommen, dass fehlerhafter Code in Boost Union integriert wird, der eine einfache SCSS-Vererbung für bestimmte Boost Union-Funktionen verhindert. Wenn Sie Probleme mit Boost Union-Funktionen haben, die auch in Theme NWV nicht funktionieren, versuchen Sie, diese Einstellung auf "Duplizieren" zu ändern und melden Sie das Problem auf Github (siehe README.md für Details).';
// ... ... Einstellung: Pre SCSS Vererbungseinstellung.
$string['prescssinheritancesetting'] = 'Pre SCSS Vererbung';
$string['prescssinheritancesetting_desc'] = 'Mit dieser Einstellung steuern Sie, ob der Pre SCSS-Code von Boost Union geerbt oder dupliziert werden soll.';
// ... ... Einstellung: Extra SCSS Vererbungseinstellung.
$string['extrascssinheritancesetting'] = 'Extra SCSS Vererbung';
$string['extrascssinheritancesetting_desc'] = 'Mit dieser Einstellung steuern Sie, ob der Extra SCSS-Code von Boost Union geerbt oder dupliziert werden soll.';

// Einstellungen: Navigationseinstellungen Tab.
// ... Abschnitt: Benutzerdefinierte Navigation.
$string['navigationheading'] = 'Navigation';
$string['customnavigation'] = 'Benutzerdefiniertes Navigationsmenü';
$string['customnavigation_desc'] = 'Konfigurieren Sie das Hauptnavigationsmenü durch Eingabe von gültigem JSON. Jedes Menüelement muss "id", "text", "url" und optional ein "children"-Array für Dropdown-Elemente haben. Beispiel mit Dropdowns: [{"id": 1, "text": "In der Schule", "url": "#", "children": [{"id": 11, "text": "1. und 2. Schulstufe", "url": "/class-1-2"}, {"id": 12, "text": "3. und 4. Schulstufe", "url": "/class-3-4"}]}, {"id": 2, "text": "Verkehrssicherheit", "url": "/security", "children": []}]';

// Praxisbörse Seite.
$string['praxisboerse'] = 'Praxisbörse';
$string['praxisboerse_dataid'] = 'Praxisbörse Datenaktivitäts-ID';
$string['praxisboerse_dataid_desc'] = 'Legen Sie die ID der Datenaktivität fest, die Praxisbörse-Einträge enthält. Sie können diese ID in der URL beim Anzeigen der Datenaktivität finden (z. B. id=42).';
$string['praxisboersetitle'] = 'Praxisbörse Beschreibung';
$string['praxisboersedescsetting'] = 'Praxisbörse Beschreibungstext';
$string['praxisboersedescdefault'] = 'In der Praxisbörse finden Sie eine umfangreiche Sammlung an Unterrichtsmaterialien, die speziell für die Verkehrs- und Mobilitätsbildung in verschiedenen Schulstufen entwickelt wurden. Unsere Materialien wurden von Expertinnen und Experten aus unterschiedlichen Bereichen zusammengestellt und bieten praxisnahe Unterstützung für die Verkehrs- und Mobilitätsbildung im Unterricht sowie für die Begleitung daheim. Sie erhalten anschauliche Arbeitsblätter, interaktive Übungen sowie abwechslungsreiche und lebendige Lehrvideos und Präsentationen.

Ob zur Vorbereitung einzelner Unterrichtseinheiten, zur Vertiefung spezieller Themen oder für Projekte und fächerübergreifende Aktivitäten – die Unterrichtsmaterialien auf unserer Seite sind vielfältig einsetzbar und orientieren sich an aktuellen pädagogischen und gesetzlichen Vorgaben. Unsere Angebote unterstützen Sie dabei, Kinder und Jugendliche nachhaltig für sichere und verantwortungsvolle Mobilität zu sensibilisieren und ihnen wichtige Kompetenzen für den Alltag im Straßenverkehr zu vermitteln.

Anhand der Volltextsuche und der vielfältigen Filteroptionen finden Sie schnell zum passenden Material!';
$string['backtomoodle'] = 'Zurück zu Moodle';
$string['aboutus'] = 'Über uns';
$string['footertext'] = 'Das österreichische Netzwerk für Verkehrserziehung und Mobilitätsbildung';
$string['quicklinks'] = 'Schnelllinks';
$string['home'] = 'Startseite';
$string['courses'] = 'Kurse';
$string['myprofile'] = 'Mein Profil';
$string['contact'] = 'Kontakt';
$string['footercontact'] = 'Für weitere Informationen kontaktieren Sie uns.';
$string['copyrighttext'] = 'Netzwerk Verkehrserziehung (NWV). Alle Rechte vorbehalten.';
$string['frontpage'] = 'Startseiten-Kacheln';
$string['tile1titlesetting'] = 'Kachel 1: Titel';
$string['tile2titlesetting'] = 'Kachel 2: Titel';
$string['tile3titlesetting'] = 'Kachel 3: Titel';
$string['tile4titlesetting'] = 'Kachel 4: Titel';
$string['tile1imagesetting'] = 'Kachel 1: Bild';
$string['tile2imagesetting'] = 'Kachel 2: Bild';
$string['tile3imagesetting'] = 'Kachel 3: Bild';
$string['tile4imagesetting'] = 'Kachel 4: Bild';
$string['tile1textsetting'] = 'Kachel 1: Text';
$string['tile2textsetting'] = 'Kachel 2: Text';
$string['tile3textsetting'] = 'Kachel 3: Text';
$string['tile4textsetting'] = 'Kachel 4: Text';
$string['tile1textdefault'] = 'Inhalte für Kachel 1';
$string['tile2textdefault'] = 'Inhalte für Kachel 2';
$string['tile3textdefault'] = 'Inhalte für Kachel 3';
$string['tile4textdefault'] = 'Inhalte für Kachel 4';
$string['tile1fullpagecontentsetting'] = 'Kachel 1: Ganzseitiger Inhalt';
$string['tile2fullpagecontentsetting'] = 'Kachel 2: Ganzseitiger Inhalt';
$string['tile3fullpagecontentsetting'] = 'Kachel 3: Ganzseitiger Inhalt';
$string['tile4fullpagecontentsetting'] = 'Kachel 4: Ganzseitiger Inhalt';
$string['tile1linksetting'] = 'Kachel 1: Link URL';
$string['tile2linksetting'] = 'Kachel 2: Link URL';
$string['tile3linksetting'] = 'Kachel 3: Link URL';
$string['tile4linksetting'] = 'Kachel 4: Link URL';

// Seiten.
$string['grundlagen'] = 'Grundlagen';
$string['netzwerk'] = 'Netzwerk';
$string['kontakt'] = 'Kontakt';

// CMS Seitenverwaltung.
$string['page_manage'] = 'Seiten verwalten';
$string['page_add'] = 'Neue Seite erstellen';
$string['page_edit'] = 'Seite bearbeiten';
$string['page_title'] = 'Titel';
$string['page_nav_title'] = 'Navigationstitel';
$string['page_nav_title_help'] = 'Kurzer Titel für Navigationsmenüs. Wenn leer, wird der vollständige Titel verwendet.';
$string['page_slug'] = 'URL-Slug';
$string['page_slug_help'] = 'URL-freundliche Kennung für diese Seite. Nur Kleinbuchstaben, Zahlen, Bindestriche und Schrägstriche erlaubt. Beispiel: grundlagen/zum-begriff';
$string['page_slug_exists'] = 'Dieser Slug wird bereits von einer anderen Seite verwendet.';
$string['page_slug_invalid'] = 'Der Slug darf nur Kleinbuchstaben, Zahlen, Bindestriche und Schrägstriche enthalten.';
$string['page_section'] = 'Bereich';
$string['page_section_footer'] = 'Fußzeile';
$string['page_parent'] = 'Übergeordnete Seite';
$string['page_parent_root'] = '— Keine (Stammebene) —';
$string['page_parent_self'] = 'Eine Seite kann nicht ihre eigene übergeordnete Seite sein.';
$string['page_content'] = 'Inhalt';
$string['page_sortorder'] = 'Sortierung';
$string['page_visible'] = 'Sichtbar';
$string['page_saved'] = 'Seite erfolgreich gespeichert.';
$string['page_deleted'] = 'Seite erfolgreich gelöscht.';
$string['page_delete_confirm'] = 'Möchten Sie die Seite "{$a}" wirklich löschen? Diese Aktion kann nicht rückgängig gemacht werden.';
$string['page_none'] = 'Es wurden noch keine Seiten erstellt. Klicken Sie auf "Neue Seite erstellen" um zu beginnen.';
$string['nwverkehrserziehung:managepages'] = 'NWV-Theme Inhaltsseiten verwalten';

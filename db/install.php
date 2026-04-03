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
 * Theme NWV - Post-install script to seed the page tree.
 *
 * Creates all CMS pages from the sitemap so the navigation
 * is ready immediately after installation.
 *
 * @package    theme_nwverkehrserziehung
 * @copyright  2026
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Post-install hook: seeds the theme_nwv_pages table with the full sitemap.
 *
 * @return bool
 */
function xmldb_theme_nwverkehrserziehung_install() {
    global $DB;

    $now = time();

    // Helper to build a page record.
    $make = function (string $slug, int $parentid, string $section, string $title,
                      ?string $navtitle, int $sortorder) use ($now) {
        return (object) [
            'slug'          => $slug,
            'parent_id'     => $parentid,
            'section'       => $section,
            'title'         => $title,
            'nav_title'     => $navtitle,
            'content'       => '',
            'contentformat' => FORMAT_HTML,
            'sortorder'     => $sortorder,
            'visible'       => 1,
            'timecreated'   => $now,
            'timemodified'  => $now,
            'usermodified'  => 0,
        ];
    };

    // -------------------------------------------------------------------------
    // Section: grundlagen
    // -------------------------------------------------------------------------
    $grundlagen = [];

    $id = $DB->insert_record('theme_nwv_pages', $make(
        'grundlagen/zum-begriff', 0, 'grundlagen',
        'Zum Begriff: Wo liegt der Unterschied zwischen Verkehrserziehung, Mobilitätserziehung und Verkehrs- und Mobilitätsbildung?',
        'Zum Begriff', 1
    ));

    $id = $DB->insert_record('theme_nwv_pages', $make(
        'grundlagen/ziele', 0, 'grundlagen',
        'Ziele: Worum geht es in der schulischen Verkehrs- und Mobilitätsbildung?',
        'Ziele', 2
    ));

    // Lehrpläne (parent with children).
    $lehrplaeneid = $DB->insert_record('theme_nwv_pages', $make(
        'grundlagen/lehrplaene', 0, 'grundlagen',
        'Verkehrs- und Mobilitätsbildung in den Lehrplänen',
        'Lehrpläne', 3
    ));

    // -- Lehrpläne children.
    $uebergreifendid = $DB->insert_record('theme_nwv_pages', $make(
        'grundlagen/lehrplaene/uebergreifendes-thema', $lehrplaeneid, 'grundlagen',
        'Übergreifendes Thema / Unterrichtsprinzip (schulstufen- und schulartenübergreifend)',
        'Übergreifendes Thema', 1
    ));

    // ---- Level-3 child.
    $DB->insert_record('theme_nwv_pages', $make(
        'grundlagen/lehrplaene/uebergreifendes-thema/ueberfachliche-kompetenzen', $uebergreifendid, 'grundlagen',
        'Zusammenhang mit überfachlichen Kompetenzen',
        'Überfachliche Kompetenzen', 1
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'grundlagen/lehrplaene/vorschulstufe', $lehrplaeneid, 'grundlagen',
        'Verbindliche Übung (Vorschulstufe)',
        'Vorschulstufe', 2
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'grundlagen/lehrplaene/primarstufe-uebergreifend', $lehrplaeneid, 'grundlagen',
        'Übergreifendes Thema (Primarstufe)',
        'Übergreifendes Thema Primarstufe', 3
    ));

    $primarstufeverbindlichid = $DB->insert_record('theme_nwv_pages', $make(
        'grundlagen/lehrplaene/primarstufe-verbindlich', $lehrplaeneid, 'grundlagen',
        'Verbindliche Übung (Primarstufe)',
        'Verbindliche Übung Primarstufe', 4
    ));

    // ---- Level-3 child.
    $DB->insert_record('theme_nwv_pages', $make(
        'grundlagen/lehrplaene/primarstufe-verbindlich/foerderschwerpunkt', $primarstufeverbindlichid, 'grundlagen',
        'Lehrplan Förderschwerpunkt Lernen (Primarstufe)',
        'Förderschwerpunkt Lernen', 1
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'grundlagen/lehrplaene/sekundarstufe-i-uebergreifend', $lehrplaeneid, 'grundlagen',
        'Übergreifendes Thema (Sekundarstufe I)',
        'Übergreifendes Thema Sek I', 5
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'grundlagen/lehrplaene/sekundarstufe-i-unverbindlich', $lehrplaeneid, 'grundlagen',
        'Unverbindliche Übung / Freigegenstand (Sekundarstufe I)',
        'Unverbindliche Übung Sek I', 6
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'grundlagen/lehrplaene/sekundarstufe-ii', $lehrplaeneid, 'grundlagen',
        'Unterrichtsprinzip (Sekundarstufe II)',
        'Unterrichtsprinzip Sek II', 7
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'grundlagen/lehrplaene/sekundarstufe-ii-unverbindlich', $lehrplaeneid, 'grundlagen',
        'Unverbindliche Übung (Sekundarstufe II)',
        'Unverbindliche Übung Sek II', 8
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'grundlagen/radfahrausweis', 0, 'grundlagen',
        'Radfahrausweis – Freiwillige Radfahrprüfung',
        'Radfahrausweis', 4
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'grundlagen/schulautonomie', 0, 'grundlagen',
        'Schulautonomie',
        'Schulautonomie', 5
    ));

    // Gesetzesgrundlagen (parent with children).
    $gesetzeid = $DB->insert_record('theme_nwv_pages', $make(
        'grundlagen/gesetze', 0, 'grundlagen',
        'Gesetzesgrundlagen',
        'Gesetzesgrundlagen', 6
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'grundlagen/gesetze/rundschreiben', $gesetzeid, 'grundlagen',
        'Rundschreiben', 'Rundschreiben', 1
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'grundlagen/gesetze/stvo', $gesetzeid, 'grundlagen',
        'StVO', 'StVO', 2
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'grundlagen/gesetze/kindersitze', $gesetzeid, 'grundlagen',
        'Kindersitze', 'Kindersitze', 3
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'grundlagen/gesetze/radhelmpflicht', $gesetzeid, 'grundlagen',
        'Radhelmpflicht', 'Radhelmpflicht', 4
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'grundlagen/gesetze/trendsportgeraete', $gesetzeid, 'grundlagen',
        'Trendsportgeräte', 'Trendsportgeräte', 5
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'grundlagen/gesetze/vertrauensgrundsatz', $gesetzeid, 'grundlagen',
        'Vertrauensgrundsatz', 'Vertrauensgrundsatz', 6
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'grundlagen/gesetze/e-scooter', $gesetzeid, 'grundlagen',
        'E-Scooter', 'E-Scooter', 7
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'grundlagen/gesetze/benuetzung-von-fahrraedern', $gesetzeid, 'grundlagen',
        'Benützung von Fahrrädern', 'Benützung von Fahrrädern', 8
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'grundlagen/paedagogische-hochschulen', 0, 'grundlagen',
        'Pädagogische Hochschulen',
        'Pädagogische Hochschulen', 7
    ));

    // -------------------------------------------------------------------------
    // Section: netzwerk
    // -------------------------------------------------------------------------

    $DB->insert_record('theme_nwv_pages', $make(
        'netzwerk/uebersicht', 0, 'netzwerk',
        'Netzwerkübersicht',
        'Netzwerkübersicht', 1
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'netzwerk/bildungsressort', 0, 'netzwerk',
        'Bildungsressort',
        'Bildungsressort', 2
    ));

    // Interministerielle Zusammenarbeit (parent with children).
    $interminid = $DB->insert_record('theme_nwv_pages', $make(
        'netzwerk/interministerielle-zusammenarbeit', 0, 'netzwerk',
        'Interministerielle Zusammenarbeit',
        'Interministerielle Zusammenarbeit', 3
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'netzwerk/interministerielle-zusammenarbeit/bmb', $interminid, 'netzwerk',
        'BMB', 'BMB', 1
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'netzwerk/interministerielle-zusammenarbeit/oekolog', $interminid, 'netzwerk',
        'ÖKOLOG, BMB', 'ÖKOLOG', 2
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'netzwerk/interministerielle-zusammenarbeit/bmi-kinderpolizei', $interminid, 'netzwerk',
        'BMI / Kinderpolizei', 'BMI / Kinderpolizei', 3
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'netzwerk/interministerielle-zusammenarbeit/klimaaktiv-mobil', $interminid, 'netzwerk',
        'BMIMI / klimaaktiv mobil', 'klimaaktiv mobil', 4
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'netzwerk/interministerielle-zusammenarbeit/forum-umweltbildung', $interminid, 'netzwerk',
        'Forum Umweltbildung, BMLUK', 'Forum Umweltbildung', 5
    ));

    // Partnerorganisationen (parent with children).
    $partnerid = $DB->insert_record('theme_nwv_pages', $make(
        'netzwerk/partnerorganisationen', 0, 'netzwerk',
        'Partnerorganisationen',
        'Partnerorganisationen', 4
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'netzwerk/partnerorganisationen/auva', $partnerid, 'netzwerk',
        'AUVA', 'AUVA', 1
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'netzwerk/partnerorganisationen/kfv', $partnerid, 'netzwerk',
        'Kuratorium für Verkehrssicherheit', 'KFV', 2
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'netzwerk/partnerorganisationen/oeamtc', $partnerid, 'netzwerk',
        'ÖAMTC', 'ÖAMTC', 3
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'netzwerk/partnerorganisationen/arboe', $partnerid, 'netzwerk',
        'ARBÖ', 'ARBÖ', 4
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'netzwerk/partnerorganisationen/oebb', $partnerid, 'netzwerk',
        'ÖBB', 'ÖBB', 5
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'netzwerk/partnerorganisationen/oejrk', $partnerid, 'netzwerk',
        'ÖJRK', 'ÖJRK', 6
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'netzwerk/partnerorganisationen/boku-young-mobility', $partnerid, 'netzwerk',
        'BOKU / YOUNG MOBILITY', 'BOKU / YOUNG MOBILITY', 7
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'netzwerk/partnerorganisationen/vcoe', $partnerid, 'netzwerk',
        'VCÖ', 'VCÖ', 8
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'netzwerk/partnerorganisationen/vereine-initiativen', $partnerid, 'netzwerk',
        'Vereine / Initiativen', 'Vereine / Initiativen', 9
    ));

    // -------------------------------------------------------------------------
    // Section: kontakt
    // -------------------------------------------------------------------------

    $DB->insert_record('theme_nwv_pages', $make(
        'kontakt/ansprechpartner', 0, 'kontakt',
        'Ansprechpartner', 'Ansprechpartner', 1
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'kontakt/bmb', 0, 'kontakt',
        'Kontakt BMB', 'Kontakt BMB', 2
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'kontakt/partnerorganisationen', 0, 'kontakt',
        'Ansprechpartner Partnerorganisationen', 'Partner-Kontakte', 3
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'kontakt/linkliste', 0, 'kontakt',
        'Linkliste', 'Linkliste', 4
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'kontakt/massgebliche-partner', 0, 'kontakt',
        'Maßgebliche Partner', 'Maßgebliche Partner', 5
    ));

    // -------------------------------------------------------------------------
    // Section: footer
    // -------------------------------------------------------------------------

    $DB->insert_record('theme_nwv_pages', $make(
        'impressum', 0, 'footer',
        'Impressum', 'Impressum', 1
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'datenschutz', 0, 'footer',
        'Datenschutz', 'Datenschutz', 2
    ));

    $DB->insert_record('theme_nwv_pages', $make(
        'medien', 0, 'footer',
        'Medien', 'Medien', 3
    ));

    return true;
}

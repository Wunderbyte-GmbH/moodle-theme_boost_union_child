<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Grundlagen - Verkehrsregeln subpage for NWV theme.
 *
 * @package   theme_nwverkehrserziehung
 * @copyright 2026
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');

// Force load NWV theme for this page.
$CFG->theme = 'nwverkehrserziehung';
$PAGE->set_context(context_system::instance());

$PAGE->set_url(new moodle_url('/theme/nwverkehrserziehung/grundlagen_regeln.php'));
$PAGE->set_pagelayout('frontpage');
$PAGE->set_title('Verkehrsregeln und Vorschriften');

echo $OUTPUT->header();

?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Verkehrsregeln und Vorschriften</h1>
            <p class="lead">Grundlegende Verkehrsregeln, die alle Verkehrsteilnehmer kennen sollten.</p>
            
            <div class="content mt-4">
                <h3>Ampelsignale</h3>
                <p>
                    Ampeln regeln den Verkehrsfluss an Kreuzungen. Rot bedeutet Stopp, Gelb zur Vorsicht und Grün ist Fahrt erlaubt.
                </p>

                <h3>Vorfahrtsregeln</h3>
                <ul>
                    <li>Rechts vor Links (falls nicht anders geregelt)</li>
                    <li>Fahrzeuge von rechts haben Vorfahrt</li>
                    <li>Beachte Verkehrszeichen und Ampeln</li>
                </ul>

                <h3>Geschwindigkeitsbegrenzungen</h3>
                <p>
                    Geschwindigkeitsbegrenzungen sind vorhanden zum Schutz aller Verkehrsteilnehmer und müssen
                    in allen Situationen beachtet werden.
                </p>

                <h3>Parkregeln</h3>
                <p>
                    Fahrzeuge dürfen nur an gekennzeichneten Orten geparkt werden. Beachte entsprechende Beschilderung.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Navigation</h5>
                    <ul class="list-unstyled">
                        <li><a href="grundlagen.php" class="btn btn-sm btn-outline-primary mb-2">← Zurück zu Grundlagen</a></li>
                        <li><a href="grundlagen_sicherheit.php" class="btn btn-sm btn-outline-secondary mb-2">Sicherheit</a></li>
                        <li><a href="grundlagen_verhalten.php" class="btn btn-sm btn-outline-secondary mb-2">Fahrtechniken</a></li>
                        <li><a href="grundlagen_umwelt.php" class="btn btn-sm btn-outline-secondary mb-2">Umweltschonung</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
echo $OUTPUT->footer();
?>

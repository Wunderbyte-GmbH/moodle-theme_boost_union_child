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
 * Grundlagen - Umweltschonung subpage for NWV theme.
 *
 * @package   theme_nwverkehrserziehung
 * @copyright 2026
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');

// Force load NWV theme for this page.
$CFG->theme = 'nwverkehrserziehung';
$PAGE->set_context(context_system::instance());

$PAGE->set_url(new moodle_url('/theme/nwverkehrserziehung/grundlagen_umwelt.php'));
$PAGE->set_pagelayout('frontpage');
$PAGE->set_title('Umweltschonung und Nachhaltigkeit');
$PAGE->set_heading('Umweltschonung und Nachhaltigkeit');

echo $OUTPUT->header();

?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Umweltschonung und Nachhaltigkeit</h1>
            <p class="lead">Umweltfreundliches Fahren trägt zur Nachhaltigkeit bei und schont Ressourcen.</p>
            
            <div class="content mt-4">
                <h3>Emissionsreduktion</h3>
                <p>
                    Durch ausgewogenes Fahrverhalten können CO₂-Emissionen und Schadstoffausstöße
                    erheblich reduziert werden.
                </p>

                <h3>Kraftstoffeffizienz</h3>
                <ul>
                    <li>Sanft beschleunigen und bremsen</li>
                    <li>Keine unnötigen Leerlauf-Phasen</li>
                    <li>Reifendruck kontrollieren</li>
                    <li>Regelmäßige Wartung durchführen</li>
                </ul>

                <h3>Alternative Verkehrsmittel</h3>
                <p>
                    Erwägen Sie Öffentliche Verkehrsmittel, Fahrrad oder Fußgänger als Alternative,
                    um den Verkehr und damit auch die Umweltbelastung zu reduzieren.
                </p>

                <h3>Fahrzeugs-Wartung</h3>
                <p>
                    Ein gut gewartetes Fahrzeug hat niedrigere Emissionen, verbraucht weniger Kraftstoff
                    und verursacht weniger Lärm.
                </p>

                <h3>Grüne Technologien</h3>
                <ul>
                    <li>Elektrofahrzeuge</li>
                    <li>Hybridfahrzeuge</li>
                    <li>Wasserstofffahrzeuge</li>
                </ul>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Navigation</h5>
                    <ul class="list-unstyled">
                        <li><a href="grundlagen.php" class="btn btn-sm btn-outline-primary mb-2">← Zurück zu Grundlagen</a></li>
                        <li><a href="grundlagen_sicherheit.php" class="btn btn-sm btn-outline-secondary mb-2">Sicherheit</a></li>
                        <li><a href="grundlagen_regeln.php" class="btn btn-sm btn-outline-secondary mb-2">Verkehrsregeln</a></li>
                        <li><a href="grundlagen_verhalten.php" class="btn btn-sm btn-outline-secondary mb-2">Fahrtechniken</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
echo $OUTPUT->footer();
?>

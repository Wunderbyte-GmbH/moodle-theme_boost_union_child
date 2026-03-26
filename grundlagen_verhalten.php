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
 * Grundlagen - Fahrtechniken subpage for NWV theme.
 *
 * @package   theme_nwverkehrserziehung
 * @copyright 2026
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');

// Force load NWV theme for this page.
$CFG->theme = 'nwverkehrserziehung';
$PAGE->set_context(context_system::instance());

$PAGE->set_url(new moodle_url('/theme/nwverkehrserziehung/grundlagen_verhalten.php'));
$PAGE->set_pagelayout('frontpage');
$PAGE->set_title('Fahrtechniken und Fahrtverhalten');

echo $OUTPUT->header();

?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Fahrtechniken und Fahrtverhalten</h1>
            <p class="lead">Lernen Sie die richtigen Fahrtechniken und Verhaltensweisen für sicheres Fahren.</p>
            
            <div class="content mt-4">
                <h3>Korrekte Fahrposition</h3>
                <p>
                    Eine aufrechte Sitzposition bietet die beste Kontrolle über das Fahrzeug und ermöglicht
                    optimale Sicht nach allen Seiten.
                </p>

                <h3>Lenktechniken</h3>
                <ul>
                    <li>Beide Hände am Lenkrad halten (9-3 Uhr oder 10-2 Uhr Position)</li>
                    <li>Sanfte, kontrollierte Lenkbewegungen</li>
                    <li>Rechtzeitig lenken, nicht zu abrupt</li>
                </ul>

                <h3>Bremsverhalten</h3>
                <p>
                    Bremsen Sie früh und sanft an. Vermeiden Sie Vollbremsungen, wenn möglich.
                    Beachte die Bremswegsignale im Straßenverkehr.
                </p>

                <h3>Kurvenfahren</h3>
                <p>
                    Vor der Kurve abbremsen, in der Kurve mit konstanter Geschwindigkeit fahren,
                    nach der Kurve beschleunigen.
                </p>

                <h3>Defensive Fahrweise</h3>
                <ul>
                    <li>Immer vorausschauend fahren</li>
                    <li>Andere Verkehrsteilnehmer beobachten</li>
                    <li>Mit dem Unerwarteten rechnen</li>
                    <li>Sicherheitsabstände einhalten</li>
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

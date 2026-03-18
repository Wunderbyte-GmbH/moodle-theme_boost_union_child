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
 * Grundlagen - Sicherheit subpage for NWV theme.
 *
 * @package   theme_nwverkehrserziehung
 * @copyright 2026
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');

// Force load NWV theme for this page.
$CFG->theme = 'nwverkehrserziehung';
$PAGE->set_context(context_system::instance());

$PAGE->set_url(new moodle_url('/theme/nwverkehrserziehung/grundlagen_sicherheit.php'));
$PAGE->set_pagelayout('frontpage');
$PAGE->set_title('Sicherheit im Straßenverkehr');
$PAGE->set_heading('Sicherheit im Straßenverkehr');

echo $OUTPUT->header();

?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Sicherheit im Straßenverkehr</h1>
            <p class="lead">Erfahren Sie mehr über wichtige Sicherheitsmaßnahmen und -regeln im Straßenverkehr.</p>
            
            <div class="content mt-4">
                <h3>Wichtige Sicherheitsaspekte</h3>
                <ul>
                    <li><strong>Schutzausrüstung:</strong> Helm, Schutzkleidung und reflektierende Materialien</li>
                    <li><strong>Fahrzeugsicherheit:</strong> Regelmäßige Wartung und Überprüfung</li>
                    <li><strong>Defensive Fahrweise:</strong> Aufmerksamkeit und Vorausplanung</li>
                    <li><strong>Verkehrsregeln:</strong> Einhalten aller Vorschriften</li>
                </ul>

                <h3 class="mt-4">Unfallvermeidung</h3>
                <p>
                    Durch bewusstes Fahrverhalten, Aufmerksamkeit und Einhaltung von Verkehrsregeln können
                    die meisten Unfälle vermieden werden.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Navigation</h5>
                    <ul class="list-unstyled">
                        <li><a href="grundlagen.php" class="btn btn-sm btn-outline-primary mb-2">← Zurück zu Grundlagen</a></li>
                        <li><a href="grundlagen_regeln.php" class="btn btn-sm btn-outline-secondary mb-2">Verkehrsregeln</a></li>
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

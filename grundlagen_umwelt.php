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
$PAGE->set_pagelayout('incourse');
$PAGE->set_title('Umweltschonung und Nachhaltigkeit');
$PAGE->set_context(context_system::instance());

echo $OUTPUT->header();

// Prepare sidebar navigation items.
$navitems = [
    ['label' => 'Grundlagen', 'url' => 'grundlagen.php', 'active' => false],
    ['label' => 'Verkehrsregeln', 'url' => 'grundlagen_regeln.php', 'active' => false],
    ['label' => 'Sicherheit', 'url' => 'grundlagen_sicherheit.php', 'active' => false],
    ['label' => 'Fahrtechniken', 'url' => 'grundlagen_verhalten.php', 'active' => false],
    [
        'label' => 'Umweltschonung',
        'url' => 'grundlagen_umwelt.php',
        'active' => true,
        'subitems' => [
            ['label' => 'Emissionsreduktion', 'url' => '#emissions', 'active' => false],
            ['label' => 'Kraftstoffeffizienz', 'url' => '#efficiency', 'active' => false],
            ['label' => 'Ressourcenschonung', 'url' => '#resources', 'active' => false],
        ],
    ],
    ['label' => 'Praxisbörse', 'url' => 'praxis.php', 'active' => false],
    ['label' => 'Netzwerk', 'url' => 'netzwerk.php', 'active' => false],
];

// Main content HTML.
$maincontent = '
<div class="page-content">
    <h1>Umweltschonung und Nachhaltigkeit</h1>
    <p class="lead">Umweltfreundliches Fahren trägt zur Nachhaltigkeit bei und schont Ressourcen.</p>
    
    <div class="content mt-4">
        <h3 id="emissions">Emissionsreduktion</h3>
        <p>
            Durch ausgewogenes Fahrverhalten können CO₂-Emissionen und Schadstoffausstöße
            erheblich reduziert werden.
        </p>

        <h3 id="efficiency">Kraftstoffeffizienz</h3>
        <ul>
            <li>Sanft beschleunigen und bremsen</li>
            <li>Konstante Geschwindigkeit halten</li>
            <li>Unnötige Gewichte vermeiden</li>
            <li>Reifendruck kontrollieren</li>
        </ul>

        <h3 id="resources">Ressourcenschonung</h3>
        <p>
            Nachhaltiges Fahren bedeutet auch:
        </p>
        <ul>
            <li>Wartung und Inspektion</li>
            <li>Umgang mit Verschleißteilen</li>
            <li>Recycling von Fahrzeugteilen</li>
        </ul>
    </div>
</div>
';

$layoutcontext = [
    'sidebar_title' => 'Navigation',
    'sidebar_items' => $navitems,
    'content' => $maincontent,
];

// Render the sidebar layout template.
echo $OUTPUT->render_from_template('theme_nwverkehrserziehung/sidebar_layout', $layoutcontext);

echo $OUTPUT->footer();

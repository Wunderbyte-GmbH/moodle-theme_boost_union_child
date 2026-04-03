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
 * Theme NWV - Upgrade steps
 *
 * @package    theme_nwverkehrserziehung
 * @copyright  2026
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade the theme_nwverkehrserziehung plugin.
 *
 * @param int $oldversion The old version of the plugin.
 * @return bool
 */
function xmldb_theme_nwverkehrserziehung_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2026040300) {
        // Drop the old unused theme_nwv_navigation table.
        $table = new xmldb_table('theme_nwv_navigation');
        if ($dbman->table_exists($table)) {
            $dbman->drop_table($table);
        }

        // Create the new theme_nwv_pages table.
        $table = new xmldb_table('theme_nwv_pages');

        // Adding fields.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('slug', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('parent_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('section', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, null);
        $table->add_field('title', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('nav_title', XMLDB_TYPE_CHAR, '100', null, null, null, null);
        $table->add_field('content', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('contentformat', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, '1');
        $table->add_field('sortorder', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('visible', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Adding indexes.
        $table->add_index('slug', XMLDB_INDEX_UNIQUE, ['slug']);
        $table->add_index('parent_sortorder', XMLDB_INDEX_NOTUNIQUE, ['parent_id', 'sortorder']);
        $table->add_index('section', XMLDB_INDEX_NOTUNIQUE, ['section']);
        $table->add_index('visible', XMLDB_INDEX_NOTUNIQUE, ['visible']);

        // Conditionally launch create table.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // NWV savepoint reached.
        upgrade_plugin_savepoint(true, 2026040300, 'theme', 'nwverkehrserziehung');
    }

    if ($oldversion < 2026040302) {
        // Seed the page tree if table is empty (existing installs that already
        // have the table from the 2026040300 step but no content yet).
        $count = $DB->count_records('theme_nwv_pages');
        if ($count == 0) {
            require_once(__DIR__ . '/install.php');
            xmldb_theme_nwverkehrserziehung_install();
        }

        upgrade_plugin_savepoint(true, 2026040302, 'theme', 'nwverkehrserziehung');
    }

    return true;
}

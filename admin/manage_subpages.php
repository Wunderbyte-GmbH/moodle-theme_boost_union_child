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
 * Manage subpages for tiles in theme nwverkehrserziehung.
 *
 * @package   theme_nwverkehrserziehung
 * @copyright 2026
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(__DIR__ . '/../../../config.php');

use theme_nwverkehrserziehung\subpage_manager;

// Check for admin access.
require_login();
$systemcontext = context_system::instance();
require_capability('theme/nwverkehrserziehung:viewpage', $systemcontext);

// Include the form.
require_once($CFG->dirroot . '/theme/nwverkehrserziehung/classes/forms/subpage_form.php');
// Set up page.
$PAGE->set_context($systemcontext);
$PAGE->set_pagelayout('admin');

$tileid = optional_param('tile', 'tile1', PARAM_ALPHANUM);
$action = optional_param('action', 'list', PARAM_ALPHA);
$subpageid = optional_param('subpageid', null, PARAM_ALPHANUM);

$baseurl = new moodle_url('/theme/nwverkehrserziehung/admin/manage_subpages.php', ['tile' => $tileid]);
$PAGE->set_url($baseurl);

echo $OUTPUT->header();

// Tile selection.
$tiles = ['tile1' => 'Tile 1', 'tile2' => 'Tile 2', 'tile3' => 'Tile 3', 'tile4' => 'Tile 4'];
$tileoptions = [];
foreach ($tiles as $tid => $tname) {
    $tileoptions[] = html_writer::tag(
        'a',
        $tname,
        [
            'href' => new moodle_url($baseurl, ['tile' => $tid]),
            'class' => ($tid === $tileid) ? 'btn btn-primary' : 'btn btn-secondary',
        ]
    );
}
echo html_writer::div(implode(' ', $tileoptions), 'tile-selector mb-3');

// Handle actions.
if ($action === 'edit' || $action === 'add') {
    // Edit or add subpage.
    $subpage = null;
    if ($action === 'edit' && $subpageid) {
        $subpage = subpage_manager::get_subpage($tileid, $subpageid);
        if (!$subpage) {
            throw new moodle_exception('pagenotfound', 'error');
        }
    }

    $formurl = new moodle_url($baseurl, ['action' => $action, 'subpageid' => $subpageid]);
    $form = new theme_nwverkehrserziehung_subpage_form(
        $formurl,
        ['subpage' => $subpage, 'tileid' => $tileid, 'action' => $action]
    );

    if ($form->is_cancelled()) {
        redirect($baseurl);
    } else if ($data = $form->get_data()) {
        // Process form data.
        $navjson = trim($data->navigation_json);
        $navigation = [];
        if ($navjson && $navjson !== '[]') {
            $navigation = json_decode($navjson, true);
        }

        // Process editor content with files.
        $content = file_save_draft_area_files(
            $data->content['itemid'],
            $systemcontext->id,
            'theme_nwverkehrserziehung',
            $tileid . '_subpages',
            $data->id,
            ['subdirs' => true, 'maxbytes' => 0, 'noclean' => true],
            $data->content['text']
        );

        $subpagedata = [
            'id' => $data->id,
            'title' => $data->title,
            'content' => $content,
            'navigation' => $navigation,
        ];

        if ($action === 'add') {
            $success = subpage_manager::add_subpage($tileid, $subpagedata);
            $message = $success ? 'Subpage added successfully' : 'Failed to add subpage';
        } else {
            $success = subpage_manager::update_subpage($tileid, $data->id, $subpagedata);
            $message = $success ? 'Subpage updated successfully' : 'Failed to update subpage';
        }

        if ($success) {
            redirect($baseurl, $message, null, \core\output\notification::NOTIFY_SUCCESS);
        } else {
            redirect($baseurl, $message, null, \core\output\notification::NOTIFY_ERROR);
        }
    }

    $form->display();

} else if ($action === 'delete' && $subpageid) {
    // Delete subpage.
    $subpage = subpage_manager::get_subpage($tileid, $subpageid);
    if (!$subpage) {
        throw new moodle_exception('pagenotfound', 'error');
    }

    if (optional_param('confirm', 0, PARAM_BOOL)) {
        $success = subpage_manager::delete_subpage($tileid, $subpageid);
        $message = $success ? 'Subpage deleted successfully' : 'Failed to delete subpage';

        if ($success) {
            redirect($baseurl, $message, null, \core\output\notification::NOTIFY_SUCCESS);
        } else {
            redirect($baseurl, $message, null, \core\output\notification::NOTIFY_ERROR);
        }
    } else {
        // Confirmation page.
        echo html_writer::div('Are you sure you want to delete "' . s($subpage['title']) . '"?', 'alert alert-warning');

        $confirmurl = new moodle_url($baseurl, ['action' => 'delete', 'subpageid' => $subpageid, 'confirm' => 1]);
        $cancelurl = $baseurl;

        echo html_writer::div(
            html_writer::link($confirmurl, 'Delete', ['class' => 'btn btn-danger']) . ' ' .
            html_writer::link($cancelurl, 'Cancel', ['class' => 'btn btn-secondary']),
            'mt-3'
        );
    }

} else {
    // List subpages.
    $subpages = subpage_manager::get_subpage_data($tileid);


    // Add button.
    $addurl = new moodle_url($baseurl, ['action' => 'add']);
    echo html_writer::link($addurl, 'Add Subpage', ['class' => 'btn btn-success mb-3']);

    if (empty($subpages)) {
        echo html_writer::div('No subpages yet.', 'alert alert-info');
    } else {
        // Create table.
        $table = new html_table();
        $table->head = ['ID', 'Title', 'Content Preview', 'Actions'];
        $table->attributes = ['class' => 'table table-striped'];

        foreach ($subpages as $subpage) {
            $preview = substr(strip_tags($subpage['content']), 0, 50) . '...';

            $editurl = new moodle_url($baseurl, ['action' => 'edit', 'subpageid' => $subpage['id']]);
            $deleteurl = new moodle_url($baseurl, ['action' => 'delete', 'subpageid' => $subpage['id']]);

            $actions = html_writer::link($editurl, 'Edit', ['class' => 'btn btn-sm btn-primary']) . ' ' .
                       html_writer::link($deleteurl, 'Delete', ['class' => 'btn btn-sm btn-danger']);

            $table->data[] = [
                s($subpage['id']),
                s($subpage['title']),
                s($preview),
                $actions,
            ];
        }

        echo html_writer::table($table);
    }
}

echo $OUTPUT->footer();

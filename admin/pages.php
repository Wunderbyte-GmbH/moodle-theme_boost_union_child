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
 * Admin page list — displays all CMS pages with add/edit/delete actions.
 *
 * @package    theme_nwverkehrserziehung
 * @copyright  2026
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');

use theme_nwverkehrserziehung\page_manager;

// Force NWV theme.
$CFG->theme = 'nwverkehrserziehung';

require_login();
$context = context_system::instance();
require_capability('theme/nwverkehrserziehung:managepages', $context);

// Handle delete action.
$delete = optional_param('delete', 0, PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_BOOL);
if ($delete && confirm_sesskey()) {
    if ($confirm) {
        page_manager::delete($delete);
        redirect(
            new moodle_url('/theme/nwverkehrserziehung/admin/pages.php'),
            get_string('page_deleted', 'theme_nwverkehrserziehung'),
            null,
            \core\output\notification::NOTIFY_SUCCESS
        );
    }
}

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/theme/nwverkehrserziehung/admin/pages.php'));
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('page_manage', 'theme_nwverkehrserziehung'));
$PAGE->set_heading(get_string('page_manage', 'theme_nwverkehrserziehung'));

// Load the modal form JS before header output.
$PAGE->requires->js_call_amd('theme_nwverkehrserziehung/page_editor', 'init');

echo $OUTPUT->header();

// Delete confirmation dialog.
if ($delete && !$confirm) {
    $page = page_manager::get_by_id($delete);
    if ($page) {
        $confirmurl = new moodle_url('/theme/nwverkehrserziehung/admin/pages.php', [
            'delete' => $delete,
            'confirm' => 1,
            'sesskey' => sesskey(),
        ]);
        $cancelurl = new moodle_url('/theme/nwverkehrserziehung/admin/pages.php');
        echo $OUTPUT->confirm(
            get_string('page_delete_confirm', 'theme_nwverkehrserziehung', $page->title),
            $confirmurl,
            $cancelurl
        );
        echo $OUTPUT->footer();
        die;
    }
}

// Add new page button (opens modal).
echo html_writer::div(
    html_writer::link('#', get_string('page_add', 'theme_nwverkehrserziehung'), [
        'class' => 'btn btn-primary',
        'data-action' => 'nwv-page-add',
    ]),
    'mb-3'
);

// Build page list table.
$pages = page_manager::get_all();

if (empty($pages)) {
    echo $OUTPUT->notification(get_string('page_none', 'theme_nwverkehrserziehung'), 'info');
} else {
    // Build a lookup for parent titles.
    $pagelookup = [];
    foreach ($pages as $p) {
        $pagelookup[$p->id] = $p;
    }

    $table = new html_table();
    $table->head = [
        get_string('page_title', 'theme_nwverkehrserziehung'),
        get_string('page_slug', 'theme_nwverkehrserziehung'),
        get_string('page_section', 'theme_nwverkehrserziehung'),
        get_string('page_parent', 'theme_nwverkehrserziehung'),
        get_string('page_sortorder', 'theme_nwverkehrserziehung'),
        get_string('page_visible', 'theme_nwverkehrserziehung'),
        get_string('actions'),
    ];
    $table->attributes['class'] = 'admintable generaltable';

    foreach ($pages as $page) {
        $deleteurl = new moodle_url('/theme/nwverkehrserziehung/admin/pages.php', [
            'delete' => $page->id,
            'sesskey' => sesskey(),
        ]);
        $viewurl = new moodle_url('/theme/nwverkehrserziehung/page.php', ['slug' => $page->slug]);

        $parentname = '-';
        if (!empty($page->parent_id) && isset($pagelookup[$page->parent_id])) {
            $parentname = $pagelookup[$page->parent_id]->title;
        }

        $visibleicon = $page->visible
            ? $OUTPUT->pix_icon('t/show', get_string('visible'))
            : $OUTPUT->pix_icon('t/hide', get_string('hidden', 'core'));

        $actions = html_writer::link($viewurl, $OUTPUT->pix_icon('t/preview', get_string('view')), ['target' => '_blank']);
        $actions .= ' ';
        $actions .= html_writer::link('#', $OUTPUT->pix_icon('t/edit', get_string('edit')), [
            'data-action' => 'nwv-page-edit',
            'data-pageid' => $page->id,
        ]);
        $actions .= ' ';
        $actions .= html_writer::link($deleteurl, $OUTPUT->pix_icon('t/delete', get_string('delete')));

        $titlecell = html_writer::link('#', format_string($page->title), [
            'data-action' => 'nwv-page-edit',
            'data-pageid' => $page->id,
        ]);

        $table->data[] = [
            $titlecell,
            html_writer::tag('code', s($page->slug)),
            s($page->section),
            s($parentname),
            $page->sortorder,
            $visibleicon,
            $actions,
        ];
    }

    echo html_writer::table($table);
}

echo $OUTPUT->footer();

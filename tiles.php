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
 * Page to edit frontpage tiles for theme nwverkehrserziehung.
 *
 * @package   theme_nwverkehrserziehung
 * @copyright 2026 Wunderbyte GmbH <info@wunderbyte.at>
 * @author    Thoma Winkler <thoma@wunderbyte.at>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');

// Check for admin access.
require_login();
$systemcontext = context_system::instance();
require_capability('theme/nwverkehrserziehung:viewpage', $systemcontext);

// Set up page.
$PAGE->set_context($systemcontext);
$PAGE->set_url(new moodle_url('/theme/nwverkehrserziehung/tiles.php'));
$PAGE->set_pagelayout('admin');

// Include the form.
require_once($CFG->dirroot . '/theme/nwverkehrserziehung/classes/forms/tiles_form.php');

// Create form instance.
$form = new theme_nwverkehrserziehung_tiles_form();

// Handle form submission.
if ($data = $form->get_data()) {
    // Save slider data to admin settings.
    for ($i = 1; $i <= 3; $i++) {
        $slidename = 'slide' . $i;

        // Save slide text.
        if (isset($data->{$slidename . 'text'})) {
            $textdata = $data->{$slidename . 'text'};
            $text = is_array($textdata) ? $textdata['text'] : $textdata;
            set_config($slidename . 'text', $text, 'theme_nwverkehrserziehung');
        }
    }

    // Save tile data to admin settings.
    for ($i = 1; $i <= 4; $i++) {
        $tilename = 'tile' . $i;

        // Save tile title.
        set_config($tilename . 'title', $data->{$tilename . 'title'}, 'theme_nwverkehrserziehung');

        // Save tile text.
        if (isset($data->{$tilename . 'text'})) {
            $textdata = $data->{$tilename . 'text'};
            $text = is_array($textdata) ? $textdata['text'] : $textdata;
            set_config($tilename . 'text', $text, 'theme_nwverkehrserziehung');
        }

        // Save tile fullpage content.
        if (isset($data->{$tilename . 'fullpage_content'})) {
            $fullpagedata = $data->{$tilename . 'fullpage_content'};
            $fullpage = is_array($fullpagedata) ? $fullpagedata['text'] : $fullpagedata;
            set_config($tilename . 'fullpage_content', $fullpage, 'theme_nwverkehrserziehung');
        }

        // Save tile link.
        set_config($tilename . 'link', $data->{$tilename . 'link'}, 'theme_nwverkehrserziehung');
    }

    // Save Praxisbörse description.
    if (isset($data->praxisboerse_description)) {
        $descdata = $data->praxisboerse_description;
        $description = is_array($descdata) ? $descdata['text'] : $descdata;
        set_config('praxisboerse_description', $description, 'theme_nwverkehrserziehung');
    }

    // Handle file uploads for slider and tile images.
    $fs = get_file_storage();
    $context = context_system::instance();

    // Handle slider images.
    for ($i = 1; $i <= 3; $i++) {
        $slidename = 'slide' . $i;
        $imagefilename = $slidename . 'image';

        if (isset($data->{$imagefilename}) && !empty($data->{$imagefilename})) {
            $draftitemid = $data->{$imagefilename};

            // Get files from draft area.
            $usercontext = context_user::instance($USER->id);
            $draftfiles = $fs->get_area_files(
                $usercontext->id,
                'user',
                'draft',
                $draftitemid,
                'sortorder DESC, id ASC',
                false
            );

            if (count($draftfiles) > 0) {
                $fs->delete_area_files($context->id, 'theme_nwverkehrserziehung', $imagefilename, 0);

                file_save_draft_area_files(
                    $draftitemid,
                    $context->id,
                    'theme_nwverkehrserziehung',
                    $imagefilename,
                    0,
                    ['subdirs' => 0, 'maxbytes' => 0, 'accepted_types' => 'web_image']
                );

                $files = $fs->get_area_files(
                    $context->id,
                    'theme_nwverkehrserziehung',
                    $imagefilename,
                    0,
                    'sortorder DESC, id ASC',
                    false
                );
                if (count($files) > 0) {
                    $file = reset($files);
                    set_config($imagefilename, $file->get_filename(), 'theme_nwverkehrserziehung');
                }
            }
        }
    }

    // Handle tile images.
    for ($i = 1; $i <= 4; $i++) {
        $tilename = 'tile' . $i;
        $imagefilename = $tilename . 'image';

        // Save new file if uploaded using draft area.
        if (isset($data->{$imagefilename}) && !empty($data->{$imagefilename})) {
            $draftitemid = $data->{$imagefilename};

            // Get files from draft area.
            $usercontext = context_user::instance($USER->id);
            $draftfiles = $fs->get_area_files(
                $usercontext->id,
                'user',
                'draft',
                $draftitemid,
                'sortorder DESC, id ASC',
                false
            );

            // Only process if there are new files in draft (not just the empty directory marker).
            if (count($draftfiles) > 0) {
                // Delete existing files ONLY if we have new files to save.
                $fs->delete_area_files($context->id, 'theme_nwverkehrserziehung', $imagefilename, 0);

                // Save files from draft area to permanent area.
                file_save_draft_area_files(
                    $draftitemid,
                    $context->id,
                    'theme_nwverkehrserziehung',
                    $imagefilename,
                    0,
                    ['subdirs' => 0, 'maxbytes' => 0, 'accepted_types' => 'web_image']
                );

                // Get the saved file and store its filename in config.
                $files = $fs->get_area_files(
                    $context->id,
                    'theme_nwverkehrserziehung',
                    $imagefilename,
                    0,
                    'sortorder DESC, id ASC',
                    false
                );
                if (count($files) > 0) {
                    $file = reset($files);
                    set_config($imagefilename, $file->get_filename(), 'theme_nwverkehrserziehung');
                } else {
                    set_config($imagefilename, '', 'theme_nwverkehrserziehung');
                }
            }
            // If draft is empty, do NOT delete existing files - user just opened and saved without changing.
        }
    }

    // Clear caches.
    theme_reset_all_caches();

    // Redirect with success message.
    redirect($PAGE->url, get_string('changessaved'), 2);
}

// Output page.
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('frontpagesettings', 'theme_nwverkehrserziehung'));

// Set initial data.
$form->set_data($form->get_initial_data());

// Display form.
$form->display();

echo $OUTPUT->footer();

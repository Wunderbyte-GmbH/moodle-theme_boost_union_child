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
 * Form for editing frontpage tiles in theme nwverkehrserziehung.
 *
 * @package   theme_nwverkehrserziehung
 * @copyright 2026 Wunderbyte GmbH <info@wunderbyte.at>
 * @author    Thoma Winkler <thoma@wunderbyte.at>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * Frontpage tiles edit form.
 *
 * @package   theme_nwverkehrserziehung
 * @copyright 2026 Wunderbyte GmbH <info@wunderbyte.at>
 * @author    Thoma Winkler <thoma@wunderbyte.at>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_nwverkehrserziehung_tiles_form extends moodleform {
    /**
     * Define the form.
     */
    public function definition() {
        $mform = $this->_form;
        $config = get_config('theme_nwverkehrserziehung');
        $context = context_system::instance();

        // Add slider header.
        $mform->addElement('header', 'sliderhdr', get_string('slider', 'theme_nwverkehrserziehung'));

        // Add slider slides (1-3).
        for ($i = 1; $i <= 3; $i++) {
            $this->add_slider_fields($mform, $i, $config, $context);
        }

        // Add heading.
        $mform->addElement('header', 'tileshdr', get_string('frontpage', 'theme_nwverkehrserziehung'));

        // Add tiles (1-4).
        for ($i = 1; $i <= 4; $i++) {
            $this->add_tile_fields($mform, $i, $config, $context);
        }

        // Add Praxisbörse description header.
        $mform->addElement(
            'header',
            'praxisboersehdr',
            get_string('praxisboersetitle', 'theme_nwverkehrserziehung')
        );

        // Add Praxisbörse description editor.
        $mform->addElement(
            'editor',
            'praxisboerse_description',
            get_string('praxisboersedescsetting', 'theme_nwverkehrserziehung'),
            null,
            ['subdirs' => 1, 'maxbytes' => 0, 'noclean' => 1, 'context' => $context]
        );
        $mform->setType('praxisboerse_description', PARAM_RAW);
        $mform->setDefault('praxisboerse_description', [
            'text' => $config->praxisboerse_description ?? '',
        ]);

        // Add buttons.
        $this->add_action_buttons(true, get_string('savechanges'));
    }

    /**
     * Add form fields for a single slider slide.
     *
     * @param MoodleQuickForm $mform The form object.
     * @param int $slidenum The slide number (1-3).
     * @param stdClass $config The theme configuration.
     * @param context $context The system context.
     */
    private function add_slider_fields(&$mform, $slidenum, $config, $context) {
        $slidename = 'slide' . $slidenum;

        // Add fieldset for this slide.
        $mform->addElement(
            'header',
            $slidename . 'hdr',
            get_string('slidertitle', 'theme_nwverkehrserziehung') . ' ' . $slidenum
        );

        // Get available pix logos.
        $pixlogos = $this->get_pix_logos();
        $logooptions = ['none' => '- Select a logo -'] + $pixlogos;

        // Slide logo selector.
        $mform->addElement(
            'select',
            $slidename . 'logo',
            get_string('sliderlogo', 'theme_nwverkehrserziehung'),
            $logooptions
        );
        $mform->setType($slidename . 'logo', PARAM_ALPHANUM);
        $mform->setDefault($slidename . 'logo', $config->{$slidename . 'logo'} ?? 'none');

        // Slide text (HTML editor).
        $mform->addElement(
            'editor',
            $slidename . 'text',
            get_string('slidertext', 'theme_nwverkehrserziehung'),
            null,
            ['subdirs' => 1, 'maxbytes' => 0, 'noclean' => 1, 'context' => $context]
        );
        $mform->setType($slidename . 'text', PARAM_RAW);
        $mform->setDefault($slidename . 'text', [
            'text' => $config->{$slidename . 'text'} ?? '',
        ]);
    }

    /**
     * Get available pix logos from the theme directory.
     *
     * @return array Array of logo filenames keyed by filename without extension.
     */
    private function get_pix_logos() {
        global $CFG;

        $logos = [];
        $pixpath = $CFG->dirroot . '/theme/nwverkehrserziehung/pix/logos';

        if (is_dir($pixpath)) {
            $files = scandir($pixpath);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && preg_match('/\.(png|jpg|jpeg|gif)$/i', $file)) {
                    $name = basename($file, pathinfo($file, PATHINFO_EXTENSION));
                    $logos[$file] = $name;
                }
            }
            asort($logos);
        }

        return $logos;
    }

    /**
     * Add form fields for a single tile.

     *
     * @param MoodleQuickForm $mform The form object.
     * @param int $tilenum The tile number (1-4).
     * @param stdClass $config The theme configuration.
     * @param context $context The system context.
     */
    private function add_tile_fields(&$mform, $tilenum, $config, $context) {
        $tilename = 'tile' . $tilenum;

        // Add fieldset for this tile.
        $mform->addElement(
            'header',
            $tilename . 'hdr',
            get_string('tile' . $tilenum . 'titlesetting', 'theme_nwverkehrserziehung')
        );

        // Tile title.
        $mform->addElement(
            'text',
            $tilename . 'title',
            get_string('tile' . $tilenum . 'titlesetting', 'theme_nwverkehrserziehung')
        );
        $mform->setType($tilename . 'title', PARAM_TEXT);
        $mform->setDefault(
            $tilename . 'title',
            $config->{$tilename . 'title'} ?? get_string('tile' . $tilenum . 'titlesetting', 'theme_nwverkehrserziehung')
        );

        // Tile image with existing file preview.
        $imagefilename = $tilename . 'image';
        $filepickeroptions = [
            'subdirs' => 0,
            'maxbytes' => 0,
            'accepted_types' => 'web_image',
            'context' => $context,
        ];
        $mform->addElement(
            'filepicker',
            $imagefilename,
            get_string('tile' . $tilenum . 'imagesetting', 'theme_nwverkehrserziehung'),
            null,
            $filepickeroptions
        );

        // Prepare draft area for existing image files.
        $draftitemid = file_get_unused_draft_itemid();
        file_prepare_draft_area(
            $draftitemid,
            $context->id,
            'theme_nwverkehrserziehung',
            $imagefilename,
            0
        );
        $mform->setDefault($imagefilename, $draftitemid);

        // Show current image if it exists.
        if (!empty($config->{$imagefilename})) {
            $imageurl = moodle_url::make_pluginfile_url(
                $context->id,
                'theme_nwverkehrserziehung',
                $imagefilename,
                0,
                '/',
                $config->{$imagefilename}
            );
            $html = html_writer::img($imageurl, 'Current image', ['style' => 'max-width: 200px; margin-top: 10px;']);
            $mform->addElement('static', $tilename . 'image_preview', '', $html);
        }

        // Tile text (HTML editor).
        $mform->addElement(
            'editor',
            $tilename . 'text',
            get_string('tile' . $tilenum . 'textsetting', 'theme_nwverkehrserziehung'),
            null,
            ['subdirs' => 1, 'maxbytes' => 0, 'noclean' => 1, 'context' => $context]
        );
        $mform->setType($tilename . 'text', PARAM_RAW);
        $mform->setDefault($tilename . 'text', [
            'text' => $config->{$tilename . 'text'} ?? get_string('tile' . $tilenum . 'textdefault', 'theme_nwverkehrserziehung'),
        ]);

        // Tile fullpage content.
        $mform->addElement(
            'editor',
            $tilename . 'fullpage_content',
            get_string('tile' . $tilenum . 'fullpagecontentsetting', 'theme_nwverkehrserziehung'),
            null,
            ['subdirs' => 1, 'maxbytes' => 0, 'noclean' => 1, 'context' => $context]
        );
        $mform->setType($tilename . 'fullpage_content', PARAM_RAW);
        $mform->setDefault($tilename . 'fullpage_content', [
            'text' => $config->{$tilename . 'fullpage_content'} ?? '',
        ]);

        // Tile link URL.
        $mform->addElement(
            'text',
            $tilename . 'link',
            get_string('tile' . $tilenum . 'linksetting', 'theme_nwverkehrserziehung')
        );
        $mform->setType($tilename . 'link', PARAM_URL);
        $mform->setDefault($tilename . 'link', $config->{$tilename . 'link'} ?? '');
    }

    /**
     * Get the initial data for the form.
     *
     * @return array The initial data.
     */
    public function get_initial_data() {
        $data = [];
        $config = get_config('theme_nwverkehrserziehung');

        // Add slider data.
        for ($i = 1; $i <= 3; $i++) {
            $slidename = 'slide' . $i;
            $data[$slidename . 'text'] = [
                'text' => $config->{$slidename . 'text'} ?? '',
            ];
        }

        // Add tile data.
        for ($i = 1; $i <= 4; $i++) {
            $tilename = 'tile' . $i;
            $data[$tilename . 'title'] = $config->{$tilename . 'title'} ?? '';
            $data[$tilename . 'text'] = [
                'text' => $config->{$tilename . 'text'} ?? '',
            ];
            $data[$tilename . 'fullpage_content'] = [
                'text' => $config->{$tilename . 'fullpage_content'} ?? '',
            ];
            $data[$tilename . 'link'] = $config->{$tilename . 'link'} ?? '';
        }

        // Add Praxisbörse description.
        $data['praxisboerse_description'] = [
            'text' => $config->praxisboerse_description ?? '',
        ];

        return $data;
    }
}

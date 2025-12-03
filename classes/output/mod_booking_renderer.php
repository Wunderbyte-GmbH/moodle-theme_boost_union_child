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
 * A custom renderer class that extends the plugin_renderer_base and is used by the booking module.
 *
 * @package theme_boost_union_child
 * @copyright 2023 Wunderbyte GmbH <info@wunderbyte.at>
 * @author Christian Badusch
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_boost_union_child\output;

use mod_booking;
use html_writer;
use mod_booking\output\bookingoption_description;
use mod_booking\output\col_availableplaces;
use mod_booking\output\col_coursestarttime;
use mod_booking\price;
use mod_booking\singleton_service;

/**
 * A custom renderer class that extends the plugin_renderer_base and is used by the booking module.
 *
 * @package mod_booking
 * @copyright 2023 Wunderbyte GmbH <info@wunderbyte.at>
 * @author David Bogner, Andraž Prinčič
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_booking_renderer extends \mod_booking\output\renderer {

    /**
     * Renders the booking option description view.
     *
     * This function processes the booking option description data, prepares necessary
     * details for display, and renders them using a template.
     *
     * @param bookingoption_description $data The booking option description data object.
     * @return string The rendered HTML output.
     */
    public function render_bookingoption_description_view(bookingoption_description $data) {
             $o = '';
        $data = $data->export_for_template($this);
        try {
            $o .= $this->render_from_template('mod_booking/bookingoption_description_view', $data);
        } catch (Exception $e) {
            $o .= get_string('bookingoptionupdated', 'mod_booking');
        }
        return $o;
    }
  

}

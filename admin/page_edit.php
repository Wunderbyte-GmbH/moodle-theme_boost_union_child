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
 * Admin page edit — non-JS fallback / URL entry point.
 *
 * Editing is primarily handled via the dynamic modal form.
 * This page serves as a URL endpoint for the editor autosave
 * and as a no-JS fallback that redirects to the admin listing.
 *
 * @package    theme_nwverkehrserziehung
 * @copyright  2026
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');

// Force NWV theme.
$CFG->theme = 'nwverkehrserziehung';

require_login();
$context = context_system::instance();
require_capability('theme/nwverkehrserziehung:managepages', $context);

// Redirect to the admin listing page — editing happens in modal.
redirect(new moodle_url('/theme/nwverkehrserziehung/admin/pages.php'));

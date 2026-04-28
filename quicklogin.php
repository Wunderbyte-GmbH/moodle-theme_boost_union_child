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
 * Theme Boost Union Child - Quick Login handler
 *
 * Allows automatic login with a pre-configured role account (admin, manager, user)
 * for development and demo environments.
 *
 * This page must only be used in non-production environments.
 * Enable it by configuring credentials in the theme settings under "Quick Login".
 *
 * @package    theme_boost_union_child
 * @copyright  2024 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// phpcs:disable moodle.Files.RequireLogin.Missing

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/theme/boost_union/lib.php');

// Only accept POST requests.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(new moodle_url('/login/index.php'));
}

// CSRF protection via sesskey.
require_sesskey();

// Check that the quick login feature is enabled in theme settings.
$quickloginsetting = get_config('theme_boost_union_child', 'quickloginenabled');
if (empty($quickloginsetting) || $quickloginsetting != THEME_BOOST_UNION_SETTING_SELECT_YES) {
    redirect(
        new moodle_url('/login/index.php'),
        get_string('quicklogindisabled', 'theme_boost_union_child'),
        null,
        \core\output\notification::NOTIFY_WARNING
    );
}

// Validate the requested role.
$role = required_param('role', PARAM_ALPHA);
$validroles = ['admin', 'manager', 'user'];
if (!in_array($role, $validroles, true)) {
    redirect(new moodle_url('/login/index.php'));
}

// Retrieve the configured credentials for this role from theme settings.
$username = get_config('theme_boost_union_child', 'quicklogin' . $role . 'username');
$password = get_config('theme_boost_union_child', 'quicklogin' . $role . 'password');

if (empty($username) || empty($password)) {
    redirect(
        new moodle_url('/login/index.php'),
        get_string('quicklogincredentialsmissing', 'theme_boost_union_child'),
        null,
        \core\output\notification::NOTIFY_ERROR
    );
}

// Attempt to authenticate with the configured credentials.
$user = authenticate_user_login($username, $password);

if (!$user) {
    redirect(
        new moodle_url('/login/index.php'),
        get_string('quickloginfailed', 'theme_boost_union_child'),
        null,
        \core\output\notification::NOTIFY_ERROR
    );
}

// Complete the Moodle login process.
complete_user_login($user);

// Redirect to the site homepage.
redirect(new moodle_url('/'));

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
 * Shortcodes for theme_boost_union_child
 *
 * @package theme_boost_union_child
 * @subpackage db
 * @copyright 2024 Thomas Winkler
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$shortcodes = [
    'bcunews' => [
        'callback' => 'theme_boost_union_child\shortcodes::wbnews',
        'wraps' => false,
        'description' => 'bcunews',
    ],
    'bcumycourses' => [
        'callback' => 'theme_boost_union_child\shortcodes::bcumycourses',
        'wraps' => false,
        'description' => 'bcumycourses',
    ],
    'bcusomevideos' => [
        'callback' => 'theme_boost_union_child\shortcodes::top3videos',
        'wraps' => false,
        'description' => 'bcusomevideos',
    ],
    'bcunewnews' => [
        'callback' => 'theme_boost_union_child\shortcodes::bcunewnews',
        'wraps' => false,
        'description' => 'bcunewnews',
    ],
    'bcuusername' => [
        'callback' => 'theme_boost_union_child\shortcodes::bcuusername',
        'wraps' => false,
        'description' => 'bcuusername',
    ],
    'bcuevents' => [
        'callback' => 'theme_boost_union_child\shortcodes::bcuevents',
        'wraps' => false,
        'description' => 'bcuevents',
    ],
    'bcusubito' => [
        'callback' => 'theme_boost_union_child\shortcodes::bcusubito',
        'wraps' => false,
        'description' => 'bcusubito',
    ],
    'bcuseguire' => [
        'callback' => 'theme_boost_union_child\shortcodes::bcuseguire',
        'wraps' => false,
        'description' => 'bcuseguire',
    ],
    'bcunuovo' => [
        'callback' => 'theme_boost_union_child\shortcodes::bcunuovo',
        'wraps' => false,
        'description' => 'bcunuovo',
    ],
    'bcucourseprogress' => [
        'callback' => 'theme_boost_union_child\shortcodes::bcucourseprogress',
        'wraps' => false,
        'description' => 'bcucourseprogress',
    ], 
];

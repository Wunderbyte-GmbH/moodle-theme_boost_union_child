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
 * Subpage manager for dynamic tile-based content pages.
 *
 * @package   theme_nwverkehrserziehung
 * @copyright 2026
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_nwverkehrserziehung;

/**
 * Manages subpages for tiles.
 */
class subpage_manager {
    /**
     * Get subpage data by tile ID.
     *
     * @param string $tileid The tile identifier (e.g., 'tile1', 'tile2').
     * @return array The subpage data with structure: ['id', 'title', 'content', 'navigation'].
     */
    public static function get_subpage_data($tileid) {
        $config = get_config('theme_nwverkehrserziehung');
        $configkey = $tileid . '_subpages';

        if (empty($config->{$configkey})) {
            return [];
        }

        try {
            $subpages = json_decode($config->{$configkey}, true);
            if (!is_array($subpages)) {
                return [];
            }
            return $subpages;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Save subpage data for a tile.
     *
     * @param string $tileid The tile identifier (e.g., 'tile1', 'tile2').
     * @param array $subpages Array of subpage data.
     * @return bool Success status.
     */
    public static function save_subpage_data($tileid, $subpages) {
        $config = get_config('theme_nwverkehrserziehung');
        $configkey = $tileid . '_subpages';

        try {
            $data = json_encode($subpages);
            set_config($configkey, $data, 'theme_nwverkehrserziehung');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Add a new subpage to a tile.
     *
     * @param string $tileid The tile identifier.
     * @param array $subpage The subpage data with keys: id, title, content, navigation.
     * @return bool Success status.
     */
    public static function add_subpage($tileid, $subpage) {
        $subpages = self::get_subpage_data($tileid);

        // Ensure required fields.
        $subpage = [
            'id' => $subpage['id'] ?? uniqid('subpage_'),
            'title' => $subpage['title'] ?? 'Untitled',
            'content' => $subpage['content'] ?? '',
            'navigation' => $subpage['navigation'] ?? [],
        ];

        // Prevent duplicates.
        foreach ($subpages as $existing) {
            if ($existing['id'] === $subpage['id']) {
                return false;
            }
        }

        $subpages[] = $subpage;
        return self::save_subpage_data($tileid, $subpages);
    }

    /**
     * Update an existing subpage.
     *
     * @param string $tileid The tile identifier.
     * @param string $subpageid The subpage ID to update.
     * @param array $data Updated subpage data.
     * @return bool Success status.
     */
    public static function update_subpage($tileid, $subpageid, $data) {
        $subpages = self::get_subpage_data($tileid);

        foreach ($subpages as &$subpage) {
            if ($subpage['id'] === $subpageid) {
                $subpage['title'] = $data['title'] ?? $subpage['title'];
                $subpage['content'] = $data['content'] ?? $subpage['content'];
                if (isset($data['navigation'])) {
                    $subpage['navigation'] = $data['navigation'];
                }
                return self::save_subpage_data($tileid, $subpages);
            }
        }

        return false;
    }

    /**
     * Get a specific subpage.
     *
     * @param string $tileid The tile identifier.
     * @param string $subpageid The subpage ID.
     * @return array|null The subpage data or null if not found.
     */
    public static function get_subpage($tileid, $subpageid) {
        $subpages = self::get_subpage_data($tileid);

        foreach ($subpages as $subpage) {
            if ($subpage['id'] === $subpageid) {
                return $subpage;
            }
        }

        return null;
    }

    /**
     * Delete a subpage.
     *
     * @param string $tileid The tile identifier.
     * @param string $subpageid The subpage ID to delete.
     * @return bool Success status.
     */
    public static function delete_subpage($tileid, $subpageid) {
        $subpages = self::get_subpage_data($tileid);
        $updated = [];

        foreach ($subpages as $subpage) {
            if ($subpage['id'] !== $subpageid) {
                $updated[] = $subpage;
            }
        }

        return self::save_subpage_data($tileid, $updated);
    }

    /**
     * Get all subpages for a tile as navigation array.
     *
     * @param string $tileid The tile identifier.
     * @param string $activeid The currently active subpage ID (optional).
     * @return array Navigation items for use in templates.
     */
    public static function get_navigation($tileid, $activeid = null) {
        $subpages = self::get_subpage_data($tileid);
        $navitems = [];

        foreach ($subpages as $subpage) {
            $navitems[] = [
                'label' => $subpage['title'],
                'url' => 'page.php?id=' . $subpage['id'] . '&tile=' . $tileid,
                'active' => ($activeid && $subpage['id'] === $activeid) ? true : false,
            ];
        }

        return $navitems;
    }
}

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
 * Page manager for the NWV theme CMS.
 *
 * Provides CRUD operations on the theme_nwv_pages table and
 * builds navigation trees from the page hierarchy.
 *
 * @package    theme_nwverkehrserziehung
 * @copyright  2026
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_nwverkehrserziehung;

defined('MOODLE_INTERNAL') || die();

/**
 * Manages CMS pages stored in theme_nwv_pages.
 */
class page_manager {

    /** @var string The database table name. */
    const TABLE = 'theme_nwv_pages';

    /** @var string Cache component. */
    const CACHE_COMPONENT = 'theme_nwverkehrserziehung';

    /** @var string Cache area for navigation tree. */
    const CACHE_AREA = 'navigation';

    /** @var string Cache key for the full nav tree. */
    const CACHE_KEY_NAV = 'navtree';

    /**
     * Get a page by its database ID.
     *
     * @param int $id
     * @return \stdClass|false
     */
    public static function get_by_id(int $id) {
        global $DB;
        return $DB->get_record(self::TABLE, ['id' => $id]);
    }

    /**
     * Get a page by its URL slug.
     *
     * @param string $slug
     * @return \stdClass|false
     */
    public static function get_by_slug(string $slug) {
        global $DB;
        return $DB->get_record(self::TABLE, ['slug' => $slug]);
    }

    /**
     * Get all direct children of a page.
     *
     * @param int $parentid Parent page ID (0 for root pages).
     * @param bool $visibleonly Only return visible pages.
     * @return array
     */
    public static function get_children(int $parentid = 0, bool $visibleonly = true): array {
        global $DB;
        $params = ['parent_id' => $parentid];
        if ($visibleonly) {
            $params['visible'] = 1;
        }
        return $DB->get_records(self::TABLE, $params, 'sortorder ASC, title ASC');
    }

    /**
     * Get all pages in a section.
     *
     * @param string $section Section name (grundlagen, netzwerk, kontakt, footer).
     * @param bool $visibleonly Only return visible pages.
     * @return array
     */
    public static function get_by_section(string $section, bool $visibleonly = true): array {
        global $DB;
        $params = ['section' => $section];
        if ($visibleonly) {
            $params['visible'] = 1;
        }
        return $DB->get_records(self::TABLE, $params, 'sortorder ASC, title ASC');
    }

    /**
     * Get all pages (for admin listing).
     *
     * @return array
     */
    public static function get_all(): array {
        global $DB;
        return $DB->get_records(self::TABLE, null, 'section ASC, sortorder ASC, title ASC');
    }

    /**
     * Check if a page has children.
     *
     * @param int $pageid
     * @return bool
     */
    public static function has_children(int $pageid): bool {
        global $DB;
        return $DB->record_exists(self::TABLE, ['parent_id' => $pageid]);
    }

    /**
     * Save a page (insert or update).
     *
     * @param \stdClass $data Page data with fields matching the DB columns.
     * @return int The page ID.
     */
    public static function save(\stdClass $data): int {
        global $DB, $USER;

        $now = time();
        $data->timemodified = $now;
        $data->usermodified = $USER->id;

        if (!empty($data->id)) {
            $DB->update_record(self::TABLE, $data);
            $id = $data->id;
        } else {
            $data->timecreated = $now;
            $id = $DB->insert_record(self::TABLE, $data);
        }

        // Invalidate navigation cache.
        self::invalidate_cache();

        return $id;
    }

    /**
     * Delete a page and reassign its children to parent_id=0.
     *
     * @param int $id Page ID.
     * @return void
     */
    public static function delete(int $id): void {
        global $DB;

        // Reassign children to root.
        $DB->set_field(self::TABLE, 'parent_id', 0, ['parent_id' => $id]);

        // Delete associated files.
        $context = \context_system::instance();
        $fs = get_file_storage();
        $fs->delete_area_files($context->id, 'theme_nwverkehrserziehung', 'pagecontent', $id);

        // Delete the page.
        $DB->delete_records(self::TABLE, ['id' => $id]);

        // Invalidate navigation cache.
        self::invalidate_cache();
    }

    /**
     * Build the breadcrumb trail for a page.
     *
     * Returns an array of objects with 'title', 'url' from the root ancestor down to the given page.
     *
     * @param \stdClass $page The current page record.
     * @return array Array of breadcrumb items with 'title' and 'url'.
     */
    public static function get_breadcrumbs(\stdClass $page): array {
        global $DB;

        $crumbs = [];
        $current = $page;
        $maxdepth = 10; // Safety limit.

        while ($current && $maxdepth > 0) {
            array_unshift($crumbs, (object) [
                'title' => $current->nav_title ?: $current->title,
                'url' => new \moodle_url('/theme/nwverkehrserziehung/page.php', ['slug' => $current->slug]),
            ]);

            if (!empty($current->parent_id)) {
                $current = $DB->get_record(self::TABLE, ['id' => $current->parent_id]);
            } else {
                break;
            }
            $maxdepth--;
        }

        // Mark the last item as current (no link).
        if (!empty($crumbs)) {
            $last = end($crumbs);
            $last->current = true;
            $last->url = null;
        }

        return $crumbs;
    }

    /**
     * Build sidebar navigation for a section.
     *
     * Returns the page tree for the given section, with 'active' flags set
     * based on the current page ID.
     *
     * @param string $section Section name.
     * @param int $currentpageid Current page ID for highlighting.
     * @return array Navigation items with nested 'children'.
     */
    public static function get_section_nav(string $section, int $currentpageid = 0): array {
        global $DB;

        // Get all visible pages in this section.
        $pages = $DB->get_records(self::TABLE, ['section' => $section, 'visible' => 1], 'sortorder ASC, title ASC');

        // Build a tree.
        return self::build_tree($pages, 0, $currentpageid);
    }

    /**
     * Build the full navigation tree for all sections, suitable for the main navbar.
     *
     * Uses cache for performance.
     *
     * @return array Navigation tree grouped by section.
     */
    public static function get_nav_tree(): array {
        $cache = \cache::make(self::CACHE_COMPONENT, self::CACHE_AREA);
        $tree = $cache->get(self::CACHE_KEY_NAV);

        if ($tree !== false) {
            return $tree;
        }

        $tree = self::build_nav_tree();
        $cache->set(self::CACHE_KEY_NAV, $tree);

        return $tree;
    }

    /**
     * Build the navigation tree from the database.
     *
     * Returns an array of top-level items, each with a 'children' array.
     * Top-level items are root pages (parent_id=0), sorted by section then sortorder.
     *
     * @return array
     */
    private static function build_nav_tree(): array {
        global $DB;

        $allpages = $DB->get_records(self::TABLE, ['visible' => 1], 'section ASC, sortorder ASC, title ASC');

        // Group by section, only include root pages as top-level nav items.
        $sections = [];
        foreach ($allpages as $page) {
            if (empty($page->parent_id)) {
                $item = self::page_to_nav_item($page);
                $item['children'] = self::build_tree($allpages, $page->id);
                $item['haschildren'] = !empty($item['children']);
                $sections[] = $item;
            }
        }

        return $sections;
    }

    /**
     * Recursively build a tree from a flat list of pages.
     *
     * @param array $pages All pages (keyed by ID).
     * @param int $parentid Parent ID to start from.
     * @param int $currentpageid Current page ID for active state.
     * @return array
     */
    private static function build_tree(array $pages, int $parentid, int $currentpageid = 0): array {
        $tree = [];
        foreach ($pages as $page) {
            if ((int)$page->parent_id === $parentid) {
                $item = self::page_to_nav_item($page);
                $item['active'] = ((int)$page->id === $currentpageid);
                $item['children'] = self::build_tree($pages, (int)$page->id, $currentpageid);
                $item['haschildren'] = !empty($item['children']);

                // Mark as active if any child is active.
                if (!$item['active']) {
                    foreach ($item['children'] as $child) {
                        if (!empty($child['active'])) {
                            $item['active'] = true;
                            break;
                        }
                    }
                }

                $tree[] = $item;
            }
        }
        return $tree;
    }

    /**
     * Convert a page record to a navigation item array.
     *
     * @param \stdClass $page
     * @return array
     */
    private static function page_to_nav_item(\stdClass $page): array {
        return [
            'id' => (int)$page->id,
            'text' => $page->nav_title ?: $page->title,
            'title' => $page->title,
            'url' => (new \moodle_url('/theme/nwverkehrserziehung/page.php', ['slug' => $page->slug]))->out(false),
            'section' => $page->section,
            'slug' => $page->slug,
            'active' => false,
            'children' => [],
            'haschildren' => false,
        ];
    }

    /**
     * Invalidate the navigation cache.
     *
     * @return void
     */
    public static function invalidate_cache(): void {
        $cache = \cache::make(self::CACHE_COMPONENT, self::CACHE_AREA);
        $cache->delete(self::CACHE_KEY_NAV);
    }

    /**
     * Generate a slug from a title.
     *
     * @param string $title
     * @param string $parentslug Optional parent slug to prepend.
     * @return string
     */
    public static function generate_slug(string $title, string $parentslug = ''): string {
        // Transliterate common German characters.
        $slug = str_replace(
            ['ä', 'ö', 'ü', 'Ä', 'Ö', 'Ü', 'ß'],
            ['ae', 'oe', 'ue', 'ae', 'oe', 'ue', 'ss'],
            $title
        );
        // Lowercase, replace non-alphanumeric with hyphens, collapse multiple.
        $slug = \core_text::strtolower($slug);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');

        if (!empty($parentslug)) {
            $slug = $parentslug . '/' . $slug;
        }

        return $slug;
    }

    /**
     * Get options array for parent page select dropdown.
     *
     * @param int $excludeid Page ID to exclude (to prevent self-parenting).
     * @return array id => indented title
     */
    public static function get_parent_options(int $excludeid = 0): array {
        $allpages = self::get_all();
        $options = [0 => get_string('page_parent_root', 'theme_nwverkehrserziehung')];

        // Build flat indented list.
        self::add_parent_options($options, $allpages, 0, '', $excludeid);

        return $options;
    }

    /**
     * Recursively add pages to parent options list with indentation.
     *
     * @param array $options Options array (modified by reference).
     * @param array $allpages All pages.
     * @param int $parentid Current parent ID.
     * @param string $indent Indentation prefix.
     * @param int $excludeid Page to exclude.
     */
    private static function add_parent_options(array &$options, array $allpages, int $parentid, string $indent, int $excludeid): void {
        foreach ($allpages as $page) {
            if ((int)$page->parent_id === $parentid && (int)$page->id !== $excludeid) {
                $options[(int)$page->id] = $indent . $page->title;
                self::add_parent_options($options, $allpages, (int)$page->id, $indent . '— ', $excludeid);
            }
        }
    }
}

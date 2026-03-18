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

namespace theme_nwverkehrserziehung;

use cache;
use stdClass;

/**
 * Manager class for Praxisbörse data handling and caching.
 *
 * This class provides methods to retrieve, cache, and manage data from the
 * Praxisbörse data module activity.
 *
 * @package   theme_nwverkehrserziehung
 * @copyright 2024
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class praxisboerse_manager {
    /**
     * Cache key for praxisboerse data.
     */
    const CACHE_KEY = 'praxisboerse_data';

    /**
     * Cache lifetime in seconds (1 hour).
     */
    const CACHE_LIFETIME = 3600;

    /**
     * Configuration key for praxisboerse data activity ID.
     */
    const CONFIG_KEY = 'praxisboerse_dataid';

    /**
     * Theme name.
     */
    const THEME_NAME = 'theme_nwverkehrserziehung';

    /**
     * Retrieve Praxisbörse data from cache or database.
     *
     * This method returns cached data if available, otherwise it fetches
     * approved records from the database and caches them.
     *
     * @return array List of data entries formatted for display
     */
    public static function get_data() {
        global $CFG;

        require_once($CFG->dirroot . '/mod/data/lib.php');

        // Try to get data from cache first.
        // $cache = cache::make(self::THEME_NAME, 'praxisboerse');
        // $cacheddata = $cache->get(self::CACHE_KEY);

        // if ($cacheddata !== false) {
        // return $cacheddata;
        // }

        // Get data from database if not in cache.
        $items = self::get_records_from_database();

        // Store in cache.
        // $cache->set(self::CACHE_KEY, $items);

        return $items;
    }

    /**
     * Retrieve Praxisbörse records from the database.
     *
     * Gets approved records from the configured data activity module,
     * including all associated field content and user information.
     *
     * @return array List of formatted data entries
     */
    private static function get_records_from_database() {
        global $DB;

        $items = [];

        // Get the data activity ID from configuration.
        $dataid = self::get_data_activity_id();
        if (empty($dataid)) {
            return $items;
        }

        // Get the data activity instance.
        $data = self::get_data_instance($dataid);
        if (!$data) {
            return $items;
        }

        // Get all approved records.
        $records = self::get_approved_records($data->id);
        if (!$records) {
            return $items;
        }

        // Process each record.
        foreach ($records as $record) {
            $item = self::build_item_from_record($record, $dataid, $data->id);
            if ($item) {
                $items[] = $item;
            }
        }

        return $items;
    }

    /**
     * Get the configured data activity ID.
     *
     * @return int|null The data activity ID or null if not configured
     */
    private static function get_data_activity_id() {
        $cmid = get_config(self::THEME_NAME, self::CONFIG_KEY);
        return !empty($cmid) ? (int) $cmid : null;
    }

    /**
     * Get the data activity instance.
     *
     * @param int $dataid The data activity ID
     * @return stdClass|null The data instance or null if not found
     */
    private static function get_data_instance($dataid) {
        global $DB;

        try {
            $cm = get_coursemodule_from_id('data', $dataid, 0, false, MUST_EXIST);
            return $DB->get_record('data', ['id' => $cm->instance], '*', MUST_EXIST);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get all approved records for a data activity.
     *
     * @param int $dataid The data activity ID
     * @return array|false Array of records or false if none found
     */
    private static function get_approved_records($dataid) {
        global $DB;

        return $DB->get_records(
            'data_records',
            ['dataid' => $dataid, 'approved' => 1],
            'timecreated DESC'
        );
    }

    /**
     * Build a formatted item from a data record.
     *
     * Processes a single record including user information and field content.
     *
     * @param stdClass $record The data record
     * @param int $cmid The course module ID
     * @param int $dataid The data activity ID
     * @return stdClass|null The formatted item or null if processing fails
     */
    private static function build_item_from_record($record, $cmid = 0, $dataid = 0) {
        $item = new stdClass();
        $item->id = $record->id;
        $item->timecreated = userdate($record->timecreated);

        // Get user information.
        $user = self::get_user_record($record->userid);
        $item->username = $user ? fullname($user) : '';

        // Get and process field content.
        $contents = self::get_record_content($record->id);
        $allfields = self::process_content($contents, $cmid, $record->id, $record->dataid);

        // Separate image from other fields
        $item->image = null;
        $item->title = '';
        $item->description_fields = [];

        if (!empty($allfields)) {
            // Find the picture field
            $imagefield = null;
            $textfields = [];

            foreach ($allfields as $field) {
                if ($field['type'] === 'picture') {
                    $imagefield = $field;
                } else {
                    $textfields[] = $field;
                }
            }

            // Set image if found
            if ($imagefield) {
                $item->image = $imagefield['value'];
            }

            // First text field is the title
            if (!empty($textfields)) {
                $item->title = $textfields[0]['value'];
                // Remaining text fields are description - only include value and name
                if (count($textfields) > 1) {
                    $descfields = array_slice($textfields, 1);
                    foreach ($descfields as $field) {
                        $item->description_fields[] = [
                            'name' => $field['name'],
                            'value' => $field['value'],
                            'isauthor' => (strtolower($field['name']) === 'author') ? 1 : 0,
                        ];
                    }
                }
            }
        }

        // Add entry URL
        $item->entryurl = new \moodle_url('/theme/nwverkehrserziehung/pages/praxisboerse_entry.php', ['id' => $record->id]);

        // Extract filter values from field data (look for fields containing these keywords)
        $item->institution = self::extract_field_value($allfields, 'Netzwerkinstitutionen');
        $item->targetgroup = self::extract_field_value($allfields, 'Zielgruppe|target');
        $item->duration = self::extract_field_value($allfields, 'dauer|duration');
        $item->material = self::extract_field_value($allfields, 'material');

        // Keep fields for backward compatibility
        $item->fields = $allfields;

        return $item;
    }

    /**
     * Get user record by ID.
     *
     * @param int $userid The user ID
     * @return stdClass|null The user record or null if not found
     */
    private static function get_user_record($userid) {
        global $DB;

        return $DB->get_record('user', ['id' => $userid], 'firstname,lastname');
    }

    /**
     * Get all content for a record.
     *
     * @param int $recordid The record ID
     * @return array Array of content records
     */
    private static function get_record_content($recordid) {
        global $DB;

        return $DB->get_records('data_content', ['recordid' => $recordid]);
    }

    /**
     * Process content records and format for display, including empty fields.
     *
     * Retrieves field information for each content item and formats
     * it for template rendering. Handles special cases for file-based fields.
     * Now includes ALL fields from the data module, even if they're empty.
     *
     * @param array $contents Array of data_content records
     * @param int $cmid The course module ID for file URLs
     * @param int $recordid The record ID for file URLs
     * @param int $dataid The data module ID to fetch all fields
     * @return array Array of formatted field data
     */
    private static function process_content($contents, $cmid = 0, $recordid = 0, $dataid = 0) {
        global $CFG, $DB;
        $fields = [];
        $index = 0;

        // First, get all possible fields from the data module
        $alldatafields = [];
        if ($dataid) {
            $alldatafields = $DB->get_records('data_fields', ['dataid' => $dataid], 'id ASC');
        }

        // Create a map of content by fieldid for quick lookup
        $contentmap = [];
        foreach ($contents as $content) {
            $contentmap[$content->fieldid] = $content;
        }

        // Process all fields, whether they have content or not
        foreach ($alldatafields as $field) {
            $value = '';

            // Check if this field has content
            if (isset($contentmap[$field->id])) {
                $content = $contentmap[$field->id];
                $value = $content->content ?? '';

                // Handle file-based fields (picture, file).
                if (in_array($field->type, ['picture', 'file'])) {
                    if ($cmid && !empty($content->content)) {
                        // Use Moodle's file API to get the actual file (not thumb).
                        $file = self::get_field_file($cmid, $content->id, $content->content);
                        if ($file) {
                            if ($field->type === 'picture') {
                                // Render as image tag for picture fields.
                                $value = self::render_image_file($file);
                            } else {
                                // Render as link for file fields.
                                $value = self::render_file_link($file);
                            }
                        }
                    }
                } else {
                    // For text fields, rewrite plugin file URLs and return value for HTML rendering
                    $value = trim($value);
                    // Rewrite @@PLUGINFILE@@ to actual URLs
                    if (strpos($value, '@@PLUGINFILE@@') !== false && $cmid) {
                        try {
                            $context = \context_module::instance($cmid);
                            $value = str_replace(
                                '@@PLUGINFILE@@',
                                $CFG->wwwroot . '/pluginfile.php/' . $context->id . '/mod_data/content/' . $content->id,
                                $value
                            );
                        } catch (\Exception $e) {
                            // If context fails, just leave URLs as-is
                        }
                    }
                }
            }

            // Add field regardless of whether it has content
            $fields[] = [
                'name' => format_string($field->name),
                'value' => $value,
                'type' => $field->type,
                'index' => $index,
                'show_field' => $index >= 2, // Skip first (title) and second field
                'isauthor' => (strtolower($field->name) === 'author') ? 1 : 0,
            ];
            $index++;
        }

        return $fields;
    }

    /**
     * Get a single file by content ID and filename.
     *
     * Uses the same approach as Moodle's picture field class.
     *
     * @param int $cmid The course module ID
     * @param int $contentid The data_content record ID
     * @param string $filename The actual filename (from content->content)
     * @return object|null The stored_file object or null
     */
    private static function get_field_file($cmid, $contentid, $filename) {
        global $CFG;
        require_once($CFG->libdir . '/filelib.php');

        try {
            $context = \context_module::instance($cmid);
            $fs = get_file_storage();

            // Get the specific file, not the thumbnail
            $file = $fs->get_file($context->id, 'mod_data', 'content', $contentid, '/', $filename);
            return $file;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Render a single image file as HTML img tag.
     *
     * @param object $file The stored_file object
     * @return string HTML with image tag
     */
    private static function render_image_file($file) {
        $fileurl = \moodle_url::make_pluginfile_url(
            $file->get_contextid(),
            $file->get_component(),
            $file->get_filearea(),
            $file->get_itemid(),
            $file->get_filepath(),
            $file->get_filename()
        );
        return '<img src="' . $fileurl . '" class="img-fluid" alt="' . s($file->get_filename()) . '" />';
    }

    /**
     * Render a single file as a download link.
     *
     * @param object $file The stored_file object
     * @return string HTML with download link
     */
    private static function render_file_link($file) {
        $fileurl = \moodle_url::make_pluginfile_url(
            $file->get_contextid(),
            $file->get_component(),
            $file->get_filearea(),
            $file->get_itemid(),
            $file->get_filepath(),
            $file->get_filename()
        );
        return '<a href="' . $fileurl . '" class="btn btn-sm btn-primary">' .
               '<i class="fa fa-download"></i> ' . s($file->get_filename()) . '</a>';
    }

    /**
     * Get field record by ID.
     *
     * @param int $fieldid The field ID
     * @return stdClass|null The field record or null if not found
     */
    private static function get_field_record($fieldid) {
        global $DB;

        return $DB->get_record('data_fields', ['id' => $fieldid]);
    }

    /**
     * Invalidate the cache for praxisboerse data.
     *
     * Call this method when data has been updated to force a fresh
     * retrieval on the next request.
     *
     * @return void
     */
    public static function invalidate_cache() {
        $cache = cache::make(self::THEME_NAME, 'praxisboerse');
        $cache->delete(self::CACHE_KEY);
    }

    /**
     * Get a single Praxisbörse entry by record ID.
     *
     * @param int $recordid The record ID to retrieve
     * @return stdClass|null The formatted item or null if not found
     */
    public static function get_entry($recordid) {
        global $DB;

        // Get the data activity ID from configuration.
        $dataid = self::get_data_activity_id();
        if (empty($dataid)) {
            return null;
        }

        // Get the data activity instance.
        $data = self::get_data_instance($dataid);
        if (!$data) {
            return null;
        }

        // Get the specific record.
        $record = $DB->get_record('data_records', ['id' => $recordid, 'dataid' => $data->id, 'approved' => 1]);
        if (!$record) {
            return null;
        }

        // Build and return the formatted item.
        return self::build_item_from_record($record, $dataid, $data->id);
    }

    /**
     * Purge all cached data for this theme.
     *
     * @return void
     */
    public static function purge_cache() {
        $cache = cache::make(self::THEME_NAME, 'praxisboerse');
        $cache->purge();
    }

    /**
     * Extract field value by name pattern.
     *
     * Searches through all fields for a field name matching the given pattern
     * and returns its value.
     *
     * @param array $allfields Array of field data with 'name' and 'value' keys
     * @param string $pattern Regex pattern or pipe-separated field names
     * @return string The field value or empty string if not found
     */
    private static function extract_field_value($allfields, $pattern) {
        if (empty($allfields) || empty($pattern)) {
            return '';
        }

        // Convert pipe-separated pattern to individual patterns
        $patterns = array_map('trim', explode('|', $pattern));

        foreach ($allfields as $field) {
            $fieldname = strtolower($field['name'] ?? '');

            // Check each pattern
            foreach ($patterns as $p) {
                $plower = strtolower($p);
                if (strpos($fieldname, $plower) !== false) {
                    // Return the value, clean up if it's from a menu field
                    $value = $field['value'] ?? '';
                    // Clean up menu field content (remove HTML)
                    $value = strip_tags($value);
                    $value = trim($value);
                    return $value;
                }
            }
        }

        return '';
    }

    /**
     * Get unique filter values from all records.
     *
     * Retrieves all unique values for each filter field (institution, targetgroup, etc)
     * to populate dropdown menus.
     *
     * @return array Array with keys 'institutions', 'targetgroups', 'durations', 'materials'
     */
    public static function get_filter_values() {
        global $DB;

        $values = [
            'institutions' => [],
            'targetgroups' => [],
            'durations' => [],
            'materials' => [],
        ];

        // Get the course module ID from configuration.
        $cmid = self::get_data_activity_id();
        if (empty($cmid)) {
            return $values;
        }

        // Get the data instance
        try {
            $cm = get_coursemodule_from_id('data', $cmid, 0, false, MUST_EXIST);
            $dataid = $cm->instance;
        } catch (\Exception $e) {
            return $values;
        }

        // Get all approved records for this data activity
        $records = $DB->get_records('data_records', [
            'dataid' => $dataid,
            'approved' => 1,
        ]);

        if (empty($records)) {
            return $values;
        }

        // Get field IDs for filter fields
        $fields = $DB->get_records('data_fields', ['dataid' => $dataid], 'id ASC');
        $fieldmap = [];
        foreach ($fields as $f) {
            $fieldmap[strtolower($f->name)] = $f->id;
        }

        // Collect unique values for each filter field
        foreach ($records as $record) {
            // Get content for this record, indexed by field ID
            $allcontents = $DB->get_records('data_content', ['recordid' => $record->id]);
            $contents = [];
            foreach ($allcontents as $c) {
                $contents[$c->fieldid] = $c;
            }

            // Extract institution values (Netzwerkinstitutionen)
            if (isset($fieldmap['netzwerkinstitutionen'])) {
                $fid = $fieldmap['netzwerkinstitutionen'];
                if (isset($contents[$fid])) {
                    $val = trim($contents[$fid]->content);
                    if (!empty($val) && !in_array($val, $values['institutions'])) {
                        $values['institutions'][] = $val;
                    }
                }
            }

            // Extract targetgroup values (Zielgruppe)
            if (isset($fieldmap['zielgruppe'])) {
                $fid = $fieldmap['zielgruppe'];
                if (isset($contents[$fid])) {
                    $val = trim($contents[$fid]->content);
                    if (!empty($val) && !in_array($val, $values['targetgroups'])) {
                        $values['targetgroups'][] = $val;
                    }
                }
            }

            // Extract duration values (Duration/Dauer field if exists)
            if (isset($fieldmap['duration']) || isset($fieldmap['dauer'])) {
                $fid = $fieldmap['duration'] ?? $fieldmap['dauer'] ?? null;
                if ($fid && isset($contents[$fid])) {
                    $val = trim($contents[$fid]->content);
                    if (!empty($val) && !in_array($val, $values['durations'])) {
                        $values['durations'][] = $val;
                    }
                }
            }

            // Extract material values (Material field if exists)
            if (isset($fieldmap['material']) || isset($fieldmap['materialbedarf'])) {
                $fid = $fieldmap['material'] ?? $fieldmap['materialbedarf'] ?? null;
                if ($fid && isset($contents[$fid])) {
                    $val = trim($contents[$fid]->content);
                    if (!empty($val) && !in_array($val, $values['materials'])) {
                        $values['materials'][] = $val;
                    }
                }
            }
        }

        // Sort values alphabetically
        sort($values['institutions']);
        sort($values['targetgroups']);
        sort($values['durations']);
        sort($values['materials']);

        return $values;
    }
}

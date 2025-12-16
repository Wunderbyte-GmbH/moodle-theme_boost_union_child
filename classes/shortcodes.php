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
 * Shortcodes for local_wb_news
 *
 * @package local_wb_news
 * @subpackage db
 * @since Moodle 3.11
 * @copyright 2024 Georg Maißer
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace theme_boost_union_child;

use local_wb_video\table\video_table;
use local_wb_news\output\wb_news;
use local_wb_news\helper;
use stdClass;
use context_course;
use moodle_url;
use core_course\external\course_summary_exporter;
use Throwable;
use mod_booking\shortcodes_handler;
use mod_booking\booking;
use theme_boost_union_child\table\bcutable;
use cache_helper;
use context_module;
use context_system;
use Exception;
use html_writer;
use local_wunderbyte_table\filters\types\datepicker;
use local_wunderbyte_table\filters\types\intrange;
use local_wunderbyte_table\filters\types\standardfilter;
use local_wunderbyte_table\wunderbyte_table;
use mod_booking\form\dynamicdeputyselect;
use mod_booking\local\shortcode_filterfield;
use mod_booking\output\booked_users;
use mod_booking\customfield\booking_handler;
use mod_booking\local\modechecker;
use mod_booking\output\view;
use mod_booking\singleton_service;
use mod_booking\table\bulkoperations_table;
use mod_booking\output\renderer;
use theme_boost_union\util\course;
use mod_booking\shortcodes as booking_shortcodes;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/wb_news/lib.php');
require_once($CFG->dirroot . '/mod/booking/lib.php');

/**
 * Deals with local_shortcodes regarding booking.
 */
class shortcodes {
    /**
     * Prints out list of previous history items in a card..
     * Arguments can be 'userid'.
     *
     * @param string $shortcode
     * @param array $args
     * @param string|null $content
     * @param object $env
     * @param Closure $next
     * @return string
     */
    public static function wbnews($shortcode, $args, $content, $env, $next) {

        global $USER, $PAGE, $OUTPUT;

        // If the id argument was not passed on, we have a fallback in the connfig.

        if (!isset($args['instance'])) {
            $instance = 0;
        } else {
            $instance = (int)$args['instance'];
        }

        $news = new wb_news($instance);

        $data = $news->return_list();
        if (empty($data["instances"][0]["news"])) {
            $out = get_string('novalidinstance', 'local_wb_news', $instance);
        } else {
            $out = $OUTPUT->render_from_template('local_wb_news/wb_news_container', $data);
        }

        return $out;
    }

    public static function bcusummary($shortcode, $args, $content, $env, $next) {
        global $OUTPUT,$COURSE;

        require_login();

        $templatecontext = [];
        if (!empty($COURSE->id) && $COURSE->id != SITEID) {
            $summary = format_text($COURSE->summary, $COURSE->summaryformat, ['overflowdiv' => true]);
            $templatecontext['summary'] = $summary;
            $templatecontext['courseid'] = $COURSE->id;
            $templatecontext['progress'] = round(\core_completion\progress::get_course_progress_percentage($COURSE, $USER->id), 2);
            if ($templatecontext['progress'] != null) {
                $templatecontext['progress'] .= '%';
            }
            if (!empty($args['durata'])) {
                $templatecontext['durata'] = $args['durata'];
            }
            if (!empty($args['formato'])) {
                $templatecontext['formato'] = $args['formato'];
            }
            if (!empty($args['categoria'])) {
                $templatecontext['categoria'] = $args['categoria'];
            }
        } else {
            $templatecontext['summary'] = '';
        }

        if (!isset($args['instance'])) {
            $instance = 0;
        }
        $out = $OUTPUT->render_from_template('theme_boost_union_child/coursesummary', $templatecontext);;
        return $out;
    }

    /**
     * Prints out list of previous history items in a card..
     * Arguments can be 'userid'.
     *
     * @param string $shortcode
     * @param array $args
     * @param string|null $content
     * @param object $env
     * @param Closure $next
     * @return string
     */
    public static function mycourses($shortcode, $args, $content, $env, $next) {
        global $USER, $OUTPUT;

        require_login();

        $onlyactive = true;
        $fields = 'id,shortname,fullname,summary,summaryformat,category,visible,startdate,enddate';
        $sort = 'fullname ASC';
        $courses = enrol_get_users_courses($USER->id, $onlyactive, $fields, $sort);

        if (empty($courses)) {
            return get_string('nocourses', 'moodle');
        }

        $fs = get_file_storage();
        $items = [];

        foreach ($courses as $c) {
            $context = context_course::instance($c->id);
            $courseimage = course_summary_exporter::get_course_image($c);

            if (!$courseimage) {
                $courseimage = $OUTPUT->image_url('course/default', 'theme')->out(true);
            }
            $catname = '';
            if (!empty($c->category)) {
                $cat = \core_course_category::get($c->category, IGNORE_MISSING);
                if ($cat) {
                    $catname = $cat->get_formatted_name();
                }
            }

            $items[] = [
                'id'            => $c->id,
                'coursename'      => $c->fullname,
                'summary'       => format_text($c->summary ?? '', $c->summaryformat ?? FORMAT_HTML, ['context' => $context]),
                'viewurl'       => (new moodle_url('/course/view.php', ['id' => $c->id]))->out(false),
                'courseimage'   => $courseimage,
                'categoryname'  => $catname,
                'visible'       => (int)$c->visible,
                'hasprogress'   => false,
                'progress'      => 0,
                'showshortname' => true,
            ];
        }

        $out = '';
        foreach ($items as $item) {
            $out .= $OUTPUT->render_from_template('core_course/coursecards', ['courses' => $items]);
        }

        return $out;
    }

    public static function top3videos($shortcode, $args, $content, $env, $next) {
        global $DB, $OUTPUT;

        require_login();
        $vt = new video_table('top3videos');
        // Letzte 3 Videos holen
        $records = $DB->get_records(
            'local_wb_video',
            [],
            'timecreated DESC',
            'id,title,description,filepath,thumbnail,categoryid,userid,timecreated,timemodified',
            0,
            3
        );

        if (!$records) {
            return get_string('nothingtodisplay', 'moodle');
        }

        $videos = [];
        foreach ($records as $r) {
            // Wenn filepath z. B. eine YouTube-ID ist
            $youtubeid = trim($r->filepath);
            // Wenn kein eigenes Thumbnail gespeichert ist → YouTube Standardbild
            $video = $vt->col_video($r);


            // Kategoriebezeichnung holen (optional)
            $catname = '';
            if (!empty($r->categoryid)) {
                if ($cat = $DB->get_record('local_wb_video_category', ['id' => $r->categoryid], 'id,name')) {
                    $catname = format_string($cat->name);
                }
            }

            $videos[] = [
                'id'          => (int)$r->id,
                'youtubeid'   => $youtubeid,
                'video'   => $video,
                'title'       => format_string($r->title ?? ''),
                'description' => format_text($r->description ?? '', FORMAT_HTML),
                'displaydate' => userdate($r->timecreated, get_string('strftimedate', 'langconfig')),
                'addedago'    => format_time(time() - $r->timecreated),
                'category'    => $catname,
            ];
        }

        $data = [
            'title'      => 'Video per conoscere MIA',
            'videos'     => $videos,
            'viewallurl' => new moodle_url('/local/wb_video/index.php'),
        ];

        return $OUTPUT->render_from_template('theme_boost_union_child/top3videos', $data);
    }

    public static function bcunewnews($shortcode, $args, $content, $env, $next) {
        global $USER, $PAGE, $OUTPUT;

        // If the id argument was not passed on, we have a fallback in the connfig.

        if (!isset($args['instance'])) {
            $instance = 0;
        } else {
            $instance = (int)$args['instance'];
        }

        $news = new wb_news($instance);

        $data = $news->return_list();

        foreach ($data['instances'] as &$instance) {
            if (!empty($instance['news'])) {
                $instance['news'] = array_slice($instance['news'], 0, 2);
            }
        }
        unset($instance);
        $data['editmode'] = false;
        if (empty($data["instances"][0]["news"])) {
            $out = get_string('novalidinstance', 'local_wb_news', $instance);
        } else {
            $out = $OUTPUT->render_from_template('local_wb_news/wb_news_container', $data);
        }

        return $out;
    }

    public static function bcuusername($shortcode, $args, $content, $env, $next) {
        global $DB, $USER;

        require_login();

        return fullname($USER);
    }

    public static function bcuevents($shortcode, $args, $content, $env, $next) {
        global $OUTPUT;

        require_login();

        $args['events'] = 1;
        [$coursehtml, $count] = self::get_my_courselistdata($shortcode, $args, $content, $env, $next);
        if ($courses &&  $count > 1) {
            $count = $count . ' ' . get_string('courses', 'moodle');
        } else {
            $count = $count . ' ' . get_string('course', 'moodle');
        }
        if (!empty($args['notitle'])) {
            $title = '';
            $count = '';
        }
        $templatecontext = [
            'title' => $title,
            'count' => $count,
            'courses' => $coursehtml,
        ];
        $out = $OUTPUT->render_from_template('theme_boost_union_child/mycourses', $templatecontext);

        return $out;
    }

    public static function bcumycourses($shortcode, $args, $content, $env, $next) {
        global $USER, $OUTPUT;

        require_login();

        $onlyactive = true;
        $fields = 'id,shortname,fullname,summary,summaryformat,category,visible,startdate,enddate';
        $sort = 'fullname ASC';
        $courses = enrol_get_users_courses($USER->id, $onlyactive, $fields, $sort);

        if (empty($courses)) {
            return get_string('nocourses', 'moodle');
        }

        $fs = get_file_storage();
        $items = [];

        foreach ($courses as $c) {
            $context = context_course::instance($c->id);
            $courseimage = course_summary_exporter::get_course_image($c);

            if (!$courseimage) {
                $courseimage = $OUTPUT->get_generated_image_for_id($data->id);
            }
            $catname = '';
            if (!empty($c->category)) {
                $cat = \core_course_category::get($c->category, IGNORE_MISSING);
                if ($cat) {
                    $catname = $cat->get_formatted_name();
                }
            }

            $items[] = [
                'id'            => $c->id,
                'coursename'    => $c->fullname,
                'summary'       => format_text($c->summary ?? '', $c->summaryformat ?? FORMAT_HTML, ['context' => $context]),
                'viewurl'       => (new moodle_url('/course/view.php', ['id' => $c->id]))->out(false),
                'courseimage'   => $courseimage,
                'categoryname'  => $catname,
                'visible'       => (int)$c->visible,
                'hasprogress'   => false,
                'progress'      => 0,
                'showshortname' => true,
            ];
        }

        $out = '';
        $out .= $OUTPUT->render_from_template('theme_boost_union_child/coursecardhorizontal', ['courses' => $items]);

        return $out;
    }

    public static function bcusubito($shortcode, $args, $content, $env, $next) {
        global $OUTPUT;

        require_login();

        $args['futureonly'] = 1;
        [$coursehtml, $count] = self::get_my_courselistdata($shortcode, $args, $content, $env, $next);
        if ($courses &&  $count > 1) {
            $count = $count . ' ' . get_string('courses', 'moodle');
        } else {
            $count = $count . ' ' . get_string('course', 'moodle');
        }

        $templatecontext = [
            'title' => 'Inizia subito',
            'count' => $count,
            'courses' => $coursehtml,
        ];
        $out = $OUTPUT->render_from_template('theme_boost_union_child/mycourses', $templatecontext);

        return $out;
    }

    public static function get_my_courselistdata($shortcode, $args, $content, $env, $next) {
        global $USER, $PAGE, $CFG;

        // Get rid of quotation marks.
        booking_shortcodes::fix_args($args);

        $requiredargs = [];
        $error = shortcodes_handler::validatecondition($shortcode, $args, true, $requiredargs);
        if ($error['error'] === 1) {
            return $error['message'];
        }

        if (isset($args['userid']) && !empty($args['userid'])) {
            $userid = $args['userid'];
        } else {
            $userid = $USER->id;
        }
        $wherearray = [];
        $course = $PAGE->course;
        $perpage = booking_shortcodes::check_perpage($args);
        $pageurl = $course->shortname . $PAGE->url->out();
        $perpage = booking_shortcodes::check_perpage($args);

        if (!empty($args['cmid'])) {
            $booking = singleton_service::get_instance_of_booking_settings_by_cmid((int)$args['cmid']);
            $wherearray['bookingid'] = (int)$booking->id;
        }

        $viewparam = booking_shortcodes::get_viewparam($args);
        $tablename = ($userid . 'mycourses');
        $table = new bcutable($tablename);

        // Additional where condition for both card and list views.
        $additionalwhere = booking_shortcodes::set_customfield_wherearray($args, $wherearray) ?? '';

        if (isset($args['completed'])) {
            $wherearray['completed'] = (int)$args['completed'];
        }

        $statusarray = [MOD_BOOKING_STATUSPARAM_BOOKED];
        if (!empty($args['statuswaitinglist'])) {
            $statusarray[] = MOD_BOOKING_STATUSPARAM_WAITINGLIST;
        }

        $possibleoptions = [
            "description",
            "statusdescription",
            "attachment",
            "teacher",
            "responsiblecontact",
            "showdates",
            "dayofweektime",
            "location",
            "institution",
            "minanswers",
            "bookingopeningtime",
            "bookingclosingtime",
            "coursestarttime",
            "booknow",
        ];
        // When calling recommendedin in the frontend we can define exclude params to set options, we don't want to display.

        if (!empty($args['exclude'])) {
            $exclude = explode(',', $args['exclude']);
            $optionsfields = array_diff($possibleoptions, $exclude);
        } else {
            $optionsfields = $possibleoptions;
        }
        $showfilter = false;
        $showsort = false;
        $showsearch = false;

        view::apply_standard_params_for_bookingtable(
            $table,
            $optionsfields,
            false,
            false,
            $showsort,
            false,
            1,
            MOD_BOOKING_VIEW_PARAM_CARDS,
            0,
            $args
        );


        if (isset($args['horizontal'])) {
            $table->tabletemplate = 'local_wunderbyte_table/table_horizontal_cards';
        }

        if (isset($args['events'])) {
            $table->tabletemplate = 'local_wunderbyte_table/events_card';
            $table->add_subcolumns('zoom', ['zoom']);
            $table->add_subcolumns('starting', ['starting']);
        }

        $table->add_subcolumns('title', ['text']);
        $table->add_subcolumns('progress', ['progress']);
        // Possibility to add customfieldfilter.
        $customfieldfilter = explode(',', ($args['customfieldfilter'] ?? ''));
        if (!empty($customfieldfilter)) {
            booking_shortcodes::apply_customfieldfilter($table, $customfieldfilter);
        }

        $table->showcountlabel = false;
        $table->showfilterontop = false;

        // Set common table options requirelogin, sortorder, sortby.

        [$fields, $from, $where, $params, $filter] =
                booking::get_options_filter_sql(
                    0,
                    0,
                    '',
                    null,
                    null,
                    [],
                    $wherearray,
                    $userid,
                    $statusarray,
                    $additionalwhere,
                    '',
                    $table
                );

        if (!empty($args['futureonly'])) {
            $startoftoday = strtotime('today 01:00:00');
            $where .= " AND (coursestarttime > $startoftoday OR coursestarttime IS NULL)";
        }
        if (!empty($args['current'])) {
            $startoftoday = strtotime('today 01:00:00');
            $endoftoday = strtotime('today 23:59:59');
            $where .= " AND (coursestarttime < $startoftoday OR coursestarttime IS NULL) AND (courseendtime > $startoftoday OR courseendtime IS NULL)";
        }

        if (!empty($args['events'])) {
            $time = time();
            $where .= " AND (courseendtime > $time)";
        }

        $table->set_filter_sql($fields, $from, $where, $filter, $params);

        $table->define_cache('mod_booking', 'mybookingoptionstable');

        try {
            $out = $table->outhtml($perpage, true);
        } catch (Throwable $e) {
            $out = get_string('shortcode:error', 'mod_booking');

            if ($CFG->debug > 0 && has_capability('moodle/site:config', context_system::instance())) {
                $out .= $e->getMessage();
            }
        }

        return [$out, count($table->rawdata), $table->rawdata];

    }

    public static function bcuseguire($shortcode, $args, $content, $env, $next) {
        global $OUTPUT, $DB;

        require_login();
        $args['horizontal'] = true;
        [$coursehtml, $count, $rawdata] = self::get_my_courselistdata($shortcode, $args, $content, $env, $next);
        if ($count <= 1) {
            $count = $count . ' ' . get_string('activity', 'moodle');
        } else {
            $count = $count . ' ' . get_string('activities', 'moodle');
        }

        $title = $args['title'] ?? 'Continua a seguire';
        if (!empty($args['notitle'])) {
            $title = '';
            $count = '';
        }
        $templatecontext = [
            'title' => $title,
            'count' => $count,
            'courses' => $coursehtml,
            'horizontal' => true,
        ];

        $out = $OUTPUT->render_from_template('theme_boost_union_child/mycourses', $templatecontext);
        return $out;
    }

    public static function bcunuovo($shortcode, $args, $content, $env, $next) {
        global $OUTPUT;
        $args['completed'] = 1;

        [$coursehtml, $count] = self::get_my_courselistdata($shortcode, $args, $content, $env, $next);
        if ($count <= 1) {
            $count = $count . ' ' . get_string('activity', 'moodle');
        } else {
            $count = $count . ' ' . get_string('activities', 'moodle');
        }
        $templatecontext = [
            'title' => 'Già visti, se vuoi seguirli di nuovo',
            'count' => $count,
            'courses' => $coursehtml,
        ];
        $out = $OUTPUT->render_from_template('theme_boost_union_child/mycourses', $templatecontext);
        return $out;
    }

    public static function bcucourseprogress($shortcode, $args, $content, $env, $next) {
        global $USER;
        if ($args['courseid']) {
            $completion = \core_completion\progress::get_course_progress_percentage(get_course($args['courseid']), $USER->id);
            return ($completion === null) ? '' : '| ' . $completion . '% completado';
        }
    }
}

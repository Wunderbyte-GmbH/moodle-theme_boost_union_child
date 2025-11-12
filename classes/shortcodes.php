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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/wb_news/lib.php');

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
        global $DB, $OUTPUT;

        require_login();
        
        $data = [
            'image' => 'https://agenastest.wunderbyte.at/pluginfile.php/36/course/overviewfiles/risorsa71.jpg',
            'category' => 'Aggiornamento',
            'date' => '10/11/2025',
            'title' => 'Nuove funzionalità disponibili nella piattaforma LMS',
            'text' => 'Abbiamo introdotto nuove funzionalità per migliorare la tua esperienza di formazione online. Ora puoi accedere più facilmente ai contenuti e monitorare i tuoi progressi.'
        ]; 
        $news = [
            'news' => [ $data, $data ]
        ];

        return $OUTPUT->render_from_template('theme_boost_union_child/newscards', $news);
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
            $out .= $OUTPUT->render_from_template('theme_boost_union_child/coursecardhorizontal', ['courses' => $items]);
        }

        return $out;
    }
}

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
 * A drawer based layout for the boost theme.
 *
 * @package   theme_aulas_toribio
 * @copyright 2021 Bas Brands
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
global $CFG,$DB;

require_once($CFG->libdir . '/behat/lib.php');
require_once($CFG->dirroot . '/course/lib.php');

// Add block button in editing mode.
$addblockbutton = $OUTPUT->addblockbutton();

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
user_preference_allow_ajax_update('drawer-open-index', PARAM_BOOL);
user_preference_allow_ajax_update('drawer-open-block', PARAM_BOOL);

if (isloggedin()) {
    $courseindexopen = (get_user_preferences('drawer-open-index', true) == true);
    $blockdraweropen = (get_user_preferences('drawer-open-block') == true);
} else {
    $courseindexopen = false;
    $blockdraweropen = false;
}

if (defined('BEHAT_SITE_RUNNING')) {
    $blockdraweropen = true;
}

$extraclasses = ['uses-drawers'];
if ($courseindexopen) {
    $extraclasses[] = 'drawer-open-index';
}

$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = (strpos($blockshtml, 'data-block=') !== false || !empty($addblockbutton));
if (!$hasblocks) {
    $blockdraweropen = false;
}
$courseindex = core_course_drawer();
if (!$courseindex) {
    $courseindexopen = false;
}

$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$forceblockdraweropen = $OUTPUT->firstview_fakeblocks();

$secondarynavigation = false;
$overflow = '';
if ($PAGE->has_secondary_navigation()) {
    $tablistnav = $PAGE->has_tablist_secondary_navigation();
    $moremenu = new \core\navigation\output\more_menu($PAGE->secondarynav, 'nav-tabs', true, $tablistnav);
    $secondarynavigation = $moremenu->export_for_template($OUTPUT);
    $overflowdata = $PAGE->secondarynav->get_overflow_menu_data();
    if (!is_null($overflowdata)) {
        $overflow = $overflowdata->export_for_template($OUTPUT);
    }
}

$primary = new core\navigation\output\primary($PAGE);
$renderer = $PAGE->get_renderer('core');
$primarymenu = $primary->export_for_template($renderer);
$buildregionmainsettings = !$PAGE->include_region_main_settings_in_header_actions() && !$PAGE->has_secondary_navigation();
// If the settings menu will be included in the header then don't add it here.
$regionmainsettingsmenu = $buildregionmainsettings ? $OUTPUT->region_main_settings_menu() : false;

$header = $PAGE->activityheader;
$headercontent = $header->export_for_template($renderer);




$courseid = $PAGE->course->id;
$course = $DB->get_record('course', array('id' => $courseid));


$courselist = new core_course_list_element($course);
$contentimages = $contentfiles = '';
foreach ($courselist->get_course_overviewfiles() as $file) {
    $isimage = $file->is_valid_image();
    $url = moodle_url::make_file_url("$CFG->wwwroot/pluginfile.php",
        '/' . $file->get_contextid() . '/' . $file->get_component() . '/' .
        $file->get_filearea() . $file->get_filepath() . $file->get_filename(), !$isimage);
    
    if ($isimage) {
        // $contentimages .= html_writer::tag('div',
        //     html_writer::empty_tag('img', ['src' => $url]),
        //     ['class' => 'courseimage']);
        $contentimages .= $url;
    } else {
        $image = $this->output->pix_icon(file_file_icon($file, 24), $file->get_filename(), 'moodle');
        $filename = html_writer::tag('span', $image, ['class' => 'fp-icon']).
            html_writer::tag('span', $file->get_filename(), ['class' => 'fp-filename']);
        // $contentfiles .= html_writer::tag('span',
        //     html_writer::link($url, $filename),
        //     ['class' => 'coursefile fp-filename-icon']);
        $contentfiles .= $url;
    }
}

$imagecourse = $contentimages . $contentfiles;
$iscourse = '';
$inACourse = false;
$iscourseinternal = '';
if($courseid != 1){
    $iscourse = 'aulas_toribio-course-bg-image';
    $inACourse = true;
    $iscourseinternal = 'taletum-course-internal';
}else{
    $iscourse = 'aulas_toribio-no-course-bg-image';
}

$img_background_footer = $OUTPUT->image_url('backgorund-footer', 'theme');
$img = $OUTPUT->image_url('logos-footer', 'theme');
$img_course = $OUTPUT->image_url('banner-cursos', 'theme');

$urltocourse = $CFG->wwwroot.'/course/view.php?id='.$courseid;

/**
 * Accesibilidad
 */
$access_contrast = $OUTPUT->image_url('accesibility/contraste', 'theme');
$access_gray = $OUTPUT->image_url('accesibility/grises', 'theme');
$access_font_increase = $OUTPUT->image_url('accesibility/font-increase', 'theme');
$access_font_normal = $OUTPUT->image_url('accesibility/font-normal', 'theme');
$access_font_decrease = $OUTPUT->image_url('accesibility/font-decrease', 'theme');
$access_button =  $OUTPUT->image_url('accesibility/body_wh', 'theme');
$access_line_height = $OUTPUT->image_url('accesibility/line-height', 'theme'); 


$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'courseindexopen' => $courseindexopen,
    'blockdraweropen' => $blockdraweropen,
    'courseindex' => $courseindex,
    'primarymoremenu' => $primarymenu['moremenu'],
    'secondarymoremenu' => $secondarynavigation ?: false,
    'mobileprimarynav' => $primarymenu['mobileprimarynav'],
    'usermenu' => $primarymenu['user'],
    'langmenu' => $primarymenu['lang'],
    'forceblockdraweropen' => $forceblockdraweropen,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    'overflow' => $overflow,
    'headercontent' => $headercontent,
    'addblockbutton' => $addblockbutton,
    'imagecourse'=> $imagecourse,
    'iscourse'=> $iscourse,
    'inacourse'=> $inACourse,
    'iscourseinternal'=> $iscourseinternal,
    'img'=> $img,
    'img_banner'=> $img_course,
    'courseurl'=> $urltocourse,
    'bk_footer' => $img_background_footer,
    'contraste' => $access_contrast,
    'grises' => $access_gray,
    'font-increase' => $access_font_increase,
    'font-normal' => $access_font_normal,
    'font-decrease' => $access_font_decrease,
    'bg_button' =>  $access_button,
    'line-height' => $access_line_height 
];

echo $OUTPUT->render_from_template('theme_aulas_toribio/drawers', $templatecontext);

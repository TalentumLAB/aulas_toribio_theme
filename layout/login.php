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

defined('MOODLE_INTERNAL') || die();
global $CFG;
/**
 * A login page layout for the boost theme.
 *
 * @package   theme_aulas_toribio
 * @copyright 2016 Damyon Wiese
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$bodyattributes = $OUTPUT->body_attributes();
$img = $OUTPUT->image_url('logo-aulas_toribio', 'theme');
$img_login_sup = $OUTPUT->image_url('login-sol-superior', 'theme');
$img_login_inf = $OUTPUT->image_url('login-robot-inferior', 'theme');
$img_login_inf_atomo = $OUTPUT->image_url('login-admin-atomo-inferior', 'theme');
$img_footer = $OUTPUT->image_url('logos-footer', 'theme');
$logo_admin = $OUTPUT->image_url('logo-admin', 'theme');
$logo_help = $OUTPUT->image_url('logo-help', 'theme');

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'bodyattributes' => $bodyattributes,
    'img_logo' => $img,
    'img_superior'=> $img_login_sup,
    'img_inferior'=> $img_login_inf,
    'img_inferior_admin'=> $img_login_inf_atomo,
    'img_footer' => $img_footer,
    'logo_admin' => $logo_admin,
    'logo_help'  => $logo_help
];

echo $OUTPUT->render_from_template('theme_aulas_toribio/login', $templatecontext);


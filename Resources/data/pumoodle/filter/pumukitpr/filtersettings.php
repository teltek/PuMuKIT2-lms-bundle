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
 * Administration settings definitions for the pumukitpr filter.
 * Settings can be accessed from:
 * Site Administration block -> Plugins -> Filters -> Pumukitpr filter
 * This form stores general settings into the site wide $CFG object.
 *
 * @copyright  2016
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

if (!class_exists('admin_setting_configtext_sizecss')) {
    class admin_setting_configtext_sizecss extends admin_setting_configtext
    {
        public function validate($data)
        {
            if ('auto' == $data) {
                return true;
            }
            //Format: Number ending on 'px', 'em', or '%'
            if (preg_match('/^\d*(px|em|%)/', $data)) {
                return true;
            }

            return get_string('css_notvalid', 'filter_pumukitpr');
        }
    }
}

if ($ADMIN->fulltree) {
    $settings->add(
        new admin_setting_configtext(
            'filter_pumukitpr_secret',
            get_string('secret', 'filter_pumukitpr'),
            get_string('secret_description', 'filter_pumukitpr'),
            'This is a PuMoodle secret!!',
            PARAM_NOTAGS
        )
    );

    $settings->add(new admin_setting_configtext_sizecss(
        'iframe_singlevideo_width',
        get_string('iframe_singlevideo_width', 'filter_pumukitpr'),
        '',
        '592px',
        PARAM_INT
    ));
    $settings->add(new admin_setting_configtext_sizecss(
        'iframe_singlevideo_height',
        get_string('iframe_singlevideo_height', 'filter_pumukitpr'),
        '',
        '333px',
        PARAM_INT
    ));

    $settings->add(new admin_setting_configtext_sizecss(
        'iframe_multivideo_width',
        get_string('iframe_multivideo_width', 'filter_pumukitpr'),
        '',
        '592px',
        PARAM_INT
    ));
    $settings->add(new admin_setting_configtext_sizecss(
        'iframe_multivideo_height',
        get_string('iframe_multivideo_height', 'filter_pumukitpr'),
        '',
        '333px',
        PARAM_INT
    ));

    $settings->add(new admin_setting_configtext_sizecss(
        'pumukit_filter_domain',
        get_string('pumukit_filter_domain', 'filter_pumukitpr'),
        '',
        '333px',
        PARAM_INT
    ));
}

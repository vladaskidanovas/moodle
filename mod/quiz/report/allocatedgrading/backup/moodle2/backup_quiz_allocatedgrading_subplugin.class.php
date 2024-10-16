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
 * Backup instructions for the quiz allocated grading report sub-plugin.
 *
 * @package    quiz_allocatedgrading
 * @author     Vlad Kidanov <vlad.kidanov@catalyst-eu.net>
 * @copyright  Catalyst IT, 2024
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Backup instructions for the quiz allocated grading report sub-plugin.
 *
 * @package    quiz_allocatedgrading
 * @author     Vlad Kidanov <vlad.kidanov@catalyst-eu.net>
 * @copyright  Catalyst IT, 2024
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_quiz_allocatedgrading_subplugin extends backup_subplugin {

    /**
     * Stores the data related to the quiz allocated grading report for a particular quiz.
     *
     * @return backup_subplugin_element
     */
    protected function define_quiz_subplugin_structure(): backup_subplugin_element {
        // To know if we are including userinfo.
        $userinfo = $this->get_setting_value('userinfo');

        // Create XML elements.
        $subplugin = $this->get_subplugin_element();
        $subpluginwrapper = new backup_nested_element($this->get_recommended_name());
        $subpluginelement = new backup_nested_element('quiz_allocatedgrading',
            null, ['id', 'quizid', 'slotid', 'userid', 'timecreated', 'allocatedby']);

        // Connect XML elements into the tree.
        $subplugin->add_child($subpluginwrapper);
        $subpluginwrapper->add_child($subpluginelement);

        // Set source to populate the data.
        if ($userinfo) {
            $subpluginelement->set_source_table('quiz_allocatedgrading', ['quizid' => backup::VAR_ACTIVITYID]);
        }

        // The parent is the submission.
        $subpluginelement->annotate_files('quiz_allocatedgrading', 'into', null);

        return $subplugin;
    }
}

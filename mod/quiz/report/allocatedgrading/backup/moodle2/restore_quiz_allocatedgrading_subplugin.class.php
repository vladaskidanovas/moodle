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
 * This file contains the class for restore of this submission plugin.
 *
 * @package    quiz_allocatedgrading
 * @author     Vlad Kidanov <vlad.kidanov@catalyst-eu.net>
 * @copyright  Catalyst IT, 2024
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Restore subplugin class.
 *
 * Provides the necessary information needed to restore one quiz_allocatedgrading subplugin.
 *
 * @package    quiz_allocatedgrading
 * @author     Vlad Kidanov <vlad.kidanov@catalyst-eu.net>
 * @copyright  Catalyst IT, 2024
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_quiz_allocatedgrading_subplugin extends restore_subplugin {

    /**
     * Returns the paths to be handled by the subplugin.
     *
     * @return array
     */
    protected function define_quiz_subplugin_structure(): array {
        $paths = [];
        $userinfo = $this->get_setting_value('userinfo');

        $elename = $this->get_namefor('quiz');
        $elepath = $this->get_pathfor('/quiz_allocatedgrading');
        // We used get_recommended_name() so this works.
        if ($userinfo) {
            $paths[] = new restore_path_element($elename, $elepath);
        }

        return $paths;
    }

    /**
     * Processes quiz allocated grading element.
     *
     * @param mixed $data quiz allocated grading report element data.
     * @return void
     */
    public function process_quiz_allocatedgrading_quiz(mixed $data): ?array {
        global $DB;

        $data = (object) $data;
        unset($data->id);
        $data->quizid = $this->get_new_parentid('quiz'); // Update quizid with new reference.
        $data->timecreated = time();

        $DB->insert_record('quiz_allocatedgrading', $data);

        // Add url related files.
        $this->add_related_files('quiz_allocatedgrading', 'intro', null);
    }
}

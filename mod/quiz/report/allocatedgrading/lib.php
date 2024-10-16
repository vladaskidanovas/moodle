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
 * Callback for inplace editable.
 *
 * @package    quiz_allocatedgrading
 * @author     Vlad Kidanov <vlad.kidanov@catalyst-eu.net>
 * @copyright  Catalyst IT, 2024
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Callback for inplace editable API.
 *
 * @param string $itemtype - Only user_allocatedgrader is supported.
 * @param string $itemid - quizid and question slot separated by a :
 * @param string $newvalue - json encoded list of allocated users.
 * @return \core\output\inplace_editable|null
 */
function quiz_allocatedgrading_inplace_editable(string $itemtype, string $itemid, string $newvalue) {
    if ($itemtype === 'user_allocatedgrader') {
        return \quiz_allocatedgrading\output\user_allocatedgrading_editable::update($itemid, $newvalue);
    }
}

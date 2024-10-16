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

namespace quiz_allocatedgrading\output;

use core\output\inplace_editable;
use core_external\external_api;

/**
 * Class to display list of user for question grading allocation.
 *
 * @package    quiz_allocatedgrading
 * @author     Vlad Kidanov <vlad.kidanov@catalyst-eu.net>
 * @copyright  Catalyst IT, 2024
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class user_allocatedgrading_editable extends inplace_editable {

    /** @var $context */
    private $context = null;

    /** @var array $enrolledusers */
    private array $enrolledusers;

    /** @var array $allocatedusers */

    /**
     * Description.
     *
     * @param int $quizid quiz id.
     * @param int $questionslotid question slot.
     * @param array $allocatedusers assigned user to grade a questions.
     * @param array $enrolledusers enrolled course or grouping user to grade a questions.
     * @param \context $context
     * @throws \coding_exception
     */
    public function __construct(int $quizid, int $questionslotid, array $allocatedusers, array $enrolledusers, \context $context) {
        // Create an itemid by concatenating quizid and questionslotid.
        $itemid = $quizid . ':' . $questionslotid;

        // Check if the current user has the capability to assign graders.
        $editable = has_capability('quiz/allocatedgrading:assigngrader', $context);

        // Encode the keys of allocated users as JSON.
        $ids = json_encode($allocatedusers);

        // Assign values to class properties.
        $this->context = $context;
        $this->enrolledusers = $enrolledusers;

        parent::__construct(
            component: 'quiz_allocatedgrading',
            itemtype: 'user_allocatedgrader',
            itemid: $itemid,
            editable: $editable,
            displayvalue: $ids,
            value: $ids,
            edithint: get_string('edithint', 'quiz_allocatedgrading'),
            editlabel: get_string('editlabel', 'quiz_allocatedgrading')
        );

        $attributes = ['multiple' => true];
        $this->set_type_autocomplete($enrolledusers, $attributes);
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return array
     */
    public function export_for_template(\renderer_base $output): array {
        $listofgraders = [];
        $assignedgraderids = json_decode($this->value);

        foreach ($assignedgraderids as $userid) {
            $listofgraders[] = format_string($this->enrolledusers[$userid], true, ['context' => $this->context]);
        }

        if (!empty($listofgraders)) {
            $this->displayvalue = implode(', ', $listofgraders);
        } else {
            $this->displayvalue = get_string('nograderassigned', 'quiz_allocatedgrading');
        }
        return parent::export_for_template($output);
    }

    /**
     * Updates the value in database and returns itself, called from inplace_editable callback.
     *
     * @param string $itemid quiz question itemid.
     * @param string $newvalue assigned user IDs.
     * @return \self
     */
    public static function update(string $itemid, string $newvalue) {
        global $DB, $USER;

        // Split the itemid into quiz ID and question slot ID.
        list($quizid, $questionslotid) = explode(':', $itemid, 2);

        $quizid = clean_param($quizid, PARAM_INT);
        $questionslotid = clean_param($questionslotid, PARAM_INT);
        $userids = json_decode($newvalue);

        // Get and validate the context.
        [, $cm] = \get_course_and_cm_from_instance($quizid, 'quiz');
        $context = \context_module::instance($cm->id);
        external_api::validate_context($context);

        // Check if the current user has the capability to assign graders.
        require_capability('quiz/allocatedgrading:assigngrader', $context);

        // Get group IDs if there are any, we only need to assign graders from the group(s).
        $groupids = [];
        if ($groups = $DB->get_records('groupings_groups', [
            'groupingid' => $cm->groupingid])) {
            foreach ($groups as $group) {
                $groupids[] = $group->groupid;
            }
        }

        // Get all enrolled users for this context.
        $enrolledusers = [];
        foreach (get_enrolled_users($context, '', $groupids, 'u.id, u.firstname, u.lastname') as $user) {
            // Concatenate firstname and lastname.
            $enrolledusers[$user->id] = "{$user->firstname} {$user->lastname}";
        }

        $enrolleduserids = array_keys($enrolledusers);

        // Check if all provided user IDs belong to enrolled users.
        $invalidusers = array_diff($userids, $enrolleduserids);
        if (!empty($invalidusers)) {
            throw new \coding_exception('Some users do not belong to the course');
        }

        // Get existing allocated grading records.
        $existingrecords = $DB->get_records('quiz_allocatedgrading', [
            'quizid' => $quizid,
            'slotid' => $questionslotid,
        ], '', 'userid, id');

        $existinguserids = array_keys($existingrecords);

        // Determine users to remove and add.
        $userstoremove = array_diff($existinguserids, $userids);
        $userstoadd = array_diff($userids, $existinguserids);

        // Remove users no longer in the list.
        if (!empty($userstoremove)) {
            list($insql, $inparams) = $DB->get_in_or_equal($userstoremove);
            $DB->delete_records_select('quiz_allocatedgrading',
                "userid $insql AND quizid = ? AND slotid = ?", array_merge($inparams, [$quizid, $questionslotid]));
        }

        // Prepare records for new users to be added.
        $currenttime = time();
        $recordstoinsert = array_map(function($userid) use ($quizid, $questionslotid, $currenttime, $USER) {
            return [
                'userid' => $userid,
                'quizid' => $quizid,
                'slotid' => $questionslotid,
                'timecreated' => $currenttime,
                'allocatedby' => $USER->id,
            ];
        }, $userstoadd);

        // Insert new records.
        if (!empty($recordstoinsert)) {
            $DB->insert_records('quiz_allocatedgrading', $recordstoinsert);
            // Send notifications to newly allocated users.
            $quiz = $DB->get_record('quiz', ['id' => $quizid], '*', MUST_EXIST);
            $course = $DB->get_record('course', ['id' => $quiz->course], '*', MUST_EXIST);
            $cm = get_coursemodule_from_instance('quiz', $quiz->id, $course->id, false, MUST_EXIST);
            foreach ($userstoadd as $userid) {
                self::notify_user($userid, $quiz, $course, $cm);
            }
        }

        // Return a new instance of the class with updated data.
        return new self($quizid, $questionslotid, $userids, $enrolledusers, $context);
    }

    /**
     * Sends email and notification for the user.
     *
     * @param int $userid user id.
     * @param \stdClass $quiz quiz object.
     * @param \stdClass $course course object.
     * @param \stdClass $cm course module object.
     * @return void
     */
    private static function notify_user(int $userid, \stdClass $quiz, \stdClass $course, \stdClass $cm) {
        global $DB;
        $user = $DB->get_record('user', ['id' => $userid], '*', MUST_EXIST);

        $reporturl = new \moodle_url('/mod/quiz/report.php', [
            'id' => $cm->id,
            'mode' => 'allocatedgrading',
        ]);

        $a = new \stdClass();
        $a->quizname = $cm->name;
        $a->studentname = $user->firstname . ' ' . $user->lastname;
        $a->coursename = $course->fullname;
        $a->allocatedgradinglink = '<a href="' . $reporturl->out() . '">' . $a->quizname . '</a>';

        $eventdata = new \core\message\message();
        $eventdata->courseid = $quiz->course;
        $eventdata->component = 'moodle';
        $eventdata->name = 'gradenotifications';
        $eventdata->userfrom = \core_user::get_noreply_user();
        $eventdata->userto = $user;
        $eventdata->notification = 1;
        $eventdata->subject = get_string('allocatedgradingnotificationsubject', 'quiz_allocatedgrading', $a);
        $eventdata->fullmessageformat = FORMAT_HTML;
        $eventdata->fullmessagehtml = get_string('allocatedgradingnotificationfull', 'quiz_allocatedgrading', $a);
        $eventdata->fullmessage = html_to_text($eventdata->fullmessagehtml);
        $eventdata->smallmessage = get_string('allocatedgradingnotificationsmall', 'quiz_allocatedgrading', $a);

        message_send($eventdata);
    }
}

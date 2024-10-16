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
 * Strings for component 'quiz_allocatedgrading', language 'en', branch 'MOODLE_20_STABLE'
 *
 * @package   quiz_allocatedgrading
 * @author    Vladislovas Kidanovas
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['alldoneredirecting'] = 'All selected attempts have been graded. Returning to the list of questions.';
$string['allocatedgradingnotificationfull'] = '<p>Hi {$a->studentname},</p>
<p>You have been allocated to grade a quiz question(s) for quiz: {$a->quizname} in course {$a->coursename}.</p>
<p>To proceed with grading, please click the following link to access the grading report: {$a->allocatedgradinglink}<p>';
$string['allocatedgradingnotificationsmall'] = 'You have been allocated to grade a quiz question(s) for quiz: \'{$a->quizname}\' in course \'{$a->coursename}\'. To proceed with grading, please click the following link to access the grading report: {$a->quizlink}';
$string['allocatedgradingnotificationsubject'] = 'You have been allocated to grade a quiz question(s): {$a->quizname}';
$string['alreadygraded'] = 'Already graded';
$string['alsoshowautomaticallygraded'] = 'Also show questions that have been graded automatically';
$string['anygrader'] = 'Any grader';
$string['assignedgrader'] = 'Assigned grader';
$string['attemptstograde'] = 'Attempts to grade';
$string['automaticallygraded'] = 'Automatically graded';
$string['backtothelistofquestions'] = 'Back to the list of questions';
$string['cannotloadquestioninfo'] = 'Unable to load questiontype specific question information';
$string['cannotgradethisattempt'] = 'Cannot grade this attempt.';
$string['changeoptions'] = 'Change options';
$string['edithint'] = 'Select grader';
$string['editlabel'] = 'Select grader';
$string['essayonly'] = 'The following questions need to be graded manually';
$string['invalidquestionid'] = 'Gradable question with ID {$a} not found';
$string['invalidattemptid'] = 'No such attempt ID exists';
$string['gradeall'] = 'grade all';
$string['gradeattemptsall'] = 'All ({$a})';
$string['gradeattemptsautograded'] = 'Those that have been graded automatically ({$a})';
$string['gradeattemptsmanuallygraded'] = 'Those that have previously been graded manually ({$a})';
$string['gradeattemptsneedsgrading'] = 'Those that need grading ({$a})';
$string['graded'] = '(graded)';
$string['gradenextungraded'] = 'Grade next {$a} ungraded attempts';
$string['gradeungraded'] = 'Grade all {$a} ungraded attempts';
$string['allocatedgrading'] = 'Allocated manual grading';
$string['grading:viewidnumber'] = 'See student identity fields while grading';
$string['grading:viewstudentnames'] = 'See student names while grading';
$string['gradingall'] = 'All {$a} attempts on this question.';
$string['gradingattempt'] = 'Attempt number {$a->attempt} for {$a->fullname}';
$string['gradingattemptwithcustomfields'] = 'Attempt number {$a->attempt} for {$a->fullname} ({$a->customfields})';
$string['gradingattemptsxtoyofz'] = 'Grading attempts {$a->from} to {$a->to} of {$a->of}';
$string['gradingnextungraded'] = 'Next {$a} ungraded attempts';
$string['gradingnotallowed'] = 'You do not have permission to manually grade responses in this quiz';
$string['gradingquestionx'] = 'Grading question {$a->number}: {$a->questionname}';
$string['gradinguser'] = 'Attempts for {$a}';
$string['gradingungraded'] = '{$a} ungraded attempts';
$string['hideautomaticallygraded'] = 'Hide questions that have been graded automatically';
$string['inprogress'] = 'In progress';
$string['nograderassigned'] = 'No grader assigned';
$string['noquestionsfound'] = 'No allocated manually graded questions found';
$string['nothingfound'] = 'Nothing to display';
$string['options'] = 'Options';
$string['orderattemptsby'] = 'Order attempts by';
$string['pluginname'] = 'Allocated manual grading';
$string['privacy:preference:order'] = 'What order to show the attempts that need grading.';
$string['privacy:preference:pagesize'] = 'How many attempts to show on each page of the grading interface.';
$string['qno'] = 'Q #';
$string['questionname'] = 'Question name';
$string['questionsperpage'] = 'Questions per page';
$string['questionsthatneedgrading'] = 'Questions that need grading';
$string['questiontitle'] = 'Question {$a->number} : "{$a->name}" ({$a->openspan}{$a->gradedattempts}{$a->closespan} / {$a->totalattempts} attempts {$a->openspan}graded{$a->closespan}).';
$string['random'] = 'Random';
$string['saveandnext'] = 'Save and show next';
$string['showstudentnames'] = 'Show student names';
$string['tograde'] = 'To grade';
$string['total'] = 'Total';
$string['unknownquestion'] = 'Unknown question';
$string['updategrade'] = 'update grades';

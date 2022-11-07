@core @core_course @core_courseformat  @javascript
Feature: Allow teachers/admin to set the sections visibility by default in the course.
  In order to set the course sections visibility by default
  As a teacher
  I need to set course sections visibility setting to expand all, collapse all and expand first, collapse the rest.

  Background:
    Given the following "users" exist:
      | username | firstname | lastname  | email                |
      | teacher1 | Teacher   | t1        | teacher1@example.com |
      | student1 | Student1  | s1        | student1@example.com |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1        | 0        |
    And the following "activities" exist:
      | activity | name       | intro                  | course | idnumber | section |
      | forum    | Activity 1 | forum description      | C1     | forum1   | 0       |
      | assign   | Activity 2 | assignment description | C1     | assign1  | 1       |
      | assign   | Activity 3 | assignment description | C1     | assign2  | 2       |
      | book     | Activity 4 | book description2      | C1     | book2    | 3       |
      | book     | Activity 5 | book description3      | C1     | book3    | 4       |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student        |

  Scenario: Course setting sections visability set to "Expand all"
    Given I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    And I navigate to "Settings" in current page administration
    When I set the following fields to these values:
      | Sections visibility | Expand all |
    And I click on "Save and display" "button"
    And I log in as "student1"
    And I am on "Course 1" course homepage
    And I should see "Activity 1" in the ".course-content" "css_element"
    And I should see "Activity 2" in the ".course-content" "css_element"
    And I should see "Activity 3" in the ".course-content" "css_element"
    And I should see "Activity 4" in the ".course-content" "css_element"
    And I should see "Activity 5" in the ".course-content" "css_element"

  Scenario: Course setting sections visability set to "Collapse all"
    Given I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    And I navigate to "Settings" in current page administration
    When I set the following fields to these values:
      | Sections visibility | Collapse all |
    And I click on "Save and display" "button"
    And I log out
    And I log in as "student1"
    And I am on "Course 1" course homepage
    And I should not see "Activity 1" in the ".course-content" "css_element"
    And I should not see "Activity 2" in the ".course-content" "css_element"
    And I should not see "Activity 3" in the ".course-content" "css_element"
    And I should not see "Activity 4" in the ".course-content" "css_element"
    And I should not see "Activity 5" in the ".course-content" "css_element"

  Scenario: Course setting sections visability set to "Expand first, collapse the rest"
    Given I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    And I navigate to "Settings" in current page administration
    When I set the following fields to these values:
      | Sections visibility | Expand first, collapse the rest |
    And I click on "Save and display" "button"
    And I log out
    And I log in as "student1"
    And I am on "Course 1" course homepage
    And I should see "Activity 1" in the ".course-content" "css_element"
    And I should not see "Activity 2" in the ".course-content" "css_element"
    And I should not see "Activity 3" in the ".course-content" "css_element"
    And I should not see "Activity 4" in the ".course-content" "css_element"
    And I should not see "Activity 5" in the ".course-content" "css_element"

  Scenario: Default sections visability setting value can changed to Expand first, collapse the rest
    Given I log in as "admin"
    And I navigate to "Courses > Course default settings" in site administration
    When I set the following fields to these values:
      | Sections visibility | Expand first, collapse the rest |
    And I click on "Save changes" "button"
    And I navigate to "Courses > Add a new course" in site administration
    Then the field "sectionsvisibility" matches value "Expand first, collapse the rest"

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

namespace block_glossary_random;

use advanced_testcase;
use block_glossary_random;
use context_course;

/**
 * PHPUnit block_glossary_random tests
 *
 * @package    block_glossary_random
 * @category   test
 * @copyright  2021 Sara Arjona (sara@moodle.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @coversDefaultClass \block_glossary_random
 */
final class glossary_random_test extends advanced_testcase {
    public static function setUpBeforeClass(): void {
        require_once(__DIR__ . '/../../moodleblock.class.php');
        require_once(__DIR__ . '/../block_glossary_random.php');
        parent::setUpBeforeClass();
    }

    /**
     * Test the behaviour of can_block_be_added() method.
     *
     * @covers ::can_block_be_added
     */
    public function test_can_block_be_added(): void {
        $this->resetAfterTest();
        $this->setAdminUser();

        // Create a course and prepare the page where the block will be added.
        $course = $this->getDataGenerator()->create_course();
        $page = new \moodle_page();
        $page->set_context(context_course::instance($course->id));
        $page->set_pagelayout('course');

        $block = new block_glossary_random();
        $pluginclass = \core_plugin_manager::resolve_plugininfo_class('mod');

        // If glossary module is enabled, the method should return true.
        $pluginclass::enable_plugin('glossary', 1);
        $this->assertTrue($block->can_block_be_added($page));

        // However, if the glossary module is disabled, the method should return false.
        $pluginclass::enable_plugin('glossary', 0);
        $this->assertFalse($block->can_block_be_added($page));
    }
}

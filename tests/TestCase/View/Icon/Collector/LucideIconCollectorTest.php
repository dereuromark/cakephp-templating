<?php declare(strict_types=1);

namespace Templating\Test\TestCase\View\Icon\Collector;

use Cake\TestSuite\TestCase;
use Templating\View\Icon\Collector\LucideIconCollector;

class LucideIconCollectorTest extends TestCase {

	/**
	 * Show that we are still API compatible/valid.
	 *
	 * @return void
	 */
	public function testCollect(): void {
		$path = TEST_FILES . 'font_icon' . DS . 'lucide' . DS . 'icons.json';

		$result = LucideIconCollector::collect($path);

		$this->assertGreaterThan(5, count($result), 'count of ' . count($result));
		$this->assertTrue(in_array('home', $result, true));
		$this->assertTrue(in_array('user', $result, true));
		$this->assertTrue(in_array('search', $result, true));
	}

	/**
	 * Test collecting from a directory of SVG files.
	 *
	 * @return void
	 */
	public function testCollectFromDirectory(): void {
		$path = TEST_FILES . 'font_icon' . DS . 'lucide_svg';

		$result = LucideIconCollector::collect($path);

		$this->assertGreaterThan(3, count($result), 'count of ' . count($result));
		$this->assertTrue(in_array('home', $result, true));
		$this->assertTrue(in_array('user', $result, true));
		$this->assertTrue(in_array('search', $result, true));
		$this->assertTrue(in_array('settings', $result, true));

		// Verify icons are sorted
		$sorted = $result;
		sort($sorted);
		$this->assertSame($sorted, $result);
	}

}

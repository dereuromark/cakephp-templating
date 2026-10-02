<?php declare(strict_types=1);

namespace Templating\Test\TestCase\View\Icon\Collector;

use Cake\TestSuite\TestCase;
use Templating\View\Icon\Collector\HeroiconsIconCollector;
use Templating\View\Icon\Collector\LucideIconCollector;

class HeroiconsIconCollectorTest extends TestCase {

	/**
	 * Show that we are still API compatible/valid.
	 *
	 * @return void
	 */
	public function testCollect(): void {
		$path = TEST_FILES . 'font_icon' . DS . 'heroicons' . DS . 'icons.json';

		$result = HeroiconsIconCollector::collect($path);

		$this->assertGreaterThan(5, count($result), 'count of ' . count($result));
		$this->assertTrue(in_array('home', $result, true));
		$this->assertTrue(in_array('user', $result, true));
		$this->assertTrue(in_array('magnifying-glass', $result, true));
	}

	/**
	 * Test collecting from a directory with style subdirectories (outline, solid).
	 *
	 * @return void
	 */
	public function testCollectFromDirectory(): void {
		$path = TEST_FILES . 'font_icon' . DS . 'heroicons_svg';

		$result = HeroiconsIconCollector::collect($path);

		$this->assertGreaterThan(2, count($result), 'count of ' . count($result));
		$this->assertTrue(in_array('home', $result, true));
		$this->assertTrue(in_array('user', $result, true));
		$this->assertTrue(in_array('magnifying-glass', $result, true));

		// Icons should be unique (not duplicated from outline and solid)
		$this->assertSame(array_unique($result), $result);

		// Verify icons are sorted
		$sorted = $result;
		sort($sorted);
		$this->assertSame($sorted, $result);
	}

	/**
	 * The in-memory cache must not leak results between collector classes for the same path.
	 *
	 * @return void
	 */
	public function testCacheIsScopedPerCollector(): void {
		$path = TEST_FILES . 'font_icon' . DS . 'heroicons_svg';

		$this->assertSame([], LucideIconCollector::collect($path));
		$this->assertSame(['home', 'magnifying-glass', 'user'], HeroiconsIconCollector::collect($path));
	}

}

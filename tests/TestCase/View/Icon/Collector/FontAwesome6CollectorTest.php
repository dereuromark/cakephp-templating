<?php declare(strict_types=1);

namespace Templating\Test\TestCase\View\Icon\Collector;

use Cake\TestSuite\TestCase;
use Templating\View\Icon\Collector\FontAwesome6IconCollector;

class FontAwesome6CollectorTest extends TestCase {

	/**
	 * Show that we are still API compatible/valid.
	 *
	 * @return void
	 */
	public function testCollectFromIcons(): void {
		$path = TEST_FILES . 'font_icon' . DS . 'fa6' . DS . 'icons.json';

		$result = FontAwesome6IconCollector::collect($path);

		$this->assertTrue(count($result) > 1001, 'count of ' . count($result));
		$this->assertTrue(in_array('thumbs-up', $result, true));
	}

	/**
	 * Show that we are still API compatible/valid.
	 *
	 * @return void
	 */
	public function testCollectFromIconFamilies(): void {
		$path = TEST_FILES . 'font_icon' . DS . 'fa6' . DS . 'icon-families.json';

		$result = FontAwesome6IconCollector::collect($path);

		$this->assertTrue(count($result) > 1951, 'count of ' . count($result));
		$this->assertTrue(in_array('thumbs-up', $result, true));

		$this->assertTrue(in_array('0', $result, true));

		// Name vs Alias
		$this->assertTrue(in_array('photo-film', $result, true));
		$this->assertTrue(in_array('photo-video', $result, true));
	}

	/**
	 * Show that we are still API compatible/valid.
	 *
	 * @return void
	 */
	public function testCollectFromIconFamiliesWithoutAlias(): void {
		$path = TEST_FILES . 'font_icon' . DS . 'fa6' . DS . 'icon-families.json';

		$result = FontAwesome6IconCollector::collect($path, ['aliases' => false]);

		$this->assertTrue(count($result) > 1380, 'count of ' . count($result));
		$this->assertTrue(in_array('thumbs-up', $result, true));

		// Name vs Alias
		$this->assertTrue(in_array('photo-film', $result, true));
		$this->assertFalse(in_array('photo-video', $result, true));
	}

	/**
	 * Show that we are still API compatible/valid.
	 *
	 * @return void
	 */
	public function testCollectSvg(): void {
		$path = TEST_FILES . 'font_icon' . DS . 'fa6' . DS . 'solid.svg';

		$result = FontAwesome6IconCollector::collect($path);

		$this->assertTrue(count($result) > 1000, 'count of ' . count($result));
		$this->assertTrue(in_array('thumbs-up', $result, true));
	}

	/**
	 * Show that we are still API compatible/valid.
	 *
	 * @return void
	 */
	public function testCollectYml(): void {
		$path = TEST_FILES . 'font_icon' . DS . 'fa6' . DS . 'icons.yml';

		$result = FontAwesome6IconCollector::collect($path);

		$this->assertTrue(count($result) > 1952, 'count of ' . count($result));
		$this->assertTrue(in_array('thumbs-up', $result, true));
	}

}

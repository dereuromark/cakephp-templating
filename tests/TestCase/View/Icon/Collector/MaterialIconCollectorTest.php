<?php declare(strict_types=1);

namespace Templating\Test\TestCase\View\Icon\Collector;

use Cake\TestSuite\TestCase;
use Templating\View\Icon\Collector\MaterialIconCollector;

class MaterialIconCollectorTest extends TestCase {

	/**
	 * Show that we are still API compatible/valid.
	 *
	 * @return void
	 */
	public function testCollect(): void {
		$path = TEST_FILES . 'font_icon' . DS . 'material' . DS . 'index.d.ts';

		$result = MaterialIconCollector::collect($path);

		$this->assertTrue(count($result) > 2444, 'count of ' . count($result));
		$this->assertTrue(in_array('zoom_in', $result, true));
	}

	/**
	 * @return void
	 */
	public function testCollectFromSvgDirectory(): void {
		$result = MaterialIconCollector::collect(TEST_FILES . 'font_icon' . DS . 'fa6_svg' . DS);

		$this->assertSame(['thumbs-up', 'user'], $result);
	}

	/**
	 * @return void
	 */
	public function testCollectFromSvgMap(): void {
		$result = MaterialIconCollector::collect(TEST_FILES . 'font_icon' . DS . 'svg_map.json');

		$this->assertSame(['house', 'user'], $result);
	}

}

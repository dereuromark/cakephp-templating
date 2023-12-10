<?php

namespace Templating\Test\TestCase\View\Icon;

use Cake\TestSuite\TestCase;
use Templating\View\Icon\FeatherIcon;
use Templating\View\Icon\IconCollection;
use Templating\View\Icon\MaterialIcon;

class IconCollectionTest extends TestCase {

	/**
	 * @return void
	 */
	public function testRender(): void {
		$config = [
			'sets' => [
				'feather' => [
					'class' => FeatherIcon::class,
				],
			],
			'separator' => ':',
		];
		$result = (new IconCollection($config))->render('foo');

		$this->assertSame('<span data-feather="foo" title="Foo"></span>', (string)$result);
	}

	/**
	 * @return void
	 */
	public function testRenderNamespaced(): void {
		$config = [
			'sets' => [
				'feather' => [
					'class' => FeatherIcon::class,
				],
				'material' => [
					'class' => MaterialIcon::class,
					'namespace' => 'material-symbols',
					'attributes' => [
						'data-custom' => 'some-custom',
					],
				],
			],
			'separator' => ':',
			'attributes' => [
				'data-default' => 'some-default',
			],
		];
		$result = (new IconCollection($config))->render('material:foo');

		$this->assertSame('<span class="material-symbols" title="Foo" data-custom="some-custom" data-default="some-default">foo</span>', (string)$result);
	}

	/**
	 * @return void
	 */
	public function testNames(): void {
		$config = [
			'sets' => [
				'feather' => [
					'class' => FeatherIcon::class,
					'path' => TEST_FILES . 'font_icon/feather/icons.json',
				],
				'material' => [
					'class' => MaterialIcon::class,
					'path' => TEST_FILES . 'font_icon/material/index.d.ts',
				],
			],
		];
		$result = (new IconCollection($config))->names();
		$this->assertTrue(count($result['material']) > 1740, 'count of ' . count($result['material']));
		$this->assertTrue(in_array('zoom_out', $result['material'], true));
	}

}

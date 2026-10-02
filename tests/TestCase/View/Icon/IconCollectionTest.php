<?php declare(strict_types=1);

namespace Templating\Test\TestCase\View\Icon;

use Cake\TestSuite\TestCase;
use Templating\View\Icon\FeatherIcon;
use Templating\View\Icon\FontAwesome7Icon;
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

	/**
	 * Test that title can be explicitly disabled with false
	 *
	 * @return void
	 */
	public function testRenderWithTitleFalse(): void {
		$config = [
			'sets' => [
				'feather' => [
					'class' => FeatherIcon::class,
				],
			],
			'separator' => ':',
		];
		$result = (new IconCollection($config))->render('foo', [], ['title' => false]);

		$this->assertSame('<span data-feather="foo"></span>', (string)$result);
		$this->assertStringNotContainsString('title=', (string)$result);
	}

	/**
	 * Test that title false works with other attributes
	 *
	 * @return void
	 */
	public function testRenderWithTitleFalseAndOtherAttributes(): void {
		$config = [
			'sets' => [
				'feather' => [
					'class' => FeatherIcon::class,
				],
			],
			'separator' => ':',
		];
		$result = (new IconCollection($config))->render('foo', [], ['title' => false, 'class' => 'custom-class']);

		$this->assertSame('<span data-feather="foo" class="custom-class"></span>', (string)$result);
		$this->assertStringNotContainsString('title=', (string)$result);
	}

	/**
	 * Name collection falls back to `svgPath` (SVG directory or JSON map) when no `path` is set.
	 *
	 * @return void
	 */
	public function testNamesFromSvgPathOnly(): void {
		$config = [
			'sets' => [
				'fa7' => [
					'class' => FontAwesome7Icon::class,
					'svgPath' => TEST_FILES . 'font_icon' . DS . 'fa6_svg' . DS,
				],
				'feather' => [
					'class' => FeatherIcon::class,
					'svgPath' => TEST_FILES . 'font_icon' . DS . 'svg_map.json',
				],
			],
			'checkExistence' => true,
			'cache' => false,
		];
		$collection = new IconCollection($config);

		$expected = [
			'fa7' => ['thumbs-up', 'user'],
			'feather' => ['house', 'user'],
		];
		$this->assertSame($expected, $collection->names());

		$result = (string)$collection->render('house', [], ['title' => false]);
		$this->assertStringStartsWith('<svg', $result);
	}

}

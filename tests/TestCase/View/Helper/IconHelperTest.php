<?php declare(strict_types=1);

namespace Templating\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use Templating\View\Helper\IconHelper;
use Templating\View\HtmlStringable;
use Templating\View\Icon\FeatherIcon;
use Templating\View\Icon\MaterialIcon;

class IconHelperTest extends TestCase {

	/**
	 * @var \Templating\View\Helper\IconHelper
	 */
	protected $Icon;

	/**
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		$config = [
			'sets' => [
				'f' => [
					'class' => FeatherIcon::class,
					'path' => TEST_FILES . 'font_icon/feather/icons.json',
				],
				'm' => [
					'class' => MaterialIcon::class,
					'path' => TEST_FILES . 'font_icon/material/index.d.ts',
				],
			],
		];

		$this->Icon = new IconHelper(new View(null), $config);
	}

	/**
	 * @return void
	 */
	public function testIconDefault() {
		$result = $this->Icon->render('edit');
		$expected = '<span data-feather="edit" title="Edit"></span>';
		$this->assertSame($expected, (string)$result);
	}

	/**
	 * @return void
	 */
	public function testIconPrefixed() {
		$result = $this->Icon->render('f:edit');
		$expected = '<span data-feather="edit" title="Edit"></span>';
		$this->assertSame($expected, (string)$result);
	}

	/**
	 * @return void
	 */
	public function testIconWithCustomAttributes() {
		$result = $this->Icon->render('m:save', [], ['data-x' => 'y']);
		$expected = '<span class="material-icons" data-x="y" title="Save">save</span>';
		$this->assertSame($expected, (string)$result);
	}

	/**
	 * @return void
	 */
	public function testIconWithCustomClassAttributes() {
		$result = $this->Icon->render('m:save', [], ['class' => 'my-extra']);
		$expected = '<span class="material-icons my-extra" title="Save">save</span>';
		$this->assertSame($expected, (string)$result);
	}

	/**
	 * @return void
	 */
	public function testIconWithCustomTitleField() {
		$result = $this->Icon->render('m:save', ['titleField' => 'data-title'], ['class' => 'my-extra']);
		$expected = '<span class="material-icons my-extra" data-title="Save">save</span>';
		$this->assertSame($expected, (string)$result);
	}

	/**
	 * @return void
	 */
	public function testIconWithCustomTitleAttributesViaOptions() {
		$result = $this->Icon->render('m:save', ['title' => 'Save me'], ['class' => 'my-extra']);
		$expected = '<span class="material-icons my-extra" title="Save me">save</span>';
		$this->assertSame($expected, (string)$result);
	}

	/**
	 * @return void
	 */
	public function testIconWithCustomFontIcon() {
		$config = [
			'sets' => [
				'f' => [
					'class' => FeatherIcon::class,
					'path' => TEST_FILES . 'font_icon/feather/icons.json',
				],
				'm' => [
					'class' => MaterialIcon::class,
					'path' => TEST_FILES . 'font_icon/material/index.d.ts',
				],
			],
			'map' => [
				'edit' => 'm:save',
			],
		];

		$this->Icon = new IconHelper(new View(null), $config);

		$result = $this->Icon->render('edit');
		$expected = '<span class="material-icons" title="Edit">save</span>';
		$this->assertInstanceOf(HtmlStringable::class, $result);
		$this->assertSame($expected, (string)$result);
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();

		unset($this->Icon);
	}

}

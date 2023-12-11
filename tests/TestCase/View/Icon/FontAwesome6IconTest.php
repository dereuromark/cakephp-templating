<?php declare(strict_types=1);

namespace Templating\Test\TestCase\View\Icon;

use Cake\TestSuite\TestCase;
use Templating\View\Icon\FontAwesome6Icon;

class FontAwesome6IconTest extends TestCase {

	/**
	 * @var \Templating\View\Icon\FontAwesome6Icon
	 */
	protected $icon;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		$this->icon = new FontAwesome6Icon();
	}

	/**
	 * @return void
	 */
	public function testRender(): void {
		$result = $this->icon->render('camera-retro');
		$this->assertSame('<span class="fa-solid fa-camera-retro"></span>', (string)$result);
	}

	/**
	 * @return void
	 */
	public function testRenderLight(): void {
		$this->icon = new FontAwesome6Icon(['namespace' => 'light']);

		$result = $this->icon->render('camera-retro');
		$this->assertSame('<span class="fa-light fa-camera-retro"></span>', (string)$result);
	}

	/**
	 * @return void
	 */
	public function testRenderRotate(): void {
		$result = $this->icon->render('camera-retro', ['rotate' => 90]);
		$this->assertSame('<span class="fa-solid fa-rotate-90 fa-camera-retro"></span>', (string)$result);
	}

	/**
	 * @return void
	 */
	public function testRenderSpin(): void {
		$result = $this->icon->render('camera-retro', ['spin' => true]);
		$this->assertSame('<span class="fa-solid fa-spin fa-camera-retro"></span>', (string)$result);
	}

}

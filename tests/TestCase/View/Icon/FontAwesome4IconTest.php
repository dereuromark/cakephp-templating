<?php declare(strict_types=1);

namespace Templating\Test\TestCase\View\Icon;

use Cake\TestSuite\TestCase;
use Templating\View\Icon\FontAwesome4Icon;

class FontAwesome4IconTest extends TestCase {

	/**
	 * @var \Templating\View\Icon\FontAwesome4Icon
	 */
	protected $icon;

	/**
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->icon = new FontAwesome4Icon();
	}

	/**
	 * @return void
	 */
	public function testRender(): void {
		$result = $this->icon->render('camera-retro');
		$this->assertSame('<span class="fa fa-camera-retro"></span>', (string)$result);
	}

	/**
	 * @return void
	 */
	public function testRenderRotate(): void {
		$result = $this->icon->render('camera-retro', ['rotate' => 90]);
		$this->assertSame('<span class="fa fa-rotate-90 fa-camera-retro"></span>', (string)$result);
	}

	/**
	 * @return void
	 */
	public function testRenderSpin(): void {
		$result = $this->icon->render('camera-retro', ['spin' => true]);
		$this->assertSame('<span class="fa fa-spin fa-camera-retro"></span>', (string)$result);
	}

}

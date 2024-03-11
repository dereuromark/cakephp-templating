<?php declare(strict_types=1);

namespace Templating\Test\TestCase\View\Icon;

use Cake\TestSuite\TestCase;
use Templating\View\Icon\FeatherIcon;

class FeatherIconTest extends TestCase {

	/**
	 * @var \Templating\View\Icon\FeatherIcon
	 */
	protected $icon;

	/**
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->icon = new FeatherIcon();
	}

	/**
	 * @return void
	 */
	public function testRender(): void {
		$result = $this->icon->render('view');
		$this->assertSame('<span data-feather="view"></span>', (string)$result);
	}

}

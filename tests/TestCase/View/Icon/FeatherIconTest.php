<?php

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
	public function setUp(): void {
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
<?php

namespace Templating\Test\TestCase\View\Icon;

use Cake\TestSuite\TestCase;
use Templating\View\Html\HtmlStringable;
use Templating\View\Icon\MaterialIcon;

class MaterialIconTest extends TestCase {

	/**
	 * @var \Templating\View\Icon\MaterialIcon
	 */
	protected $icon;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		$this->icon = new MaterialIcon();
	}

	/**
	 * @return void
	 */
	public function testRender(): void {
		$result = $this->icon->render('view');
		$this->assertInstanceOf(HtmlStringable::class, $result);
		$this->assertSame('<span class="material-icons">view</span>', (string)$result);
	}

	/**
	 * @return void
	 */
	public function testRenderNamespace(): void {
		$this->icon = new MaterialIcon(['namespace' => 'material-symbols-outlined']);

		$result = $this->icon->render('view');
		$this->assertInstanceOf(HtmlStringable::class, $result);
		$this->assertSame('<span class="material-symbols-outlined">view</span>', (string)$result);
	}

}

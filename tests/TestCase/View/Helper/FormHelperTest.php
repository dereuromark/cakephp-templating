<?php declare(strict_types=1);

namespace Templating\Test\TestCase\View\Helper;

use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use Templating\View\Helper\FormHelper;
use Templating\View\HtmlStringable;

class FormHelperTest extends TestCase {

	/**
	 * @var \Templating\View\Helper\FormHelper
	 */
	protected $helper;

	/**
	 * @var \Cake\Http\ServerRequest
	 */
	protected $request;

	/**
	 * setUp method
	 *
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->request = new ServerRequest();
		$view = new View($this->request);
		$this->helper = new FormHelper($view);
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();

		unset($this->helper);
	}

	/**
	 * @return void
	 */
	public function testButton() {
		$icon = new class implements HtmlStringable {

			/**
			 * @return string
			 */
			public function __toString(): string {
				return '<span>Some ICON HTML</span>';
			}
		};

		$result = $this->helper->button($icon);
		$expected = '<button type="submit"><span>Some ICON HTML</span></button>';
		$this->assertSame($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testPostButton() {
		$icon = new class implements HtmlStringable {

			/**
			 * @return string
			 */
			public function __toString(): string {
				return '<span>Some ICON HTML</span>';
			}
		};

		$result = $this->helper->postButton($icon, '/');
		$expected = '<form method="post" accept-charset="utf-8" action="/"><button type="submit"><span>Some ICON HTML</span></button></form>';
		$this->assertSame($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testPostLink() {
		$icon = new class implements HtmlStringable {

			/**
			 * @return string
			 */
			public function __toString(): string {
				return '<span>Some ICON HTML</span>';
			}
		};

		$result = $this->helper->postLink($icon, '/');
		$this->assertTextContains('><span>Some ICON HTML</span></a>', $result);
	}

}

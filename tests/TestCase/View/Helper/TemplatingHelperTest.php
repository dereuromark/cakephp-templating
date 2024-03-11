<?php declare(strict_types=1);

namespace Templating\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use Templating\View\Helper\TemplatingHelper;
use Templating\View\Html;

class TemplatingHelperTest extends TestCase {

	/**
	 * @var \Templating\View\Helper\TemplatingHelper
	 */
	protected TemplatingHelper $Templating;

	/**
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->Templating = new TemplatingHelper(new View(null));
	}

	/**
	 * @return void
	 */
	public function testOk() {
		$result = $this->Templating->ok('Foo Bar', true);
		$expected = '<span class="ok-yes" style="color:green">Foo Bar</span>';
		$this->assertEquals($expected, $result);

		$result = $this->Templating->ok('<b>Some unsafe string</b>', false);
		$expected = '<span class="ok-no" style="color:red">&lt;b&gt;Some unsafe string&lt;/b&gt;</span>';
		$this->assertEquals($expected, $result);

		$result = $this->Templating->ok('<b>Some HTML string</b>', false, ['escape' => false]);
		$expected = '<span class="ok-no" style="color:red"><b>Some HTML string</b></span>';
		$this->assertEquals($expected, $result);

		$result = $this->Templating->ok(Html::create('<b>Some HTML string</b>'), false);
		$expected = '<span class="ok-no" style="color:red"><b>Some HTML string</b></span>';
		$this->assertEquals($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testWarning(): void {
		$result = $this->Templating->warning('Foo <b>Bar</b>', true);
		$expected = 'Foo &lt;b&gt;Bar&lt;/b&gt;';
		$this->assertEquals($expected, $result);

		$result = $this->Templating->warning('Foo <b>Bar</b>', false);
		$expected = '<span class="ok-no" style="color:red">Foo &lt;b&gt;Bar&lt;/b&gt;</span>';
		$this->assertEquals($expected, $result);

		$result = $this->Templating->warning(Html::create('<b>Some HTML string</b>'), true);
		$expected = '<b>Some HTML string</b>';
		$this->assertEquals($expected, $result);
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();

		unset($this->Templating);
	}

}

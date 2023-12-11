<?php declare(strict_types=1);

namespace Templating\Test\TestCase\View\Html;

use Cake\TestSuite\TestCase;
use Templating\View\Html;

class HtmlTest extends TestCase {

	/**
	 * @return void
	 */
	public function testCreate(): void {
		$html = Html::create('<p>Some text</p>');

		$this->assertSame('<p>Some text</p>', (string)$html);
	}

}

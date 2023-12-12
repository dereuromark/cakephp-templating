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

	/**
	 * @return void
	 */
	public function testSerializeUnserialize(): void {
		$html = Html::create('<p>Some text</p>');
		$array = [
			'html' => $html,
		];
		$serialized = serialize($array);

		$array = unserialize($serialized);

		$this->assertSame('<p>Some text</p>', (string)$array['html']);
	}

	/**
	 * @return void
	 */
	public function testJsonEncodeDecode(): void {
		$html = Html::create('<p>Some text</p>');
		$array = [
			'html' => $html,
		];
		$serialized = json_encode($array, JSON_PRETTY_PRINT);

		$array = json_decode($serialized, true);

		$this->assertSame('<p>Some text</p>', (string)$array['html']);
	}

}

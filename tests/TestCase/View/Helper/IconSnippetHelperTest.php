<?php declare(strict_types=1);

namespace Templating\Test\TestCase\View\Helper;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use Templating\View\Helper\IconSnippetHelper;
use Templating\View\Icon\BootstrapIcon;

class IconSnippetHelperTest extends TestCase {

	/**
	 * @var \Templating\View\Helper\IconSnippetHelper
	 */
	protected IconSnippetHelper $IconSnippet;

	/**
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->loadRoutes();

		Configure::write('Icon', [
			'sets' => [
				'bs' => BootstrapIcon::class,
				/*
				'bs' => [
					'class' => BootstrapIcon::class,
					'path' => TEST_FILES . 'font_icon/bootstrap/bootstrap-icons.json',
				],
				*/
			],
		]);

		$this->IconSnippet = new IconSnippetHelper(new View(null));
	}

	/**
	 * @return void
	 */
	public function testYesNo() {
		$result = $this->IconSnippet->yesNo(true);
		$expected = '<span class="bi bi-yes" title="Yes"></span>';
		$this->assertEquals($expected, $result);

		$result = $this->IconSnippet->yesNo(false);
		$expected = '<span class="bi bi-no" title="No"></span>';
		$this->assertEquals($expected, $result);

		$result = $this->IconSnippet->yesNo('2', ['on' => 2, 'onTitle' => 'foo']);
		$expected = '<span class="bi bi-yes" title="foo"></span>';
		$this->assertEquals($expected, $result);

		$result = $this->IconSnippet->yesNo('3', ['on' => 4, 'offTitle' => 'nope']);
		$expected = '<span class="bi bi-no" title="nope"></span>';
		$this->assertEquals($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testThumbs() {
		$result = $this->IconSnippet->thumbs(1);
		$expected = '<span class="bi bi-pro" title="Pro"></span>';
		$this->assertEquals($expected, $result);

		$result = $this->IconSnippet->thumbs(0);
		$expected = '<span class="bi bi-contra" title="Contra"></span>';
		$this->assertEquals($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testNeighbors() {
		$neighbors = [
			'prev' => ['id' => 1, 'foo' => 'bar'],
			'next' => ['id' => 2, 'foo' => 'y'],
		];
		$options = [
			'url' => ['controller' => 'Some'],
		];

		$result = $this->IconSnippet->neighbors($neighbors, 'foo', $options);
		$expected = '<div class="next-prev-navi"><a href="/some/index/1" title="bar"><span class="bi bi-prev" title="Prev"></span>&nbsp;prevRecord</a>&nbsp;&nbsp;<a href="/some/index/2" title="y"><span class="bi bi-next" title="Next"></span>&nbsp;nextRecord</a></div>';
		$this->assertEquals($expected, $result);
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();

		unset($this->IconSnippet);

		Configure::delete('App.imageBaseUrl');
		Configure::delete('Icon');
	}

}

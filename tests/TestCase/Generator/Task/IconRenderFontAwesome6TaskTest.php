<?php declare(strict_types=1);

namespace Templating\Test\TestCase\Generator\Task;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use IdeHelper\Generator\Directive\ExpectedArguments;
use IdeHelper\Generator\Directive\RegisterArgumentsSet;
use Templating\Generator\Task\IconRenderTask;
use Templating\View\Helper\IconHelper;
use Templating\View\Icon\FontAwesome6Icon;

class IconRenderFontAwesome6TaskTest extends TestCase {

	protected IconHelper $helper;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		$config = [
			'sets' => [
				'fa6' => [
					'class' => FontAwesome6Icon::class,
					'path' => TEST_FILES . 'font_icon/fa6/icons.json',
				],
			],
		];

		if (!file_exists($config['sets']['fa6']['path'])) {
			exec('cd test_files && php update-test-files.php');
		}

		$this->helper = new IconHelper(new View(), $config);
	}

	/**
	 * @return void
	 */
	public function testCollect(): void {
		$config = $this->helper->getConfig();

		$task = new IconRenderTask($config);

		$result = $task->collect();

		$this->assertCount(2, $result);

		/** @var \IdeHelper\Generator\Directive\RegisterArgumentsSet $directive */
		$directive = array_shift($result);

		$this->assertInstanceOf(RegisterArgumentsSet::class, $directive);

		$list = $directive->toArray()['list'];
		$list = array_map(function ($className) {
			return (string)$className;
		}, $list);

		$this->assertTrue(count($list) > 900);
		$this->assertSame('\'smile\'', $list['smile']);

		/** @var \IdeHelper\Generator\Directive\ExpectedArguments $directive */
		$directive = array_shift($result);
		$this->assertInstanceOf(ExpectedArguments::class, $directive);
	}

}

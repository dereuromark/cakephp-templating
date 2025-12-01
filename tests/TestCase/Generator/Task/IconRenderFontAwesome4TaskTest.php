<?php declare(strict_types=1);

namespace Templating\Test\TestCase\Generator\Task;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use IdeHelper\Generator\Directive\ExpectedArguments;
use IdeHelper\Generator\Directive\RegisterArgumentsSet;
use PHPUnit\Framework\Attributes\DataProvider;
use Templating\Generator\Task\IconRenderTask;
use Templating\View\Helper\IconHelper;
use Templating\View\Icon\FontAwesome4Icon;

class IconRenderFontAwesome4TaskTest extends TestCase {

	protected IconHelper $helper;

	/**
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		$config = [
			'sets' => [
				'fa4' => [
					'class' => FontAwesome4Icon::class,
					'path' => TEST_FILES . 'font_icon/fa4/variables.less',
				],
			],
			'cache' => false,
		];

		if (!file_exists($config['sets']['fa4']['path'])) {
			exec('cd test_files && php update-test-files.php');
		}

		$this->helper = new IconHelper(new View(), $config);
	}

	/**
	 *
	 * @param string $extension
	 * @return void
	 */
	#[DataProvider('extensions')]
	public function testCollect(string $extension): void {
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

		$this->assertTrue(count($list) > 780, 'count of ' . count($list));
		$this->assertSame('\'smile-o\'', $list['smile-o']);

		/** @var \IdeHelper\Generator\Directive\ExpectedArguments $directive */
		$directive = array_shift($result);
		$this->assertInstanceOf(ExpectedArguments::class, $directive);
	}

	/**
	 * @return array
	 */
	public static function extensions(): array {
		return [
			'scss' => ['scss'],
			'less' => ['less'],
		];
	}

}

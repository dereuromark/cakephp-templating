<?php declare(strict_types=1);

namespace Templating\Test\TestCase\Controller\Admin;

use Cake\Core\Configure;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use Templating\View\Icon\BootstrapIcon;

class IconsControllerTest extends TestCase {

	use IntegrationTestTrait;

	/**
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		Configure::write('Icon', [
			'sets' => [
				'bs' => [
					'class' => BootstrapIcon::class,
					'path' => TEST_FILES . 'font_icon/bootstrap/bootstrap-icons.json',
				],
			],
			'map' => [
				'home' => 'bs:home',
			],
		]);

		$this->loadPlugins(['Templating']);

		$this->disableErrorHandlerMiddleware();
	}

	/**
	 * @return void
	 */
	public function testIndex(): void {
		$this->get(['prefix' => 'Admin', 'plugin' => 'Templating', 'controller' => 'Icons', 'action' => 'index']);

		$this->assertResponseCode(200);
	}

	/**
	 * @return void
	 */
	public function testSets(): void {
		$this->get(['prefix' => 'Admin', 'plugin' => 'Templating', 'controller' => 'Icons', 'action' => 'sets']);

		$this->assertResponseCode(200);
	}

	/**
	 * @return void
	 */
	public function testConflicts(): void {
		$this->get(['prefix' => 'Admin', 'plugin' => 'Templating', 'controller' => 'Icons', 'action' => 'conflicts']);

		$this->assertResponseCode(200);
	}

}

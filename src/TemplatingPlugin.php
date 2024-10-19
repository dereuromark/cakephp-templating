<?php declare(strict_types=1);

namespace Templating;

use Cake\Core\BasePlugin;
use Cake\Routing\RouteBuilder;

class TemplatingPlugin extends BasePlugin {

	/**
	 * @var bool
	 */
	protected bool $middlewareEnabled = false;

	/**
	 * @var bool
	 */
	protected bool $consoleEnabled = false;

	/**
	 * @var bool
	 */
	protected bool $bootstrapEnabled = false;

	/**
	 * @param \Cake\Routing\RouteBuilder $routes The route builder to update.
	 *
	 * @return void
	 */
	public function routes(RouteBuilder $routes): void {
		$routes->prefix('Admin', function (RouteBuilder $routes): void {
			$routes->plugin('Templating', function (RouteBuilder $routes): void {
				$routes->fallbacks();
			});
		});
	}

}

<?php declare(strict_types=1);

namespace Templating\Controller\Admin;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Http\Exception\NotFoundException;
use Templating\View\Icon\IconCollection;

class IconsController extends AppController {

	/**
	 * @return \Cake\Http\Response|null|void
	 */
	public function index() {
		$config = (array)Configure::read('Icon');
		if (empty($config['sets'])) {
			throw new NotFoundException('No icon set defined yet!');
		}

		$flat = [];
		if (Configure::read('Icon.autoPrefix') !== false) {
			$icons = (new IconCollection($config))->names();
			foreach ($icons as $set => $list) {
				foreach ($list as $icon) {
					if (isset($flat[$icon])) {
						continue;
					}

					$flat[$icon] = $set;
				}
			}
		}

		$map = $config['map'] ?? [];
		ksort($map);

		ksort($flat);
		$icons = $flat;

		$this->set(compact('icons', 'map'));
	}

	/**
	 * @return \Cake\Http\Response|null|void
	 */
	public function sets() {
		$config = (array)Configure::read('Icon');
		if (empty($config['sets'])) {
			throw new NotFoundException('No icon set defined yet!');
		}

		Configure::write('Icon.checkExistence', false);

		$icons = (new IconCollection($config))->names();
		$count = 0;
		$flat = [];
		$conflicting = [];
		foreach ($icons as $set => $list) {
			$count += count($list);
			foreach ($list as $icon) {
				if (!isset($flat[$icon])) {
					$flat[$icon] = $set;

					continue;
				}

				$conflicting[$icon][] = $set;
				$conflicting[$icon][] = $flat[$icon];
			}
		}

		$map = $config['map'] ?? [];

		$this->set(compact('icons', 'count', 'map', 'conflicting'));
	}

	/**
	 * @return \Cake\Http\Response|null|void
	 */
	public function conflicts() {
		$config = (array)Configure::read('Icon');
		if (empty($config['sets'])) {
			throw new NotFoundException('No icon set defined yet!');
		}

		Configure::write('Icon.checkExistence', false);

		$icons = (new IconCollection($config))->names();
		$flat = [];
		$conflicting = [];
		foreach ($icons as $set => $list) {
			foreach ($list as $icon) {
				if (!isset($flat[$icon])) {
					$flat[$icon] = $set;

					continue;
				}

				$conflicting[$icon][] = $set;
				$conflicting[$icon][] = $flat[$icon];
			}
		}
		ksort($conflicting);

		$this->set(compact('conflicting'));
	}

}

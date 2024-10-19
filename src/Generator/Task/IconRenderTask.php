<?php declare(strict_types=1);

namespace Templating\Generator\Task;

use Cake\Core\Configure;
use Cake\View\View;
use IdeHelper\Generator\Directive\ExpectedArguments;
use IdeHelper\Generator\Directive\RegisterArgumentsSet;
use IdeHelper\Generator\Task\TaskInterface;
use Templating\View\Helper\IconHelper;

class IconRenderTask implements TaskInterface {

	public const CLASS_ICON_HELPER = IconHelper::class;

	/**
	 * @var string
	 */
	public const SET_ICONS = 'icons';

	/**
	 * @var array<string, mixed>
	 */
	protected $config;

	/**
	 * @param array|null $config
	 */
	public function __construct(?array $config = null) {
		if ($config === null) {
			$config = Configure::read('Icon') ?: [];
		}

		$this->config = $config;
	}

	/**
	 * @return array<\IdeHelper\Generator\Directive\BaseDirective>
	 */
	public function collect(): array {
		$result = [];

		$icons = $this->collectIcons();
		$list = [];
		foreach ($icons as $icon) {
			$list[$icon] = '\'' . $icon . '\'';
		}

		ksort($list);

		$registerArgumentsSet = new RegisterArgumentsSet(static::SET_ICONS, $list);
		$result[$registerArgumentsSet->key()] = $registerArgumentsSet;

		$method = '\\' . static::CLASS_ICON_HELPER . '::render()';
		$directive = new ExpectedArguments($method, 0, [(string)$registerArgumentsSet]);
		$result[$directive->key()] = $directive;

		return $result;
	}

	/**
	 * Collects all icons.
	 *
	 * @return array<string>
	 */
	protected function collectIcons(): array {
		$helper = new IconHelper(new View(), $this->config);
		$autoPrefixed = $helper->getConfig('autoPrefix');
		$configured = $helper->getConfig('map') ?: [];
		/** @var array<string> $configured */
		$configured = array_keys($configured);

		$icons = [];
		foreach ($configured as $icon) {
			$icons[$icon] = $icon;
		}

		$names = $helper->names();

		$separator = $helper->getConfig('separator');
		foreach ($names as $setName => $setList) {
			foreach ($setList as $icon) {
				if ($autoPrefixed !== false && !isset($icons[$icon])) {
					$icons[$icon] = $icon;
				}

				// namespaced fallback
				$name = $setName . $separator . $icon;
				$icons[$name] = $name;
			}
		}

		ksort($icons);

		return $icons;
	}

}

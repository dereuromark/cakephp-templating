<?php declare(strict_types=1);

namespace Templating\View\Icon;

use Cake\Cache\Cache;
use Cake\Core\InstanceConfigTrait;
use Cake\Utility\Inflector;
use Exception;
use RuntimeException;
use Templating\View\HtmlStringable;

class IconCollection {

	use InstanceConfigTrait;

	/**
	 * @var array<string, mixed>
	 */
	protected array $_defaultConfig = [
		'cache' => null,
	];

	/**
	 * @var string
	 */
	protected $defaultSet;

	/**
	 * @var array<string, \Templating\View\Icon\IconInterface>
	 */
	protected array $iconSets = [];

	/**
	 * @var array|null
	 */
	protected $names;

	/**
	 * @var array|null
	 */
	protected $map;

	/**
	 * @param array<string, mixed> $config
	 */
	public function __construct(array $config = []) {
		/** @var array<class-string<\Templating\View\Icon\IconInterface>|array<string, mixed>> $sets */
		$sets = $config['sets'] ?? [];
		unset($config['sets']);

		foreach ($sets as $set => $setConfig) {
			if (is_string($setConfig)) {
				$setConfig = [
					'class' => $setConfig,
				];
			} else {
				if (empty($setConfig['class'])) {
					throw new RuntimeException('You must define a `class` for each icon set.');
				}
			}

			/** @var class-string<\Templating\View\Icon\IconInterface> $className */
			$className = $setConfig['class'];
			if (isset($config['attributes']) && isset($setConfig['attributes'])) {
				$setConfig['attributes'] += $config['attributes'];
			}
			$setConfig += $config;
			$this->iconSets[$set] = new $className($setConfig);
		}

		/** @var string|null $key */
		$key = array_key_first($sets);
		if (!$key) {
			throw new RuntimeException('No set defined for icon collection, at least one is required.');
		}

		$this->defaultSet = $key;

		$this->setConfig($config);
		$this->buildMap();
	}

	/**
	 * @param bool $sort
	 * @return array<string, array<string>>
	 */
	public function names(bool $sort = false): array {
		if ($this->names === null) {
			$cache = $this->getConfig('cache') ?? 'default';
			if ($cache) {
				$cacheKey = 'icon-collection-' . md5(serialize($this->_config));
				$result = Cache::read($cacheKey, $cache);
				if ($result) {
					$this->names = $result;
				}
			}
		}

		if ($this->names !== null) {
			$names = $this->names;
			if ($sort) {
				ksort($names);
			}

			return $names;
		}

		$names = [];
		foreach ($this->iconSets as $name => $set) {
			$iconNames = $set->names();
			$names[$name] = $iconNames;
		}

		$cache = $this->getConfig('cache') ?? 'default';
		if ($cache) {
			$cacheKey = 'icon-collection-' . md5(serialize($this->_config));
			Cache::write($cacheKey, $names, $cache);
		}

		$this->names = $names;

		if ($sort) {
			ksort($names);
		}

		return $names;
	}

	/**
	 * Icons using the default namespace or an already prefixed one.
	 *
	 * @param string $icon Icon name, prefixed for non default namespace
	 * @param array<string, mixed> $options :
	 * - translate, titleField, ...
	 * @param array<string, mixed> $attributes :
	 * - class, title, ...
	 *
	 * If no title is given, it will auto-create one from the icon name (or alias).
	 *
	 * @return \Templating\View\HtmlStringable
	 */
	public function render(string $icon, array $options = [], array $attributes = []): HtmlStringable {
		$iconName = null;
		$separator = $this->_config['separator'];
		if (!str_contains($icon, $separator) && isset($this->map[$icon])) {
			$iconName = $icon;
			$icon = $this->map[$icon];
		}

		$separatorPos = strpos($icon, $separator);
		if ($separatorPos !== false) {
			[$set, $icon] = explode($separator, $icon, 2);
		} else {
			$set = $this->defaultSet;
		}

		if (!isset($this->iconSets[$set])) {
			throw new RuntimeException('No such icon namespace: `' . $set . '`.');
		}

		$options += $this->_config;
		if (!isset($options['title']) || $options['title'] !== false) {
			/** @var string $titleField */
			$titleField = $options['titleField'] ?? 'title';
			if (isset($options['title']) && $options['title'] !== true) {
				trigger_error('Deprecated. Use `$attributes` here instead. For custom title field use `titleField` config key.', E_USER_DEPRECATED);
				$attributes[$titleField] = $options['title'];
			} else {
				// Handle explicit false in attributes to disable title
				if (isset($attributes[$titleField]) && $attributes[$titleField] === false) {
					unset($attributes[$titleField]);
				} elseif (!isset($attributes[$titleField])) {
					$attributes[$titleField] = ucwords(Inflector::humanize(Inflector::underscore($iconName ?? $icon)));
				}
				// Only translate if attribute exists and is not null/false
				if (isset($attributes[$titleField]) && $attributes[$titleField] !== false) {
					if (!isset($options['translate']) || $options['translate'] !== false) {
						$attributes[$titleField] = __($attributes[$titleField]);
					}
				}
			}
		}

		unset($options['attributes']);
		if ($this->getConfig('checkExistence') && !$this->exists($icon, $set)) {
			trigger_error(sprintf('Icon `%s` does not exist', $set . ':' . $icon), E_USER_WARNING);
		}

		return $this->iconSets[$set]->render($icon, $options, $attributes);
	}

	/**
	 * @param string $icon
	 * @param string $set
	 *
	 * @return bool
	 */
	protected function exists(string $icon, string $set): bool {
		$names = $this->names();

		return !empty($names[$set]) && in_array($icon, $names[$set]);
	}

	/**
	 * @return void
	 */
	protected function buildMap(): void {
		$this->map = $this->_config['map'] ?? [];

		if (isset($this->_config['autoPrefix']) && $this->_config['autoPrefix'] === false) {
			return;
		}

		try {
			$names = $this->names();
		} catch (Exception $e) {
			if (!$this->getConfig('checkExistence')) {
				return;
			}

			throw $e;
		}

		foreach ($names as $set => $icons) {
			foreach ($icons as $icon) {
				if (isset($this->map[$icon])) {
					continue;
				}

				$this->map[$icon] = $set . ':' . $icon;
			}
		}
	}

}

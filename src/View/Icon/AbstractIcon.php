<?php declare(strict_types=1);

namespace Templating\View\Icon;

use Cake\View\StringTemplate;
use RuntimeException;
use Templating\View\HtmlStringable;

abstract class AbstractIcon implements IconInterface {

	/**
	 * @var \Cake\View\StringTemplate
	 */
	protected $template;

	/**
	 * @var array<string, mixed>
	 */
	protected $config;

	/**
	 * @param array<string, mixed> $config
	 */
	public function __construct(array $config = []) {
		$this->template = new StringTemplate(['icon' => $config['template']]);
		$this->config = $config;
	}

	/**
	 * @return string
	 */
	protected function path(): string {
		$path = $this->config['path'] ?? null;
		if ($path && file_exists($path)) {
			return $path;
		}

		$svgPath = $this->config['svgPath'] ?? null;
		if ($svgPath && is_dir($svgPath)) {
			return $svgPath;
		}

		if (!$path && !$svgPath) {
			throw new RuntimeException('You need to define a meta data file path or SVG directory path for `' . static::class . '` in order to get icon names.');
		}

		if ($path && !file_exists($path)) {
			throw new RuntimeException('Cannot find meta data file path `' . $path . '` for `' . static::class . '`.');
		}

		if ($svgPath && !is_dir($svgPath)) {
			throw new RuntimeException('SVG path `' . $svgPath . '` is not a directory for `' . static::class . '`.');
		}

		throw new RuntimeException('No valid path configuration found for `' . static::class . '`.');
	}

	/**
	 * @param array<string, mixed> $options
	 *
	 * @return \Templating\View\HtmlStringable
	 */
	protected function format(array $options): HtmlStringable {
		$icon = $this->template->format('icon', $options);

		return $this->wrap($icon);
	}

	/**
	 * @param string $icon
	 *
	 * @return \Templating\View\HtmlStringable
	 */
	protected function wrap(string $icon): HtmlStringable {
		return Icon::create($icon);
	}

}

<?php

namespace Templating\View\Icon;

use Cake\View\StringTemplate;
use RuntimeException;
use Templating\View\Html\HtmlStringable;

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
		if (!$path) {
			throw new RuntimeException('You need to define a meta data file path for `' . static::class . '` in order to get icon names.');
		}
		if (!file_exists($path)) {
			throw new RuntimeException('Cannot find meta data file path `' . $path . '` for `' . static::class . '`.');
		}

		return $path;
	}

	/**
	 * @param array $options
	 *
	 * @return \Templating\View\Html\HtmlStringable
	 */
	protected function format(array $options): HtmlStringable {
		$icon = $this->template->format('icon', $options);

		return $this->wrap($icon);
	}

	/**
	 * @param string $icon
	 *
	 * @return \Templating\View\Html\HtmlStringable
	 */
	protected function wrap(string $icon): HtmlStringable {
		return Icon::create($icon);
	}

}

<?php

namespace Templating\View\Icon;

use Templating\View\Html\HtmlStringable;
use Templating\View\Icon\Collector\FontAwesome4IconCollector;

class FontAwesome4Icon extends AbstractIcon {

	/**
	 * @param array<string, mixed> $config
	 */
	public function __construct(array $config = []) {
		$config += [
			'template' => '<span class="{{class}}"{{attributes}}></span>',
		];

		parent::__construct($config);
	}

	/**
	 * @return array<string>
	 */
	public function names(): array {
		$path = $this->path();

		return FontAwesome4IconCollector::collect($path);
	}

	/**
	 * @param string $icon
	 * @param array $options
	 * @param array $attributes
	 *
	 * @return \Templating\View\Html\HtmlStringable
	 */
	public function render(string $icon, array $options = [], array $attributes = []): HtmlStringable {
		if (!empty($this->config['attributes'])) {
			$attributes += $this->config['attributes'];
		}

		$namespace = 'fa';

		$class = [
			$namespace,
		];
		if (!empty($options['extra'])) {
			foreach ($options['extra'] as $i) {
				$class[] = $namespace . '-' . $i;
			}
		}
		if (!empty($options['size'])) {
			$class[] = $namespace . '-' . ($options['size'] === 'large' ? 'large' : $options['size'] . 'x');
		}
		if (!empty($options['pull'])) {
			$class[] = 'pull-' . $options['pull'];
		}
		if (!empty($options['rotate'])) {
			$class[] = $namespace . '-rotate-' . (int)$options['rotate'];
		}
		if (!empty($options['spin'])) {
			$class[] = $namespace . '-spin';
		}

		$options['class'] = implode(' ', $class) . ' ' . $namespace . '-' . $icon;
		if (!empty($attributes['class'])) {
			$options['class'] .= ' ' . $attributes['class'];
		}
		$options['attributes'] = $this->template->formatAttributes($attributes, ['class']);

		return $this->format($options);
	}

}

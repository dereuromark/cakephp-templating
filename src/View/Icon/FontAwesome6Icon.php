<?php declare(strict_types=1);

namespace Templating\View\Icon;

use Templating\View\HtmlStringable;
use Templating\View\Icon\Collector\FontAwesome6IconCollector;

class FontAwesome6Icon extends AbstractIcon {

	use SvgRenderTrait;

	/**
	 * @param array<string, mixed> $config
	 */
	public function __construct(array $config = []) {
		$config += [
			'template' => '<span class="{{class}}"{{attributes}}></span>',
			'namespace' => 'solid',
			'aliases' => true,
			'svgPath' => null,
			'cache' => null,
			'inline' => null,
		];

		parent::__construct($config);
	}

	/**
	 * @return array<string>
	 */
	public function names(): array {
		$path = $this->path();

		return FontAwesome6IconCollector::collect($path, $this->config);
	}

	/**
	 * @param string $icon
	 * @param array<string, mixed> $options
	 * @param array<string, mixed> $attributes
	 *
	 * @return \Templating\View\HtmlStringable
	 */
	public function render(string $icon, array $options = [], array $attributes = []): HtmlStringable {
		if (!empty($this->config['attributes'])) {
			$attributes += $this->config['attributes'];
		}

		if ($this->config['svgPath']) {
			return $this->renderSvg($icon, $attributes);
		}

		$namespace = 'fa-' . $this->config['namespace'];

		$class = [
			$namespace,
		];
		if (!empty($options['extra'])) {
			foreach ($options['extra'] as $i) {
				$class[] = 'fa-' . $i;
			}
		}
		if (!empty($options['size'])) {
			$class[] = 'fa-' . ($options['size'] === 'large' ? 'large' : $options['size'] . 'x');
		}
		if (!empty($options['pull'])) {
			$class[] = 'pull-' . $options['pull'];
		}
		if (!empty($options['rotate'])) {
			$class[] = 'fa-rotate-' . (int)$options['rotate'];
		}
		if (!empty($options['spin'])) {
			$class[] = 'fa-spin';
		}

		$options['class'] = implode(' ', $class) . ' ' . 'fa-' . $icon;
		if (!empty($attributes['class'])) {
			$options['class'] .= ' ' . $attributes['class'];
		}
		$options['attributes'] = $this->template->formatAttributes($attributes, ['class']);

		return $this->format($options);
	}

}

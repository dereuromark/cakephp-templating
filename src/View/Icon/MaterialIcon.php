<?php declare(strict_types=1);

namespace Templating\View\Icon;

use Templating\View\HtmlStringable;
use Templating\View\Icon\Collector\MaterialIconCollector;

class MaterialIcon extends AbstractIcon {

	use SvgRenderTrait;

	/**
	 * @param array<string, mixed> $config
	 */
	public function __construct(array $config = []) {
		$config += [
			'template' => '<span class="{{class}}"{{attributes}}>{{name}}</span>',
			'namespace' => 'material-icons',
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

		return MaterialIconCollector::collect($path);
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

		$options['name'] = $icon;
		$options['class'] = $this->config['namespace'];
		if (!empty($attributes['class'])) {
			$options['class'] .= ' ' . $attributes['class'];
		}
		$options['attributes'] = $this->template->formatAttributes($attributes, ['class']);

		return $this->format($options);
	}

}

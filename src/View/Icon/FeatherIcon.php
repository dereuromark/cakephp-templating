<?php declare(strict_types=1);

namespace Templating\View\Icon;

use Templating\View\HtmlStringable;
use Templating\View\Icon\Collector\FeatherIconCollector;

class FeatherIcon extends AbstractIcon {

	/**
	 * @param array<string, mixed> $config
	 */
	public function __construct(array $config = []) {
		$config += [
			'template' => '<span data-feather="{{name}}"{{attributes}}></span>',
		];

		parent::__construct($config);
	}

	/**
	 * @return array<string>
	 */
	public function names(): array {
		$path = $this->path();

		return FeatherIconCollector::collect($path);
	}

	/**
	 * @param string $icon
	 * @param array $options
	 * @param array $attributes
	 *
	 * @return \Templating\View\HtmlStringable
	 */
	public function render(string $icon, array $options = [], array $attributes = []): HtmlStringable {
		if (!empty($this->config['attributes'])) {
			$attributes += $this->config['attributes'];
		}

		$options['name'] = $icon;
		$options['attributes'] = $this->template->formatAttributes($attributes);

		return $this->format($options);
	}

}

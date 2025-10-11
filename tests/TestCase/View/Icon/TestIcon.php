<?php declare(strict_types=1);

namespace Templating\Test\TestCase\View\Icon;

use Templating\View\HtmlStringable;
use Templating\View\Icon\AbstractIcon;

/**
 * Test concrete implementation of AbstractIcon for testing purposes
 */
class TestIcon extends AbstractIcon {

	/**
	 * @param array<string, mixed> $config
	 */
	public function __construct(array $config = []) {
		$config += [
			'template' => '<span class="icon {{name}}"{{attributes}}></span>',
		];
		parent::__construct($config);
	}

	/**
	 * @return array<string>
	 */
	public function names(): array {
		return ['test-icon', 'another-icon'];
	}

	/**
	 * @param string $icon
	 * @param array<string, mixed> $options
	 * @param array<string, mixed> $attributes
	 *
	 * @return \Templating\View\HtmlStringable
	 */
	public function render(string $icon, array $options = [], array $attributes = []): HtmlStringable {
		$options['name'] = $icon;
		$options['attributes'] = $this->template->formatAttributes($attributes);

		return $this->format($options);
	}

}

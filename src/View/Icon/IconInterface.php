<?php

namespace Templating\View\Icon;

use Templating\View\Html\HtmlStringable;

interface IconInterface {

	/**
	 * @return array<string>
	 */
	public function names(): array;

	/**
	 * Icon formatting using the specific engine.
	 *
	 * @param string $icon Icon name
	 * @param array<string, mixed> $options :
	 * - translate, title, ...
	 * @param array<string, mixed> $attributes :
	 * - class, ...
	 *
	 * @return \Templating\View\Html\HtmlStringable
	 */
	public function render(string $icon, array $options = [], array $attributes = []): HtmlStringable;

}

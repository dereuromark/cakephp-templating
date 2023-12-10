<?php

namespace Templating\View\Helper;

use Templating\View\Html\HtmlStringable;

trait FormTrait {

	/**
	 * @param \Templating\View\Html\HtmlStringable|string $title
	 * @param array<string, mixed> $options
	 *
	 * @return string
	 */
	public function button(string|HtmlStringable $title, array $options = []): string {
		if ($title instanceof HtmlStringable) {
			$options['escapeTitle'] = false;
			$title = (string)$title;
		}

		return parent::button($title, $options);
	}

	/**
	 * @param \Templating\View\Html\HtmlStringable|string $title
	 * @param array|string|null $url
	 * @param array<string, mixed> $options
	 *
	 * @return string
	 */
	public function postLink(string|HtmlStringable $title, array|string|null $url = null, array $options = []): string {
		if ($title instanceof HtmlStringable) {
			$options['escapeTitle'] = false;
			$title = (string)$title;
		}

		return parent::postLink($title, $url, $options);
	}

	/**
	 * @param \Templating\View\Html\HtmlStringable|string $title
	 * @param array|string $url
	 * @param array<string, mixed> $options
	 *
	 * @return string
	 */
	public function postButton(string|HtmlStringable $title, array|string $url, array $options = []): string {
		if ($title instanceof HtmlStringable) {
			$options['escapeTitle'] = false;
			$title = (string)$title;
		}

		return parent::postButton($title, $url, $options);
	}

}

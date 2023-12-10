<?php

namespace Templating\View\Helper;

use Templating\View\Html\HtmlStringable;

trait HtmlTrait {

	/**
	 * @param \Templating\View\Html\HtmlStringable|array|string $title
	 * @param array|string|null $url
	 * @param array $options
	 *
	 * @return string
	 */
	public function link(array|string|HtmlStringable $title, array|string|null $url = null, array $options = []): string {
		if ($title instanceof HtmlStringable) {
			$options['escapeTitle'] = false;
			$title = (string)$title;
		}

		return parent::link($title, $url, $options);
	}

	/**
	 * @param \Templating\View\Html\HtmlStringable|string $title
	 * @param string $path
	 * @param array $params
	 * @param array $options
	 *
	 * @return string
	 */
	public function linkFromPath(string|HtmlStringable $title, string $path, array $params = [], array $options = []): string {
		if ($title instanceof HtmlStringable) {
			$options['escapeTitle'] = false;
			$title = (string)$title;
		}

		return parent::linkFromPath($title, $path, $params, $options);
	}

}

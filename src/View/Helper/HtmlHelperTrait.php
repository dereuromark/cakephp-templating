<?php declare(strict_types=1);

namespace Templating\View\Helper;

use Templating\View\Html;
use Templating\View\HtmlStringable;

trait HtmlHelperTrait {

	/**
	 * @param \Templating\View\HtmlStringable|array|string $title
	 * @param array|string|null $url
	 * @param array<string, mixed> $options
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
	 * @param \Templating\View\HtmlStringable|string $title
	 * @param string $path
	 * @param array $params
	 * @param array<string, mixed> $options
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

	/**
	 * Convenience method to generate a HTML text snippet.
	 *
	 * @param string $string
	 *
	 * @return \Templating\View\HtmlStringable
	 */
	public function string(string $string): HtmlStringable {
		return Html::create($string);
	}

}

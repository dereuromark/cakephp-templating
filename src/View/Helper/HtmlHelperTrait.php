<?php declare(strict_types=1);

namespace Templating\View\Helper;

use Templating\View\Html;
use Templating\View\HtmlStringable;

trait HtmlHelperTrait {

	/**
	 * @param \Templating\View\HtmlStringable|array|string $content
	 * @param array|string|null $url
	 * @param array<string, mixed> $options
	 *
	 * @return string
	 */
	public function link(HtmlStringable|array|string $content, array|string|null $url = null, array $options = []): string {
		if ($content instanceof HtmlStringable) {
			$options['escape'] = false;
			$content = (string)$content;
		}

		return parent::link($content, $url, $options);
	}

	/**
	 * @param \Templating\View\HtmlStringable|string $content
	 * @param string $path
	 * @param array $params
	 * @param array<string, mixed> $options
	 *
	 * @return string
	 */
	public function linkFromPath(HtmlStringable|string $content, string $path, array $params = [], array $options = []): string {
		if ($content instanceof HtmlStringable) {
			$options['escape'] = false;
			$content = (string)$content;
		}

		return parent::linkFromPath($content, $path, $params, $options);
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

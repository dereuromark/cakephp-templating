<?php declare(strict_types=1);

namespace Templating\View\Helper;

use Templating\View\HtmlStringable;

trait FormHelperTrait {

	/**
	 * @param \Templating\View\HtmlStringable|string $content
	 * @param array<string, mixed> $options
	 *
	 * @return string
	 */
	public function button(HtmlStringable|string $content, array $options = []): string {
		if ($content instanceof HtmlStringable) {
			$options['escape'] = false;
			$content = (string)$content;
		}

		return parent::button($content, $options);
	}

	/**
	 * @param \Templating\View\HtmlStringable|string $content
	 * @param array|string|null $url
	 * @param array<string, mixed> $options
	 *
	 * @return string
	 */
	public function postLink(HtmlStringable|string $content, array|string|null $url = null, array $options = []): string {
		if ($content instanceof HtmlStringable) {
			$options['escape'] = false;
			$content = (string)$content;
		}

		return parent::postLink($content, $url, $options);
	}

	/**
	 * @param \Templating\View\HtmlStringable|string $content
	 * @param array|string $url
	 * @param array<string, mixed> $options
	 *
	 * @return string
	 */
	public function postButton(HtmlStringable|string $content, array|string $url, array $options = []): string {
		if ($content instanceof HtmlStringable) {
			$options['escape'] = false;
			$content = (string)$content;
		}

		return parent::postButton($content, $url, $options);
	}

}

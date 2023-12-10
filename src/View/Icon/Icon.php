<?php

namespace Templating\View\Icon;

use Templating\View\Html\HtmlStringable;

class Icon implements HtmlStringable {

	/**
	 * @var string
	 */
	protected string $html;

	/**
	 * Use as Icon::create($html) instead.
	 *
	 * @param string $html
	 */
	protected function __construct(string $html) {
		$this->html = $html;
	}

	/**
	 * @param string $html
	 *
	 * @return \Templating\View\Html\HtmlStringable
	 */
	public static function create(string $html): HtmlStringable {
		return new static($html);
	}

	/**
	 * @return string
	 */
	public function __toString(): string {
		return $this->html;
	}

}

<?php declare(strict_types=1);

namespace Templating\View;

use InvalidArgumentException;
use JsonSerializable;

/**
 * Html value object
 */
class Html implements HtmlStringable, JsonSerializable {

	/**
	 * @var string
	 */
	protected string $html;

	/**
	 * Use as Html::create($html) instead.
	 *
	 * @param string $html
	 */
	protected function __construct(string $html) {
		$this->html = $html;
	}

	/**
	 * @param string $html
	 *
	 * @return \Templating\View\HtmlStringable
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

	/**
	 * @return array<string, string>
	 */
	public function __serialize(): array {
		return ['html' => $this->html];
	}

	/**
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function __unserialize(array $data): void {
		if (!isset($data['html']) || !is_string($data['html'])) {
			throw new InvalidArgumentException('Invalid value passed to `__unserialize()`.');
		}

		$this->html = $data['html'];
	}

	/**
	 * @return mixed
	 */
	public function jsonSerialize(): mixed {
		return $this->html;
	}

}

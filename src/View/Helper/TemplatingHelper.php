<?php declare(strict_types=1);

namespace Templating\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;
use Cake\View\StringTemplate;
use Cake\View\View;
use Templating\View\HtmlStringable;

/**
 * Useful templating functionality.
 *
 * @author Mark Scherer
 * @license MIT
 * @property \Cake\View\Helper\HtmlHelper $Html
 * @property \Templating\View\Helper\IconSnippetHelper $IconSnippet
 */
class TemplatingHelper extends Helper {

	/**
	 * @var array
	 */
	protected array $helpers = [
		'Html',
		'Templating.IconSnippet',
	];

	/**
	 * @var \Cake\View\StringTemplate
	 */
	protected $template;

	/**
	 * @var array
	 */
	protected array $_defaults = [
		'templates' => [
			'ok' => '<span class="ok-{{type}}" style="color:{{color}}"{{attributes}}>{{content}}</span>',
		],
	];

	/**
	 * @param \Cake\View\View $View
	 * @param array<string, mixed> $config
	 */
	public function __construct(View $View, array $config = []) {
		$defaults = (array)Configure::read('Templating') + $this->_defaults;
		$config += $defaults;

		$this->template = new StringTemplate($config['templates']);

		parent::__construct($View, $config);
	}

	/**
	 * Returns red/warning colored if not ok.
	 *
	 * @param \Templating\View\HtmlStringable|string $content
	 * @param bool $ok Boolish value
	 * @param array<string, mixed> $attributes
	 *
	 * @return string Value in HTML tags
	 */
	public function warning(string|HtmlStringable $content, bool $ok = false, array $attributes = []): string {
		if (!$ok) {
			return $this->ok($content, false, $attributes);
		}

		$escape = true;
		if (isset($attributes['escape'])) {
			$escape = $attributes['escape'] !== false;

			unset($attributes['escape']);
		}
		if (is_string($content) && $escape) {
			$content = h($content);
		}

		return (string)$content;
	}

	/**
	 * Returns green on ok, red otherwise
	 *
	 * By default, this method escapes the content.
	 * Use attributes and `escape` set to false to disable escaping.
	 *
	 * @param \Templating\View\HtmlStringable|string $content Output
	 * @param bool $ok Boolish value
	 * @param array<string, mixed> $attributes
	 * @return string Value nicely formatted/colored
	 */
	public function ok(string|HtmlStringable $content, bool $ok = false, array $attributes = []): string {
		if ($ok) {
			$type = 'yes';
			$color = 'green';
		} else {
			$type = 'no';
			$color = 'red';
		}

		$escape = true;
		if (isset($attributes['escape'])) {
			$escape = $attributes['escape'] !== false;

			unset($attributes['escape']);
		}
		if (is_string($content) && $escape) {
			$content = h($content);
		}

		$options = [
			'type' => $type,
			'color' => $color,
		];
		$options['content'] = $content;
		$options['attributes'] = $this->template->formatAttributes($attributes);

		return $this->template->format('ok', $options);
	}

	/**
	 * Convenience method: Returns yes/no icon - green on ok, red otherwise.
	 *
	 * Make sure to configure these (font) icons in your `Icon.map` app config, e.g.
	 *     'yes' => 'fa4:check',
	 *     'no' => 'fa4:times',
	 *
	 * @param mixed $value Value being internally bool casted.
	 * @param array<string, mixed> $options
	 *  - onTitle
	 *  - offTitle
	 *  - invert: If true, green for no, red for yes.
	 * @param array<string, mixed> $attributes
	 *  - title, ...
	 * @return string Value nicely formatted/colored
	 */
	public function yesNo(mixed $value, array $options = [], array $attributes = []): string {
		$invert = !empty($options['invert']);
		unset($options['invert']);

		$value = (bool)$value;
		$ok = $value;
		if ($invert) {
			$ok = !$ok;
		}

		$iconAttributes = $attributes;
		unset($attributes['title']);

		return $this->ok($this->IconSnippet->yesNo($value, $options, $iconAttributes), $ok, $attributes);
	}

}

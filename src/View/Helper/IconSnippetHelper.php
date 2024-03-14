<?php declare(strict_types=1);

namespace Templating\View\Helper;

use Cake\Utility\Text;
use Cake\View\Helper;
use Templating\View\HtmlStringable;

/**
 * Font icon rendering.
 *
 * @author Mark Scherer
 * @license MIT
 * @property \Templating\View\Helper\IconHelper $Icon
 * @property \Cake\View\Helper\HtmlHelper $Html
 */
class IconSnippetHelper extends Helper {

	/**
	 * @var array
	 */
	protected array $helpers = [
		'Html',
		'Templating.Icon',
	];

	/**
	 * Displays yes/no symbol.
	 *
	 * Make sure to configure these (font) icons in your `Icon.map` app config, e.g.
	 *   'yes' => 'fa4:check',
	 *   'no' => 'fa4:times',
	 *
	 * @param int|bool $value Value
	 * @param array<string, mixed> $options
	 * - on (defaults to 1/true)
	 * - onTitle
	 * - offTitle
	 * @param array<string, mixed> $attributes
	 * - title, ...
	 *
	 * @return \Templating\View\HtmlStringable HTML icon Yes/No
	 */
	public function yesNo($value, array $options = [], array $attributes = []): HtmlStringable {
		$defaults = [
			'on' => 1,
			'onTitle' => __d('template', 'Yes'),
			'offTitle' => __d('template', 'No'),
		];
		$options += $defaults;

		if ($value == $options['on']) {
			$icon = 'yes';
			$value = 'on';
		} else {
			$icon = 'no';
			$value = 'off';
		}

		$attributes += ['title' => $options[$value . 'Title']];

		return $this->Icon->render($icon, $options, $attributes);
	}

	/**
	 * Make sure to configure these (font) icons in your `Icon.map` app config, e.g.
	 *   'pro' => 'fa4:thumbs-up',
	 *   'contra' => 'fa4:thumbs-down',
	 *
	 * @param mixed $value Boolish value
	 * @param array<string, mixed> $options
	 * @param array<string, mixed> $attributes
	 *
	 * @return \Templating\View\HtmlStringable
	 */
	public function thumbs($value, array $options = [], array $attributes = []): HtmlStringable {
		$icon = !empty($value) ? 'pro' : 'contra';

		return $this->Icon->render($icon, $options, $attributes);
	}

	/**
	 * Display neighbor quicklinks
	 *
	 * Make sure to configure these (font) icons in your `Icon.map` app config, e.g.
	 *   'prev' => 'fa4:arrow-left',
	 *   'next' => 'fa4:arrow-right',
	 *
	 * @param array $neighbors (containing prev and next)
	 * @param string $field Field as `Field` or `Model.field` syntax
	 * @param array<string, mixed> $options :
	 * - name: title name: next{Record} (if none is provided, "record" is used - not translated!)
	 * - slug: true/false (defaults to false)
	 * - titleField: field or `Model.field`
	 *
	 * @return string
	 */
	public function neighbors(array $neighbors, string $field, array $options = []): string {
		$name = 'Record'; // Translation further down!
		if (!empty($options['name'])) {
			$name = ucfirst($options['name']);
		}

		$prevSlug = $nextSlug = null;
		if (!empty($options['slug'])) {
			if (!empty($neighbors['prev'])) {
				$prevSlug = Text::slug($neighbors['prev'][$field]);
			}
			if (!empty($neighbors['next'])) {
				$nextSlug = Text::slug($neighbors['next'][$field]);
			}
		}
		$titleField = $field;
		if (!empty($options['titleField'])) {
			$titleField = $options['titleField'];
		}

		$ret = '<div class="next-prev-navi">';
		if (!empty($neighbors['prev'])) {
			$url = [$neighbors['prev']['id'], $prevSlug];
			if (!empty($options['url'])) {
				$url += $options['url'];
			}

			$ret .= $this->Html->link(
				$this->Icon->render('prev') . '&nbsp;' . __d('template', 'prev' . $name),
				$url,
				['escape' => false, 'title' => $neighbors['prev'][$titleField]],
			);
		} else {
			$ret .= $this->Icon->render('prev');
		}

		$ret .= '&nbsp;&nbsp;';
		if (!empty($neighbors['next'])) {
			$url = [$neighbors['next']['id'], $nextSlug];
			if (!empty($options['url'])) {
				$url += $options['url'];
			}

			$ret .= $this->Html->link(
				$this->Icon->render('next') . '&nbsp;' . __d('template', 'next' . $name),
				$url,
				['escape' => false, 'title' => $neighbors['next'][$titleField]],
			);
		} else {
			$ret .= $this->Icon->render('next') . '&nbsp;' . __d('template', 'next' . $name);
		}

		$ret .= '</div>';

		return $ret;
	}

}

<?php declare(strict_types=1);

namespace Templating\View\Icon;

use RuntimeException;
use Templating\View\HtmlStringable;
use Templating\View\Icon\Collector\HeroiconsIconCollector;

class HeroiconsIcon extends AbstractIcon {

	use SvgRenderTrait;

	/**
	 * Allowed Heroicons style sub-directories.
	 *
	 * Anchored against the directories Heroicons ships (incl. v2 sized
	 * variants). Keeps the `style` config value out of free-form path
	 * concatenation and prevents traversal via misconfiguration.
	 *
	 * @var array<int, string>
	 */
	protected const ALLOWED_STYLES = [
		'outline',
		'solid',
		'mini',
		'24/outline',
		'24/solid',
		'20/solid',
		'16/solid',
	];

	/**
	 * @param array<string, mixed> $config
	 */
	public function __construct(array $config = []) {
		$config += [
			'template' => '<span class="{{class}}"{{attributes}}></span>',
			'style' => 'outline',
			'svgPath' => null,
			'cache' => null,
			'inline' => null,
		];

		parent::__construct($config);
	}

	/**
	 * @return array<string>
	 */
	public function names(): array {
		$path = $this->path();

		return HeroiconsIconCollector::collect($path);
	}

	/**
	 * @param string $icon
	 * @param array<string, mixed> $options
	 * @param array<string, mixed> $attributes
	 *
	 * @return \Templating\View\HtmlStringable
	 */
	public function render(string $icon, array $options = [], array $attributes = []): HtmlStringable {
		if (!empty($this->config['attributes'])) {
			$attributes += $this->config['attributes'];
		}

		if ($this->resolveSvgPath()) {
			return $this->renderSvg($icon, $attributes);
		}

		$options['class'] = 'heroicon-' . $this->config['style'];
		if (!empty($attributes['class'])) {
			$options['class'] .= ' ' . $attributes['class'];
		}
		$options['attributes'] = $this->template->formatAttributes($attributes, ['class']);

		return $this->format($options);
	}

	/**
	 * Get the full path to the SVG file, including style subdirectory
	 *
	 * @param string $icon
	 *
	 * @return string
	 */
	protected function getSvgPath(string $icon): string {
		$basePath = $this->resolveSvgPath();
		if (!$basePath) {
			throw new RuntimeException('SVG path not configured. Set `svgPath` in configuration.');
		}

		$this->assertSafeIconName($icon);

		$style = (string)$this->config['style'];
		if (!in_array($style, static::ALLOWED_STYLES, true)) {
			throw new RuntimeException(sprintf('Invalid Heroicons style: `%s`.', $style));
		}

		$basePath = rtrim((string)$basePath, '/');

		return $this->confineToBasePath($basePath, $basePath . '/' . $style . '/' . $icon . '.svg');
	}

}

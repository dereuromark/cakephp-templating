<?php declare(strict_types=1);

namespace Templating\View\Icon;

use Templating\View\HtmlStringable;
use Templating\View\Icon\Collector\BootstrapIconCollector;

class BootstrapIcon extends AbstractIcon {

	/**
	 * @param array<string, mixed> $config
	 */
	public function __construct(array $config = []) {
		$config += [
			'template' => '<span class="{{class}}"{{attributes}}></span>',
			'svgPath' => null,
		];

		parent::__construct($config);
	}

	/**
	 * @return array<string>
	 */
	public function names(): array {
		$path = $this->path();

		return BootstrapIconCollector::collect($path);
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

		if ($this->config['svgPath']) {
			return $this->renderSvg($icon, $attributes);
		}

		$options['class'] = 'bi bi-' . $icon;
		if (!empty($attributes['class'])) {
			$options['class'] .= ' ' . $attributes['class'];
		}
		$options['attributes'] = $this->template->formatAttributes($attributes, ['class']);

		return $this->format($options);
	}

	/**
	 * Render SVG icon inline
	 *
	 * @param string $icon
	 * @param array<string, mixed> $attributes
	 *
	 * @return \Templating\View\HtmlStringable
	 */
	protected function renderSvg(string $icon, array $attributes = []): HtmlStringable {
		$svgPath = $this->getSvgPath($icon);

		if (!file_exists($svgPath)) {
			throw new \RuntimeException(sprintf('SVG icon file not found: %s', $svgPath));
		}

		$svgContent = file_get_contents($svgPath);
		if ($svgContent === false) {
			throw new \RuntimeException(sprintf('Failed to read SVG icon file: %s', $svgPath));
		}

		// Add custom attributes to the SVG element
		if ($attributes) {
			$svgContent = $this->addAttributesToSvg($svgContent, $attributes);
		}

		return $this->wrap($svgContent);
	}

	/**
	 * Get the full path to the SVG file
	 *
	 * @param string $icon
	 *
	 * @return string
	 */
	protected function getSvgPath(string $icon): string {
		$basePath = $this->config['svgPath'];
		if (!$basePath) {
			throw new \RuntimeException('SVG path not configured. Set `svgPath` in configuration.');
		}

		return rtrim($basePath, '/') . '/' . $icon . '.svg';
	}

	/**
	 * Add custom attributes to SVG element
	 *
	 * @param string $svgContent
	 * @param array<string, mixed> $attributes
	 *
	 * @return string
	 */
	protected function addAttributesToSvg(string $svgContent, array $attributes): string {
		// Parse existing SVG tag to merge attributes
		if (preg_match('/<svg([^>]*)>/', $svgContent, $matches)) {
			$existingAttributes = $matches[1];
			$attributeString = $this->template->formatAttributes($attributes);

			// If there's a class attribute in custom attributes, merge it with existing class
			if (!empty($attributes['class']) && preg_match('/class="([^"]*)"/', $existingAttributes, $classMatches)) {
				$existingClass = $classMatches[1];
				$newClass = $existingClass . ' ' . $attributes['class'];
				$existingAttributes = preg_replace('/class="[^"]*"/', 'class="' . $newClass . '"', $existingAttributes);
				$attributeString = $this->template->formatAttributes($attributes, ['class']);
			}

			$svgContent = (string)preg_replace('/<svg[^>]*>/', '<svg' . $existingAttributes . $attributeString . '>', $svgContent, 1);
		}

		return $svgContent;
	}

}

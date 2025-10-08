<?php declare(strict_types=1);

namespace Templating\View\Icon;

use Cake\Cache\Cache;
use Templating\View\HtmlStringable;

trait SvgRenderTrait {

	/**
	 * @var array<string, string>
	 */
	protected static array $svgCache = [];

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

		if (!isset(static::$svgCache[$svgPath])) {
			$svgContent = null;

			// Try CakePHP cache if configured
			if ($this->config['cache']) {
				$cacheKey = str_replace('\\', '_', static::class) . '_' . md5($svgPath);
				$svgContent = Cache::read($cacheKey, $this->config['cache']);
			}

			// Load from file if not in cache
			if ($svgContent === null) {
				if (!file_exists($svgPath)) {
					throw new \RuntimeException(sprintf('SVG icon file not found: %s', $svgPath));
				}

				$svgContent = file_get_contents($svgPath);
				if ($svgContent === false) {
					throw new \RuntimeException(sprintf('Failed to read SVG icon file: %s', $svgPath));
				}

				// Store in CakePHP cache if configured
				if ($this->config['cache']) {
					Cache::write($cacheKey, $svgContent, $this->config['cache']);
				}
			}

			static::$svgCache[$svgPath] = $svgContent;
		}

		$svgContent = static::$svgCache[$svgPath];

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

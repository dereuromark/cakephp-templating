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
	 * @var array<string, array<string, string>>
	 */
	protected static array $svgMapCache = [];

	/**
	 * Render SVG icon inline
	 *
	 * @param string $icon
	 * @param array<string, mixed> $attributes
	 *
	 * @return \Templating\View\HtmlStringable
	 */
	protected function renderSvg(string $icon, array $attributes = []): HtmlStringable {
		// Check if using JSON map mode
		if ($this->isJsonMapMode()) {
			return $this->renderSvgFromMap($icon, $attributes);
		}

		// Original file-based approach
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
	 * Check if JSON map mode is enabled
	 *
	 * @return bool
	 */
	protected function isJsonMapMode(): bool {
		$svgPath = $this->config['svgPath'] ?? null;

		return $svgPath && str_ends_with($svgPath, '.json');
	}

	/**
	 * Render SVG from JSON map
	 *
	 * @param string $icon
	 * @param array<string, mixed> $attributes
	 *
	 * @return \Templating\View\HtmlStringable
	 */
	protected function renderSvgFromMap(string $icon, array $attributes = []): HtmlStringable {
		$map = $this->loadSvgMap();

		if (!isset($map[$icon])) {
			throw new \RuntimeException(sprintf('SVG icon not found in map: %s', $icon));
		}

		$svgContent = $this->wrapSvgContent($map[$icon], $attributes);

		return $this->wrap($svgContent);
	}

	/**
	 * Load SVG map from JSON file
	 *
	 * @return array<string, string>
	 */
	protected function loadSvgMap(): array {
		$jsonPath = $this->config['svgPath'];
		$cacheKey = static::class . '_svg_map';

		// Check static cache
		if (isset(static::$svgMapCache[$cacheKey])) {
			return static::$svgMapCache[$cacheKey];
		}

		$map = null;

		// Try CakePHP cache if configured
		if ($this->config['cache']) {
			$map = Cache::read($cacheKey, $this->config['cache']);
		}

		// Load from JSON file if not in cache
		if ($map === null) {
			if (!file_exists($jsonPath)) {
				throw new \RuntimeException(sprintf('SVG map file not found: %s', $jsonPath));
			}

			$content = file_get_contents($jsonPath);
			if ($content === false) {
				throw new \RuntimeException(sprintf('Failed to read SVG map file: %s', $jsonPath));
			}

			$map = json_decode($content, true);
			if (!is_array($map)) {
				throw new \RuntimeException(sprintf('Invalid JSON in SVG map file: %s', $jsonPath));
			}

			// Store in CakePHP cache if configured
			if ($this->config['cache']) {
				Cache::write($cacheKey, $map, $this->config['cache']);
			}
		}

		static::$svgMapCache[$cacheKey] = $map;

		return $map;
	}

	/**
	 * Wrap SVG content from map with proper SVG tags
	 *
	 * @param string $content
	 * @param array<string, mixed> $attributes
	 *
	 * @return string
	 */
	protected function wrapSvgContent(string $content, array $attributes = []): string {
		// Get default SVG attributes from config or use defaults
		$defaultAttrs = $this->config['svgAttributes'] ?? [
			'xmlns' => 'http://www.w3.org/2000/svg',
			'width' => '24',
			'height' => '24',
			'viewBox' => '0 0 24 24',
			'fill' => 'none',
			'stroke' => 'currentColor',
			'stroke-width' => '2',
			'stroke-linecap' => 'round',
			'stroke-linejoin' => 'round',
		];

		// Merge with custom attributes
		$svgAttrs = array_merge($defaultAttrs, $attributes);

		// Build attributes string
		$attrString = $this->template->formatAttributes($svgAttrs);

		return '<svg' . $attrString . '>' . $content . '</svg>';
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

<?php declare(strict_types=1);

namespace Templating\View\Icon;

use Cake\Cache\Cache;
use Cake\Core\Configure;
use RuntimeException;
use Templating\View\HtmlStringable;

trait SvgRenderTrait {

	/**
	 * Allowed pattern for icon names used as a filesystem path component.
	 *
	 * Matches the union of identifiers shipped by the supported icon sets
	 * (Heroicons, Lucide, Feather, FontAwesome, Material). Forward slashes,
	 * backslashes, dots, and other path metacharacters are intentionally
	 * excluded to prevent path traversal via attacker-controlled icon names.
	 *
	 * @var string
	 */
	protected static string $iconNamePattern = '/^[a-zA-Z0-9][a-zA-Z0-9_\-]*$/';

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
		// Check if svgPath is configured
		if (!$this->resolveSvgPath()) {
			throw new RuntimeException('SVG path not configured. Set `svgPath` in configuration.');
		}

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
				$cacheKey = str_replace('\\', '_', static::class) . '_' . md5((string) $svgPath);
				$svgContent = Cache::read($cacheKey, $this->config['cache']);
			}

			// Load from file if not in cache
			if ($svgContent === null) {
				if (!file_exists($svgPath)) {
					throw new RuntimeException(sprintf('SVG icon file not found: %s', $svgPath));
				}

				$svgContent = file_get_contents($svgPath);
				if ($svgContent === false) {
					throw new RuntimeException(sprintf('Failed to read SVG icon file: %s', $svgPath));
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

		// Apply inlining if enabled (defaults to true in production)
		if ($this->config['inline'] ?? !Configure::read('debug', false)) {
			$svgContent = $this->inlineSvg($svgContent);
		}

		return $this->wrap($svgContent);
	}

	/**
	 * Check if JSON map mode is enabled
	 *
	 * @return bool
	 */
	protected function isJsonMapMode(): bool {
		$svgPath = $this->resolveSvgPath();

		return $svgPath && str_ends_with((string) $svgPath, '.json');
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
		$this->assertSafeIconName($icon);

		$map = $this->loadSvgMap();

		if (!isset($map[$icon])) {
			throw new RuntimeException(sprintf('SVG icon not found in map: %s', $icon));
		}

		$svgContent = $this->wrapSvgContent($map[$icon], $attributes);

		// Apply inlining if enabled (defaults to true in production)
		if ($this->config['inline'] ?? !Configure::read('debug', false)) {
			$svgContent = $this->inlineSvg($svgContent);
		}

		return $this->wrap($svgContent);
	}

	/**
	 * Load SVG map from JSON file
	 *
	 * @return array<string, string>
	 */
	protected function loadSvgMap(): array {
		$jsonPath = $this->resolveSvgPath();
		$cacheKey = static::class . '_svg_map_' . md5((string)$jsonPath);

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
			if (!$jsonPath || !file_exists($jsonPath)) {
				throw new RuntimeException(sprintf('SVG map file not found: %s', $jsonPath));
			}

			$content = file_get_contents($jsonPath);
			if ($content === false) {
				throw new RuntimeException(sprintf('Failed to read SVG map file: %s', $jsonPath));
			}

			$map = json_decode($content, true);
			if (!is_array($map)) {
				throw new RuntimeException(sprintf('Invalid JSON in SVG map file: %s', $jsonPath));
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
		$basePath = $this->resolveSvgPath();
		if (!$basePath) {
			throw new RuntimeException('SVG path not configured. Set `svgPath` in configuration.');
		}

		$this->assertSafeIconName($icon);

		$basePath = rtrim((string)$basePath, '/');

		return $this->confineToBasePath($basePath, $basePath . '/' . $icon . '.svg');
	}

	/**
	 * Validate that an icon name is safe to use as a filesystem path component.
	 *
	 * @param string $icon
	 *
	 * @throws \RuntimeException When the icon name contains disallowed characters.
	 *
	 * @return void
	 */
	protected function assertSafeIconName(string $icon): void {
		if ($icon === '' || !preg_match(static::$iconNamePattern, $icon)) {
			throw new RuntimeException(sprintf('Invalid icon name: `%s`.', $icon));
		}
	}

	/**
	 * Ensure that a resolved file path stays within the configured base directory.
	 *
	 * Uses realpath on the base directory and on the directory containing the
	 * candidate file. The candidate file itself may not yet exist, so it is
	 * intentionally not realpathed directly. If realpath of the base directory
	 * fails (e.g. it is not a real directory), the candidate is rejected.
	 *
	 * @param string $basePath
	 * @param string $candidate
	 *
	 * @throws \RuntimeException When the candidate is outside the base directory.
	 *
	 * @return string
	 */
	protected function confineToBasePath(string $basePath, string $candidate): string {
		$realBase = realpath($basePath);
		if ($realBase === false) {
			// Base directory does not exist on disk. The downstream
			// `file_exists()` check will produce the canonical
			// "SVG icon file not found" error. The icon-name allow-list
			// already prevents traversal through any non-resolvable base.
			return $candidate;
		}

		$candidateDir = dirname($candidate);
		$realCandidateDir = realpath($candidateDir);
		if ($realCandidateDir === false) {
			// Sub-directory does not (yet) exist; the downstream existence
			// check will fail. Allow-list on the icon name still guarantees
			// no traversal segments were spliced into the path.
			return $candidate;
		}

		$separator = DIRECTORY_SEPARATOR;
		if ($realCandidateDir !== $realBase && !str_starts_with($realCandidateDir . $separator, $realBase . $separator)) {
			throw new RuntimeException(sprintf('Resolved SVG path escapes base directory: `%s`.', $candidate));
		}

		return $candidate;
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
			$existingAttributesString = trim($matches[1]);
			$existingAttributes = $this->parseAttributesFromString($existingAttributesString);

			$mergedAttributes = $existingAttributes;
			foreach ($attributes as $key => $value) {
				if ($key === 'class' && isset($existingAttributes['class'])) {
					$mergedAttributes['class'] = trim($existingAttributes['class'] . ' ' . $value);
				} else {
					$mergedAttributes[$key] = $value;
				}
			}

			$attributeString = $this->template->formatAttributes($mergedAttributes);
			$svgContent = (string)preg_replace('/<svg[^>]*>/', '<svg' . $attributeString . '>', $svgContent, 1);
		}

		return $svgContent;
	}

	/**
	 * Parse attributes from an attribute string
	 *
	 * @param string $attributeString
	 *
	 * @return array<string, string>
	 */
	protected function parseAttributesFromString(string $attributeString): array {
		$attributes = [];

		// Match attribute="value" or attribute='value' patterns
		if (preg_match_all('/([\w-]+)=(["\'])((?:(?!\2)[^\\\\]|\\\\.)*)\\2/', $attributeString, $matches, PREG_SET_ORDER)) {
			foreach ($matches as $match) {
				$attributes[$match[1]] = $match[3];
			}
		}

		return $attributes;
	}

	/**
	 * Inline SVG content by stripping whitespace and HTML comments
	 *
	 * @param string $svgContent
	 *
	 * @return string
	 */
	protected function inlineSvg(string $svgContent): string {
		// Remove HTML comments
		$svgContent = (string)preg_replace('/<!--.*?-->/s', '', $svgContent);

		// Collapse whitespace outside of quoted attribute values
		$svgContent = (string)preg_replace_callback(
			'/(["\'])(?:\\\\.|[^\\\\])*?\1|(\s+)/s',
			function ($matches) {
				// If group 1 matched, it's a quoted string: return as-is
				if (!empty($matches[1])) {
					return $matches[0];
				}

				// Otherwise, it's whitespace: collapse to a single space
				return ' ';
			},
			$svgContent,
		);

		// Remove whitespace around tags
		$svgContent = (string)preg_replace('/>\s+</', '><', $svgContent);

		// Remove leading and trailing whitespace
		$svgContent = trim($svgContent);

		return $svgContent;
	}

}

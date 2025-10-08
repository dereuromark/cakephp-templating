<?php declare(strict_types=1);

namespace Templating\View\Icon\Collector;

use RuntimeException;

/**
 * Using e.g. "heroicons" npm package.
 */
class HeroiconsIconCollector {

	/**
	 * Static cache for icon names by path (in-memory cache for request)
	 *
	 * @var array<string, array<string>>
	 */
	protected static array $cache = [];

	/**
	 * @param string $path Path to JSON file or directory containing SVG files
	 *
	 * @return array<string>
	 */
	public static function collect(string $path): array {
		// Check static cache first (in-memory for this request)
		if (isset(static::$cache[$path])) {
			return static::$cache[$path];
		}

		// Check if path is a directory
		if (is_dir($path)) {
			$result = static::collectFromDirectory($path);
		} else {
			// Otherwise treat as JSON file
			$result = static::collectFromJson($path);
		}

		// Cache in memory for this request
		static::$cache[$path] = $result;

		return $result;
	}

	/**
	 * Collect icon names from a directory of SVG files
	 * Supports both flat directories and nested style directories (outline, solid)
	 *
	 * @param string $directory
	 *
	 * @return array<string>
	 */
	protected static function collectFromDirectory(string $directory): array {
		$icons = [];
		$directory = rtrim($directory, '/');

		// Check for style subdirectories (outline, solid)
		$styleDirectories = ['outline', 'solid'];
		$hasStyleDirs = false;

		foreach ($styleDirectories as $style) {
			$styleDir = $directory . '/' . $style;
			if (is_dir($styleDir)) {
				$hasStyleDirs = true;
				$files = glob($styleDir . '/*.svg');
				if ($files !== false) {
					foreach ($files as $file) {
						$iconName = basename($file, '.svg');
						if (!in_array($iconName, $icons, true)) {
							$icons[] = $iconName;
						}
					}
				}
			}
		}

		// If no style directories found, scan the directory directly
		if (!$hasStyleDirs) {
			$files = glob($directory . '/*.svg');
			if ($files === false) {
				throw new RuntimeException('Cannot read directory: ' . $directory);
			}

			foreach ($files as $file) {
				$icons[] = basename($file, '.svg');
			}
		}

		sort($icons);

		return $icons;
	}

	/**
	 * Collect icon names from a JSON file
	 *
	 * @param string $filePath
	 *
	 * @return array<string>
	 */
	protected static function collectFromJson(string $filePath): array {
		$content = file_get_contents($filePath);
		if ($content === false) {
			throw new RuntimeException('Cannot read file: ' . $filePath);
		}
		$array = json_decode($content, true);
		if (!$array) {
			throw new RuntimeException('Cannot parse JSON: ' . $filePath);
		}

		/** @var array<string> */
		return array_keys($array);
	}

}

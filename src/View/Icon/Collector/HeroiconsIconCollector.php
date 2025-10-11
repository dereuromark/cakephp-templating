<?php declare(strict_types=1);

namespace Templating\View\Icon\Collector;

use RuntimeException;

/**
 * Using e.g. "heroicons" npm package.
 */
class HeroiconsIconCollector extends AbstractCollector {

	/**
	 * @param string $path Path to JSON file or directory containing SVG files
	 * @param array<string, mixed> $options Collection options
	 *
	 * @return array<string>
	 */
	public static function collect(string $path, array $options = []): array {
		return static::cached($path, $options, function() use ($path, $options) {
			// Check if path is a directory
			if (is_dir($path)) {
				return static::collectFromDirectory($path, $options);
			}

			// Otherwise treat as JSON file
			return static::collectFromJsonFile($path);
		});
	}

	/**
	 * Collect icon names from a directory of SVG files
	 * Supports both flat directories and nested style directories (outline, solid)
	 *
	 * @param string $directory
	 * @param array<string, mixed> $options Collection options
	 *
	 * @return array<string>
	 */
	protected static function collectFromDirectory(string $directory, array $options = []): array {
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

}

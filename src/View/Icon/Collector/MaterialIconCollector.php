<?php declare(strict_types=1);

namespace Templating\View\Icon\Collector;

use RuntimeException;

/**
 * Using e.g. "material-symbols" npm package.
 */
class MaterialIconCollector extends AbstractCollector {

	/**
	 * @param string $path Path to TypeScript definition file, a JSON SVG map, or a directory of SVG files
	 * @param array<string, mixed> $options Collection options
	 *
	 * @return array<string>
	 */
	public static function collect(string $path, array $options = []): array {
		return static::cached($path, $options, function() use ($path, $options) {
			if (is_dir($path)) {
				return static::collectFromDirectory($path, $options);
			}
			if (str_ends_with(strtolower($path), '.json')) {
				return static::collectFromJsonFile($path);
			}

			$content = static::readFile($path);
			$icons = static::extractWithRegex($content, '/"(.+)"/u', $options);

			if (empty($icons)) {
				throw new RuntimeException('Cannot parse content: ' . $path);
			}

			return $icons;
		});
	}

}

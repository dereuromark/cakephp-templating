<?php declare(strict_types=1);

namespace Templating\View\Icon\Collector;

/**
 * Using e.g. "material-symbols" npm package.
 */
class MaterialIconCollector extends AbstractCollector {

	/**
	 * @param string $path Path to TypeScript definition file
	 * @param array<string, mixed> $options Collection options
	 *
	 * @return array<string>
	 */
	public static function collect(string $path, array $options = []): array {
		return static::cached($path, $options, function() use ($path, $options) {
			$content = static::readFile($path);
			$icons = static::extractWithRegex($content, '/"(.+)"/u', $options);

			if (empty($icons)) {
				throw new \RuntimeException('Cannot parse content: ' . $path);
			}

			return $icons;
		});
	}

}

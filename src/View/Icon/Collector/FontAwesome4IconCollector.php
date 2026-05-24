<?php declare(strict_types=1);

namespace Templating\View\Icon\Collector;

use RuntimeException;

/**
 * Using e.g. "font-awesome" npm package.
 */
class FontAwesome4IconCollector extends AbstractCollector {

	/**
	 * @param string $path Path to LESS or SCSS file
	 * @param array<string, mixed> $options Collection options
	 *
	 * @return array<string>
	 */
	public static function collect(string $path, array $options = []): array {
		return static::cached($path, $options, function() use ($path, $options) {
			$content = static::readFile($path);
			$extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

			$pattern = match ($extension) {
                'less' => '/@fa-var-([0-9a-z-]+):/',
                'scss' => '/\$fa-var-([0-9a-z-]+):/',
                default => throw new RuntimeException('Format not supported: ' . $extension),
            };

			return static::extractWithRegex($content, $pattern, $options);
		});
	}

}

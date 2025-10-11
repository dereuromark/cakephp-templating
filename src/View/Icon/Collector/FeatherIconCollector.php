<?php declare(strict_types=1);

namespace Templating\View\Icon\Collector;

/**
 * Using e.g. "feather-icons" npm package.
 */
class FeatherIconCollector extends AbstractCollector {

	/**
	 * @param string $path Path to JSON file or directory containing SVG files
	 * @param array<string, mixed> $options Collection options
	 *
	 * @return array<string>
	 */
	public static function collect(string $path, array $options = []): array {
		return static::cached($path, $options, fn () => static::collectByType($path, $options));
	}

}

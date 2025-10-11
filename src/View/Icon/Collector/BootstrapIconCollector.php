<?php declare(strict_types=1);

namespace Templating\View\Icon\Collector;

/**
 * Using e.g. "bootstrap-icons" npm package.
 */
class BootstrapIconCollector extends AbstractCollector {

	/**
	 * @param string $path Path to JSON file
	 * @param array<string, mixed> $options Collection options
	 *
	 * @return array<string>
	 */
	public static function collect(string $path, array $options = []): array {
		return static::cached($path, $options, fn () => static::collectFromJsonFile($path));
	}

}

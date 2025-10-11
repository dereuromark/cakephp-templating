<?php declare(strict_types=1);

namespace Templating\View\Icon\Collector;

use RuntimeException;

/**
 * Using e.g. "fontawesome-free" npm package.
 */
class FontAwesome6IconCollector extends AbstractCollector {

	/**
	 * @param string $path Path to SVG, YML, or JSON file
	 * @param array<string, mixed> $options Collection options (includes config)
	 *
	 * @return array<string>
	 */
	public static function collect(string $path, array $options = []): array {
		$options += [
			'namespace' => 'solid',
			'aliases' => true,
		];

		return static::cached($path, $options, function() use ($path, $options) {
			$content = static::readFile($path);
			$extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

			switch ($extension) {
				case 'svg':
					$icons = static::extractWithRegex($content, '/symbol id="([a-z][^"]+)"/', $options);
					if (empty($icons)) {
						throw new RuntimeException('Cannot parse SVG: ' . $path);
					}

					break;
				case 'yml':
					$array = yaml_parse($content);
					if (!$array) {
						throw new RuntimeException('Cannot parse YML: ' . $path);
					}

					$icons = static::icons($array, $options);

					break;
				case 'json':
					$array = static::parseJsonFile($path);
					$icons = static::icons($array, $options);

					break;
				default:
					throw new RuntimeException('Unknown file extension: ' . $extension);
			}

			return $icons;
		});
	}

	/**
	 * @param array $array
	 * @param array $config
	 *
	 * @return array<string>
	 */
	protected static function icons(array $array, array $config): array {
		$icons = [];
		foreach ($array as $key => $details) {
			if (!static::isStyle($details, $config['namespace'])) {
				continue;
			}

			$icons[] = (string)$key;

			if (!$config['aliases'] || empty($details['aliases']['names'])) {
				continue;
			}

			foreach ($details['aliases']['names'] as $alias) {
				$icons[] = $alias;
			}
		}

		return $icons;
	}

	/**
	 * @param array<string, mixed> $details
	 * @param string $namespace
	 *
	 * @return bool
	 */
	protected static function isStyle(array $details, string $namespace): bool {
		if (!empty($details['styles'])) {
			return in_array($namespace, $details['styles'], true);
		}

		if (!empty($details['svgs']['classic'])) {
			$styles = array_keys($details['svgs']['classic']);

			return in_array($namespace, $styles, true);
		}

		throw new RuntimeException('Cannot determine style for icon - ' . print_r($details, true));
	}

}

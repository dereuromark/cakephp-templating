<?php declare(strict_types=1);

namespace Templating\View\Icon\Collector;

use RuntimeException;

/**
 * Using e.g. "fontawesome-free" npm package.
 */
class FontAwesome6IconCollector {

	/**
	 * @param string $filePath
	 * @param array<string, mixed> $config
	 *
	 * @return array<string>
	 */
	public static function collect(string $filePath, array $config = []): array {
		$config += [
			'namespace' => 'solid',
			'aliases' => true,
		];

		$content = file_get_contents($filePath);
		if ($content === false) {
			throw new RuntimeException('Cannot read file: ' . $filePath);
		}

		$ext = pathinfo($filePath, PATHINFO_EXTENSION);
		switch ($ext) {
			case 'svg':
				preg_match_all('/symbol id="([a-z][^"]+)"/', $content, $matches);
				if (!$matches) {
					throw new RuntimeException('Cannot parse SVG: ' . $filePath);
				}
				$icons = $matches[1];

				break;
			case 'yml':
				$array = yaml_parse($content);
				/** @var array<string> $icons */
				$icons = array_keys($array);

				break;
			case 'json':
				$array = json_decode($content, true);
				if (!$array) {
					throw new RuntimeException('Cannot parse JSON: ' . $filePath);
				}

				$icons = static::icons($array, $config);

				break;
			default:
				throw new RuntimeException('Unknown file extension: ' . $ext);
		}

		return $icons;
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

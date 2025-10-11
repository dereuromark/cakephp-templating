<?php declare(strict_types=1);

namespace Templating\View\Icon\Collector;

use RuntimeException;

/**
 * Using e.g. "@fortawesome/fontawesome-free" npm package or "font-awesome-v5-icons" npm meta package.
 */
class FontAwesome5IconCollector extends AbstractCollector {

	/**
	 * @param string $path Path to SVG, YML, or JSON file
	 * @param array<string, mixed> $options Collection options
	 *
	 * @return array<string>
	 */
	public static function collect(string $path, array $options = []): array {
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
					/** @var array<string> $icons */
					$icons = array_keys($array);

					break;
				case 'json':
					$array = static::parseJsonFile($path);
					$icons = static::icons($array);

					break;
				default:
					throw new RuntimeException('Unknown file extension: ' . $extension);
			}

			return $icons;
		});
	}

	/**
	 * @param array $array
	 *
	 * @return array<string>
	 */
	protected static function icons(array $array): array {
		if (!empty($array['icons']) && empty($array['icons']['label'])) {
			$icons = [];
			foreach ($array['icons'] as $row) {
				$icons[] = $row['name'];
			}

			return $icons;
		}

		// Legacy style?
		return array_keys($array);
	}

}

<?php declare(strict_types=1);

namespace Templating\View\Icon\Collector;

use RuntimeException;

/**
 * Using e.g. "fontawesome-free" npm package.
 */
class FontAwesome6IconCollector {

	/**
	 * @param string $filePath
	 *
	 * @return array<string>
	 */
	public static function collect(string $filePath): array {
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
				/** @var array<string> $icons */
				$icons = array_keys($array);

				break;
			default:
				throw new RuntimeException('Unknown file extension: ' . $ext);
		}

		return $icons;
	}

}

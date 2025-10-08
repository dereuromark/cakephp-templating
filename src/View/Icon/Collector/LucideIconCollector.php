<?php declare(strict_types=1);

namespace Templating\View\Icon\Collector;

use RuntimeException;

/**
 * Using e.g. "lucide" npm package.
 */
class LucideIconCollector {

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
		$array = json_decode($content, true);
		if (!$array) {
			throw new RuntimeException('Cannot parse JSON: ' . $filePath);
		}

		/** @var array<string> */
		return array_keys($array);
	}

}

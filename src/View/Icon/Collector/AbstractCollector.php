<?php declare(strict_types=1);

namespace Templating\View\Icon\Collector;

use RuntimeException;

/**
 * Abstract base class for icon collectors providing common functionality
 */
abstract class AbstractCollector {

	/**
	 * Static cache for icon names by path (in-memory cache for request)
	 *
	 * @var array<string, array<string>>
	 */
	protected static array $cache = [];

	/**
	 * Collect icon names from a file or directory - must be implemented by concrete classes
	 *
	 * @param string $path Path to file or directory
	 * @param array<string, mixed> $options Collection options
	 *
	 * @return array<string>
	 */
	abstract public static function collect(string $path, array $options = []): array;

	/**
	 * Get cached result or compute and cache new result
	 *
	 * @param string $path
	 * @param array<string, mixed> $options
	 * @param callable $callback
	 *
	 * @return array<string>
	 */
	protected static function cached(string $path, array $options, callable $callback): array {
		$cacheKey = static::getCacheKey($path, $options);

		// Check static cache first (in-memory for this request)
		if (isset(static::$cache[$cacheKey])) {
			return static::$cache[$cacheKey];
		}

		$result = $callback();

		// Cache in memory for this request
		static::$cache[$cacheKey] = $result;

		return $result;
	}

	/**
	 * Generate cache key for path and options
	 *
	 * @param string $path
	 * @param array<string, mixed> $options
	 *
	 * @return string
	 */
	protected static function getCacheKey(string $path, array $options = []): string {
		if (empty($options)) {
			return $path;
		}

		return $path . '|' . md5(serialize($options));
	}

	/**
	 * Read and validate file contents
	 *
	 * @param string $filePath
	 *
	 * @return string
	 */
	protected static function readFile(string $filePath): string {
		if (!file_exists($filePath)) {
			throw new RuntimeException('File not found: ' . $filePath);
		}

		$content = file_get_contents($filePath);
		if ($content === false) {
			throw new RuntimeException('Cannot read file: ' . $filePath);
		}

		return $content;
	}

	/**
	 * Parse JSON file and return decoded array
	 *
	 * @param string $filePath
	 *
	 * @return array<string, mixed>
	 */
	protected static function parseJsonFile(string $filePath): array {
		$content = static::readFile($filePath);
		$array = json_decode($content, true);

		if (!is_array($array)) {
			throw new RuntimeException('Cannot parse JSON or invalid format: ' . $filePath);
		}

		return $array;
	}

	/**
	 * Collect icon names from JSON file (returns array keys)
	 *
	 * @param string $filePath
	 *
	 * @return array<string>
	 */
	protected static function collectFromJsonFile(string $filePath): array {
		$array = static::parseJsonFile($filePath);

		return array_keys($array);
	}

	/**
	 * Collect icon names from directory of SVG files
	 *
	 * @param string $directory
	 * @param array<string, mixed> $options Options for directory scanning
	 *
	 * @return array<string>
	 */
	protected static function collectFromDirectory(string $directory, array $options = []): array {
		if (!is_dir($directory)) {
			throw new RuntimeException('Path is not a directory: ' . $directory);
		}

		$options += [
			'pattern' => '*.svg',
			'recursive' => false,
			'sort' => true,
			'removeExtension' => true,
		];

		$icons = [];
		$directory = rtrim($directory, '/');
		$pattern = $directory . '/' . $options['pattern'];

		$files = glob($pattern);
		if ($files === false) {
			throw new RuntimeException('Cannot read directory: ' . $directory);
		}

		foreach ($files as $file) {
			if (is_file($file)) {
				$iconName = basename($file);

				if ($options['removeExtension']) {
					$iconName = pathinfo($iconName, PATHINFO_FILENAME);
				}

				if ($iconName !== '') {
					$icons[] = $iconName;
				}
			}
		}

		if ($options['sort']) {
			sort($icons);
		}

		return $icons;
	}

	/**
	 * Extract icon names using regex pattern
	 *
	 * @param string $content File content to parse
	 * @param string $pattern Regex pattern with one capture group
	 * @param array<string, mixed> $options Parsing options
	 *
	 * @return array<string>
	 */
	protected static function extractWithRegex(string $content, string $pattern, array $options = []): array {
		$options += [
			'sort' => true,
			'unique' => true,
		];

		preg_match_all($pattern, $content, $matches);

		if (empty($matches[1])) {
			return [];
		}

		$icons = $matches[1];

		if ($options['unique']) {
			$icons = array_unique($icons);
		}

		if ($options['sort']) {
			sort($icons);
		}

		return array_values($icons);
	}

	/**
	 * Handle file type detection and routing
	 *
	 * @param string $path
	 * @param array<string, mixed> $options
	 *
	 * @return array<string>
	 */
	protected static function collectByType(string $path, array $options = []): array {
		if (is_dir($path)) {
			return static::collectFromDirectory($path, $options);
		}

		$extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

		switch ($extension) {
			case 'json':
				return static::collectFromJsonFile($path);
			default:
				// For other file types, concrete classes should override collect
				throw new RuntimeException('Unsupported file type: ' . $extension . ' for path: ' . $path);
		}
	}

}

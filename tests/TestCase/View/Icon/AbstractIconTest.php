<?php declare(strict_types=1);

namespace Templating\Test\TestCase\View\Icon;

use Cake\TestSuite\TestCase;
use RuntimeException;

class AbstractIconTest extends TestCase {

	/**
	 * Test that path() method works with traditional path config
	 *
	 * @return void
	 */
	public function testPathWithValidPathConfig(): void {
		$validPath = TEST_FILES . 'font_icon' . DS . 'feather' . DS . 'icons.json';
		$icon = new TestIcon(['path' => $validPath]);

		$result = $this->invokeMethod($icon, 'path');
		$this->assertSame($validPath, $result);
	}

	/**
	 * Test that path() method works with svgPath directory
	 *
	 * @return void
	 */
	public function testPathWithValidSvgPathDirectory(): void {
		$validSvgDir = TEST_FILES . 'font_icon' . DS . 'lucide_svg';
		$icon = new TestIcon(['svgPath' => $validSvgDir]);

		$result = $this->invokeMethod($icon, 'path');
		$this->assertSame($validSvgDir, $result);
	}

	/**
	 * Test that path() prefers path over svgPath when both are present
	 *
	 * @return void
	 */
	public function testPathPrefersPathOverSvgPath(): void {
		$validPath = TEST_FILES . 'font_icon' . DS . 'feather' . DS . 'icons.json';
		$validSvgDir = TEST_FILES . 'font_icon' . DS . 'lucide_svg';
		$icon = new TestIcon([
			'path' => $validPath,
			'svgPath' => $validSvgDir,
		]);

		$result = $this->invokeMethod($icon, 'path');
		$this->assertSame($validPath, $result);
	}

	/**
	 * Test that path() throws exception when neither path nor svgPath is configured
	 *
	 * @return void
	 */
	public function testPathThrowsExceptionWhenNoPathConfigured(): void {
		$icon = new TestIcon([]);

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('You need to define a meta data file path or SVG directory path for');

		$this->invokeMethod($icon, 'path');
	}

	/**
	 * Test that path() throws exception when path file does not exist
	 *
	 * @return void
	 */
	public function testPathThrowsExceptionWhenPathFileNotFound(): void {
		$icon = new TestIcon(['path' => '/non/existent/file.json']);

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('Cannot find meta data file path');

		$this->invokeMethod($icon, 'path');
	}

	/**
	 * Test that path() throws exception when svgPath is not a directory
	 *
	 * @return void
	 */
	public function testPathThrowsExceptionWhenSvgPathNotDirectory(): void {
		$filePath = TEST_FILES . 'font_icon' . DS . 'lucide_svg' . DS . 'home.svg';
		$icon = new TestIcon(['svgPath' => $filePath]);

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('SVG path `' . $filePath . '` is not a directory for');

		$this->invokeMethod($icon, 'path');
	}

	/**
	 * Test that path() returns svgPath when path is not configured
	 *
	 * @return void
	 */
	public function testPathReturnsSvgPathWhenPathNotConfigured(): void {
		$validSvgDir = TEST_FILES . 'font_icon' . DS . 'lucide_svg';
		$icon = new TestIcon([
			'svgPath' => $validSvgDir,
		]);

		$result = $this->invokeMethod($icon, 'path');
		$this->assertSame($validSvgDir, $result);
	}

	/**
	 * Helper method to invoke protected methods
	 *
	 * @param object $object
	 * @param string $methodName
	 * @param array<mixed> $parameters
	 *
	 * @return mixed
	 */
	protected function invokeMethod(object $object, string $methodName, array $parameters = []): mixed {
		$reflection = new \ReflectionClass(get_class($object));
		$method = $reflection->getMethod($methodName);

		return $method->invokeArgs($object, $parameters);
	}

}

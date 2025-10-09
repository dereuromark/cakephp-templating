<?php declare(strict_types=1);

namespace Templating\Test\TestCase\View\Icon;

use Cake\TestSuite\TestCase;
use Templating\View\Icon\LucideIcon;

class LucideIconTest extends TestCase {

	/**
	 * @var \Templating\View\Icon\LucideIcon
	 */
	protected $icon;

	/**
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->icon = new LucideIcon();
	}

	/**
	 * @return void
	 */
	public function testRender(): void {
		$result = $this->icon->render('user');
		$this->assertSame('<span data-lucide="user"></span>', (string)$result);
	}

	/**
	 * @return void
	 */
	public function testRenderSvg(): void {
		$svgPath = TMP . 'tests' . DS . 'lucide-icons';
		if (!is_dir($svgPath)) {
			mkdir($svgPath, 0777, true);
		}

		$svgFile = $svgPath . DS . 'test-icon.svg';
		$svgContent = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg>';
		file_put_contents($svgFile, $svgContent);

		$icon = new LucideIcon([
			'svgPath' => $svgPath,
		]);

		$result = $icon->render('test-icon');
		$this->assertStringContainsString('<svg', (string)$result);
		$this->assertStringContainsString('viewBox="0 0 24 24"', (string)$result);
		$this->assertStringContainsString('stroke="currentColor"', (string)$result);

		unlink($svgFile);
	}

	/**
	 * @return void
	 */
	public function testRenderSvgWithAttributes(): void {
		$svgPath = TMP . 'tests' . DS . 'lucide-icons';
		if (!is_dir($svgPath)) {
			mkdir($svgPath, 0777, true);
		}

		$svgFile = $svgPath . DS . 'test-icon-2.svg';
		$svgContent = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide"><circle cx="12" cy="12" r="10"/></svg>';
		file_put_contents($svgFile, $svgContent);

		$icon = new LucideIcon([
			'svgPath' => $svgPath,
		]);

		$result = $icon->render('test-icon-2', [], ['class' => 'custom-class', 'data-test' => 'value']);
		$resultString = (string)$result;

		$this->assertStringContainsString('<svg', $resultString);
		$this->assertStringContainsString('class="lucide custom-class"', $resultString);
		$this->assertStringContainsString('data-test="value"', $resultString);

		unlink($svgFile);
	}

	/**
	 * @return void
	 */
	public function testRenderSvgThrowsExceptionWhenFileNotFound(): void {
		$icon = new LucideIcon([
			'svgPath' => TMP . 'nonexistent',
		]);

		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage('SVG icon file not found');

		$icon->render('nonexistent-icon');
	}

	/**
	 * @return void
	 */
	public function testRenderSvgFromJsonMap(): void {
		$jsonFile = TMP . 'tests' . DS . 'lucide-icons.json';
		$jsonContent = json_encode([
			'home' => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
			'user' => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
		]);
		file_put_contents($jsonFile, $jsonContent);

		$icon = new LucideIcon([
			'svgPath' => $jsonFile,
		]);

		$result = $icon->render('home');
		$resultString = (string)$result;

		$this->assertStringContainsString('<svg', $resultString);
		$this->assertStringContainsString('viewBox="0 0 24 24"', $resultString);
		$this->assertStringContainsString('m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z', $resultString);
		$this->assertStringContainsString('</svg>', $resultString);

		unlink($jsonFile);
	}

	/**
	 * @return void
	 */
	public function testRenderSvgFromJsonMapWithAttributes(): void {
		$jsonFile = TMP . 'tests' . DS . 'lucide-icons-attrs.json';
		$jsonContent = json_encode([
			'heart' => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>',
		]);
		file_put_contents($jsonFile, $jsonContent);

		$icon = new LucideIcon([
			'svgPath' => $jsonFile,
		]);

		$result = $icon->render('heart', [], ['class' => 'custom-icon', 'data-test' => 'value']);
		$resultString = (string)$result;

		$this->assertStringContainsString('<svg', $resultString);
		$this->assertStringContainsString('class="custom-icon"', $resultString);
		$this->assertStringContainsString('data-test="value"', $resultString);

		unlink($jsonFile);
	}

	/**
	 * Test SVG attribute merging scenarios
	 *
	 * @return void
	 */
	public function testSvgAttributeMerging(): void {
		$svgPath = TEST_FILES . 'font_icon' . DS . 'lucide_svg';

		$icon = new LucideIcon(['svgPath' => $svgPath]);
		$result = $icon->render('user', [], ['class' => 'new', 'width' => '32', 'data-test' => 'value']);
		$resultString = (string)$result;

		// Class merging, attribute overriding, hyphenated attributes preserved, new attributes added
		$this->assertStringContainsString('class="lucide lucide-user new"', $resultString);
		$this->assertStringContainsString('width="32"', $resultString);
		$this->assertStringContainsString('stroke-width="2"', $resultString);
		$this->assertStringContainsString('data-test="value"', $resultString);
	}

	/**
	 * Test with multiline SVG and hyphenated attributes
	 *
	 * @return void
	 */
	public function testMultilineSvgWithHyphenatedAttributes(): void {
		$svgPath = TEST_FILES . 'font_icon' . DS . 'lucide_svg';

		$icon = new LucideIcon(['svgPath' => $svgPath]);
		$result = $icon->render('home', [], ['class' => 'icon-lg']);
		$resultString = (string)$result;

		$this->assertStringContainsString('class="lucide lucide-home icon-lg"', $resultString);
		$this->assertStringContainsString('stroke-width="2"', $resultString);
		$this->assertStringContainsString('stroke-linecap="round"', $resultString);
	}

	/**
	 * @return void
	 */
	public function testRenderSvgFromJsonMapThrowsExceptionWhenIconNotFound(): void {
		$jsonFile = TMP . 'tests' . DS . 'lucide-icons-missing.json';
		$jsonContent = json_encode([
			'home' => '<path d="..."/>',
		]);
		file_put_contents($jsonFile, $jsonContent);

		$icon = new LucideIcon([
			'svgPath' => $jsonFile,
		]);

		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage('SVG icon not found in map: nonexistent');

		$icon->render('nonexistent');

		unlink($jsonFile);
	}

}

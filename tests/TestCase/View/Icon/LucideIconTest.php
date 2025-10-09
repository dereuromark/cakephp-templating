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

	/**
	 * Test SVG inlining functionality with existing test files
	 *
	 * @return void
	 */
	public function testRenderSvgWithInlining(): void {
		$svgPath = TEST_FILES . 'font_icon' . DS . 'lucide_svg';

		$icon = new LucideIcon([
			'svgPath' => $svgPath,
			'inline' => true,
		]);

		$result = $icon->render('home');
		$resultString = (string)$result;

		// Should not contain license comment
		$this->assertStringNotContainsString('<!-- @license lucide-static', $resultString);

		// Should have compressed whitespace - no newlines or multiple spaces
		$this->assertStringNotContainsString("\n", $resultString);
		$this->assertStringNotContainsString('  ', $resultString);

		// Should still contain the actual SVG content and attributes
		$this->assertStringContainsString('<svg', $resultString);
		$this->assertStringContainsString('viewBox="0 0 24 24"', $resultString);
		$this->assertStringContainsString('class="lucide lucide-home"', $resultString);
		$this->assertStringContainsString('stroke-width="2"', $resultString);
		$this->assertStringContainsString('d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"', $resultString);
	}

	/**
	 * Test SVG inlining functionality with JSON map mode
	 *
	 * @return void
	 */
	public function testRenderSvgFromJsonMapWithInlining(): void {
		$jsonFile = TMP . 'tests' . DS . 'lucide-icons-inline.json';
		$jsonContent = json_encode([
			'clock' => '<!-- Comment in content -->
<path d="M12 6v6l4 2"/>  
<circle cx="12" cy="12" r="10"/>
<!-- Another comment -->',
		]);
		file_put_contents($jsonFile, $jsonContent);

		$icon = new LucideIcon([
			'svgPath' => $jsonFile,
			'inline' => true,
		]);

		$result = $icon->render('clock');
		$resultString = (string)$result;

		// Should not contain comments
		$this->assertStringNotContainsString('<!-- Comment in content -->', $resultString);
		$this->assertStringNotContainsString('<!-- Another comment -->', $resultString);

		// Should have compressed whitespace
		$this->assertStringNotContainsString("\n", $resultString);
		$this->assertStringNotContainsString('  ', $resultString);

		// Should still contain the actual SVG content wrapped properly
		$this->assertStringContainsString('<svg', $resultString);
		$this->assertStringContainsString('viewBox="0 0 24 24"', $resultString);
		$this->assertStringContainsString('<path d="M12 6v6l4 2"/>', $resultString);
		$this->assertStringContainsString('<circle cx="12" cy="12" r="10"/>', $resultString);

		unlink($jsonFile);
	}

	/**
	 * Test that inlining is disabled by default
	 *
	 * @return void
	 */
	public function testRenderSvgWithoutInlining(): void {
		$svgPath = TEST_FILES . 'font_icon' . DS . 'lucide_svg';

		$icon = new LucideIcon([
			'svgPath' => $svgPath,
			// inline not set, should default to false
		]);

		$result = $icon->render('home');
		$resultString = (string)$result;

		// Should contain comments and whitespace (not inlined)
		$this->assertStringContainsString('<!-- @license lucide-static', $resultString);
		$this->assertStringContainsString("\n", $resultString);
		$this->assertStringContainsString('  ', $resultString);
	}

	/**
	 * Test inlining preserves quoted attribute values properly
	 *
	 * @return void
	 */
	public function testInliningPreservesQuotedValues(): void {
		$svgPath = TMP . 'tests' . DS . 'lucide-icons';
		if (!is_dir($svgPath)) {
			mkdir($svgPath, 0777, true);
		}

		$svgFile = $svgPath . DS . 'test-quotes.svg';
		$svgContent = '<svg xmlns="http://www.w3.org/2000/svg" 
     data-test="value with spaces" 
     class="icon class-name">
  <path d="M12 6 L16 8 L12 10"/>
</svg>';
		file_put_contents($svgFile, $svgContent);

		$icon = new LucideIcon([
			'svgPath' => $svgPath,
			'inline' => true,
		]);

		$result = $icon->render('test-quotes');
		$resultString = (string)$result;

		// Should preserve spaces within quoted values
		$this->assertStringContainsString('data-test="value with spaces"', $resultString);
		$this->assertStringContainsString('class="icon class-name"', $resultString);
		$this->assertStringContainsString('d="M12 6 L16 8 L12 10"', $resultString);

		// But should remove other whitespace
		$this->assertStringNotContainsString("\n", $resultString);

		unlink($svgFile);
	}

	/**
	 * Test inlining with mixed content including complex SVG elements
	 *
	 * @return void
	 */
	public function testInliningWithComplexSvg(): void {
		$svgPath = TMP . 'tests' . DS . 'lucide-icons';
		if (!is_dir($svgPath)) {
			mkdir($svgPath, 0777, true);
		}

		$svgFile = $svgPath . DS . 'complex-test.svg';
		$svgContent = '<!-- Complex SVG with various elements -->
<svg xmlns="http://www.w3.org/2000/svg" 
     viewBox="0 0 100 100"
     class="complex-icon">
  <!-- Group element -->
  <g transform="scale(0.5)">
    <!-- Multiple paths with complex data -->
    <path d="M10 10 Q15 5 20 10 T30 10" fill="red"/>
    
    <circle cx="50" cy="50" r="20" 
            stroke="blue" 
            stroke-width="2"/>
  </g>
  
  <!-- Text element -->
  <text x="50" y="80" text-anchor="middle">Hello World</text>
</svg>';
		file_put_contents($svgFile, $svgContent);

		$icon = new LucideIcon([
			'svgPath' => $svgPath,
			'inline' => true,
		]);

		$result = $icon->render('complex-test');
		$resultString = (string)$result;

		// Should remove comments
		$this->assertStringNotContainsString('<!-- Complex SVG', $resultString);
		$this->assertStringNotContainsString('<!-- Group element -->', $resultString);
		$this->assertStringNotContainsString('<!-- Multiple paths', $resultString);
		$this->assertStringNotContainsString('<!-- Text element -->', $resultString);

		// Should compress whitespace
		$this->assertStringNotContainsString("\n", $resultString);
		$this->assertStringNotContainsString('  ', $resultString);

		// Should preserve complex path data and text content
		$this->assertStringContainsString('d="M10 10 Q15 5 20 10 T30 10"', $resultString);
		$this->assertStringContainsString('text-anchor="middle"', $resultString);
		$this->assertStringContainsString('>Hello World</text>', $resultString);
		$this->assertStringContainsString('transform="scale(0.5)"', $resultString);

		unlink($svgFile);
	}

	/**
	 * Test that inline option can be overridden at render time
	 *
	 * @return void
	 */
	public function testInlineOptionInheritance(): void {
		$svgPath = TEST_FILES . 'font_icon' . DS . 'lucide_svg';

		// Test with inline enabled globally
		$icon = new LucideIcon([
			'svgPath' => $svgPath,
			'inline' => true,
		]);

		$result = $icon->render('home');
		$resultString = (string)$result;

		// Should be inlined
		$this->assertStringNotContainsString('<!-- @license', $resultString);
		$this->assertStringNotContainsString("\n", $resultString);

		// Test with inline disabled globally
		$icon2 = new LucideIcon([
			'svgPath' => $svgPath,
			'inline' => false,
		]);

		$result2 = $icon2->render('home');
		$resultString2 = (string)$result2;

		// Should not be inlined
		$this->assertStringContainsString('<!-- @license', $resultString2);
		$this->assertStringContainsString("\n", $resultString2);
	}

}

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

}

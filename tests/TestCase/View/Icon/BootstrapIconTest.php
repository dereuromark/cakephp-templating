<?php declare(strict_types=1);

namespace Templating\Test\TestCase\View\Icon;

use Cake\TestSuite\TestCase;
use Templating\View\Icon\BootstrapIcon;

class BootstrapIconTest extends TestCase {

	/**
	 * @var \Templating\View\Icon\BootstrapIcon
	 */
	protected $icon;

	/**
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->icon = new BootstrapIcon();
	}

	/**
	 * @return void
	 */
	public function testRender(): void {
		$result = $this->icon->render('info-circle-fill');
		$this->assertSame('<span class="bi bi-info-circle-fill"></span>', (string)$result);
	}

	/**
	 * @return void
	 */
	public function testRenderSvg(): void {
		$svgPath = TMP . 'tests' . DS . 'bootstrap-icons';
		if (!is_dir($svgPath)) {
			mkdir($svgPath, 0777, true);
		}

		$svgFile = $svgPath . DS . 'test-icon.svg';
		$svgContent = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-test-icon" viewBox="0 0 16 16"><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/></svg>';
		file_put_contents($svgFile, $svgContent);

		$icon = new BootstrapIcon([
			'svg' => true,
			'svgPath' => $svgPath,
		]);

		$result = $icon->render('test-icon');
		$this->assertStringContainsString('<svg', (string)$result);
		$this->assertStringContainsString('bi bi-test-icon', (string)$result);
		$this->assertStringContainsString('viewBox="0 0 16 16"', (string)$result);

		unlink($svgFile);
	}

	/**
	 * @return void
	 */
	public function testRenderSvgWithAttributes(): void {
		$svgPath = TMP . 'tests' . DS . 'bootstrap-icons';
		if (!is_dir($svgPath)) {
			mkdir($svgPath, 0777, true);
		}

		$svgFile = $svgPath . DS . 'test-icon-2.svg';
		$svgContent = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-test-icon-2" viewBox="0 0 16 16"><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/></svg>';
		file_put_contents($svgFile, $svgContent);

		$icon = new BootstrapIcon([
			'svg' => true,
			'svgPath' => $svgPath,
		]);

		$result = $icon->render('test-icon-2', [], ['class' => 'custom-class', 'data-test' => 'value']);
		$resultString = (string)$result;

		$this->assertStringContainsString('<svg', $resultString);
		$this->assertStringContainsString('class="bi bi-test-icon-2 custom-class"', $resultString);
		$this->assertStringContainsString('data-test="value"', $resultString);

		unlink($svgFile);
	}

	/**
	 * @return void
	 */
	public function testRenderSvgThrowsExceptionWhenFileNotFound(): void {
		$icon = new BootstrapIcon([
			'svg' => true,
			'svgPath' => TMP . 'nonexistent',
		]);

		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage('SVG icon file not found');

		$icon->render('nonexistent-icon');
	}

	/**
	 * @return void
	 */
	public function testRenderSvgThrowsExceptionWhenPathNotConfigured(): void {
		$icon = new BootstrapIcon([
			'svg' => true,
		]);

		$this->expectException(\RuntimeException::class);
		$this->expectExceptionMessage('SVG path not configured');

		$icon->render('test-icon');
	}

}

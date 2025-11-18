<?php declare(strict_types=1);

namespace Templating\Test\TestCase\View\Icon;

use Cake\TestSuite\TestCase;
use RuntimeException;
use Templating\View\Icon\HeroiconsIcon;

class HeroiconsIconTest extends TestCase {

	/**
	 * @var \Templating\View\Icon\HeroiconsIcon
	 */
	protected $icon;

	/**
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->icon = new HeroiconsIcon();
	}

	/**
	 * @return void
	 */
	public function testRender(): void {
		$result = $this->icon->render('user');
		$this->assertSame('<span class="heroicon-outline"></span>', (string)$result);
	}

	/**
	 * @return void
	 */
	public function testRenderWithSolidStyle(): void {
		$icon = new HeroiconsIcon([
			'style' => 'solid',
		]);

		$result = $icon->render('user');
		$this->assertSame('<span class="heroicon-solid"></span>', (string)$result);
	}

	/**
	 * @return void
	 */
	public function testRenderSvg(): void {
		$svgPath = TMP . 'tests' . DS . 'heroicons';
		$outlinePath = $svgPath . DS . 'outline';
		if (!is_dir($outlinePath)) {
			mkdir($outlinePath, 0777, true);
		}

		$svgFile = $outlinePath . DS . 'test-icon.svg';
		$svgContent = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9" /></svg>';
		file_put_contents($svgFile, $svgContent);

		$icon = new HeroiconsIcon([
			'svgPath' => $svgPath,
			'style' => 'outline',
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
	public function testRenderSvgWithSolidStyle(): void {
		$svgPath = TMP . 'tests' . DS . 'heroicons';
		$solidPath = $svgPath . DS . 'solid';
		if (!is_dir($solidPath)) {
			mkdir($solidPath, 0777, true);
		}

		$svgFile = $solidPath . DS . 'test-solid.svg';
		$svgContent = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 15a3 3 0 100-6 3 3 0 000 6z" /></svg>';
		file_put_contents($svgFile, $svgContent);

		$icon = new HeroiconsIcon([
			'svgPath' => $svgPath,
			'style' => 'solid',
		]);

		$result = $icon->render('test-solid');
		$this->assertStringContainsString('<svg', (string)$result);
		$this->assertStringContainsString('viewBox="0 0 24 24"', (string)$result);
		$this->assertStringContainsString('fill="currentColor"', (string)$result);

		unlink($svgFile);
	}

	/**
	 * @return void
	 */
	public function testRenderSvgWithAttributes(): void {
		$svgPath = TMP . 'tests' . DS . 'heroicons';
		$outlinePath = $svgPath . DS . 'outline';
		if (!is_dir($outlinePath)) {
			mkdir($outlinePath, 0777, true);
		}

		$svgFile = $outlinePath . DS . 'test-icon-2.svg';
		$svgContent = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="heroicon"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9" /></svg>';
		file_put_contents($svgFile, $svgContent);

		$icon = new HeroiconsIcon([
			'svgPath' => $svgPath,
		]);

		$result = $icon->render('test-icon-2', [], ['class' => 'custom-class', 'data-test' => 'value']);
		$resultString = (string)$result;

		$this->assertStringContainsString('<svg', $resultString);
		$this->assertStringContainsString('class="heroicon custom-class"', $resultString);
		$this->assertStringContainsString('data-test="value"', $resultString);

		unlink($svgFile);
	}

	/**
	 * @return void
	 */
	public function testRenderSvgThrowsExceptionWhenFileNotFound(): void {
		$icon = new HeroiconsIcon([
			'svgPath' => TMP . 'nonexistent',
		]);

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('SVG icon file not found');

		$icon->render('nonexistent-icon');
	}

}

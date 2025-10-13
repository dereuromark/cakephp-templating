<?php declare(strict_types=1);

namespace Templating\Test\TestCase\View\Icon;

use Cake\TestSuite\TestCase;
use Templating\View\Icon\FeatherIcon;

class FeatherIconTest extends TestCase {

	/**
	 * @var \Templating\View\Icon\FeatherIcon
	 */
	protected $icon;

	/**
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->icon = new FeatherIcon();
	}

	/**
	 * @return void
	 */
	public function testRender(): void {
		$result = $this->icon->render('view');
		$this->assertSame('<span data-feather="view"></span>', (string)$result);
	}

	/**
	 * @return void
	 */
	public function testRenderSvgFromJsonMap(): void {
		$jsonFile = TMP . 'tests' . DS . 'feather-icons.json';
		$jsonContent = json_encode([
			'activity' => '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>',
			'home' => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline>',
		]);
		file_put_contents($jsonFile, $jsonContent);

		$icon = new FeatherIcon([
			'svgPath' => $jsonFile,
		]);

		$result = $icon->render('activity');
		$resultString = (string)$result;

		$this->assertStringContainsString('<svg', $resultString);
		$this->assertStringContainsString('viewBox="0 0 24 24"', $resultString);
		$this->assertStringContainsString('points="22 12 18 12 15 21 9 3 6 12 2 12"', $resultString);
		$this->assertStringContainsString('</svg>', $resultString);

		unlink($jsonFile);
	}

	/**
	 * @return void
	 */
	public function testRenderSvgFromJsonMapWithCustomAttributes(): void {
		$jsonFile = TMP . 'tests' . DS . 'feather-icons-custom.json';
		$jsonContent = json_encode([
			'star' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>',
		]);
		file_put_contents($jsonFile, $jsonContent);

		$icon = new FeatherIcon([
			'svgPath' => $jsonFile,
		]);

		$result = $icon->render('star', [], ['class' => 'star-icon', 'width' => '32', 'height' => '32']);
		$resultString = (string)$result;

		$this->assertStringContainsString('<svg', $resultString);
		$this->assertStringContainsString('class="star-icon"', $resultString);
		$this->assertStringContainsString('width="32"', $resultString);
		$this->assertStringContainsString('height="32"', $resultString);

		unlink($jsonFile);
	}

	/**
	 * Test that svgPath can point to a directory of SVG files
	 * and names() method works with directory scanning
	 *
	 * @return void
	 */
	public function testNamesFromSvgDirectory(): void {
		$svgDir = TEST_FILES . 'font_icon' . DS . 'lucide_svg';

		$icon = new FeatherIcon([
			'svgPath' => $svgDir,
		]);

		$names = $icon->names();

		$this->assertIsArray($names);
		$this->assertContains('home', $names);
		$this->assertContains('search', $names);
		$this->assertContains('settings', $names);
		$this->assertContains('user', $names);
		$this->assertCount(4, $names);
	}

	/**
	 * Test rendering SVG from directory
	 *
	 * @return void
	 */
	public function testRenderSvgFromDirectory(): void {
		$svgDir = TEST_FILES . 'font_icon' . DS . 'lucide_svg';

		$icon = new FeatherIcon([
			'svgPath' => $svgDir,
		]);

		$result = $icon->render('home');
		$resultString = (string)$result;

		$this->assertStringContainsString('<svg', $resultString);
		$this->assertStringContainsString('viewBox="0 0 24 24"', $resultString);
		$this->assertStringContainsString('path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"', $resultString);
		$this->assertStringContainsString('</svg>', $resultString);
	}

	/**
	 * Test that svgPath = true uses the path value for JSON map
	 *
	 * @return void
	 */
	public function testRenderSvgPathTrueWithJsonMap(): void {
		$jsonFile = TMP . 'tests' . DS . 'feather-icons-path-true.json';
		$jsonContent = json_encode([
			'circle' => '<circle cx="12" cy="12" r="10"></circle>',
			'square' => '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>',
		]);
		file_put_contents($jsonFile, $jsonContent);

		$icon = new FeatherIcon([
			'path' => $jsonFile,
			'svgPath' => true, // Should use the path value
		]);

		$result = $icon->render('circle');
		$resultString = (string)$result;

		$this->assertStringContainsString('<svg', $resultString);
		$this->assertStringContainsString('viewBox="0 0 24 24"', $resultString);
		$this->assertStringContainsString('cx="12" cy="12" r="10"', $resultString);
		$this->assertStringContainsString('</svg>', $resultString);

		unlink($jsonFile);
	}

}

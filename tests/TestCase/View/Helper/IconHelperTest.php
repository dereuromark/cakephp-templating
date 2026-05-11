<?php declare(strict_types=1);

namespace Templating\Test\TestCase\View\Helper;

use Cake\I18n\I18n;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use Templating\View\Helper\IconHelper;
use Templating\View\HtmlStringable;
use Templating\View\Icon\FeatherIcon;
use Templating\View\Icon\LucideIcon;
use Templating\View\Icon\MaterialIcon;

class IconHelperTest extends TestCase {

	/**
	 * @var \Templating\View\Helper\IconHelper
	 */
	protected $Icon;

	/**
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		$config = [
			'sets' => [
				'f' => [
					'class' => FeatherIcon::class,
					'path' => TEST_FILES . 'font_icon/feather/icons.json',
				],
				'm' => [
					'class' => MaterialIcon::class,
					'path' => TEST_FILES . 'font_icon/material/index.d.ts',
				],
				'lucide' => [
					'class' => LucideIcon::class,
					'svgPath' => TEST_FILES . 'font_icon' . DS . 'lucide_svg',
				],
			],
		];

		$this->Icon = new IconHelper(new View(null), $config);
	}

	/**
	 * Test SVG inlining functionality through IconHelper
	 *
	 * @return void
	 */
	public function testIconSvgInlining(): void {
		$config = [
			'sets' => [
				'lucide' => [
					'class' => LucideIcon::class,
					'svgPath' => TEST_FILES . 'font_icon' . DS . 'lucide_svg',
					'inline' => true,
				],
			],
		];

		$IconWithInline = new IconHelper(new View(null), $config);
		$result = $IconWithInline->render('lucide:home');
		$resultString = (string)$result;

		// Should not contain license comment or newlines when inlined
		$this->assertStringNotContainsString('<!-- @license lucide-static', $resultString);
		$this->assertStringNotContainsString("\n", $resultString);

		// Should still contain the SVG content
		$this->assertStringContainsString('<svg', $resultString);
		$this->assertStringContainsString('class="lucide lucide-home"', $resultString);
	}

	/**
	 * Test SVG without inlining (default behavior)
	 *
	 * @return void
	 */
	public function testIconSvgWithoutInlining(): void {
		$config = [
			'sets' => [
				'lucide' => [
					'class' => LucideIcon::class,
					'svgPath' => TEST_FILES . 'font_icon' . DS . 'lucide_svg',
					// inline not set, defaults to false
				],
			],
		];

		$IconWithoutInline = new IconHelper(new View(null), $config);
		$result = $IconWithoutInline->render('lucide:home');
		$resultString = (string)$result;

		// Should contain license comment and newlines when not inlined
		$this->assertStringContainsString('<!-- @license lucide-static', $resultString);
		$this->assertStringContainsString("\n", $resultString);

		// Should still contain the SVG content
		$this->assertStringContainsString('<svg', $resultString);
		$this->assertStringContainsString('class="lucide lucide-home"', $resultString);
	}

	/**
	 * @return void
	 */
	public function testIconDefault() {
		$result = $this->Icon->render('edit');
		$expected = '<span data-feather="edit" title="Edit"></span>';
		$this->assertSame($expected, (string)$result);
	}

	/**
	 * @return void
	 */
	public function testIconPrefixed() {
		$result = $this->Icon->render('f:edit');
		$expected = '<span data-feather="edit" title="Edit"></span>';
		$this->assertSame($expected, (string)$result);
	}

	/**
	 * @return void
	 */
	public function testIconWithCustomAttributes() {
		$result = $this->Icon->render('m:save', [], ['data-x' => 'y']);
		$expected = '<span class="material-icons" data-x="y" title="Save">save</span>';
		$this->assertSame($expected, (string)$result);
	}

	/**
	 * @return void
	 */
	public function testIconWithCustomClassAttributes() {
		$result = $this->Icon->render('m:save', [], ['class' => 'my-extra']);
		$expected = '<span class="material-icons my-extra" title="Save">save</span>';
		$this->assertSame($expected, (string)$result);
	}

	/**
	 * @return void
	 */
	public function testIconWithCustomTitleField() {
		$result = $this->Icon->render('m:save', ['titleField' => 'data-title'], ['class' => 'my-extra']);
		$expected = '<span class="material-icons my-extra" data-title="Save">save</span>';
		$this->assertSame($expected, (string)$result);
	}

	/**
	 * @return void
	 */
	public function testIconWithCustomTitleAttributes() {
		$result = $this->Icon->render('m:save', [], ['class' => 'my-extra', 'title' => 'Save me']);
		$expected = '<span class="material-icons my-extra" title="Save me">save</span>';
		$this->assertSame($expected, (string)$result);
	}

	/**
	 * Auto-title translation is opt-in. With no `translateAutoTitle` config and no
	 * per-call `translate => true`, the humanized icon name passes through unchanged
	 * — even when a translator IS registered for the `template` domain, the auto
	 * path skips the __d() call entirely so the registered translation does not run.
	 *
	 * @return void
	 */
	public function testIconAutoTitleNotTranslatedByDefault() {
		$this->registerTemplateTranslator(['Save' => 'Speichern']);
		$result = $this->Icon->render('m:save');

		// Translator IS registered, but auto path bypasses it — we still see "Save".
		$this->assertStringContainsString('title="Save"', (string)$result);
		$this->assertStringNotContainsString('Speichern', (string)$result);
	}

	/**
	 * `translateAutoTitle => true` re-enables translation on the auto path — the
	 * registered translator runs and the German "Speichern" surfaces in the title.
	 *
	 * @return void
	 */
	public function testIconAutoTitleTranslatesWhenConfigEnabled() {
		$this->registerTemplateTranslator(['Save' => 'Speichern']);
		// Pass translateAutoTitle via the helper-level config — IconCollection picks it up
		// through getConfig() in the same shape as the existing `sets` config.
		$config = [
			'sets' => [
				'm' => [
					'class' => MaterialIcon::class,
					'path' => TEST_FILES . 'font_icon/material/index.d.ts',
				],
			],
			'translateAutoTitle' => true,
		];
		$this->Icon = new IconHelper(new View(null), $config);

		$result = $this->Icon->render('m:save');

		$this->assertStringContainsString('title="Speichern"', (string)$result);
	}

	/**
	 * Per-call `translate => true` overrides config and runs translation for the auto path.
	 *
	 * @return void
	 */
	public function testIconAutoTitleTranslatesWhenExplicitlyEnabled() {
		$this->registerTemplateTranslator(['Save' => 'Speichern']);
		$result = $this->Icon->render('m:save', ['translate' => true]);

		$this->assertStringContainsString('title="Speichern"', (string)$result);
	}

	/**
	 * Caller-supplied (non-auto) titles continue to be translated by default — that
	 * preserves prior behavior for apps that intentionally pass __d() strings as
	 * titles. translateAutoTitle does not gate the custom-title path.
	 *
	 * @return void
	 */
	public function testIconCustomTitleStillTranslatedByDefault() {
		$this->registerTemplateTranslator(['My Title' => 'Mein Titel']);

		$result = $this->Icon->render('m:save', [], ['title' => 'My Title']);

		$this->assertStringContainsString('title="Mein Titel"', (string)$result);
	}

	/**
	 * Per-call `translate => false` skips translation even on the custom-title path.
	 *
	 * @return void
	 */
	public function testIconCustomTitleNotTranslatedWhenExplicitlyDisabled() {
		$this->registerTemplateTranslator(['My Title' => 'Mein Titel']);

		$result = $this->Icon->render('m:save', ['translate' => false], ['title' => 'My Title']);

		$this->assertStringContainsString('title="My Title"', (string)$result);
		$this->assertStringNotContainsString('Mein Titel', (string)$result);
	}

	/**
	 * Tiny in-memory translator for the `template` domain so the opt-in/opt-out
	 * contract can be asserted with deterministic translations rather than the
	 * usual `__d()` identity fallback that hides whether the call ran.
	 *
	 * @param array<string, string> $map source string => translation
	 * @return void
	 */
	protected function registerTemplateTranslator(array $map): void {
		I18n::getTranslator('template')->getPackage()->addMessages($map);
	}

	/**
	 * @return void
	 */
	public function testIconWithCustomFontIcon() {
		$config = [
			'sets' => [
				'f' => [
					'class' => FeatherIcon::class,
					'path' => TEST_FILES . 'font_icon/feather/icons.json',
				],
				'm' => [
					'class' => MaterialIcon::class,
					'path' => TEST_FILES . 'font_icon/material/index.d.ts',
				],
			],
			'map' => [
				'edit' => 'm:save',
			],
		];

		$this->Icon = new IconHelper(new View(null), $config);

		$result = $this->Icon->render('edit');
		$expected = '<span class="material-icons" title="Edit">save</span>';
		$this->assertInstanceOf(HtmlStringable::class, $result);
		$this->assertSame($expected, (string)$result);
	}

	/**
	 * @return void
	 */
	public function tearDown(): void {
		parent::tearDown();

		unset($this->Icon);
	}

}

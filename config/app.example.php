<?php

/**
 * Templating Example Configuration
 *
 * Merge the keys below into your application's config/app.php (or
 * config/app_local.php) — do not replace the whole file, since this snippet
 * only contains this plugin's configuration. When copying entries that
 * reference imported classes, use fully-qualified class names or move the
 * `use` imports to the top of the target file. Customize the values as needed.
 *
 * `Templating` is read by Templating\View\Helper\TemplatingHelper and `Icon` by
 * Templating\View\Helper\IconHelper (and the Admin IconsController / icon render task).
 * Both are merged as defaults at helper construction; helper options passed at load
 * time still win.
 */
return [
	// Read by Templating\View\Helper\TemplatingHelper.
	'Templating' => [
		// StringTemplate templates used by the helper. NOTE: setting this REPLACES the
		// whole templates array (applied with a top-level `+`, not a per-entry merge),
		// so always include the built-in `ok` template below or TemplatingHelper::ok()
		// will fail at runtime.
		'templates' => [
			'ok' => '<span class="ok-{{type}}" style="color:{{color}}"{{attributes}}>{{content}}</span>',
		],
	],

	// Read by Templating\View\Helper\IconHelper (consumed by Templating\View\Icon\IconCollection).
	'Icon' => [
		// Icon sets to register. Map of set name => icon class string, or
		// set name => ['class' => ClassString, ...setConfig]. At least one set is
		// required for the helper to render. Each set class must implement
		// Templating\View\Icon\IconInterface (e.g. BootstrapIcon, FontAwesome6Icon,
		// FeatherIcon, HeroiconsIcon, LucideIcon, MaterialIcon, FontAwesome4/5/7Icon).
		// The first registered set becomes the default set. Default: [] (none).
		'sets' => [
			// 'fa' => \Templating\View\Icon\FontAwesome6Icon::class,
			// 'bs' => \Templating\View\Icon\BootstrapIcon::class,
		],
		// NOTE: per-set asset locations go inside each set's config above as `path` /
		// `svgPath`. There is no top-level `Icon.paths` key — IconCollection does not
		// read one, so setting it here has no effect.
		// When true, an icon used without a set prefix resolves against the primary
		// (first) set without requiring the prefix. Default: true.
		'autoPrefix' => true,
		// Separator between set name and icon name (e.g. 'fa:home'). Default: ':'.
		'separator' => ':',
		// Cache config name used when caching the icon name map. null falls back to the
		// 'default' cache config; set to false to disable caching. Default: null.
		'cache' => null,
		// When true, the auto-generated title attribute (humanized icon name) is run
		// through translation at render time. Off by default to avoid PO scanner false
		// positives and a per-render runtime cost. Default: false.
		'translateAutoTitle' => false,
	],
];

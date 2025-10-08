# Icon Helper

A CakePHP helper to handle most common font icons. Contains convenience wrappers and overview in backend.

## Setup
Include helper in your AppView class as
```php
$this->loadHelper('Templating.Icon', [
    ...
]);
```

You can store default configs also in Configure key `'Icon'`.

Make sure to set up at least one icon set:
- **Bootstrap**: npm package `bootstrap-icons`
- **FontAwesome** v4/v5/v6: npm package `fontawesome-free` for v6
- **Material**: npm package `material-symbols`
- **Feather**: npm package `feather-icons`
- **Lucide**: npm package `lucide` (modern Feather fork with 1000+ icons)
- **Heroicons**: npm package `heroicons` (by Tailwind CSS team)

or your custom Icon class (see https://icon-sets.iconify.design/ for inspiration).

E.g.
```php
'Icon' => [
    'sets' => [
        'bs' => \Templating\View\Icon\BootstrapIcon::class,
        ...
    ],
],
```

For some Icon classes, there is additional configuration available:
- `namespace`: Some fonts offer different traits (light, bold, round, ...)

In this case make sure to use an array instead of just the class string:
```php
'Icon' => [
    'sets' => [
        'material' => [
            'class' => \Templating\View\Icon\MaterialIcon::class,
            'namespace' => 'material-symbols-round',
        ],
        ...
    ],
],
```

You can also set a global attributes config that would be merged in with every icon:
```php
'Icon' => [
    'sets' => [
        'material' => [
            'attributes' => [
                'data-custom' => 'some-custom-default',
            ],
        ],
        ...
    ],
    'attributes' => [
        'data-default' => 'some-default',
        ...
    ],
],
```

Don't forget to also set up the necessary stylesheets (CSS files) and alike.

### SVG Mode

Most icon sets can be rendered as inline SVG instead of using icon fonts or data attributes. This provides better customization, accessibility, and consistent rendering across browsers.

#### Bootstrap Icons

```php
'Icon' => [
    'sets' => [
        'bs' => [
            'class' => \Templating\View\Icon\BootstrapIcon::class,
            'svgPath' => WWW_ROOT . 'css/bootstrap-icons/icons/',
        ],
        ...
    ],
],
```

#### FontAwesome (v4/v5/v6)

```php
'Icon' => [
    'sets' => [
        'fa6' => [
            'class' => \Templating\View\Icon\FontAwesome6Icon::class,
            'svgPath' => WWW_ROOT . 'css/fontawesome/svgs/solid/',
        ],
        ...
    ],
],
```

#### Lucide

```php
'Icon' => [
    'sets' => [
        'lucide' => [
            'class' => \Templating\View\Icon\LucideIcon::class,
            'svgPath' => WWW_ROOT . 'node_modules/lucide/icons/',
        ],
        ...
    ],
],
```

#### Heroicons

Heroicons supports multiple styles (outline, solid, mini). The style can be configured:

```php
'Icon' => [
    'sets' => [
        'heroicons' => [
            'class' => \Templating\View\Icon\HeroiconsIcon::class,
            'svgPath' => WWW_ROOT . 'node_modules/heroicons/',
            'style' => 'outline', // outline, solid, or mini
        ],
        ...
    ],
],
```

The `svgPath` should point to the parent directory, and the class will automatically append the style subdirectory (e.g., `outline/`, `solid/`).

#### Feather Icons

```php
'Icon' => [
    'sets' => [
        'feather' => [
            'class' => \Templating\View\Icon\FeatherIcon::class,
            'svgPath' => WWW_ROOT . 'node_modules/feather-icons/dist/icons/',
        ],
        ...
    ],
],
```

#### Material Icons

```php
'Icon' => [
    'sets' => [
        'material' => [
            'class' => \Templating\View\Icon\MaterialIcon::class,
            'svgPath' => WWW_ROOT . 'css/material-symbols/svg/',
        ],
        ...
    ],
],
```

#### Benefits of SVG Mode

- More consistent rendering across devices and browsers
- Greater customization possibilities (e.g., partial hover color changes, multi-color icons)
- Better accessibility features
- No need to load icon font files
- Smaller file sizes when using only a subset of icons

#### Optional: Enable Caching

For better performance, you can enable CakePHP caching for SVG files:

```php
'Icon' => [
    'sets' => [
        'bs' => [
            'class' => \Templating\View\Icon\BootstrapIcon::class,
            'svgPath' => WWW_ROOT . 'css/bootstrap-icons/icons/',
            'cache' => 'default', // Use your cache configuration name
        ],
        ...
    ],
],
```

When `svgPath` is configured, the icon will be rendered as an inline SVG element loaded from the configured directory. Icons are cached in memory per request, and optionally in your configured CakePHP cache for persistence across requests.

## Usage

### render()
Display font icons using the default namespace or an already aliased one.
```php
echo $this->Html->link(
    $this->Icon->render('view', $options, $attributes),
    $url,
);
```

You can alias them via Configure for more usability:
```php
// In app.php
'Icon' => [
    'map' => [
        'view' => 'bs:eye',
        'translate' => 'bs:translate',
        ...
    ],
],

// in the template
echo $this->Icon->render('translate', [], ['title' => __('Translate this')]);
```
This way you can also rename icons (and map them in any custom way)

Such aliasing can be especially useful to give some icons more meaningful way for your specific use case:
```php
    'details' => 'fas:chevron-right',
    'delete' => 'fas:trash',
    'female' => 'fas:venus',
    'male' => 'fas:mars',
    'yes' => 'fas:check',
    'no' => 'fas:times',
    'repeat' => 'bs:arrow-clockwise',
    'config' => 'fas:cogs',
    'admin' => 'fas:shield',
    ...
```

Especially if you have multiple icon sets defined, any icon set after the first one would require prefixing for colliding icon names:
```php
echo $this->Html->link(
    $this->Icon->render('bs:view', $options, $attributes),
    $url,
);
```
This would also be needed if you set `autoPrefix` config to `false`. Then only the alias map would be used here.

### names()
You can get a nested list of all configured and available icons.

For this make sure to set up the path config to the icon meta files as per each collector.
E.g.:
```php
'Icon' => [
    // For being able to parse the available icons
    'sets' => [
        'fa' => [
            ...
            'path' => '/path/to/font-awesome/less/variables.less',
        ],
        'bs' => [
            ...
            'path' => '/path/to/bootstrap-icons/font/bootstrap-icons.json',
        ],
        'feather' => [
            ...
            'path' => '/path/to/feather-icons/dist/icons.json',
        ],
        'lucide' => [
            ...
            'path' => '/path/to/lucide/dist/icons.json',
        ],
        'heroicons' => [
            ...
            'path' => '/path/to/heroicons/optimized/icons.json',
        ],
        'material' => [
            ...
            'path' => '/path/to/material-symbols/index.d.ts',
        ],
        ...
    ],
],
```

You can then use this to iterate over all of them for display:
```php
$icons = $this->Icon->names();
foreach ($icons as $iconSet => $list) {
    foreach ($list as $icon) {
        ...
    }
}
```

## Configuration

You can enable `checkExistence` to ensure each icon exists or otherwise throws a warning in logs:
```php
'Icon' => [
    'checkExistence' => true,
    ...
],
```

You can define caching for the icon lists for performance - defaults to `'default'` cache engine.
```php
'Icon' => [
    'cache' => ...,
    ...
],
```
Set it to `false` if you want to not cache it.


## Backend
If routes are enabled, you should be able to navigate to
```
/admin/templating/icons
```
and see all your custom (mapped) icons, as well as the icons available.
You can also check the full icon sets available (namespaced ones).

It can also show you possible conflicts (same icon in different sets, here the defined order matters).
For conflicting ones you can use aliasing through the map - or directly use the verbose `set:name` syntax where the "other one" is needed.

## Tips

Check out [animations](https://fontawesome.com/docs/web/style/animate) and
other cool things you can add for FontAwesome icons, which are by far the
most powerful and most used ones.

## Auto-Complete
Now for the most powerful feature and probably most helpful one:
Let your IDE (e.g. PHPStorm) provide you the available icons when you type `$this->Icon->render(` and quick-select from the dropdown list.

Use [IdeHelper plugin](https://github.com/dereuromark/cakephp-ide-helper/) here to get full autocomplete for the icon names as input for `render($name)`.
This requires an IDE that can understand the meta-data (e.g. PHPStorm).
Just add the `IconRenderTask` shipped with this plugin and you are all set.

```php
    'IdeHelper' => [
        ...
        'generatorTasks' => [
            \Templating\Generator\Task\IconRenderTask::class,
        ],
```

## Demo
https://sandbox.dereuromark.de/sandbox/templating-examples/icons

## Writing your own class
You mainly need to set up your own template string and how it should render:
```php
class YourIcon extends AbstractIcon {

	public function __construct(array $config = []) {
		$config += [
			'template' => '<span class="{{class}}...></span>',
		];

		parent::__construct($config);
	}

	public function render(string $icon, array $options = [], array $attributes = []): HtmlStringable {
	    ...
	}

	public function names(): array {
		$path = $this->path();

		return YourCollector::collect($path);
	}

}
```
Now you can hook it into your config and enjoy!

## Supported Icon Sets

Currently supported icon sets with full SVG rendering capability:
- **Bootstrap Icons** - 1800+ icons, MIT license
- **FontAwesome** (v4/v5/v6) - 2000+ icons (free tier), Font Awesome license
- **Lucide** - 1000+ icons, ISC license (modern Feather fork)
- **Heroicons** - 300+ icons with multiple styles, MIT license (by Tailwind CSS)
- **Feather** - 280+ icons, MIT license
- **Material Icons** - Google's icon set, Apache 2.0 license

## TODO
TBD:
- `@icon/icofont` ( https://icofont.com/ )
- https://fontello.com/
- Tabler Icons (4000+ icons)
- Phosphor Icons (6000+ icons)

Help welcome!

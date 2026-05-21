# Icon Helper

A comprehensive CakePHP helper for rendering icons from popular icon libraries,
with support for both font-based and SVG rendering modes.

The Icon helper provides a unified interface for rendering icons from multiple
popular icon libraries. It supports three distinct rendering modes, each
optimized for different use cases and performance requirements.

This page covers the overview, quick start, setup, and everyday usage. See also:

- [Icon Configuration](./icon-configuration) — the full configuration reference and per-set setups.
- [Icon Advanced](./icon-advanced) — backend browser, IDE auto-complete, custom sets, and best practices.

## Key features

- Support for 8+ popular icon libraries
- Automatic mode detection based on configuration
- Advanced two-tier caching system
- Icon mapping and aliasing
- Backend browser interface
- IDE auto-completion support
- Performance optimizations for production

## Rendering modes

### Font-based icons (traditional)

- Uses icon fonts (CSS classes or data attributes).
- Requires loading CSS/font files.
- Lightweight setup, familiar approach.
- Limited customization options.

### SVG icons

**JSON map (recommended)**

- All icons loaded from a single JSON file.
- Best performance (single file load).
- Full SVG customization plus caching benefits.
- Most efficient for large icon libraries.

**Individual SVG files**

- Each icon loaded from a separate `.svg` file.
- Full SVG customization capabilities.
- Ideal for selective icon usage or custom sets.
- Requires file system access per icon.

## Quick start

**Font-based rendering:**

```php
// In your AppView.php
$this->loadHelper('Templating.Icon', [
    'sets' => [
        'bs' => \Templating\View\Icon\BootstrapIcon::class,
    ],
]);

// In your template
echo $this->Icon->render('house'); // <i class="bi bi-house"></i>
```

**SVG rendering (JSON map):**

```php
// In your AppView.php
$this->loadHelper('Templating.Icon', [
    'sets' => [
        'feather' => [
            'class' => \Templating\View\Icon\FeatherIcon::class,
            'svgPath' => WWW_ROOT . 'node_modules/feather-icons/dist/icons.json',
        ],
    ],
]);

// In your template
echo $this->Icon->render('home'); // <svg>...</svg> (inline SVG)
```

**SVG rendering (individual files):**

```php
// In your AppView.php
$this->loadHelper('Templating.Icon', [
    'sets' => [
        'lucide' => [
            'class' => \Templating\View\Icon\LucideIcon::class,
            'svgPath' => WWW_ROOT . 'node_modules/lucide-static/icons/',
        ],
    ],
]);

// In your template
echo $this->Icon->render('home'); // <svg>...</svg> (loaded from home.svg)
```

## Installation and setup

### 1. Load the helper

Add the Icon helper to your `src/View/AppView.php`:

```php
public function initialize(): void
{
    $this->loadHelper('Templating.Icon', [
        'sets' => [
            'bs' => \Templating\View\Icon\BootstrapIcon::class,
            'fa6' => \Templating\View\Icon\FontAwesome6Icon::class,
        ],
    ]);
}
```

### 2. Install icon libraries

::: tip npm is not required
This plugin does not handle asset management or shipping of icon files. You can
install icon libraries using any method (npm, Composer, CDN, manual download,
etc.). The plugin only needs to know where the files are located via the
configuration paths.
:::

Install your preferred icon libraries via npm:

```bash
# Bootstrap Icons
npm install bootstrap-icons

# FontAwesome
npm install @fortawesome/fontawesome-free

# Lucide (modern Feather fork)
npm install lucide-static

# Heroicons (by Tailwind CSS)
npm install heroicons

# Feather Icons
npm install feather-icons

# Material Symbols
npm install material-symbols
```

::: important Font CSS is your responsibility
For font-based icons, you must include the necessary CSS/font files in your
layout yourself. The plugin only generates the HTML markup — it does not handle
loading stylesheets or font files.
:::

::: warning Ship icon files to production
When deploying your application, ensure icon files are available in production.
If using npm-installed icons, include them in your build/deployment process or
copy them to a permanent location. The plugin expects icon files to exist at the
configured paths — missing files will cause runtime errors.
:::

### 3. Configuration

You can store default configurations in `config/app.php`:

```php
'Icon' => [
    'sets' => [
        'bs' => [
            'class' => \Templating\View\Icon\BootstrapIcon::class,
            'svgPath' => WWW_ROOT . 'node_modules/bootstrap-icons/icons/',
        ],
        'fa6' => [
            'class' => \Templating\View\Icon\FontAwesome6Icon::class,
            'svgPath' => WWW_ROOT . 'node_modules/@fortawesome/fontawesome-free/svgs/solid/',
        ],
    ],
    'map' => [
        'home' => 'bs:house',
        'user' => 'fa6:user',
        'settings' => 'bs:gear',
    ],
],
```

See [Icon Configuration](./icon-configuration) for the full reference.

## Supported icon sets

| Icon Set | Class | Icons | License | NPM Package |
|----------|-------|-------|---------|-------------|
| **Bootstrap Icons** | `BootstrapIcon` | 1,800+ | MIT | `bootstrap-icons` |
| **FontAwesome v7** | `FontAwesome7Icon` | 2,000+ | Font Awesome | `@fortawesome/fontawesome-free` |
| **FontAwesome v6** | `FontAwesome6Icon` | 2,000+ | Font Awesome | `@fortawesome/fontawesome-free` |
| **FontAwesome v5** | `FontAwesome5Icon` | 1,600+ | Font Awesome | `fontawesome-free` |
| **FontAwesome v4** | `FontAwesome4Icon` | 800+ | Font Awesome | `font-awesome` |
| **Lucide** | `LucideIcon` | 1,000+ | ISC | `lucide-static` |
| **Heroicons** | `HeroiconsIcon` | 300+ | MIT | `heroicons` |
| **Feather** | `FeatherIcon` | 280+ | MIT | `feather-icons` |
| **Material Icons** | `MaterialIcon` | 2,000+ | Apache 2.0 | `material-symbols` |

Besides these out-of-the-box ones, you can easily create your own Icon class —
see [Creating custom icon sets](./icon-advanced#creating-custom-icon-sets).

## Usage

### Basic rendering

```php
// Render using alias
echo $this->Icon->render('home');

// Render with explicit namespace
echo $this->Icon->render('bs:house');

// Render with attributes
echo $this->Icon->render('user', [], ['class' => 'text-primary', 'title' => 'User Profile']);
```

### Icon prefixes and auto-prefixing

The Icon helper provides flexible prefixing to handle multiple icon sets.

#### Auto-prefixing (default behavior)

By default, `autoPrefix` is enabled, which automatically creates unprefixed
aliases for all icons:

```php
'Icon' => [
    'autoPrefix' => true, // Default - creates automatic mappings
    'sets' => [
        'bs' => \Templating\View\Icon\BootstrapIcon::class,
        'fa6' => \Templating\View\Icon\FontAwesome6Icon::class,
    ],
],
```

```php
// All these work without prefixes (first match wins)
echo $this->Icon->render('home');     // Uses bs:home (first set)
echo $this->Icon->render('house');    // Uses bs:house
echo $this->Icon->render('user');     // Uses bs:user or fa6:user (whichever is found first)

// Explicit prefixes always work
echo $this->Icon->render('bs:house');
echo $this->Icon->render('fa6:user');
```

#### Manual prefixing only

Disable auto-prefixing to require explicit prefixes:

```php
'Icon' => [
    'autoPrefix' => false, // Disable automatic mappings
    'sets' => [
        'bs' => \Templating\View\Icon\BootstrapIcon::class,
        'fa6' => \Templating\View\Icon\FontAwesome6Icon::class,
    ],
    'map' => [
        'home' => 'bs:house',   // Manual aliases only
        'user' => 'fa6:user',
    ],
],
```

```php
// Only explicit prefixes and manual mappings work
echo $this->Icon->render('bs:house');  // Works
echo $this->Icon->render('fa6:user');  // Works
echo $this->Icon->render('home');      // Works (manual mapping)
echo $this->Icon->render('house');     // Fails (no mapping, no auto-prefix)
```

#### Handling icon conflicts

When multiple sets have the same icon name:

```php
'Icon' => [
    'sets' => [
        'bs' => \Templating\View\Icon\BootstrapIcon::class,    // First set (default)
        'fa6' => \Templating\View\Icon\FontAwesome6Icon::class,
    ],
],
```

```php
// Both sets have a "home" icon
echo $this->Icon->render('home');     // Uses Bootstrap (first set)
echo $this->Icon->render('bs:home');  // Explicitly Bootstrap
echo $this->Icon->render('fa6:home'); // Explicitly FontAwesome
```

Use manual mapping to resolve conflicts:

```php
'Icon' => [
    'map' => [
        'home' => 'bs:house',        // Use Bootstrap house for "home"
        'home-alt' => 'fa6:home',    // Alternative FontAwesome home
    ],
],
```

### Attributes and options

```php
echo $this->Icon->render('settings',
    ['translate' => false], // Options
    ['class' => 'icon-lg', 'data-toggle' => 'tooltip'] // HTML attributes
);
```

**Available options:**

- `translate` (bool) — whether to translate the title attribute.
- `titleField` (string) — attribute name for auto-generated titles.
- `title` (string|false) — custom title, or `false` to disable the auto-title.

### Auto-title translation

When a `title` attribute is auto-generated from the icon name (for example,
`arrow-left` becomes `Arrow Left`), translation is **off by default**.

The `translateAutoTitle` config flag opts auto-generated titles back into
translation for apps that have curated `Arrow Left → Pfeil links` mappings:

```php
'Icon' => [
    'translateAutoTitle' => true, // Default: false
],
```

Caller-supplied titles (when you pass `$attributes['title'] = 'Custom Title'`)
continue to be translated by default. To override either contract per-call,
pass `translate => true|false` in `$options`:

```php
$this->Icon->render('arrow-left'); // title="Arrow Left" (no translation)
$this->Icon->render('arrow-left', ['translate' => true]); // title runs through translation
$this->Icon->render('save', [], ['title' => 'Save now']); // 'Save now' translated by default
$this->Icon->render('save', ['translate' => false], ['title' => 'Save now']); // not translated
```

::: details Why auto-title translation is off by default
The previous behavior translated auto-generated titles unconditionally, which
had two problems: the runtime translation value is dynamic, so a static PO
scanner could not extract the title strings (every icon name needed a
hand-curated entry in `template.po`), and most apps never translate icon titles,
so the runtime call was pure overhead and a misleading "this gets translated"
signal.
:::

### Getting available icons

```php
$icons = $this->Icon->names();
foreach ($icons as $iconSet => $iconList) {
    echo "Icon set: {$iconSet}\n";
    foreach ($iconList as $icon) {
        echo "  - {$icon}\n";
    }
}
```

**Example output:**

```text
Icon set: bs
  - house
  - gear
  - person
Icon set: fa6
  - home
  - user
  - cog
```

---

**Demo:** [sandbox.dereuromark.de/sandbox/templating-examples/icons](https://sandbox.dereuromark.de/sandbox/templating-examples/icons)

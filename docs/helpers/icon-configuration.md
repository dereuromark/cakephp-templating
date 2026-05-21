# Icon Configuration

This page covers the rendering modes in depth, per-set configurations for each
supported library, the complete configuration reference, and performance and
caching options. For everyday usage, see the [Icon helper](./icon); for custom
sets and IDE auto-complete, see [Icon Advanced](./icon-advanced).

## Rendering modes explained

The Icon helper automatically detects the rendering mode based on your
configuration.

::: important Two different path configurations
There are two distinct path options:

- **`path`** — used for collecting icon names (metadata files like `.json`,
  `.less`, `.ts`, or the path of icons if no metadata file exists).
- **`svgPath`** — used for rendering SVG icons (either a directory of `.svg`
  files or a `.json` map). Set to `true` if it equals the `path`.
:::

### Font-based rendering

**When to use:** simple setups, existing font-based workflows, or when SVG files
are not available.

**Configuration:** only specify the icon class, no `svgPath`:

```php
'Icon' => [
    'sets' => [
        'bs' => \Templating\View\Icon\BootstrapIcon::class,
        // Optional: Add 'path' for icon name collection (backend browser)
        'bs-with-names' => [
            'class' => \Templating\View\Icon\BootstrapIcon::class,
            'path' => WWW_ROOT . 'node_modules/bootstrap-icons/font/bootstrap-icons.json',
        ],
    ],
],
```

**Output:** traditional HTML with CSS classes or data attributes:

```html
<i class="bi bi-house"></i>
<span data-lucide="home"></span>
<span class="material-icons">home</span>
```

**Requirements:**

- Icon font CSS files must be loaded in your layout.
- Font files must be accessible to browsers.

### SVG JSON map (recommended)

**When to use:** production environments, maximum performance, full icon
libraries.

**Configuration:** set `svgPath` to a `.json` file containing all icon
definitions:

```php
'Icon' => [
    'sets' => [
        'feather' => [
            'class' => \Templating\View\Icon\FeatherIcon::class,
            'svgPath' => WWW_ROOT . 'node_modules/feather-icons/dist/icons.json', // For rendering
            'path' => WWW_ROOT . 'node_modules/feather-icons/dist/icons.json',    // For name collection
            'svgAttributes' => [ // Custom SVG wrapper attributes
                'width' => '20',
                'height' => '20',
                'stroke-width' => '1.5',
            ],
        ],
    ],
],
```

**JSON format expected:**

```json
{
  "home": "<path d='M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z'/>",
  "user": "<circle cx='12' cy='7' r='4'/><path d='M5.5 21v-2a7.5 7.5 0 0115 0v2'/>"
}
```

**Output:** inline SVG with wrapper attributes:

```html
<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
  <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
</svg>
```

**Performance:** single file load, all icons cached in memory.

### SVG individual files

**When to use:** custom icon sets, selective icon usage, or when JSON maps are
not available.

**Configuration:** set `svgPath` to a directory containing `.svg` files:

```php
'Icon' => [
    'sets' => [
        'lucide' => [
            'class' => \Templating\View\Icon\LucideIcon::class,
            'svgPath' => WWW_ROOT . 'node_modules/lucide-static/icons/', // For rendering
            'path' => WWW_ROOT . 'node_modules/lucide-static/icons/',    // For name collection
            'cache' => 'default', // Highly recommended for file-based mode
        ],
        'heroicons' => [
            'class' => \Templating\View\Icon\HeroiconsIcon::class,
            'svgPath' => WWW_ROOT . 'node_modules/heroicons/24/',       // For rendering (includes outline/solid subdirs)
            'path' => WWW_ROOT . 'node_modules/heroicons/24/',          // For name collection
            'style' => 'outline', // 'outline' or 'solid'
        ],
    ],
],
```

**Output:** inline SVG loaded from individual files:

```html
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
  <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
</svg>
```

**Performance:** each icon requires a file system read (cached after first
load). Use caching for optimal performance.

### SVG inlining and optimization

Control SVG output optimization:

```php
'Icon' => [
    'sets' => [
        'bs' => [
            'class' => \Templating\View\Icon\BootstrapIcon::class,
            'path' => WWW_ROOT . 'bootstrap-icons/icons/',
            'svgPath' => true,
            'inline' => true, // Compress SVG output
        ],
    ],
],
```

**Default behavior:**

- **Production** (`debug = false`): `inline = true` (compressed).
- **Development** (`debug = true`): `inline = false` (readable).

**Before inlining:**

```xml
<!-- Bootstrap Icons v1.11.0 -->
<svg
  class="bi bi-house"
  xmlns="http://www.w3.org/2000/svg"
  width="16"
  height="16"
>
  <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"/>
</svg>
```

**After inlining:**

```xml
<svg class="bi bi-house" xmlns="http://www.w3.org/2000/svg" width="16" height="16"><path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"/></svg>
```

## Icon set configurations

### Bootstrap Icons

**Font-based rendering:**

```php
'bs' => [
    'class' => \Templating\View\Icon\BootstrapIcon::class,
    'path' => WWW_ROOT . 'node_modules/bootstrap-icons/font/bootstrap-icons.json', // For names() method
],
// Output: <i class="bi bi-house"></i>
```

**SVG individual files:**

```php
'bs' => [
    'class' => \Templating\View\Icon\BootstrapIcon::class,
    'path' => WWW_ROOT . 'node_modules/bootstrap-icons/font/bootstrap-icons.json', // For names() method
    'svgPath' => WWW_ROOT . 'node_modules/bootstrap-icons/icons/', // Directory with .svg files
    'cache' => 'default', // Recommended for file-based mode
],
// Output: <svg>...</svg> (loaded from individual .svg files)
```

### FontAwesome

::: tip FontAwesome 7
FontAwesome 7 uses the same CSS class syntax and metadata format as
FontAwesome 6. The `FontAwesome7Icon` class is provided as an alias for clarity
and forward compatibility.
:::

**Font-based rendering (FontAwesome 6/7):**

```php
'fa6' => [
    'class' => \Templating\View\Icon\FontAwesome6Icon::class,
    'path' => WWW_ROOT . 'node_modules/@fortawesome/fontawesome-free/metadata/icons.json', // For names() method
],
// Output: <i class="fas fa-home"></i>
```

**SVG individual files (FontAwesome 6):**

```php
'fa6' => [
    'class' => \Templating\View\Icon\FontAwesome6Icon::class,
    'svgPath' => WWW_ROOT . 'node_modules/@fortawesome/fontawesome-free/svgs/solid/', // Directory with .svg files
    'path' => WWW_ROOT . 'node_modules/@fortawesome/fontawesome-free/metadata/icons.json', // For names() method
    'cache' => 'default', // Recommended for file-based mode
],
// Output: <svg>...</svg> (loaded from individual .svg files)
```

**Multiple styles with SVG:**

```php
'fa6-solid' => [
    'class' => \Templating\View\Icon\FontAwesome6Icon::class,
    'svgPath' => WWW_ROOT . 'fontawesome/svgs/solid/', // SVG individual files mode
    'namespace' => 'solid',
    'cache' => 'default',
],
'fa6-regular' => [
    'class' => \Templating\View\Icon\FontAwesome6Icon::class,
    'svgPath' => WWW_ROOT . 'fontawesome/svgs/regular/', // SVG individual files mode
    'namespace' => 'regular',
    'cache' => 'default',
],
```

### Lucide Icons

**Font-based rendering:**

```php
'lucide' => [
    'class' => \Templating\View\Icon\LucideIcon::class,
    'path' => WWW_ROOT . 'node_modules/lucide-static/icons/', // For names() method
],
// Output: <span data-lucide="home"></span>
```

**SVG individual files (recommended for Lucide):**

```php
'lucide' => [
    'class' => \Templating\View\Icon\LucideIcon::class,
    'path' => WWW_ROOT . 'node_modules/lucide-static/icons/', // For names() method
    'svgPath' => true, // Directory with .svg files (use same as path)
    'cache' => 'default', // Highly recommended
    'inline' => true, // Compress SVG output
],
// Output: <svg>...</svg> (loaded from individual .svg files)
```

::: info
As of the current version, Lucide does not provide JSON map files, so individual
file mode is the only SVG option.
:::

**Example usage:**

```php
echo $this->Icon->render('lucide:home');
echo $this->Icon->render('lucide:user-circle');
```

### Heroicons

**Font-based rendering:**

```php
'heroicons' => [
    'class' => \Templating\View\Icon\HeroiconsIcon::class,
    'path' => WWW_ROOT . 'node_modules/heroicons/24/', // For names() method
    'style' => 'outline', // 'outline' or 'solid'
],
// Output: <span class="heroicon-outline"></span>
```

**SVG individual files:**

```php
'heroicons' => [
    'class' => \Templating\View\Icon\HeroiconsIcon::class,
    'path' => WWW_ROOT . 'node_modules/heroicons/24/', // For names() method
    'svgPath' => true, // Directory with style subdirs (use same as path)
    'style' => 'outline', // 'outline' or 'solid'
    'cache' => 'default',
],
// Output: <svg>...</svg> (loaded from 24/outline/*.svg or 24/solid/*.svg)
```

**Different sizes with SVG:**

```php
// 24x24 icons (outline and solid styles available)
'heroicons-24' => [
    'class' => \Templating\View\Icon\HeroiconsIcon::class,
    'svgPath' => WWW_ROOT . 'node_modules/heroicons/24/', // SVG individual files mode
    'style' => 'outline',
    'cache' => 'default',
],

// 20x20 icons (solid only)
'heroicons-20' => [
    'class' => \Templating\View\Icon\HeroiconsIcon::class,
    'svgPath' => WWW_ROOT . 'node_modules/heroicons/20/', // SVG individual files mode
    'style' => 'solid', // Only solid available for 20px
    'cache' => 'default',
],
```

### Feather Icons

**Font-based (data attributes):**

```php
'feather' => [
    'class' => \Templating\View\Icon\FeatherIcon::class,
    // No svgPath - uses data-feather attributes for client-side rendering
    'path' => WWW_ROOT . 'node_modules/feather-icons/dist/icons.json', // For name collection
],
```

**SVG JSON map (recommended):**

```php
'feather' => [
    'class' => \Templating\View\Icon\FeatherIcon::class,
    'path' => WWW_ROOT . 'node_modules/feather-icons/dist/icons.json',    // Same file for names
    'svgPath' => true, // JSON map for rendering (use same as path)
    'svgAttributes' => [
        'width' => '20',
        'height' => '20',
        'stroke-width' => '1.5',
    ],
],
```

**SVG individual files:**

```php
'feather' => [
    'class' => \Templating\View\Icon\FeatherIcon::class,
    'path' => WWW_ROOT . 'node_modules/feather-icons/dist/icons.json',   // JSON file for names
    'svgPath' => WWW_ROOT . 'node_modules/feather-icons/dist/icons/',    // Directory of .svg files
    'cache' => 'default',
],
```

### Material Icons

**Font-based (CSS classes with text content):**

```php
'material' => [
    'class' => \Templating\View\Icon\MaterialIcon::class,
    'namespace' => 'material-icons', // CSS class name
    // For name collection from TypeScript definitions
    'path' => WWW_ROOT . 'node_modules/material-symbols/index.d.ts',
],
```

**SVG individual files:**

```php
'material' => [
    'class' => \Templating\View\Icon\MaterialIcon::class,
    'path' => WWW_ROOT . 'node_modules/material-symbols/index.d.ts',     // TypeScript file for names
    'svgPath' => WWW_ROOT . 'node_modules/material-symbols/svg/',        // Directory of .svg files
    'namespace' => 'material-symbols-outlined', // Used for font fallback
    'cache' => 'default',
],
```

**Different Material Icon styles:**

```php
'material-outlined' => [
    'class' => \Templating\View\Icon\MaterialIcon::class,
    'path' => WWW_ROOT . 'material-symbols/index.d.ts',
    'svgPath' => WWW_ROOT . 'material-symbols/outlined/',
    'namespace' => 'material-symbols-outlined',
],
'material-rounded' => [
    'class' => \Templating\View\Icon\MaterialIcon::class,
    'path' => WWW_ROOT . 'material-symbols/index.d.ts',
    'svgPath' => WWW_ROOT . 'material-symbols/rounded/',
    'namespace' => 'material-symbols-rounded',
],
'material-sharp' => [
    'class' => \Templating\View\Icon\MaterialIcon::class,
    'path' => WWW_ROOT . 'material-symbols/index.d.ts',
    'svgPath' => WWW_ROOT . 'material-symbols/sharp/',
    'namespace' => 'material-symbols-sharp',
],
```

**Example output:**

```php
// Font mode
echo $this->Icon->render('home');
// <span class="material-icons">home</span>

// SVG mode
echo $this->Icon->render('home');
// <svg>...</svg>
```

## Configuration reference

### Complete configuration options

```php
'Icon' => [
    // Global settings
    'cache' => 'default',           // Cache configuration name or false to disable
    'checkExistence' => true,       // Validate icon names exist (disable in production)
    'autoPrefix' => true,          // Auto-create unprefixed aliases for all icons
    'separator' => ':',            // Separator for namespaced icons (set:icon)

    // Global attributes applied to all icons
    'attributes' => [
        'data-toggle' => 'tooltip',
        'class' => 'icon',
    ],

    // Icon aliases/mapping
    'map' => [
        'home' => 'bs:house',
        'user' => 'fa6:user',
        'settings' => 'bs:gear',
    ],

    // Icon sets configuration
    'sets' => [
        'bs' => [
            'class' => \Templating\View\Icon\BootstrapIcon::class,

            // Icon name collection (for backend browser, IDE helper)
            'path' => WWW_ROOT . 'node_modules/bootstrap-icons/font/bootstrap-icons.json',

            // SVG rendering (optional)
            'svgPath' => WWW_ROOT . 'node_modules/bootstrap-icons/icons/',

            // Caching (inherits global if not set)
            'cache' => 'icons',

            // SVG optimization (defaults based on debug mode)
            'inline' => true,

            // Set-specific attributes
            'attributes' => [
                'class' => 'bi-icon',
            ],

            // Additional icon-specific config
            'namespace' => 'custom-namespace',  // For Material Icons
            'style' => 'outline',              // For Heroicons
            'svgAttributes' => [               // For JSON map SVG wrapper
                'width' => '20',
                'height' => '20',
                'stroke-width' => '1.5',
            ],
        ],
    ],
],
```

### Path configuration types

Different icon sets support different metadata file formats:

| Icon Set | Supported Path Formats |
|----------|------------------------|
| **Bootstrap** | `.json` (bootstrap-icons.json) |
| **FontAwesome 4** | `.less`, `.scss` (variables files) |
| **FontAwesome 5/6** | `.json`, `.yml` (icons metadata) |
| **Feather** | `.json` (icons.json) |
| **Heroicons** | `.json`, directory with `outline/` and `solid/` subdirs |
| **Lucide** | `.json`, directory of `.svg` files |
| **Material** | `.ts` (TypeScript definitions) |

### SVG path configuration types

For SVG rendering, different formats are supported:

| Format | Description | Performance | Use Case |
|--------|-------------|-------------|----------|
| **JSON Map** | Single `.json` file with all icons | Best | Production |
| **Directory** | Folder containing `.svg` files | Good (with cache) | Selective usage |
| **Subdirectories** | Style-based subdirs (Heroicons) | Good (with cache) | Multi-style icons |

### Configuration inheritance

Settings inherit in this order (higher priority overwrites lower):

1. **Method parameters** (render options/attributes)
2. **Set-specific config** (`sets.bs.attributes`)
3. **Global config** (`attributes`)
4. **Helper defaults** (built-in defaults)

Example:

```php
'Icon' => [
    'attributes' => ['class' => 'global-icon'],           // Priority 3
    'sets' => [
        'bs' => [
            'class' => \Templating\View\Icon\BootstrapIcon::class,
            'attributes' => ['class' => 'bs-icon'],       // Priority 2
        ],
    ],
],
```

```php
// In template
echo $this->Icon->render('home', [], ['class' => 'custom']); // Priority 1
// Result: class="bi bi-home custom" (built-in + method parameter)
```

### Icon resolution order

When `autoPrefix` is enabled, icons are resolved in this order:

1. **Manual mappings** (`map` configuration)
2. **Explicit prefixes** (`set:icon` syntax)
3. **Auto-prefixed icons** (first-match from all sets)
4. **Default set** (first configured set)

```php
'Icon' => [
    'autoPrefix' => true,
    'map' => ['home' => 'fa6:house'],           // 1. Manual mapping wins
    'sets' => [
        'bs' => \Templating\View\Icon\BootstrapIcon::class,    // 4. Default set
        'fa6' => \Templating\View\Icon\FontAwesome6Icon::class,
    ],
],
```

```php
echo $this->Icon->render('home');        // Uses fa6:house (manual mapping)
echo $this->Icon->render('fa6:home');    // Uses fa6:home (explicit prefix)
echo $this->Icon->render('gear');        // Uses bs:gear (auto-prefix, first found)
```

## Performance and caching

### Cache configuration

Enable caching for better performance using global and/or per-set configuration:

```php
'Icon' => [
    'cache' => 'default', // Global cache - used for icon name lists (names() method)
    'sets' => [
        'bs' => [
            'class' => \Templating\View\Icon\BootstrapIcon::class,
            'path' => WWW_ROOT . 'bootstrap-icons/icons/',
            'svgPath' => true,
            'cache' => 'icons', // Per-set cache - used for SVG content caching
        ],
        'fa6' => [
            'class' => \Templating\View\Icon\FontAwesome6Icon::class,
            'path' => WWW_ROOT . 'fontawesome/svgs/solid/',
            'svgPath' => true,
            // No cache specified - inherits global 'default' cache
        ],
    ],
],
```

**Cache configuration levels:**

1. **Global cache** (`'cache' => 'default'`)
   - Caches icon name lists from the `names()` method.
   - Used by IconCollection for backend browser performance.
   - Applies to all sets unless overridden.
2. **Per-set cache** (`sets.bs.cache`)
   - Caches SVG file content when using `svgPath`.
   - Allows fine-tuning different cache backends per icon set.
   - Overrides the global cache setting for that specific set.

**Two-tier caching system:**

1. **In-memory cache** — caches content per request (automatic).
2. **CakePHP cache** — persists across requests using your cache configuration.

### Cache storage types

**File cache (default):**

```php
// In config/app.php
'Cache' => [
    'icons' => [
        'className' => 'Cake\Cache\Engine\FileEngine',
        'duration' => '+1 days',
        'path' => CACHE . 'icons/',
    ],
],
```

**Redis cache:**

```php
'Cache' => [
    'icons' => [
        'className' => 'Cake\Cache\Engine\RedisEngine',
        'duration' => '+1 days',
        'host' => '127.0.0.1',
        'port' => 6379,
    ],
],
```

### Performance tips

1. **Use JSON map mode** for icon sets that support it.
2. **Enable caching** in production environments.
3. **Use SVG inlining** to reduce HTTP requests.
4. **Configure an appropriate cache duration** based on deployment frequency.

### Performance in production

```php
'Icon' => [
    'cache' => 'default',
    'checkExistence' => false, // Disable in production
    'sets' => [
        'bs' => [
            'class' => \Templating\View\Icon\BootstrapIcon::class,
            'svgPath' => WWW_ROOT . 'icons/bootstrap-icons.json', // Use JSON map
            'inline' => true, // Enable compression
            'cache' => 'default',
        ],
    ],
],
```

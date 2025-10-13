# Icon Helper

A comprehensive CakePHP helper for rendering icons from popular icon libraries with support for both font-based and SVG rendering modes.

## Table of Contents

1. [Overview](#overview)
   - [Rendering Modes](#rendering-modes)
2. [Quick Start](#quick-start)
3. [Installation & Setup](#installation--setup)
4. [Supported Icon Sets](#supported-icon-sets)
5. [Configuration](#configuration)
   - [Basic Configuration](#basic-configuration)
   - [Global Attributes](#global-attributes)
   - [Icon Mapping](#icon-mapping)
6. [Rendering Modes Explained](#rendering-modes-explained)
   - [Font-Based Rendering](#font-based-rendering)
   - [SVG Individual Files](#svg-individual-files)
   - [SVG JSON Map (Recommended)](#svg-json-map-recommended)
   - [SVG Inlining & Optimization](#svg-inlining--optimization)
   - [Caching System](#caching-system)
7. [Usage](#usage)
   - [Basic Rendering](#basic-rendering)
   - [Icon Prefixes](#icon-prefixes)
   - [Attributes & Options](#attributes--options)
   - [Getting Available Icons](#getting-available-icons)
8. [Icon Set Configurations](#icon-set-configurations)
   - [Bootstrap Icons](#bootstrap-icons)
   - [FontAwesome](#fontawesome)
   - [Lucide Icons](#lucide-icons)
   - [Heroicons](#heroicons)
   - [Feather Icons](#feather-icons)
   - [Material Icons](#material-icons)
9. [Configuration Reference](#configuration-reference)
   - [Complete Configuration Options](#complete-configuration-options)
   - [Path Configuration Types](#path-configuration-types)
   - [SVG Path Configuration Types](#svg-path-configuration-types)
   - [Configuration Inheritance](#configuration-inheritance)
10. [Backend Browser](#backend-browser)
11. [Performance & Caching](#performance--caching)
12. [IDE Auto-Complete](#ide-auto-complete)
13. [Creating Custom Icon Sets](#creating-custom-icon-sets)
14. [Tips & Best Practices](#tips--best-practices)

## Overview

The Icon Helper provides a unified interface for rendering icons from multiple popular icon libraries. It supports three distinct rendering modes, each optimized for different use cases and performance requirements.

### Rendering Modes

**1. Font-Based Icons** (Traditional)
- Uses icon fonts (CSS classes or data attributes)
- Requires loading CSS/font files
- Lightweight setup, familiar approach
- Limited customization options

**2. Individual SVG Files**
- Each icon loaded from separate `.svg` files
- Full SVG customization capabilities
- Ideal for selective icon usage or custom sets
- Requires file system access per icon

**3. JSON Map (Recommended)**
- All icons loaded from single JSON file
- Best performance (single file load)
- Full SVG customization + caching benefits
- Most efficient for large icon libraries

**Key Features:**
- Support for 8+ popular icon libraries
- Automatic mode detection based on configuration
- Advanced two-tier caching system
- Icon mapping and aliasing
- Backend browser interface
- IDE auto-completion support
- Performance optimizations for production

## Quick Start

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

## Installation & Setup

### 1. Load the Helper

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

### 2. Install Icon Libraries

> [!NOTE]
> Using npm is not required - this plugin does not handle asset management or shipping of icon files. You can install icon libraries using any method (npm, composer, CDN, manual download, etc.). The plugin only needs to know where the files are located via configuration paths.

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

> [!IMPORTANT]
> For font-based icons, you must include the necessary CSS/font files in your layout yourself. The plugin only generates the HTML markup - it does not handle loading stylesheets or font files.

> [!WARNING]
> When deploying your application, ensure icon files are available in production. If using npm-installed icons, include them in your build/deployment process or copy them to a permanent location. The plugin expects icon files to exist at the configured paths - missing files will cause runtime errors.

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

## Supported Icon Sets

| Icon Set | Class | Icons | License | NPM Package |
|----------|-------|-------|---------|-------------|
| **Bootstrap Icons** | `BootstrapIcon` | 1,800+ | MIT | `bootstrap-icons` |
| **FontAwesome v6** | `FontAwesome6Icon` | 2,000+ | Font Awesome | `@fortawesome/fontawesome-free` |
| **FontAwesome v5** | `FontAwesome5Icon` | 1,600+ | Font Awesome | `fontawesome-free` |
| **FontAwesome v4** | `FontAwesome4Icon` | 800+ | Font Awesome | `font-awesome` |
| **Lucide** | `LucideIcon` | 1,000+ | ISC | `lucide-static` |
| **Heroicons** | `HeroiconsIcon` | 300+ | MIT | `heroicons` |
| **Feather** | `FeatherIcon` | 280+ | MIT | `feather-icons` |
| **Material Icons** | `MaterialIcon` | 2,000+ | Apache 2.0 | `material-symbols` |

## Configuration

### Basic Configuration

```php
'Icon' => [
    'sets' => [
        'bs' => \Templating\View\Icon\BootstrapIcon::class,
        'fa6' => [
            'class' => \Templating\View\Icon\FontAwesome6Icon::class,
            'svgPath' => WWW_ROOT . 'fontawesome/svgs/solid/',
            'cache' => 'default',
        ],
    ],
    'cache' => 'default',
    'checkExistence' => true,
],
```

### Global Attributes

Set default attributes that apply to all icons:

```php
'Icon' => [
    'attributes' => [
        'data-bs-toggle' => 'tooltip',
        'class' => 'icon',
    ],
    'sets' => [
        'bs' => [
            'class' => \Templating\View\Icon\BootstrapIcon::class,
            'attributes' => [
                'class' => 'bi', // Set-specific class
            ],
        ],
    ],
],
```

### Icon Mapping

Create aliases for easier icon usage:

```php
'Icon' => [
    'map' => [
        'view' => 'bs:eye',
        'edit' => 'bs:pencil',
        'delete' => 'fa6:trash',
        'save' => 'bs:check',
        'cancel' => 'bs:x',
        'admin' => 'fa6:shield-halved',
        'user' => 'fa6:user',
        'settings' => 'bs:gear',
    ],
],
```

## Rendering Modes Explained

The Icon Helper automatically detects the rendering mode based on your configuration. 

> [!IMPORTANT]
> There are two different path configurations:

- **`path`** - Used for collecting icon names (metadata files like .json, .less, .ts)
- **`svgPath`** - Used for rendering SVG icons (either directory of .svg files or .json map)

Here's how each mode works:

### Font-Based Rendering

**When to use:** Simple setups, existing font-based workflows, or when SVG files are not available.

**Configuration:** Only specify the icon class, no `svgPath`:
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

**Output:** Traditional HTML with CSS classes or data attributes:
```html
<i class="bi bi-house"></i>
<span data-lucide="home"></span>
<span class="material-icons">home</span>
```

**Requirements:**
- Icon font CSS files must be loaded in your layout
- Font files must be accessible to browsers

### SVG Individual Files

**When to use:** Custom icon sets, selective icon usage, or when JSON maps are not available.

**Configuration:** Set `svgPath` to a directory containing `.svg` files:
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

**Output:** Inline SVG loaded from individual files:
```html
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
  <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
</svg>
```

**Performance:** Each icon requires a file system read (cached after first load). Use caching for optimal performance.

### SVG JSON Map (Recommended)

**When to use:** Production environments, maximum performance, full icon libraries.

**Configuration:** Set `svgPath` to a `.json` file containing all icon definitions:
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

**Output:** Inline SVG with wrapper attributes:
```html
<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
  <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
</svg>
```

**Performance:** Single file load, all icons cached in memory.

### SVG Inlining & Optimization

Control SVG output optimization:

```php
'Icon' => [
    'sets' => [
        'bs' => [
            'class' => \Templating\View\Icon\BootstrapIcon::class,
            'svgPath' => WWW_ROOT . 'bootstrap-icons/icons/',
            'inline' => true, // Compress SVG output
        ],
    ],
],
```

**Default Behavior:**
- **Production** (`debug = false`): `inline = true` (compressed)
- **Development** (`debug = true`): `inline = false` (readable)

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

### Caching System

Enable caching for better performance using global and/or per-set configuration:

```php
'Icon' => [
    'cache' => 'default', // Global cache - used for icon name lists (names() method)
    'sets' => [
        'bs' => [
            'class' => \Templating\View\Icon\BootstrapIcon::class,
            'svgPath' => WWW_ROOT . 'bootstrap-icons/icons/',
            'cache' => 'icons', // Per-set cache - used for SVG content caching
        ],
        'fa6' => [
            'class' => \Templating\View\Icon\FontAwesome6Icon::class,
            'svgPath' => WWW_ROOT . 'fontawesome/svgs/solid/',
            // No cache specified - inherits global 'default' cache
        ],
    ],
],
```

**Cache Configuration Levels:**

1. **Global Cache** (`'cache' => 'default'`)
   - Caches icon name lists from `names()` method
   - Used by IconCollection for backend browser performance
   - Applies to all sets unless overridden

2. **Per-Set Cache** (`sets.bs.cache`)
   - Caches SVG file content when using `svgPath`
   - Allows fine-tuning different cache backends per icon set
   - Overrides global cache setting for that specific set

**Two-tier caching system:**
1. **In-memory cache** - Caches content per request (automatic)
2. **CakePHP cache** - Persists across requests using your cache configuration

## Usage

### Basic Rendering

```php
// Render using alias
echo $this->Icon->render('home');

// Render with explicit namespace
echo $this->Icon->render('bs:house');

// Render with attributes
echo $this->Icon->render('user', [], ['class' => 'text-primary', 'title' => 'User Profile']);
```

### Icon Prefixes & Auto-Prefixing

The Icon Helper provides flexible prefixing to handle multiple icon sets:

#### Auto-Prefixing (Default Behavior)

By default, `autoPrefix` is enabled, which automatically creates unprefixed aliases for all icons:

```php
'Icon' => [
    'autoPrefix' => true, // Default - creates automatic mappings
    'sets' => [
        'bs' => \Templating\View\Icon\BootstrapIcon::class,
        'fa6' => \Templating\View\Icon\FontAwesome6Icon::class,
    ],
],

// All these work without prefixes (first match wins)
echo $this->Icon->render('home');     // Uses bs:home (first set)
echo $this->Icon->render('house');    // Uses bs:house
echo $this->Icon->render('user');     // Uses bs:user or fa6:user (whichever is found first)

// Explicit prefixes always work
echo $this->Icon->render('bs:house');
echo $this->Icon->render('fa6:user');
```

#### Manual Prefixing Only

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

// Only explicit prefixes and manual mappings work
echo $this->Icon->render('bs:house');  // ✓ Works
echo $this->Icon->render('fa6:user');  // ✓ Works
echo $this->Icon->render('home');      // ✓ Works (manual mapping)
echo $this->Icon->render('house');     // ✗ Fails (no mapping, no auto-prefix)
```

#### Handling Icon Conflicts

When multiple sets have the same icon name:

```php
'Icon' => [
    'sets' => [
        'bs' => \Templating\View\Icon\BootstrapIcon::class,    // First set (default)
        'fa6' => \Templating\View\Icon\FontAwesome6Icon::class,
    ],
],

// Both sets have a "home" icon
echo $this->Icon->render('home');     // Uses Bootstrap (first set)
echo $this->Icon->render('bs:home');  // Explicitly Bootstrap
echo $this->Icon->render('fa6:home'); // Explicitly FontAwesome

// Use manual mapping to resolve conflicts
'Icon' => [
    'map' => [
        'home' => 'bs:house',        // Use Bootstrap house for "home"
        'home-alt' => 'fa6:home',    // Alternative FontAwesome home
    ],
],
```

### Attributes & Options

```php
echo $this->Icon->render('settings',
    ['translate' => false], // Options
    ['class' => 'icon-lg', 'data-toggle' => 'tooltip'] // HTML attributes
);
```

**Available options:**
- `translate` (bool): Whether to translate the title attribute
- `titleField` (string): Attribute name for auto-generated titles
- `title` (string|false): Custom title or disable auto-title

### Getting Available Icons

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
```
Icon set: bs
  - house
  - gear
  - person
Icon set: fa6
  - home
  - user
  - cog
```

## Icon Set Configurations

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
    'svgPath' => WWW_ROOT . 'node_modules/bootstrap-icons/icons/', // Directory with .svg files
    'path' => WWW_ROOT . 'node_modules/bootstrap-icons/font/bootstrap-icons.json', // For names() method
    'cache' => 'default', // Recommended for file-based mode
],
// Output: <svg>...</svg> (loaded from individual .svg files)
```

### FontAwesome

**Font-based rendering (FontAwesome 6):**
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
    'svgPath' => WWW_ROOT . 'node_modules/lucide-static/icons/', // Directory with .svg files
    'path' => WWW_ROOT . 'node_modules/lucide-static/icons/', // For names() method
    'cache' => 'default', // Highly recommended
    'inline' => true, // Compress SVG output
],
// Output: <svg>...</svg> (loaded from individual .svg files)
```

> [!NOTE]
> Lucide does not provide JSON map files, so individual file mode is the only SVG option.

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
    'svgPath' => WWW_ROOT . 'node_modules/heroicons/24/', // Directory with style subdirs
    'path' => WWW_ROOT . 'node_modules/heroicons/24/', // For names() method
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

**SVG JSON Map (Recommended):**
```php
'feather' => [
    'class' => \Templating\View\Icon\FeatherIcon::class,
    'svgPath' => WWW_ROOT . 'node_modules/feather-icons/dist/icons.json', // JSON map for rendering
    'path' => WWW_ROOT . 'node_modules/feather-icons/dist/icons.json',    // Same file for names
    'svgAttributes' => [
        'width' => '20',
        'height' => '20',
        'stroke-width' => '1.5',
    ],
],
```

**SVG Individual Files:**
```php
'feather' => [
    'class' => \Templating\View\Icon\FeatherIcon::class,
    'svgPath' => WWW_ROOT . 'node_modules/feather-icons/dist/icons/',    // Directory of .svg files
    'path' => WWW_ROOT . 'node_modules/feather-icons/dist/icons.json',   // JSON file for names
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

**SVG Individual Files:**
```php
'material' => [
    'class' => \Templating\View\Icon\MaterialIcon::class,
    'svgPath' => WWW_ROOT . 'node_modules/material-symbols/svg/',        // Directory of .svg files
    'path' => WWW_ROOT . 'node_modules/material-symbols/index.d.ts',     // TypeScript file for names
    'namespace' => 'material-symbols-outlined', // Used for font fallback
    'cache' => 'default',
],
```

**Different Material Icon styles:**
```php
'material-outlined' => [
    'class' => \Templating\View\Icon\MaterialIcon::class,
    'svgPath' => WWW_ROOT . 'material-symbols/outlined/',
    'path' => WWW_ROOT . 'material-symbols/index.d.ts',
    'namespace' => 'material-symbols-outlined',
],
'material-rounded' => [
    'class' => \Templating\View\Icon\MaterialIcon::class,
    'svgPath' => WWW_ROOT . 'material-symbols/rounded/',
    'path' => WWW_ROOT . 'material-symbols/index.d.ts',
    'namespace' => 'material-symbols-rounded',
],
'material-sharp' => [
    'class' => \Templating\View\Icon\MaterialIcon::class,
    'svgPath' => WWW_ROOT . 'material-symbols/sharp/',
    'path' => WWW_ROOT . 'material-symbols/index.d.ts',
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

## Configuration Reference

### Complete Configuration Options

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

### Path Configuration Types

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

### SVG Path Configuration Types

For SVG rendering, different formats are supported:

| Format | Description | Performance | Use Case |
|--------|-------------|-------------|----------|
| **JSON Map** | Single `.json` file with all icons | Best | Production |
| **Directory** | Folder containing `.svg` files | Good (with cache) | Selective usage |
| **Subdirectories** | Style-based subdirs (Heroicons) | Good (with cache) | Multi-style icons |

### Configuration Inheritance

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

// In template
echo $this->Icon->render('home', [], ['class' => 'custom']); // Priority 1
// Result: class="bi bi-home custom" (built-in + method parameter)
```

### Icon Resolution Order

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

echo $this->Icon->render('home');        // Uses fa6:house (manual mapping)
echo $this->Icon->render('fa6:home');    // Uses fa6:home (explicit prefix)
echo $this->Icon->render('gear');        // Uses bs:gear (auto-prefix, first found)
```

## Backend Browser

The plugin provides a built-in icon browser interface accessible at:
```
/admin/templating/icons
```

**Features:**
- Browse all configured icon sets
- View icon conflicts between sets
- Test icon rendering
- Copy icon names for use in templates

**Securing Access:**
```php
// In your routes.php or controller
$routes->prefix('Admin', function (RouteBuilder $builder) {
    $builder->connect('/templating/icons', [
        'plugin' => 'Templating',
        'controller' => 'Icons',
        'action' => 'index',
        '_auth' => true, // Require authentication
    ]);
});
```

## Performance & Caching

### Cache Configuration

You can configure caching at the **global level** (applies to all sets) or **per-set level** (fine-tune individual sets):

```php
'Icon' => [
    'cache' => 'default', // Global cache - used for icon name lists
    'sets' => [
        'bs' => [
            'class' => \Templating\View\Icon\BootstrapIcon::class,
            'svgPath' => WWW_ROOT . 'bootstrap-icons/icons/',
            'cache' => 'icons', // Per-set cache - overrides global for SVG content
        ],
        'fa6' => [
            'class' => \Templating\View\Icon\FontAwesome6Icon::class,
            'svgPath' => WWW_ROOT . 'fontawesome/svgs/solid/',
            // No cache specified - inherits global 'default' cache
        ],
    ],
],
```

**Cache levels:**
- **Global cache**: Caches icon name lists (`names()` method results)
- **Per-set cache**: Caches SVG content for individual icon sets
- **Inheritance**: Sets inherit global cache unless explicitly overridden

### Cache Storage Types

**File Cache (Default):**
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

**Redis Cache:**
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

### Performance Tips

1. **Use JSON Map mode** for icon sets that support it
2. **Enable caching** in production environments
3. **Use SVG inlining** to reduce HTTP requests
4. **Configure appropriate cache duration** based on deployment frequency

## IDE Auto-Complete

Get full auto-completion for icon names using the [IdeHelper plugin](https://github.com/dereuromark/cakephp-ide-helper/):

### 1. Install IdeHelper

```bash
composer require --dev dereuromark/cakephp-ide-helper
```

### 2. Configure Generator Task

```php
// In config/app.php
'IdeHelper' => [
    'generatorTasks' => [
        \Templating\Generator\Task\IconRenderTask::class,
    ],
],
```

### 3. Generate Annotations

```bash
ddev exec bin/cake ide_helper generate
```

### 4. IDE Auto-Complete

Your IDE will now provide auto-completion for:
```php
$this->Icon->render('| // Auto-complete shows available icons
```

## Creating Custom Icon Sets

### 1. Create Icon Class

```php
<?php declare(strict_types=1);

namespace App\View\Icon;

use Templating\View\Icon\AbstractIcon;
use Templating\View\HtmlStringable;

class CustomIcon extends AbstractIcon {

    public function __construct(array $config = []) {
        $config += [
            'template' => '<i class="custom-{{name}}"{{attributes}}></i>',
        ];

        parent::__construct($config);
    }

    public function names(): array {
        // Return array of available icon names
        return ['icon1', 'icon2', 'icon3'];
    }

    public function render(string $icon, array $options = [], array $attributes = []): HtmlStringable {
        if (!empty($this->config['attributes'])) {
            $attributes += $this->config['attributes'];
        }

        $options['name'] = $icon;
        $options['attributes'] = $this->template->formatAttributes($attributes);

        return $this->format($options);
    }
}
```

### 2. Register Icon Set

```php
'Icon' => [
    'sets' => [
        'custom' => \App\View\Icon\CustomIcon::class,
    ],
],
```

### 3. Use Custom Icons

```php
echo $this->Icon->render('custom:icon1');
```

## Tips & Best Practices

### Icon Mapping Strategy

Create semantic aliases for better maintainability:

```php
'Icon' => [
    'map' => [
        // Actions
        'create' => 'bs:plus-circle',
        'edit' => 'bs:pencil',
        'delete' => 'bs:trash',
        'save' => 'bs:check-circle',
        'cancel' => 'bs:x-circle',

        // Navigation
        'home' => 'bs:house',
        'back' => 'bs:arrow-left',
        'next' => 'bs:arrow-right',

        // Status
        'success' => 'bs:check-circle-fill',
        'warning' => 'bs:exclamation-triangle-fill',
        'error' => 'bs:x-circle-fill',
        'info' => 'bs:info-circle-fill',
    ],
],
```

### Accessibility

Always provide meaningful titles:

```php
echo $this->Icon->render('delete', [], [
    'title' => __('Delete this item'),
    'aria-label' => __('Delete'),
]);
```

### Performance in Production

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

### CSS Integration

For consistent styling across icon sets:

```css
.icon {
    width: 1em;
    height: 1em;
    display: inline-block;
    vertical-align: middle;
}

.icon-sm { width: 0.875em; height: 0.875em; }
.icon-lg { width: 1.25em; height: 1.25em; }
.icon-xl { width: 1.5em; height: 1.5em; }
```

### Animation Support

For FontAwesome animations:

```php
echo $this->Icon->render('fa6:spinner', [], [
    'class' => 'fa-spin',
    'title' => __('Loading...'),
]);
```

### Responsive Icons

```php
echo $this->Icon->render('home', [], [
    'class' => 'd-none d-md-inline', // Hide on mobile
]);
```

---

**Demo:** https://sandbox.dereuromark.de/sandbox/templating-examples/icons

**Repository:** https://github.com/dereuromark/cakephp-templating

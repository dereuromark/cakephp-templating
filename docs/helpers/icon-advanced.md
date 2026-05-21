# Icon Advanced

This page covers the backend icon browser, IDE auto-complete, creating your own
icon sets, and best practices. For everyday usage see the [Icon helper](./icon),
and for the configuration reference see [Icon Configuration](./icon-configuration).

## Backend browser

The plugin provides a built-in icon browser interface accessible at:

```text
/admin/templating/icons
```

**Features:**

- Browse all configured icon sets.
- View icon conflicts between sets.
- Test icon rendering.
- Copy icon names for use in templates.

### Securing access

::: warning
The backend browser is not protected by default. Make sure to restrict access
behind your application's authentication/authorization.
:::

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

## IDE auto-complete

Get full auto-completion for icon names using the
[IdeHelper plugin](https://github.com/dereuromark/cakephp-ide-helper/).

### 1. Install IdeHelper

```bash
composer require --dev dereuromark/cakephp-ide-helper
```

### 2. Configure the generator task

```php
// In config/app.php
'IdeHelper' => [
    'generatorTasks' => [
        \Templating\Generator\Task\IconRenderTask::class,
    ],
],
```

### 3. Generate annotations

```bash
bin/cake ide_helper generate
```

### 4. IDE auto-complete

Your IDE will now provide auto-completion for the icon names:

```php
$this->Icon->render('| // Auto-complete shows available icons
```

## Creating custom icon sets

### 1. Create the icon class

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

### 2. Register the icon set

```php
'Icon' => [
    'sets' => [
        'custom' => \App\View\Icon\CustomIcon::class,
    ],
],
```

### 3. Use the custom icons

```php
echo $this->Icon->render('custom:icon1');
```

## Tips and best practices

### Icon mapping strategy

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

::: tip
When using aliasing, the title is by default set to the same name. So aliasing
helps to improve the out-of-the-box title building when you use speaking aliases.
:::

### CSS integration

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

### Animation support

For FontAwesome animations:

```php
echo $this->Icon->render('fa6:spinner', [], [
    'class' => 'fa-spin',
    'title' => __('Loading...'),
]);
```

### Responsive icons

```php
echo $this->Icon->render('home', [], [
    'class' => 'd-none d-md-inline', // Hide on mobile
]);
```

---

**Demo:** [sandbox.dereuromark.de/sandbox/templating-examples/icons](https://sandbox.dereuromark.de/sandbox/templating-examples/icons)

**Repository:** [github.com/dereuromark/cakephp-templating](https://github.com/dereuromark/cakephp-templating)

# Html Helper

An enhanced CakePHP HtmlHelper that automatically handles `HtmlStringable` objects.

## Setup
Include helper in your AppView class as
```php
$this->loadHelper('Templating.Html');
```

This replaces the core HtmlHelper with the enhanced version that supports `HtmlStringable` interface.

## Features

The Templating HtmlHelper extends CakePHP's core HtmlHelper and adds:
- Automatic support for `HtmlStringable` objects in link titles
- Convenience method to create `HtmlStringable` objects

### Supported Methods

#### link()
Creates a link element with optional icon or HTML content.

```php
// With icon
echo $this->Html->link(
    $this->Icon->render('home') . ' Home',
    ['controller' => 'Pages', 'action' => 'display', 'home']
);

// With HtmlStringable (automatically handles escaping)
echo $this->Html->link(
    $this->Icon->render('bs:eye'),
    ['action' => 'view', $id],
    ['title' => __('View')]
);
```

#### linkFromPath()
Creates a link from a named route with optional icon or HTML content.

```php
// With icon
echo $this->Html->linkFromPath(
    $this->Icon->render('user') . ' Profile',
    'Users::view',
    ['id' => $userId]
);
```

#### string()
Convenience method to create `HtmlStringable` objects from raw HTML strings.

```php
// Create an HtmlStringable object
$html = $this->Html->string('<strong>Bold text</strong>');

// Use it in links or other methods
echo $this->Html->link($html, '/some-url');

// Combine with icons
$title = $this->Icon->render('star') . $this->Html->string(' Featured');
echo $this->Html->link($title, ['action' => 'featured']);
```

## How It Works

When you pass a `HtmlStringable` object (like an Icon) as the title parameter, the helper:
1. Automatically sets `escapeTitle` to `false`
2. Converts the `HtmlStringable` to a string
3. Passes it to the parent HtmlHelper method

This means you don't need to manually set `'escapeTitle' => false` when using icons or other HTML content.

## Example Usage

```php
// Traditional way (still works)
echo $this->Html->link(
    $this->Icon->render('edit') . ' Edit',
    ['action' => 'edit', $id],
    ['escapeTitle' => false]
);

// New way (automatic handling)
echo $this->Html->link(
    $this->Icon->render('edit') . ' Edit',
    ['action' => 'edit', $id]
);

// Using string() method
$badge = $this->Html->string('<span class="badge">New</span>');
echo $this->Html->link(
    'Product ' . $badge,
    ['action' => 'view', $productId]
);

// Complex navigation example
echo $this->Html->link(
    $this->Icon->render('fa6:house') . ' ' . __('Dashboard'),
    ['controller' => 'Dashboard', 'action' => 'index'],
    ['class' => 'nav-link']
);
```

## Benefits

- Cleaner code - no need to remember `escapeTitle` option
- Works seamlessly with Icon helper
- Type-safe with `HtmlStringable` interface
- Compatible with all existing HtmlHelper functionality
- `string()` method provides explicit HTML content creation

## Working with HtmlStringable

The `HtmlStringable` interface is a marker interface that tells the helper "this content is already safe HTML and should not be escaped."

```php
// Any HtmlStringable object works
$icon = $this->Icon->render('check');
$html = $this->Html->string('<em>Emphasis</em>');

// Concatenate them
$combined = $icon . ' ' . $html . ' Text';

// Use in links
echo $this->Html->link($combined, '/url');
```

See [HtmlStringable docs](../HtmlStringable.md) for more details on the interface.

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
    $this->Icon->render('home'),
    ['controller' => 'Pages', 'action' => 'display', 'home'],
    //['escapeTitle' => false] is not necessary as it handles this internally
);

// With icon and text
echo $this->Html->link(
    $this->Html->string($this->Icon->render('bs:eye') . ' ' . __('View')),
    ['action' => 'view', $id],
    ['title' => __('View me')],
);
```

#### linkFromPath()
Creates a link from a named route with optional icon or HTML content.

```php
// With icon
echo $this->Html->linkFromPath(
    $this->Icon->render('user'),
    'Users::view',
    ['id' => $userId],
);
```

#### string()
Convenience method to create `HtmlStringable` objects from raw HTML strings.

```php
// Create an HtmlStringable object
$html = $this->Html->string('<strong>Bold text</strong>');

// Use it in links or other methods (pure HtmlStringable)
echo $this->Html->link($html, '/some-url');

// Combine text with icons
$title = $this->Html->string($this->Icon->render('star') . ' ' .  __('Featured'));
echo $this->Html->link($title, ['action' => 'featured']);
```

## How It Works

When you pass a `HtmlStringable` object (like an Icon) as the title parameter, the helper:
1. Automatically sets `escapeTitle` to `false`
2. Converts the `HtmlStringable` to a string
3. Passes it to the parent HtmlHelper method

**Important:** This automatic handling only works when passing a pure `HtmlStringable` object. When you concatenate with strings, you need to wrap using `$this->Html->string()`.

## Example Usage

```php
echo $this->Html->link(
    $this->Icon->render('edit'),
    ['action' => 'edit', $id],
);

// Icon with text
echo $this->Html->link(
    $this->Html->string($this->Icon->render('edit') . ' Edit'),
    ['action' => 'edit', $id],
);

// Using string() method (concatenation requires escapeTitle)
$badge = $this->Html->string('Product ' . '<span class="badge">New</span>');
echo $this->Html->link(
    $badge,
    ['action' => 'view', $productId],
);

// Complex navigation example
echo $this->Html->link(
    $this->Html->string($this->Icon->render('fa6:house') . ' ' . __('Dashboard')),
    ['controller' => 'Dashboard', 'action' => 'index'],
    ['class' => 'nav-link'],
);
```

## Benefits

- Cleaner code - no need to remember `escapeTitle` option when using pure `HtmlStringable` objects
- Works seamlessly with Icon helper
- Type-safe with `HtmlStringable` interface
- Compatible with all existing HtmlHelper functionality
- `string()` method provides explicit HTML content creation

## Important Notes

- **String concatenation breaks automatic handling**: When you concatenate a `HtmlStringable` object with a string using `.`, PHP's `__toString()` method is called and the result becomes a regular string. You need to wrap here using `$this->Html->string()`.

## Working with HtmlStringable

The `HtmlStringable` interface is a marker interface that tells the helper "this content is already safe HTML and should not be escaped."

```php
// Any HtmlStringable object works (automatic handling)
$icon = $this->Icon->render('check');
echo $this->Html->link($icon, '/url');  // No escapeTitle needed

// Concatenate them (becomes regular string, needs escapeTitle)
$html = $this->Html->string('<em>Emphasis</em>');
$combined = $icon . ' ' . $html . ' Text';
echo $this->Html->link($this->Html->string($combined), '/url');
```

See [HtmlStringable docs](../HtmlStringable.md) for more details on the interface.

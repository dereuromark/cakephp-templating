# Form Helper

An enhanced CakePHP FormHelper that automatically handles `HtmlStringable` objects.

## Setup
Include helper in your AppView class as
```php
$this->loadHelper('Templating.Form');
```

This replaces the core FormHelper with the enhanced version that supports `HtmlStringable` interface.

## Features

The Templating FormHelper extends CakePHP's core FormHelper and adds automatic support for `HtmlStringable` objects (like Icon renders) in button and link titles.

### Supported Methods

All methods work exactly like the core FormHelper methods, but with additional `HtmlStringable` support:

#### button()
Creates a button element with optional icon or HTML content.

```php
// With icon
echo $this->Form->button(
    $this->Icon->render('save') . ' Save',
);

// With HtmlStringable (automatically handles escaping)
echo $this->Form->button(
    $this->Icon->render('save'),
    ['type' => 'submit']
);
```

#### postLink()
Creates a link that submits a POST request with optional icon or HTML content.

```php
// With icon
echo $this->Form->postLink(
    $this->Icon->render('delete') . ' Delete',
    ['action' => 'delete', $id],
    ['confirm' => 'Are you sure?']
);
```

#### postButton()
Creates a button that submits a POST request with optional icon or HTML content.

```php
// With icon
echo $this->Form->postButton(
    $this->Icon->render('trash') . ' Remove',
    ['action' => 'remove', $id],
    ['confirm' => 'Remove this item?']
);
```

## How It Works

When you pass a `HtmlStringable` object (like an Icon) as the title parameter, the helper:
1. Automatically sets `escapeTitle` to `false`
2. Converts the `HtmlStringable` to a string
3. Passes it to the parent FormHelper method

This means you don't need to manually set `'escapeTitle' => false` when using icons or other HTML content.

## Example Usage

```php
// Traditional way (still works)
echo $this->Form->button(
    $this->Icon->render('save') . ' Save Record',
    ['escapeTitle' => false]
);

// New way (automatic handling)
echo $this->Form->button(
    $this->Icon->render('save') . ' Save Record'
);

// Complex example with postLink
echo $this->Form->postLink(
    $this->Icon->render('bs:trash') . ' ' . __('Delete'),
    ['action' => 'delete', $record->id],
    [
        'confirm' => __('Are you sure you want to delete {0}?', $record->name),
        'class' => 'btn btn-danger'
    ]
);
```

## Benefits

- Cleaner code - no need to remember `escapeTitle` option
- Works seamlessly with Icon helper
- Compatible with all existing FormHelper functionality
- Type-safe with `HtmlStringable` interface

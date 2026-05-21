# Form Helper

An enhanced CakePHP `FormHelper` that automatically handles
[`HtmlStringable`](./html-stringable) objects.

## Setup

Include the helper in your `AppView` class:

```php
$this->loadHelper('Templating.Form');
```

This replaces the core `FormHelper` with the enhanced version that supports the
`HtmlStringable` interface.

## Features

The Templating `FormHelper` extends CakePHP's core `FormHelper` and adds
automatic support for `HtmlStringable` objects (like Icon renders) in button and
link titles.

## Supported methods

All methods work exactly like the core `FormHelper` methods, but with additional
`HtmlStringable` support.

### button()

Creates a button element with optional icon or HTML content.

```php
// With icon
echo $this->Form->button(
    $this->Icon->render('save'),
    // ['escapeTitle' => false] is not necessary as it handles this internally
);

// With text
echo $this->Form->button(
    $this->Html->string('<b>Save</b>'),
    // ['escapeTitle' => false] is not necessary as it handles this internally
);

// With multiple elements or string concat requires wrapping
echo $this->Form->button(
    $this->Html->string($this->Icon->render('save') . ' ' . __('Save')),
    ['type' => 'submit'],
);
```

::: tip
When adding text, make sure it is safe and valid HTML — otherwise wrap it with
`h()`.
:::

### postLink()

Creates a link that submits a POST request with optional icon or HTML content.

```php
// With icon
echo $this->Form->postLink(
    $this->Icon->render('delete'),
    ['action' => 'delete', $id],
    ['confirm' => 'Are you sure?'],
);
```

### postButton()

Creates a button that submits a POST request with optional icon or HTML content.

```php
// With icon
echo $this->Form->postButton(
    $this->Icon->render('trash'),
    ['action' => 'remove', $id],
    ['confirm' => 'Remove this item?'],
);
```

## How it works

When you pass an `HtmlStringable` object (like an Icon) as the title parameter,
the helper:

1. automatically sets `escapeTitle` to `false`,
2. converts the `HtmlStringable` to a string,
3. passes it to the parent `FormHelper` method.

This means you do not need to manually set `'escapeTitle' => false` when using
icons or other HTML content.

## Example usage

```php
// Icon only - automatic handling (no escapeTitle needed)
echo $this->Form->button(
    $this->Icon->render('save'),
    ['type' => 'submit']
);

// Complex example with postLink
echo $this->Form->postLink(
    $this->Html->string($this->Icon->render('bs:trash') . ' ' . __('Delete')),
    ['action' => 'delete', $record->id],
    [
        'confirm' => __('Are you sure you want to delete {0}?', $record->name),
        'class' => 'btn btn-danger',
    ]
);
```

## Benefits

- Cleaner code — no need to remember the `escapeTitle` option when using pure
  `HtmlStringable` objects.
- Works seamlessly with the [Icon helper](./icon).
- Compatible with all existing `FormHelper` functionality.
- Type-safe with the `HtmlStringable` interface.

## Important notes

::: warning String concatenation breaks automatic handling
When you concatenate an `HtmlStringable` object with a string using `.`, PHP's
`__toString()` method is called and the result becomes a regular string. You
need to wrap it here using `$this->Html->string()`.
:::

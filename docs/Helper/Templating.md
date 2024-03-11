# Templating Helper

A CakePHP helper to handle some templating use cases. Contains convenience wrappers.

## Setup
Include helper in your AppView class as
```php
$this->loadHelper('Templating.Templating', [
    ...
]);
```

It provides the following methods:

- `ok()` - printing them with markup to color it green/red
- `warning()` - wrapping `ok()` as always "red" right away

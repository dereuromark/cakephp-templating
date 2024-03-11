# IconSnippet Helper

A CakePHP helper to handle some icon snippet use cases. Contains convenience wrappers.

## Setup
Include helper in your AppView class as
```php
$this->loadHelper('Templating.IconSnippet', [
    ...
]);
```

It provides the following methods:

- `neighbors()` - printing them as icon elements with URLS
- `thumbs()` - up/down icon
- `yesNo()` - printing an icon representation

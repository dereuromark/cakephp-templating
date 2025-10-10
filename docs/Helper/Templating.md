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
- `yesNo()` - convenience method combining icon rendering with colored output

## Methods

### ok()
Returns green text on true/ok, red text otherwise.

```php
// Basic usage
echo $this->Templating->ok('Success', true);  // Green text
echo $this->Templating->ok('Failed', false);  // Red text

// With HtmlStringable (icons)
echo $this->Templating->ok($this->Icon->render('check'), true);

// With attributes
echo $this->Templating->ok('Status', $isActive, ['class' => 'status-badge']);

// Disable escaping if needed
echo $this->Templating->ok('<strong>Bold</strong>', true, ['escape' => false]);
```

By default, this method escapes the content. Use the `escape` attribute set to `false` to disable escaping.

### warning()
Convenience wrapper for `ok()` that always returns red text unless explicitly ok.

```php
// Only shows red when not ok
echo $this->Templating->warning('Error occurred', false);  // Red text
echo $this->Templating->warning('All good', true);  // Plain text (not colored)

// With attributes
echo $this->Templating->warning($errorMessage, $hasError, ['class' => 'alert']);
```

### yesNo()
Convenience method that combines icon rendering with colored output. Returns a yes/no icon colored green/red based on the value.

Make sure to configure these icons in your `Icon.map` app config:
```php
'Icon' => [
    'map' => [
        'yes' => 'fa4:check',
        'no' => 'fa4:times',
    ],
],
```

**Usage:**
```php
// Basic usage
echo $this->Templating->yesNo($user->is_active);  // Green check icon
echo $this->Templating->yesNo($user->is_banned);  // Red times icon

// With options
echo $this->Templating->yesNo($value, [
    'onTitle' => __('Active'),
    'offTitle' => __('Inactive'),
]);

// Invert colors (green for no, red for yes)
echo $this->Templating->yesNo($hasErrors, [
    'invert' => true,  // Green when false, red when true
]);

// With attributes
echo $this->Templating->yesNo($status, [], [
    'class' => 'status-icon',
    'data-toggle' => 'tooltip',
]);
```

**Parameters:**
- `$value` (mixed): Value being internally bool casted
- `$options` (array): Optional configuration
  - `onTitle` (string): Title/tooltip for true value (default: "Yes")
  - `offTitle` (string): Title/tooltip for false value (default: "No")
  - `invert` (bool): If true, green for no/false, red for yes/true
- `$attributes` (array): HTML attributes for the icon element
  - `title`: Custom title attribute
  - Any other HTML attributes

**Returns:** String with colored icon HTML

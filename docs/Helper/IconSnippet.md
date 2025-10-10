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

- `neighbors()` - printing them as icon elements with URLs
- `thumbs()` - up/down icon (pro/contra)
- `yesNo()` - printing an icon representation

## Methods

### yesNo()
Displays yes/no symbol based on a boolean value.

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
echo $this->IconSnippet->yesNo($user->is_active);
echo $this->IconSnippet->yesNo($record->published);

// With custom titles
echo $this->IconSnippet->yesNo($value, [
    'onTitle' => __('Active'),
    'offTitle' => __('Inactive'),
]);

// With attributes
echo $this->IconSnippet->yesNo($status, [], [
    'class' => 'status-icon',
    'title' => __('Current status'),
]);
```

**Parameters:**
- `$value` (int|bool): Value to check (defaults to comparing against 1)
- `$options` (array): Optional configuration
  - `on` (int|bool): Value that represents "on/yes" (default: 1)
  - `onTitle` (string): Title for "yes" state (default: "Yes")
  - `offTitle` (string): Title for "no" state (default: "No")
- `$attributes` (array): HTML attributes for the icon

**Returns:** HtmlStringable icon

### thumbs()
Displays thumbs up/down icon based on a boolean value.

Make sure to configure these icons in your `Icon.map` app config:
```php
'Icon' => [
    'map' => [
        'pro' => 'fa4:thumbs-up',
        'contra' => 'fa4:thumbs-down',
    ],
],
```

**Usage:**
```php
// Basic usage
echo $this->IconSnippet->thumbs($review->is_positive);
echo $this->IconSnippet->thumbs($vote->approved);

// With options and attributes
echo $this->IconSnippet->thumbs($value, [], [
    'class' => 'vote-icon',
    'title' => __('User vote'),
]);
```

**Parameters:**
- `$value` (mixed): Boolish value (truthy = thumbs up, falsy = thumbs down)
- `$options` (array): Optional configuration
- `$attributes` (array): HTML attributes for the icon

**Returns:** HtmlStringable icon (thumbs-up for true, thumbs-down for false)

### neighbors()
Display neighbor quicklinks for prev/next navigation with icons.

Make sure to configure these icons in your `Icon.map` app config:
```php
'Icon' => [
    'map' => [
        'prev' => 'fa4:arrow-left',
        'next' => 'fa4:arrow-right',
    ],
],
```

**Usage:**
```php
// In controller
$neighbors = $this->Articles->find('neighbors', ['field' => 'id', 'value' => $id]);
$this->set('neighbors', $neighbors);

// In template
echo $this->IconSnippet->neighbors($neighbors, 'title');

// With slug support
echo $this->IconSnippet->neighbors($neighbors, 'title', [
    'slug' => true,
    'name' => 'Article',  // Used for link text: "prevArticle", "nextArticle"
]);

// With custom title field
echo $this->IconSnippet->neighbors($neighbors, 'id', [
    'titleField' => 'name',  // Show 'name' in link title attribute
]);

// With custom URL parameters
echo $this->IconSnippet->neighbors($neighbors, 'title', [
    'url' => ['?' => ['page' => 1]],  // Additional URL params
]);
```

**Parameters:**
- `$neighbors` (array): Array containing 'prev' and 'next' records
- `$field` (string): Field to use for URL (e.g., 'id', 'slug', 'Model.field')
- `$options` (array): Optional configuration
  - `name` (string): Name used in link text (e.g., 'Article' becomes 'prevArticle', 'nextArticle')
  - `slug` (bool): Whether to slugify the field value for URL (default: false)
  - `titleField` (string): Field to use for link title attribute (default: same as $field)
  - `url` (array): Additional URL parameters to merge

**Returns:** String with HTML navigation links

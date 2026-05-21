# IconSnippet Helper

A CakePHP helper for common icon snippet use cases. It contains convenience
wrappers built on top of the [Icon helper](./icon).

## Setup

Include the helper in your `AppView` class:

```php
$this->loadHelper('Templating.IconSnippet', [
    // ...
]);
```

It provides the following methods:

- `neighbors()` — print prev/next records as icon elements with URLs.
- `thumbs()` — up/down icon (pro/contra).
- `yesNo()` — print an icon representation of a boolean value.

## yesNo()

Displays a yes/no symbol based on a boolean value.

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

- `$value` (int|bool) — value to check (defaults to comparing against `1`).
- `$options` (array) — optional configuration:
  - `on` (int|bool) — value that represents "on/yes" (default: `1`).
  - `onTitle` (string) — title for the "yes" state (default: "Yes").
  - `offTitle` (string) — title for the "no" state (default: "No").
- `$attributes` (array) — HTML attributes for the icon.

**Returns:** an `HtmlStringable` icon.

## thumbs()

Displays a thumbs up/down icon based on a boolean value.

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

- `$value` (mixed) — boolish value (truthy = thumbs up, falsy = thumbs down).
- `$options` (array) — optional configuration.
- `$attributes` (array) — HTML attributes for the icon.

**Returns:** an `HtmlStringable` icon (thumbs-up for true, thumbs-down for false).

## neighbors()

Displays neighbor quicklinks for prev/next navigation with icons.

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

- `$neighbors` (array) — array containing `prev` and `next` records.
- `$field` (string) — field to use for the URL (for example, `id`, `slug`, `Model.field`).
- `$options` (array) — optional configuration:
  - `name` (string) — name used in link text (for example, `Article` becomes `prevArticle`, `nextArticle`).
  - `slug` (bool) — whether to slugify the field value for the URL (default: `false`).
  - `titleField` (string) — field to use for the link title attribute (default: same as `$field`).
  - `url` (array) — additional URL parameters to merge.

**Returns:** a string with the HTML navigation links.

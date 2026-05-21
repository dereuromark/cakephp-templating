# HtmlStringable

The `HtmlStringable` interface lets you build any HTML snippet and use it inside
other helpers or template elements with ease.

The idea is that the snippet knows how to behave (it should not be escaped), and
your helpers can adjust themselves accordingly — saving you from sprinkling
`'escapeTitle' => false'` all over your templates.

## The problem it solves

Before, you had to disable title escaping by hand every time:

```php
$icon = $this->Icon->render('delete');

$this->Form->postLink($icon, ['action' => 'delete', $id], ['escapeTitle' => false]);
```

After, the helper recognizes the `HtmlStringable` and handles escaping for you:

```php
$icon = $this->Icon->render('delete');

$this->Form->postLink($icon, ['action' => 'delete', $id]);
```

## Creating value objects

The [Html helper](./html) can be used to create `Html` value objects:

```php
$html = $this->Html->string('<i>text</i>');
$this->Html->link($html, '/my/url');
```

You can also use the `Html` value object directly:

```php
use Templating\View\Html;

$html = Html::create('<i>text</i>');
$this->Html->link($html, '/my/url');
```

The same goes for any custom snippet of yours:

```php
use Templating\View\HtmlStringable;

class SvgGraph implements HtmlStringable { ... }

// in your templates
$icon = new SvgIcon($name);
$this->Html->link($icon, '/my/url');
```

No more `escapeTitle` overhead. In these cases you need a `use` statement in the
template, however.

You can always just echo it as well:

```php
echo $icon;
```

## Adding support to your helpers

You can use the helpers shipped with this plugin, add the traits to your own
helpers, or just write your own three-liner for it.

The quickest path is to load the bundled helpers:

```php
// in your AppView::initialize()
$this->addHelper('Templating.Html');
$this->addHelper('Templating.Form');
```

Or add the traits to your app's own helpers — necessary if you already extended
or added other helper methods:

```php
// in your app's HtmlHelper
namespace App\View\Helper;

use Cake\View\Helper\HtmlHelper as CoreHtmlHelper;
use Templating\View\Helper\HtmlHelperTrait;

class HtmlHelper extends CoreHtmlHelper {

    use HtmlHelperTrait;

    // ...

}
```

and

```php
// in your app's FormHelper
namespace App\View\Helper;

use Cake\View\Helper\FormHelper as CoreFormHelper;
use Templating\View\Helper\FormHelperTrait;

class FormHelper extends CoreFormHelper {

    use FormHelperTrait;

    // ...

}
```

## Strict types

When using `declare(strict_types=1);` you need to manually cast when passing a
value object to methods that only accept `string`:

```php
$icon = new SvgIcon($name);
// CustomHelper::display(string $html) does not accept HtmlStringable
$this->Custom->display((string)$icon);
```

When not using strict types this cast is optional.

It is recommended to adjust the helper and method on the project level, adding
the interface into the signature as done for the Html and Form helpers:

```php
public function display(string|HtmlStringable $icon, array $options = []): string {
    if ($icon instanceof HtmlStringable) {
        $options['escapeTitle'] = false;
        $icon = (string)$icon;
    }

    return parent::display($icon, $options);
}
```

## Serializing

The value objects are safe for serialization using PHP's native `serialize()`
as well as `json_encode()`. As such they work fine with caching and other forms
of transportation (for example an API) through different layers.

## Security note

Some methods (for example link generation) provide these options:

- `escape` — set to `false` to disable escaping of title and attributes.
- `escapeTitle` — set to `false` to disable escaping of the title only. Takes
  precedence over the value of `escape`.

::: warning Prefer escapeTitle over escape
Make sure to use `escapeTitle` instead of `escape` where possible. The latter
would not escape any elements in your HTML element, including other attributes,
and can open you up to XSS vulnerabilities. The default usage of the value
object is for the main title of the link.
:::

In general, also make sure your HTML is safe and not user-provided in any way
without proper sanitization.

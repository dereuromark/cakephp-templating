# HtmlStringable interface
With this you can build any HTML snippet and use it inside other helpers or template elements with ease.
The idea is that it knows how to behave (no escaping) and your helpers can then also adjust themselves, saving
you from using `'escapeTitle' => false` all over the place all the time.

Before:
```php
$icon = $this->Icon->render('delete');

$this->Form->postLink($icon, ['action' => 'delete', $id], ['escapeTitle' => false]);
```

After:
```php
$icon = $this->Icon->render('delete');

$this->Form->postLink($icon, ['action' => 'delete', $id]);
```

Use can also use the `Html` value object directly:

```php
use Templating\View\Html;

$html = Html::create('<i>text</i>');
$this->Html->link($html, '/my/url');
```

The same goes for any custom snippet of yours, e.g.

```php
use Templating\View\HtmlStringable;

class SvgGraph implements HtmlStringable { ... }

// in your templates
$icon = new SvgIcon($name);
$this->Html->link($icon, '/my/url');
```
No more `'escapeTitle'` overhead.

You can always just echo it, as well:
```php
echo $icon;
```

You can use the helpers shipped with this plugin, you can add the traits yourself to your helpers or just write your own
3-liner for it.
```php
// in your AppView::initialize()
$this->addHelper('Templating.Html');
$this->addHelper('Templating.Form');
```
or
```php
// in your app's HtmlHelper
namespace App\View\Helper;

use Cake\View\Helper\HtmlHelper as CoreHtmlHelper;
use Templating\View\Helper\HtmlHelperTrait;

class HtmlHelper extends CoreHtmlHelper {

    use HtmlHelperTrait;

    ...

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

    ...

}
```
The latter is necessary if you already extended or added any other helper methods.

Note that when using `declare(strict_types=1);` you need to manually cast when passing this to methods that only accept string:
```php
$icon = new SvgIcon($name);
// CustomHelper::display(string $html) does not accept HtmlStringable
$this->Custom->display((string)$icon);
```
When not using strict_types this is optional.

It is recommended to adjust this helper and method on project level then, adding the interface into the signature
as done for Html and Form helpers.
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
The value objects should be safe for serialization using PHP native `serialize()` as well as
`json_encode()`. As such they should work fine with caching and other forms of transportation
(e.g. API) through different layers.

## Security note

Some methods (e.g. for link generation) provide:
- `escape` Set to false to disable escaping of title and attributes.
- `escapeTitle` Set to false to disable escaping of title. Takes precedence over value of `escape`)

Make sure to use `escapeTitle` instead of `escape` where possible here.
The latter would not escape any elements in your HTML element, including other attributes and can open yourself up
to XSS vulnerability. The default usage of the above value object is for the main title of the link.

In general also make sure your HTML is safe, and not user-provided in any way without proper sanitization.

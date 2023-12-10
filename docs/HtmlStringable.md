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

The same goes for any custom snippet of yours, e.g.

```php
use Templating\View\Html\HtmlStringable;

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

Note that when using `declare(strict_types=1);` you need to manually cast when passing this to methods that only accept string:
```php
$icon = new SvgIcon($name);
// CustomHelper::display(string $html) does not accept Stringable
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

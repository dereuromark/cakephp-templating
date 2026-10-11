# Getting started

Complete [installation](./installation.md) first. This example renders an application-owned SVG without a font stylesheet or JavaScript.

## Add one SVG

Create `webroot/icons/home.svg` with this content:

```xml
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
  <path d="M3 11 12 3l9 8v10h-6v-7H9v7H3z"/>
</svg>
```

The Bootstrap renderer can read individual SVG files. You can later replace this directory with your installed icon library's SVG directory.

## Load the Icon helper

Merge this helper registration into `src/View/AppView.php`:

```php
public function initialize(): void
{
    parent::initialize();

    $this->loadHelper('Templating.Icon', [
        'sets' => [
            'bs' => [
                'class' => \Templating\View\Icon\BootstrapIcon::class,
                'svgPath' => WWW_ROOT . 'icons',
            ],
        ],
    ]);
}
```

## Render it

Add this to a template rendered through your AppView:

```php
<?= $this->Icon->render('home', [], ['aria-hidden' => 'true', 'title' => false]) ?>
<span>Home</span>
```

You should see the house icon beside “Home”. The visible text names the item, so the decorative icon is hidden from assistive technology. Pass HTML attributes as the third argument; the old `title` option in the second argument is deprecated.

If the icon is missing, check that `webroot/icons/home.svg` exists and the helper is loaded by the view rendering that template. A font-mode icon needs its library's CSS; this SVG example does not.

## Continue

Use [Icon configuration](../helpers/icon-configuration.md) for installed libraries, aliases, caching and multiple sets. [IconSnippet](../helpers/icon-snippet.md) adds common snippets. The [HtmlStringable](../helpers/html-stringable.md) page explains how safe icon objects work with the optional Html and Form helpers.

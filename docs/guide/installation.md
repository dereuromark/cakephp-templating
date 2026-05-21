# Installation

## Require the plugin

Install the plugin via [Composer](https://getcomposer.org/):

```bash
composer require dereuromark/cakephp-templating
```

## Load the plugin

Load the plugin using the CakePHP plugin shell:

```bash
bin/cake plugin load Templating
```

Or add it manually to your application's `src/Application.php`:

```php
public function bootstrap(): void
{
    parent::bootstrap();

    $this->addPlugin('Templating');
}
```

## Load the helpers

Add the helpers you want to use in your `src/View/AppView.php`:

```php
public function initialize(): void
{
    parent::initialize();

    $this->addHelper('Templating.Icon', [
        'sets' => [
            'bs' => \Templating\View\Icon\BootstrapIcon::class,
        ],
    ]);
    $this->addHelper('Templating.IconSnippet');
    $this->addHelper('Templating.Templating');
    $this->addHelper('Templating.Html');
    $this->addHelper('Templating.Form');
}
```

::: tip
You only need to load the helpers you actually use. The [Icon helper](/helpers/icon)
is the main entry point; the [Html](/helpers/html) and [Form](/helpers/form)
helpers add automatic `HtmlStringable` support to the core helpers.
:::

## Install icon libraries

This plugin does not handle asset management or shipping of icon files. Install
your preferred icon libraries using any method you like (npm, Composer, CDN, or
manual download) and point the configuration at the files. See the
[Icon helper](/helpers/icon) and [Icon configuration](/helpers/icon-configuration)
pages for the details.

## Next steps

- [HtmlStringable](/helpers/html-stringable) — make your template code HTML aware.
- [Icon helper](/helpers/icon) — render icons from any configured set.

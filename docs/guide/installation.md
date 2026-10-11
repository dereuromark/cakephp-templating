# Installation

Install the plugin in your CakePHP application:

```bash
composer require dereuromark/cakephp-templating
bin/cake plugin load Templating
```

Composer declares the supported PHP and CakePHP ranges. To register the plugin manually, add `$this->addPlugin('Templating');` after `parent::bootstrap()` in `src/Application.php`.

Continue with [Getting started](./getting-started.md) to render your first icon. Icon files and font stylesheets belong to your application; this plugin renders them and does not download assets.

The [Html](../helpers/html.md) and [Form](../helpers/form.md) helpers are optional replacements for CakePHP's helpers. Load them only when you need automatic `HtmlStringable` handling.

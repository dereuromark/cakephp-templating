# Overview

The **Templating** plugin for CakePHP makes working with HTML and view helpers
more convenient and ships a powerful, unified way to render (font) icons from a
range of popular libraries.

It helps you to:

- make working with HTML and helpers more convenient,
- provide (font) icons from various libraries out of the box,
- and, together with the IdeHelper plugin, get auto-complete on those icons.

::: info CakePHP version
This documentation is for the branch that supports **CakePHP 5.1+**. See the
[version map](https://github.com/dereuromark/cakephp-templating/wiki#cakephp-version-map)
for older releases.
:::

## Supported icon sets

You can use one or many of the following icon sets out of the box:

- [Bootstrap](https://icons.getbootstrap.com/)
- [FontAwesome](https://fontawesome.com/icons) v4/v5/v6/v7
- [Material](https://fonts.google.com/icons)
- [Feather](https://feathericons.com/)
- [Lucide](https://lucide.dev/) — a modern Feather fork with 1000+ icons
- [Heroicons](https://heroicons.com/) — by the Tailwind CSS team

You can also add your own custom icon set.

## The pieces

| Concept | Responsibility |
|---------|----------------|
| Icon helper | Renders icons from any configured set, in font or SVG mode. |
| IconSnippet helper | Convenience wrappers for common icon snippets (yes/no, thumbs, neighbors). |
| Templating helper | Colored status output (`ok()`, `warning()`, `yesNo()`). |
| Html helper | Enhanced `HtmlHelper` with automatic `HtmlStringable` support. |
| Form helper | Enhanced `FormHelper` with automatic `HtmlStringable` support. |
| `HtmlStringable` | A marker contract for value objects that carry already-safe HTML. |

## Next steps

- [Installation](./installation) — install the plugin and load helpers.
- [HtmlStringable](/helpers/html-stringable) — the core concept behind the helpers.
- [Icon helper](/helpers/icon) — render icons from any configured set.

## Demo

A live demo of the helpers is available at
[sandbox.dereuromark.de/sandbox/templating-examples](https://sandbox.dereuromark.de/sandbox/templating-examples).

import { defineConfig } from 'vitepress'

function sidebar() {
  return [
    {
      text: 'Guide',
      items: [
        { text: 'Overview', link: '/guide/' },
        { text: 'Installation', link: '/guide/installation' },
      ],
    },
    {
      text: 'Helpers',
      items: [
        { text: 'HtmlStringable', link: '/helpers/html-stringable' },
        { text: 'Icon Helper', link: '/helpers/icon' },
        { text: 'Icon Configuration', link: '/helpers/icon-configuration' },
        { text: 'Icon Advanced', link: '/helpers/icon-advanced' },
        { text: 'IconSnippet Helper', link: '/helpers/icon-snippet' },
        { text: 'Templating Helper', link: '/helpers/templating' },
        { text: 'Html Helper', link: '/helpers/html' },
        { text: 'Form Helper', link: '/helpers/form' },
      ],
    },
  ]
}

export default defineConfig({
  title: 'cakephp-templating',
  description: 'Convenient HTML helpers and out-of-the-box (font) icons for CakePHP — Bootstrap, FontAwesome, Material, Feather, Lucide, and Heroicons.',
  base: '/cakephp-templating/',
  lastUpdated: true,
  cleanUrls: true,
  sitemap: {
    hostname: 'https://dereuromark.github.io/cakephp-templating/',
  },
  head: [
    ['link', { rel: 'icon', href: '/cakephp-templating/favicon.svg', type: 'image/svg+xml' }],
  ],
  themeConfig: {
    logo: '/logo.svg',
    nav: [
      { text: 'Guide', link: '/guide/', activeMatch: '/guide/' },
      { text: 'Helpers', link: '/helpers/icon', activeMatch: '/helpers/' },
      {
        text: 'Links',
        items: [
          { text: 'GitHub', link: 'https://github.com/dereuromark/cakephp-templating' },
          { text: 'Packagist', link: 'https://packagist.org/packages/dereuromark/cakephp-templating' },
          { text: 'Issues', link: 'https://github.com/dereuromark/cakephp-templating/issues' },
          { text: 'Demo', link: 'https://sandbox.dereuromark.de/sandbox/templating-examples' },
        ],
      },
    ],
    sidebar: {
      '/guide/': sidebar(),
      '/helpers/': sidebar(),
    },
    socialLinks: [
      { icon: 'github', link: 'https://github.com/dereuromark/cakephp-templating' },
    ],
    search: {
      provider: 'local',
    },
    editLink: {
      pattern: 'https://github.com/dereuromark/cakephp-templating/edit/master/docs/:path',
      text: 'Edit this page on GitHub',
    },
    footer: {
      message: 'Released under the MIT License.',
      copyright: 'Copyright Mark Scherer',
    },
  },
})

![Version](https://img.shields.io/packagist/v/jhae/shopware-6-twig-extensions?label=Version)
![License](https://img.shields.io/packagist/l/jhae/shopware-6-twig-extensions?label=License&color=lightgrey)
![Tests](https://img.shields.io/github/actions/workflow/status/jhae-de/shopware-6-twig-extensions/main.yaml?label=Tests)
![Coverage](https://img.shields.io/codecov/c/github/jhae-de/shopware-6-twig-extensions/main?label=Coverage)

> [!WARNING]
> This repository is no longer maintained and was archived on January 11, 2025.
>
> The package [jhae/shopware-6-twig-extensions](https://packagist.org/packages/jhae/shopware-6-twig-extensions) has been
> marked as abandoned. There is currently no replacement.

---

# Shopware 6 Twig Extensions

This Shopware 6 plugin adds more Twig features.

## Installation

1. Navigate to the root folder of your Shopware installation.
2. Download the plugin.
   ```bash
   composer require jhae/shopware-6-twig-extensions
   ```
3. Refresh the Shopware plugin list.
   ```bash
   ./bin/console plugin:refresh
   ```
4. Install and activate the plugin.
   ```bash
   ./bin/console plugin:install --activate JhaeTwigExtensions
   ```

You are ready to use the Twig extensions in your templates.

## Twig functions

### sw_cms_page

This function allows you to include any CMS page you created in "Content / Shopping Experiences".

#### Usage

```html
{{ sw_cms_page(id) }}
```

#### Parameters

| Name  | Type     | Description                                                               |
|-------|----------|---------------------------------------------------------------------------|
| `id`  | `string` | The ID of the CMS page.<br/>Example: `'c00dd7c0b7684ebca451f49dd6e636aa'` |

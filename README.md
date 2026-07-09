# HugginButt

Child theme for [hugginbutt.com](https://hugginbutt.com) — a WooCommerce store selling handcrafted fantasy jewelry and faire-ready treasures. Built on top of the [Kadence](https://www.kadencewp.com/) parent theme.

## What this repo is

Just the theme (`wp-content/themes/hugginbutt-child`) — not the full WordPress install. Core, plugins (WooCommerce, Kadence Blocks, Jetpack), uploads, and the database are intentionally left untracked; they're either vendored dependencies or environment/data that don't belong in version control.

## Structure

- `front-page.php` + `template-parts/front-page/` — homepage sections (hero, shop by category, featured products, about, events, testimonials/newsletter).
- `header.php` / `footer.php` — site chrome.
- `page-events.php` — full `/events/` schedule page.
- `inc/`
  - `setup.php` — theme supports, menus, image sizes.
  - `enqueue.php` — styles/scripts.
  - `customizer.php` — Customizer options backing the editable homepage copy.
  - `theme-content.php` — content getters (`hugginbutt_get_events()`, testimonials, etc.).
  - `woocommerce.php` — shop category/featured product queries, cart fragment, coming-soon body class.
  - `events-cpt.php` — registers the `hb_event` custom post type used for the Upcoming Events sections.
  - `shop-sidebar.php` — Shop Filters sidebar setup.
  - `icons.php`, `template-tags.php` — inline SVG icon helper and small template utilities.
- `patterns/page-coming-soon-default.php` — override of WooCommerce's bundled "Coming Soon" block pattern, restyled to match the site (registered under the same slug so it replaces the plugin's version without editing plugin files).
- `assets/` — CSS (`hugginbutt.css`), JS, and images (`generated/` for illustrated art assets, `decor/` for background textures, `placeholders/` for fallback SVGs, `real-products/` for photography).

## Brand

- Palette: forest green (`--hb-green`), parchment/cream (`--hb-cream*`), rust/copper (`--hb-rust`), gold (`--hb-gold`) — defined as CSS custom properties at the top of `assets/css/hugginbutt.css`, also overriding Kadence's `--global-palette*` tokens so untouched WooCommerce pages (cart, checkout, single product) stay on-brand.
- Fonts: Cinzel (headings/display) + Cormorant Garamond (body).
- The site-wide green fabric-texture background lives on `.hb-body`; the Coming Soon page reproduces it separately since that page renders through WordPress's block-template system rather than `header.php`/`footer.php`.

## Local setup

This theme expects WordPress + WooCommerce + Kadence already installed, with this repo checked out as `wp-content/themes/hugginbutt-child`. No build step — CSS/JS are hand-authored, not compiled.

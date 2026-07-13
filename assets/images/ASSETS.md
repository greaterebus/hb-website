# Image assets in use

Every file under `assets/images/` that the theme actually serves, as of the 2026-07 cleanup that
removed 40 orphaned/duplicate files (old torn-edge art superseded by inline CSS masks, the
pre-"tarot card" shop-card design, dropped hover-icon variants, placeholder SVGs superseded by
generated art, and `real-products/` photos duplicated in the Media Library) and converted every
in-use PNG to WebP.

All images below are WebP except the favicon set (kept as PNG — required for OS/browser favicon
and apple-touch-icon compatibility) and the four utility placeholder SVGs (vector, format is
already optimal).

If you add a new image, add a row here. If you remove the last reference to one, delete both the
file and its row.

## decor/ — background textures & surface treatments

| File | Dimensions | Used in |
|---|---|---|
| `green-fabric-texture.webp` | 750×750 | `hugginbutt.css` — site-wide `.hb-body` fabric background |
| `parchment-fill.webp` | 1254×1254 | `hugginbutt.css` — `.hb-paper` utility (Shop By Category, About, Testimonials, Newsletter, event cards) |
| `parchment-noise.svg` | vector | `hugginbutt.css` — subtle noise overlay paired with `.hb-paper` |
| `tarot-card.webp` | 942×1439 | `hugginbutt.css` — shop product card background (tarot-tile design) |

## generated/ — illustrated art, decorative art, and site chrome

| File | Dimensions | Used in |
|---|---|---|
| `about-makers-v2.webp` | 1350×900 | `inc/template-tags.php` — About section fallback (`hb_about_image` Customizer override takes priority) |
| `about-paper-leafs.webp` | 978×1277 | `hugginbutt.css` — About / My Account page background |
| `cart-scroll-bg.webp` | 885×1045 | `hugginbutt.css` — Cart page background |
| `category-accessories-v2.webp`, `category-bracelets-v2.webp`, `category-earrings-v2.webp`, `category-pendants-v2.webp`, `category-rings-v2.webp` | 640×640 | `inc/template-tags.php` — Shop By Category tile fallbacks (used until a real product-category thumbnail exists) |
| `category-arrow-leaf.webp` / `category-arrow-leaf-fall.webp` | 937×383 | `hugginbutt.css` — Shop By Category decorative arrow (+ seasonal variant) |
| `coming-soon-upcoming-events.webp` | 1073×1216 | `patterns/page-coming-soon-default.php` |
| `coming-soon-work-in-progress.webp` | 1448×1086 | `patterns/page-coming-soon-default.php` |
| `empty-cart-feed-me.webp` | 1254×1254 | `inc/woocommerce.php` — empty-cart art |
| `event-note-small.webp` / `event-note-wide.webp` | 913×1041 / 1618×852 | `hugginbutt.css` — event card note-paper background (responsive variants) |
| `events-view-all.webp` | 1603×514 | `hugginbutt.css` — "View All Events" button background |
| `fairy-wings-favicon-{16,32,48,180,192,512}.png` | native | `inc/enqueue.php` (`hugginbutt_site_icon_url()`) — dynamically selected by requested size. **Kept as PNG deliberately** — favicon/apple-touch-icon formats need broad OS support that WebP doesn't reliably have |
| `footer-logo-cream.webp` | 640×640 | `inc/template-tags.php` — logo fallback |
| `footer-mascot.webp` | 517×531 | `footer.php` |
| `hero-jewelry-v2.webp` | 1500×1000 | `inc/template-tags.php` — hero fallback (`hb_shop_banner_image` Customizer override takes priority) |
| `hero-shop-now.webp` | 1805×573 | `hugginbutt.css` — hero "Shop Now" button background |
| `hugginbutt-logo-wordmark-transparent.webp` | 2068×567 | `patterns/page-coming-soon-default.php` |
| `ornament-leaf-branch-v2.webp`, `ornament-leaf-sprig-v2.webp`, `ornament-leaf-sprig-green-v2.webp` | 512×512 / 768×256 / 768×256 | `hugginbutt.css` — decorative leaf ornaments across several sections |
| `ren-faire-v2.webp` | 1067×800 | `inc/template-tags.php` — event image fallback |
| `testimonial-mascot.webp` | 640×640 | `inc/template-tags.php` — testimonial fallback |

## icons/ — small UI icons

| File | Dimensions | Used in |
|---|---|---|
| `account-gnome-hat.webp` / `account-gnome-bald.webp` | 96×96 | `hugginbutt.css` — header account icon, default/hover (displayed 22×22px; downscaled from 625-800px source PNGs in the 2026-07 conversion) |
| `cart-chest.webp` / `cart-mimic.webp` | 96×96 | `hugginbutt.css` — header cart icon, default/hover (same downscale) |
| `add-to-cart.webp` | 625×625 | `hugginbutt.css` — product card add-to-cart button art |
| `feed-the-mimic.webp` / `feed-the-mimic-hover.webp` | 1200×510 | `hugginbutt.css` — shop card add-to-cart button, default/hover |

## placeholders/ — vector fallbacks

| File | Used in |
|---|---|
| `category-generic.svg` | `inc/template-tags.php` — catch-all for any `category-*` key without dedicated art |
| `product-placeholder-1.svg`, `product-placeholder-2.svg`, `product-placeholder-3.svg` | `inc/template-tags.php` — product image fallbacks |

## source/ — not served, tooling inputs only

`shop-card-back-source.png`, `shop-card-note-source.png`, `shop-tarot-card-bg-source.png` — raw
chroma-key inputs for `tools/prepare-transparent-card-asset.py`. No URL ever points at these;
kept for reproducing the card art pipeline if it's rerun later.

# Architecture

Scope reviewed 2026-11-06: six Ukrainian language landing pages and crawler text files. Other application flows remain unreviewed.

Production: WordPress 7.1.4, ACF Pro 6.0.2, PHP CLI 8.2.35, MariaDB 10.11.19. Theme: `wp-content/themes/educadotheme`. Hosting root: `/home/educadoc/educado.school`.

Verified flow: page slug -> WordPress page template -> `get_fields()` -> native ACF content -> HTML. English/Spanish use `template/template-rozvod.php`; French/German/Polish/Italian use `template/template-language.php`. `functions.php` loads `inc/language-seo.php`, which registers a page-scoped ACF group. Scoped SEO styles live in `src/scss/_language-seo.scss`, imported by `src/scss/style.scss` and emitted inside the normal `assets/css/style.min.css`. Both templates call `parts/content-language-beginners.php`; it renders only with a nonempty title and body, and is currently hidden pending copywriter text.

Root `robots.txt` and `llms.txt` are physical files served independently of WordPress templates. LiteSpeed page cache is purged with the site's WP-CLI after changes.

Local clone refreshed from production on 2026-11-06: same WordPress 7.1.4, production active-plugin files, 2531 uploads images and 166 hosted theme/MU files verified by SHA-256. Local DB URLs use `https://educado-school.loc`, including serialized ACF and escaped JSON values. `parts/content-menu.php` uses `home_url()` for blog/vacancies in both environments. Local-only MU safeguards activate through `WP_ENVIRONMENT_TYPE=local`. Web PHP remains 7.4.33 locally versus production 8.2.35; server stacks are not identical. See [local sync procedure and boundaries](../LOCAL-PRODUCTION-SYNC-2026-11-06.md).

See [release and verification](../SEO-RELEASE-2026-11-06.md), [HTTP assertions](../../scripts/verify-seo.py), [verified heading outlines](../seo-verification.json). FILE_INDEX.json is inventory only; GitNexus initialization failed because the selected Node runtime could not parse `??=`. Source plus native/HTTP checks supplied the evidence for this scoped review; no repeated index setup was attempted.

Typography: historical `h1` mixin from `907d2bd` restored to 70/50/32 px in source and compiled CSS; the real `scssBuild` task emits these sizes and scoped SEO styles after a clean asset rebuild. See `docs/HEADING-TYPOGRAPHY-2026-11-06.md`.

Home-section navigation (2026-11-06): header/mobile `parts/content-menu.php` and `footer.php` build reviews/teachers URLs with `home_url('/#ed-testimonials')` and `home_url('/#ed-teachers')`. Both IDs exist in `template/template-home.php`. `inc/enqueue.php` registers `assets/js/libraries/home-navigation.js` after `ed-main` with a content-hash version. Source is `src/js/libraries/home-navigation.js`, emitted by the existing Gulp `copy` task. It aligns the initial hash after page load/fonts, offsets the fixed header by 16 px, and handles same-page clicks while closing the mobile menu. Modified/new-tab clicks retain browser behavior.

Production acceptance recorded 2026-11-06 in [the specification audit](../SEO-PRODUCTION-AUDIT-2026-11-06.md): six page outlines, all 42 FAQ titles, FAQPage output, self-canonicals, sitemap membership, crawler files, media and home-section navigation verified. Implementation/recheck commits are `1789fbb`, `c1fe105` and `9b7ff4b`, pushed to `origin/main`.

Remaining dependency is editorial: beginner content for English, Spanish, German and Italian awaits user-supplied text; Polish requires separate approval. The existing ACF group and conditional template already support publication without new markup or schema changes. Keep the current empty-body guard until content is supplied.

The crawler text files use UTF-8 without BOM and LF. The web root `.htaccess` includes the rules stored in [crawler-charset.conf](../seo-spec/crawler-charset.conf) outside the WordPress-managed block. The rules set the explicit UTF-8 HTTP charset for the two static crawler files. Keep the block when replacing the server-specific `.htaccess`.

The local clone was refreshed again on 2026-11-10. See the [sync record](../LOCAL-PRODUCTION-SYNC-2026-11-06.md#обновление-локальной-копии--2026-11-10) for the active database, rollback target and file verification.

Language pricing v2 (deployed for filling, 2026-11-15) uses inc/language-pricing.php for a template-scoped native ACF group, stable option IDs, combination validation and automatic visibility from the selected visual and validated available prices. template-language.php selects the old markup or parts/content-language-pricing.php. Local Polish has twelve blank draft rows and pricing_visual=v2. The source/asset JS pairs use Gulp copy; pricing.scss is a separate native Sass entry alongside style.scss. See [the filling guide and release boundary](../LANGUAGE-PRICING-DESIGN.md).

Pricing preparation is deployed on production as of 2026-11-15. Native ACF fields are registered for all 13 shared-template pages; Polish has 12 blank draft combinations. Since 2026-11-18 public v2 automatically displays available saved prices when pricing_visual=v2; an authorized nonce-protected editor preview also remains available. Production files were verified against local SHA-256. See the filling guide for deployment evidence.


Optional pricing axes (2026-11-18) are defined centrally by educado_pricing_dimensions(). Each page can enable/disable course, duration, level and format, with empty lists omitted. The model builds keys only from active axes; the frontend receives the canonical key order through data-pricing-dimensions. The admin generator computes the active Cartesian product. Chinese local/production now have individual/paired formats.

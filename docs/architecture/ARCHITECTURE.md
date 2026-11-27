# Architecture

Scope reviewed 2026-11-06: six Ukrainian language landing pages and crawler text files. Other application flows remain unreviewed.

Production: WordPress 7.1.4, ACF Pro 6.0.2, PHP CLI 8.2.35, MariaDB 10.11.19. Theme: `wp-content/themes/educadotheme`. Hosting root: `/home/educadoc/educado.school`.

The local `wp-content/mu-plugins/educado-editor-tools.php` extends WordPress TinyMCE settings, ACF WYSIWYG toolbars and admin editors initialized through `wp.editor.initialize()`. Production uses the existing Advanced Editor Tools plugin. Gutenberg's native Table block has its own editing path.

Standard posts use `wp-content/themes/educadotheme/single-post.php`. It reads native post content and the existing ACF blog cover and `shablon` repeater, reuses the existing banner, video and callback views, and renders an optional ACF FAQ. `inc/blog-post.php` adds `template4` to the existing template selector and injects the table block fields at runtime. Styles and the responsive table wrapper load on standard posts only. [Editor guide and production release](../BLOG-POSTS-2026-11-27.md)

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


Pricing selector availability (2026-11-18): the existing price map is also the availability source; there is no additional admin switch. Server markup disables globally empty options, while frontend filtering follows visible format/course/duration/level order and selects compatible descendants automatically. Only enabled dimensions participate. Empty preview retains its fallback message; public legacy/v2 visibility rules remain unchanged.


Group pricing extension (2026-11-20, local only): `inc/language-group-pricing.php` registers a separate native ACF `pricing_group` and validates the selected active format, terms and module/course prices. `inc/language-pricing.php` loads the helper and permits a ready group-only offer under the existing v2 selection. `parts/content-language-pricing.php` composes `pricing-group.php` and shared `pricing-installments.php`. Source/emitted JS switches views within the block and preserves the package selection. Source SCSS and generated pricing CSS cover two-column desktop and stacked mobile layouts. No production release yet.


Group pricing production status (2026-11-20): supersedes the local-only status above. Twelve theme source/runtime files deployed and verified by readback. ACF fields are registered for every language template page; only Chinese (504) received an explicitly approved example group offer. Existing production package data remain authoritative; no local DB import.


Store redesign (2026-11-20, local only): `functions.php` loads `inc/market.php`, which registers template-scoped assets and additive native ACF FAQ/form fields. `template-market.php` renders existing nested course repeater data as compact cards and category anchors. The homepage and store now both call `parts/content-questions-form.php`, retaining the native callback handler and nonce. Isolated market SCSS/JS and reference media have source/runtime copies and a normal Gulp entry. See [store design and filling](../MARKET-DESIGN.md).

The promotional header banner reads the ACF `Home` group from page 5. Across templates that load `header.php`, the banner renders when enabled and the current calendar day in the WordPress timezone is within the saved date range, including the final day. The shared `language-pricing-admin.js` checks for attached ACF field elements before initializing pricing controls or applying its input limit.

`inc/setup.php` loads `inc/header-banner.php`, which registers the page-5 ACF group «Вигляд банера». The shared header supports the classic button and a separate message and button. In the separate mode, `assets/css/header-banner.css` covers the selected width with the background photo while the content stays centered. Both modes use the existing callback popup.


Store production status (2026-11-25): supersedes the local-only status above. Fifteen store-related files deployed and verified. Both store records use the approved reference catalog and native ACF FAQ/form groups; media attachment IDs remapped on production. The shared homepage form contract remains unchanged. Market JS additionally measures the live header through ResizeObserver, distinguishing sticky and fixed positioning for content/anchor offsets; the active banner itself is unchanged.


Final store catalog state (2026-11-25): original per-language content restored (Spanish 28, English 9); the reference catalog is no longer active. Cover topics are rendered from existing tematika fields, while original facts/prices/media/URLs feed the new layout. FAQ and shared contact form remain in place.

2026-11-27 — optional group levels: `inc/language-group-pricing.php` registers `pricing_group.levels` and validates each offer; shared texts are inherited, level prices are mandatory and independent. `educado_group_pricing_model()` returns `offers`/`has_levels`, retaining singleton behavior for an empty repeater. `parts/pricing-group.php` renders native radios and server-escaped offer panels; `content-language-pricing.php` renders corresponding notes. Source/runtime `language-pricing.js` switches both panel sets separately from the standard dimensions. Existing pricing CSS supplies wrapping, focus and responsive card layout. No new style bundle or frontend dependency.

2026-11-27 group-level content example: production Chinese page 504 supplies two saved level rows to the existing `offers` model and PHP price/note panels. Each row explicitly stores copied test prices; blank row texts inherit shared conditions. This is page content, not theme defaults or an automatic migration. New pages and empty repeaters retain the singleton behavior. See LANGUAGE-PRICING-DESIGN.md for the example and native ACF write/readback details.

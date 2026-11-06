# Architecture

Scope reviewed 2026-11-06: six Ukrainian language landing pages and crawler text files. Other application flows remain unreviewed.

Production: WordPress 7.1.4, ACF Pro 6.0.2, PHP CLI 8.2.35, MariaDB 10.11.19. Theme: `wp-content/themes/educadotheme`. Hosting root: `/home/educadoc/educado.school`.

Verified flow: page slug -> WordPress page template -> `get_fields()` -> native ACF content -> HTML. English/Spanish use `template/template-rozvod.php`; French/German/Polish/Italian use `template/template-language.php`. `functions.php` loads `inc/language-seo.php`, which registers a page-scoped ACF group. Scoped SEO styles live in `src/scss/_language-seo.scss`, imported by `src/scss/style.scss` and emitted inside the normal `assets/css/style.min.css`. Both templates call `parts/content-language-beginners.php`; it renders only with a nonempty title and body, and is currently hidden pending copywriter text.

Root `robots.txt` and `llms.txt` are physical files served independently of WordPress templates. LiteSpeed page cache is purged with the site's WP-CLI after changes.

Local clone refreshed from production on 2026-11-06: same WordPress 7.1.4, production active-plugin files, 2531 uploads images and 166 hosted theme/MU files verified by SHA-256. Local DB URLs use `https://educado-school.loc`, including serialized ACF and escaped JSON values. `parts/content-menu.php` uses `home_url()` for blog/vacancies in both environments. Local-only MU safeguards activate through `WP_ENVIRONMENT_TYPE=local`. Web PHP remains 7.4.33 locally versus production 8.2.35; server stacks are not identical. See [local sync procedure and boundaries](../LOCAL-PRODUCTION-SYNC-2026-11-06.md).

See [release and verification](../SEO-RELEASE-2026-11-06.md), [HTTP assertions](../../scripts/verify-seo.py), [verified heading outlines](../seo-verification.json). FILE_INDEX.json is inventory only; GitNexus initialization failed because the selected Node runtime could not parse `??=`. Source plus native/HTTP checks supplied the evidence for this scoped review; no repeated index setup was attempted.

Typography: historical `h1` mixin from `907d2bd` restored to 70/50/32 px in source and compiled CSS; the real `scssBuild` task emits these sizes and scoped SEO styles after a clean asset rebuild. See `docs/HEADING-TYPOGRAPHY-2026-11-06.md`.

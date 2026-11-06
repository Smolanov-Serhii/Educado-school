# Database and persistent data relations

Reviewed 2026-11-06: WordPress page content and ACF metadata on the exact production site. MariaDB 10.11.19; actual `ecd_postmeta` engine InnoDB, collation `utf8mb4_unicode_520_ci`. Hosting conventions and schema were preserved.

Logical relationship: page ID -> `ecd_postmeta.post_id` -> ACF scalar/group/repeater fields. No foreign-key audit was performed. Target page IDs: 773, 536, 499, 487, 495, 492. Existing field keys and original text are recorded in `scripts/seo-content-20261106.json`; new definitions live in `inc/language-seo.php`.

ACF `link` fields store arrays (`title`, `url`, `target`) even when formatted retrieval returns only a URL. Repeater migrations must update only changed text subfields with `update_sub_field`; formatted repeater values are not a safe storage payload. No prices, forms, students, mail or unrelated options were intentionally changed. The shared homepage reviews heading remains unchanged; new reviews headings are per page.

The full database SQL snapshot is outside the repository at `D:\educado-school-before-seo-20261106.sql`; matching SHA-256 and export details are in the release report. No credentials or SQL dump belong in these maps.

Local production snapshot: 29 tables imported into `educado_school_prod_20261106` on local MySQL 8.0.45 (`127.0.1.30`); previous `educado_school` retained. Local-only `wp-config.php` selects the new database. Native WP-CLI serialized-data replacement changed production URLs to the local HTTPS domain while preserving `guid`. ACF group registration, heading output, attachment paths and local image HTTP responses were checked after import. Full original SQL stays at `D:\educado-school-prod-after-seo-20261106.sql`, outside Git.

Final FAQ correction (`9b7ff4b`): pages 499, 487, 495 and 492 had only the `pitannya` subfield of the lesson-duration question normalized from «онлайн урок» to «онлайн-урок». The guarded `scripts/normalize-faq-lesson-heading.php` was applied to production and local; complete repeater readback confirmed unchanged answers and other rows. Existing SQL snapshots precede this final punctuation correction; reapplying the committed script reproduces it after a restore. No database schema change was needed.

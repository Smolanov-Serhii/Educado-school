# Course store design

Reference: https://claude.ai/artifact/6fRSVmqsr6KtazCJ8k2MW9#page-dcb81aee8d83

Accepted direction: dark #141414 catalog, Educado #D3D360 accent, existing Poiret One display and Roboto body fonts. Preserve the normal site header/footer. Four compact square-image cards per desktop row; one card per row on mobile, explicitly confirmed by the user. Categories and course data come from native per-page ACF repeaters. The user subsequently approved replacing both local catalogs with reference content; this approval does not publish demo data to production.

Category anchors, image/button hover and short card entrance animations; respect reduced motion. Native accessible FAQ disclosures. The final user clarification replaces the empty form slot with the actual homepage callback form, with separate editable store copy.

The reference decorative teacher illustration is reused directly; course covers remain the existing per-course media.

User correction: the market header is white from initial load; mobile cards use a single column.


## Implementation and filling

Local pages: Spanish 895 (`/mahazyn-kursiv-z-ispanskoyi/`) and English 943 (`/mahazyn-kursiv-z-anhliyskoyi/`). Both now have ten reference cards: four general, two business, four mini courses. Headings remain page-specific. The original catalog snapshot is `D:/educado-market-prod-before-20261125.json` (outside Git). The repeaters, course field names and schema consumers remain native.

Exact cover extracted from the rendered supplied artifact: `market-course-reference.jpg`, also imported into the local media library as attachment 1748. It includes the printed course title. The reference repeats that same cover across all cards. Duration and lesson count are separate ACF fields and render with the site's existing leaf SVG. Prices are displayed in one row, including on mobile. Business B1 has no amounts or real destination in the artifact; its amounts stay empty, the page says «Уточніть вартість» and its details link leads to the contact form. The other nine known external course destinations are retained from the matching English offers.

`inc/market.php` registers «Магазин — навігація, FAQ і форма». Category titles and course contents remain in `perelk_propozicj`; added editable fields are `nav_label`, `details_label`, `faq_heading`, `market_faq` (question/answer) and `market_form` (heading, subtitle, name/phone placeholders, button, agreement text/label, lead title). The agreement text supports `{кнопка}` so button copy can change without stale consent wording. The policy destination remains the site's shared option.

`parts/content-questions-form.php` is the actual shared callback form used by the home template and store template. It preserves `form.form`, required name/phone classes, `ed_callback`, AJAX URL, nonce and the existing theme form handler. Homepage ACF fields remain `questions_zagolovok`, `questions_pdzagolovok` and common form options; store copy is independent. No new form endpoint or checkout flow.

FAQ: six questions and the first answer come from the reference. Other answers were drafted neutrally with explicit user approval; no access periods or payment terms invented. Native `details/summary` works without JavaScript. JavaScript adds one-open-at-a-time height animation and keyboard-compatible interactions. Category anchors, restrained cover/button hover and card entrances respect reduced motion. Header is white immediately; mobile uses one card per row.

Build: `src/scss/market.scss` imports `_course-market.scss`; the entry is included in `gulp/config/path.js`. Runtime is `assets/css/market.min.css`. Source/emitted `market.js` and source/runtime JPG/SVG assets are kept together. SVG has an XML namespace and case-correct viewBox for standalone use. Styles/scripts enqueue only for the store template.

Local checks: HTTP 200 for home and both stores; ten cards/three categories/six FAQs per store, all ten duration+lesson pairs, reference prices, image loading and native ACF fields. Callback action, AJAX URL, nonce and required inputs match the homepage contract. Empty-submit browser check showed both validation errors and sent no lead. Actual successful delivery/payment was not tested. Desktop 1440 and mobile 390/320 have no horizontal overflow; keyboard FAQ opens one item, reduced motion exposes all cards without entrance animation. Original homepage field values retained. PHP 7.4 lint, Node syntax, source/emitted equality and normal Gulp build passed. Mechanical UI detector found no issues before the final user-directed data/style corrections.

Production was not changed. `scripts/seed-market-reference.php` is explicitly local-only; the reference fixture is in `scripts/fixtures/market-reference.json`. Future production publication requires separate approval of template release and catalog content, plus scoped media/data transfer rather than a database replacement.


## Production release — 2026-11-25

The user authorized publishing the reviewed store. Both Spanish (895) and English (943) now use the approved ten-card reference catalog, three categories, six FAQs and independently editable form copy. Fifteen store source/runtime files passed baseline preflight and remote readback. The reference cover was imported through WordPress as production attachment 1755; local attachment 1748 was remapped, never copied as a production ID. Existing production page headings and unrelated ACF values were retained. Business B1 still has no invented price/destination and links to the inquiry form.

Database operations were limited to the two catalogs and their six approved ACF fields; no SQL import. A scoped previous production catalog export is stored locally at `D:/educado-market-prod-before-20261125.json`. Hashes for all thirteen language-page pricing groups and homepage ACF values matched before/after publication. Previously deployed banner/editor files were compared to local and preserved.

Production currently has the Black Friday split banner and a sticky header. Store JS now measures header height via ResizeObserver, updates the initial content offset for fixed versus sticky headers and supplies category/FAQ/form scroll margins. Matching source JS/SCSS and generated assets were deployed. On mobile the live header was 208 px tall, category top 228 px, with no overlap or horizontal overflow.

Production checks: PHP lint, HTTP 200 for home, Chinese and both stores; ten cards/six FAQs and valid JSON-LD on both stores; form endpoint uses production, media use production URLs. Desktop 1440 has four cards, mobile 390 one; header is white, keyboard FAQ opens one item, empty form produces two validation errors. Actual lead delivery/payment were not exercised. Autoptimize and LiteSpeed caches were cleared. All source/runtime copies are included in Git.


## Original catalogs restored — 2026-11-25

The user's later clarification supersedes the reference-data publication above: both stores must display their ORIGINAL course names, duration, lesson counts, regular/discount prices, covers and destinations. Restored only `perelk_propozicj` from the pre-release production export: Spanish 28 offers, English 9. Both production arrays exactly match the archived originals; local arrays match after domain mapping. Every media URL was resolved to its existing attachment ID before any write. FAQ, form and homepage values were unchanged. No demo Business B1 remains.

Course topics now render visibly at the top of each cover; the existing level/category labels remain above the cards. Long monthly unit text is abbreviated to «грн/міс» only in the view, preserving the saved original prices. Empty duration/lesson fields on grammar trainers were already empty in the archive and were not invented. Source SCSS and compiled CSS were updated together; three runtime/source files deployed and read back, template PHP lint passed, caches cleared. Desktop 1440 and mobile 390 checked: unique actual media, course names, facts and both prices, one mobile column and no horizontal overflow.

`restore-market-content.php` is the scoped restoration utility: input is the saved production catalog export outside Git; it resolves environment-specific media IDs and updates course repeaters only. Actual successful form delivery/payment remains outside this check.


2026-11-25: by user request, the market-card__topic overlay markup is commented out in the shared template. Original course data and the level labels remain unchanged.

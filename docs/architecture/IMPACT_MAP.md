# Impact map

Verified 2026-11-06, scope limited to six language landing pages:

| Change | Consumers and boundaries | Verification |
| --- | --- | --- |
| Two PHP templates | Six target page slugs get H2/H3; other users of these templates keep div tags; H1, forms and existing classes retained | PHP lint, HTTP outlines of all six pages |
| Existing ACF headings and repeater text | Page metadata, prices/links/content in the same repeaters must survive | All-field preflight, targeted subfield writes, ACF readback |
| Per-page reviews title | Six pages use `seo_reviews_heading`; homepage title remains a fallback | HTTP heading assertions |
| Beginner ACF fields and partial | Hidden until both heading and nonempty body exist; copywriter text is pending | Final HTTP checks confirm no empty sections |
| Scoped CSS | Class styling, margins and FAQ interactions | Chrome desktop/mobile for both templates; six English style probes initially matched baseline; historical h1 sizes were subsequently restored at the user’s request; no horizontal overflow; FAQ and tabs work |
| robots.txt / llms.txt | Crawlers, direct static requests, sitemap directive | HTTP 200/text/plain and exact supplied-content comparison |

Cache boundary: site-specific LiteSpeed purge after writes. No real form submission, payment, mail, or full database restore was tested. Supplied crawler policies were implemented as requested; actual indexing and AI citations are not guaranteed. See `docs/SEO-RELEASE-2026-11-06.md` for backup and rollback boundaries.

Local sync adds these verified boundaries: separate imported database and domain replacement; image file SHA-256 plus 66 rendered image URL HEAD checks; environment-gated mail/HTTP/noindex MU guard; two local-only inactive marketing/indexing plugins. Hosted theme/MU files match the local copy, including the newly portable blog/vacancies menu links. Previous local theme versions were archived outside Git. Uploaded media, SQL, WordPress core and third-party plugins remain outside the code commit; relevant native theme/MU additions are tracked. Local web PHP is still 7.4.33, so HTTP validation documents compatibility rather than identical infrastructure.

Build path: `src/scss/style.scss` imports `_language-seo.scss`; `helpers/_fonts.scss` owns the restored 70/50/32 px `.h1` rule. `scssBuild` was executed with isolated output, asserting both historical sizes and SEO rule presence. CSS is now part of the normal bundle, so asset cleanup cannot discard a separately enqueued SEO file. Main-page appointment and language section headings were checked on wide desktop, shorter desktop, laptop and mobile dimensions.

Reviews/teachers menu fix: shared header template covers desktop/mobile; footer uses the same home-page URLs. Navigation script is narrowly limited to `#ed-teachers`/`#ed-testimonials` on the current origin and leaves other links alone. Source/asset equality and the real Gulp copy task were checked. Local HTTP checks covered blog, vacancies and language-page links plus both home targets; browser checks confirmed desktop blog -> home teachers on local and production (target ~176 px below viewport top, header 160 px) and mobile vacancies -> home reviews (target ~111 px, header 95 px). No database writes or form sends were needed.

Production spec audit: all 6 desktop/mobile layouts, 42 exact FAQ titles, 66 image URLs, 7 direction links, 35 llms/sitemap links, self-canonicals and sitemap membership checked. Four FAQ title hyphens were normalized through guarded ACF subfield writes on production and local; full-array readback preserved answers. `normalize-faq-lesson-heading.php` documents the narrow operation. Beginner bodies remain intentionally empty under the user's deferred-copy instruction; see the production audit report.

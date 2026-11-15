# Language pricing v2 Implementation Plan

> Execute inline with superpowers:executing-plans.

Goal — shared Ukrainian ACF editor and pricing visual for language-template pages.
Architecture — native repeaters define stable option IDs; native price rows reference those IDs; an admin generator fills missing combinations. A page selector chooses the visual. Production public release remains explicitly locked until authorized.
Stack — WordPress, ACF Pro, PHP 7.4-compatible theme, existing Gulp SCSS/JS pipeline.
Spec — docs/LANGUAGE-PRICING-DESIGN.md, amended by the user to require a per-page visual selector.

## Steps

- [x] Add ACF definitions, input validation and readiness model in inc/language-pricing.php; load from functions.php. Check duplicate/missing prices and stable references with a WP-CLI verification script.
- [x] Add admin generator in src/js/libraries/language-pricing-admin.js and a Ukrainian guide. Check create, rename, reorder and preserve prices through native ACF save.
- [x] Add parts/content-language-pricing.php, frontend controller and scoped SCSS. Wrap only the old pricing block with a conditional in template-language.php. Use the existing callback form, with no navigation links on variants or cards.
- [x] Build through native tasks; check old Polish output, new preview with temporary local fixture, all combinations and missing values, desktop/mobile layout, syntax and public production lock. Remove the temporary fixture, keep live content on the old visual, document fields and rollout.

## Decisions

The release selector is editable per page, but production guest visibility additionally requires EDUCADO_PRICING_V2_PUBLIC=true in deployment configuration. Local and authorized editor preview allow checking v2 while filling. No production content is seeded with invented prices.

Review completed in a separate read-only agent. No high/medium issues found. The heading-field instruction was clarified after the review. Ruling — keep production deployment pending while the requested local editor is prepared. Polish local selection is v2 with twelve blank draft rows.

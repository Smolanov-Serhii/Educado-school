# Educado website

## Platform
Web: existing WordPress theme with ACF and Gulp assets.

## Confirmed scope
Educado provides language learning offers. The course store lets visitors compare existing courses and open their existing external course links. Ukrainian is the current store UI language.

## Constraints
Preserve the native ACF course schema and production data. The shared store template serves Spanish and English. For local review, the user explicitly approved filling BOTH stores with the English reference catalog; original contents are archived outside Git. Implement locally before a separately authorized production release.

Reuse the homepage callback form and its existing handler; store-specific text must be editable independently. Use reference FAQ questions and first answer; other answers may be drafted neutrally without inventing commercial conditions, as approved by the user.

## Evidence
`template/template-market.php`, `template/template-home.php`, current ACF records and the supplied store artifact. No new payment integration is requested.

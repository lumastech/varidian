# Handoff: Varidian landing page redesign (Welcome.vue)

## Overview
A visual redesign of the Varidian Consulting marketing landing page. The goal was to move the page off flat gradients and icon-only cards onto real photography, shift the palette from the old teal/blue to Varidian Blue, and remove unverified trust claims.

The target file in your repo is `resources/js/Pages/Welcome.vue` (or wherever `Welcome.vue` currently lives). **All body copy is unchanged from the existing Vue file** — this is a visual/structural change, not a copy rewrite.

## About the Design Files
`Landing Redesign.html` in this bundle is a **design reference created in HTML** — a prototype showing the intended look and behaviour, not production code to paste in. Recreate it inside your existing Vue 3 + Tailwind + Inertia setup, keeping your `SeoHead`, `WhatsAppButton`, `mkt-*` CSS variables and component conventions. The plain CSS in the mockup is there so the file opens standalone; port those rules into the SFC's `<style>` block (or Tailwind utilities) as fits your codebase.

## Fidelity
**High-fidelity.** Final colours, typography, spacing and photo treatment. Recreate closely.

## What changed from the current Welcome.vue

1. **Palette**: teal `#00C9A7` / blue `#00A3FF` → Varidian Blue `#0F9ED5` with deep-teal-navy anchors. The teal-to-blue gradient on "Africa." and "in action?" is replaced with flat brand blue.
2. **Hero**: was a two-column layout (copy left, fake dashboard card right). Now a **single centred column over a full-bleed photo**. The mock dashboard card (412 pupils / 98% collected / bar chart) is **deleted** — it was fabricated data.
3. **Product cards**: gained a duotone photo header strip with the section icon straddling the photo/body seam.
4. **ZRA badge removed.** The `badge: 'ZRA Smart Invoice compliant'` field and `.zra-badge` styling are gone until certification is in hand. The FAQ answer about ZRA is unchanged.
5. **Offline-first section**: now a full-bleed dark photo band (mobile money kiosk) instead of a flat alt-background section.
6. **Why Varidian**: the "Locally supported, regionally scaled" card is now a photo card (Cairo Road, Lusaka) with white text over duotone.
7. **Trust strip**: unchanged content, now sitting on a translucent bar at the bottom of the hero photo.

## Screens / Views

Single page, seven bands in order: Header · Hero · Products · Offline-first · Why Varidian · FAQ · Bottom CTA. (The header is included in the mockup for context only — your layout already provides it. Nav items match `ui_kits/varidian_site/index.html`: Home / Products / About / Contact + WhatsApp CTA.)

### Hero
- **Purpose**: state what Varidian does and drive a demo request.
- **Layout**: `position:relative; overflow:hidden`, `min-height: min(760px, 100vh)`, flex column centred, `padding: 150px 0 0`. Inner content `max-width:880px`, centred, `text-align:center`.
- **Photo layer**: absolutely positioned, `inset:0`. `<img>` fills with `object-fit:cover`, `filter: grayscale(1) contrast(1.15) brightness(.72) blur(1.5px)`, `transform: scale(1.06)`, `opacity:.42`.
- **Scrim** (`::after` on the photo layer, `inset:0`):
  `radial-gradient(ellipse 90% 70% at 50% 20%, rgba(15,158,213,.30), transparent 62%)`, over
  `linear-gradient(180deg, rgba(8,44,60,.86) 0%, rgba(8,44,60,.80) 45%, rgba(8,44,60,.96) 100%)`.
  This is deliberately heavy — the photo reads as texture, not subject.
- **Dot grid**: `radial-gradient(circle at 1px 1px, rgba(255,255,255,.07) 1px, transparent 0)`, `background-size: 34px 34px`.
- **Pill**: border `1px solid rgba(166,221,243,.38)`, bg `rgba(15,158,213,.14)`, text `#A6DDF3`, radius 999px, padding `6px 15px`, 12.5px/600, `backdrop-filter: blur(4px)`. 7px brand-blue dot.
- **H1**: Bricolage Grotesque 800, `clamp(42px, 6vw, 72px)`, line-height 1.03, letter-spacing -0.02em, `#FFFFFF`. "Africa." in `#0F9ED5`.
- **Body**: 17px/1.68, `rgba(255,255,255,.74)`, `max-width:640px`, centred.
- **Primary CTA**: bg `#0F9ED5`, white, 700/15px, padding `14px 26px`, radius 12px, shadow `0 10px 30px rgba(15,158,213,.35)`; hover `#0B82B3`. WhatsApp glyph 17px.
- **Secondary CTA**: transparent `rgba(255,255,255,.06)`, border `1.5px solid rgba(255,255,255,.28)`, white; hover border `rgba(255,255,255,.6)`.
- **Trust strip**: `margin-top:72px`, `border-top:1px solid rgba(255,255,255,.12)`, bg `rgba(8,44,60,.4)`, `backdrop-filter: blur(6px)`, padding `26px 24px`. 4-col grid → 2-col under 700px. Labels 10.5px/700, letter-spacing .09em, uppercase, `rgba(166,221,243,.75)`. Values 14.5px/700 white.

### Products
- **Layout**: `padding: 110px 0`, `max-width:1120px`. 2-col grid, `gap:22px`, single column under 760px.
- **Section head**: centred, `max-width:620px`, `margin-bottom:56px`. Chip → title → lead.
- **Chip**: 11px/700, letter-spacing .11em, uppercase, `#0B82B3` on `#ECF8FD`, border `1px solid #D2EEFA`, radius 999px, padding `5px 13px`.
- **Section title**: `clamp(30px,3.4vw,42px)`/700, line-height 1.14, `#102029`; `<em>` renders non-italic in `#0B82B3`.
- **Card**: `position:relative`, bg white, border `1px solid #E2E8EE`, radius 16px. Hover: border `#A6DDF3`, `translateY(-3px)`, shadow `0 18px 44px rgba(14,66,88,.09)`, 0.25s.
- **Photo strip**: height 132px, `border-radius:15px 15px 0 0`, `overflow:visible` (so the icon can overhang), duotone treatment below.
- **Icon tile**: absolutely positioned inside the photo strip at `left:20px; bottom:-22px; z-index:2`. 46×46, radius 12px, white bg, border `1px solid #E2E8EE`, shadow `0 6px 18px rgba(14,66,88,.12)`, icon colour `#0B82B3`, stroke-width 1.6.
- **Body**: `padding: 38px 24px 26px` — the 38px top clears the overhanging icon. H3 17px/700/1.3. Paragraph 14.5px/1.65 `#4F5F6B`. "Learn more →" 13px/700 in `#0B82B3`.
- **Below grid**: centred outline button, `margin-top:42px`.

### Duotone treatment (used on product cards, the why-photo card, and — in a lighter form — the offline band)
```css
.duo { position: relative; overflow: hidden; background: #082C3C; }
.duo img { width:100%; height:100%; object-fit:cover;
           filter: grayscale(1) contrast(1.1) brightness(.85); opacity:.62; }
.duo::after { content:""; position:absolute; inset:0;
              background: linear-gradient(165deg, rgba(15,158,213,.42), rgba(8,44,60,.78));
              mix-blend-mode: multiply; }
```
On the product cards `.prod-img img` and `.prod-img::after` also take `border-radius:15px 15px 0 0`, because the strip itself must stay `overflow:visible`.

### Offline-first band
- Full-bleed, bg `#082C3C`, `padding: 104px 0`, white text.
- Photo layer `inset:0`: `object-fit:cover`, `filter: grayscale(1) brightness(.7)`, `opacity:.36`.
- Overlay: `linear-gradient(105deg, rgba(8,44,60,.95) 0%, rgba(8,44,60,.86) 52%, rgba(15,158,213,.42) 100%)`.
- Inner grid `56px 1fr`, `gap:34px`, collapses to one column under 700px.
- Icon box 56×56, radius 14px, bg `rgba(15,158,213,.18)`, border `1px solid rgba(166,221,243,.3)`.
- Chip on dark: bg `rgba(15,158,213,.16)`, border `rgba(166,221,243,.32)`, text `#A6DDF3`.
- Lead 15.5px, `rgba(255,255,255,.74)`, `max-width:640px`.
- Three feature cards: `rgba(255,255,255,.06)` on `1px solid rgba(255,255,255,.13)`, radius 14px, padding 20px. Heading 11.5px/800 uppercase letter-spacing .09em in `#A6DDF3` (Manrope, not the display face). Body 13.5px/1.62 `rgba(255,255,255,.7)`.

### Why Varidian
- 6-col grid, `gap:16px`. Large cards `span 3`, small `span 2`; 2-col under 1024px, 1-col under 640px.
- Card: white, border `1px solid #E2E8EE`, radius 18px, padding 28px. Hover: border `#A6DDF3`, shadow `0 14px 40px rgba(14,66,88,.08)`.
- Icon well 46×46, radius 12px, bg `#ECF8FD`, border `1px solid #D2EEFA`, icon `#0B82B3`, `margin-bottom:20px`.
- H4 16.5px/700/1.35. Body 14px/1.65 `#4F5F6B`.
- **Photo variant** (last card): `padding:0`, `overflow:hidden`, `min-height:230px`, transparent border, content bottom-aligned. Absolutely positioned `.duo` fills it; text block sits above at `padding:26px` with white heading and `rgba(255,255,255,.75)` body.

### FAQ
- Alt background `#F7F9FB` with top/bottom `1px solid #E2E8EE` hairlines. Content `max-width:760px`.
- Row: `border-bottom:1px solid #E2E8EE`, `padding:20px 8px`, `cursor:pointer`, hover bg white.
- Question 15.5px/600 `#102029`. Marker 28×28 circle, border `1px solid #CBD5DD`, chevron `#6B7C88`.
- Open state: marker bg `#ECF8FD`, border `#A6DDF3`, chevron `#0B82B3`, `transform: rotate(180deg)`. Answer 14.5px/1.7 `#4F5F6B`, `padding: 12px 44px 4px 0`.
- **Accordion behaviour is unchanged from your current implementation** — single open row, clicking the open row closes it. First row open on load (`activeFaq = ref(0)`).

### Bottom CTA
- Panel radius 24px, `padding: 74px 32px`, centred, white text.
- Background `linear-gradient(150deg, #0E4258, #082C3C 60%)` with a `::before` overlay `radial-gradient(ellipse 70% 90% at 80% 0%, rgba(15,158,213,.42), transparent 60%)`.
- "in action?" is flat `#0F9ED5` — the old teal→blue text gradient is gone.

## Interactions & Behavior
- **Reveal on scroll**: `IntersectionObserver`, threshold 0.1, adds `.in`. Initial `opacity:0; transform:translateY(18px)`; revealed `opacity:1; transform:none`; transition `.7s ease` on both. Same as your current `[data-reveal]` implementation — keep it in `onMounted`.
- **FAQ**: as above, unchanged logic.
- **Card hovers**: 0.25s on border-color, transform, box-shadow.
- **Responsive**: hero type scales via `clamp()`; nav hides under 860px; product grid 2→1 at 760px; trust strip 4→2 at 700px; offline band grid collapses at 700px; why grid 6-col → 2-col at 1024px → 1-col at 640px.
- Consider `@media (prefers-reduced-motion: reduce)` to disable the reveal transform, matching `ui_kits/varidian_site/index.html`.

## State Management
- `activeFaq: ref<number|null>(0)` — unchanged.
- The `products`, `offlineFeatures`, `whyCards`, `trustBar` and `faqs` arrays stay as they are, with two edits: drop the `badge` key from the BizManager product, and add an `img` key (and `alt`) per product for the new photo strips. `whyCards` needs a `photo` flag on the "Locally supported, regionally scaled" entry.

## Design Tokens
**Colour**
| Token | Hex | Use |
|---|---|---|
| brand | `#0F9ED5` | primary, accents, CTA |
| brand-600 | `#0B82B3` | hover, link text, chip text |
| brand-700 | `#0A6B93` | link hover |
| brand-50 | `#ECF8FD` | chip bg, icon wells |
| brand-100 | `#D2EEFA` | chip border |
| brand-200 | `#A6DDF3` | hover borders, on-dark text |
| deep | `#0E4258` | CTA gradient start |
| deepest | `#082C3C` | dark bands, scrims |
| ink | `#102029` | headings |
| ink-700 | `#2A3E49` | body |
| muted | `#4F5F6B` | secondary text |
| muted-2 | `#6B7C88` | tertiary text |
| line | `#E2E8EE` | borders |
| line-2 | `#CBD5DD` | stronger borders |
| surface | `#FFFFFF` | cards, page |
| surface-2 | `#F7F9FB` | alt sections |

**Type** — Display: Bricolage Grotesque 600/700/800 (h1–h4). Body: Manrope 400/500/600/700. Scale: h1 `clamp(42px,6vw,72px)`/800; section title `clamp(30px,3.4vw,42px)`/700; card h3 17px/700; why h4 16.5px/700; body 14–17px; eyebrow/chip 11px/800 uppercase.

**Spacing** — section padding 110px vertical (104px for the dark band); container `max-width:1120px`, `padding: 0 24px`; grid gaps 16–22px; card padding 24–28px.

**Radius** — 12px (buttons, icon tiles) · 14px (feature cards, icon box) · 16px (product cards) · 18px (why cards) · 24px (CTA panel) · 999px (pills, chips).

**Shadows** — CTA `0 10px 30px rgba(15,158,213,.35)` · product hover `0 18px 44px rgba(14,66,88,.09)` · why hover `0 14px 40px rgba(14,66,88,.08)` · icon tile `0 6px 18px rgba(14,66,88,.12)`.

## Assets

**Logo**: `assets/brand/varidian-logo-white.png` (header, on dark). Use your existing logo pipeline.

**Photography — these are placeholders and must be replaced before launch.** The mockup hotlinks Wikimedia Commons files via `Special:FilePath`. They load reliably but are **CC BY-SA**, which requires attribution and share-alike — not appropriate for a commercial site as-is.

| Slot | File (Wikimedia Commons) | Notes |
|---|---|---|
| Hero | `Lusaka, capital city of Zambia.jpg` | CC BY-SA 4.0 |
| Product — School | `Zambian kids learning how to use computers.jpg` | |
| Product — Church | `The Great Psalms Choir.jpg` | |
| Product — BizManager | `A trader at Makola Market.jpg` | Ghana, not Zambia |
| Product — Village Banking | `A traditional market woman.jpg` | Ghana, not Zambia |
| Offline-first band | `Woman on mobile money kiosk.jpg` | |
| Why — locally supported | `2010 Cairo Road Lusaka Zambia 4973669840.jpg` | |

URL pattern used: `https://commons.wikimedia.org/wiki/Special:FilePath/<File%20Name>.jpg?width=<w>`

**Recommended replacements**, in order of preference:
1. **Varidian's own photography** — real client schools, real staff, real offices. This is the actual trust upgrade; stock of any kind is a stopgap.
2. **Unsplash** (free for commercial use, no attribution required): search pages `unsplash.com/s/photos/lusaka`, `/mobile-money`, `/african-classroom`, `/market-trader`, `/church-congregation`. Download and self-host under `/public/images/` — do not hotlink Unsplash in production.

Suggested self-hosted filenames: `hero-lusaka.jpg` (≥1800px wide), `product-school.jpg`, `product-church.jpg`, `product-biz.jpg`, `product-village.jpg` (≥900px), `band-mobile-money.jpg` (≥1600px), `why-local.jpg` (≥900px). Serve WebP with JPEG fallback; the duotone treatment hides most compression artefacts, so these can be aggressively compressed.

## Files
- `Landing Redesign.html` — the design reference (this bundle).
- `Welcome.vue` — the current source file this replaces (this bundle, for diffing).
- In the design project: `ui_kits/varidian_site/index.html` and `Varidian Site.html` define the site's real header, nav and footer chrome.

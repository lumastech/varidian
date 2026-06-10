# Varidian Website Update — Coding Agent Prompt
**Varidian Consulting Limited** | varidianlab.com
**Stack:** Laravel + Vue 3 + Inertia.js + Tailwind CSS
**Scope:** Content & copy update · SEO & meta tags · WhatsApp & contact integration

---

## Context

This is the public-facing marketing website for **Varidian Consulting Limited**, a Zambian software development firm based in Lusaka. The site is built on the same Laravel + Vue 3 + Inertia + Tailwind stack used across all Varidian products.

The goal of this update is NOT a visual redesign — do not alter the existing design system or layout structure unless a specific instruction below says to. The goals are:

1. Replace generic/placeholder content with accurate, market-specific copy
2. Add complete SEO meta tags to every page
3. Add a floating WhatsApp contact button site-wide
4. Build or update a Contact page with a working inquiry form
5. Ensure every page communicates trust signals relevant to the Zambian B2B market

Read all sections before writing any code. Each section has specific file targets, exact copy, and acceptance criteria.

---

## 1. Site-Wide SEO — Head Meta Tags

### 1.1 Create a Reusable SEO Vue Component

Create `resources/js/Components/SeoHead.vue`:

```vue
<script setup lang="ts">
import { Head } from '@inertiajs/vue3'

interface Props {
    title?: string
    description?: string
    ogTitle?: string
    ogDescription?: string
    ogImage?: string
    ogType?: string
    canonicalUrl?: string
}

const props = withDefaults(defineProps<Props>(), {
    title: 'Varidian Consulting Limited — Software Solutions for Zambia',
    description: 'Varidian builds sector-specific management systems for Zambian schools, churches, NGOs, and businesses. ZRA Smart Invoice compliant. Built and supported in Lusaka.',
    ogTitle: 'Varidian Consulting Limited',
    ogDescription: 'Management systems purpose-built for Zambian organisations — schools, churches, NGOs, and SMEs.',
    ogImage: '/images/og-default.png',
    ogType: 'website',
    canonicalUrl: '',
})
</script>

<template>
    <Head>
        <title>{{ title }}</title>
        <meta name="description" :content="description" />

        <!-- Open Graph (Facebook, WhatsApp, LinkedIn previews) -->
        <meta property="og:title" :content="ogTitle || title" />
        <meta property="og:description" :content="ogDescription || description" />
        <meta property="og:image" :content="ogImage" />
        <meta property="og:type" :content="ogType" />
        <meta property="og:site_name" content="Varidian Consulting Limited" />
        <meta property="og:locale" content="en_ZM" />
        <link v-if="canonicalUrl" rel="canonical" :href="canonicalUrl" />

        <!-- Twitter / X Card -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="ogTitle || title" />
        <meta name="twitter:description" :content="ogDescription || description" />
        <meta name="twitter:image" :content="ogImage" />

        <!-- Additional trust/regional signals -->
        <meta name="geo.region" content="ZM-09" />
        <meta name="geo.placename" content="Lusaka, Zambia" />
        <meta name="author" content="Varidian Consulting Limited" />
    </Head>
</template>
```

### 1.2 Create the OG Default Image

Create `public/images/og-default.png`:
- Dimensions: 1200 × 630px
- Content: Varidian logo centred, tagline "Software built for Zambia" below, brand background colour
- If image generation is not available, create a placeholder SVG at `public/images/og-default.svg` with the same content and note it for manual replacement

### 1.3 Structured Data — JSON-LD

Add a JSON-LD organisation schema to the root layout (`resources/js/Layouts/GuestLayout.vue` or the main app layout used for public pages). Inject it as a `<script type="application/ld+json">` tag inside `<Head>`:

```json
{
    "@context": "https://schema.org",
    "@type": "SoftwareApplication",
    "name": "Varidian Consulting Limited",
    "url": "https://varidianlab.com",
    "description": "Sector-specific management systems for Zambian schools, churches, NGOs, and businesses.",
    "applicationCategory": "BusinessApplication",
    "operatingSystem": "Web",
    "offers": {
        "@type": "Offer",
        "priceCurrency": "ZMW",
        "availability": "https://schema.org/InStock"
    },
    "provider": {
        "@type": "Organization",
        "name": "Varidian Consulting Limited",
        "url": "https://varidianlab.com",
        "logo": "https://varidianlab.com/images/logo.png",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Lusaka",
            "addressCountry": "ZM"
        },
        "contactPoint": {
            "@type": "ContactPoint",
            "contactType": "customer support",
            "availableLanguage": "English"
        }
    }
}
```

### 1.4 Laravel `config/seo.php`

Create `config/seo.php` for centralised SEO defaults shared with Inertia:

```php
return [
    'site_name'       => 'Varidian Consulting Limited',
    'default_title'   => 'Varidian Consulting Limited — Software Solutions for Zambia',
    'title_separator' => ' — ',
    'description'     => 'Varidian builds sector-specific management systems for Zambian schools, churches, NGOs, and businesses. ZRA Smart Invoice compliant. Built and supported in Lusaka.',
    'og_image'        => '/images/og-default.png',
    'twitter_handle'  => '',
    'canonical_base'  => env('APP_URL', 'https://varidianlab.com'),
];
```

### 1.5 robots.txt

Create `public/robots.txt`:
```
User-agent: *
Allow: /
Sitemap: https://varidianlab.com/sitemap.xml
```

### 1.6 Sitemap Route

Add `GET /sitemap.xml` route returning a dynamic XML sitemap with all public pages:
- `/` (homepage)
- `/about`
- `/products`
- `/products/school-management-system`
- `/products/church-management-system`
- `/products/bizmanager`
- `/products/village-banking`
- `/contact`
- `/pricing`

---

## 2. Homepage Copy Update

### 2.1 Target file
Locate the homepage Vue component — likely `resources/js/Pages/Home.vue` or `resources/js/Pages/Welcome.vue`. Update the following sections with the exact copy below. Do not alter component structure, CSS classes, or layout — only update text content and add the `SeoHead` component.

### 2.2 Add SeoHead to Homepage

At the top of the template, add:
```vue
<SeoHead
    title="Varidian Consulting Limited — Software Built for Zambia"
    description="We build school, church, and business management systems purpose-built for Zambian organisations. ZRA Smart Invoice compliant. Locally supported in Lusaka."
    canonical-url="https://varidianlab.com"
/>
```

### 2.3 Hero Section Copy

Replace existing hero headline and subheadline with:

**Headline:**
> Software built for Zambian schools, churches, and businesses

**Subheadline:**
> We build management systems that understand the Zambian context — ECZ exams, ZRA Smart Invoice compliance, Airtel Money and MTN MoMo, NAPSA payroll. Built and supported right here in Lusaka.

**Primary CTA button text:**
> Request a demo

**Primary CTA link:**
> Opens WhatsApp: `https://wa.me/260971864421?text=Hi%2C%20I%27d%20like%20to%20request%20a%20demo%20of%20a%20Varidian%20product.`

**Secondary CTA button text:**
> See our products

**Secondary CTA link:**
> `/products`

### 2.4 Social Proof Bar

Add a trust bar below the hero (a simple row of short statements). Insert this text content into the existing trust/stats section or add a new one:

| Signal | Text |
|---|---|
| Location | Lusaka, Zambia |
| Compliance | ZRA Smart Invoice ready |
| Integrations | Airtel Money · MTN MoMo |
| Support | Local phone & WhatsApp support |

### 2.5 Products Section Copy

Replace each product card's title, description, and CTA with the following:

**Card 1 — School Management System**
- Title: `Zambian School Management System (ZSSMS)`
- Description: `Grades 1–12 on the MoE three-term calendar. ECZ exam tracking for Grades 7, 9, and 12. Fee collection via Airtel Money and MTN MoMo. NAPSA and PAYE payroll for staff. Built for Zambian private and community schools.`
- CTA: `Learn more →`

**Card 2 — Church Management System**
- Title: `Church Management System`
- Description: `Member and cell group registry. Tithe and offering tracking with mobile money. Attendance, events, and SMS or WhatsApp notifications to your congregation. Purpose-built for Zambian churches and ministries.`
- CTA: `Learn more →`

**Card 3 — BizManager**
- Title: `Varidian BizManager`
- Badge (add a small badge above the title): `ZRA Smart Invoice compliant`
- Description: `A complete business manager for Zambian SMEs. Invoicing, inventory, sales, expenses, and reports — all connected to ZRA Smart Invoice for full compliance. Works on mobile. Supported locally.`
- CTA: `Learn more →`

**Card 4 — Village Banking**
- Title: `Village Banking & Microfinance Platform`
- Description: `Group and member management for VSLAs and microfinance organisations. Loan and savings cycle tracking, campaign management, and mobile money disbursements. Built for Zambian NGOs and community finance groups.`
- CTA: `Learn more →`

### 2.6 Why Varidian Section

Replace or update the "why us" section with the following four points:

| Icon (Tabler) | Heading | Body |
|---|---|---|
| `ti-map-pin` | Built in Zambia, for Zambia | Our systems understand the local context — ZRA compliance, ECZ exams, NAPSA payroll, Airtel and MTN integrations. No offshore vendor offers this depth. |
| `ti-headset` | Local support you can reach | Call or WhatsApp a real person in Lusaka. No helpdesk tickets. No international time zones. We are here when something needs fixing. |
| `ti-refresh` | Systems that grow with you | Start with one module. Add more as your organisation grows. All products are subscription-based with regular updates included. |
| `ti-shield-check` | ZRA compliant from day one | Varidian BizManager is built to the ZRA Smart Invoice VSDC specification. Your business stays compliant without extra effort. |

### 2.7 Footer Copy

Update the footer with the following:

**Company column:**
```
Varidian Consulting Limited
Registered in Zambia
PACRA Reg. No: 120261044865
```

**Contact column:**
```
Lusaka, Zambia
+260 97 1864421
info@varidianlab.com
WhatsApp: +260 97 1864421
```

**Products column links:**
- School Management System
- Church Management System
- BizManager
- Village Banking

**Footer bottom line:**
```
© [CURRENT YEAR] Varidian Consulting Limited. All rights reserved.
```
Use `{{ new Date().getFullYear() }}` in Vue to keep the year current automatically.

---

## 3. About Page

### 3.1 Target file
`resources/js/Pages/About.vue` — create if it does not exist.

### 3.2 SeoHead
```vue
<SeoHead
    title="About Varidian — Zambian Software Development Firm"
    description="Varidian Consulting Limited is a Lusaka-based software development firm building sector-specific management systems for Zambian schools, churches, NGOs, and businesses."
    canonical-url="https://varidianlab.com/about"
/>
```

### 3.3 Page Copy

**Page headline:**
> We build software that understands Zambia

**Body copy (render as flowing paragraphs, not bullet points):**

> Varidian Consulting Limited is a software development firm based in Lusaka, Zambia. We build sector-specific management systems for Zambian institutions — schools, churches, NGOs, and small businesses.
>
> Our products are not generic software adapted for Africa. They are built from the ground up for the Zambian context: the Ministry of Education three-term calendar, ECZ examination tracking, ZRA Smart Invoice compliance, NAPSA and PAYE payroll, and mobile money integrations with Airtel Money and MTN MoMo.
>
> We believe that the organisations doing the most important work in Zambia — the schools educating the next generation, the churches holding communities together, the microfinance groups lifting families out of poverty — deserve software built specifically for them, supported by people who understand their world.
>
> Every system we deliver is built on a proven technology stack and supported locally. When something needs fixing, you call a number in Lusaka.

**Team / director block:**

> **Lubomba Mulomya**
> Director & Software Development Lead
> Over 10 years building software for the Zambian market and Southen African Countries. Led development of Tobacco Board of Zambia, Northen Technical College, Zambian Open University, Zambia
> [Short 2–3 sentence bio — e.g. "X years building software for the Zambian market. Led development of ZSSMS, ChurchMS, and Varidian BizManager. Based in Lusaka."]

Add a placeholder `<img>` tag for the director's photo with `alt="[Name] — Director, Varidian Consulting Limited"`. Note in a code comment: `<!-- Replace src with actual photo path: /images/team/director.jpg -->`.

**Company details block (renders as a clean info card):**

| Label | Value |
|---|---|
| Registered name | Varidian Consulting Limited |
| Registration | PACRA Reg. No: 120261044865 |
| Location | Lusaka, Zambia |
| Website | varidianlab.com |
| Email | info@varidianlab.com |
| Phone / WhatsApp | +260 97 1864421 |

### 3.4 Add Route

Ensure `routes/web.php` has:
```php
Route::get('/about', fn () => inertia('About'))->name('about');
```

---

## 4. Products Pages

### 4.1 Products Index Page

**Target:** `resources/js/Pages/Products/Index.vue` — create if not exists.

**SeoHead:**
```vue
<SeoHead
    title="Our Products — Varidian Consulting Limited"
    description="School management, church management, ZRA-compliant business management, and village banking systems — purpose-built for Zambian organisations."
    canonical-url="https://varidianlab.com/products"
/>
```

**Page headline:** `Management systems built for Zambia`

**Sub-headline:** `Each product is purpose-built for a specific Zambian sector — not adapted from a generic template. All products are web-based, mobile-friendly, and locally supported.`

Render each product as a card linking to its detail page.

**Route:**
```php
Route::get('/products', fn () => inertia('Products/Index'))->name('products');
```

### 4.2 Individual Product Pages

Create the following four pages. Apply the `SeoHead` and body copy as specified. Routes follow the same pattern.

---

**`resources/js/Pages/Products/SchoolManagement.vue`**

Route: `GET /products/school-management-system` → name `products.zssms`

SeoHead:
- Title: `Zambian School Management System (ZSSMS) — Varidian`
- Description: `School management system built for Zambian private and community schools. ECZ exam tracking, MoE three-term calendar, Airtel/MTN fee collection, NAPSA payroll. Supported in Lusaka.`
- Canonical: `https://varidianlab.com/products/school-management-system`

**Feature list copy** (render as icon + heading + description cards):

| Feature | Description |
|---|---|
| MoE three-term calendar | Academic year structured around the Zambian Ministry of Education three-term calendar. Term dates, holidays, and exam periods are built in. |
| ECZ exam tracking | Track pupil performance in Grade 7, Grade 9, and Grade 12 national examinations. Division calculations and ECZ result entry built in. |
| Fee collection with mobile money | Issue fee invoices and collect payments via Airtel Money or MTN MoMo. Automated receipts and arrears tracking. No more paper receipt books. |
| NAPSA & PAYE payroll | Monthly payroll computation for teaching and non-teaching staff. NAPSA contributions and PAYE deductions calculated automatically. |
| Parent communication | SMS notifications to parents for fee reminders, results, and school announcements. |
| Multi-school architecture | One system for school groups or boards managing multiple campuses. |

**Pricing block copy:**
> Annual licence from **K12,000/year** — includes setup, onboarding, and local support.
> Contact us for a custom quote based on your school's size.

CTA: `Request a demo` → WhatsApp link

---

**`resources/js/Pages/Products/ChurchManagement.vue`**

Route: `GET /products/church-management-system` → name `products.cms`

SeoHead:
- Title: `Church Management System — Varidian`
- Description: `Church management system for Zambian churches and ministries. Member registry, tithe tracking, attendance, events, and WhatsApp or SMS notifications.`
- Canonical: `https://varidianlab.com/products/church-management-system`

**Feature list copy:**

| Feature | Description |
|---|---|
| Member & cell group registry | Full member profiles, family links, and cell group assignments. Track membership growth and transfers. |
| Tithe & offering tracking | Record and reconcile tithes, offerings, and pledges. Mobile money collection and reporting by envelope number or member. |
| Attendance management | Weekly service and cell meeting attendance. Identify members who have gone inactive. |
| Events management | Create and manage church events, register attendees, and send reminders. |
| SMS & WhatsApp notifications | Send announcements, reminders, and follow-ups to members via SMS or WhatsApp broadcast. |
| Multi-branch / campus | Support for churches with multiple campuses or satellite congregations under one system. |

**Pricing block copy:**
> Annual licence from **K6,000/year** — includes setup and onboarding.

CTA: `Request a demo` → WhatsApp link

---

**`resources/js/Pages/Products/BizManager.vue`**

Route: `GET /products/bizmanager` → name `products.bizmanager`

SeoHead:
- Title: `Varidian BizManager — ZRA Smart Invoice–Compliant Business Manager`
- Description: `ZRA Smart Invoice–compliant business management system for Zambian SMEs. Invoicing, inventory, sales, expenses, mobile money payments, and VAT reports.`
- Canonical: `https://varidianlab.com/products/bizmanager`

Add a prominent badge/banner at the top of the page:
> ✓ ZRA Smart Invoice compliant — VSDC integrated

**Feature list copy:**

| Feature | Description |
|---|---|
| ZRA Smart Invoice integration | All invoices submitted to ZRA's Smart Invoice system in real time via the VSDC API. ZRA receipt number and QR code printed on every invoice. |
| Offline queue | Transactions are queued locally when connectivity is unavailable and submitted to ZRA automatically when the connection resumes. |
| Inventory management | Stock levels, goods received notes, reorder alerts, and stock movement history. |
| Sales & POS | Fast counter sales interface with barcode support. Cash and mobile money payment recording. |
| Mobile money payments | Record Airtel Money and MTN MoMo payments against invoices. Payment request and reconciliation. |
| Expenses tracking | Record business expenses by category with receipt uploads. |
| VAT & financial reports | VAT summary, outstanding invoices, profit overview, and payment method breakdowns. Exportable to PDF. |
| Multi-branch support | Manage multiple business locations from one dashboard. |

**Pricing block copy:**
> Starting from **K2,400/year** (K200/month equivalent).
> 30-day free trial — no credit card required.

CTA: `Start free trial` → `/register` or contact link

---

**`resources/js/Pages/Products/VillageBanking.vue`**

Route: `GET /products/village-banking` → name `products.village-banking`

SeoHead:
- Title: `Village Banking & Microfinance Platform — Varidian`
- Description: `Village banking and VSLA management platform for Zambian NGOs and microfinance organisations. Group management, loan tracking, savings cycles, and mobile money disbursements.`
- Canonical: `https://varidianlab.com/products/village-banking`

**Feature list copy:**

| Feature | Description |
|---|---|
| Village group management | Register and manage VSLA groups, savings clubs, and village banking groups with full member profiles. |
| Loan & savings cycles | Track savings cycle rounds, loan applications, disbursements, and repayments per member and group. |
| Campaign & referral tracking | Manage outreach campaigns and track new group registrations from field agents. |
| Affiliation & hierarchy | Support for national organisations managing regional, district, and local-level structures. |
| Mobile money disbursements | Disburse loans and savings payouts to members via Airtel Money or MTN MoMo. |
| Field agent portal | Field agents can register groups and members from their mobile browser. |

**Pricing block copy:**
> Custom pricing based on organisation size and number of groups.
> Contact us to discuss your requirements.

CTA: `Get in touch` → WhatsApp link

---

## 5. Contact Page

### 5.1 Target file
`resources/js/Pages/Contact.vue` — create if not exists.

### 5.2 SeoHead
```vue
<SeoHead
    title="Contact Varidian — Lusaka, Zambia"
    description="Get in touch with Varidian Consulting Limited. Call, WhatsApp, or email us from Lusaka, Zambia. Request a product demo or discuss a custom development project."
    canonical-url="https://varidianlab.com/contact"
/>
```

### 5.3 Page Copy

**Headline:** `Get in touch`

**Subheadline:** `We are based in Lusaka. Call, WhatsApp, or send us a message and we will respond within one business day.`

**Contact detail blocks** (render as icon + label + value cards):

| Icon | Label | Value |
|---|---|---|
| `ti-map-pin` | Location | Lusaka, Zambia |
| `ti-phone` | Phone | +260 97 1864421 |
| `ti-brand-whatsapp` | WhatsApp | +260 97 1864421 |
| `ti-mail` | Email | info@varidianlab.com |
| `ti-clock` | Business hours | Monday – Friday, 08:00 – 17:00 CAT |

The WhatsApp value should be a hyperlink: `https://wa.me/260971864421`
The email should be a `mailto:` link.

### 5.4 Inquiry Form

Build a Laravel + Inertia inquiry form:

**Form fields:**
- `name` — text input, required, max 150
- `organisation` — text input, optional, placeholder "School, church, company name"
- `phone` — text input, required, placeholder "+260..."
- `email` — email input, optional
- `product_interest` — select dropdown, required
  - Options: School Management System, Church Management System, BizManager, Village Banking, Custom Development, General Inquiry
- `message` — textarea, required, min 10 chars, max 2000
- Submit button: `Send message`

**Backend:**

Create `app/Http/Controllers/ContactController.php`:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ContactInquiryMail;

class ContactController extends Controller
{
    public function show()
    {
        return inertia('Contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:150',
            'organisation'     => 'nullable|string|max:200',
            'phone'            => 'required|string|max:30',
            'email'            => 'nullable|email|max:150',
            'product_interest' => 'required|string',
            'message'          => 'required|string|min:10|max:2000',
        ]);

        // Send email notification
        try {
            Mail::to(config('mail.contact_recipient', 'info@varidianlab.com'))
                ->send(new ContactInquiryMail($validated));
        } catch (\Throwable $e) {
            Log::error('Contact form mail failed', ['error' => $e->getMessage()]);
        }

        return back()->with('success', 'Thank you for your message. We will be in touch within one business day.');
    }
}
```

**Mailable:**

Create `app/Mail/ContactInquiryMail.php`:
- Subject: `New inquiry from [name] — [product_interest] | varidianlab.com`
- Plain text email body listing all submitted fields
- Reply-To set to the submitter's email (if provided) or phone

**Routes:**
```php
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
```

**Rate limiting:**
Add to `RouteServiceProvider` or `routes/web.php`:
```php
Route::middleware('throttle:5,1')->post('/contact', ...);
```
(Maximum 5 submissions per IP per minute)

**Add to `.env`:**
```
MAIL_CONTACT_RECIPIENT=info@varidianlab.com
```

**Success state:**
On successful submission, show an inline success message (no redirect):
> "Thank you, [name]. We have received your message and will be in touch within one business day. You can also reach us directly on WhatsApp: +260 XXXXXXXXX"

**Validation errors:**
Display field-level errors inline using Inertia's `useForm` error handling — same pattern as the rest of the application.

---

## 6. WhatsApp Integration — Site-Wide

### 6.1 WhatsApp Config

Add to `config/app.php` (or a new `config/contact.php`):
```php
'whatsapp_number'  => env('WHATSAPP_NUMBER', '260971864421'),
'whatsapp_message' => env('WHATSAPP_DEFAULT_MESSAGE', 'Hi, I would like to learn more about Varidian products.'),
```

Add to `.env`:
```
WHATSAPP_NUMBER=260971864421
WHATSAPP_DEFAULT_MESSAGE=Hi, I would like to learn more about Varidian products.
```

Share via Inertia's `HandleInertiaRequests.php` shared data:
```php
'contact' => [
    'whatsapp_number'  => config('contact.whatsapp_number'),
    'whatsapp_url'     => 'https://wa.me/' . config('contact.whatsapp_number'),
    'phone'            => config('contact.phone'),
    'email'            => config('contact.email'),
],
```

### 6.2 WhatsAppButton Vue Component

Create `resources/js/Components/WhatsAppButton.vue`:

```vue
<script setup lang="ts">
import { usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

interface Props {
    message?: string
    label?: string
    variant?: 'floating' | 'inline' | 'icon'
}

const props = withDefaults(defineProps<Props>(), {
    message: 'Hi, I would like to learn more about Varidian products.',
    label: 'Chat on WhatsApp',
    variant: 'inline',
})

const page = usePage()
const baseNumber = computed(() => (page.props.contact as any)?.whatsapp_number ?? '')

const waUrl = computed(() => {
    const encoded = encodeURIComponent(props.message)
    return `https://wa.me/${baseNumber.value}?text=${encoded}`
})
</script>

<template>
    <!-- Floating variant -->
    <a
        v-if="variant === 'floating'"
        :href="waUrl"
        target="_blank"
        rel="noopener noreferrer"
        aria-label="Chat with Varidian on WhatsApp"
        class="fixed bottom-6 right-6 z-50 flex items-center gap-2 rounded-full bg-[#25D366] px-4 py-3 text-white shadow-lg transition hover:bg-[#1DA851] focus:outline-none focus:ring-2 focus:ring-[#25D366] focus:ring-offset-2"
    >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5" aria-hidden="true">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
        <span v-if="variant !== 'icon'" class="text-sm font-medium">{{ label }}</span>
    </a>

    <!-- Inline variant -->
    <a
        v-else
        :href="waUrl"
        target="_blank"
        rel="noopener noreferrer"
        class="inline-flex items-center gap-2 rounded-lg bg-[#25D366] px-4 py-2 text-sm font-medium text-white transition hover:bg-[#1DA851]"
    >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4" aria-hidden="true">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
        {{ label }}
    </a>
</template>
```

### 6.3 Add Floating Button to Root Layout

In the main public layout (the layout used by all guest/marketing pages), add inside the `<template>`:

```vue
<WhatsAppButton
    variant="floating"
    message="Hi, I'd like to learn more about Varidian products."
/>
```

This renders the floating green WhatsApp button fixed to the bottom-right corner of every public page.

### 6.4 Per-Page WhatsApp CTAs

On each product page's CTA section, use the inline variant with a product-specific pre-filled message:

```vue
<!-- On ZSSMS page -->
<WhatsAppButton
    variant="inline"
    label="Request a ZSSMS demo"
    message="Hi, I'd like to request a demo of the Zambian School Management System (ZSSMS)."
/>

<!-- On ChurchMS page -->
<WhatsAppButton
    variant="inline"
    label="Request a ChurchMS demo"
    message="Hi, I'd like to request a demo of the Church Management System."
/>

<!-- On BizManager page -->
<WhatsAppButton
    variant="inline"
    label="Start free trial"
    message="Hi, I'd like to start a free trial of Varidian BizManager."
/>

<!-- On Village Banking page -->
<WhatsAppButton
    variant="inline"
    label="Get in touch"
    message="Hi, I'd like to learn more about the Village Banking platform."
/>
```

---

## 7. Navigation Updates

### 7.1 Update the Public Navigation

In the site's navbar component, ensure the navigation links are:

```
Home        /
Products    /products     (with a dropdown showing all 4 products)
About       /about
Contact     /contact
```

The dropdown on Products should list:
- School Management System → `/products/school-management-system`
- Church Management System → `/products/church-management-system`
- BizManager → `/products/bizmanager`
- Village Banking → `/products/village-banking`

### 7.2 Navbar CTA

Add a right-aligned CTA button in the navbar:

```vue
<WhatsAppButton
    variant="inline"
    label="Get in touch"
    message="Hi, I'd like to learn more about Varidian products."
/>
```

---

## 8. Acceptance Criteria

Before marking this work complete, verify every item below:

**SEO**
- [ ] `<title>` tag on every page is unique, descriptive, and includes "Varidian" and a relevant keyword
- [ ] `<meta name="description">` on every page is between 120–160 characters
- [ ] OG image appears correctly when the homepage URL is pasted into WhatsApp (test using `https://og.live/` or similar)
- [ ] `GET /sitemap.xml` returns valid XML listing all public pages
- [ ] `GET /robots.txt` returns the correct file
- [ ] JSON-LD structured data passes Google's Rich Results Test

**Content**
- [ ] Hero headline on homepage reads "Software built for Zambian schools, churches, and businesses"
- [ ] All four product cards have Zambia-specific descriptions with local feature callouts
- [ ] Footer contains company registration number, Lusaka address, phone, and WhatsApp number
- [ ] About page includes company registration details and director bio section
- [ ] All placeholder text (lorem ipsum or "coming soon") has been replaced

**WhatsApp**
- [ ] Floating green WhatsApp button appears on every public page (bottom-right)
- [ ] Clicking the floating button opens WhatsApp with the default pre-filled message
- [ ] Each product page CTA opens WhatsApp with a product-specific pre-filled message
- [ ] WhatsApp number in `.env` is the correct business number (+260...)
- [ ] Button does not overlap important content on mobile (375px viewport)

**Contact form**
- [ ] Form submits successfully and sends an email to `info@varidianlab.com`
- [ ] Validation errors display inline below each field
- [ ] Success message appears after submission without page redirect
- [ ] Rate limiting prevents more than 5 submissions per minute per IP
- [ ] Form is fully usable on a 375px mobile screen

**Zambia-specific trust signals**
- [ ] PACRA registration number is visible (footer or About page)
- [ ] Lusaka address appears on Contact page and footer
- [ ] +260 phone number is visible and clickable (tel: link) on Contact page
- [ ] "ZRA Smart Invoice compliant" badge is prominent on BizManager page

---

## 9. Placeholders to Fill Before Deploying

The following values must be manually inserted before the site goes live. The agent should insert clearly marked placeholder comments everywhere these appear:

```
<!-- TODO: Replace with actual WhatsApp number -->
WHATSAPP_NUMBER=260971864421

<!-- TODO: Insert PACRA registration number -->
PACRA Reg. No: [INSERT]

<!-- TODO: Insert actual Lusaka address -->
[Street address, Area, Lusaka]

<!-- TODO: Insert director full name and bio -->
<!-- TODO: Replace director photo placeholder /images/team/director.jpg -->

<!-- TODO: Insert actual phone number -->
+260 [NUMBER]
```

---

*Prepared by Varidian Consulting Limited · varidianlab.com*
*Stack: Laravel 11 · Vue 3 · Inertia.js · Tailwind CSS*

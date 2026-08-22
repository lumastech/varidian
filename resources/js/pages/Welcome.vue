<script setup lang="ts">
import { ref, onMounted } from 'vue';
import SeoHead from '@/components/SeoHead.vue';
import WhatsAppButton from '@/components/WhatsAppButton.vue';

interface Product {
    title: string;
    desc: string;
    href: string;
    icon: string;
    img: string;
    alt: string;
}

interface WhyCard {
    heading: string;
    body: string;
    large: boolean;
    icon?: string;
    photo?: boolean;
    img?: string;
    alt?: string;
}

const activeFaq = ref<number | null>(0);

/**
 * Placeholder photography — Wikimedia Commons, CC BY-SA. Replace with Varidian's
 * own photography (or licence-free stock) before launch; the paths stay the same.
 */
const photos = {
    hero: '/images/landing/hero-lusaka.jpg',
    school: '/images/landing/product-school.jpg',
    church: '/images/landing/product-church.jpg',
    biz: '/images/landing/product-biz.jpg',
    village: '/images/landing/product-village.jpg',
    mobileMoney: '/images/landing/band-mobile-money.jpg',
    local: '/images/landing/why-local.jpg',
};

const trustBar = [
    { label: 'Home Base', value: 'Lusaka, Zambia' },
    { label: 'Platforms', value: 'Web · Mobile · Desktop' },
    { label: 'Integrations', value: 'Airtel · MTN · Zamtel' },
    { label: 'Reach', value: 'Deployed across Africa' },
];

const products: Product[] = [
    {
        title: 'School Management System (ZSSMS)',
        desc: 'Grades 1–12 on the MoE three-term calendar. ECZ exam tracking, Airtel/MTN fee collection, NAPSA payroll. Offline-first — continues working through connectivity outages.',
        href: '/products/school-management-system',
        img: photos.school,
        alt: 'Pupils learning to use computers, Zambia',
        icon: `<svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422A12.083 12.083 0 0112 21.07a12.083 12.083 0 01-6.16-10.492L12 14z"/></svg>`,
    },
    {
        title: 'Church Management System',
        desc: 'Member and cell group registry. Tithe and offering tracking with mobile money. Attendance, events, and SMS or WhatsApp notifications. Purpose-built for African churches and ministries.',
        href: '/products/church-management-system',
        img: photos.church,
        alt: 'Church choir',
        icon: `<svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>`,
    },
    {
        title: 'Varidian BizManager',
        desc: 'Complete business manager for SMEs. Invoicing, inventory, sales, expenses — fully integrated with ZRA Smart Invoice. Offline queue ensures no lost transactions during outages.',
        href: '/products/bizmanager',
        img: photos.biz,
        alt: 'A trader at a market stall',
        icon: `<svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>`,
    },
    {
        title: 'Village Banking & Microfinance',
        desc: 'Group and member management for VSLAs and NGO microfinance programmes. Loan and savings cycle tracking, mobile money disbursements. Field agent portal works offline.',
        href: '/products/village-banking',
        img: photos.village,
        alt: 'Market trader',
        icon: `<svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>`,
    },
];

const offlineFeatures = [
    {
        title: 'Local data storage',
        body: 'All records are saved on-device first. Internet is used to sync, not to function.',
    },
    {
        title: 'Automatic sync',
        body: 'The moment connectivity returns, all queued transactions and changes sync without any manual action.',
    },
    {
        title: 'No data loss',
        body: 'Fee payments, exam marks, invoices, savings records — nothing is ever lost to a network outage.',
    },
];

const whyCards: WhyCard[] = [
    {
        icon: `<svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20"/></svg>`,
        heading: 'Built for African contexts — not adapted for them',
        body: 'Our products are not generic systems modified to bolt on mobile money. Local regulations, mobile payments, low-bandwidth environments and offline use are the foundation from day one — not additions.',
        large: true,
    },
    {
        icon: `<svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><line x1="1" y1="1" x2="23" y2="23"/><path d="M16.72 11.06A10.94 10.94 0 0119 12.55M5 12.55a10.94 10.94 0 015.17-2.39M10.71 5.05A16 16 0 0122.56 9M1.42 9a15.91 15.91 0 014.7-2.88M8.53 16.11a6 6 0 006.95 0M12 20h.01"/></svg>`,
        heading: 'Offline-first where it counts',
        body: 'Offline queues, local data storage and background sync are capabilities we build in deliberately, not workarounds added after the fact. A genuine differentiator in African markets.',
        large: true,
    },
    {
        icon: `<svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>`,
        heading: 'Multi-platform delivery',
        body: 'We deliver across web, mobile and desktop — selecting the right platform and stack for each project rather than forcing every problem into one solution.',
        large: false,
    },
    {
        icon: `<svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>`,
        heading: 'Financial governance at board level',
        body: 'With a senior government finance director on the board, financial accountability and regulatory awareness are embedded at the highest level of the company.',
        large: false,
    },
    {
        heading: 'Locally supported, regionally scaled',
        body: 'Clients get a real contact reachable by phone or WhatsApp. Our support infrastructure is local; our reach is continental.',
        large: false,
        photo: true,
        img: photos.local,
        alt: 'Cairo Road, Lusaka',
    },
];

const faqs = [
    {
        q: 'Which sectors do your products serve?',
        a: 'We build for schools (private, community, and higher education), churches and ministries, SMEs requiring ZRA compliance, and NGOs running village banking or microfinance programmes. Our primary market is Zambia but our systems are deployed across East and Southern Africa.',
    },
    {
        q: 'What does "offline-first" mean in practice?',
        a: 'Every Varidian product stores data locally on the device and continues working during internet outages. When connectivity returns, all changes sync automatically. For BizManager, invoices are queued and submitted to ZRA once the connection resumes. For schools, fee records and exam marks are captured regardless of connectivity.',
    },
    {
        q: 'Are your products ZRA Smart Invoice compliant?',
        a: 'Yes. Varidian BizManager is fully integrated with ZRA Smart Invoice via the VSDC API. All invoices are submitted to ZRA in real time with ZRA receipt numbers and QR codes printed on every invoice. The offline queue handles submissions automatically when connectivity returns.',
    },
    {
        q: 'Do you work with organisations outside Zambia?',
        a: 'Yes. While Zambia is our home base, our systems have been deployed in Zimbabwe, Malawi, Tanzania, and other countries. Product features can be adapted for local compliance and payment systems in other African markets.',
    },
    {
        q: 'Can I request a demo before committing?',
        a: 'Absolutely. WhatsApp us or fill in the contact form to request a demo of any product. We will walk you through the system and answer every question before you decide.',
    },
];

onMounted(() => {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((e) => {
                if (e.isIntersecting) {
                    e.target.classList.add('in');
                }
            });
        },
        { threshold: 0.1 },
    );
    document.querySelectorAll('[data-reveal]').forEach((el) => observer.observe(el));
});
</script>

<template>
    <SeoHead
        title="Varidian Consulting Limited — Software that understands Africa"
        description="We design and build web, mobile and desktop management systems for African institutions — purpose-built for the local context. ECZ exams, ZRA tax compliance, Airtel, MTN and Zamtel mobile money, NAPSA payroll, and offline-first where connectivity is limited."
        keywords="varidianlab, varidian lab, varidian consulting, school management system Africa, church management system Zambia, ZRA Smart Invoice software, business management Zambia, village banking VSLA software, offline-first management system, ECZ exam tracking system, NAPSA payroll software Zambia, Airtel Money MTN MoMo integration, Varidian Consulting Limited, African school management software, church software Africa, microfinance NGO software Zambia"
        canonical-url="https://varidianlab.com"
    />

    <div class="v-landing">
        <!-- ══════════════ HERO ══════════════ -->
        <section class="v-hero">
            <div class="v-hero-photo">
                <img :src="photos.hero" alt="Lusaka, Zambia" />
            </div>
            <div class="v-hero-grid"></div>

            <div class="v-hero-in">
                <div class="v-pill">
                    <i></i>
                    Built from Lusaka · Deployed across Africa
                </div>
                <h1 class="v-hero-title">Software that understands <span class="v-accent">Africa.</span></h1>
                <p class="v-hero-body">
                    We design and build web, mobile and desktop management systems for African institutions — purpose-built for the local context. ECZ exams, ZRA tax compliance, Airtel, MTN and Zamtel mobile money, NAPSA payroll, and offline-first where connectivity is limited.
                </p>
                <div class="v-hero-actions">
                    <a
                        href="https://wa.me/260971864421?text=Hi%2C%20I%27d%20like%20to%20request%20a%20demo%20of%20a%20Varidian%20product."
                        target="_blank"
                        rel="noopener noreferrer"
                        class="v-btn-wa"
                    >
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                        </svg>
                        Request a demo
                    </a>
                    <a href="/products" class="v-btn-ghost">See our products</a>
                </div>
            </div>

            <!-- Trust strip -->
            <div class="v-trust">
                <div class="v-trust-in">
                    <div v-for="t in trustBar" :key="t.label" class="v-trust-item">
                        <div class="v-trust-l">{{ t.label }}</div>
                        <div class="v-trust-v">{{ t.value }}</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════ PRODUCTS ══════════════ -->
        <section id="products" class="v-sec">
            <div class="v-wrap">
                <div class="v-sec-head" data-reveal>
                    <div class="v-chip">Products</div>
                    <h2 class="v-sec-title">Management systems<br /><em>built for African institutions</em></h2>
                    <p class="v-sec-lead">Each product is purpose-built for a specific sector — not adapted from a generic template. Offline-first, mobile-ready, and locally supported.</p>
                </div>

                <div class="v-prod-grid" data-reveal>
                    <article v-for="product in products" :key="product.title" class="v-prod">
                        <div class="v-prod-img v-duo">
                            <img :src="product.img" :alt="product.alt" />
                            <!-- eslint-disable-next-line vue/no-v-html -->
                            <div class="v-prod-ico" v-html="product.icon"></div>
                        </div>
                        <div class="v-prod-body">
                            <h3>{{ product.title }}</h3>
                            <p>{{ product.desc }}</p>
                            <a :href="product.href" class="v-more">Learn more →</a>
                        </div>
                    </article>
                </div>

                <div class="v-center-cta" data-reveal>
                    <a href="/products" class="v-btn-outline">View all products</a>
                </div>
            </div>
        </section>

        <!-- ══════════════ OFFLINE-FIRST ══════════════ -->
        <section id="offline" class="v-band">
            <div class="v-band-photo">
                <img :src="photos.mobileMoney" alt="Mobile money kiosk" />
            </div>
            <div class="v-band-in" data-reveal>
                <div class="v-band-ico">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <line x1="1" y1="1" x2="23" y2="23" />
                        <path d="M16.72 11.06A10.94 10.94 0 0119 12.55M5 12.55a10.94 10.94 0 015.17-2.39M10.71 5.05A16 16 0 0122.56 9M1.42 9a15.91 15.91 0 014.7-2.88M8.53 16.11a6 6 0 006.95 0M12 20h.01" />
                    </svg>
                </div>
                <div>
                    <div class="v-chip">Offline-first</div>
                    <h2 class="v-sec-title">Built for the reality<br /><em>of African connectivity.</em></h2>
                    <p class="v-band-lead">
                        Every Varidian product is designed to keep working when your internet goes down. Data is stored locally on the device. Transactions are queued. The moment connectivity returns, everything syncs automatically — no lost data, no manual re-entry.
                    </p>
                    <div class="v-feat-grid">
                        <div v-for="item in offlineFeatures" :key="item.title" class="v-feat">
                            <h4>{{ item.title }}</h4>
                            <p>{{ item.body }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════ WHY VARIDIAN ══════════════ -->
        <section id="why" class="v-sec">
            <div class="v-wrap">
                <div class="v-sec-head" data-reveal>
                    <div class="v-chip">Why Varidian</div>
                    <h2 class="v-sec-title">What sets Varidian <em>apart</em></h2>
                </div>

                <div class="v-why-grid" data-reveal>
                    <div
                        v-for="w in whyCards"
                        :key="w.heading"
                        class="v-why"
                        :class="[w.large ? 'v-why--l' : 'v-why--s', { 'v-why--photo': w.photo }]"
                    >
                        <template v-if="w.photo">
                            <div class="v-duo">
                                <img :src="w.img" :alt="w.alt" />
                            </div>
                            <div class="v-why-txt">
                                <h4>{{ w.heading }}</h4>
                                <p>{{ w.body }}</p>
                            </div>
                        </template>
                        <template v-else>
                            <!-- eslint-disable-next-line vue/no-v-html -->
                            <div class="v-why-ico" v-html="w.icon"></div>
                            <h4>{{ w.heading }}</h4>
                            <p>{{ w.body }}</p>
                        </template>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════ FAQ ══════════════ -->
        <section id="faq" class="v-sec v-sec-alt">
            <div class="v-wrap">
                <div class="v-sec-head" data-reveal>
                    <div class="v-chip">FAQ</div>
                    <h2 class="v-sec-title">Common <em>questions</em></h2>
                </div>

                <div class="v-faq" data-reveal>
                    <div
                        v-for="(faq, i) in faqs"
                        :key="i"
                        class="v-faq-row"
                        :class="{ 'is-open': activeFaq === i }"
                        @click="activeFaq = activeFaq === i ? null : i"
                    >
                        <div class="v-faq-q">
                            <span>{{ faq.q }}</span>
                            <div class="v-faq-mark">
                                <svg fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24">
                                    <path d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        <div v-if="activeFaq === i" class="v-faq-a">{{ faq.a }}</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════ BOTTOM CTA ══════════════ -->
        <section id="cta" class="v-sec">
            <div class="v-wrap">
                <div class="v-cta" data-reveal>
                    <div class="v-chip">Let's get started</div>
                    <h2 class="v-sec-title">
                        Ready to see a product<br />
                        <span class="v-accent">in action?</span>
                    </h2>
                    <p class="v-cta-lead">Request a demo and we'll walk you through the product most relevant to your organisation. No obligation — just a practical conversation.</p>
                    <div class="v-cta-row">
                        <WhatsAppButton
                            variant="inline"
                            label="Request a demo"
                            message="Hi, I'd like to request a demo of a Varidian product."
                            class="v-btn-wa"
                        />
                        <a href="/products" class="v-btn-ghost">See our products</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<style>
/* ══════════════════════════════════════════════════════════════
   Landing page — Varidian Blue redesign.
   Brand tokens are scoped to .v-landing so the shared mkt-* system
   is untouched. Dark values are the default (matching the site);
   the light overrides carry the handoff's literal hex values.
   ══════════════════════════════════════════════════════════════ */
.v-landing {
    --v-brand: #0f9ed5;
    --v-brand-600: #0b82b3;
    --v-brand-700: #0a6b93;
    --v-brand-200: #a6ddf3;
    --v-deep: #0e4258;
    --v-deepest: #082c3c;

    --v-chip-bg: rgba(15, 158, 213, 0.16);
    --v-chip-border: rgba(166, 221, 243, 0.32);
    --v-chip-text: #a6ddf3;
    --v-accent-text: var(--v-brand);
    --v-link: var(--v-brand-200);
    --v-link-hover: #ffffff;
    --v-well-bg: rgba(15, 158, 213, 0.16);
    --v-well-border: rgba(166, 221, 243, 0.3);
    --v-well-icon: var(--v-brand-200);
    --v-hover-border: rgba(166, 221, 243, 0.35);
    --v-prod-shadow: 0 18px 44px rgba(0, 0, 0, 0.4);
    --v-why-shadow: 0 14px 40px rgba(0, 0, 0, 0.35);
    --v-ico-tile-bg: #0f171d;
}

@media (prefers-color-scheme: light) {
    .v-landing {
        --v-chip-bg: #ecf8fd;
        --v-chip-border: #d2eefa;
        --v-chip-text: var(--v-brand-600);
        --v-accent-text: var(--v-brand-600);
        --v-link: var(--v-brand-600);
        --v-link-hover: var(--v-brand-700);
        --v-well-bg: #ecf8fd;
        --v-well-border: #d2eefa;
        --v-well-icon: var(--v-brand-600);
        --v-hover-border: var(--v-brand-200);
        --v-prod-shadow: 0 18px 44px rgba(14, 66, 88, 0.09);
        --v-why-shadow: 0 14px 40px rgba(14, 66, 88, 0.08);
        --v-ico-tile-bg: #ffffff;
    }
}

.v-landing h1,
.v-landing h2,
.v-landing h3,
.v-landing h4 {
    font-family: 'Bricolage Grotesque', sans-serif;
    color: var(--mkt-text-h);
    letter-spacing: -0.02em;
    margin: 0;
}

.v-wrap {
    max-width: 1120px;
    margin: 0 auto;
    padding: 0 24px;
}

/* ── Sections ── */
.v-sec {
    padding: 110px 0;
    background: var(--mkt-bg);
}
.v-sec-alt {
    background: var(--mkt-bg-2);
    border-top: 1px solid var(--mkt-line);
    border-bottom: 1px solid var(--mkt-line);
}
.v-sec-head {
    text-align: center;
    max-width: 620px;
    margin: 0 auto 56px;
}
.v-sec-title {
    font-size: clamp(30px, 3.4vw, 42px);
    font-weight: 700;
    line-height: 1.14;
    margin-top: 18px;
}
.v-sec-title em {
    font-style: normal;
    color: var(--v-accent-text);
}
.v-sec-lead {
    color: var(--mkt-text-m);
    font-size: 15.5px;
    margin-top: 16px;
}
.v-chip {
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.11em;
    text-transform: uppercase;
    color: var(--v-chip-text);
    background: var(--v-chip-bg);
    border: 1px solid var(--v-chip-border);
    border-radius: 999px;
    padding: 5px 13px;
}

/* ── Buttons ── */
.v-landing .v-btn-wa {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: var(--v-brand);
    color: #fff;
    font-weight: 700;
    font-size: 15px;
    padding: 14px 26px;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(15, 158, 213, 0.35);
    text-decoration: none;
    transition: background 0.2s;
}
.v-landing .v-btn-wa:hover {
    background: var(--v-brand-600);
    color: #fff;
}
.v-landing .v-btn-wa svg {
    width: 17px;
    height: 17px;
    flex: none;
}
.v-btn-ghost {
    display: inline-flex;
    align-items: center;
    padding: 14px 26px;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 700;
    color: #fff;
    border: 1.5px solid rgba(255, 255, 255, 0.28);
    background: rgba(255, 255, 255, 0.06);
    text-decoration: none;
    transition: border-color 0.2s;
}
.v-btn-ghost:hover {
    border-color: rgba(255, 255, 255, 0.6);
    color: #fff;
}
.v-btn-outline {
    display: inline-flex;
    align-items: center;
    padding: 13px 26px;
    border-radius: 12px;
    font-size: 14.5px;
    font-weight: 700;
    color: var(--mkt-text-h);
    border: 1.5px solid var(--mkt-line-s);
    background: var(--mkt-surface);
    text-decoration: none;
    transition: border-color 0.2s;
}
.v-btn-outline:hover {
    border-color: var(--v-brand);
    color: var(--mkt-text-h);
}

/* ── Hero ── */
.v-hero {
    position: relative;
    overflow: hidden;
    background: var(--v-deepest);
    min-height: min(760px, 100vh);
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 150px 0 0;
}
.v-hero-photo {
    position: absolute;
    inset: 0;
}
.v-hero-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: grayscale(0.35) contrast(1.08) brightness(0.92);
    transform: scale(1.06);
    opacity: 0.9;
}
.v-hero-photo::after {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 90% 70% at 50% 20%, rgba(15, 158, 213, 0.26), transparent 62%),
        linear-gradient(180deg, rgba(8, 44, 60, 0.62) 0%, rgba(8, 44, 60, 0.52) 45%, rgba(8, 44, 60, 0.82) 100%);
}
.v-hero-grid {
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle at 1px 1px, rgba(255, 255, 255, 0.07) 1px, transparent 0);
    background-size: 34px 34px;
}
.v-hero-in {
    position: relative;
    text-align: center;
    max-width: 880px;
    margin: 0 auto;
    padding: 0 24px;
}
.v-pill {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    border: 1px solid rgba(166, 221, 243, 0.38);
    background: rgba(15, 158, 213, 0.14);
    color: var(--v-brand-200);
    border-radius: 999px;
    padding: 6px 15px;
    font-size: 12.5px;
    font-weight: 600;
    letter-spacing: 0.02em;
    backdrop-filter: blur(4px);
}
.v-pill i {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: var(--v-brand);
    display: block;
    flex: none;
}
.v-hero .v-hero-title {
    color: #fff;
    font-size: clamp(42px, 6vw, 72px);
    font-weight: 800;
    line-height: 1.03;
    margin: 26px 0 0;
    /* The hero photo sits at near-full opacity behind this, so the white needs
       its own scrim to read as white rather than washing into the image. */
    text-shadow: 0 2px 20px rgba(8, 44, 60, 0.8), 0 1px 4px rgba(8, 44, 60, 0.65);
}
.v-hero .v-accent {
    color: var(--v-brand);
}
.v-hero-body {
    color: rgba(255, 255, 255, 0.88);
    font-size: 17px;
    line-height: 1.68;
    max-width: 640px;
    margin: 22px auto 0;
    text-shadow: 0 1px 12px rgba(8, 44, 60, 0.75);
}
.v-hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    justify-content: center;
    margin-top: 34px;
}

/* ── Trust strip ── */
.v-trust {
    position: relative;
    margin-top: 72px;
    border-top: 1px solid rgba(255, 255, 255, 0.12);
    background: rgba(8, 44, 60, 0.4);
    backdrop-filter: blur(6px);
}
.v-trust-in {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
    max-width: 1120px;
    margin: 0 auto;
    padding: 26px 24px;
}
.v-trust-item {
    text-align: center;
}
.v-trust-l {
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 0.09em;
    text-transform: uppercase;
    color: rgba(166, 221, 243, 0.75);
}
.v-trust-v {
    margin-top: 6px;
    font-size: 14.5px;
    font-weight: 700;
    color: #fff;
}
@media (max-width: 700px) {
    .v-trust-in {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px 8px;
    }
}

/* ── Duotone photo treatment ── */
.v-duo {
    position: relative;
    overflow: hidden;
    background: var(--v-deepest);
}
.v-duo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: grayscale(0.3) contrast(1.05) brightness(1);
    opacity: 1;
}
.v-duo::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(165deg, rgba(15, 158, 213, 0.22), rgba(8, 44, 60, 0.4));
    mix-blend-mode: multiply;
}

/* ── Product cards ── */
.v-prod-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 22px;
}
@media (max-width: 760px) {
    .v-prod-grid {
        grid-template-columns: 1fr;
    }
}
.v-prod {
    position: relative;
    background: var(--mkt-surface);
    border: 1px solid var(--mkt-line);
    border-radius: 16px;
    transition: border-color 0.25s, transform 0.25s, box-shadow 0.25s;
}
.v-prod:hover {
    border-color: var(--v-hover-border);
    transform: translateY(-3px);
    box-shadow: var(--v-prod-shadow);
}
.v-prod-img {
    height: 132px;
    position: relative;
    border-radius: 15px 15px 0 0;
    overflow: visible;
}
.v-prod-img img,
.v-prod-img::after {
    border-radius: 15px 15px 0 0;
}
.v-prod-ico {
    position: absolute;
    left: 20px;
    bottom: -22px;
    z-index: 2;
    width: 46px;
    height: 46px;
    border-radius: 12px;
    background: var(--v-ico-tile-bg);
    border: 1px solid var(--mkt-line);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--v-well-icon);
    box-shadow: 0 6px 18px rgba(14, 66, 88, 0.12);
}
.v-prod-body {
    padding: 38px 24px 26px;
}
.v-prod h3 {
    font-size: 17px;
    font-weight: 700;
    line-height: 1.3;
}
.v-prod p {
    color: var(--mkt-text-m);
    font-size: 14.5px;
    line-height: 1.65;
    margin: 12px 0 20px;
}
.v-more {
    font-size: 13px;
    font-weight: 700;
    color: var(--v-link);
    text-decoration: none;
    transition: color 0.2s;
}
.v-more:hover {
    color: var(--v-link-hover);
}
.v-center-cta {
    text-align: center;
    margin-top: 42px;
}

/* ── Offline-first band ── */
.v-band {
    position: relative;
    overflow: hidden;
    background: var(--v-deepest);
    color: #fff;
    padding: 104px 0;
}
.v-band-photo {
    position: absolute;
    inset: 0;
}
.v-band-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: grayscale(0.4) brightness(0.9);
    opacity: 0.8;
}
.v-band-photo::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(105deg, rgba(8, 44, 60, 0.88) 0%, rgba(8, 44, 60, 0.7) 52%, rgba(15, 158, 213, 0.3) 100%);
}
.v-band-in {
    position: relative;
    display: grid;
    grid-template-columns: 56px 1fr;
    gap: 34px;
    max-width: 1120px;
    margin: 0 auto;
    padding: 0 24px;
}
@media (max-width: 700px) {
    .v-band-in {
        grid-template-columns: 1fr;
        gap: 22px;
    }
}
.v-band-ico {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    background: rgba(15, 158, 213, 0.18);
    border: 1px solid rgba(166, 221, 243, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--v-brand);
    margin-top: 4px;
}
.v-band .v-chip {
    background: rgba(15, 158, 213, 0.16);
    border-color: rgba(166, 221, 243, 0.32);
    color: var(--v-brand-200);
}
.v-band h2 {
    color: #fff;
}
.v-band .v-sec-title {
    color: #fff;
}
.v-band .v-sec-title em {
    color: var(--v-brand);
}
.v-band-lead {
    color: rgba(255, 255, 255, 0.74);
    font-size: 15.5px;
    max-width: 640px;
    margin: 18px 0 34px;
}
.v-feat-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}
@media (max-width: 760px) {
    .v-feat-grid {
        grid-template-columns: 1fr;
    }
}
.v-feat {
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.13);
    border-radius: 14px;
    padding: 20px;
}
.v-feat h4 {
    color: var(--v-brand-200);
    font-family: inherit;
    font-size: 11.5px;
    font-weight: 800;
    letter-spacing: 0.09em;
    text-transform: uppercase;
}
.v-feat p {
    color: rgba(255, 255, 255, 0.7);
    font-size: 13.5px;
    line-height: 1.62;
    margin: 9px 0 0;
}

/* ── Why Varidian ── */
.v-why-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 16px;
}
.v-why--l {
    grid-column: span 3;
}
.v-why--s {
    grid-column: span 2;
}
@media (max-width: 1023px) {
    .v-why-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .v-why--l,
    .v-why--s {
        grid-column: span 1;
    }
}
@media (max-width: 640px) {
    .v-why-grid {
        grid-template-columns: 1fr;
    }
}
.v-why {
    background: var(--mkt-surface);
    border: 1px solid var(--mkt-line);
    border-radius: 18px;
    padding: 28px;
    transition: border-color 0.25s, box-shadow 0.25s;
}
.v-why:hover {
    border-color: var(--v-hover-border);
    box-shadow: var(--v-why-shadow);
}
.v-why-ico {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    background: var(--v-well-bg);
    border: 1px solid var(--v-well-border);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--v-well-icon);
    margin-bottom: 20px;
}
.v-why h4 {
    font-size: 16.5px;
    font-weight: 700;
    line-height: 1.35;
}
.v-why p {
    color: var(--mkt-text-m);
    font-size: 14px;
    line-height: 1.65;
    margin: 10px 0 0;
}
.v-why--photo {
    padding: 0;
    overflow: hidden;
    position: relative;
    min-height: 230px;
    display: flex;
    align-items: flex-end;
    border-color: transparent;
}
.v-why--photo .v-duo {
    position: absolute;
    inset: 0;
}
.v-why--photo .v-duo::before {
    content: '';
    position: absolute;
    inset: 0;
    z-index: 1;
    background: linear-gradient(180deg, transparent 30%, rgba(8, 44, 60, 0.82) 100%);
}
.v-why-txt {
    position: relative;
    padding: 26px;
    color: #fff;
}
.v-why--photo h4 {
    color: #fff;
}
.v-why--photo p {
    color: rgba(255, 255, 255, 0.75);
}

/* ── FAQ ── */
.v-faq {
    max-width: 760px;
    margin: 0 auto;
}
.v-faq-row {
    border-bottom: 1px solid var(--mkt-line);
    padding: 20px 8px;
    cursor: pointer;
    transition: background 0.2s;
}
.v-faq-row:hover {
    background: var(--mkt-surface);
}
.v-faq-q {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}
.v-faq-q span {
    font-size: 15.5px;
    font-weight: 600;
    color: var(--mkt-text-h);
}
.v-faq-mark {
    width: 28px;
    height: 28px;
    flex: none;
    border-radius: 50%;
    border: 1px solid var(--mkt-line-s);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--mkt-text-m);
    transition: 0.25s;
}
.v-faq-mark svg {
    width: 13px;
    height: 13px;
}
.v-faq-row.is-open .v-faq-mark {
    background: var(--v-well-bg);
    border-color: var(--v-well-border);
    color: var(--v-well-icon);
    transform: rotate(180deg);
}
.v-faq-a {
    color: var(--mkt-text-m);
    font-size: 14.5px;
    line-height: 1.7;
    padding: 12px 44px 4px 0;
}

/* ── Bottom CTA ── */
.v-cta {
    position: relative;
    overflow: hidden;
    border-radius: 24px;
    background: linear-gradient(150deg, var(--v-deep), var(--v-deepest) 60%);
    padding: 74px 32px;
    text-align: center;
    color: #fff;
}
.v-cta::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse 70% 90% at 80% 0%, rgba(15, 158, 213, 0.42), transparent 60%);
}
.v-cta > * {
    position: relative;
}
.v-cta h2 {
    color: #fff;
    margin-top: 20px;
}
.v-cta .v-accent {
    color: var(--v-brand);
}
.v-cta-lead {
    color: rgba(255, 255, 255, 0.72);
    font-size: 15.5px;
    max-width: 520px;
    margin: 18px auto 32px;
}
.v-cta .v-chip {
    background: rgba(15, 158, 213, 0.16);
    border-color: rgba(166, 221, 243, 0.32);
    color: var(--v-brand-200);
}
.v-cta-row {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    justify-content: center;
}
.v-cta .v-btn-ghost {
    border-color: rgba(255, 255, 255, 0.3);
}

/* ── Scroll reveal ── */
.v-landing [data-reveal] {
    opacity: 0;
    transform: translateY(18px);
    transition: opacity 0.7s ease, transform 0.7s ease;
}
.v-landing [data-reveal].in {
    opacity: 1;
    transform: none;
}
@media (prefers-reduced-motion: reduce) {
    .v-landing [data-reveal] {
        opacity: 1;
        transform: none;
        transition: none;
    }
    .v-prod:hover {
        transform: none;
    }
}
</style>

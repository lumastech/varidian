<script setup lang="ts">
import { ref, onMounted } from 'vue';
import SeoHead from '@/components/SeoHead.vue';
import WhatsAppButton from '@/components/WhatsAppButton.vue';

const activeFaq = ref<number | null>(0);

const trustBar = [
    { label: 'Home Base', value: 'Lusaka, Zambia' },
    { label: 'Platforms', value: 'Web · Mobile · Desktop' },
    { label: 'Integrations', value: 'Airtel · MTN · Zamtel' },
    { label: 'Reach', value: 'Deployed across Africa' },
];

const products = [
    {
        title: 'School Management System (ZSSMS)',
        badge: null,
        desc: 'Grades 1–12 on the MoE three-term calendar. ECZ exam tracking, Airtel/MTN fee collection, NAPSA payroll. Offline-first — continues working through connectivity outages.',
        href: '/products/school-management-system',
        icon: `<svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422A12.083 12.083 0 0112 21.07a12.083 12.083 0 01-6.16-10.492L12 14z"/></svg>`,
    },
    {
        title: 'Church Management System',
        badge: null,
        desc: 'Member and cell group registry. Tithe and offering tracking with mobile money. Attendance, events, and SMS or WhatsApp notifications. Purpose-built for African churches and ministries.',
        href: '/products/church-management-system',
        icon: `<svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>`,
    },
    {
        title: 'Varidian BizManager',
        badge: 'ZRA Smart Invoice compliant',
        desc: 'Complete business manager for SMEs. Invoicing, inventory, sales, expenses — fully integrated with ZRA Smart Invoice. Offline queue ensures no lost transactions during outages.',
        href: '/products/bizmanager',
        icon: `<svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>`,
    },
    {
        title: 'Village Banking & Microfinance',
        badge: null,
        desc: 'Group and member management for VSLAs and NGO microfinance programmes. Loan and savings cycle tracking, mobile money disbursements. Field agent portal works offline.',
        href: '/products/village-banking',
        icon: `<svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>`,
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

const whyCards = [
    {
        icon: `<svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20"/></svg>`,
        heading: 'Built from Zambia, deployed across Africa',
        body: 'Our home is Lusaka. Our systems are built for the African context — local compliance, local payment rails, local operating conditions. Deployed across Zambia, Zimbabwe, Malawi, and beyond.',
    },
    {
        icon: `<svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><line x1="1" y1="1" x2="23" y2="23"/><path d="M16.72 11.06A10.94 10.94 0 0119 12.55M5 12.55a10.94 10.94 0 015.17-2.39M10.71 5.05A16 16 0 0122.56 9M1.42 9a15.91 15.91 0 014.7-2.88M8.53 16.11a6 6 0 006.95 0M12 20h.01"/></svg>`,
        heading: 'Offline-first by design',
        body: 'Every product is built for intermittent connectivity. Data is stored locally, queued, and synced automatically. Your operations never stop because the internet did.',
    },
    {
        icon: `<svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>`,
        heading: 'Web, mobile & desktop',
        body: 'All products run across web browsers, Android, iOS, and Windows desktop. Your team uses the same system from any device, wherever they are.',
    },
    {
        icon: `<svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>`,
        heading: 'Local support you can reach',
        body: 'Call or WhatsApp a real person in Lusaka. No helpdesk tickets. No international time zones. We are here when something needs fixing.',
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
        description="We build web, mobile and desktop management systems for African schools, churches, NGOs, and businesses. Offline-first, ZRA Smart Invoice compliant, locally supported in Lusaka."
        canonical-url="https://varidianlab.com"
    />

    <!-- ══════════════ HERO ══════════════ -->
    <section class="hero-bg relative overflow-hidden" style="border-bottom: 1px solid var(--mkt-line)">
        <div class="hero-dot-grid absolute inset-0 pointer-events-none"></div>
        <div class="hero-glow absolute pointer-events-none"></div>

        <div class="relative mx-auto max-w-6xl items-center gap-14 px-6 pt-28 pb-14 lg:grid lg:grid-cols-[1.05fr_0.95fr]">
            <!-- Left: copy -->
            <div>
                <div class="hero-pill mb-6 inline-flex items-center gap-2">
                    <span class="hero-pill-dot"></span>
                    Built from Lusaka · Deployed across Africa
                </div>
                <h1 class="hero-title">
                    Software that<br />
                    understands <span class="hero-accent">Africa.</span>
                </h1>
                <p class="hero-body mt-5">
                    We design and build web, mobile and desktop management systems for African institutions — purpose-built for the local context. ECZ exams, ZRA tax compliance, Airtel, MTN and Zamtel mobile money, NAPSA payroll, and offline-first where connectivity is limited.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a
                        href="https://wa.me/260971864421?text=Hi%2C%20I%27d%20like%20to%20request%20a%20demo%20of%20a%20Varidian%20product."
                        target="_blank"
                        rel="noopener noreferrer"
                        class="mkt-btn-wa hero-cta-btn"
                    >
                        <svg class="h-4 w-4 flex-none" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                        </svg>
                        Request a demo
                    </a>
                    <a href="/products" class="hero-btn-outline">See our products</a>
                </div>
            </div>

            <!-- Right: dashboard card (always dark) -->
            <div class="mt-12 lg:mt-0">
                <div class="hero-card">
                    <div class="hero-card-header">
                        <span class="hero-card-brand">VARIDIAN</span>
                        <span class="hero-live-badge">
                            <span class="hero-live-dot"></span>Live
                        </span>
                    </div>
                    <div class="hero-card-body">
                        <div class="mb-3 grid grid-cols-2 gap-3">
                            <div class="hero-stat">
                                <svg class="mb-2 h-4 w-4 flex-none" fill="none" stroke="#00c9a7" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M12 14l9-5-9-5-9 5 9 5z" /><path d="M12 14l6.16-3.422A12.083 12.083 0 0112 21.07a12.083 12.083 0 01-6.16-10.492L12 14z" />
                                </svg>
                                <div class="hero-stat-num">412</div>
                                <div class="hero-stat-lbl">Pupils enrolled</div>
                            </div>
                            <div class="hero-stat">
                                <svg class="mb-2 h-4 w-4 flex-none" fill="none" stroke="#00a3ff" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8zM14 2v6h6M9 13h6M9 17h4" />
                                </svg>
                                <div class="hero-stat-num">98%</div>
                                <div class="hero-stat-lbl">Collected · Term 2</div>
                            </div>
                        </div>
                        <div class="hero-chart">
                            <div class="hero-chart-lbl">FEE COLLECTION · TERM 2</div>
                            <div class="hero-bars">
                                <div
                                    v-for="(h, i) in [55, 70, 48, 82, 78, 96]"
                                    :key="i"
                                    class="hero-bar"
                                    :class="{ 'hero-bar--accent': i === 5 }"
                                    :style="{ height: h + '%' }"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trust strip -->
        <div class="relative mx-auto max-w-6xl px-6 pb-12 flex flex-wrap justify-center gap-10">
            <div v-for="t in trustBar" :key="t.label" class="text-center">
                <div class="trust-label">{{ t.label }}</div>
                <div class="trust-value">{{ t.value }}</div>
            </div>
        </div>
    </section>

    <!-- ══════════════ PRODUCTS ══════════════ -->
    <section class="mkt-sec-dark px-6 py-24">
        <div class="mx-auto max-w-6xl">
            <div class="mb-14 text-center" data-reveal>
                <div class="mkt-chip mb-4">Products</div>
                <h2 class="mkt-sec-title">Management systems<br /><em>built for African institutions</em></h2>
                <p class="mx-auto mt-4 max-w-md text-sm leading-relaxed" style="color:var(--mkt-text-m)">Each product is purpose-built for a specific sector — not adapted from a generic template. Offline-first, mobile-ready, and locally supported.</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2" data-reveal>
                <div v-for="product in products" :key="product.title" class="product-card">
                    <div class="mb-3 flex items-start gap-3">
                        <div class="mkt-service-icon flex-shrink-0" v-html="product.icon"></div>
                        <div>
                            <div v-if="product.badge" class="mb-1">
                                <span class="zra-badge">✓ {{ product.badge }}</span>
                            </div>
                            <h3 class="text-base font-semibold" style="color:var(--mkt-text-h)">{{ product.title }}</h3>
                        </div>
                    </div>
                    <p class="mb-5 text-sm leading-relaxed" style="color:var(--mkt-text-m)">{{ product.desc }}</p>
                    <a :href="product.href" class="text-xs font-semibold text-teal-400 transition-colors hover:text-teal-300">Learn more →</a>
                </div>
            </div>

            <div class="mt-10 text-center" data-reveal>
                <a href="/products" class="mkt-btn-outline">View all products</a>
            </div>
        </div>
    </section>

    <!-- ══════════════ OFFLINE-FIRST ══════════════ -->
    <section class="mkt-sec-alt px-6 py-24">
        <div class="mx-auto max-w-6xl">
            <div class="offline-wrap" data-reveal>
                <div class="offline-icon-box">
                    <svg class="h-7 w-7" fill="none" stroke="#00c9a7" stroke-width="1.5" viewBox="0 0 24 24">
                        <line x1="1" y1="1" x2="23" y2="23" />
                        <path d="M16.72 11.06A10.94 10.94 0 0119 12.55M5 12.55a10.94 10.94 0 015.17-2.39M10.71 5.05A16 16 0 0122.56 9M1.42 9a15.91 15.91 0 014.7-2.88M8.53 16.11a6 6 0 006.95 0M12 20h.01" />
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="mkt-chip mb-4">Offline-first</div>
                    <h2 class="mkt-sec-title mb-4">Built for the reality<br /><em>of African connectivity.</em></h2>
                    <p class="mb-8 max-w-xl text-sm leading-relaxed" style="color:var(--mkt-text-m)">
                        Every Varidian product is designed to keep working when your internet goes down. Data is stored locally on the device. Transactions are queued. The moment connectivity returns, everything syncs automatically — no lost data, no manual re-entry.
                    </p>
                    <div class="grid gap-4 sm:grid-cols-3">
                        <div v-for="item in offlineFeatures" :key="item.title" class="offline-feat">
                            <div class="mb-2 text-xs font-bold uppercase tracking-wider text-teal-400">{{ item.title }}</div>
                            <p class="text-xs leading-relaxed" style="color:var(--mkt-text-m)">{{ item.body }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══════════════ WHY VARIDIAN ══════════════ -->
    <section class="mkt-sec-dark px-6 py-24">
        <div class="mx-auto max-w-6xl">
            <div class="mb-14 text-center" data-reveal>
                <div class="mkt-chip mb-4">Why Varidian</div>
                <h2 class="mkt-sec-title">What sets Varidian<br /><em>apart</em></h2>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div v-for="w in whyCards" :key="w.heading" class="why-card p-6" data-reveal>
                    <div class="mkt-service-icon mb-4" v-html="w.icon"></div>
                    <h4 class="mb-2 text-sm font-semibold" style="color:var(--mkt-text-h)">{{ w.heading }}</h4>
                    <p class="text-xs leading-relaxed" style="color:var(--mkt-text-m)">{{ w.body }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ══════════════ FAQ ══════════════ -->
    <section class="mkt-sec-alt px-6 py-24">
        <div class="mx-auto max-w-3xl">
            <div class="mb-14 text-center" data-reveal>
                <div class="mkt-chip mb-4">FAQ</div>
                <h2 class="mkt-sec-title">Common <em>questions</em></h2>
            </div>
            <div data-reveal>
                <div
                    v-for="(faq, i) in faqs"
                    :key="i"
                    class="faq-row cursor-pointer rounded-lg px-2 py-5"
                    @click="activeFaq = activeFaq === i ? null : i"
                >
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-sm font-medium" style="color:var(--mkt-text-h)">{{ faq.q }}</span>
                        <div
                            class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full border transition-all"
                            :class="activeFaq === i ? 'bg-teal-500/20 border-teal-500/40' : ''"
                            :style="activeFaq !== i ? { borderColor: 'var(--mkt-line-s)' } : {}"
                        >
                            <svg
                                class="h-3.5 w-3.5 transition-transform"
                                :class="activeFaq === i ? 'rotate-180 text-teal-400' : ''"
                                :style="activeFaq !== i ? { color: 'var(--mkt-text-m)' } : {}"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2.5"
                                viewBox="0 0 24 24"
                            >
                                <path d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    <div v-if="activeFaq === i" class="mt-3 pr-10 text-sm leading-relaxed" style="color:var(--mkt-text-m)">{{ faq.a }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══════════════ BOTTOM CTA ══════════════ -->
    <section class="mkt-sec-dark px-6 py-24">
        <div class="mx-auto max-w-4xl">
            <div class="mkt-cta-bottom px-8 py-16 text-center" data-reveal>
                <div class="mkt-chip mb-6">Let's get started</div>
                <h2 class="mkt-sec-title mb-4">
                    Ready to see a product<br />
                    <span style="background:linear-gradient(90deg,#00c9a7,#00a3ff);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">in action?</span>
                </h2>
                <p class="mx-auto mb-8 max-w-md text-sm leading-relaxed" style="color:var(--mkt-text-m)">Request a demo and we'll walk you through the product most relevant to your organisation. No obligation — just a practical conversation.</p>
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <WhatsAppButton
                        variant="inline"
                        label="Request a demo"
                        message="Hi, I'd like to request a demo of a Varidian product."
                        class="mkt-btn-wa-lg"
                    />
                    <a href="/products" class="mkt-btn-outline">See our products</a>
                </div>
            </div>
        </div>
    </section>
</template>

<style>
/* ── Hero ── */
.hero-bg {
    background: radial-gradient(ellipse 80% 60% at 70% -10%, color-mix(in srgb, #00c9a7 14%, transparent), transparent 60%),
        var(--mkt-bg);
}
.hero-dot-grid {
    background-image: radial-gradient(circle at 1px 1px, var(--mkt-dot-grid) 1px, transparent 0);
    background-size: 34px 34px;
}
.hero-glow {
    top: -180px;
    right: -120px;
    width: 520px;
    height: 520px;
    border-radius: 50%;
    background: radial-gradient(circle, color-mix(in srgb, #00c9a7 18%, transparent), transparent 66%);
}
.hero-pill {
    border: 1px solid rgba(0, 201, 167, 0.35);
    background: rgba(0, 201, 167, 0.08);
    border-radius: 999px;
    padding: 5px 14px;
    font-size: 12px;
    font-weight: 600;
    color: #00c9a7;
    letter-spacing: 0.02em;
}
.hero-pill-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #00c9a7;
    display: inline-block;
    flex-shrink: 0;
}
.hero-title {
    font-family: 'Bricolage Grotesque', sans-serif;
    font-size: clamp(40px, 5.5vw, 62px);
    font-weight: 800;
    line-height: 1.06;
    letter-spacing: -0.025em;
    color: var(--mkt-text-h);
    margin: 20px 0 0;
}
.hero-accent {
    background: linear-gradient(90deg, #00c9a7, #00a3ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.hero-body {
    font-size: 16px;
    line-height: 1.65;
    color: var(--mkt-text-m);
    max-width: 520px;
}
.hero-cta-btn {
    padding: 13px 24px;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 700;
}
.hero-btn-outline {
    display: inline-flex;
    align-items: center;
    padding: 13px 24px;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 700;
    border: 1.5px solid var(--mkt-line-s);
    color: var(--mkt-text);
    background: var(--mkt-surface);
    text-decoration: none;
    transition: border-color 0.2s, background 0.2s;
}
.hero-btn-outline:hover {
    border-color: #00c9a7;
}

/* ── Hero card (always dark) ── */
.hero-card {
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 24px 64px rgba(0, 0, 0, 0.35);
}
.hero-card-header {
    background: #0b1217;
    padding: 13px 18px;
    display: flex;
    align-items: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.07);
}
.hero-card-brand {
    font-family: 'Bricolage Grotesque', sans-serif;
    font-size: 13px;
    font-weight: 700;
    color: #fff;
    letter-spacing: 0.04em;
}
.hero-live-badge {
    margin-left: auto;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 700;
    color: #5fe3d3;
    background: rgba(0, 201, 167, 0.14);
    padding: 3px 10px;
    border-radius: 999px;
}
.hero-live-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #00c9a7;
    flex-shrink: 0;
}
.hero-card-body {
    padding: 16px;
    background: #f0f4f8;
}
.hero-stat {
    background: #fff;
    border-radius: 12px;
    padding: 14px;
    display: flex;
    flex-direction: column;
}
.hero-stat-num {
    font-family: 'Bricolage Grotesque', sans-serif;
    font-size: 26px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1;
    margin-bottom: 4px;
}
.hero-stat-lbl {
    font-size: 11.5px;
    color: #64748b;
}
.hero-chart {
    background: #fff;
    border-radius: 12px;
    padding: 14px;
}
.hero-chart-lbl {
    font-size: 10.5px;
    font-weight: 700;
    color: #94a3b8;
    letter-spacing: 0.05em;
    margin-bottom: 12px;
    text-transform: uppercase;
}
.hero-bars {
    display: flex;
    align-items: flex-end;
    gap: 8px;
    height: 62px;
}
.hero-bar {
    flex: 1;
    border-radius: 4px 4px 0 0;
    background: #bfdbfe;
    transition: height 0.4s ease;
}
.hero-bar--accent {
    background: #00c9a7;
}

/* ── Trust strip ── */
.trust-label {
    font-size: 10.5px;
    color: var(--mkt-text-m);
    font-weight: 600;
    letter-spacing: 0.06em;
    text-transform: uppercase;
}
.trust-value {
    margin-top: 4px;
    font-size: 13.5px;
    font-weight: 700;
    color: var(--mkt-text-h);
}

/* ── Product cards ── */
.product-card {
    background: var(--mkt-surface);
    border: 1px solid var(--mkt-line);
    border-radius: 14px;
    padding: 28px 24px;
    transition: border-color 0.25s, transform 0.25s;
}
.product-card:hover {
    border-color: rgba(0, 201, 167, 0.3);
    transform: translateY(-3px);
}

/* ── Offline-first section ── */
.offline-wrap {
    display: flex;
    gap: 40px;
    align-items: flex-start;
}
.offline-icon-box {
    width: 56px;
    height: 56px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    background: rgba(0, 201, 167, 0.1);
    border: 1px solid rgba(0, 201, 167, 0.2);
    margin-top: 4px;
}
.offline-feat {
    background: var(--mkt-surface);
    border: 1px solid var(--mkt-line);
    border-radius: 12px;
    padding: 18px;
}

/* ── Why cards ── */
.why-card {
    background: var(--mkt-surface);
    border: 1px solid var(--mkt-line);
    border-radius: 14px;
    overflow: hidden;
    transition: border-color 0.25s;
}
.why-card:hover {
    border-color: rgba(0, 163, 255, 0.3);
}

/* ── FAQ ── */
.faq-row {
    border-bottom: 1px solid var(--mkt-line);
    transition: background 0.2s;
}
.faq-row:hover {
    background: var(--mkt-surface);
}

/* ── ZRA badge ── */
.zra-badge {
    display: inline-block;
    background: rgba(0, 201, 167, 0.12);
    border: 1px solid rgba(0, 201, 167, 0.3);
    color: #00c9a7;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.05em;
    border-radius: 999px;
    padding: 2px 8px;
}
</style>

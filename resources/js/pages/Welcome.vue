<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import SeoHead from '@/components/SeoHead.vue';
import WhatsAppButton from '@/components/WhatsAppButton.vue';

interface Service {
    title: string;
    body: string;
    icon: string;
}

interface Product {
    sector: string;
    name: string;
    desc: string;
    href: string;
}

interface HostingPlan {
    name: string;
    price: string | null;
    spec: string;
    featured: boolean;
}

interface CaseStudy {
    tag: string;
    title: string;
    body: string;
    preview: string;
}

const integrations = [
    { title: 'Airtel Money', body: 'Collections & payouts' },
    { title: 'MTN MoMo', body: 'Collections & payouts' },
    { title: 'ZRA Smart Invoice', body: 'Tax-compliant invoicing' },
    { title: 'Bulk SMS', body: 'Alerts & reminders' },
    { title: 'NAPSA & PAYE', body: 'Payroll deductions' },
    { title: 'Local hosting', body: 'Data stays in Zambia' },
];

const audiences = ['Private & mission schools', 'NGOs', 'Churches', 'Microfinance & village banking', 'SMEs', 'Universities & colleges'];

const services: Service[] = [
    {
        title: 'Custom software',
        body: 'Web platforms, portals and internal systems designed around your processes — not the other way round.',
        icon: `<svg fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M8 7l-5 5 5 5"/><path d="M16 7l5 5-5 5"/><path d="M14 4l-4 16"/></svg>`,
    },
    {
        title: 'AI & automation',
        body: 'Practical AI for businesses — document processing, assistants and workflow automation, with local inference options for sensitive data.',
        icon: `<svg fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="5" y="5" width="14" height="14" rx="2"/><path d="M9 1v4M15 1v4M9 19v4M15 19v4M1 9h4M1 15h4M19 9h4M19 15h4"/></svg>`,
    },
    {
        title: 'Zambian web hosting',
        body: 'Websites and applications hosted on servers in Zambia, supporting your obligations under the Data Protection Act, 2021.',
        icon: `<svg fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="7" rx="1.5"/><rect x="3" y="13" width="18" height="7" rx="1.5"/><path d="M7 7.5h.01M7 16.5h.01"/></svg>`,
    },
    {
        title: 'Consulting & support',
        body: 'Digital strategy, system audits and ongoing IT support, with service levels that match how critical your system is.',
        icon: `<svg fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>`,
    },
];

const products: Product[] = [
    {
        sector: 'Education',
        name: 'SKUU',
        desc: 'School & student management for Grades 1–12 — enrolment, fees via mobile money, ECZ exam tracking, payroll and parent SMS.',
        href: '/products/school-management-system',
    },
    {
        sector: 'Non-profit',
        name: 'Varidian Reach',
        desc: 'NGO management — beneficiaries, programmes, donors and reporting in one dedicated installation per organisation.',
        href: '#contact',
    },
    {
        sector: 'Business',
        name: 'BizManager',
        desc: 'SME business management with ZRA Smart Invoice integration — sales, stock, customers and compliant invoices.',
        href: '/products/bizmanager',
    },
    {
        sector: 'Accounting',
        name: 'Varidian Books',
        desc: 'Offline-first accounting that keeps working when the connection drops, and syncs when it returns.',
        href: '#contact',
    },
    {
        sector: 'Finance',
        name: 'Village Banking',
        desc: 'Microfinance and savings-group platform — members, loans, repayments and mobile money collections.',
        href: '/products/village-banking',
    },
    {
        sector: 'Faith',
        name: 'ChurchMS',
        desc: 'Church management — membership, giving, groups, events and congregation communication.',
        href: '/products/church-management-system',
    },
    {
        sector: 'Higher education',
        name: 'Coursify',
        desc: 'Online learning management for universities and colleges — courses, assessments and student progress.',
        href: '#contact',
    },
    {
        sector: 'Events',
        name: 'Varidian Events',
        desc: 'Event registration and ticketing with online payments — a data-protection-compliant alternative to generic forms.',
        href: '#contact',
    },
];

const reasons = [
    {
        title: 'Built for local systems',
        body: 'Airtel Money, MTN MoMo, ZRA Smart Invoice, NAPSA, PAYE and bulk SMS — integrated, not bolted on.',
    },
    {
        title: 'Your data stays home',
        body: 'Hosted on our own servers in Zambia, so personal data is stored at rest within the country.',
    },
    {
        title: 'Own it or rent it',
        body: 'Choose a monthly cloud subscription, or a one-off licence where you own the system and its customisations.',
    },
    {
        title: 'Support you can reach',
        body: 'Standard support included on hosted plans, with premium support and SLAs available.',
    },
];

/**
 * Set `price` to the monthly ZMW figure once pricing is final; null renders "Pricing on request".
 */
const hostingPlans: HostingPlan[] = [
    { name: 'Starter', price: null, spec: '1 website · SSD storage', featured: false },
    { name: 'Business', price: null, spec: 'Multiple websites · SSD storage', featured: true },
    { name: 'Developer', price: null, spec: 'Unlimited sites · multi-PHP', featured: false },
];

const caseStudies: CaseStudy[] = [
    {
        tag: 'NGO · Southern Province',
        title: "Choma District Women's Development Association",
        body: 'A seven-module management system and public website for chomadwda.org, covering members, programmes and reporting.',
        preview: 'Admin dashboard',
    },
    {
        tag: 'Microfinance',
        title: 'ZMAI village banking platform',
        body: 'A savings and microfinance platform managing members, loans and repayments.',
        preview: 'Loans module',
    },
];

const steps = [
    { title: 'Discover', body: 'We map your processes, users and compliance needs on site.' },
    { title: 'Design', body: 'A clear scope, fixed quotation and screens you approve before we build.' },
    { title: 'Build', body: 'Iterative delivery with regular demos, testing and staff training.' },
    { title: 'Run & support', body: 'Hosting, monitoring, backups and support after go-live.' },
];

const enquiryTopics = ['Custom Development', 'AI & Automation', 'Web Hosting', 'Consulting & Support', ...products.map((p) => p.name), 'General Inquiry'];

const form = useForm({
    name: '',
    organisation: '',
    phone: '',
    email: '',
    product_interest: '',
    message: '',
});

const enquirySent = ref(false);

function sendEnquiry(): void {
    form.post('/contact', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            enquirySent.value = true;
        },
    });
}

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
        title="Varidian Consulting Limited — Software built for the way Zambia works"
        description="Varidian designs, builds and hosts business systems for schools, NGOs, SMEs and institutions — with mobile money, SMS and ZRA compliance built in from day one, and your data kept in Zambia."
        keywords="varidianlab, varidian lab, varidian consulting, software house Lusaka, school management system Zambia, NGO management software, church management system Zambia, ZRA Smart Invoice software, village banking software, web hosting Zambia, data protection act Zambia hosting, Airtel Money MTN MoMo integration, AI automation Zambia, Varidian Consulting Limited"
        canonical-url="https://varidianlab.com"
    />

    <div class="v-landing">
        <!-- ══════════════ HERO ══════════════ -->
        <section id="top" class="v-hero">
            <div class="v-hero-grid"></div>

            <div class="v-hero-in">
                <div class="v-hero-copy">
                    <div class="v-pill">
                        <i></i>
                        Software house · Lusaka, Zambia
                    </div>
                    <h1 class="v-hero-title">Software built for the way <span class="v-accent">Zambia works.</span></h1>
                    <p class="v-hero-body">
                        Varidian designs, builds and hosts business systems for schools, NGOs, SMEs and institutions — with mobile money, SMS and ZRA compliance built in from day one, and your data kept in Zambia.
                    </p>
                    <div class="v-hero-actions">
                        <WhatsAppButton variant="inline" label="Start a project" message="Hi, I'd like to start a project with Varidian." class="v-btn-wa" />
                        <a href="#products" class="v-btn-ghost">Explore our products</a>
                    </div>
                </div>

                <div class="v-int">
                    <div class="v-int-l">Integrated out of the box</div>
                    <div class="v-int-grid">
                        <div v-for="item in integrations" :key="item.title" class="v-int-item">
                            <div class="v-int-t">{{ item.title }}</div>
                            <div class="v-int-b">{{ item.body }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════ BUILT FOR ══════════════ -->
        <section aria-label="Who we serve" class="v-strip">
            <div class="v-wrap v-strip-in">
                <span class="v-strip-l">Built for</span>
                <span v-for="audience in audiences" :key="audience" class="v-strip-v">{{ audience }}</span>
            </div>
        </section>

        <!-- ══════════════ SERVICES ══════════════ -->
        <section id="services" class="v-sec">
            <div class="v-wrap">
                <div class="v-sec-head" data-reveal>
                    <div class="v-chip">What we do</div>
                    <h2 class="v-sec-title">One partner from first idea <em>to running system.</em></h2>
                </div>

                <div class="v-svc-grid" data-reveal>
                    <div v-for="service in services" :key="service.title" class="v-card">
                        <!-- eslint-disable-next-line vue/no-v-html -->
                        <div class="v-card-ico" v-html="service.icon"></div>
                        <h3>{{ service.title }}</h3>
                        <p>{{ service.body }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════ PRODUCTS ══════════════ -->
        <section id="products" class="v-sec v-sec-alt">
            <div class="v-wrap">
                <div class="v-sec-head v-sec-head--split" data-reveal>
                    <div>
                        <div class="v-chip">Ready-made platforms</div>
                        <h2 class="v-sec-title">Proven products, <em>configured for you.</em></h2>
                        <p class="v-sec-lead">Start faster with a Varidian platform — on our cloud for a monthly fee, or licensed outright and installed on your own infrastructure.</p>
                    </div>
                    <WhatsAppButton variant="inline" label="Request a demo" message="Hi, I'd like to request a demo of a Varidian product." class="v-btn-outline" />
                </div>

                <div class="v-prod-grid" data-reveal>
                    <a v-for="product in products" :key="product.name" :href="product.href" class="v-prod">
                        <div class="v-prod-sector">{{ product.sector }}</div>
                        <div class="v-prod-name">{{ product.name }}</div>
                        <p>{{ product.desc }}</p>
                        <span class="v-more">{{ product.href.startsWith('/') ? 'Learn more →' : 'Ask about it →' }}</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- ══════════════ WHY VARIDIAN ══════════════ -->
        <section id="about" class="v-sec">
            <div class="v-wrap v-why" data-reveal>
                <div class="v-why-head">
                    <div class="v-chip">Why Varidian</div>
                    <h2 class="v-sec-title">Local knowledge <em>is the feature.</em></h2>
                    <p class="v-sec-lead">
                        Imported software rarely understands mobile money, ZRA rules or the school calendar. Ours starts there. We're a Lusaka team that answers the phone, visits the site and stays after go-live.
                    </p>
                </div>
                <ol class="v-why-list">
                    <li v-for="(reason, i) in reasons" :key="reason.title" class="v-why-row">
                        <span class="v-why-n">{{ String(i + 1).padStart(2, '0') }}</span>
                        <div>
                            <h4>{{ reason.title }}</h4>
                            <p>{{ reason.body }}</p>
                        </div>
                    </li>
                </ol>
            </div>
        </section>

        <!-- ══════════════ HOSTING ══════════════ -->
        <section id="hosting" class="v-band">
            <div class="v-hero-grid"></div>
            <div class="v-wrap v-band-in" data-reveal>
                <div>
                    <div class="v-chip">Varidian Hosting</div>
                    <h2 class="v-sec-title">Hosting in Zambia, <em>priced for Zambian businesses.</em></h2>
                    <p class="v-band-lead">Shared hosting for SMEs, developers and existing clients — free SSL, daily backups and local support, with your files stored on Zambian soil.</p>
                </div>
                <div class="v-plan-grid">
                    <div v-for="plan in hostingPlans" :key="plan.name" class="v-plan" :class="{ 'v-plan--featured': plan.featured }">
                        <div class="v-plan-name">{{ plan.name }}</div>
                        <div v-if="plan.price" class="v-plan-price">ZMW {{ plan.price }}<span>/mo</span></div>
                        <WhatsAppButton
                            v-else
                            variant="inline"
                            label="Pricing on request"
                            :message="`Hi, I'd like pricing for the ${plan.name} hosting plan.`"
                            class="v-plan-ask"
                        />
                        <div class="v-plan-spec">{{ plan.spec }}</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════ SELECTED WORK ══════════════ -->
        <section id="work" class="v-sec">
            <div class="v-wrap">
                <div class="v-sec-head" data-reveal>
                    <div class="v-chip">Selected work</div>
                    <h2 class="v-sec-title">Systems running <em>in the field.</em></h2>
                </div>

                <div class="v-work-grid" data-reveal>
                    <article v-for="study in caseStudies" :key="study.title" class="v-work">
                        <div class="v-work-shot" aria-hidden="true">
                            <span>{{ study.preview }}</span>
                        </div>
                        <div class="v-work-body">
                            <div class="v-prod-sector">{{ study.tag }}</div>
                            <h3>{{ study.title }}</h3>
                            <p>{{ study.body }}</p>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <!-- ══════════════ PROCESS ══════════════ -->
        <section class="v-sec v-sec-alt">
            <div class="v-wrap">
                <div class="v-sec-head" data-reveal>
                    <div class="v-chip">Process</div>
                    <h2 class="v-sec-title">How <em>we work</em></h2>
                </div>
                <div class="v-steps" data-reveal>
                    <div v-for="(step, i) in steps" :key="step.title" class="v-step">
                        <h4>{{ i + 1 }}. {{ step.title }}</h4>
                        <p>{{ step.body }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════ CONTACT ══════════════ -->
        <section id="contact" class="v-sec">
            <div class="v-wrap">
                <div class="v-cta" data-reveal>
                    <div class="v-cta-copy">
                        <div class="v-chip">Let's get started</div>
                        <h2 class="v-sec-title">Have a system in mind? <span class="v-accent">Let's talk it through.</span></h2>
                        <p class="v-cta-lead">Tell us what you need. We'll come back within one working day with next steps and, where possible, a ballpark figure.</p>
                        <ul class="v-cta-details">
                            <li>Lusaka, Zambia</li>
                            <li><a href="tel:+260971864421">+260 97 1864421</a></li>
                            <li><a href="mailto:info@varidianlab.com">info@varidianlab.com</a></li>
                        </ul>
                        <WhatsAppButton variant="inline" label="Chat on WhatsApp" message="Hi, I'd like to talk through a system with Varidian." class="v-btn-wa" />
                    </div>

                    <form class="v-form" @submit.prevent="sendEnquiry">
                        <div v-if="enquirySent" class="v-form-ok" role="status">Thanks — your enquiry is on its way. We'll be in touch within one working day.</div>

                        <label>
                            Name
                            <input v-model="form.name" type="text" autocomplete="name" required />
                            <span v-if="form.errors.name" class="v-form-err">{{ form.errors.name }}</span>
                        </label>
                        <label>
                            Organisation
                            <input v-model="form.organisation" type="text" autocomplete="organization" />
                        </label>
                        <div class="v-form-row">
                            <label>
                                Phone
                                <input v-model="form.phone" type="tel" autocomplete="tel" placeholder="+260…" required />
                                <span v-if="form.errors.phone" class="v-form-err">{{ form.errors.phone }}</span>
                            </label>
                            <label>
                                Email
                                <input v-model="form.email" type="email" autocomplete="email" />
                                <span v-if="form.errors.email" class="v-form-err">{{ form.errors.email }}</span>
                            </label>
                        </div>
                        <label>
                            Interested in
                            <select v-model="form.product_interest" required>
                                <option value="" disabled>Select a product or service…</option>
                                <option v-for="topic in enquiryTopics" :key="topic">{{ topic }}</option>
                            </select>
                            <span v-if="form.errors.product_interest" class="v-form-err">{{ form.errors.product_interest }}</span>
                        </label>
                        <label>
                            What do you need?
                            <textarea v-model="form.message" rows="4" required></textarea>
                            <span v-if="form.errors.message" class="v-form-err">{{ form.errors.message }}</span>
                        </label>
                        <button type="submit" class="v-btn-wa v-form-btn" :disabled="form.processing">
                            {{ form.processing ? 'Sending…' : 'Send enquiry' }}
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </div>
</template>

<style>
/* ══════════════════════════════════════════════════════════════
   Landing page — Varidian Blue.
   Brand tokens are scoped to .v-landing so the shared mkt-* system
   is untouched. Dark values are the default (matching the site);
   light overrides follow below.
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
    --v-card-shadow: 0 18px 44px rgba(0, 0, 0, 0.4);
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
        --v-card-shadow: 0 18px 44px rgba(14, 66, 88, 0.09);
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
.v-landing section[id] {
    scroll-margin-top: 80px;
}

.v-wrap {
    max-width: 1120px;
    margin: 0 auto;
    padding: 0 24px;
}

/* ── Sections ── */
.v-sec {
    padding: 104px 0;
    background: var(--mkt-bg);
}
.v-sec-alt {
    background: var(--mkt-bg-2);
    border-top: 1px solid var(--mkt-line);
    border-bottom: 1px solid var(--mkt-line);
}
.v-sec-head {
    max-width: 680px;
    margin: 0 0 48px;
}
.v-sec-head--split {
    max-width: none;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: flex-end;
    gap: 24px;
}
.v-sec-head--split > div {
    max-width: 680px;
}
.v-sec-title {
    font-size: clamp(30px, 3.4vw, 44px);
    font-weight: 700;
    line-height: 1.12;
    margin-top: 18px;
}
.v-sec-title em {
    font-style: normal;
    color: var(--v-accent-text);
}
.v-sec-lead {
    color: var(--mkt-text-m);
    font-size: 15.5px;
    line-height: 1.7;
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
    justify-content: center;
    gap: 10px;
    background: var(--v-brand);
    color: #fff;
    font-weight: 700;
    font-size: 15px;
    padding: 14px 26px;
    border: 0;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(15, 158, 213, 0.35);
    text-decoration: none;
    cursor: pointer;
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
.v-landing .v-btn-outline {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 13px 24px;
    border-radius: 12px;
    font-size: 14.5px;
    font-weight: 700;
    color: var(--mkt-text-h);
    border: 1.5px solid var(--mkt-line-s);
    background: var(--mkt-surface);
    text-decoration: none;
    transition: border-color 0.2s;
}
.v-landing .v-btn-outline:hover {
    border-color: var(--v-brand);
    background: var(--mkt-surface);
    color: var(--mkt-text-h);
}
.v-landing .v-btn-outline svg {
    color: var(--v-brand);
}

/* ── Hero ── */
.v-hero {
    position: relative;
    overflow: hidden;
    background:
        radial-gradient(ellipse 70% 80% at 85% 10%, rgba(15, 158, 213, 0.3), transparent 60%),
        linear-gradient(160deg, var(--v-deep), var(--v-deepest) 55%);
    padding: 150px 0 96px;
}
.v-hero-grid {
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle at 1px 1px, rgba(255, 255, 255, 0.07) 1px, transparent 0);
    background-size: 34px 34px;
    pointer-events: none;
}
.v-hero-in {
    position: relative;
    max-width: 1120px;
    margin: 0 auto;
    padding: 0 24px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(min(340px, 100%), 1fr));
    gap: 56px;
    align-items: center;
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
    font-size: clamp(40px, 5.4vw, 68px);
    font-weight: 800;
    line-height: 1.03;
    margin: 26px 0 0;
}
.v-landing .v-accent {
    color: var(--v-brand);
}
.v-hero-body {
    color: rgba(255, 255, 255, 0.82);
    font-size: 17px;
    line-height: 1.68;
    max-width: 560px;
    margin: 22px 0 0;
}
.v-hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    margin-top: 34px;
}

/* ── Integrations card ── */
.v-int {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(166, 221, 243, 0.2);
    border-radius: 18px;
    padding: 26px;
    backdrop-filter: blur(6px);
}
.v-int-l,
.v-strip-l {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: rgba(166, 221, 243, 0.8);
}
.v-int-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
    margin-top: 18px;
}
.v-int-item {
    background: rgba(8, 44, 60, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    padding: 16px;
}
.v-int-t {
    color: #fff;
    font-weight: 700;
    font-size: 15px;
}
.v-int-b {
    color: rgba(255, 255, 255, 0.6);
    font-size: 13px;
    margin-top: 2px;
}

/* ── Built-for strip ── */
.v-strip {
    background: var(--mkt-bg-2);
    border-bottom: 1px solid var(--mkt-line);
    padding: 26px 0;
}
.v-strip-in {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px 32px;
}
.v-strip .v-strip-l {
    color: var(--v-chip-text);
}
.v-strip-v {
    font-size: 14.5px;
    font-weight: 600;
    color: var(--mkt-text-h);
}

/* ── Service cards ── */
.v-svc-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(min(240px, 100%), 1fr));
    gap: 18px;
}
.v-card,
.v-prod,
.v-work {
    background: var(--mkt-surface);
    border: 1px solid var(--mkt-line);
    border-radius: 16px;
    transition: border-color 0.25s, transform 0.25s, box-shadow 0.25s;
}
.v-card:hover,
.v-prod:hover,
.v-work:hover {
    border-color: var(--v-hover-border);
    box-shadow: var(--v-card-shadow);
}
.v-card {
    padding: 28px;
}
.v-card-ico {
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
.v-card-ico svg {
    width: 22px;
    height: 22px;
}
.v-card h3 {
    font-size: 19px;
    font-weight: 700;
}
.v-card p {
    color: var(--mkt-text-m);
    font-size: 14.5px;
    line-height: 1.65;
    margin: 10px 0 0;
}

/* ── Product cards ── */
.v-prod-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(min(250px, 100%), 1fr));
    gap: 18px;
}
.v-prod {
    display: flex;
    flex-direction: column;
    padding: 26px;
    text-decoration: none;
    color: inherit;
}
.v-prod:hover {
    transform: translateY(-3px);
}
.v-prod-sector {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--v-accent-text);
}
.v-prod-name {
    font-family: 'Bricolage Grotesque', sans-serif;
    font-size: 24px;
    font-weight: 800;
    letter-spacing: -0.02em;
    color: var(--mkt-text-h);
    margin-top: 8px;
}
.v-prod p {
    flex: 1;
    color: var(--mkt-text-m);
    font-size: 14.5px;
    line-height: 1.65;
    margin: 10px 0 18px;
}
.v-more {
    font-size: 13px;
    font-weight: 700;
    color: var(--v-link);
    transition: color 0.2s;
}
.v-prod:hover .v-more {
    color: var(--v-link-hover);
}

/* ── Why Varidian ── */
.v-why {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(min(340px, 100%), 1fr));
    gap: 56px;
    align-items: start;
}
.v-why-list {
    list-style: none;
    margin: 0;
    padding: 0;
}
.v-why-row {
    display: grid;
    grid-template-columns: 56px minmax(0, 1fr);
    gap: 16px;
    padding: 22px 0;
    border-top: 1px solid var(--mkt-line-s);
}
.v-why-row:last-child {
    border-bottom: 1px solid var(--mkt-line-s);
}
.v-why-n {
    font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
    font-size: 15px;
    font-weight: 600;
    color: var(--v-accent-text);
    padding-top: 2px;
}
.v-why-row h4 {
    font-size: 18px;
    font-weight: 700;
}
.v-why-row p {
    color: var(--mkt-text-m);
    font-size: 14.5px;
    line-height: 1.65;
    margin: 6px 0 0;
}

/* ── Hosting band ── */
.v-band {
    position: relative;
    overflow: hidden;
    background:
        radial-gradient(ellipse 60% 90% at 100% 50%, rgba(15, 158, 213, 0.35), transparent 65%),
        linear-gradient(105deg, var(--v-deepest), var(--v-deep));
    color: #fff;
    padding: 96px 0;
}
.v-band-in {
    position: relative;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(min(320px, 100%), 1fr));
    gap: 48px;
    align-items: center;
}
.v-band .v-chip,
.v-cta .v-chip {
    background: rgba(15, 158, 213, 0.16);
    border-color: rgba(166, 221, 243, 0.32);
    color: var(--v-brand-200);
}
.v-landing .v-band .v-sec-title,
.v-landing .v-cta .v-sec-title {
    color: #fff;
}
.v-band .v-sec-title em {
    color: var(--v-brand);
}
.v-band-lead {
    color: rgba(255, 255, 255, 0.74);
    font-size: 15.5px;
    line-height: 1.7;
    max-width: 560px;
    margin: 18px 0 0;
}
.v-plan-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(min(170px, 100%), 1fr));
    gap: 14px;
}
.v-plan {
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.13);
    border-radius: 14px;
    padding: 22px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.v-plan--featured {
    border-color: var(--v-brand);
    box-shadow: 0 0 0 1px var(--v-brand), 0 14px 40px rgba(15, 158, 213, 0.25);
}
.v-plan-name {
    color: var(--v-brand-200);
    font-size: 11.5px;
    font-weight: 800;
    letter-spacing: 0.09em;
    text-transform: uppercase;
}
.v-plan-price {
    font-family: 'Bricolage Grotesque', sans-serif;
    font-size: 26px;
    font-weight: 800;
    color: #fff;
}
.v-plan-price span {
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.6);
}
.v-landing .v-plan .v-plan-ask {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 0;
    border-radius: 0;
    background: none;
    font-size: 15px;
    font-weight: 700;
    color: #fff;
    text-decoration: none;
}
.v-landing .v-plan .v-plan-ask:hover {
    background: none;
    color: var(--v-brand-200);
}
.v-landing .v-plan .v-plan-ask svg {
    width: 15px;
    height: 15px;
    color: var(--v-brand);
}
.v-plan-spec {
    color: rgba(255, 255, 255, 0.7);
    font-size: 13.5px;
    line-height: 1.5;
}

/* ── Selected work ── */
.v-work-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(min(340px, 100%), 1fr));
    gap: 22px;
}
.v-work {
    overflow: hidden;
    display: flex;
    flex-direction: column;
}
.v-work-shot {
    position: relative;
    height: 220px;
    display: flex;
    align-items: flex-end;
    padding: 20px;
    background:
        radial-gradient(circle at 1px 1px, rgba(255, 255, 255, 0.09) 1px, transparent 0) 0 0 / 22px 22px,
        linear-gradient(150deg, var(--v-deep), var(--v-deepest) 70%);
}
.v-work-shot span {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--v-brand-200);
    background: rgba(8, 44, 60, 0.7);
    border: 1px solid rgba(166, 221, 243, 0.3);
    border-radius: 999px;
    padding: 5px 12px;
}
.v-work-body {
    padding: 28px;
}
.v-work h3 {
    font-size: 22px;
    font-weight: 700;
    line-height: 1.25;
    margin-top: 8px;
}
.v-work p {
    color: var(--mkt-text-m);
    font-size: 14.5px;
    line-height: 1.65;
    margin: 10px 0 0;
}

/* ── Process ── */
.v-steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(min(220px, 100%), 1fr));
    gap: 24px;
}
.v-step {
    border-top: 3px solid var(--v-brand);
    padding-top: 18px;
}
.v-step h4 {
    font-size: 18px;
    font-weight: 700;
}
.v-step p {
    color: var(--mkt-text-m);
    font-size: 14.5px;
    line-height: 1.65;
    margin: 8px 0 0;
}

/* ── Contact ── */
.v-cta {
    position: relative;
    overflow: hidden;
    border-radius: 24px;
    background: linear-gradient(150deg, var(--v-deep), var(--v-deepest) 60%);
    padding: 64px 48px;
    color: #fff;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(min(320px, 100%), 1fr));
    gap: 48px;
}
.v-cta::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse 70% 90% at 0% 0%, rgba(15, 158, 213, 0.38), transparent 60%);
    pointer-events: none;
}
.v-cta > * {
    position: relative;
}
@media (max-width: 640px) {
    .v-cta {
        padding: 40px 22px;
    }
}
.v-cta-lead {
    color: rgba(255, 255, 255, 0.72);
    font-size: 15.5px;
    line-height: 1.7;
    max-width: 480px;
    margin: 18px 0 24px;
}
.v-cta-details {
    list-style: none;
    padding: 0;
    margin: 0 0 28px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    color: rgba(255, 255, 255, 0.82);
    font-size: 15px;
}
.v-cta-details a {
    color: inherit;
    text-decoration: none;
}
.v-cta-details a:hover {
    color: var(--v-brand-200);
}
.v-form {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(166, 221, 243, 0.2);
    border-radius: 18px;
    padding: 26px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.v-form label {
    display: flex;
    flex-direction: column;
    gap: 6px;
    font-size: 14px;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.88);
}
.v-form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(min(180px, 100%), 1fr));
    gap: 16px;
}
.v-form input,
.v-form select,
.v-form textarea {
    min-height: 46px;
    border-radius: 10px;
    border: 1px solid rgba(166, 221, 243, 0.25);
    background: rgba(8, 44, 60, 0.7);
    color: #fff;
    padding: 0 14px;
    font: inherit;
    font-weight: 400;
    transition: border-color 0.2s;
}
.v-form textarea {
    padding: 12px 14px;
    resize: vertical;
}
.v-form select option {
    background: var(--v-deepest);
}
.v-form input:focus,
.v-form select:focus,
.v-form textarea:focus {
    outline: none;
    border-color: var(--v-brand);
}
.v-form-err {
    font-size: 12.5px;
    font-weight: 500;
    color: #fca5a5;
}
.v-form-ok {
    font-size: 14px;
    color: var(--v-brand-200);
    background: rgba(15, 158, 213, 0.14);
    border: 1px solid rgba(166, 221, 243, 0.3);
    border-radius: 10px;
    padding: 12px 14px;
}
.v-landing .v-form-btn {
    min-height: 50px;
    font: inherit;
    font-weight: 700;
}
.v-landing .v-form-btn:disabled {
    opacity: 0.6;
    cursor: wait;
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

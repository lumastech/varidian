<script setup lang="ts">
import EnquiryForm from '@/components/marketing/EnquiryForm.vue';
import SeoHead from '@/components/SeoHead.vue';
import WhatsAppButton from '@/components/WhatsAppButton.vue';
import { useScrollReveal } from '@/composables/useScrollReveal';

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

// interface CaseStudy {
//     tag: string;
//     title: string;
//     body: string;
//     preview: string;
// }

/**
 * Hero background photo. Swap this path for your own image (ideally 1920×800, subject on the right).
 */
const heroImage = '/images/hero-bg.png';

/**
 * Hosting band background photo. Swap this path for your own image (ideally 1920×800).
 */
const hostingImage = '/images/hero-bg-girl.jpg';

/**
 * Contact card background photo. Swap this path for your own image (ideally 1600×900).
 */
const contactImage = '/images/hero-bg-girl.jpg';

interface Solution {
    title: string;
    body: string;
    icon: string;
}

const solutions: Solution[] = [
    {
        title: 'School Systems',
        body: 'Student & staff management',
        icon: `<svg fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="7" r="3"/><circle cx="5" cy="9" r="2.2"/><circle cx="19" cy="9" r="2.2"/><path d="M6.5 20v-2a5.5 5.5 0 0 1 11 0v2M1.5 19v-1a3.5 3.5 0 0 1 4-3.4M22.5 19v-1a3.5 3.5 0 0 0-4-3.4"/></svg>`,
    },
    {
        title: 'NGOs & Institutions',
        body: 'Programmes & administration',
        icon: `<svg fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 9.5 12 4l9 5.5M4 9.5h16M5.5 9.5v8M9.5 9.5v8M14.5 9.5v8M18.5 9.5v8M3 20.5h18M4 17.5h16"/></svg>`,
    },
    {
        title: 'SMEs & Startups',
        body: 'Inventory & sales systems',
        icon: `<svg fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M7 16v-3M11 16v-5M15 16v-4M19 16V9M6 11l4-4 4 3 5-5"/></svg>`,
    },
    {
        title: 'Web & Mobile Apps',
        body: 'iOS • Android • Progressive Web',
        icon: `<svg fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M7 18a4.5 4.5 0 0 1-.6-8.96A6 6 0 0 1 18 8.5a4.5 4.5 0 0 1-1 8.9"/><path d="M12 21v-9M8.5 15.5 12 12l3.5 3.5"/></svg>`,
    },
    {
        title: 'Secure & Scalable',
        body: 'Local hosting & cloud ready',
        icon: `<svg fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 2.5 4 5.5v6c0 5 3.4 8.8 8 10 4.6-1.2 8-5 8-10v-6l-8-3Z"/><path d="m8.5 12 2.5 2.5 4.5-5"/></svg>`,
    },
    {
        title: 'Local Support',
        body: 'Based in Zambia',
        icon: `<svg fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3.5 14v-2a8.5 8.5 0 0 1 17 0v2"/><rect x="2.5" y="13" width="4" height="6" rx="1.5"/><rect x="17.5" y="13" width="4" height="6" rx="1.5"/><path d="M19.5 19v.5a2.5 2.5 0 0 1-2.5 2.5h-3"/></svg>`,
    },
];

const audiences = [
    'Private & mission schools',
    'NGOs',
    'Churches',
    'Microfinance & village banking',
    'SMEs',
    'Universities & colleges',
];

const services: Service[] = [
    {
        title: 'Custom software',
        body: 'Web platforms, portals and internal systems designed around your processes  not the other way round.',
        icon: `<svg fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M8 7l-5 5 5 5"/><path d="M16 7l5 5-5 5"/><path d="M14 4l-4 16"/></svg>`,
    },
    {
        title: 'AI & automation',
        body: 'Practical AI for businesses  document processing, assistants and workflow automation, with local inference options for sensitive data.',
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
        desc: 'School & student management for Grades 1-12  enrolment, fees via mobile money, ECZ exam tracking, payroll and parent SMS.',
        href: '/products/school-management-system',
    },
    {
        sector: 'Non-profit',
        name: 'Varidian Reach',
        desc: 'NGO management  beneficiaries, programmes, donors and reporting in one dedicated installation per organisation.',
        href: '#contact',
    },
    {
        sector: 'Business',
        name: 'BizManager',
        desc: 'SME business management with ZRA Smart Invoice integration  sales, stock, customers and compliant invoices.',
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
        desc: 'Microfinance and savings-group platform  members, loans, repayments and mobile money collections.',
        href: '/products/village-banking',
    },
    {
        sector: 'Faith',
        name: 'ChurchMS',
        desc: 'Church management  membership, giving, groups, events and congregation communication.',
        href: '/products/church-management-system',
    },
    {
        sector: 'Higher education',
        name: 'Coursify',
        desc: 'Online learning management for universities and colleges  courses, assessments and student progress.',
        href: '#contact',
    },
    {
        sector: 'Events',
        name: 'Varidian Events',
        desc: 'Event registration and ticketing with online payments  a data-protection-compliant alternative to generic forms.',
        href: '#contact',
    },
];

const reasons = [
    {
        title: 'Built for local systems',
        body: 'Airtel Money, MTN MoMo, ZRA Smart Invoice, NAPSA, PAYE and bulk SMS  integrated, not bolted on.',
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
 * A null `price` renders a "Pricing on request" WhatsApp link instead.
 */
const hostingPlans: HostingPlan[] = [
    {
        name: 'Starter',
        price: '250',
        spec: '1 website · 5GB SSD',
        featured: false,
    },
    {
        name: 'Business',
        price: '1000',
        spec: '5 websites · 20GB SSD',
        featured: true,
    },
    {
        name: 'Developer',
        price: '500',
        spec: '3 websites · multi-PHP',
        featured: false,
    },
];

// const caseStudies: CaseStudy[] = [
//     {
//         tag: 'NGO · Southern Province',
//         title: "Choma District Women's Development Association",
//         body: 'A seven-module management system and public website for chomadwda.org, covering members, programmes and reporting.',
//         preview: 'Admin dashboard',
//     },
//     {
//         tag: 'Microfinance',
//         title: 'ZMAI village banking platform',
//         body: 'A savings and microfinance platform managing members, loans and repayments.',
//         preview: 'Loans module',
//     },
// ];

const steps = [
    {
        title: 'Discover',
        body: 'We map your processes, users and compliance needs on site.',
    },
    {
        title: 'Design',
        body: 'A clear scope, fixed quotation and screens you approve before we build.',
    },
    {
        title: 'Build',
        body: 'Iterative delivery with regular demos, testing and staff training.',
    },
    {
        title: 'Run & support',
        body: 'Hosting, monitoring, backups and support after go-live.',
    },
];

useScrollReveal();
</script>

<template>
    <SeoHead
        title="Varidian Consulting Limited  Software built for the way Zambia works"
        description="Varidian designs, builds and hosts business systems for schools, NGOs, SMEs and institutions  with mobile money, SMS and ZRA compliance built in from day one, and your data kept in Zambia."
        keywords="varidianlab, varidian lab, varidian consulting, software house Lusaka, school management system Zambia, NGO management software, church management system Zambia, ZRA Smart Invoice software, village banking software, web hosting Zambia, data protection act Zambia hosting, Airtel Money MTN MoMo integration, AI automation Zambia, Varidian Consulting Limited"
        canonical-url="https://varidianlab.com"
    />

    <div class="v-landing">
        <!-- ══════════════ HERO ══════════════ -->
        <section id="top" class="v-hero v-hero--home">
            <img
                :src="heroImage"
                alt=""
                class="v-hero-img"
                fetchpriority="high"
            />
            <div class="v-hero-shade" aria-hidden="true"></div>
            <div class="v-hero-grid"></div>
            <div class="v-hero-glow" aria-hidden="true"></div>

            <svg
                class="v-hero-net"
                aria-hidden="true"
                viewBox="0 0 600 520"
                fill="none"
                preserveAspectRatio="xMaxYMid slice"
            >
                <g
                    stroke="currentColor"
                    stroke-width="1"
                    stroke-dasharray="3 5"
                    opacity="0.55"
                >
                    <path d="M150 90 L260 120 L330 60 L470 110 L540 60" />
                    <path d="M470 110 L520 230 L580 300 L540 400" />
                    <path d="M330 60 L300 20" />
                    <path d="M520 230 L440 330 L560 470" />
                </g>
                <g fill="currentColor">
                    <circle cx="260" cy="120" r="3" />
                    <circle cx="330" cy="60" r="2.5" />
                    <circle cx="470" cy="110" r="3" />
                    <circle cx="520" cy="230" r="2.5" />
                    <circle cx="580" cy="300" r="3" />
                    <circle cx="440" cy="330" r="2.5" />
                    <circle cx="300" cy="20" r="2" />
                </g>
                <g
                    class="v-hero-net-ico"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <!-- globe -->
                    <g transform="translate(126 66)">
                        <circle cx="24" cy="24" r="22" />
                        <path
                            d="M2 24h44M24 2c7 7 7 37 0 44M24 2c-7 7-7 37 0 44"
                        />
                    </g>
                    <!-- cloud upload -->
                    <g transform="translate(500 30)">
                        <path
                            d="M18 48a14 14 0 0 1-2-27.8A20 20 0 0 1 54 18a14 14 0 0 1 4 28"
                        />
                        <path d="M37 52V28M28 37l9-9 9 9" />
                    </g>
                    <!-- globe small -->
                    <g transform="translate(500 210)">
                        <circle cx="20" cy="20" r="18" />
                        <path
                            d="M2 20h36M20 2c6 6 6 30 0 36M20 2c-6 6-6 30 0 36"
                        />
                    </g>
                    <!-- code -->
                    <g transform="translate(410 310)">
                        <rect x="0" y="0" width="44" height="38" rx="5" />
                        <path d="m15 13-6 6 6 6M29 13l6 6-6 6M24 11l-4 16" />
                    </g>
                    <!-- monitor -->
                    <g transform="translate(550 280)">
                        <rect x="0" y="0" width="40" height="28" rx="3" />
                        <path d="M14 36h12M20 28v8" />
                    </g>
                </g>
            </svg>

            <div class="v-hero-in">
                <div class="v-hero-copy">
                    <div class="v-pill">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                        >
                            <path d="m8 7-5 5 5 5M16 7l5 5-5 5M14 4l-4 16" />
                        </svg>
                        Custom Software <b>•</b> Web <b>•</b> Mobile
                        <b>•</b> Cloud
                    </div>
                    <h1 class="v-hero-title">
                        Software built for the way
                        <span class="v-accent">Zambia works.</span>
                    </h1>
                    <p class="v-hero-body">
                        We design, build and deploy modern business systems for
                        schools, SMEs and institutions  with reliable money
                        flows, secure data and real-world solutions built in
                        from day one… and your data kept in Zambia.
                    </p>
                    <div class="v-hero-actions">
                        <WhatsAppButton
                            variant="inline"
                            label="Start a project"
                            message="Hi, I'd like to start a project with Varidian."
                            class="v-btn-wa"
                        />
                        <a href="products" class="v-btn-ghost">
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >
                                <path
                                    d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"
                                />
                                <path d="M3.3 7 12 12l8.7-5M12 22V12" />
                            </svg>
                            Explore our products
                        </a>
                    </div>
                </div>

                <div class="v-sol">
                    <div class="v-sol-l">Integrated software solutions</div>
                    <div class="v-sol-grid">
                        <div
                            v-for="solution in solutions"
                            :key="solution.title"
                            class="v-sol-item"
                        >
                            <!-- eslint-disable-next-line vue/no-v-html -->
                            <div class="v-sol-ico" v-html="solution.icon"></div>
                            <div>
                                <div class="v-sol-t">{{ solution.title }}</div>
                                <div class="v-sol-b">{{ solution.body }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════ BUILT FOR ══════════════ -->
        <section aria-label="Who we serve" class="v-strip">
            <div class="v-wrap v-strip-in">
                <span class="v-strip-l">Built for</span>
                <span
                    v-for="audience in audiences"
                    :key="audience"
                    class="v-strip-v"
                    >{{ audience }}</span
                >
            </div>
        </section>

        <!-- ══════════════ SERVICES ══════════════ -->
        <section id="services" class="v-sec">
            <div class="v-wrap">
                <div class="v-sec-head" data-reveal>
                    <div class="v-chip">What we do</div>
                    <h2 class="v-sec-title">
                        One partner from first idea <em>to running system.</em>
                    </h2>
                </div>

                <div class="v-svc-grid" data-reveal>
                    <div
                        v-for="service in services"
                        :key="service.title"
                        class="v-card"
                    >
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
                        <h2 class="v-sec-title">
                            Proven products, <em>configured for you.</em>
                        </h2>
                        <p class="v-sec-lead">
                            Start faster with a Varidian platform  on our cloud
                            for a monthly fee, or licensed outright and
                            installed on your own infrastructure.
                        </p>
                    </div>
                    <WhatsAppButton
                        variant="inline"
                        label="Request a demo"
                        message="Hi, I'd like to request a demo of a Varidian product."
                        class="v-btn-outline"
                    />
                </div>

                <div class="v-prod-grid" data-reveal>
                    <a
                        v-for="product in products"
                        :key="product.name"
                        :href="product.href"
                        class="v-prod"
                    >
                        <div class="v-prod-sector">{{ product.sector }}</div>
                        <div class="v-prod-name">{{ product.name }}</div>
                        <p>{{ product.desc }}</p>
                        <span class="v-more">{{
                            product.href.startsWith('/')
                                ? 'Learn more →'
                                : 'Ask about it →'
                        }}</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- ══════════════ WHY VARIDIAN ══════════════ -->
        <section id="about" class="v-sec">
            <div class="v-wrap v-why" data-reveal>
                <div class="v-why-head">
                    <div class="v-chip">Why Varidian</div>
                    <h2 class="v-sec-title">
                        Local knowledge <em>is the feature.</em>
                    </h2>
                    <p class="v-sec-lead">
                        Imported software rarely understands mobile money, ZRA
                        rules or the school calendar. Ours starts there. We're a
                        Lusaka team that answers the phone, visits the site and
                        stays after go-live.
                    </p>
                </div>
                <ol class="v-why-list">
                    <li
                        v-for="(reason, i) in reasons"
                        :key="reason.title"
                        class="v-why-row"
                    >
                        <span class="v-why-n">{{
                            String(i + 1).padStart(2, '0')
                        }}</span>
                        <div>
                            <h3>{{ reason.title }}</h3>
                            <p>{{ reason.body }}</p>
                        </div>
                    </li>
                </ol>
            </div>
        </section>

        <!-- ══════════════ HOSTING ══════════════ -->
        <section id="hosting" class="v-band">
            <img :src="hostingImage" alt="" class="v-hero-img" loading="lazy" />
            <div class="v-band-shade" aria-hidden="true"></div>
            <div class="v-hero-grid"></div>
            <div class="v-wrap v-band-in" data-reveal>
                <div>
                    <div class="v-chip">Varidian Hosting</div>
                    <h2 class="v-sec-title">
                        Hosting in Zambia,
                        <em>priced for Zambian businesses.</em>
                    </h2>
                    <p class="v-band-lead">
                        Shared hosting for SMEs, developers and existing clients
                         free SSL, daily backups and local support, with your
                        files stored on Zambian soil.
                    </p>
                    <a href="/hosting" class="v-band-link"
                        >See hosting plans →</a
                    >
                </div>
                <div class="v-plan-grid">
                    <div
                        v-for="plan in hostingPlans"
                        :key="plan.name"
                        class="v-plan"
                        :class="{ 'v-plan--featured': plan.featured }"
                    >
                        <div class="v-plan-name">{{ plan.name }}</div>
                        <div v-if="plan.price" class="v-plan-price">
                            ZMW {{ plan.price }}<span>/mo</span>
                        </div>
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
        <!-- <section id="work" class="v-sec hidden">
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
        </section> -->

        <!-- ══════════════ PROCESS ══════════════ -->
        <section class="v-sec v-sec-alt">
            <div class="v-wrap">
                <div class="v-sec-head" data-reveal>
                    <div class="v-chip">Process</div>
                    <h2 class="v-sec-title">How <em>we work</em></h2>
                </div>
                <div class="v-steps" data-reveal>
                    <div
                        v-for="(step, i) in steps"
                        :key="step.title"
                        class="v-step"
                    >
                        <h3>{{ i + 1 }}. {{ step.title }}</h3>
                        <p>{{ step.body }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ══════════════ CONTACT ══════════════ -->
        <section id="contact" class="v-sec">
            <div class="v-wrap">
                <div class="v-cta" data-reveal>
                    <img
                        :src="contactImage"
                        alt=""
                        class="v-hero-img"
                        loading="lazy"
                    />
                    <div class="v-cta-shade" aria-hidden="true"></div>
                    <div class="v-cta-copy">
                        <div class="v-chip">Let's get started</div>
                        <h2 class="v-sec-title">
                            Have a system in mind?
                            <span class="v-accent">Let's talk it through.</span>
                        </h2>
                        <p class="v-cta-lead">
                            Tell us what you need. We'll come back within one
                            working day with next steps and, where possible, a
                            ballpark figure.
                        </p>

                        <ul class="v-cta-details">
                            <li>Lusaka, Zambia</li>
                            <li>
                                <a href="tel:+260971864421">+260 97 1864421</a>
                            </li>
                            <li>
                                <a href="mailto:info@varidianlab.com"
                                    >info@varidianlab.com</a
                                >
                            </li>
                        </ul>
                        <WhatsAppButton
                            variant="inline"
                            label="Chat on WhatsApp"
                            message="Hi, I'd like to talk through a system with Varidian."
                            class="v-btn-wa"
                        />
                    </div>

                    <EnquiryForm />
                </div>
            </div>
        </section>
    </div>
</template>

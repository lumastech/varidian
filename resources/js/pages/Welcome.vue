<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const activeFaq = ref<number | null>(0);

const softwareTags = [
    'Web Applications',
    'Mobile Apps (Android & iOS)',
    'Enterprise Systems',
    'Cloud-Based Platforms',
    'API & Payment Integration',
    'Custom Business Software',
];

const services = [
    {
        title: 'Web & Mobile Development',
        desc: 'Responsive corporate websites, e-commerce platforms, web portals, and native or cross-platform mobile apps — built for performance on every device.',
        icon: `<svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="5" y="2" width="14" height="20" rx="2"/><path d="M12 18h.01"/></svg>`,
    },
    {
        title: 'Payment & Systems Integration',
        desc: 'End-to-end integration with bank APIs, mobile money platforms (Airtel, MTN, Zamtel), and third-party systems — seamless financial workflows for your business.',
        icon: `<svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>`,
    },
    {
        title: 'Cybersecurity',
        desc: 'Vulnerability assessments, penetration testing, cybersecurity strategy, and 24/7 monitoring to protect your systems and keep you compliant.',
        icon: `<svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>`,
    },
    {
        title: 'Data Analytics & AI',
        desc: 'Transform raw data into decisions. We build BI dashboards, data pipelines, and AI-driven automation tailored to your industry and operational needs.',
        icon: `<svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M18 20V10M12 20V4M6 20v-6"/></svg>`,
    },
    {
        title: 'ICT Audits',
        desc: 'Independent assessments of your systems, security posture, databases, and business processes — delivered with a clear findings report and actionable recommendations.',
        icon: `<svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>`,
    },
    {
        title: 'ICT Consulting & Outsourcing',
        desc: 'Strategic IT advisory, digital strategy, management consulting, and flexible ICT outsourcing — skilled technology capacity when and where your business needs it.',
        icon: `<svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>`,
    },
];

const steps = [
    { num: '01', title: 'Discover', desc: 'We take time to understand your business goals, challenges, and requirements before recommending any solution.' },
    { num: '02', title: 'Design', desc: 'We architect the right solution together — clear specifications, system design, and a roadmap you can see and approve.' },
    { num: '03', title: 'Build', desc: 'Our engineers develop and rigorously test your solution using modern frameworks and development best practices.' },
    { num: '04', title: 'Deploy', desc: 'We launch with confidence — migration support, user training, and a smooth go-live experience from day one.' },
    { num: '05', title: 'Support', desc: "We don't disappear after delivery. Ongoing maintenance and improvements keep your solution performing long-term." },
];

const whyCards = [
    {
        title: 'End-to-End Capability',
        desc: 'One partner for all your ICT needs — from strategy and design through development, deployment, and long-term support. No hand-offs, no gaps.',
        preview: `<svg class="w-40 h-16 opacity-30" viewBox="0 0 160 64" fill="none" stroke="#00c9a7" stroke-width="1.5"><rect x="4" y="20" width="24" height="24" rx="3"/><rect x="44" y="20" width="24" height="24" rx="3"/><rect x="84" y="20" width="24" height="24" rx="3"/><rect x="124" y="20" width="24" height="24" rx="3" fill="#00c9a7" opacity="0.15"/><line x1="28" y1="32" x2="44" y2="32"/><line x1="68" y1="32" x2="84" y2="32"/><line x1="108" y1="32" x2="124" y2="32"/></svg>`,
    },
    {
        title: 'Local Knowledge, Global Standards',
        desc: 'Deep understanding of the Zambian business environment — regulatory context, mobile money, local infrastructure — combined with international technology best practices.',
        preview: `<svg class="w-20 h-20 opacity-30" viewBox="0 0 80 80" fill="none" stroke="#00a3ff" stroke-width="1.2"><circle cx="40" cy="40" r="24"/><ellipse cx="40" cy="40" rx="11" ry="24"/><line x1="16" y1="40" x2="64" y2="40"/><line x1="20" y1="28" x2="60" y2="28"/><line x1="20" y1="52" x2="60" y2="52"/><circle cx="40" cy="18" r="4" fill="#00a3ff" opacity="0.5"/></svg>`,
    },
    {
        title: 'Custom-Built for You',
        desc: "We don't offer one-size-fits-all products. Every solution is designed and built specifically for your business context, processes, and goals.",
        preview: `<svg class="w-28 h-20 opacity-30" viewBox="0 0 112 80" fill="none" stroke="#00c9a7" stroke-width="1.2"><rect x="6" y="6" width="28" height="28" rx="4"/><rect x="42" y="6" width="28" height="28" rx="4" fill="#00c9a7" opacity="0.2"/><rect x="6" y="42" width="28" height="28" rx="4" fill="#00c9a7" opacity="0.12"/><rect x="42" y="42" width="28" height="28" rx="4"/><rect x="78" y="22" width="26" height="26" rx="4" fill="#00c9a7" opacity="0.08" stroke="#00a3ff"/></svg>`,
    },
    {
        title: 'Experienced Team',
        desc: 'Our engineers, developers, and consultants bring hands-on experience across financial services, e-commerce, enterprise systems, and more.',
        preview: `<svg class="w-24 h-20 opacity-30" viewBox="0 0 96 80" fill="none" stroke="#00c9a7" stroke-width="1"><circle cx="48" cy="22" r="11"/><circle cx="18" cy="34" r="9"/><circle cx="78" cy="34" r="9"/><path d="M26 58c0-9 11-15 22-15s22 6 22 15"/><path d="M4 66c0-7 6-11 14-11"/><path d="M92 66c0-7-6-11-14-11"/></svg>`,
    },
    {
        title: 'Partnership Approach',
        desc: 'We invest in understanding your business and stay engaged long after project delivery — because technology needs evolve, and we evolve with you.',
        preview: `<svg class="w-36 h-16 opacity-30" viewBox="0 0 144 64" fill="none" stroke="#00a3ff" stroke-width="1.5"><circle cx="24" cy="32" r="14" fill="#00a3ff" opacity="0.1"/><circle cx="120" cy="32" r="14" fill="#00a3ff" opacity="0.1"/><line x1="38" y1="32" x2="106" y2="32"/><circle cx="72" cy="32" r="7" fill="#00a3ff" opacity="0.35"/></svg>`,
    },
    {
        title: 'Transparent Pricing',
        desc: "Clear, fair pricing with no hidden costs or surprises. You know exactly what you're paying for and why — before we write a single line of code.",
        preview: `<svg class="w-32 h-20 opacity-30" viewBox="0 0 128 80" fill="none"><rect x="8" y="52" width="16" height="22" rx="2" fill="#00c9a7" opacity="0.4"/><rect x="32" y="34" width="16" height="40" rx="2" fill="#00c9a7" opacity="0.5"/><rect x="56" y="22" width="16" height="52" rx="2" fill="#00c9a7" opacity="0.65"/><rect x="80" y="34" width="16" height="40" rx="2" fill="#00c9a7" opacity="0.5"/><rect x="104" y="46" width="16" height="28" rx="2" fill="#00c9a7" opacity="0.4"/><line x1="4" y1="20" x2="124" y2="20" stroke="#00c9a7" opacity="0.15" stroke-width="1"/></svg>`,
    },
];

const testimonials = [
    { name: 'Alex Johnson', role: 'CTO, FinEdge', initials: 'AJ', c1: '#00c9a7', c2: '#00a3ff', text: 'Varidian rebuilt our entire infrastructure in 3 months. Their cloud architecture reduced our AWS bill by 40% while doubling performance. Exceptional work.' },
    { name: 'Priya Mehta', role: 'VP Engineering, DataSync', initials: 'PM', c1: '#9333ea', c2: '#2979ff', text: 'The team understood our data problems better than we did. They delivered a pipeline that now processes 10M records daily without breaking a sweat.' },
    { name: 'Marcus T.', role: 'CEO, BoldStack', initials: 'MT', c1: '#f59e0b', c2: '#ef4444', text: 'From strategy to launch, Varidian was a true partner. They pushed back when we were wrong, delivered on time, and the product speaks for itself.' },
    { name: 'Sophie Laurent', role: 'Director of IT, Renova', initials: 'SL', c1: '#10b981', c2: '#06b6d4', text: 'Our cybersecurity posture went from reactive to proactive. Zero incidents in 18 months. The 24/7 monitoring team is incredibly responsive.' },
    { name: 'David Chen', role: 'Product Manager, TerraOps', initials: 'DC', c1: '#00a3ff', c2: '#9333ea', text: 'The mobile app they built for us launched on time and handled our first 50,000 users without a single critical issue. Outstanding execution.' },
    { name: 'Lena Rosario', role: 'COO, NovaCraft', initials: 'LR', c1: '#f97316', c2: '#fbbf24', text: "Varidian's transparent process set them apart. We always knew what was happening, why decisions were made, and what was coming next. Rare quality." },
];

const faqs = [
    { q: 'What types of software do you build?', a: 'We build custom business applications, web platforms, mobile apps (Android & iOS), enterprise back-office systems, e-commerce solutions, and cloud-based platforms. We also handle API integrations and payment system connections with banks and mobile money providers like Airtel, MTN, and Zamtel.' },
    { q: 'How long does a typical software project take?', a: 'It depends on scope. A standard web application typically takes 6–10 weeks. A full enterprise system or mobile app can take 3–5 months. We always provide a detailed timeline during our discovery phase so you know exactly what to expect.' },
    { q: 'Do you work with businesses outside Zambia?', a: 'Yes. While we are headquartered in Lusaka, we serve clients across Zambia and the broader southern African region. We are fully equipped to support clients remotely and can arrange on-site presence where required.' },
    { q: 'Can I start with one service and expand later?', a: 'Absolutely. Most clients begin with a single project — a custom application, a website, or a cybersecurity audit — and grow the relationship from there. Our services are modular and designed to scale with you.' },
    { q: 'What makes Varidian different from other ICT firms?', a: "We combine deep technical capability with genuine business strategy thinking. We don't just write code — we understand your business, challenge assumptions where needed, and stay accountable for outcomes long after delivery." },
    { q: 'Is there a free consultation available?', a: 'Yes. We offer a complimentary 45-minute discovery call to understand your goals, discuss your project, and determine if we\'re the right fit. No obligation, no hard sell.' },
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
        { threshold: 0.12 },
    );
    document.querySelectorAll('[data-reveal]').forEach((el) => observer.observe(el));
});
</script>

<template>
    <Head title="Custom Software Solutions | Lusaka, Zambia" />

    <!-- ══════════════ HERO ══════════════ -->
    <section class="hero-bg relative flex min-h-screen flex-col items-center justify-center overflow-hidden px-6 pt-24 pb-16 text-center">
        <div class="absolute inset-0 pointer-events-none" style="background-image: linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px); background-size: 60px 60px;"></div>

        <div class="relative z-10 mx-auto max-w-3xl">
            <div class="mkt-pill mb-8 inline-flex">
                <span class="mkt-pill-dot"></span>
                Lusaka, Zambia — Full-Service ICT Partner
            </div>

            <h1 class="hero-title mb-6">
                Your Business Runs<br />
                on <span class="accent">Software.</span><br />
                We Build It Right.
            </h1>

            <p class="mx-auto mb-10 max-w-lg text-base leading-relaxed text-slate-400">
                From custom applications and payment integrations to cybersecurity and data analytics — Varidian delivers software solutions that work on time, within budget, and built to last.
            </p>

            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="/services" class="mkt-btn-outline-hero">Our Services</a>
                <a href="https://wa.me/260971864421" target="_blank" rel="noopener noreferrer" class="mkt-btn-solid-hero flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 18v-6a9 9 0 0118 0v6" />
                        <path d="M21 19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-3a2 2 0 012-2h3zM3 19a2 2 0 002 2h1a2 2 0 002-2V9M3 19a2 2 0 002 2h1a2 2 0 002-2H3z" />
                    </svg>
                    Book a Free Call
                </a>
            </div>

            <div class="mt-14 flex flex-wrap items-center justify-center gap-8">
                <div class="text-center">
                    <div class="font-display text-2xl font-bold text-white">8+</div>
                    <div class="mt-0.5 text-xs text-slate-500">Service areas</div>
                </div>
                <div class="h-8 w-px bg-white/10"></div>
                <div class="text-center">
                    <div class="font-display text-2xl font-bold text-white">100%</div>
                    <div class="mt-0.5 text-xs text-slate-500">Custom-built solutions</div>
                </div>
                <div class="h-8 w-px bg-white/10"></div>
                <div class="text-center">
                    <div class="font-display text-2xl font-bold text-white">24/7</div>
                    <div class="mt-0.5 text-xs text-slate-500">Support & monitoring</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══════════════ SERVICES ══════════════ -->
    <section class="px-6 py-24" style="background:#080c10">
        <div class="mx-auto max-w-6xl">
            <div class="mb-14 text-center" data-reveal>
                <div class="mkt-chip mb-4">Services</div>
                <h2 class="mkt-sec-title">Everything Your Business<br /><em>Needs to Grow</em></h2>
                <p class="mx-auto mt-4 max-w-md text-sm text-slate-500">Software development is our core — supported by a full suite of ICT services designed to take your business further.</p>
            </div>

            <!-- Featured core service -->
            <div class="service-card featured-service mb-4" data-reveal>
                <div class="flex flex-col gap-6 sm:flex-row sm:items-start">
                    <div class="flex-shrink-0">
                        <div class="mkt-service-icon" style="width:48px;height:48px;border-radius:12px;">
                            <svg class="h-6 w-6 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="mb-2 flex items-center gap-3">
                            <h3 class="text-lg font-semibold text-white">Custom Software Development</h3>
                            <span class="mkt-chip">Core Service</span>
                        </div>
                        <p class="mb-4 text-sm leading-relaxed text-slate-400">Our flagship practice. We design and build tailored business applications, enterprise systems, web platforms, mobile apps, and cloud-based solutions — covering the entire development lifecycle from requirements discovery through deployment and ongoing support.</p>
                        <div class="flex flex-wrap gap-2">
                            <span v-for="tag in softwareTags" :key="tag" class="rounded-full border border-white/10 px-3 py-1 text-xs text-slate-400">{{ tag }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="svc in services" :key="svc.title" class="service-card" data-reveal>
                    <div class="mkt-service-icon" v-html="svc.icon"></div>
                    <h3 class="mb-2 text-base font-semibold text-white">{{ svc.title }}</h3>
                    <p class="text-sm leading-relaxed text-slate-400">{{ svc.desc }}</p>
                </div>
            </div>

            <div class="mkt-sep my-2"></div>
            <div class="flex flex-col items-center justify-between gap-4 pt-8 sm:flex-row">
                <p class="text-sm text-slate-400">Have a project in mind? Let's talk about what we can build for you.</p>
                <a href="/contact" class="mkt-btn-outline text-sm">Book Free Consultation</a>
            </div>
        </div>
    </section>

    <!-- ══════════════ OUR APPROACH ══════════════ -->
    <section class="px-6 py-24" style="background:#0a0f14">
        <div class="mx-auto max-w-5xl">
            <div class="mb-16 text-center" data-reveal>
                <div class="mkt-chip mb-4">Our Approach</div>
                <h2 class="mkt-sec-title">How We <em>Deliver</em></h2>
                <p class="mx-auto mt-4 max-w-sm text-sm text-slate-500">A structured, client-centred process — from understanding your needs to supporting your solution long-term.</p>
            </div>

            <!-- Desktop: row with connectors -->
            <div class="hidden items-start gap-0 lg:flex" data-reveal>
                <div v-for="(step, i) in steps" :key="step.title" class="flex flex-1 items-start">
                    <div class="step-wrap flex-1 px-2 text-center">
                        <div class="step-node">
                            <span class="font-display text-sm font-bold text-teal-400">{{ step.num }}</span>
                        </div>
                        <h4 class="mb-2 text-sm font-semibold text-white">{{ step.title }}</h4>
                        <p class="text-xs leading-relaxed text-slate-500">{{ step.desc }}</p>
                    </div>
                    <div v-if="i < steps.length - 1" class="step-connector mx-1 mt-6 hidden lg:block" style="min-width:20px;"></div>
                </div>
            </div>

            <!-- Mobile: stacked -->
            <div class="grid gap-6 sm:grid-cols-2 lg:hidden" data-reveal>
                <div v-for="step in steps" :key="step.title" class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="step-node" style="margin:0;width:40px;height:40px;">
                            <span class="font-display text-xs font-bold text-teal-400">{{ step.num }}</span>
                        </div>
                    </div>
                    <div class="pt-2">
                        <h4 class="mb-1 text-sm font-semibold text-white">{{ step.title }}</h4>
                        <p class="text-xs leading-relaxed text-slate-500">{{ step.desc }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══════════════ WHY VARIDIAN ══════════════ -->
    <section class="why-bg px-6 py-24">
        <div class="mx-auto max-w-6xl">
            <div class="mb-14 text-center" data-reveal>
                <div class="mkt-chip mb-4">Why Us</div>
                <h2 class="mkt-sec-title">What Sets Varidian<br /><em>Apart</em></h2>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="w in whyCards" :key="w.title" class="why-card" data-reveal>
                    <div class="why-card-preview flex h-36 items-center justify-center p-5" v-html="w.preview"></div>
                    <div class="p-5">
                        <h4 class="mb-1.5 text-sm font-semibold text-white">{{ w.title }}</h4>
                        <p class="text-xs leading-relaxed text-slate-400">{{ w.desc }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══════════════ TESTIMONIALS ══════════════ -->
    <section class="px-6 py-24" style="background:#0a0f14">
        <div class="mx-auto max-w-6xl">
            <div class="mb-14 text-center" data-reveal>
                <div class="mkt-chip mb-4">Testimonials</div>
                <h2 class="mkt-sec-title">What Our <em>Clients Say</em></h2>
            </div>
            <div class="columns-1 gap-4 space-y-4 sm:columns-2 lg:columns-3" data-reveal>
                <div v-for="t in testimonials" :key="t.name" class="testi-card mb-4 break-inside-avoid">
                    <div class="mb-3 flex gap-0.5">
                        <svg v-for="i in 5" :key="i" class="h-3.5 w-3.5 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                        </svg>
                    </div>
                    <p class="mb-4 text-sm leading-relaxed text-slate-300">"{{ t.text }}"</p>
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full text-xs font-bold text-white"
                            :style="`background: linear-gradient(135deg, ${t.c1}, ${t.c2})`"
                        >
                            {{ t.initials }}
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-white">{{ t.name }}</div>
                            <div class="text-xs text-slate-500">{{ t.role }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══════════════ FAQ ══════════════ -->
    <section class="px-6 py-24" style="background:#0a0f14">
        <div class="mx-auto max-w-3xl">
            <div class="mb-14 text-center" data-reveal>
                <div class="mkt-chip mb-4">FAQ</div>
                <h2 class="mkt-sec-title">Common <em>Questions</em></h2>
            </div>
            <div data-reveal>
                <div
                    v-for="(faq, i) in faqs"
                    :key="i"
                    class="faq-row cursor-pointer rounded-lg px-2 py-5"
                    @click="activeFaq = activeFaq === i ? null : i"
                >
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-sm font-medium text-white">{{ faq.q }}</span>
                        <div
                            class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full border border-white/10 transition-all"
                            :class="activeFaq === i ? 'bg-teal-500/20 border-teal-500/40' : ''"
                        >
                            <svg
                                class="h-3.5 w-3.5 transition-transform"
                                :class="activeFaq === i ? 'rotate-180 text-teal-400' : 'text-slate-500'"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2.5"
                                viewBox="0 0 24 24"
                            >
                                <path d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    <div v-if="activeFaq === i" class="mt-3 pr-10 text-sm leading-relaxed text-slate-400">{{ faq.a }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ══════════════ BOTTOM CTA ══════════════ -->
    <section class="px-6 py-24" style="background:#080c10">
        <div class="mx-auto max-w-4xl">
            <div class="mkt-cta-bottom px-8 py-16 text-center" data-reveal>
                <div class="mkt-chip mb-6">Let's Build Together</div>
                <h2 class="mkt-sec-title mb-4">
                    Ready to Build Something<br />
                    <span style="background: linear-gradient(90deg,#00c9a7,#00a3ff);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">That Lasts?</span>
                </h2>
                <p class="mx-auto mb-8 max-w-md text-sm leading-relaxed text-slate-400">Start with a free 45-minute discovery call. We'll learn about your business, your goals, and whether we're the right fit — no obligation, no pressure.</p>
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <a href="https://wa.me/260971864421" target="_blank" rel="noopener noreferrer" class="mkt-btn-book px-8 py-3.5 text-base">Book Free Discovery Call</a>
                    <a href="/services" class="mkt-btn-outline">View Our Services</a>
                </div>
            </div>
        </div>
    </section>
</template>

<style>
.hero-bg {
    background: radial-gradient(ellipse 80% 60% at 50% -10%, rgba(0, 180, 160, 0.18) 0%, transparent 65%),
        radial-gradient(ellipse 50% 40% at 80% 60%, rgba(0, 120, 255, 0.08) 0%, transparent 60%), #080c10;
}
.why-bg {
    background: radial-gradient(ellipse 60% 50% at 50% 50%, rgba(0, 180, 160, 0.07) 0%, transparent 70%), #0a0f14;
}
.hero-title {
    font-family: 'Bricolage Grotesque', sans-serif;
    font-size: clamp(36px, 6vw, 72px);
    font-weight: 800;
    line-height: 1.08;
    color: #eef2f7;
}
.hero-title .accent {
    background: linear-gradient(90deg, #00c9a7, #00a3ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.font-display {
    font-family: 'Bricolage Grotesque', sans-serif;
}
.mkt-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(255, 255, 255, 0.04);
    backdrop-filter: blur(8px);
    border-radius: 999px;
    padding: 5px 14px;
    font-size: 11px;
    color: #9ba8b8;
    letter-spacing: 0.04em;
}
.mkt-pill-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #00c9a7;
}
.mkt-btn-outline-hero {
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 999px;
    padding: 10px 26px;
    font-size: 14px;
    color: #c8d0db;
    background: rgba(255, 255, 255, 0.04);
    cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
    text-decoration: none;
    display: inline-block;
}
.mkt-btn-outline-hero:hover {
    border-color: rgba(255, 255, 255, 0.35);
    background: rgba(255, 255, 255, 0.07);
}
.mkt-btn-solid-hero {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 999px;
    padding: 10px 26px;
    font-size: 14px;
    color: #fff;
    cursor: pointer;
    transition: background 0.2s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}
.mkt-btn-solid-hero:hover {
    background: rgba(255, 255, 255, 0.15);
}
.service-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.07);
    border-radius: 14px;
    padding: 28px 24px;
    transition: border-color 0.25s, background 0.25s, transform 0.25s;
}
.service-card:hover {
    border-color: rgba(0, 201, 167, 0.3);
    background: rgba(0, 201, 167, 0.04);
    transform: translateY(-3px);
}
.service-card.featured-service {
    border-color: rgba(0, 201, 167, 0.25);
    background: linear-gradient(160deg, rgba(0, 201, 167, 0.07), rgba(0, 163, 255, 0.04));
}
.service-card.featured-service:hover {
    border-color: rgba(0, 201, 167, 0.5);
}
.step-node {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    border: 1px solid rgba(0, 201, 167, 0.3);
    background: rgba(0, 201, 167, 0.08);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    transition: border-color 0.25s, background 0.25s;
}
.step-wrap:hover .step-node {
    border-color: rgba(0, 201, 167, 0.6);
    background: rgba(0, 201, 167, 0.15);
}
.step-connector {
    flex: 1;
    height: 1px;
    background: linear-gradient(to right, rgba(0, 201, 167, 0.3), rgba(0, 163, 255, 0.3));
    margin-top: -26px;
}
.why-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.07);
    border-radius: 14px;
    overflow: hidden;
    transition: border-color 0.25s;
}
.why-card:hover {
    border-color: rgba(0, 163, 255, 0.3);
}
.why-card-preview {
    background: rgba(0, 0, 0, 0.4);
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
}
.testi-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.07);
    border-radius: 14px;
    padding: 20px;
    transition: border-color 0.25s;
}
.testi-card:hover {
    border-color: rgba(0, 201, 167, 0.25);
}
.faq-row {
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    transition: background 0.2s;
}
.faq-row:hover {
    background: rgba(255, 255, 255, 0.02);
}
</style>

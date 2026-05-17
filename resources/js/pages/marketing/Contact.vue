<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import { onMounted } from 'vue';

defineProps<{
    status?: string;
}>();

const form = useForm({
    name: '',
    company: '',
    email: '',
    phone: '',
    service: '',
    message: '',
});

function sendMessage() {
    form.post('/contact', {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}

onMounted(() => {
    const obs = new IntersectionObserver(
        (entries) => entries.forEach((e) => { if (e.isIntersecting) { e.target.classList.add('in'); } }),
        { threshold: 0.08 },
    );
    document.querySelectorAll('[data-reveal]').forEach((el) => obs.observe(el));
});
</script>

<template>
    <Head title="Contact — Varidian" />

    <!-- PAGE HEADER -->
    <section class="mkt-page-header relative overflow-hidden px-6 pt-40 pb-20 text-center" style="background: radial-gradient(ellipse 80% 50% at 50% -5%, rgba(0,163,255,0.10) 0%, transparent 65%), #080c10;">
        <div class="absolute inset-0 pointer-events-none" style="background-image:linear-gradient(rgba(255,255,255,0.02) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.02) 1px,transparent 1px);background-size:60px 60px"></div>
        <div class="relative z-10 mx-auto max-w-3xl">
            <div class="mkt-chip mb-5">Get In Touch</div>
            <h1 class="mkt-page-title mb-5">Let's Build Something<br /><span class="accent">Together</span></h1>
            <p class="mx-auto max-w-lg text-base leading-relaxed text-slate-400">Tell us about your project, your business, or just say hello. We'll get back to you within one business day.</p>
        </div>
    </section>

    <!-- CONTACT BODY -->
    <section class="px-6 py-20" style="background:#080c10">
        <div class="mx-auto max-w-6xl">
            <div class="mb-16 grid gap-10 lg:grid-cols-5">

                <!-- FORM -->
                <div class="lg:col-span-3" data-reveal>
                    <div class="contact-form-card">
                        <h2 class="mb-1 text-lg font-bold text-white">Send Us a Message</h2>
                        <p class="mb-8 text-xs text-slate-500">Fill in the form and we'll get back to you within one business day.</p>

                        <!-- Success banner -->
                        <div v-if="status === 'message-sent'" class="contact-success-banner mb-6 flex items-center gap-3">
                            <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M5 13l4 4L19 7" />
                            </svg>
                            Message sent! We'll get back to you within one business day.
                        </div>

                        <!-- Error banner -->
                        <div v-if="form.hasErrors" class="mb-6 flex items-center gap-3 rounded-xl px-4.5 py-3.5 text-sm text-red-400" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25)">
                            <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" /><path d="M12 8v4m0 4h.01" />
                            </svg>
                            Please correct the errors below.
                        </div>

                        <div class="mb-5 grid gap-5 sm:grid-cols-2">
                            <div>
                                <label class="contact-field-label">Your Name *</label>
                                <input v-model="form.name" class="contact-field-input" :class="{ 'border-red-500/50': form.errors.name }" type="text" placeholder="John Banda" />
                                <p v-if="form.errors.name" class="mt-1 text-xs text-red-400">{{ form.errors.name }}</p>
                            </div>
                            <div>
                                <label class="contact-field-label">Company / Organisation</label>
                                <input v-model="form.company" class="contact-field-input" type="text" placeholder="Acme Ltd" />
                            </div>
                        </div>

                        <div class="mb-5 grid gap-5 sm:grid-cols-2">
                            <div>
                                <label class="contact-field-label">Email Address *</label>
                                <input v-model="form.email" class="contact-field-input" :class="{ 'border-red-500/50': form.errors.email }" type="email" placeholder="john@company.com" />
                                <p v-if="form.errors.email" class="mt-1 text-xs text-red-400">{{ form.errors.email }}</p>
                            </div>
                            <div>
                                <label class="contact-field-label">Phone / WhatsApp</label>
                                <input v-model="form.phone" class="contact-field-input" type="tel" placeholder="+260 97 ..." />
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="contact-field-label">Service You're Interested In</label>
                            <select v-model="form.service" class="contact-field-input">
                                <option value="">Select a service...</option>
                                <option>Custom Software Development</option>
                                <option>Web Development</option>
                                <option>Mobile App Development</option>
                                <option>Payment & API Integration</option>
                                <option>Cybersecurity</option>
                                <option>Data Analytics & AI</option>
                                <option>ICT Audit</option>
                                <option>Management Consulting</option>
                                <option>ICT Outsourcing</option>
                                <option>ICT Equipment Supply</option>
                                <option>Other / Not Sure Yet</option>
                            </select>
                        </div>

                        <div class="mb-7">
                            <label class="contact-field-label">Tell Us About Your Project *</label>
                            <textarea
                                v-model="form.message"
                                class="contact-field-input"
                                :class="{ 'border-red-500/50': form.errors.message }"
                                placeholder="Briefly describe what you're trying to build or solve. The more detail, the better."
                            ></textarea>
                            <p v-if="form.errors.message" class="mt-1 text-xs text-red-400">{{ form.errors.message }}</p>
                        </div>

                        <button class="mkt-btn-teal w-full text-center" :disabled="form.processing || status === 'message-sent'" @click="sendMessage">
                            <span v-if="form.processing" class="flex items-center justify-center gap-2">
                                <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-opacity=".25" />
                                    <path d="M12 2a10 10 0 0110 10" stroke="currentColor" stroke-width="3" stroke-linecap="round" />
                                </svg>
                                Sending…
                            </span>
                            <span v-else>Send Message</span>
                        </button>

                        <p class="mt-3 text-center text-xs text-slate-600">
                            Or
                            <a href="https://wa.me/260971864421" target="_blank" rel="noopener noreferrer" class="text-slate-500 underline transition-colors hover:text-teal-400">send via WhatsApp instead</a>
                        </p>
                    </div>
                </div>

                <!-- CONTACT INFO -->
                <div class="flex flex-col gap-5 lg:col-span-2" data-reveal>

                    <!-- WhatsApp CTA -->
                    <div class="rounded-2xl p-6 text-center" style="background:linear-gradient(135deg,rgba(37,211,102,0.12),rgba(0,180,160,0.08));border:1px solid rgba(37,211,102,0.25)">
                        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full" style="background:rgba(37,211,102,0.15)">
                            <svg class="h-6 w-6" fill="#25D366" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                            </svg>
                        </div>
                        <h3 class="mb-2 text-base font-bold text-white">Prefer to Chat Directly?</h3>
                        <p class="mb-5 text-xs leading-relaxed text-slate-400">Message us on WhatsApp and we'll respond within a few hours — even on weekends.</p>
                        <a href="https://wa.me/260971864421" target="_blank" rel="noopener noreferrer" class="contact-btn-wa">
                            <svg class="h-4 w-4" fill="white" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                            </svg>
                            Chat on WhatsApp
                        </a>
                    </div>

                    <!-- Contact details -->
                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <svg class="h-4 w-4 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <div class="mb-1 text-xs text-slate-500">Email</div>
                            <a href="mailto:info@varidianlab.com" class="text-sm text-white transition-colors hover:text-teal-400">info@varidianlab.com</a>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <svg class="h-4 w-4 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="mb-1 text-xs text-slate-500">Office</div>
                            <div class="text-sm text-white">Lusaka, Zambia</div>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <svg class="h-4 w-4 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" /><path d="M2 12h20M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20" />
                            </svg>
                        </div>
                        <div>
                            <div class="mb-1 text-xs text-slate-500">Website</div>
                            <div class="text-sm text-white">www.varidianlab.com</div>
                        </div>
                    </div>

                    <!-- Active indicator -->
                    <div class="rounded-xl p-4" style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05)">
                        <div class="mb-2 flex items-center gap-2">
                            <div class="h-2 w-2 rounded-full bg-emerald-400" style="box-shadow:0 0 6px rgba(52,211,153,0.6)"></div>
                            <span class="text-xs font-semibold text-white">We're active</span>
                        </div>
                        <p class="text-xs leading-relaxed text-slate-500">We typically respond within a few hours. For urgent matters, WhatsApp is the fastest way to reach us.</p>
                    </div>
                </div>
            </div>

            <!-- Discovery call section -->
            <div class="mkt-sep mb-16"></div>
            <div class="mx-auto max-w-2xl text-center" data-reveal>
                <div class="mkt-chip mb-5">Free Discovery Call</div>
                <h2 class="contact-sec-title mb-4">Not Sure Where to Start?</h2>
                <p class="mb-8 text-sm leading-relaxed text-slate-400">Book a complimentary 45-minute discovery call. We'll listen to your challenges, share our perspective on the best approach, and tell you honestly whether we're the right fit — no hard sell.</p>
                <a
                    href="https://wa.me/260971864421?text=Hi%20Varidian!%20I'd%20like%20to%20book%20a%20free%20discovery%20call."
                    target="_blank"
                    rel="noopener noreferrer"
                    class="contact-btn-wa inline-flex justify-center"
                >
                    <svg class="h-4 w-4" fill="white" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                    </svg>
                    Book Discovery Call on WhatsApp
                </a>
            </div>
        </div>
    </section>
</template>

<style>
.contact-sec-title {
    font-family: 'Bricolage Grotesque', sans-serif;
    font-size: clamp(22px, 3vw, 36px);
    font-weight: 800;
    color: #eef2f7;
    line-height: 1.2;
}
.contact-form-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 18px;
    padding: 36px;
}
.contact-field-label {
    display: block;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #8898aa;
    margin-bottom: 8px;
}
.contact-field-input {
    width: 100%;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    padding: 11px 15px;
    font-size: 14px;
    color: #eef2f7;
    font-family: inherit;
    transition: border-color 0.2s;
    outline: none;
}
.contact-field-input:focus {
    border-color: rgba(0, 201, 167, 0.5);
    background: rgba(255, 255, 255, 0.07);
}
.contact-field-input::placeholder {
    color: #4a5568;
}
select.contact-field-input option {
    background: #111827;
    color: #eef2f7;
}
textarea.contact-field-input {
    resize: vertical;
    min-height: 120px;
}
.contact-info-item {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 18px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 12px;
    transition: border-color 0.2s;
}
.contact-info-item:hover {
    border-color: rgba(0, 201, 167, 0.2);
}
.contact-info-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: rgba(0, 201, 167, 0.1);
    border: 1px solid rgba(0, 201, 167, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.contact-btn-wa {
    background: #25d366;
    border-radius: 999px;
    padding: 12px 28px;
    font-size: 14px;
    font-weight: 600;
    color: #fff;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: opacity 0.2s;
}
.contact-btn-wa:hover {
    opacity: 0.88;
}
.contact-success-banner {
    background: rgba(0, 201, 167, 0.1);
    border: 1px solid rgba(0, 201, 167, 0.3);
    border-radius: 10px;
    padding: 14px 18px;
    color: #00c9a7;
    font-size: 13px;
}
</style>

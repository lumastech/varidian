<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import SeoHead from '@/components/SeoHead.vue';

defineProps<{
    status?: string;
}>();

const form = useForm({
    name: '',
    organisation: '',
    phone: '',
    email: '',
    product_interest: '',
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
        (entries) =>
            entries.forEach((e) => {
                if (e.isIntersecting) {
                    e.target.classList.add('in');
                }
            }),
        { threshold: 0.08 },
    );
    document.querySelectorAll('[data-reveal]').forEach((el) => obs.observe(el));
});
</script>

<template>
    <SeoHead
        title="Contact Varidian — Lusaka, Zambia"
        description="Get in touch with Varidian Consulting Limited. Call, WhatsApp, or email us from Lusaka, Zambia. Request a product demo or discuss a custom development project."
        canonical-url="https://varidianlab.com/contact"
    />

    <!-- PAGE HEADER -->
    <section class="relative overflow-hidden px-6 pt-40 pb-20 text-center" style="background:radial-gradient(ellipse 80% 50% at 50% -5%, rgba(0,163,255,0.10) 0%, transparent 65%), var(--mkt-bg);border-bottom:1px solid var(--mkt-line)">
        <div class="absolute inset-0 pointer-events-none" style="background-image:linear-gradient(var(--mkt-dot-grid) 1px,transparent 1px),linear-gradient(90deg,var(--mkt-dot-grid) 1px,transparent 1px);background-size:60px 60px"></div>
        <div class="relative z-10 mx-auto max-w-3xl">
            <div class="mkt-chip mb-5">Get In Touch</div>
            <h1 class="mkt-page-title mb-5">Get in touch</h1>
            <p class="mx-auto max-w-lg text-base leading-relaxed" style="color:var(--mkt-text-m)">We are based in Zambia. Call, WhatsApp, or send us a message and we will respond within one business day.</p>
        </div>
    </section>

    <!-- CONTACT BODY -->
    <section class="mkt-sec-dark px-6 py-20">
        <div class="mx-auto max-w-6xl">
            <div class="mb-16 grid gap-10 lg:grid-cols-5">

                <!-- FORM -->
                <div class="lg:col-span-3" data-reveal>
                    <div class="contact-form-card">
                        <h2 class="mb-1 text-lg font-bold" style="color:var(--mkt-text-h)">Send Us a Message</h2>
                        <p class="mb-8 text-xs" style="color:var(--mkt-text-m)">Fill in the form and we'll get back to you within one business day.</p>

                        <!-- Success banner -->
                        <div v-if="status === 'message-sent'" class="contact-success-banner mb-6 flex items-center gap-3">
                            <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M5 13l4 4L19 7" />
                            </svg>
                            Thank you. We have received your message and will be in touch within one business day. You can also reach us directly on WhatsApp: +260 97 1864421
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
                                <label class="contact-field-label">School, church, company name</label>
                                <input v-model="form.organisation" class="contact-field-input" type="text" placeholder="School, church, company name" />
                            </div>
                        </div>

                        <div class="mb-5 grid gap-5 sm:grid-cols-2">
                            <div>
                                <label class="contact-field-label">Phone / WhatsApp *</label>
                                <input v-model="form.phone" class="contact-field-input" :class="{ 'border-red-500/50': form.errors.phone }" type="tel" placeholder="+260..." />
                                <p v-if="form.errors.phone" class="mt-1 text-xs text-red-400">{{ form.errors.phone }}</p>
                            </div>
                            <div>
                                <label class="contact-field-label">Email Address</label>
                                <input v-model="form.email" class="contact-field-input" :class="{ 'border-red-500/50': form.errors.email }" type="email" placeholder="john@company.com" />
                                <p v-if="form.errors.email" class="mt-1 text-xs text-red-400">{{ form.errors.email }}</p>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="contact-field-label">Product Interest *</label>
                            <select v-model="form.product_interest" class="contact-field-input" :class="{ 'border-red-500/50': form.errors.product_interest }">
                                <option value="">Select a product or service...</option>
                                <option>School Management System</option>
                                <option>Church Management System</option>
                                <option>BizManager</option>
                                <option>Village Banking</option>
                                <option>Custom Development</option>
                                <option>General Inquiry</option>
                            </select>
                            <p v-if="form.errors.product_interest" class="mt-1 text-xs text-red-400">{{ form.errors.product_interest }}</p>
                        </div>

                        <div class="mb-7">
                            <label class="contact-field-label">Message *</label>
                            <textarea
                                v-model="form.message"
                                class="contact-field-input"
                                :class="{ 'border-red-500/50': form.errors.message }"
                                placeholder="Tell us about your organisation and what you are looking for. The more detail, the better."
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
                            <span v-else>Send message</span>
                        </button>

                        <p class="mt-3 text-center text-xs" style="color:var(--mkt-text-m)">
                            Or
                            <a href="https://wa.me/260971864421" target="_blank" rel="noopener noreferrer" class="underline transition-colors hover:text-teal-400" style="color:var(--mkt-text-m)">send via WhatsApp instead</a>
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
                        <h3 class="mb-2 text-base font-bold" style="color:var(--mkt-text-h)">Prefer to Chat Directly?</h3>
                        <p class="mb-5 text-xs leading-relaxed" style="color:var(--mkt-text-m)">Message us on WhatsApp and we'll respond within a few hours.</p>
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
                                <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="mb-1 text-xs" style="color:var(--mkt-text-m)">Location</div>
                            <div class="text-sm" style="color:var(--mkt-text-h)">Lusaka, Zambia</div>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <svg class="h-4 w-4 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div>
                            <div class="mb-1 text-xs" style="color:var(--mkt-text-m)">Phone</div>
                            <a href="tel:+260971864421" class="text-sm transition-colors hover:text-teal-400" style="color:var(--mkt-text-h)">+260 97 1864421</a>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <svg class="h-4 w-4 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <div class="mb-1 text-xs" style="color:var(--mkt-text-m)">Email</div>
                            <a href="mailto:info@varidianlab.com" class="text-sm transition-colors hover:text-teal-400" style="color:var(--mkt-text-h)">info@varidianlab.com</a>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <svg class="h-4 w-4 text-teal-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" /><path d="M12 6v6l4 2" />
                            </svg>
                        </div>
                        <div>
                            <div class="mb-1 text-xs" style="color:var(--mkt-text-m)">Business hours</div>
                            <div class="text-sm" style="color:var(--mkt-text-h)">Monday – Friday, 08:00 – 17:00 CAT</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style>
.contact-sec-title {
    font-family: 'Bricolage Grotesque', sans-serif;
    font-size: clamp(22px, 3vw, 36px);
    font-weight: 800;
    color: var(--mkt-text-h);
    line-height: 1.2;
}
.contact-form-card {
    background: var(--mkt-surface);
    border: 1px solid var(--mkt-line);
    border-radius: 18px;
    padding: 36px;
}
.contact-field-label {
    display: block;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--mkt-text-m);
    margin-bottom: 8px;
}
.contact-field-input {
    width: 100%;
    background: var(--mkt-surface-2);
    border: 1px solid var(--mkt-line-s);
    border-radius: 10px;
    padding: 11px 15px;
    font-size: 14px;
    color: var(--mkt-text-h);
    font-family: inherit;
    transition: border-color 0.2s, background 0.2s;
    outline: none;
}
.contact-field-input:focus {
    border-color: rgba(0, 201, 167, 0.5);
    background: var(--mkt-surface);
}
.contact-field-input::placeholder {
    color: var(--mkt-muted);
}
select.contact-field-input option {
    background: var(--mkt-bg-2);
    color: var(--mkt-text-h);
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
    background: var(--mkt-surface);
    border: 1px solid var(--mkt-line);
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

import { onBeforeUnmount, onMounted } from 'vue';

/**
 * Fades in every `[data-reveal]` element on the page as it scrolls into view.
 */
export function useScrollReveal(): void {
    let observer: IntersectionObserver | null = null;

    onMounted(() => {
        observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('in');
                    }
                });
            },
            { threshold: 0.1 },
        );

        document.querySelectorAll('[data-reveal]').forEach((el) => observer?.observe(el));
    });

    onBeforeUnmount(() => observer?.disconnect());
}

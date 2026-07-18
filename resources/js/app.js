import './bootstrap';
import { gsap } from 'gsap';
import { initFormCascade, initDashboard, initProfile } from './mblrc';
import { initIb39Dashboard, initIb39Map } from './ib39';

window.gsap = gsap;

/**
 * SHIELD — vanilla JS interaction layer (no Alpine).
 * Behaviors are wired via data-attributes so Blade stays declarative.
 */
document.addEventListener('DOMContentLoaded', () => {
    initSidebar();
    initDropdowns();
    initModals();
    initTabs();
    initFlashToasts();
    revealOnLoad();

    // mblrc modules self-gate on the presence of their DOM markers.
    initFormCascade();
    initDashboard();
    initProfile();
    initIb39Dashboard();
    initIb39Map();
});

// Mobile sidebar toggle -------------------------------------------------
function initSidebar() {
    const sidebar = document.querySelector('[data-sidebar]');
    const backdrop = document.querySelector('[data-sidebar-backdrop]');
    const open = () => {
        sidebar?.classList.remove('-translate-x-full');
        backdrop?.classList.remove('hidden');
    };
    const close = () => {
        sidebar?.classList.add('-translate-x-full');
        backdrop?.classList.add('hidden');
    };
    document.querySelectorAll('[data-sidebar-open]').forEach((b) => b.addEventListener('click', open));
    document.querySelectorAll('[data-sidebar-close]').forEach((b) => b.addEventListener('click', close));
    backdrop?.addEventListener('click', close);
}

// Click-to-toggle dropdowns (profile menu, etc.) ------------------------
function initDropdowns() {
    document.querySelectorAll('[data-dropdown]').forEach((root) => {
        const trigger = root.querySelector('[data-dropdown-trigger]');
        const menu = root.querySelector('[data-dropdown-menu]');
        if (!trigger || !menu) return;
        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            menu.classList.toggle('hidden');
        });
    });
    document.addEventListener('click', () => {
        document.querySelectorAll('[data-dropdown-menu]').forEach((m) => m.classList.add('hidden'));
    });
}

// Lightweight modals ----------------------------------------------------
// Trigger:  [data-modal-open="id"]   Panel: #id with [data-modal]
// Close:    [data-modal-close] or backdrop / Escape.
function initModals() {
    const open = (id) => {
        const m = document.getElementById(id);
        if (!m) return;
        m.classList.remove('hidden');
        m.classList.add('flex');
        gsap.fromTo(m.querySelector('[data-modal-panel]'),
            { y: 20, opacity: 0 }, { y: 0, opacity: 1, duration: 0.25, ease: 'power2.out' });
    };
    const close = (m) => { m.classList.add('hidden'); m.classList.remove('flex'); };

    document.querySelectorAll('[data-modal-open]').forEach((b) =>
        b.addEventListener('click', () => open(b.dataset.modalOpen)));
    document.querySelectorAll('[data-modal]').forEach((m) => {
        m.addEventListener('click', (e) => {
            if (e.target === m || e.target.closest('[data-modal-close]')) close(m);
        });
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') document.querySelectorAll('[data-modal]:not(.hidden)').forEach(close);
    });
    window.openModal = open;
    window.closeModals = () => document.querySelectorAll('[data-modal]').forEach(close);
}

// Tabs ------------------------------------------------------------------
// Container [data-tabs]; buttons [data-tab-btn="key"]; panels [data-tab-panel="key"].
function initTabs() {
    document.querySelectorAll('[data-tabs]').forEach((root) => {
        const btns = root.querySelectorAll('[data-tab-btn]');
        const panels = root.querySelectorAll('[data-tab-panel]');
        const activate = (key) => {
            btns.forEach((b) => b.classList.toggle('tab-active', b.dataset.tabBtn === key));
            panels.forEach((p) => p.classList.toggle('hidden', p.dataset.tabPanel !== key));
        };
        btns.forEach((b) => b.addEventListener('click', () => activate(b.dataset.tabBtn)));
        if (btns.length) activate(btns[0].dataset.tabBtn);
    });
}

// Session-flash toasts --------------------------------------------------
function initFlashToasts() {
    document.querySelectorAll('[data-toast]').forEach((el) => {
        gsap.fromTo(el, { x: 40, opacity: 0 }, { x: 0, opacity: 1, duration: 0.4, ease: 'power2.out' });
        setTimeout(() => {
            gsap.to(el, { x: 40, opacity: 0, duration: 0.3, onComplete: () => el.remove() });
        }, 4500);
    });
}

// Subtle content reveal -------------------------------------------------
function revealOnLoad() {
    const items = document.querySelectorAll('[data-reveal]');
    if (!items.length) return;
    gsap.fromTo(
        items,
        { y: 16, opacity: 0 },
        { y: 0, opacity: 1, duration: 0.5, ease: 'power2.out', stagger: 0.06 },
    );
}

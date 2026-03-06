<script lang="ts">
    import { page } from '$app/stores';
    import AppHead from '$lib/components/AppHead.svelte';
    import AppLogoIcon from '$lib/components/AppLogoIcon.svelte';
    import {
        ArrowRight, ArrowUpRight, ChevronDown, Shield,
        Palette, Globe, Users, Lock, Terminal, Star, Sparkles,
    } from 'lucide-svelte';

    const user = $derived($page.data.user);

    let openFaq = $state(-1);
    function toggleFaq(i: number) {
        openFaq = openFaq === i ? -1 : i;
    }

    function reveal(node: HTMLElement) {
        if (typeof IntersectionObserver === 'undefined') return {};
        const rect = node.getBoundingClientRect();
        if (rect.top < window.innerHeight + 50) return {};
        node.style.opacity = '0';
        node.style.transform = 'translateY(32px)';
        node.style.transition = 'opacity 0.9s cubic-bezier(0.16, 1, 0.3, 1), transform 0.9s cubic-bezier(0.16, 1, 0.3, 1)';
        const observer = new IntersectionObserver(
            ([entry]) => {
                if (entry.isIntersecting) {
                    node.style.opacity = '1';
                    node.style.transform = 'translateY(0)';
                    observer.unobserve(node);
                }
            },
            { threshold: 0.08 },
        );
        observer.observe(node);
        return { destroy: () => observer.disconnect() };
    }

    const features = [
        { icon: Shield, title: 'Authentication', desc: 'Login, registration, password reset, email verification, and two-factor authentication — all powered by Laravel Fortify.' },
        { icon: Users, title: 'Social Login', desc: 'One-click OAuth with GitHub, Google, Facebook, X, and Apple. Existing accounts auto-link by email.' },
        { icon: Palette, title: 'Dark Mode', desc: 'System-aware theme switching with manual override. Light, dark, and auto modes persisted across sessions.' },
        { icon: Globe, title: 'Server-Side Rendering', desc: 'SvelteKit SSR pre-configured out of the box for blazing-fast initial page loads and better SEO.' },
        { icon: Lock, title: 'Full Type Safety', desc: 'End-to-end TypeScript — Svelte components, shared types, API contracts, and route definitions.' },
        { icon: Terminal, title: 'Developer Experience', desc: 'Vite HMR, path aliases, concurrent dev server, one-command setup. Start building in under a minute.' },
    ];

    const included = [
        { num: '01', title: 'Authentication System', desc: 'Complete auth scaffolding — login, register, password reset, email verification, TOTP two-factor auth with recovery codes, plus social login for five providers.' },
        { num: '02', title: 'Modern Frontend Stack', desc: 'Svelte 5 with runes, TypeScript, SvelteKit routing with SSR, Skeleton UI components, and server-side rendering. Dark mode included.' },
        { num: '03', title: 'Developer Tooling', desc: 'Vite for instant HMR, path aliases for clean imports, concurrent dev server, pre-configured SSR builds, and a sensible project structure that scales.' },
    ];

    const faqs = [
        { q: 'What PHP version do I need?', a: 'PHP 8.2 or higher. Laravel 12 takes advantage of the latest PHP features including enums, fibers, and readonly properties.' },
        { q: 'Can I use MySQL or PostgreSQL instead of SQLite?', a: 'Absolutely. SQLite is the default for zero-config local development, but you can switch to any Laravel-supported database by updating your .env file.' },
        { q: 'Is this ready for production?', a: 'Yes. All dependencies are stable releases. Authentication, security headers, and the build pipeline are production-ready out of the box.' },
        { q: 'How do I customize the theme?', a: 'The starter uses Skeleton UI with the Cerberus theme. Swap to any built-in theme, customize design tokens, or build your own design system with Tailwind CSS v4.' },
        { q: 'Is server-side rendering required?', a: 'Not at all. SSR is pre-configured but entirely optional. Adjust your SvelteKit adapter or config to disable it.' },
    ];

    const testimonials = [
        { initials: 'MR', name: 'Maria Rodriguez', role: 'Tech Lead at Laracasts', quote: 'This saved our team weeks of setup. The auth system with SSO was exactly what we needed — and the code quality is exceptional.' },
        { initials: 'JC', name: 'James Chen', role: 'Indie Developer', quote: 'Svelte 5 and Laravel is an incredible combination. This starter kit makes it trivial to ship with best practices from day one.' },
        { initials: 'SB', name: 'Sarah Blake', role: 'Freelance Engineer', quote: 'I reach for this on every client project. Dark mode, 2FA, social login — the polish is there from the very first deploy.' },
    ];

    const stats = [
        { value: '6', label: 'Auth Providers' },
        { value: '2FA', label: 'Built-in' },
        { value: 'SSR', label: 'Pre-configured' },
        { value: '<60s', label: 'Setup Time' },
    ];
</script>

<AppHead title="Welcome" />

<div class="bg-surface-50-950 relative min-h-screen">
    <!-- Hero gradient mesh -->
    <div class="hero-gradient pointer-events-none absolute inset-x-0 top-0 h-[900px]"></div>

    <!-- Navbar -->
    <nav class="glass-nav sticky top-0 z-50 border-b border-surface-200/60 dark:border-surface-800/60">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-8">
            <a href="/" class="flex items-center gap-2">
                <AppLogoIcon />
            </a>
            <div class="hidden items-center gap-8 md:flex">
                <a href="#features" class="text-surface-500 hover:text-surface-900 dark:hover:text-surface-50 text-sm font-medium transition-colors">Features</a>
                <a href="#stack" class="text-surface-500 hover:text-surface-900 dark:hover:text-surface-50 text-sm font-medium transition-colors">Stack</a>
                <a href="#faq" class="text-surface-500 hover:text-surface-900 dark:hover:text-surface-50 text-sm font-medium transition-colors">FAQ</a>
            </div>
            <div class="flex items-center gap-3">
                {#if user}
                    <a href="/dashboard" class="preset-filled-primary-500 btn">Dashboard</a>
                {:else}
                    <a href="/login" class="text-surface-500 hover:text-surface-900 dark:hover:text-surface-50 hidden text-sm font-medium transition-colors sm:block">Log in</a>
                    <a href="/register" class="preset-filled-primary-500 btn shadow-lg shadow-primary-500/20">Get Started</a>
                {/if}
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="relative mx-auto max-w-7xl px-6 lg:px-8">
        <div class="grid items-center gap-16 pt-24 pb-20 lg:grid-cols-2 lg:pt-36 lg:pb-32">
            <div class="hero-enter">
                <div class="inline-flex items-center gap-2 rounded-full border border-primary-500/20 bg-primary-500/[.06] px-4 py-1.5">
                    <Sparkles class="text-primary-500 size-4" />
                    <span class="text-primary-500 text-sm font-medium">Laravel 12 + SvelteKit Starter Kit</span>
                </div>

                <h1 class="mt-8 text-[2.75rem] font-extrabold leading-[1.08] tracking-tight sm:text-6xl lg:text-7xl">
                    Build with confidence and <span class="gradient-text">deliver on time</span>
                </h1>

                <p class="text-surface-500 mt-6 max-w-lg text-lg leading-relaxed">
                    A production-ready starter kit with authentication, social login, dark mode, and SSR — pre-configured so you can focus on building what matters.
                </p>

                <div class="mt-10 flex flex-wrap items-center gap-4">
                    {#if user}
                        <a href="/dashboard" class="preset-filled-primary-500 btn gap-2 shadow-lg shadow-primary-500/25">
                            Go to Dashboard <ArrowRight class="size-4" />
                        </a>
                    {:else}
                        <a href="/register" class="preset-filled-primary-500 btn gap-2 shadow-lg shadow-primary-500/25">
                            Get Started <ArrowRight class="size-4" />
                        </a>
                        <a href="/login" class="preset-tonal btn gap-2">Log in</a>
                    {/if}
                </div>

                <div class="mt-14 flex items-center gap-3">
                    <div class="flex gap-0.5">
                        {#each Array(5) as _}
                            <Star class="size-4 fill-amber-400 text-amber-400" />
                        {/each}
                    </div>
                    <span class="text-surface-400 text-sm">Built with the latest stable releases</span>
                </div>
            </div>

            <div class="hero-enter-delay hidden lg:block">
                <div class="mockup-wrapper">
                    <div class="mockup-float relative">
                        <div class="pointer-events-none absolute -inset-6 rounded-[2rem] bg-gradient-to-br from-primary-500/20 via-purple-500/15 to-amber-500/20 blur-3xl"></div>
                        <div class="relative overflow-hidden rounded-2xl border border-white/[.08] shadow-2xl ring-1 ring-white/5">
                            <div class="flex items-center gap-2 border-b border-white/5 bg-[#16162a] px-4 py-3">
                                <span class="size-2.5 rounded-full bg-[#ff5f57]"></span>
                                <span class="size-2.5 rounded-full bg-[#febc2e]"></span>
                                <span class="size-2.5 rounded-full bg-[#28c840]"></span>
                                <div class="ml-3 flex-1 rounded-md bg-white/[.04] px-3 py-1">
                                    <span class="text-[11px] text-white/30 select-none">localhost:5173/dashboard</span>
                                </div>
                            </div>
                            <div class="flex bg-[#0d0d1a]">
                                <div class="w-44 space-y-5 border-r border-white/5 p-4">
                                    <div class="h-6 w-20 rounded-md bg-primary-500/20"></div>
                                    <div class="space-y-2">
                                        <div class="h-[34px] w-full rounded-lg bg-primary-500/25"></div>
                                        <div class="h-[34px] w-full rounded-lg bg-white/[.03]"></div>
                                        <div class="h-[34px] w-5/6 rounded-lg bg-white/[.03]"></div>
                                    </div>
                                    <div class="space-y-2 pt-2">
                                        <div class="h-3 w-14 rounded bg-white/[.08]"></div>
                                        <div class="h-[34px] w-full rounded-lg bg-white/[.03]"></div>
                                        <div class="h-[34px] w-3/4 rounded-lg bg-white/[.03]"></div>
                                    </div>
                                </div>
                                <div class="flex-1 p-5">
                                    <div class="mb-5 h-5 w-28 rounded bg-white/[.08]"></div>
                                    <div class="mb-5 grid grid-cols-3 gap-3">
                                        <div class="rounded-xl bg-white/[.03] p-3.5">
                                            <div class="mb-2 h-3 w-14 rounded bg-white/[.06]"></div>
                                            <div class="h-6 w-16 rounded bg-primary-500/30"></div>
                                        </div>
                                        <div class="rounded-xl bg-white/[.03] p-3.5">
                                            <div class="mb-2 h-3 w-14 rounded bg-white/[.06]"></div>
                                            <div class="h-6 w-16 rounded bg-emerald-500/30"></div>
                                        </div>
                                        <div class="rounded-xl bg-white/[.03] p-3.5">
                                            <div class="mb-2 h-3 w-14 rounded bg-white/[.06]"></div>
                                            <div class="h-6 w-16 rounded bg-amber-500/30"></div>
                                        </div>
                                    </div>
                                    <div class="rounded-xl bg-white/[.03] p-4">
                                        <div class="flex items-end gap-[5px] h-[7.5rem]">
                                            <div class="w-full rounded-t bg-primary-500/15" style="height:40%"></div>
                                            <div class="w-full rounded-t bg-primary-500/25" style="height:60%"></div>
                                            <div class="w-full rounded-t bg-primary-500/35" style="height:80%"></div>
                                            <div class="w-full rounded-t bg-primary-500/20" style="height:45%"></div>
                                            <div class="w-full rounded-t bg-primary-500/45" style="height:92%"></div>
                                            <div class="w-full rounded-t bg-primary-500/30" style="height:70%"></div>
                                            <div class="w-full rounded-t bg-primary-500/20" style="height:55%"></div>
                                            <div class="w-full rounded-t bg-primary-500/40" style="height:85%"></div>
                                            <div class="w-full rounded-t bg-primary-500/25" style="height:65%"></div>
                                            <div class="w-full rounded-t bg-primary-500/35" style="height:78%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats strip -->
    <section class="border-surface-200-800 border-y">
        <div class="divide-surface-200-800 mx-auto grid max-w-7xl grid-cols-2 divide-x px-6 sm:grid-cols-4 lg:px-8">
            {#each stats as stat}
                <div class="px-6 py-10 text-center">
                    <p class="text-3xl font-extrabold tracking-tight lg:text-4xl">{stat.value}</p>
                    <p class="text-surface-500 mt-1.5 text-sm">{stat.label}</p>
                </div>
            {/each}
        </div>
    </section>

    <!-- Tech logos -->
    <section use:reveal class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
        <p class="text-surface-400 mb-8 text-center text-xs font-semibold uppercase tracking-widest">Powered by</p>
        <div class="flex flex-wrap items-center justify-center gap-x-14 gap-y-5">
            {#each ['Laravel', 'SvelteKit', 'Tailwind CSS', 'Skeleton UI', 'TypeScript', 'Vite'] as tech}
                <span class="text-surface-300 dark:text-surface-600 text-[15px] font-semibold tracking-wide select-none transition-colors hover:text-surface-500 dark:hover:text-surface-400">{tech}</span>
            {/each}
        </div>
    </section>

    <!-- Value proposition -->
    <section use:reveal class="mx-auto max-w-7xl px-6 pb-24 lg:px-8 lg:pb-32">
        <div class="mx-auto max-w-3xl text-center">
            <p class="text-xl leading-relaxed lg:text-2xl lg:leading-relaxed">
                <span class="gradient-text font-bold">→</span>
                We've combined the most powerful tools in the Laravel ecosystem so you can stop
                configuring boilerplate and <span class="font-semibold">focus entirely on what makes your app unique</span>.
                Auth, social login, theming, SSR — it's all wired up and ready.
            </p>
        </div>
    </section>

    <!-- What's included -->
    <section use:reveal id="features" class="mx-auto max-w-7xl px-6 pb-28 lg:px-8 lg:pb-36">
        <div class="grid gap-12 lg:grid-cols-2 lg:gap-24">
            <div class="lg:sticky lg:top-28 lg:self-start">
                <h2 class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                    See what's<br />included
                </h2>
                <p class="text-surface-500 mt-5 max-w-sm text-lg leading-relaxed">Everything you need to launch a modern web application — nothing you don't.</p>
            </div>
            <div class="space-y-5">
                {#each included as item}
                    <div class="card-premium border-surface-200-800 group rounded-2xl border p-7 transition-all duration-300 hover:border-primary-500/40 hover:shadow-xl hover:shadow-primary-500/[.04]">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <span class="gradient-text font-mono text-sm font-bold">{item.num}</span>
                                <span class="text-surface-300 dark:text-surface-600">→</span>
                                <h3 class="text-sm font-bold tracking-wide uppercase">{item.title}</h3>
                            </div>
                            <ArrowUpRight class="text-surface-300 dark:text-surface-700 group-hover:text-primary-500 size-5 shrink-0 transition-colors duration-300" />
                        </div>
                        <p class="text-surface-500 mt-4 text-[15px] leading-relaxed">{item.desc}</p>
                    </div>
                {/each}
            </div>
        </div>
    </section>

    <!-- Feature grid -->
    <section use:reveal id="stack" class="bg-surface-100-900 py-28 lg:py-36">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="flex flex-wrap items-end justify-between gap-6">
                <h2 class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                    Production-ready<br />features
                </h2>
                <a href="#faq" class="text-primary-500 hover:text-primary-400 group flex items-center gap-1.5 text-sm font-medium transition-colors">
                    View all details <ArrowRight class="size-4 transition-transform duration-300 group-hover:translate-x-1" />
                </a>
            </div>
            <div class="mt-14 grid gap-5 sm:grid-cols-2 lg:mt-16 lg:grid-cols-3">
                {#each features as feature}
                    <div class="bg-surface-50-950 group rounded-2xl p-7 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-surface-900/5 dark:hover:shadow-black/20">
                        <div class="flex size-12 items-center justify-center rounded-xl bg-gradient-to-br from-primary-500/15 to-primary-500/5 ring-1 ring-primary-500/10">
                            <feature.icon class="text-primary-500 size-5" />
                        </div>
                        <h3 class="mt-5 text-lg font-bold">{feature.title}</h3>
                        <p class="text-surface-500 mt-2.5 text-[15px] leading-relaxed">{feature.desc}</p>
                    </div>
                {/each}
            </div>
        </div>
    </section>

    <!-- Testimonial quote -->
    <section use:reveal class="dark-section relative overflow-hidden py-28 lg:py-36">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_60%_50%_at_50%_50%,rgba(168,85,247,0.08),transparent)]"></div>
        <svg class="pointer-events-none absolute left-1/2 top-12 -translate-x-1/2 text-white/[.03]" width="220" height="180" viewBox="0 0 24 24" fill="currentColor">
            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10H14.017zM0 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151C7.546 6.068 5.983 8.789 5.983 11h4v10H0z"/>
        </svg>
        <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <blockquote class="text-2xl font-medium leading-relaxed text-white lg:text-4xl lg:leading-snug">
                    "The best way to start a new Laravel project. Everything is configured exactly how
                    I'd set it up myself — but it saves hours of boilerplate work every single time."
                </blockquote>
                <div class="mt-12 flex items-center justify-center gap-4">
                    <div class="flex size-14 items-center justify-center rounded-full bg-gradient-to-br from-primary-500 to-purple-500 text-sm font-bold text-white shadow-lg shadow-primary-500/25">
                        AK
                    </div>
                    <div class="text-left">
                        <p class="text-lg font-semibold text-white">Alex Kovacs</p>
                        <p class="text-surface-400 text-sm">Senior Full-Stack Developer</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Terminal section -->
    <section use:reveal class="mx-auto max-w-7xl px-6 py-28 lg:px-8 lg:py-36">
        <div class="grid items-center gap-16 lg:grid-cols-2 lg:gap-24">
            <div>
                <h2 class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                    Up and running<br />in minutes
                </h2>
                <p class="text-surface-500 mt-6 max-w-md text-lg leading-relaxed">
                    No configuration rabbit holes. One command handles everything — dependencies,
                    migrations, and the frontend build.
                </p>
                <div class="mt-10 space-y-5">
                    {#each ['Full auth with 2FA & social login', 'Sidebar & header layout options', 'Dark mode with system detection', 'SSR + Vite HMR out of the box'] as item}
                        <div class="flex items-center gap-3.5">
                            <div class="flex size-6 items-center justify-center rounded-full bg-gradient-to-br from-primary-500/20 to-primary-500/5 ring-1 ring-primary-500/20">
                                <svg class="text-primary-500 size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12" /></svg>
                            </div>
                            <span class="text-[15px]">{item}</span>
                        </div>
                    {/each}
                </div>
            </div>
            <div class="relative">
                <div class="pointer-events-none absolute -inset-4 rounded-3xl bg-primary-500/[.06] blur-2xl"></div>
                <div class="relative overflow-hidden rounded-2xl border border-white/[.06] bg-[#0d0d1a] shadow-2xl ring-1 ring-white/5">
                    <div class="flex items-center gap-2 border-b border-white/5 px-5 py-3.5">
                        <span class="size-3 rounded-full bg-[#ff5f57]"></span>
                        <span class="size-3 rounded-full bg-[#febc2e]"></span>
                        <span class="size-3 rounded-full bg-[#28c840]"></span>
                        <span class="ml-3 text-xs text-white/25 select-none">Terminal</span>
                    </div>
                    <div class="space-y-2 p-6 font-mono text-[13px] leading-relaxed text-surface-300">
                        <p><span class="text-emerald-400">~</span> git clone laravel-svelte-starter my-app</p>
                        <p><span class="text-emerald-400">~</span> cd my-app</p>
                        <p><span class="text-emerald-400">~</span> composer setup</p>
                        <p class="text-white/25 pt-1">  Installing dependencies... done</p>
                        <p class="text-white/25">  Running migrations... done</p>
                        <p class="text-white/25">  Building frontend... done</p>
                        <p class="pt-3"><span class="text-emerald-400">~</span> composer dev</p>
                        <p class="pt-1"><span class="text-primary-400">→</span> <span class="text-white/60">App running at</span> <span class="text-primary-400 underline decoration-primary-500/30">http://localhost:5173</span></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Developer testimonials -->
    <section use:reveal class="border-surface-200-800 border-t py-28 lg:py-36">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="flex flex-wrap items-end justify-between gap-6">
                <h2 class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                    Developers<br />love it
                </h2>
                <div class="flex items-center gap-3">
                    <div class="flex gap-0.5">
                        {#each Array(5) as _}
                            <Star class="size-5 fill-amber-400 text-amber-400" />
                        {/each}
                    </div>
                    <span class="text-surface-400 text-sm font-medium">From 100+ developers</span>
                </div>
            </div>
            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                {#each testimonials as t}
                    <div class="border-surface-200-800 group rounded-2xl border p-7 transition-all duration-300 hover:border-primary-500/30 hover:shadow-lg hover:shadow-primary-500/[.03]">
                        <div class="mb-4 flex gap-0.5">
                            {#each Array(5) as _}
                                <Star class="size-3.5 fill-amber-400 text-amber-400" />
                            {/each}
                        </div>
                        <p class="text-surface-600 dark:text-surface-400 text-[15px] leading-relaxed">"{t.quote}"</p>
                        <div class="mt-7 flex items-center gap-3">
                            <div class="flex size-11 items-center justify-center rounded-full bg-gradient-to-br from-surface-200 to-surface-100 text-xs font-bold dark:from-surface-700 dark:to-surface-800">
                                {t.initials}
                            </div>
                            <div>
                                <p class="text-sm font-semibold">{t.name}</p>
                                <p class="text-surface-500 text-xs">{t.role}</p>
                            </div>
                        </div>
                    </div>
                {/each}
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section use:reveal id="faq" class="bg-surface-100-900 py-28 lg:py-36">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <h2 class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">FAQ</h2>
            <div class="divide-surface-200-800 mt-14 max-w-3xl divide-y">
                {#each faqs as faq, i}
                    <div>
                        <button
                            onclick={() => toggleFaq(i)}
                            class="group flex w-full items-center justify-between py-6 text-left"
                        >
                            <span class="pr-6 text-lg font-semibold transition-colors group-hover:text-primary-500">{faq.q}</span>
                            <div class="flex size-8 shrink-0 items-center justify-center rounded-full bg-surface-200/60 transition-all duration-300 group-hover:bg-primary-500/10 dark:bg-surface-800/60 {openFaq === i ? 'rotate-180' : ''}">
                                <ChevronDown class="text-surface-500 size-4" />
                            </div>
                        </button>
                        {#if openFaq === i}
                            <p class="text-surface-500 pb-6 text-[15px] leading-relaxed">{faq.a}</p>
                        {/if}
                    </div>
                {/each}
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section use:reveal class="relative overflow-hidden py-32 lg:py-44">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_70%_50%_at_50%_100%,rgba(168,85,247,0.06),transparent)]"></div>
        <div class="relative mx-auto max-w-7xl px-6 text-center lg:px-8">
            <h2 class="cta-heading text-5xl font-extrabold uppercase tracking-tight sm:text-6xl lg:text-8xl xl:text-9xl">
                Start building<br />
                your next project<br />
                <span class="gradient-text">→ today</span>
            </h2>
            <div class="mt-12 flex justify-center gap-4">
                {#if user}
                    <a href="/dashboard" class="preset-filled-primary-500 btn gap-2 px-8 py-3 text-base shadow-lg shadow-primary-500/25">
                        Dashboard <ArrowRight class="size-5" />
                    </a>
                {:else}
                    <a href="/register" class="preset-filled-primary-500 btn gap-2 px-8 py-3 text-base shadow-lg shadow-primary-500/25">
                        Get Started Free <ArrowRight class="size-5" />
                    </a>
                {/if}
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-surface-200-800 border-t">
        <div class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
            <div class="grid gap-12 sm:grid-cols-2 lg:grid-cols-4">
                <div class="lg:col-span-1">
                    <a href="/" class="flex items-center gap-2.5">
                        <AppLogoIcon />
                        <span class="text-lg font-bold">Laravel</span>
                    </a>
                    <p class="text-surface-500 mt-4 text-sm leading-relaxed">A production-ready starter kit for modern Laravel applications with SvelteKit and Skeleton UI.</p>
                </div>
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-widest text-surface-400">Product</h3>
                    <ul class="mt-4 space-y-3">
                        <li><a href="#features" class="text-surface-500 hover:text-surface-900 dark:hover:text-surface-50 text-sm transition-colors">Features</a></li>
                        <li><a href="#stack" class="text-surface-500 hover:text-surface-900 dark:hover:text-surface-50 text-sm transition-colors">Stack</a></li>
                        <li><a href="#faq" class="text-surface-500 hover:text-surface-900 dark:hover:text-surface-50 text-sm transition-colors">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-widest text-surface-400">Resources</h3>
                    <ul class="mt-4 space-y-3">
                        <li><a href="https://laravel.com/docs" class="text-surface-500 hover:text-surface-900 dark:hover:text-surface-50 text-sm transition-colors">Laravel Docs</a></li>
                        <li><a href="https://svelte.dev/docs/kit" class="text-surface-500 hover:text-surface-900 dark:hover:text-surface-50 text-sm transition-colors">SvelteKit Docs</a></li>
                        <li><a href="https://www.skeleton.dev" class="text-surface-500 hover:text-surface-900 dark:hover:text-surface-50 text-sm transition-colors">Skeleton UI</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-widest text-surface-400">Connect</h3>
                    <ul class="mt-4 space-y-3">
                        <li><a href="https://github.com" class="text-surface-500 hover:text-surface-900 dark:hover:text-surface-50 text-sm transition-colors">GitHub</a></li>
                        {#if !user}
                            <li><a href="/login" class="text-surface-500 hover:text-surface-900 dark:hover:text-surface-50 text-sm transition-colors">Log in</a></li>
                            <li><a href="/register" class="text-surface-500 hover:text-surface-900 dark:hover:text-surface-50 text-sm transition-colors">Register</a></li>
                        {/if}
                    </ul>
                </div>
            </div>
            <div class="border-surface-200-800 mt-14 flex flex-col items-center justify-between gap-4 border-t pt-8 sm:flex-row">
                <p class="text-surface-400 text-sm">Built with Laravel, SvelteKit & Skeleton UI</p>
            </div>
        </div>
    </footer>
</div>

<style>
    :global(html) {
        scroll-behavior: smooth;
    }

    .hero-gradient {
        background:
            radial-gradient(ellipse 80% 50% at 15% -10%, rgba(168, 85, 247, 0.18) 0%, transparent 70%),
            radial-gradient(ellipse 60% 40% at 85% 0%, rgba(251, 191, 36, 0.14) 0%, transparent 70%),
            radial-gradient(ellipse 40% 30% at 50% 60%, rgba(99, 102, 241, 0.06) 0%, transparent 70%);
    }

    .glass-nav {
        background: rgba(255, 255, 255, 0.82);
        backdrop-filter: blur(24px) saturate(1.8);
        -webkit-backdrop-filter: blur(24px) saturate(1.8);
    }
    :global([data-mode="dark"]) .glass-nav {
        background: rgba(10, 10, 20, 0.82);
    }

    .gradient-text {
        background: linear-gradient(135deg, #6366f1, #a855f7, #ec4899);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .dark-section {
        background: linear-gradient(160deg, #0c0c18 0%, #150f28 50%, #0c0c18 100%);
    }

    .hero-enter {
        animation: hero-in 0.9s cubic-bezier(0.16, 1, 0.3, 1) both;
    }
    .hero-enter-delay {
        animation: hero-in 0.9s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both;
    }
    @keyframes hero-in {
        from {
            opacity: 0;
            transform: translateY(24px);
        }
    }

    .mockup-wrapper {
        transform: perspective(1400px) rotateY(-10deg) rotateX(4deg);
        transform-style: preserve-3d;
    }
    .mockup-float {
        animation: float 8s ease-in-out infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-14px); }
    }
</style>

<script lang="ts">
    import { page } from '@inertiajs/svelte';
    import type { Snippet } from 'svelte';
    import Heading from '@/components/Heading.svelte';
    import { User, Lock, Palette, ShieldCheck } from 'lucide-svelte';

    let { children }: { children: Snippet } = $props();

    const tabs = [
        { name: 'Profile', href: '/settings/profile', icon: User },
        { name: 'Password', href: '/settings/password', icon: Lock },
        { name: 'Appearance', href: '/settings/appearance', icon: Palette },
        { name: 'Two-Factor Auth', href: '/settings/two-factor', icon: ShieldCheck },
    ];

    const currentPath = $derived(($page as any).url?.split('?')[0] ?? '');
</script>

<div class="space-y-6">
    <Heading title="Settings" description="Manage your profile and account settings." />

    <nav class="border-surface-200-800 flex gap-1 border-b pb-0">
        {#each tabs as tab}
            {@const active = currentPath === tab.href}
            <a
                href={tab.href}
                class="flex items-center gap-2 border-b-2 px-4 py-2 text-sm font-medium transition-colors {active
                    ? 'border-primary-500 text-primary-500'
                    : 'border-transparent text-surface-500 hover:text-surface-700 dark:hover:text-surface-300'}"
            >
                <tab.icon class="size-4" />
                {tab.name}
            </a>
        {/each}
    </nav>

    <div>
        {@render children()}
    </div>
</div>

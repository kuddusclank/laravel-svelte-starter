<script lang="ts">
    import { page } from '$app/stores';
    import type { Snippet } from 'svelte';
    import Heading from '$lib/components/Heading.svelte';
    import { User, Lock, Palette, ShieldCheck } from 'lucide-svelte';

    let { children }: { children: Snippet } = $props();

    const tabs = [
        { name: 'Profile', href: '/settings/profile', icon: User },
        { name: 'Password', href: '/settings/password', icon: Lock },
        { name: 'Appearance', href: '/settings/appearance', icon: Palette },
        { name: 'Two-Factor Auth', href: '/settings/two-factor', icon: ShieldCheck },
    ];

    const currentPath = $derived($page.url.pathname);
</script>

<div class="space-y-6">
    <Heading title="Settings" description="Manage your profile and account settings." />

    <nav class="border-border flex gap-1 border-b pb-3">
        {#each tabs as tab}
            {@const active = currentPath === tab.href}
            <a
                href={tab.href}
                class="flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-colors {active
                    ? 'bg-primary/10 text-primary font-semibold'
                    : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground'}"
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

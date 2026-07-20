<script lang="ts">
    import Breadcrumbs from './Breadcrumbs.svelte';
    import type { BreadcrumbItem } from '$lib/types';
    import { PanelLeftClose, PanelLeftOpen } from 'lucide-svelte';

    let {
        sidebarOpen = true,
        onToggleSidebar,
        breadcrumbs = [],
    }: {
        sidebarOpen?: boolean;
        onToggleSidebar?: () => void;
        breadcrumbs?: BreadcrumbItem[];
    } = $props();
</script>

<header class="bg-card text-card-foreground border-border flex h-16 items-center gap-4 border-b px-6">
    <button
        onclick={onToggleSidebar}
        class="border-border bg-background hover:bg-accent hover:text-accent-foreground cursor-pointer rounded-lg border p-1.5 transition-colors"
        aria-label="Toggle sidebar"
    >
        {#if sidebarOpen}
            <PanelLeftClose class="size-5" />
        {:else}
            <PanelLeftOpen class="size-5" />
        {/if}
    </button>
    <Breadcrumbs items={breadcrumbs} />
</header>

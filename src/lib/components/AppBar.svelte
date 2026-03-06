<script lang="ts">
    import Breadcrumbs from './Breadcrumbs.svelte';
    import type { BreadcrumbItem } from '$lib/types';
    import { PanelLeftClose, PanelLeftOpen } from 'lucide-svelte';

    let { sidebarOpen = true, onToggleSidebar, breadcrumbs = [] }: {
        sidebarOpen?: boolean;
        onToggleSidebar?: () => void;
        breadcrumbs?: BreadcrumbItem[];
    } = $props();
</script>

<header class="bg-surface-50-950 flex h-16 items-center gap-4 border-b border-surface-200/60 dark:border-surface-800/60 px-6">
    <button onclick={onToggleSidebar} class="preset-tonal rounded-token p-1.5" aria-label="Toggle sidebar">
        {#if sidebarOpen}
            <PanelLeftClose class="size-5" />
        {:else}
            <PanelLeftOpen class="size-5" />
        {/if}
    </button>
    <Breadcrumbs items={breadcrumbs} />
</header>

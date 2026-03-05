<script lang="ts">
    import { page } from '@inertiajs/svelte';
    import Breadcrumbs from './Breadcrumbs.svelte';
    import type { BreadcrumbItem } from '@/types';
    import { PanelLeftClose, PanelLeftOpen } from 'lucide-svelte';

    let { sidebarOpen = true, onToggleSidebar }: { sidebarOpen?: boolean; onToggleSidebar?: () => void } = $props();

    const breadcrumbs: BreadcrumbItem[] = $derived(($page.props as any).breadcrumbs ?? []);
</script>

<header class="bg-surface-50-950 border-surface-200-800 flex h-16 items-center gap-4 border-b px-6">
    <button onclick={onToggleSidebar} class="preset-tonal rounded-token p-1.5" aria-label="Toggle sidebar">
        {#if sidebarOpen}
            <PanelLeftClose class="size-5" />
        {:else}
            <PanelLeftOpen class="size-5" />
        {/if}
    </button>
    <Breadcrumbs items={breadcrumbs} />
</header>

<script lang="ts">
    import { page } from '@inertiajs/svelte';
    import type { NavItem } from '@/types';

    let { items = [] }: { items?: NavItem[] } = $props();

    const currentPath = $derived(($page as any).url?.split('?')[0] ?? '');
</script>

<ul class="space-y-1">
    {#each items as item}
        {@const active = currentPath.startsWith(item.href)}
        <li>
            <a
                href={item.href}
                class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors {active
                    ? 'preset-filled-primary-500'
                    : 'text-surface-600 dark:text-surface-400 hover:bg-surface-200-800'}"
            >
                {#if item.icon}
                    <item.icon class="size-5" />
                {/if}
                {item.title}
            </a>
        </li>
    {/each}
</ul>

<script lang="ts">
    import type { BreadcrumbItem } from '$lib/types';
    import { ChevronRight } from 'lucide-svelte';

    let { items = [] }: { items?: BreadcrumbItem[] } = $props();
</script>

{#if items.length > 0}
    <nav aria-label="Breadcrumb">
        <ol class="flex items-center gap-1.5 text-sm">
            {#each items as item, i}
                {#if i > 0}
                    <li>
                        <ChevronRight class="text-surface-400 size-3.5" />
                    </li>
                {/if}
                <li>
                    {#if i < items.length - 1}
                        <a href={item.href} class="text-surface-500 hover:text-surface-700 dark:hover:text-surface-300">
                            {item.title}
                        </a>
                    {:else}
                        <span class="text-surface-900 dark:text-surface-50 font-medium">{item.title}</span>
                    {/if}
                </li>
            {/each}
        </ol>
    </nav>
{/if}

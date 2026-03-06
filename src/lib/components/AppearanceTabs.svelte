<script lang="ts">
    import type { Appearance } from '$lib/types';
    import { createAppearanceState } from '$lib/utils/theme.svelte';
    import { Sun, Moon, Monitor } from 'lucide-svelte';

    const state = createAppearanceState(
        (typeof document !== 'undefined'
            ? document.cookie.match(/appearance=(\w+)/)?.[1]
            : 'system') as Appearance ?? 'system'
    );

    const options: { value: Appearance; label: string; icon: typeof Sun }[] = [
        { value: 'light', label: 'Light', icon: Sun },
        { value: 'dark', label: 'Dark', icon: Moon },
        { value: 'system', label: 'System', icon: Monitor },
    ];
</script>

<div class="inline-flex gap-1 rounded-lg bg-surface-200/50 dark:bg-surface-800/50 p-1">
    {#each options as option}
        <button
            onclick={() => state.update(option.value)}
            class="flex items-center gap-2 rounded-md px-4 py-2 text-sm font-medium transition-colors {state.appearance === option.value
                ? 'bg-surface-50 dark:bg-surface-900 shadow-sm'
                : 'text-surface-500 hover:text-surface-700 dark:hover:text-surface-300'}"
        >
            <option.icon class="size-4" />
            {option.label}
        </button>
    {/each}
</div>

<script lang="ts">
    import type { Snippet } from 'svelte';
    import AppLogoIcon from '@/components/AppLogoIcon.svelte';
    import PlaceholderPattern from '@/components/PlaceholderPattern.svelte';

    let { children, title = '', description = '' }: { children: Snippet; title?: string; description?: string } = $props();
</script>

<div class="flex min-h-screen">
    <div class="bg-surface-100-900 relative hidden w-1/2 items-center justify-center lg:flex">
        <PlaceholderPattern />
    </div>
    <div class="bg-surface-50-950 flex w-full items-center justify-center p-6 lg:w-1/2 sm:p-12">
        <div class="w-full max-w-md">
            <div class="mb-8 flex flex-col items-center gap-4">
                <a href="/">
                    <AppLogoIcon />
                </a>
                {#if title}
                    <h1 class="h3 font-bold">{title}</h1>
                {/if}
                {#if description}
                    <p class="text-surface-500 text-sm">{description}</p>
                {/if}
            </div>
            {@render children()}
        </div>
    </div>
</div>

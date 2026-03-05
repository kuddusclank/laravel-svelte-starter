<script lang="ts">
    import { page } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';
    import AppLogoIcon from '@/components/AppLogoIcon.svelte';

    const user = $derived(($page.props as any).auth?.user);
</script>

<AppHead title="Welcome" />

<div class="bg-surface-50-950 flex min-h-screen flex-col items-center justify-center p-6">
    <div class="w-full max-w-2xl text-center">
        <div class="mb-8 flex justify-center">
            <AppLogoIcon />
        </div>

        <h1 class="h1 font-bold">Welcome to Laravel</h1>
        <p class="text-surface-500 mx-auto mt-4 max-w-lg text-lg">
            A starter kit powered by Laravel, Inertia.js, Svelte 5, and Skeleton UI.
        </p>

        <div class="mt-8 flex justify-center gap-4">
            {#if user}
                <a href="/dashboard" class="preset-filled-primary-500 btn">Dashboard</a>
            {:else}
                <a href="/login" class="preset-filled-primary-500 btn">Log in</a>
                <a href="/register" class="preset-tonal btn">Register</a>
            {/if}
        </div>
    </div>
</div>

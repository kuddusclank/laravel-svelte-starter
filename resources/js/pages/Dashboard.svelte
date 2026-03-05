<script lang="ts" module>
    import { layout } from '@/layouts/AppLayout.svelte';
    export { layout };
</script>

<script lang="ts">
    import { page } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';
    import PlaceholderPattern from '@/components/PlaceholderPattern.svelte';
</script>

<AppHead title="Dashboard" />

<div class="space-y-6">
    <h2 class="h3 font-bold">Dashboard</h2>

    <div class="preset-outlined-surface-200-800 relative min-h-[300px] overflow-hidden rounded-xl">
        <PlaceholderPattern />
        <div class="relative flex h-full min-h-[300px] items-center justify-center">
            <p class="text-surface-500">You're logged in!</p>
        </div>
    </div>
</div>

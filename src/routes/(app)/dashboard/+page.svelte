<script lang="ts">
    import AppHead from '$lib/components/AppHead.svelte';
    import PlaceholderPattern from '$lib/components/PlaceholderPattern.svelte';
</script>

<AppHead title="Dashboard" />

<div class="space-y-6">
    <h2 class="h3 font-bold">Dashboard</h2>

    <div class="relative min-h-[300px] overflow-hidden rounded-2xl border border-surface-200/60 dark:border-surface-800/60 shadow-sm">
        <PlaceholderPattern />
        <div class="relative flex h-full min-h-[300px] items-center justify-center">
            <p class="text-surface-500">You're logged in!</p>
        </div>
    </div>
</div>

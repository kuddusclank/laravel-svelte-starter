<script lang="ts">
    import AppHead from '$lib/components/AppHead.svelte';
    import AppearanceTabs from '$lib/components/AppearanceTabs.svelte';
</script>

<AppHead title="Appearance" />

<section>
    <header>
        <h3 class="text-foreground text-lg font-semibold tracking-tight">Appearance</h3>
        <p class="text-muted-foreground mt-1 text-sm">Choose how the application looks for you.</p>
    </header>

    <div class="mt-4">
        <AppearanceTabs />
    </div>
</section>

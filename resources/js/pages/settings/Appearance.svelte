<script lang="ts" module>
    import type { LayoutResolver } from '@inertiajs/svelte';
    import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.svelte';
    import SettingsLayout from '@/layouts/settings/Layout.svelte';
    export const layout: LayoutResolver = (h) => [AppSidebarLayout, SettingsLayout, h];
</script>

<script lang="ts">
    import AppHead from '@/components/AppHead.svelte';
    import AppearanceTabs from '@/components/AppearanceTabs.svelte';
</script>

<AppHead title="Appearance" />

<section>
    <header>
        <h3 class="h4 font-bold">Appearance</h3>
        <p class="text-surface-500 mt-1 text-sm">Choose how the application looks for you.</p>
    </header>

    <div class="mt-4">
        <AppearanceTabs />
    </div>
</section>

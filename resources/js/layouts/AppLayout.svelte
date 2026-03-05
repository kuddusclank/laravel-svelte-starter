<script lang="ts" module>
    import type { LayoutResolver } from '@inertiajs/svelte';
    import AppSidebarLayout from './app/AppSidebarLayout.svelte';
    export const layout: LayoutResolver = (h) => [AppSidebarLayout, h];
</script>

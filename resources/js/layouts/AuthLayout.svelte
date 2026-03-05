<script lang="ts" module>
    import type { LayoutResolver } from '@inertiajs/svelte';
    import AuthSimpleLayout from './auth/AuthSimpleLayout.svelte';
    export const layout: LayoutResolver = (h) => [AuthSimpleLayout, h];
</script>

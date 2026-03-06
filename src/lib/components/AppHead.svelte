<script lang="ts">
    let { title = '' }: { title?: string } = $props();
</script>

<svelte:head>
    {#if title}
        <title>{title}</title>
    {/if}
</svelte:head>

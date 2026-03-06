<script lang="ts">
    let { title, description = '' }: { title: string; description?: string } = $props();
</script>

<div>
    <h2 class="h3 font-bold">{title}</h2>
    {#if description}
        <p class="text-surface-500 mt-1 text-sm">{description}</p>
    {/if}
</div>

<script lang="ts">
    let { message = '' }: { message?: string } = $props();
</script>

{#if message}
    <p class="text-error-500 text-sm">{message}</p>
{/if}

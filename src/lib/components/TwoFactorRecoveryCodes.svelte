<script lang="ts">
    let { codes = [] }: { codes?: string[] } = $props();
</script>

{#if codes.length > 0}
    <div class="space-y-3">
        <p class="text-surface-500 text-sm">
            Store these recovery codes in a secure location. They can be used to access your account if you lose your two-factor authentication device.
        </p>
        <div class="bg-surface-100-900 rounded-lg p-4">
            <ul class="grid grid-cols-2 gap-1 font-mono text-sm">
                {#each codes as code}
                    <li>{code}</li>
                {/each}
            </ul>
        </div>
    </div>
{/if}

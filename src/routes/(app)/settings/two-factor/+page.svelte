<script lang="ts">
    import { enhance } from '$app/forms';
    import { invalidateAll } from '$app/navigation';
    import AppHead from '$lib/components/AppHead.svelte';
    import TwoFactorSetupModal from '$lib/components/TwoFactorSetupModal.svelte';
    import TwoFactorRecoveryCodes from '$lib/components/TwoFactorRecoveryCodes.svelte';

    let { data, form } = $props();

    let showSetup = $state(false);
    let qrSvg = $state('');
    let secretKey = $state('');
    let recoveryCodes = $state<string[]>([]);
    let enabling = $state(false);
    let showCodes = $state(false);

    // React to form action results
    $effect(() => {
        if (form?.showSetup) {
            qrSvg = form.qrSvg ?? '';
            secretKey = form.secretKey ?? '';
            showSetup = true;
        }
        if (form?.confirmed || form?.disabled) {
            showSetup = false;
            invalidateAll();
        }
        if (form?.recoveryCodes) {
            recoveryCodes = form.recoveryCodes;
            showCodes = true;
        }
    });
</script>

<AppHead title="Two-Factor Authentication" />

<section class="space-y-6">
    <header>
        <h3 class="text-foreground text-lg font-semibold tracking-tight">Two-Factor Authentication</h3>
        <p class="text-muted-foreground mt-1 text-sm">
            Add an extra layer of security to your account using two-factor authentication.
        </p>
    </header>

    {#if data.twoFactorEnabled && data.twoFactorConfirmed}
        <div class="rounded-lg border border-emerald-500/20 bg-emerald-500/10 p-3 text-sm text-emerald-500">
            Two-factor authentication is enabled.
        </div>

        <div class="flex flex-wrap gap-3">
            {#if !showCodes}
                <form method="POST" action="?/showRecoveryCodes" use:enhance>
                    <button
                        type="submit"
                        class="border-border bg-background text-foreground hover:bg-accent hover:text-accent-foreground inline-flex h-9 cursor-pointer items-center justify-center rounded-lg border px-4 py-2 text-sm font-medium transition-colors"
                    >
                        Show Recovery Codes
                    </button>
                </form>
            {:else}
                <form method="POST" action="?/regenerateRecoveryCodes" use:enhance>
                    <button
                        type="submit"
                        class="border-border bg-background text-foreground hover:bg-accent hover:text-accent-foreground inline-flex h-9 cursor-pointer items-center justify-center rounded-lg border px-4 py-2 text-sm font-medium transition-colors"
                    >
                        Regenerate Recovery Codes
                    </button>
                </form>
            {/if}
            <form
                method="POST"
                action="?/disable"
                use:enhance={() => {
                    return async ({ update }) => {
                        recoveryCodes = [];
                        showCodes = false;
                        await update();
                        await invalidateAll();
                    };
                }}
            >
                <button
                    type="submit"
                    class="bg-destructive text-destructive-foreground hover:bg-destructive/90 inline-flex h-9 cursor-pointer items-center justify-center rounded-lg px-4 py-2 text-sm font-medium shadow-xs transition-colors"
                >
                    Disable
                </button>
            </form>
        </div>

        {#if showCodes}
            <TwoFactorRecoveryCodes codes={recoveryCodes} />
        {/if}
    {:else if data.twoFactorEnabled && !data.twoFactorConfirmed}
        <div class="rounded-lg border border-amber-500/20 bg-amber-500/10 p-3 text-sm text-amber-500">
            Two-factor authentication is enabled but not yet confirmed. Please complete setup.
        </div>
        <form
            method="POST"
            action="?/enable"
            use:enhance={() => {
                enabling = true;
                return async ({ update }) => {
                    enabling = false;
                    await update();
                };
            }}
        >
            <button
                type="submit"
                class="bg-primary text-primary-foreground hover:bg-primary/90 focus-visible:ring-ring inline-flex h-9 cursor-pointer items-center justify-center rounded-lg px-4 py-2 text-sm font-medium shadow-xs focus-visible:ring-1 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
                disabled={enabling}
            >
                Complete Setup
            </button>
        </form>
    {:else}
        <form
            method="POST"
            action="?/enable"
            use:enhance={() => {
                enabling = true;
                return async ({ update }) => {
                    enabling = false;
                    await update();
                };
            }}
        >
            <button
                type="submit"
                class="bg-primary text-primary-foreground hover:bg-primary/90 focus-visible:ring-ring inline-flex h-9 cursor-pointer items-center justify-center rounded-lg px-4 py-2 text-sm font-medium shadow-xs focus-visible:ring-1 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
                disabled={enabling}
            >
                {enabling ? 'Enabling...' : 'Enable Two-Factor Authentication'}
            </button>
        </form>
    {/if}

    <TwoFactorSetupModal
        open={showSetup}
        {qrSvg}
        {secretKey}
        onClose={() => {
            showSetup = false;
            invalidateAll();
        }}
    />
</section>

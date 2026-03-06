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
        <h3 class="h4 font-bold">Two-Factor Authentication</h3>
        <p class="text-surface-500 mt-1 text-sm">
            Add an extra layer of security to your account using two-factor authentication.
        </p>
    </header>

    {#if data.twoFactorEnabled && data.twoFactorConfirmed}
        <div class="preset-filled-success-500 rounded-lg p-3 text-sm">
            Two-factor authentication is enabled.
        </div>

        <div class="flex flex-wrap gap-3">
            {#if !showCodes}
                <form method="POST" action="?/showRecoveryCodes" use:enhance>
                    <button type="submit" class="preset-tonal btn">
                        Show Recovery Codes
                    </button>
                </form>
            {:else}
                <form method="POST" action="?/regenerateRecoveryCodes" use:enhance>
                    <button type="submit" class="preset-tonal btn">
                        Regenerate Recovery Codes
                    </button>
                </form>
            {/if}
            <form method="POST" action="?/disable" use:enhance={() => {
                return async ({ update }) => {
                    recoveryCodes = [];
                    showCodes = false;
                    await update();
                    await invalidateAll();
                };
            }}>
                <button type="submit" class="preset-filled-error-500 btn">
                    Disable
                </button>
            </form>
        </div>

        {#if showCodes}
            <TwoFactorRecoveryCodes codes={recoveryCodes} />
        {/if}
    {:else if data.twoFactorEnabled && !data.twoFactorConfirmed}
        <div class="preset-filled-warning-500 rounded-lg p-3 text-sm">
            Two-factor authentication is enabled but not yet confirmed. Please complete setup.
        </div>
        <form method="POST" action="?/enable" use:enhance={() => {
            enabling = true;
            return async ({ update }) => {
                enabling = false;
                await update();
            };
        }}>
            <button type="submit" class="preset-filled-primary-500 btn" disabled={enabling}>
                Complete Setup
            </button>
        </form>
    {:else}
        <form method="POST" action="?/enable" use:enhance={() => {
            enabling = true;
            return async ({ update }) => {
                enabling = false;
                await update();
            };
        }}>
            <button type="submit" class="preset-filled-primary-500 btn" disabled={enabling}>
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

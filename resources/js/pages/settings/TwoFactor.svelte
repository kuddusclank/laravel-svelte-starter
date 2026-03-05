<script lang="ts" module>
    import type { LayoutResolver } from '@inertiajs/svelte';
    import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.svelte';
    import SettingsLayout from '@/layouts/settings/Layout.svelte';
    export const layout: LayoutResolver = (h) => [AppSidebarLayout, SettingsLayout, h];
</script>

<script lang="ts">
    import { router, page } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';
    import TwoFactorSetupModal from '@/components/TwoFactorSetupModal.svelte';
    import TwoFactorRecoveryCodes from '@/components/TwoFactorRecoveryCodes.svelte';

    let { twoFactorEnabled = false, twoFactorConfirmed = false }: {
        twoFactorEnabled?: boolean;
        twoFactorConfirmed?: boolean;
    } = $props();

    let showSetup = $state(false);
    let qrSvg = $state('');
    let secretKey = $state('');
    let recoveryCodes = $state<string[]>([]);
    let enabling = $state(false);
    let showCodes = $state(false);

    function enableTwoFactor() {
        enabling = true;
        router.post('/user/two-factor-authentication', {}, {
            preserveScroll: true,
            onSuccess: async () => {
                // Fetch QR code and secret
                const qrRes = await fetch('/user/two-factor-qr-code');
                const qrData = await qrRes.json();
                qrSvg = qrData.svg;

                const secretRes = await fetch('/user/two-factor-secret-key');
                const secretData = await secretRes.json();
                secretKey = secretData.secretKey;

                showSetup = true;
            },
            onFinish: () => {
                enabling = false;
            },
        });
    }

    function disableTwoFactor() {
        router.delete('/user/two-factor-authentication', {
            preserveScroll: true,
            onSuccess: () => {
                recoveryCodes = [];
                showCodes = false;
            },
        });
    }

    async function showRecoveryCodes() {
        const res = await fetch('/user/two-factor-recovery-codes');
        const data = await res.json();
        recoveryCodes = data;
        showCodes = true;
    }

    function regenerateRecoveryCodes() {
        router.post('/user/two-factor-recovery-codes', {}, {
            preserveScroll: true,
            onSuccess: async () => {
                await showRecoveryCodes();
            },
        });
    }
</script>

<AppHead title="Two-Factor Authentication" />

<section class="space-y-6">
    <header>
        <h3 class="h4 font-bold">Two-Factor Authentication</h3>
        <p class="text-surface-500 mt-1 text-sm">
            Add an extra layer of security to your account using two-factor authentication.
        </p>
    </header>

    {#if twoFactorEnabled && twoFactorConfirmed}
        <div class="preset-filled-success-500 rounded-lg p-3 text-sm">
            Two-factor authentication is enabled.
        </div>

        <div class="flex flex-wrap gap-3">
            {#if !showCodes}
                <button class="preset-tonal btn" onclick={showRecoveryCodes}>
                    Show Recovery Codes
                </button>
            {:else}
                <button class="preset-tonal btn" onclick={regenerateRecoveryCodes}>
                    Regenerate Recovery Codes
                </button>
            {/if}
            <button class="preset-filled-error-500 btn" onclick={disableTwoFactor}>
                Disable
            </button>
        </div>

        {#if showCodes}
            <TwoFactorRecoveryCodes codes={recoveryCodes} />
        {/if}
    {:else if twoFactorEnabled && !twoFactorConfirmed}
        <div class="preset-filled-warning-500 rounded-lg p-3 text-sm">
            Two-factor authentication is enabled but not yet confirmed. Please complete setup.
        </div>
        <button class="preset-filled-primary-500 btn" onclick={enableTwoFactor} disabled={enabling}>
            Complete Setup
        </button>
    {:else}
        <button class="preset-filled-primary-500 btn" onclick={enableTwoFactor} disabled={enabling}>
            {enabling ? 'Enabling...' : 'Enable Two-Factor Authentication'}
        </button>
    {/if}

    <TwoFactorSetupModal
        open={showSetup}
        {qrSvg}
        {secretKey}
        onClose={() => {
            showSetup = false;
            // Reload page to get updated state
            router.reload();
        }}
    />
</section>

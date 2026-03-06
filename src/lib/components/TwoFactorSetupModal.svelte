<script lang="ts">
    import InputError from './InputError.svelte';

    let { open = false, qrSvg = '', secretKey = '', onClose }: {
        open?: boolean;
        qrSvg?: string;
        secretKey?: string;
        onClose?: () => void;
    } = $props();

    let code = $state('');
    let error = $state('');
    let confirming = $state(false);

    async function confirm() {
        confirming = true;
        error = '';

        const res = await fetch('/settings/two-factor?/confirm', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `code=${encodeURIComponent(code)}`,
        });

        if (res.ok || res.status === 303) {
            onClose?.();
            code = '';
            error = '';
            window.location.reload();
        } else {
            error = 'Invalid code. Please try again.';
        }

        confirming = false;
    }
</script>

{#if open}
    <!-- svelte-ignore a11y_no_static_element_interactions -->
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm" onclick={onClose} onkeydown={() => {}}>
        <!-- svelte-ignore a11y_no_static_element_interactions -->
        <div class="bg-surface-50 dark:bg-surface-900 w-full max-w-md rounded-2xl border border-surface-200/60 dark:border-surface-800/60 p-6 shadow-2xl" onclick={(e) => e.stopPropagation()} onkeydown={() => {}}>
            <h3 class="h4 font-bold">Set Up Two-Factor Authentication</h3>
            <p class="text-surface-500 mt-2 text-sm">
                Scan the QR code below with your authenticator app, then enter the verification code.
            </p>

            {#if qrSvg}
                <div class="my-4 flex justify-center">
                    {@html qrSvg}
                </div>
            {/if}

            {#if secretKey}
                <div class="bg-surface-100-900 mb-4 rounded-lg p-3 text-center">
                    <p class="text-surface-500 text-xs">Manual entry key:</p>
                    <p class="font-mono text-sm font-bold">{secretKey}</p>
                </div>
            {/if}

            <div class="space-y-4">
                <div>
                    <label for="2fa-code" class="label text-sm font-medium">Verification Code</label>
                    <input
                        id="2fa-code"
                        type="text"
                        bind:value={code}
                        class="input mt-1"
                        placeholder="Enter 6-digit code"
                        maxlength={6}
                    />
                    <InputError message={error} />
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" class="preset-tonal btn" onclick={onClose}>Cancel</button>
                    <button class="preset-filled-primary-500 btn" onclick={confirm} disabled={confirming || code.length < 6}>
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
{/if}

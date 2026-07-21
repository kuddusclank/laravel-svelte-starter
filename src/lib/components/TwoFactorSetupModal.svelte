<script lang="ts">
    import InputError from './InputError.svelte';

    let {
        open = false,
        qrSvg = '',
        secretKey = '',
        onClose,
    }: {
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
    <div
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs"
        onclick={onClose}
        onkeydown={() => {}}
    >
        <!-- svelte-ignore a11y_no_static_element_interactions -->
        <div
            class="bg-card border-border w-full max-w-md rounded-2xl border p-6 shadow-2xl"
            onclick={(e) => e.stopPropagation()}
            onkeydown={() => {}}
        >
            <h3 class="text-foreground text-lg font-semibold tracking-tight">Set Up Two-Factor Authentication</h3>
            <p class="text-muted-foreground mt-2 text-sm">
                Scan the QR code below with your authenticator app, then enter the verification code.
            </p>

            {#if qrSvg}
                <div class="my-4 flex justify-center">
                    {@html qrSvg}
                </div>
            {/if}

            {#if secretKey}
                <div class="bg-secondary mb-4 rounded-lg p-3 text-center">
                    <p class="text-muted-foreground text-xs">Manual entry key:</p>
                    <p class="text-foreground font-mono text-sm font-bold">{secretKey}</p>
                </div>
            {/if}

            <div class="space-y-4">
                <div>
                    <label for="2fa-code" class="text-foreground text-sm leading-none font-medium"
                        >Verification Code</label
                    >
                    <input
                        id="2fa-code"
                        type="text"
                        bind:value={code}
                        class="border-border bg-background placeholder:text-muted-foreground focus-visible:ring-ring mt-1.5 flex h-9 w-full rounded-md border px-3 py-1.5 text-sm shadow-xs transition-colors focus-visible:ring-1 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                        placeholder="Enter 6-digit code"
                        maxlength={6}
                    />
                    <InputError message={error} />
                </div>

                <div class="flex justify-end gap-3">
                    <button
                        type="button"
                        class="border-border bg-background text-foreground hover:bg-accent hover:text-accent-foreground inline-flex h-9 cursor-pointer items-center justify-center rounded-lg border px-4 py-2 text-sm font-medium transition-colors"
                        onclick={onClose}>Cancel</button
                    >
                    <button
                        class="bg-primary text-primary-foreground hover:bg-primary/90 focus-visible:ring-ring inline-flex h-9 cursor-pointer items-center justify-center rounded-lg px-4 py-2 text-sm font-medium shadow-xs focus-visible:ring-1 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
                        onclick={confirm}
                        disabled={confirming || code.length < 6}
                    >
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
{/if}

<script lang="ts">
    import { enhance } from '$app/forms';
    import AppHead from '$lib/components/AppHead.svelte';
    import InputError from '$lib/components/InputError.svelte';

    let { form } = $props();

    let useRecovery = $state(false);
    let processing = $state(false);

    function toggleMode() {
        useRecovery = !useRecovery;
    }
</script>

<AppHead title="Two-Factor Challenge" />

<div class="space-y-6">
    <div class="text-center">
        <h1 class="text-foreground text-2xl font-bold tracking-tight">Two-factor authentication</h1>
        <p class="text-muted-foreground mt-1.5 text-sm">
            {#if useRecovery}
                Enter one of your emergency recovery codes.
            {:else}
                Enter the authentication code from your authenticator app.
            {/if}
        </p>
    </div>

    <form
        method="POST"
        use:enhance={() => {
            processing = true;
            return async ({ update }) => {
                processing = false;
                await update();
            };
        }}
        class="space-y-4"
    >
        {#if useRecovery}
            <div>
                <label for="recovery_code" class="text-foreground text-sm leading-none font-medium">Recovery Code</label
                >
                <input
                    id="recovery_code"
                    name="recovery_code"
                    type="text"
                    class="border-border bg-background placeholder:text-muted-foreground focus-visible:ring-ring mt-1.5 flex h-9 w-full rounded-md border px-3 py-1.5 text-sm shadow-xs transition-colors focus-visible:ring-1 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                    placeholder="Recovery code"
                    required
                    autofocus
                />
                <InputError message={form?.errors?.recovery_code} />
            </div>
        {:else}
            <div>
                <label for="code" class="text-foreground text-sm leading-none font-medium">Code</label>
                <input
                    id="code"
                    name="code"
                    type="text"
                    inputmode="numeric"
                    class="border-border bg-background placeholder:text-muted-foreground focus-visible:ring-ring mt-1.5 flex h-9 w-full rounded-md border px-3 py-1.5 text-sm shadow-xs transition-colors focus-visible:ring-1 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                    placeholder="6-digit code"
                    maxlength={6}
                    required
                    autofocus
                />
                <InputError message={form?.errors?.code} />
            </div>
        {/if}

        <button
            type="submit"
            class="bg-primary text-primary-foreground hover:bg-primary/90 focus-visible:ring-ring inline-flex h-9 w-full cursor-pointer items-center justify-center rounded-lg px-4 py-2 text-sm font-medium shadow-xs focus-visible:ring-1 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
            disabled={processing}
        >
            {processing ? 'Verifying...' : 'Verify'}
        </button>

        <button
            type="button"
            class="text-muted-foreground hover:text-foreground mt-4 w-full cursor-pointer text-center text-sm transition-colors"
            onclick={toggleMode}
        >
            {#if useRecovery}
                Use authentication code
            {:else}
                Use recovery code
            {/if}
        </button>
    </form>
</div>

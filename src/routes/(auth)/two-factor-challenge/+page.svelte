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
        <h1 class="h3 font-bold">Two-factor authentication</h1>
        <p class="text-surface-500 mt-1 text-sm">
            {#if useRecovery}
                Enter one of your emergency recovery codes.
            {:else}
                Enter the authentication code from your authenticator app.
            {/if}
        </p>
    </div>

    <form method="POST" use:enhance={() => {
        processing = true;
        return async ({ update }) => {
            processing = false;
            await update();
        };
    }} class="space-y-4">
        {#if useRecovery}
            <div>
                <label for="recovery_code" class="label text-sm font-medium">Recovery Code</label>
                <input id="recovery_code" name="recovery_code" type="text" class="input mt-1" placeholder="Recovery code" required autofocus />
                <InputError message={form?.errors?.recovery_code} />
            </div>
        {:else}
            <div>
                <label for="code" class="label text-sm font-medium">Code</label>
                <input id="code" name="code" type="text" inputmode="numeric" class="input mt-1" placeholder="6-digit code" maxlength={6} required autofocus />
                <InputError message={form?.errors?.code} />
            </div>
        {/if}

        <button type="submit" class="preset-filled-primary-500 btn w-full shadow-lg shadow-primary-500/20" disabled={processing}>
            {processing ? 'Verifying...' : 'Verify'}
        </button>

        <button type="button" class="text-surface-500 w-full text-center text-sm hover:underline" onclick={toggleMode}>
            {#if useRecovery}
                Use authentication code
            {:else}
                Use recovery code
            {/if}
        </button>
    </form>
</div>

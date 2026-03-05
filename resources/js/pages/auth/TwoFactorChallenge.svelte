<script lang="ts" module>
    import { layout } from '@/layouts/AuthLayout.svelte';
    export { layout };
</script>

<script lang="ts">
    import { useForm } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';
    import InputError from '@/components/InputError.svelte';

    let useRecovery = $state(false);

    const form = useForm({
        code: '',
        recovery_code: '',
    });

    function submit(e: SubmitEvent) {
        e.preventDefault();
        $form.post('/two-factor-challenge');
    }

    function toggleMode() {
        useRecovery = !useRecovery;
        $form.reset();
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

    <form onsubmit={submit} class="space-y-4">
        {#if useRecovery}
            <div>
                <label for="recovery_code" class="label text-sm font-medium">Recovery Code</label>
                <input id="recovery_code" type="text" bind:value={$form.recovery_code} class="input mt-1" placeholder="Recovery code" required autofocus />
                <InputError message={$form.errors.recovery_code} />
            </div>
        {:else}
            <div>
                <label for="code" class="label text-sm font-medium">Code</label>
                <input id="code" type="text" inputmode="numeric" bind:value={$form.code} class="input mt-1" placeholder="6-digit code" maxlength={6} required autofocus />
                <InputError message={$form.errors.code} />
            </div>
        {/if}

        <button type="submit" class="preset-filled-primary-500 btn w-full" disabled={$form.processing}>
            {$form.processing ? 'Verifying...' : 'Verify'}
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

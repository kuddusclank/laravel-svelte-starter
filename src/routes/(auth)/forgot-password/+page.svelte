<script lang="ts">
    import { enhance } from '$app/forms';
    import AppHead from '$lib/components/AppHead.svelte';
    import InputError from '$lib/components/InputError.svelte';
    import TextLink from '$lib/components/TextLink.svelte';

    let { form } = $props();

    let processing = $state(false);
</script>

<AppHead title="Forgot Password" />

<div class="space-y-6">
    <div class="text-center">
        <h1 class="h3 font-bold">Forgot password?</h1>
        <p class="text-surface-500 mt-1 text-sm">Enter your email and we'll send you a reset link</p>
    </div>

    {#if form?.status}
        <div class="preset-filled-success-500 rounded-lg p-3 text-center text-sm">{form.status}</div>
    {/if}

    <form method="POST" use:enhance={() => {
        processing = true;
        return async ({ update }) => {
            processing = false;
            await update();
        };
    }} class="space-y-4">
        <div>
            <label for="email" class="label text-sm font-medium">Email</label>
            <input id="email" name="email" type="email" value={form?.email ?? ''} class="input mt-1" placeholder="email@example.com" required autofocus />
            <InputError message={form?.errors?.email} />
        </div>

        <button type="submit" class="preset-filled-primary-500 btn w-full shadow-lg shadow-primary-500/20" disabled={processing}>
            {processing ? 'Sending...' : 'Send reset link'}
        </button>

        <p class="text-surface-500 text-center text-sm">
            <TextLink href="/login">Back to login</TextLink>
        </p>
    </form>
</div>

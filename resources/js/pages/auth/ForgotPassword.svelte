<script lang="ts" module>
    import { layout } from '@/layouts/AuthLayout.svelte';
    export { layout };
</script>

<script lang="ts">
    import { useForm } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';
    import InputError from '@/components/InputError.svelte';
    import TextLink from '@/components/TextLink.svelte';

    let { status = '' }: { status?: string } = $props();

    const form = useForm({ email: '' });

    function submit(e: SubmitEvent) {
        e.preventDefault();
        $form.post('/forgot-password');
    }
</script>

<AppHead title="Forgot Password" />

<div class="space-y-6">
    <div class="text-center">
        <h1 class="h3 font-bold">Forgot password?</h1>
        <p class="text-surface-500 mt-1 text-sm">Enter your email and we'll send you a reset link</p>
    </div>

    {#if status}
        <div class="preset-filled-success-500 rounded-lg p-3 text-center text-sm">{status}</div>
    {/if}

    <form onsubmit={submit} class="space-y-4">
        <div>
            <label for="email" class="label text-sm font-medium">Email</label>
            <input id="email" type="email" bind:value={$form.email} class="input mt-1" placeholder="email@example.com" required autofocus />
            <InputError message={$form.errors.email} />
        </div>

        <button type="submit" class="preset-filled-primary-500 btn w-full" disabled={$form.processing}>
            {$form.processing ? 'Sending...' : 'Send reset link'}
        </button>

        <p class="text-surface-500 text-center text-sm">
            <TextLink href="/login">Back to login</TextLink>
        </p>
    </form>
</div>

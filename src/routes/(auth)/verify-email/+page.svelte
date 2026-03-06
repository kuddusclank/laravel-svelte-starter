<script lang="ts">
    import { enhance } from '$app/forms';
    import AppHead from '$lib/components/AppHead.svelte';

    let { form } = $props();

    let processing = $state(false);
</script>

<AppHead title="Verify Email" />

<div class="space-y-6">
    <div class="text-center">
        <h1 class="h3 font-bold">Verify your email</h1>
        <p class="text-surface-500 mt-1 text-sm">
            We've sent a verification link to your email address. Please check your inbox and click the link to verify.
        </p>
    </div>

    {#if form?.status === 'verification-link-sent'}
        <div class="preset-filled-success-500 rounded-lg p-3 text-center text-sm">
            A new verification link has been sent to your email address.
        </div>
    {/if}

    <div class="flex items-center justify-between">
        <form method="POST" action="?/resend" use:enhance={() => {
            processing = true;
            return async ({ update }) => {
                processing = false;
                await update();
            };
        }}>
            <button type="submit" class="preset-filled-primary-500 btn shadow-lg shadow-primary-500/20" disabled={processing}>
                Resend verification email
            </button>
        </form>

        <form method="POST" action="?/logout" use:enhance>
            <button type="submit" class="preset-tonal btn">Log out</button>
        </form>
    </div>
</div>

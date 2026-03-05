<script lang="ts" module>
    import { layout } from '@/layouts/AuthLayout.svelte';
    export { layout };
</script>

<script lang="ts">
    import { useForm, router } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';

    let { status = '' }: { status?: string } = $props();

    const form = useForm({});

    function resend(e: SubmitEvent) {
        e.preventDefault();
        $form.post('/email/verification-notification');
    }

    function logout() {
        router.post('/logout');
    }
</script>

<AppHead title="Verify Email" />

<div class="space-y-6">
    <div class="text-center">
        <h1 class="h3 font-bold">Verify your email</h1>
        <p class="text-surface-500 mt-1 text-sm">
            We've sent a verification link to your email address. Please check your inbox and click the link to verify.
        </p>
    </div>

    {#if status === 'verification-link-sent'}
        <div class="preset-filled-success-500 rounded-lg p-3 text-center text-sm">
            A new verification link has been sent to your email address.
        </div>
    {/if}

    <div class="flex items-center justify-between">
        <form onsubmit={resend}>
            <button type="submit" class="preset-filled-primary-500 btn" disabled={$form.processing}>
                Resend verification email
            </button>
        </form>

        <button onclick={logout} class="preset-tonal btn">Log out</button>
    </div>
</div>

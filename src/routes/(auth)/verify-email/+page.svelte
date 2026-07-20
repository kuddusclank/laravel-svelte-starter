<script lang="ts">
    import { enhance } from '$app/forms';
    import AppHead from '$lib/components/AppHead.svelte';

    let { form } = $props();

    let processing = $state(false);
</script>

<AppHead title="Verify Email" />

<div class="space-y-6">
    <div class="text-center">
        <h1 class="text-foreground text-2xl font-bold tracking-tight">Verify your email</h1>
        <p class="text-muted-foreground mt-1.5 text-sm">
            We've sent a verification link to your email address. Please check your inbox and click the link to verify.
        </p>
    </div>

    {#if form?.status === 'verification-link-sent'}
        <div class="rounded-lg border border-emerald-500/20 bg-emerald-500/10 p-3 text-center text-sm text-emerald-500">
            A new verification link has been sent to your email address.
        </div>
    {/if}

    <div class="flex items-center justify-between gap-4">
        <form
            method="POST"
            action="?/resend"
            use:enhance={() => {
                processing = true;
                return async ({ update }) => {
                    processing = false;
                    await update();
                };
            }}
            class="flex-1"
        >
            <button
                type="submit"
                class="bg-primary text-primary-foreground hover:bg-primary/90 focus-visible:ring-ring inline-flex h-9 w-full cursor-pointer items-center justify-center rounded-lg px-4 py-2 text-sm font-medium shadow-xs focus-visible:ring-1 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
                disabled={processing}
            >
                Resend verification email
            </button>
        </form>

        <form method="POST" action="?/logout" use:enhance>
            <button
                type="submit"
                class="border-border bg-card text-foreground hover:bg-accent inline-flex h-9 cursor-pointer items-center justify-center rounded-lg border px-4 py-2 text-sm font-medium transition-all duration-200"
                >Log out</button
            >
        </form>
    </div>
</div>

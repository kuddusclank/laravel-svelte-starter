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
        <h1 class="text-foreground text-2xl font-bold tracking-tight">Forgot password?</h1>
        <p class="text-muted-foreground mt-1.5 text-sm">Enter your email and we'll send you a reset link</p>
    </div>

    {#if form?.status}
        <div class="rounded-lg border border-emerald-500/20 bg-emerald-500/10 p-3 text-center text-sm text-emerald-500">
            {form.status}
        </div>
    {/if}

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
        <div>
            <label for="email" class="text-foreground text-sm leading-none font-medium">Email</label>
            <input
                id="email"
                name="email"
                type="email"
                value={form?.email ?? ''}
                class="border-border bg-background placeholder:text-muted-foreground focus-visible:ring-ring mt-1.5 flex h-9 w-full rounded-md border px-3 py-1.5 text-sm shadow-xs transition-colors focus-visible:ring-1 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                placeholder="email@example.com"
                required
                autofocus
            />
            <InputError message={form?.errors?.email} />
        </div>

        <button
            type="submit"
            class="bg-primary text-primary-foreground hover:bg-primary/90 focus-visible:ring-ring inline-flex h-9 w-full cursor-pointer items-center justify-center rounded-lg px-4 py-2 text-sm font-medium shadow-xs focus-visible:ring-1 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
            disabled={processing}
        >
            {processing ? 'Sending...' : 'Send reset link'}
        </button>

        <p class="text-muted-foreground mt-4 text-center text-sm">
            <TextLink href="/login">Back to login</TextLink>
        </p>
    </form>
</div>

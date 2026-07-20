<script lang="ts">
    import { enhance } from '$app/forms';
    import AppHead from '$lib/components/AppHead.svelte';
    import InputError from '$lib/components/InputError.svelte';
    import SocialLoginButtons from '$lib/components/SocialLoginButtons.svelte';
    import TextLink from '$lib/components/TextLink.svelte';

    let { form } = $props();

    let processing = $state(false);
</script>

<AppHead title="Log in" />

<div class="space-y-6">
    <div class="text-center">
        <h1 class="text-foreground text-2xl font-bold tracking-tight">Log in to your account</h1>
        <p class="text-muted-foreground mt-1.5 text-sm">Enter your email and password to continue</p>
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

        <div>
            <div class="mb-1.5 flex items-center justify-between">
                <label for="password" class="text-foreground text-sm leading-none font-medium">Password</label>
                <TextLink href="/forgot-password">Forgot password?</TextLink>
            </div>
            <input
                id="password"
                name="password"
                type="password"
                class="border-border bg-background placeholder:text-muted-foreground focus-visible:ring-ring flex h-9 w-full rounded-md border px-3 py-1.5 text-sm shadow-xs transition-colors focus-visible:ring-1 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                placeholder="Password"
                required
            />
            <InputError message={form?.errors?.password} />
        </div>

        <label class="flex cursor-pointer items-center gap-2 select-none">
            <input
                type="checkbox"
                name="remember"
                class="border-border text-primary focus:ring-ring bg-background h-4 w-4 cursor-pointer rounded"
            />
            <span class="text-muted-foreground text-sm font-medium">Remember me</span>
        </label>

        <button
            type="submit"
            class="bg-primary text-primary-foreground hover:bg-primary/90 focus-visible:ring-ring inline-flex h-9 w-full cursor-pointer items-center justify-center rounded-lg px-4 py-2 text-sm font-medium shadow-xs focus-visible:ring-1 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
            disabled={processing}
        >
            {processing ? 'Logging in...' : 'Log in'}
        </button>

        <p class="text-muted-foreground mt-4 text-center text-sm">
            Don't have an account?
            <TextLink href="/register">Sign up</TextLink>
        </p>
    </form>

    <SocialLoginButtons />
</div>

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
        <h1 class="h3 font-bold">Log in to your account</h1>
        <p class="text-surface-500 mt-1 text-sm">Enter your email and password to continue</p>
    </div>

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

        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="label text-sm font-medium">Password</label>
                <TextLink href="/forgot-password">Forgot password?</TextLink>
            </div>
            <input id="password" name="password" type="password" class="input mt-1" placeholder="Password" required />
            <InputError message={form?.errors?.password} />
        </div>

        <label class="flex items-center gap-2">
            <input type="checkbox" name="remember" class="checkbox" />
            <span class="text-sm">Remember me</span>
        </label>

        <button type="submit" class="preset-filled-primary-500 btn w-full shadow-lg shadow-primary-500/20" disabled={processing}>
            {processing ? 'Logging in...' : 'Log in'}
        </button>

        <p class="text-surface-500 text-center text-sm">
            Don't have an account?
            <TextLink href="/register">Sign up</TextLink>
        </p>
    </form>

    <SocialLoginButtons />
</div>

<script lang="ts">
    import { enhance } from '$app/forms';
    import AppHead from '$lib/components/AppHead.svelte';
    import InputError from '$lib/components/InputError.svelte';
    import SocialLoginButtons from '$lib/components/SocialLoginButtons.svelte';
    import TextLink from '$lib/components/TextLink.svelte';

    let { form } = $props();

    let processing = $state(false);
</script>

<AppHead title="Register" />

<div class="space-y-6">
    <div class="text-center">
        <h1 class="h3 font-bold">Create an account</h1>
        <p class="text-surface-500 mt-1 text-sm">Enter your details to get started</p>
    </div>

    <form method="POST" use:enhance={() => {
        processing = true;
        return async ({ update }) => {
            processing = false;
            await update();
        };
    }} class="space-y-4">
        <div>
            <label for="name" class="label text-sm font-medium">Name</label>
            <input id="name" name="name" type="text" value={form?.name ?? ''} class="input mt-1" placeholder="Full name" required autofocus />
            <InputError message={form?.errors?.name} />
        </div>

        <div>
            <label for="email" class="label text-sm font-medium">Email</label>
            <input id="email" name="email" type="email" value={form?.email ?? ''} class="input mt-1" placeholder="email@example.com" required />
            <InputError message={form?.errors?.email} />
        </div>

        <div>
            <label for="password" class="label text-sm font-medium">Password</label>
            <input id="password" name="password" type="password" class="input mt-1" placeholder="Password" required />
            <InputError message={form?.errors?.password} />
        </div>

        <div>
            <label for="password_confirmation" class="label text-sm font-medium">Confirm Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="input mt-1" placeholder="Confirm password" required />
            <InputError message={form?.errors?.password_confirmation} />
        </div>

        <button type="submit" class="preset-filled-primary-500 btn w-full shadow-lg shadow-primary-500/20" disabled={processing}>
            {processing ? 'Creating account...' : 'Create account'}
        </button>

        <p class="text-surface-500 text-center text-sm">
            Already have an account?
            <TextLink href="/login">Log in</TextLink>
        </p>
    </form>

    <SocialLoginButtons />
</div>

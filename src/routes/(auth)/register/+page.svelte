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
        <h1 class="text-foreground text-2xl font-bold tracking-tight">Create an account</h1>
        <p class="text-muted-foreground mt-1.5 text-sm">Enter your details to get started</p>
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
            <label for="name" class="text-foreground text-sm leading-none font-medium">Name</label>
            <input
                id="name"
                name="name"
                type="text"
                value={form?.name ?? ''}
                class="border-border bg-background placeholder:text-muted-foreground focus-visible:ring-ring mt-1.5 flex h-9 w-full rounded-md border px-3 py-1.5 text-sm shadow-xs transition-colors focus-visible:ring-1 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                placeholder="Full name"
                required
                autofocus
            />
            <InputError message={form?.errors?.name} />
        </div>

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
            />
            <InputError message={form?.errors?.email} />
        </div>

        <div>
            <label for="password" class="text-foreground text-sm leading-none font-medium">Password</label>
            <input
                id="password"
                name="password"
                type="password"
                class="border-border bg-background placeholder:text-muted-foreground focus-visible:ring-ring mt-1.5 flex h-9 w-full rounded-md border px-3 py-1.5 text-sm shadow-xs transition-colors focus-visible:ring-1 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                placeholder="Password"
                required
            />
            <InputError message={form?.errors?.password} />
        </div>

        <div>
            <label for="password_confirmation" class="text-foreground text-sm leading-none font-medium"
                >Confirm Password</label
            >
            <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                class="border-border bg-background placeholder:text-muted-foreground focus-visible:ring-ring mt-1.5 flex h-9 w-full rounded-md border px-3 py-1.5 text-sm shadow-xs transition-colors focus-visible:ring-1 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                placeholder="Confirm password"
                required
            />
            <InputError message={form?.errors?.password_confirmation} />
        </div>

        <button
            type="submit"
            class="bg-primary text-primary-foreground hover:bg-primary/90 focus-visible:ring-ring inline-flex h-9 w-full cursor-pointer items-center justify-center rounded-lg px-4 py-2 text-sm font-medium shadow-xs focus-visible:ring-1 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
            disabled={processing}
        >
            {processing ? 'Creating account...' : 'Create account'}
        </button>

        <p class="text-muted-foreground mt-4 text-center text-sm">
            Already have an account?
            <TextLink href="/login">Log in</TextLink>
        </p>
    </form>

    <SocialLoginButtons />
</div>

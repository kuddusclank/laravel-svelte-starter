<script lang="ts">
    import { enhance } from '$app/forms';
    import AppHead from '$lib/components/AppHead.svelte';
    import InputError from '$lib/components/InputError.svelte';

    let { data, form } = $props();

    let processing = $state(false);
</script>

<AppHead title="Reset Password" />

<div class="space-y-6">
    <div class="text-center">
        <h1 class="text-foreground text-2xl font-bold tracking-tight">Reset password</h1>
        <p class="text-muted-foreground mt-1.5 text-sm">Enter your new password</p>
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
        <input type="hidden" name="token" value={data.token} />

        <div>
            <label for="email" class="text-foreground text-sm leading-none font-medium">Email</label>
            <input
                id="email"
                name="email"
                type="email"
                value={form?.email ?? data.email}
                class="border-border bg-background placeholder:text-muted-foreground focus-visible:ring-ring mt-1.5 flex h-9 w-full rounded-md border px-3 py-1.5 text-sm shadow-xs transition-colors focus-visible:ring-1 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
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
                placeholder="New password"
                required
                autofocus
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
                placeholder="Confirm new password"
                required
            />
            <InputError message={form?.errors?.password_confirmation} />
        </div>

        <button
            type="submit"
            class="bg-primary text-primary-foreground hover:bg-primary/90 focus-visible:ring-ring inline-flex h-9 w-full cursor-pointer items-center justify-center rounded-lg px-4 py-2 text-sm font-medium shadow-xs focus-visible:ring-1 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
            disabled={processing}
        >
            {processing ? 'Resetting...' : 'Reset password'}
        </button>
    </form>
</div>

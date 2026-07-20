<script lang="ts">
    import { enhance } from '$app/forms';
    import AppHead from '$lib/components/AppHead.svelte';
    import InputError from '$lib/components/InputError.svelte';

    let { form } = $props();

    let processing = $state(false);
</script>

<AppHead title="Confirm Password" />

<div class="space-y-6">
    <div class="text-center">
        <h1 class="text-foreground text-2xl font-bold tracking-tight">Confirm password</h1>
        <p class="text-muted-foreground mt-1.5 text-sm">
            This is a secure area. Please confirm your password before continuing.
        </p>
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
            <label for="password" class="text-foreground text-sm leading-none font-medium">Password</label>
            <input
                id="password"
                name="password"
                type="password"
                class="border-border bg-background placeholder:text-muted-foreground focus-visible:ring-ring mt-1.5 flex h-9 w-full rounded-md border px-3 py-1.5 text-sm shadow-xs transition-colors focus-visible:ring-1 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                placeholder="Password"
                required
                autofocus
            />
            <InputError message={form?.errors?.password} />
        </div>

        <button
            type="submit"
            class="bg-primary text-primary-foreground hover:bg-primary/90 focus-visible:ring-ring inline-flex h-9 w-full cursor-pointer items-center justify-center rounded-lg px-4 py-2 text-sm font-medium shadow-xs focus-visible:ring-1 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
            disabled={processing}
        >
            Confirm
        </button>
    </form>
</div>

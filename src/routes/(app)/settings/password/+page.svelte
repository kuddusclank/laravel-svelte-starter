<script lang="ts">
    import { enhance } from '$app/forms';
    import AppHead from '$lib/components/AppHead.svelte';
    import InputError from '$lib/components/InputError.svelte';

    let { form } = $props();

    let processing = $state(false);
    let saved = $state(false);
</script>

<AppHead title="Password" />

<section>
    <header>
        <h3 class="text-foreground text-lg font-semibold tracking-tight">Update Password</h3>
        <p class="text-muted-foreground mt-1 text-sm">Ensure your account is using a long, random password.</p>
    </header>

    <form
        method="POST"
        use:enhance={({ formElement }) => {
            processing = true;
            saved = false;
            return async ({ result, update }) => {
                processing = false;
                if (result.type === 'success') {
                    saved = true;
                    formElement.reset();
                    setTimeout(() => (saved = false), 3000);
                } else {
                    await update();
                }
            };
        }}
        class="mt-4 max-w-xl space-y-4"
    >
        <div>
            <label for="current_password" class="text-foreground text-sm leading-none font-medium"
                >Current Password</label
            >
            <input
                id="current_password"
                name="current_password"
                type="password"
                class="border-border bg-background placeholder:text-muted-foreground focus-visible:ring-ring mt-1.5 flex h-9 w-full rounded-md border px-3 py-1.5 text-sm shadow-xs transition-colors focus-visible:ring-1 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                required
            />
            <InputError message={form?.errors?.current_password} />
        </div>

        <div>
            <label for="password" class="text-foreground text-sm leading-none font-medium">New Password</label>
            <input
                id="password"
                name="password"
                type="password"
                class="border-border bg-background placeholder:text-muted-foreground focus-visible:ring-ring mt-1.5 flex h-9 w-full rounded-md border px-3 py-1.5 text-sm shadow-xs transition-colors focus-visible:ring-1 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
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
                required
            />
            <InputError message={form?.errors?.password_confirmation} />
        </div>

        <div class="flex items-center gap-4">
            <button
                type="submit"
                class="bg-primary text-primary-foreground hover:bg-primary/90 focus-visible:ring-ring inline-flex h-9 cursor-pointer items-center justify-center rounded-lg px-4 py-2 text-sm font-medium shadow-xs focus-visible:ring-1 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
                disabled={processing}
            >
                Update Password
            </button>
            {#if saved}
                <span class="text-sm font-medium text-emerald-500">Saved.</span>
            {/if}
        </div>
    </form>
</section>

<script lang="ts">
    import { enhance } from '$app/forms';
    import { page } from '$app/stores';
    import { invalidateAll } from '$app/navigation';
    import AppHead from '$lib/components/AppHead.svelte';
    import InputError from '$lib/components/InputError.svelte';
    import DeleteUser from '$lib/components/DeleteUser.svelte';

    let { form } = $props();

    const user = $derived($page.data.user);

    let processing = $state(false);
    let saved = $state(false);
</script>

<AppHead title="Profile" />

<div class="space-y-8">
    <section>
        <header>
            <h3 class="text-foreground text-lg font-semibold tracking-tight">Profile Information</h3>
            <p class="text-muted-foreground mt-1 text-sm">Update your name and email address.</p>
        </header>

        <form
            method="POST"
            action="?/updateProfile"
            use:enhance={() => {
                processing = true;
                saved = false;
                return async ({ result, update }) => {
                    processing = false;
                    if (result.type === 'success') {
                        saved = true;
                        await invalidateAll();
                        setTimeout(() => (saved = false), 3000);
                    } else {
                        await update();
                    }
                };
            }}
            class="mt-4 max-w-xl space-y-4"
        >
            <div>
                <label for="name" class="text-foreground text-sm leading-none font-medium">Name</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value={form?.name ?? user?.name ?? ''}
                    class="border-border bg-background placeholder:text-muted-foreground focus-visible:ring-ring mt-1.5 flex h-9 w-full rounded-md border px-3 py-1.5 text-sm shadow-xs transition-colors focus-visible:ring-1 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                    required
                />
                <InputError message={form?.errors?.name} />
            </div>

            <div>
                <label for="email" class="text-foreground text-sm leading-none font-medium">Email</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value={form?.email ?? user?.email ?? ''}
                    class="border-border bg-background placeholder:text-muted-foreground focus-visible:ring-ring mt-1.5 flex h-9 w-full rounded-md border px-3 py-1.5 text-sm shadow-xs transition-colors focus-visible:ring-1 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                    required
                />
                <InputError message={form?.errors?.email} />
            </div>

            <div class="flex items-center gap-4">
                <button
                    type="submit"
                    class="bg-primary text-primary-foreground hover:bg-primary/90 focus-visible:ring-ring inline-flex h-9 cursor-pointer items-center justify-center rounded-lg px-4 py-2 text-sm font-medium shadow-xs focus-visible:ring-1 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
                    disabled={processing}
                >
                    Save
                </button>
                {#if saved}
                    <span class="text-sm font-medium text-emerald-500">Saved.</span>
                {/if}
            </div>
        </form>
    </section>

    <hr class="border-border" />

    <DeleteUser />
</div>

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
            <h3 class="h4 font-bold">Profile Information</h3>
            <p class="text-surface-500 mt-1 text-sm">Update your name and email address.</p>
        </header>

        <form method="POST" action="?/updateProfile" use:enhance={() => {
            processing = true;
            saved = false;
            return async ({ result, update }) => {
                processing = false;
                if (result.type === 'success') {
                    saved = true;
                    await invalidateAll();
                    setTimeout(() => saved = false, 3000);
                } else {
                    await update();
                }
            };
        }} class="mt-4 max-w-xl space-y-4">
            <div>
                <label for="name" class="label text-sm font-medium">Name</label>
                <input id="name" name="name" type="text" value={form?.name ?? user?.name ?? ''} class="input mt-1" required />
                <InputError message={form?.errors?.name} />
            </div>

            <div>
                <label for="email" class="label text-sm font-medium">Email</label>
                <input id="email" name="email" type="email" value={form?.email ?? user?.email ?? ''} class="input mt-1" required />
                <InputError message={form?.errors?.email} />
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="preset-filled-primary-500 btn" disabled={processing}>
                    Save
                </button>
                {#if saved}
                    <span class="text-success-500 text-sm">Saved.</span>
                {/if}
            </div>
        </form>
    </section>

    <hr class="border-surface-200-800" />

    <DeleteUser />
</div>

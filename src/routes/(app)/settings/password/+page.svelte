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
        <h3 class="h4 font-bold">Update Password</h3>
        <p class="text-surface-500 mt-1 text-sm">Ensure your account is using a long, random password.</p>
    </header>

    <form method="POST" use:enhance={({ formElement }) => {
        processing = true;
        saved = false;
        return async ({ result, update }) => {
            processing = false;
            if (result.type === 'success') {
                saved = true;
                formElement.reset();
                setTimeout(() => saved = false, 3000);
            } else {
                await update();
            }
        };
    }} class="mt-4 max-w-xl space-y-4">
        <div>
            <label for="current_password" class="label text-sm font-medium">Current Password</label>
            <input id="current_password" name="current_password" type="password" class="input mt-1" required />
            <InputError message={form?.errors?.current_password} />
        </div>

        <div>
            <label for="password" class="label text-sm font-medium">New Password</label>
            <input id="password" name="password" type="password" class="input mt-1" required />
            <InputError message={form?.errors?.password} />
        </div>

        <div>
            <label for="password_confirmation" class="label text-sm font-medium">Confirm Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="input mt-1" required />
            <InputError message={form?.errors?.password_confirmation} />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="preset-filled-primary-500 btn" disabled={processing}>
                Update Password
            </button>
            {#if saved}
                <span class="text-success-500 text-sm">Saved.</span>
            {/if}
        </div>
    </form>
</section>

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
        <h1 class="h3 font-bold">Confirm password</h1>
        <p class="text-surface-500 mt-1 text-sm">
            This is a secure area. Please confirm your password before continuing.
        </p>
    </div>

    <form method="POST" use:enhance={() => {
        processing = true;
        return async ({ update }) => {
            processing = false;
            await update();
        };
    }} class="space-y-4">
        <div>
            <label for="password" class="label text-sm font-medium">Password</label>
            <input id="password" name="password" type="password" class="input mt-1" placeholder="Password" required autofocus />
            <InputError message={form?.errors?.password} />
        </div>

        <button type="submit" class="preset-filled-primary-500 btn w-full shadow-lg shadow-primary-500/20" disabled={processing}>
            Confirm
        </button>
    </form>
</div>

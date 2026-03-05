<script lang="ts" module>
    import type { LayoutResolver } from '@inertiajs/svelte';
    import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.svelte';
    import SettingsLayout from '@/layouts/settings/Layout.svelte';
    export const layout: LayoutResolver = (h) => [AppSidebarLayout, SettingsLayout, h];
</script>

<script lang="ts">
    import { useForm } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';
    import InputError from '@/components/InputError.svelte';

    const form = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    function submit(e: SubmitEvent) {
        e.preventDefault();
        $form.put('/user/password', {
            preserveScroll: true,
            onSuccess: () => {
                $form.reset();
            },
        });
    }
</script>

<AppHead title="Password" />

<section>
    <header>
        <h3 class="h4 font-bold">Update Password</h3>
        <p class="text-surface-500 mt-1 text-sm">Ensure your account is using a long, random password.</p>
    </header>

    <form onsubmit={submit} class="mt-4 max-w-xl space-y-4">
        <div>
            <label for="current_password" class="label text-sm font-medium">Current Password</label>
            <input id="current_password" type="password" bind:value={$form.current_password} class="input mt-1" required />
            <InputError message={$form.errors.current_password} />
        </div>

        <div>
            <label for="password" class="label text-sm font-medium">New Password</label>
            <input id="password" type="password" bind:value={$form.password} class="input mt-1" required />
            <InputError message={$form.errors.password} />
        </div>

        <div>
            <label for="password_confirmation" class="label text-sm font-medium">Confirm Password</label>
            <input id="password_confirmation" type="password" bind:value={$form.password_confirmation} class="input mt-1" required />
            <InputError message={$form.errors.password_confirmation} />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="preset-filled-primary-500 btn" disabled={$form.processing}>
                Update Password
            </button>
            {#if $form.recentlySuccessful}
                <span class="text-success-500 text-sm">Saved.</span>
            {/if}
        </div>
    </form>
</section>

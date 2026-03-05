<script lang="ts" module>
    import type { LayoutResolver } from '@inertiajs/svelte';
    import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.svelte';
    import SettingsLayout from '@/layouts/settings/Layout.svelte';
    export const layout: LayoutResolver = (h) => [AppSidebarLayout, SettingsLayout, h];
</script>

<script lang="ts">
    import { useForm, page } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';
    import InputError from '@/components/InputError.svelte';
    import DeleteUser from '@/components/DeleteUser.svelte';

    let { mustVerifyEmail = false, status = '' }: { mustVerifyEmail?: boolean; status?: string } = $props();

    const user = $derived(($page.props as any).auth?.user);

    const form = useForm({
        name: user?.name ?? '',
        email: user?.email ?? '',
    });

    function submit(e: SubmitEvent) {
        e.preventDefault();
        $form.put('/settings/profile', {
            preserveScroll: true,
        });
    }
</script>

<AppHead title="Profile" />

<div class="space-y-8">
    <section>
        <header>
            <h3 class="h4 font-bold">Profile Information</h3>
            <p class="text-surface-500 mt-1 text-sm">Update your name and email address.</p>
        </header>

        <form onsubmit={submit} class="mt-4 max-w-xl space-y-4">
            <div>
                <label for="name" class="label text-sm font-medium">Name</label>
                <input id="name" type="text" bind:value={$form.name} class="input mt-1" required />
                <InputError message={$form.errors.name} />
            </div>

            <div>
                <label for="email" class="label text-sm font-medium">Email</label>
                <input id="email" type="email" bind:value={$form.email} class="input mt-1" required />
                <InputError message={$form.errors.email} />
            </div>

            {#if mustVerifyEmail && !user?.email_verified_at}
                <p class="text-warning-500 text-sm">
                    Your email address is unverified.
                    <a href="/email/verification-notification" class="underline">Resend verification email</a>
                </p>
            {/if}

            <div class="flex items-center gap-4">
                <button type="submit" class="preset-filled-primary-500 btn" disabled={$form.processing}>
                    Save
                </button>
                {#if $form.recentlySuccessful}
                    <span class="text-success-500 text-sm">Saved.</span>
                {/if}
            </div>
        </form>
    </section>

    <hr class="border-surface-200-800" />

    <DeleteUser />
</div>

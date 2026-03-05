<script lang="ts" module>
    import { layout } from '@/layouts/AuthLayout.svelte';
    export { layout };
</script>

<script lang="ts">
    import { useForm } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';
    import InputError from '@/components/InputError.svelte';

    let { email = '', token = '' }: { email?: string; token?: string } = $props();

    const form = useForm({
        token,
        email,
        password: '',
        password_confirmation: '',
    });

    function submit(e: SubmitEvent) {
        e.preventDefault();
        $form.post('/reset-password', {
            onFinish: () => {
                $form.reset('password', 'password_confirmation');
            },
        });
    }
</script>

<AppHead title="Reset Password" />

<div class="space-y-6">
    <div class="text-center">
        <h1 class="h3 font-bold">Reset password</h1>
        <p class="text-surface-500 mt-1 text-sm">Enter your new password</p>
    </div>

    <form onsubmit={submit} class="space-y-4">
        <div>
            <label for="email" class="label text-sm font-medium">Email</label>
            <input id="email" type="email" bind:value={$form.email} class="input mt-1" required />
            <InputError message={$form.errors.email} />
        </div>

        <div>
            <label for="password" class="label text-sm font-medium">Password</label>
            <input id="password" type="password" bind:value={$form.password} class="input mt-1" placeholder="New password" required autofocus />
            <InputError message={$form.errors.password} />
        </div>

        <div>
            <label for="password_confirmation" class="label text-sm font-medium">Confirm Password</label>
            <input id="password_confirmation" type="password" bind:value={$form.password_confirmation} class="input mt-1" placeholder="Confirm new password" required />
            <InputError message={$form.errors.password_confirmation} />
        </div>

        <button type="submit" class="preset-filled-primary-500 btn w-full" disabled={$form.processing}>
            {$form.processing ? 'Resetting...' : 'Reset password'}
        </button>
    </form>
</div>

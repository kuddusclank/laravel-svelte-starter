<script lang="ts" module>
    import { layout } from '@/layouts/AuthLayout.svelte';
    export { layout };
</script>

<script lang="ts">
    import { useForm } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';
    import InputError from '@/components/InputError.svelte';

    const form = useForm({ password: '' });

    function submit(e: SubmitEvent) {
        e.preventDefault();
        $form.post('/user/confirm-password', {
            onFinish: () => {
                $form.reset();
            },
        });
    }
</script>

<AppHead title="Confirm Password" />

<div class="space-y-6">
    <div class="text-center">
        <h1 class="h3 font-bold">Confirm password</h1>
        <p class="text-surface-500 mt-1 text-sm">
            This is a secure area. Please confirm your password before continuing.
        </p>
    </div>

    <form onsubmit={submit} class="space-y-4">
        <div>
            <label for="password" class="label text-sm font-medium">Password</label>
            <input id="password" type="password" bind:value={$form.password} class="input mt-1" placeholder="Password" required autofocus />
            <InputError message={$form.errors.password} />
        </div>

        <button type="submit" class="preset-filled-primary-500 btn w-full" disabled={$form.processing}>
            Confirm
        </button>
    </form>
</div>

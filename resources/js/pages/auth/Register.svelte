<script lang="ts" module>
    import { layout } from '@/layouts/AuthLayout.svelte';
    export { layout };
</script>

<script lang="ts">
    import { useForm } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';
    import InputError from '@/components/InputError.svelte';
    import TextLink from '@/components/TextLink.svelte';

    const form = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    function submit(e: SubmitEvent) {
        e.preventDefault();
        $form.post('/register', {
            onFinish: () => {
                $form.reset('password', 'password_confirmation');
            },
        });
    }
</script>

<AppHead title="Register" />

<div class="space-y-6">
    <div class="text-center">
        <h1 class="h3 font-bold">Create an account</h1>
        <p class="text-surface-500 mt-1 text-sm">Enter your details to get started</p>
    </div>

    <form onsubmit={submit} class="space-y-4">
        <div>
            <label for="name" class="label text-sm font-medium">Name</label>
            <input id="name" type="text" bind:value={$form.name} class="input mt-1" placeholder="Full name" required autofocus />
            <InputError message={$form.errors.name} />
        </div>

        <div>
            <label for="email" class="label text-sm font-medium">Email</label>
            <input id="email" type="email" bind:value={$form.email} class="input mt-1" placeholder="email@example.com" required />
            <InputError message={$form.errors.email} />
        </div>

        <div>
            <label for="password" class="label text-sm font-medium">Password</label>
            <input id="password" type="password" bind:value={$form.password} class="input mt-1" placeholder="Password" required />
            <InputError message={$form.errors.password} />
        </div>

        <div>
            <label for="password_confirmation" class="label text-sm font-medium">Confirm Password</label>
            <input id="password_confirmation" type="password" bind:value={$form.password_confirmation} class="input mt-1" placeholder="Confirm password" required />
            <InputError message={$form.errors.password_confirmation} />
        </div>

        <button type="submit" class="preset-filled-primary-500 btn w-full" disabled={$form.processing}>
            {$form.processing ? 'Creating account...' : 'Create account'}
        </button>

        <p class="text-surface-500 text-center text-sm">
            Already have an account?
            <TextLink href="/login">Log in</TextLink>
        </p>
    </form>
</div>

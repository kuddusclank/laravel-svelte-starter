<script lang="ts" module>
    import { layout } from '@/layouts/AuthLayout.svelte';
    export { layout };
</script>

<script lang="ts">
    import { useForm, page } from '@inertiajs/svelte';
    import AppHead from '@/components/AppHead.svelte';
    import InputError from '@/components/InputError.svelte';
    import TextLink from '@/components/TextLink.svelte';

    let { canResetPassword = false, status = '' }: { canResetPassword?: boolean; status?: string } = $props();

    const form = useForm({
        email: '',
        password: '',
        remember: false,
    });

    function submit(e: SubmitEvent) {
        e.preventDefault();
        $form.post('/login', {
            onFinish: () => {
                $form.reset('password');
            },
        });
    }
</script>

<AppHead title="Log in" />

<div class="space-y-6">
    <div class="text-center">
        <h1 class="h3 font-bold">Log in to your account</h1>
        <p class="text-surface-500 mt-1 text-sm">Enter your email and password to continue</p>
    </div>

    {#if status}
        <div class="preset-filled-success-500 rounded-lg p-3 text-center text-sm">{status}</div>
    {/if}

    <form onsubmit={submit} class="space-y-4">
        <div>
            <label for="email" class="label text-sm font-medium">Email</label>
            <input id="email" type="email" bind:value={$form.email} class="input mt-1" placeholder="email@example.com" required autofocus />
            <InputError message={$form.errors.email} />
        </div>

        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="label text-sm font-medium">Password</label>
                {#if canResetPassword}
                    <TextLink href="/forgot-password">Forgot password?</TextLink>
                {/if}
            </div>
            <input id="password" type="password" bind:value={$form.password} class="input mt-1" placeholder="Password" required />
            <InputError message={$form.errors.password} />
        </div>

        <label class="flex items-center gap-2">
            <input type="checkbox" bind:checked={$form.remember} class="checkbox" />
            <span class="text-sm">Remember me</span>
        </label>

        <button type="submit" class="preset-filled-primary-500 btn w-full" disabled={$form.processing}>
            {$form.processing ? 'Logging in...' : 'Log in'}
        </button>

        <p class="text-surface-500 text-center text-sm">
            Don't have an account?
            <TextLink href="/register">Sign up</TextLink>
        </p>
    </form>
</div>

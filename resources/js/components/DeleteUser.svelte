<script lang="ts">
    import { useForm } from '@inertiajs/svelte';
    import InputError from './InputError.svelte';

    let showModal = $state(false);

    const form = useForm({ password: '' });

    function submit(e: SubmitEvent) {
        e.preventDefault();
        $form.delete('/settings/profile', {
            preserveScroll: true,
            onSuccess: () => {
                showModal = false;
            },
            onFinish: () => {
                $form.reset();
            },
        });
    }
</script>

<section class="space-y-6">
    <header>
        <h3 class="h4 font-bold">Delete Account</h3>
        <p class="text-surface-500 mt-1 text-sm">
            Once your account is deleted, all of its resources and data will be permanently deleted.
        </p>
    </header>

    <button class="preset-filled-error-500 btn" onclick={() => showModal = true}>
        Delete Account
    </button>

    {#if showModal}
        <!-- svelte-ignore a11y_no_static_element_interactions -->
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" onclick={() => showModal = false} onkeydown={() => {}}>
            <!-- svelte-ignore a11y_no_static_element_interactions -->
            <div class="preset-elevated-surface-50-950 w-full max-w-md rounded-xl border p-6" onclick={(e) => e.stopPropagation()} onkeydown={() => {}}>
                <h3 class="h4 font-bold">Are you sure?</h3>
                <p class="text-surface-500 mt-2 text-sm">
                    This action cannot be undone. Please enter your password to confirm.
                </p>

                <form onsubmit={submit} class="mt-4 space-y-4">
                    <div>
                        <label for="password" class="label text-sm font-medium">Password</label>
                        <input
                            id="password"
                            type="password"
                            bind:value={$form.password}
                            class="input mt-1"
                            placeholder="Password"
                        />
                        <InputError message={$form.errors.password} />
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" class="preset-tonal btn" onclick={() => showModal = false}>
                            Cancel
                        </button>
                        <button type="submit" class="preset-filled-error-500 btn" disabled={$form.processing}>
                            Delete Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    {/if}
</section>

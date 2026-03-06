<script lang="ts">
    import { enhance } from '$app/forms';
    import InputError from './InputError.svelte';

    let showModal = $state(false);
    let error = $state('');
    let processing = $state(false);
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
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm" onclick={() => showModal = false} onkeydown={() => {}}>
            <!-- svelte-ignore a11y_no_static_element_interactions -->
            <div class="bg-surface-50 dark:bg-surface-900 w-full max-w-md rounded-2xl border border-surface-200/60 dark:border-surface-800/60 p-6 shadow-2xl" onclick={(e) => e.stopPropagation()} onkeydown={() => {}}>
                <h3 class="h4 font-bold">Are you sure?</h3>
                <p class="text-surface-500 mt-2 text-sm">
                    This action cannot be undone. Please enter your password to confirm.
                </p>

                <form method="POST" action="/settings/profile?/deleteAccount" use:enhance={() => {
                    processing = true;
                    error = '';
                    return async ({ result }) => {
                        processing = false;
                        if (result.type === 'success') {
                            window.location.href = '/';
                        } else if (result.type === 'failure' && result.data?.deleteErrors?.password) {
                            error = result.data.deleteErrors.password;
                        } else {
                            error = 'Failed to delete account. Please check your password.';
                        }
                    };
                }} class="mt-4 space-y-4">
                    <div>
                        <label for="delete-password" class="label text-sm font-medium">Password</label>
                        <input
                            id="delete-password"
                            name="password"
                            type="password"
                            class="input mt-1"
                            placeholder="Password"
                        />
                        <InputError message={error} />
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" class="preset-tonal btn" onclick={() => showModal = false}>
                            Cancel
                        </button>
                        <button type="submit" class="preset-filled-error-500 btn" disabled={processing}>
                            Delete Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    {/if}
</section>

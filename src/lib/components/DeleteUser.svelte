<script lang="ts">
    import { enhance } from '$app/forms';
    import InputError from './InputError.svelte';

    let showModal = $state(false);
    let error = $state('');
    let processing = $state(false);
</script>

<section class="space-y-6">
    <header>
        <h3 class="text-foreground text-lg font-semibold tracking-tight">Delete Account</h3>
        <p class="text-muted-foreground mt-1 text-sm">
            Once your account is deleted, all of its resources and data will be permanently deleted.
        </p>
    </header>

    <button
        class="bg-destructive text-destructive-foreground hover:bg-destructive/90 inline-flex h-9 cursor-pointer items-center justify-center rounded-lg px-4 py-2 text-sm font-medium shadow-xs transition-colors"
        onclick={() => (showModal = true)}
    >
        Delete Account
    </button>

    {#if showModal}
        <!-- svelte-ignore a11y_no_static_element_interactions -->
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs"
            onclick={() => (showModal = false)}
            onkeydown={() => {}}
        >
            <!-- svelte-ignore a11y_no_static_element_interactions -->
            <div
                class="bg-card border-border w-full max-w-md rounded-2xl border p-6 shadow-2xl"
                onclick={(e) => e.stopPropagation()}
                onkeydown={() => {}}
            >
                <h3 class="text-foreground text-lg font-semibold tracking-tight">Are you sure?</h3>
                <p class="text-muted-foreground mt-2 text-sm">
                    This action cannot be undone. Please enter your password to confirm.
                </p>

                <form
                    method="POST"
                    action="/settings/profile?/deleteAccount"
                    use:enhance={() => {
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
                    }}
                    class="mt-4 space-y-4"
                >
                    <div>
                        <label for="delete-password" class="text-foreground text-sm leading-none font-medium"
                            >Password</label
                        >
                        <input
                            id="delete-password"
                            name="password"
                            type="password"
                            class="border-border bg-background placeholder:text-muted-foreground focus-visible:ring-ring mt-1.5 flex h-9 w-full rounded-md border px-3 py-1.5 text-sm shadow-xs transition-colors focus-visible:ring-1 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Password"
                        />
                        <InputError message={error} />
                    </div>

                    <div class="flex justify-end gap-3">
                        <button
                            type="button"
                            class="border-border bg-background text-foreground hover:bg-accent hover:text-accent-foreground inline-flex h-9 cursor-pointer items-center justify-center rounded-lg border px-4 py-2 text-sm font-medium transition-colors"
                            onclick={() => (showModal = false)}
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="bg-destructive text-destructive-foreground hover:bg-destructive/90 inline-flex h-9 cursor-pointer items-center justify-center rounded-lg px-4 py-2 text-sm font-medium shadow-xs transition-colors"
                            disabled={processing}
                        >
                            Delete Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    {/if}
</section>

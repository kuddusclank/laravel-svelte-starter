<script lang="ts">
    import { page } from '$app/stores';
    import { getInitials } from '$lib/utils/initials';
    import { LogOut, Settings, ChevronsUpDown } from 'lucide-svelte';
    import UserInfo from './UserInfo.svelte';

    const user = $derived($page.data.user);

    let menuOpen = $state(false);
</script>

<div class="relative">
    <button
        onclick={() => (menuOpen = !menuOpen)}
        class="hover:bg-accent hover:text-accent-foreground flex w-full cursor-pointer items-center gap-3 rounded-lg p-2 text-left transition-colors"
    >
        <div
            class="bg-primary text-primary-foreground flex size-8 items-center justify-center rounded-full text-xs font-bold"
        >
            {getInitials(user?.name ?? '')}
        </div>
        <div class="flex-1 truncate">
            <p class="truncate text-sm font-medium">{user?.name}</p>
            <p class="text-muted-foreground truncate text-xs">{user?.email}</p>
        </div>
        <ChevronsUpDown class="text-muted-foreground size-4" />
    </button>

    {#if menuOpen}
        <!-- svelte-ignore a11y_no_static_element_interactions -->
        <div class="fixed inset-0 z-40" onclick={() => (menuOpen = false)} onkeydown={() => {}}></div>
        <div
            class="border-border bg-card text-card-foreground absolute bottom-full left-0 z-50 mb-2 w-full rounded-lg border p-1 shadow-md"
        >
            <UserInfo {user} />
            <hr class="border-border my-1" />
            <a
                href="/settings/profile"
                class="hover:bg-accent hover:text-accent-foreground flex items-center gap-2 rounded-md px-3 py-2 text-sm transition-colors"
            >
                <Settings class="size-4" />
                Settings
            </a>
            <form method="POST" action="/logout">
                <button
                    type="submit"
                    class="hover:bg-accent hover:text-accent-foreground flex w-full cursor-pointer items-center gap-2 rounded-md px-3 py-2 text-sm transition-colors"
                >
                    <LogOut class="size-4" />
                    Log out
                </button>
            </form>
        </div>
    {/if}
</div>

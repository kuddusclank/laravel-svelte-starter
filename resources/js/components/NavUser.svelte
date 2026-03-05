<script lang="ts">
    import { page, router } from '@inertiajs/svelte';
    import { getInitials } from '@/lib/initials';
    import { LogOut, Settings, ChevronsUpDown } from 'lucide-svelte';
    import UserInfo from './UserInfo.svelte';

    const user = $derived(($page.props as any).auth?.user);

    let menuOpen = $state(false);

    function logout() {
        router.post('/logout');
    }
</script>

<div class="relative">
    <button
        onclick={() => menuOpen = !menuOpen}
        class="hover:bg-surface-200-800 flex w-full items-center gap-3 rounded-lg p-2 text-left transition-colors"
    >
        <div class="bg-primary-500 flex size-8 items-center justify-center rounded-full text-xs font-bold text-white">
            {getInitials(user?.name ?? '')}
        </div>
        <div class="flex-1 truncate">
            <p class="text-sm font-medium truncate">{user?.name}</p>
            <p class="text-surface-500 truncate text-xs">{user?.email}</p>
        </div>
        <ChevronsUpDown class="text-surface-400 size-4" />
    </button>

    {#if menuOpen}
        <!-- svelte-ignore a11y_no_static_element_interactions -->
        <div class="fixed inset-0 z-40" onclick={() => menuOpen = false} onkeydown={() => {}}></div>
        <div class="preset-elevated-surface-50-950 absolute bottom-full left-0 z-50 mb-2 w-full rounded-lg border p-1 shadow-lg">
            <UserInfo {user} />
            <hr class="border-surface-200-800 my-1" />
            <a href="/settings/profile" class="hover:bg-surface-200-800 flex items-center gap-2 rounded-md px-3 py-2 text-sm">
                <Settings class="size-4" />
                Settings
            </a>
            <button
                onclick={logout}
                class="hover:bg-surface-200-800 flex w-full items-center gap-2 rounded-md px-3 py-2 text-sm"
            >
                <LogOut class="size-4" />
                Log out
            </button>
        </div>
    {/if}
</div>

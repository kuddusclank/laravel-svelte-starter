<script lang="ts">
    import { router } from '@inertiajs/svelte';
    import { LogOut, Settings } from 'lucide-svelte';

    function logout() {
        router.post('/logout');
    }
</script>

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

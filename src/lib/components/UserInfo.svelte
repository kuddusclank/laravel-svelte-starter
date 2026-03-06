<script lang="ts">
    import { getInitials } from '$lib/utils/initials';
    import type { User } from '$lib/types';

    let { user }: { user: User | null } = $props();
</script>

{#if user}
    <div class="flex items-center gap-3 px-3 py-2">
        <div class="bg-primary-500 flex size-8 items-center justify-center rounded-full text-xs font-bold text-white">
            {getInitials(user.name)}
        </div>
        <div class="truncate">
            <p class="text-sm font-medium truncate">{user.name}</p>
            <p class="text-surface-500 truncate text-xs">{user.email}</p>
        </div>
    </div>
{/if}

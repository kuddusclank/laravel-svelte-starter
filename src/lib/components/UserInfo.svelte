<script lang="ts">
    import { getInitials } from '$lib/utils/initials';
    import type { User } from '$lib/types';

    let { user }: { user: User | null } = $props();
</script>

{#if user}
    <div class="flex items-center gap-3 px-3 py-2">
        <div
            class="bg-primary text-primary-foreground flex size-8 items-center justify-center rounded-full text-xs font-bold"
        >
            {getInitials(user.name)}
        </div>
        <div class="truncate">
            <p class="truncate text-sm font-medium">{user.name}</p>
            <p class="text-muted-foreground truncate text-xs">{user.email}</p>
        </div>
    </div>
{/if}

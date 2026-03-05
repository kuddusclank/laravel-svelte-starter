<script lang="ts">
    import { page } from '@inertiajs/svelte';
    import type { Snippet } from 'svelte';
    import NavUser from '@/components/NavUser.svelte';
    import AppLogo from '@/components/AppLogo.svelte';
    import { LayoutDashboard } from 'lucide-svelte';

    let { children }: { children: Snippet } = $props();
</script>

<div class="flex min-h-screen flex-col">
    <header class="bg-surface-100-900 border-surface-200-800 flex h-16 items-center justify-between border-b px-6">
        <div class="flex items-center gap-6">
            <a href="/dashboard">
                <AppLogo />
            </a>
            <nav class="flex items-center gap-4">
                <a href="/dashboard" class="preset-link text-sm">Dashboard</a>
            </nav>
        </div>
        <NavUser />
    </header>
    <main class="flex-1 p-6">
        {@render children()}
    </main>
</div>

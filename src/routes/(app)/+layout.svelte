<script lang="ts">
    import { page } from '$app/stores';
    import type { Snippet } from 'svelte';
    import type { BreadcrumbItem, NavItem } from '$lib/types';
    import AppBar from '$lib/components/AppBar.svelte';
    import AppLogo from '$lib/components/AppLogo.svelte';
    import NavFooter from '$lib/components/NavFooter.svelte';
    import NavMain from '$lib/components/NavMain.svelte';
    import NavUser from '$lib/components/NavUser.svelte';
    import { LayoutDashboard } from 'lucide-svelte';

    let { children }: { children: Snippet } = $props();

    const mainNavItems: NavItem[] = [
        { title: 'Dashboard', href: '/dashboard', icon: LayoutDashboard },
    ];

    let sidebarOpen = $state(true);

    // Read sidebar state from cookie on mount
    if (typeof document !== 'undefined') {
        const match = document.cookie.match(/sidebar_state=(\w+)/);
        sidebarOpen = match ? match[1] === 'true' : true;
    }

    function toggleSidebar() {
        sidebarOpen = !sidebarOpen;
        document.cookie = `sidebar_state=${sidebarOpen};path=/;max-age=${365 * 24 * 60 * 60};SameSite=Lax`;
    }

    const breadcrumbs: BreadcrumbItem[] = [];
</script>

<div class="flex min-h-screen">
    {#if sidebarOpen}
        <aside class="bg-surface-100-900 border-surface-200/60 dark:border-surface-800/60 flex w-64 shrink-0 flex-col border-r">
            <div class="border-surface-200/60 dark:border-surface-800/60 flex h-16 items-center border-b px-4">
                <a href="/dashboard">
                    <AppLogo />
                </a>
            </div>
            <nav class="flex-1 overflow-y-auto p-4">
                <NavMain items={mainNavItems} />
            </nav>
            <NavFooter />
            <div class="border-surface-200/60 dark:border-surface-800/60 border-t p-3">
                <NavUser />
            </div>
        </aside>
    {/if}

    <div class="flex flex-1 flex-col">
        <AppBar {sidebarOpen} onToggleSidebar={toggleSidebar} {breadcrumbs} />
        <main class="flex-1 p-6">
            {@render children()}
        </main>
    </div>
</div>

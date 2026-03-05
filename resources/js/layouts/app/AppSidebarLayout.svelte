<script lang="ts">
    import { page } from '@inertiajs/svelte';
    import type { Snippet } from 'svelte';
    import type { BreadcrumbItem, NavItem } from '@/types';
    import AppBar from '@/components/AppBar.svelte';
    import AppLogo from '@/components/AppLogo.svelte';
    import AppLogoIcon from '@/components/AppLogoIcon.svelte';
    import NavFooter from '@/components/NavFooter.svelte';
    import NavMain from '@/components/NavMain.svelte';
    import NavUser from '@/components/NavUser.svelte';
    import { LayoutDashboard, Settings } from 'lucide-svelte';

    let { children }: { children: Snippet } = $props();

    const mainNavItems: NavItem[] = [
        { title: 'Dashboard', href: '/dashboard', icon: LayoutDashboard },
    ];

    let sidebarOpen = $state($page.props.sidebarOpen as boolean ?? true);

    function toggleSidebar() {
        sidebarOpen = !sidebarOpen;
        document.cookie = `sidebar_state=${sidebarOpen};path=/;max-age=${365 * 24 * 60 * 60};SameSite=Lax`;
    }

    const breadcrumbs: BreadcrumbItem[] = $derived(($page.props as any).breadcrumbs ?? []);
</script>

<div class="flex min-h-screen">
    <!-- Sidebar -->
    {#if sidebarOpen}
        <aside class="bg-surface-100-900 border-surface-200-800 flex w-64 shrink-0 flex-col border-r">
            <div class="border-surface-200-800 flex h-16 items-center border-b px-4">
                <a href="/dashboard">
                    <AppLogo />
                </a>
            </div>
            <nav class="flex-1 overflow-y-auto p-4">
                <NavMain items={mainNavItems} />
            </nav>
            <NavFooter />
            <div class="border-surface-200-800 border-t p-3">
                <NavUser />
            </div>
        </aside>
    {/if}

    <!-- Main content -->
    <div class="flex flex-1 flex-col">
        <AppBar {sidebarOpen} onToggleSidebar={toggleSidebar} />
        <main class="flex-1 p-6">
            {@render children()}
        </main>
    </div>
</div>

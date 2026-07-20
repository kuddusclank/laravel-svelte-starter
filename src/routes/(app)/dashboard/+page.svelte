<script lang="ts">
    import { page } from '$app/stores';
    import AppHead from '$lib/components/AppHead.svelte';
    import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '$lib/components/ui/card';
    import { Button } from '$lib/components/ui/button';
    import { ShieldCheck, UserCheck, KeyRound, Activity } from 'lucide-svelte';

    const user = $derived($page.data.user);
</script>

<AppHead title="Dashboard" />

<div class="mx-auto max-w-6xl space-y-8">
    <!-- Header -->
    <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Dashboard</h1>
            <p class="text-muted-foreground text-sm">
                Welcome back, {user?.name || 'Developer'}. Here is your application overview.
            </p>
        </div>
        <div>
            <Button
                variant="outline"
                size="sm"
                class="cursor-pointer"
                onclick={() => window.open('https://github.com/kuddusclank/laravel-svelte-starter', '_blank')}
            >
                Starter Github
            </Button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <Card>
            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                <CardTitle class="text-sm font-medium">Session Status</CardTitle>
                <UserCheck class="text-muted-foreground h-4 w-4" />
            </CardHeader>
            <CardContent>
                <div class="text-2xl font-bold">Active</div>
                <p class="text-muted-foreground mt-1 text-xs">Logged in as {user?.email}</p>
            </CardContent>
        </Card>

        <Card>
            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                <CardTitle class="text-sm font-medium">SSO Integrations</CardTitle>
                <ShieldCheck class="text-muted-foreground h-4 w-4" />
            </CardHeader>
            <CardContent>
                <div class="text-2xl font-bold">Configured</div>
                <p class="text-muted-foreground mt-1 text-xs">Fortify + Socialite active</p>
            </CardContent>
        </Card>

        <Card>
            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                <CardTitle class="text-sm font-medium">2FA Status</CardTitle>
                <KeyRound class="text-muted-foreground h-4 w-4" />
            </CardHeader>
            <CardContent>
                <div class="text-2xl font-bold">Available</div>
                <p class="text-muted-foreground mt-1 text-xs">Setup in Account settings</p>
            </CardContent>
        </Card>

        <Card>
            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                <CardTitle class="text-sm font-medium">System Health</CardTitle>
                <Activity class="h-4 w-4 text-emerald-500" />
            </CardHeader>
            <CardContent>
                <div class="text-2xl font-bold text-emerald-500">Excellent</div>
                <p class="text-muted-foreground mt-1 text-xs">API & Web servers responsive</p>
            </CardContent>
        </Card>
    </div>

    <!-- Main Workspace -->
    <div class="grid gap-6 md:grid-cols-2">
        <Card>
            <CardHeader>
                <CardTitle>Welcome to Laravelte</CardTitle>
                <CardDescription
                    >A state-of-the-art Laravel 12 + Svelte 5 boilerplate built for developers.</CardDescription
                >
            </CardHeader>
            <CardContent class="space-y-4">
                <p class="text-muted-foreground text-sm leading-relaxed">
                    You have successfully scaffolded a Laravelte project. It features system-wide dark/light mode
                    toggles, pre-configured AI Agent instruction sets, Fortify Authentication, and pre-bundled
                    Shadcn-Svelte components.
                </p>
                <div class="flex flex-wrap gap-2 pt-2">
                    <span
                        class="bg-secondary text-secondary-foreground inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium"
                    >
                        Laravel 12
                    </span>
                    <span
                        class="bg-secondary text-secondary-foreground inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium"
                    >
                        Svelte 5 Runes
                    </span>
                    <span
                        class="bg-secondary text-secondary-foreground inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium"
                    >
                        Tailwind CSS v4
                    </span>
                    <span
                        class="bg-secondary text-secondary-foreground inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium"
                    >
                        Shadcn UI
                    </span>
                </div>
            </CardContent>
        </Card>

        <Card>
            <CardHeader>
                <CardTitle>Security Checklist</CardTitle>
                <CardDescription>Complete the baseline checklist to secure your account settings.</CardDescription>
            </CardHeader>
            <CardContent>
                <ul class="space-y-3.5">
                    <li class="flex items-center gap-3">
                        <div
                            class="flex size-5 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-500"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                                class="size-3.5"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                        <span class="text-sm font-medium">Verify Email Address</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div
                            class="flex size-5 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-500"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                                class="size-3.5"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                        <span class="text-sm font-medium">Setup Secure Account Password</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div
                            class="flex size-5 items-center justify-center rounded-full bg-amber-500/10 text-amber-500"
                        >
                            <span class="size-1.5 rounded-full bg-amber-500"></span>
                        </div>
                        <span class="text-muted-foreground flex-1 text-sm font-medium"
                            >Configure Two-Factor Authentication</span
                        >
                        <a href="/settings/two-factor" class="text-primary text-xs font-semibold hover:underline"
                            >Setup</a
                        >
                    </li>
                </ul>
            </CardContent>
        </Card>
    </div>
</div>

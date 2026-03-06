<script lang="ts">
    import type { Snippet } from 'svelte';
    import AppLogoIcon from '$lib/components/AppLogoIcon.svelte';

    let { children }: { children: Snippet } = $props();
</script>

<div class="bg-surface-50-950 relative flex min-h-screen flex-col items-center justify-center p-6 sm:p-12">
    <div class="auth-gradient pointer-events-none absolute inset-0"></div>

    <div class="relative w-full max-w-md">
        <div class="mb-8 flex flex-col items-center gap-4">
            <a href="/" class="transition-transform duration-200 hover:scale-105">
                <AppLogoIcon />
            </a>
        </div>
        <div class="auth-card rounded-2xl p-8">
            {@render children()}
        </div>
    </div>
</div>

<style>
    .auth-gradient {
        background:
            radial-gradient(ellipse 80% 50% at 15% -10%, rgba(168, 85, 247, 0.12) 0%, transparent 70%),
            radial-gradient(ellipse 60% 40% at 85% 0%, rgba(251, 191, 36, 0.10) 0%, transparent 70%),
            radial-gradient(ellipse 40% 30% at 50% 100%, rgba(99, 102, 241, 0.05) 0%, transparent 70%);
    }

    .auth-card {
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(24px) saturate(1.8);
        -webkit-backdrop-filter: blur(24px) saturate(1.8);
        border: 1px solid rgba(0, 0, 0, 0.06);
        box-shadow:
            0 20px 60px -15px rgba(0, 0, 0, 0.08),
            0 0 0 1px rgba(0, 0, 0, 0.02);
    }

    :global([data-mode="dark"]) .auth-card {
        background: rgba(15, 15, 25, 0.75);
        border-color: rgba(255, 255, 255, 0.06);
        box-shadow:
            0 20px 60px -15px rgba(0, 0, 0, 0.5),
            0 0 0 1px rgba(255, 255, 255, 0.03);
    }
</style>

import type { Appearance, ResolvedAppearance } from '$lib/types';

function setCookie(name: string, value: string, days = 365) {
    const maxAge = days * 24 * 60 * 60;
    document.cookie = `${name}=${value};path=/;max-age=${maxAge};SameSite=Lax`;
}

function mediaQuery() {
    return window.matchMedia('(prefers-color-scheme: dark)');
}

function resolveAppearance(appearance: Appearance): ResolvedAppearance {
    if (appearance === 'system') {
        return mediaQuery().matches ? 'dark' : 'light';
    }
    return appearance;
}

function applyMode(resolved: ResolvedAppearance) {
    if (resolved === 'dark') {
        document.documentElement.setAttribute('data-mode', 'dark');
    } else {
        document.documentElement.removeAttribute('data-mode');
    }
}

export function createAppearanceState(initial: Appearance = 'system') {
    let appearance = $state<Appearance>(initial);

    function update(value: Appearance) {
        appearance = value;
        setCookie('appearance', value);
        applyMode(resolveAppearance(value));
    }

    // Listen for system changes
    if (typeof window !== 'undefined') {
        mediaQuery().addEventListener('change', () => {
            if (appearance === 'system') {
                applyMode(resolveAppearance('system'));
            }
        });
    }

    return {
        get appearance() { return appearance; },
        update,
    };
}

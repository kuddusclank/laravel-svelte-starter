import '../css/app.css';

import { createInertiaApp } from '@inertiajs/svelte';
import { mount, hydrate } from 'svelte';
import { route as ziggyRoute } from 'ziggy-js';
import type { RouteParams, Router } from 'ziggy-js';

declare global {
    var route: typeof ziggyRoute;
}

createInertiaApp({
    title: (title) => title ? `${title} - ${import.meta.env.VITE_APP_NAME || 'Laravel'}` : (import.meta.env.VITE_APP_NAME || 'Laravel'),
    resolve: (name) => {
        const pages = import.meta.glob<{ default: any }>('./pages/**/*.svelte', { eager: true });
        return pages[`./pages/${name}.svelte`];
    },
    setup({ el, App, props }) {
        // Set up Ziggy route helper globally
        const ziggy = props.initialPage.props.ziggy as any;
        globalThis.route = (name?: string, params?: RouteParams<string>, absolute?: boolean) =>
            ziggyRoute(name as string, params, absolute, { ...ziggy, location: new URL(ziggy.location) } as any);

        if (el?.dataset.page) {
            hydrate(App, { target: el, props });
        } else {
            mount(App, { target: el!, props });
        }
    },
});

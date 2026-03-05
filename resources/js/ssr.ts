import { createInertiaApp } from '@inertiajs/svelte';
import { render } from 'svelte/server';
import createServer from '@inertiajs/svelte/server';
import { route as ziggyRoute } from 'ziggy-js';

createServer((page) =>
    createInertiaApp({
        page,
        resolve: (name) => {
            const pages = import.meta.glob<{ default: any }>('./pages/**/*.svelte', { eager: true });
            return pages[`./pages/${name}.svelte`];
        },
        setup({ App, props }) {
            // Set up Ziggy for SSR
            const ziggy = props.initialPage.props.ziggy as any;
            globalThis.route = (name?: string, params?: any, absolute?: boolean) =>
                ziggyRoute(name as string, params, absolute, { ...ziggy, location: new URL(ziggy.location) } as any);

            return render(App, { props });
        },
    }),
);

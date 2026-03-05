import type { route as ziggyRoute } from 'ziggy-js';

declare global {
    var route: typeof ziggyRoute;
}

interface ImportMeta {
    glob<T = Record<string, unknown>>(pattern: string, options?: { eager?: boolean }): Record<string, T>;
    env: Record<string, string>;
}

export {};

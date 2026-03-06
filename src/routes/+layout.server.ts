import { laravelFetch } from '$lib/api';
import type { LayoutServerLoad } from './$types';

export const load: LayoutServerLoad = async ({ locals }) => {
    const [userRes, providersRes] = await Promise.all([
        laravelFetch('/api/user', locals).catch(() => null),
        laravelFetch('/api/social-providers', locals).catch(() => null),
    ]);

    let user = null;
    if (userRes && userRes.ok) {
        user = await userRes.json();
    }

    let socialProviders: string[] = [];
    if (providersRes && providersRes.ok) {
        socialProviders = await providersRes.json();
    }

    return {
        user,
        socialProviders,
    };
};

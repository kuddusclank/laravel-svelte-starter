import { redirect } from '@sveltejs/kit';
import { laravelFetch, forwardCookies } from '$lib/api';
import type { RequestHandler } from './$types';

export const GET: RequestHandler = async ({ params, url, locals, cookies }) => {
    // Forward the callback query string (code, state, etc.) to Laravel
    const queryString = url.search;
    const res = await laravelFetch(`/auth/${params.provider}/callback${queryString}`, locals);

    forwardCookies(res, cookies);

    // Laravel redirects to /dashboard on success
    redirect(303, '/dashboard');
};

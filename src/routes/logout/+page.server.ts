import { redirect } from '@sveltejs/kit';
import { laravelFetch, forwardCookies } from '$lib/api';
import type { Actions } from './$types';

export const actions: Actions = {
    default: async ({ locals, cookies }) => {
        const res = await laravelFetch('/logout', locals, {
            method: 'POST',
        });

        forwardCookies(res, cookies);

        redirect(303, '/');
    },
};

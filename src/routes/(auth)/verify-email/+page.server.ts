import { fail, redirect } from '@sveltejs/kit';
import { laravelFetch, forwardCookies } from '$lib/api';
import type { Actions, PageServerLoad } from './$types';

export const load: PageServerLoad = async ({ parent }) => {
    const { user } = await parent();
    if (!user) {
        redirect(303, '/login');
    }
    return {};
};

export const actions: Actions = {
    resend: async ({ locals, cookies }) => {
        const res = await laravelFetch('/email/verification-notification', locals, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
        });

        forwardCookies(res, cookies);

        if (res.ok || res.status === 302) {
            return { status: 'verification-link-sent' };
        }

        return fail(res.status, {
            errors: { email: 'Failed to send verification email.' },
        });
    },
    logout: async ({ locals, cookies }) => {
        const res = await laravelFetch('/logout', locals, {
            method: 'POST',
        });

        forwardCookies(res, cookies);
        redirect(303, '/');
    },
};

import { fail } from '@sveltejs/kit';
import { laravelFetch, forwardCookies } from '$lib/api';
import type { Actions } from './$types';

export const actions: Actions = {
    default: async ({ request, locals, cookies }) => {
        const formData = await request.formData();
        const email = formData.get('email') as string;

        const res = await laravelFetch('/forgot-password', locals, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email }),
        });

        forwardCookies(res, cookies);

        if (res.ok || res.status === 302) {
            return { status: 'We have emailed your password reset link.' };
        }

        if (res.status === 422) {
            const data = await res.json();
            return fail(422, {
                errors: data.errors ?? {},
                email,
            });
        }

        return fail(res.status, {
            errors: { email: 'Something went wrong. Please try again.' },
            email,
        });
    },
};

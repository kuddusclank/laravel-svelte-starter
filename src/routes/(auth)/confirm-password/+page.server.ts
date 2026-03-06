import { fail, redirect } from '@sveltejs/kit';
import { laravelFetch, forwardCookies } from '$lib/api';
import type { Actions } from './$types';

export const actions: Actions = {
    default: async ({ request, locals, cookies }) => {
        const formData = await request.formData();
        const password = formData.get('password') as string;

        const res = await laravelFetch('/user/confirm-password', locals, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ password }),
        });

        forwardCookies(res, cookies);

        if (res.ok || res.status === 302 || res.status === 201) {
            redirect(303, '/dashboard');
        }

        if (res.status === 422) {
            const data = await res.json();
            return fail(422, {
                errors: data.errors ?? {},
            });
        }

        return fail(res.status, {
            errors: { password: 'The provided password is incorrect.' },
        });
    },
};

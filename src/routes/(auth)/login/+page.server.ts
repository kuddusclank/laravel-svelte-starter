import { fail, redirect } from '@sveltejs/kit';
import { laravelFetch, forwardCookies } from '$lib/api';
import type { Actions } from './$types';

export const actions: Actions = {
    default: async ({ request, locals, cookies }) => {
        const formData = await request.formData();
        const email = formData.get('email') as string;
        const password = formData.get('password') as string;
        const remember = formData.get('remember') === 'on';

        const res = await laravelFetch('/login', locals, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password, remember }),
        });

        forwardCookies(res, cookies);

        if (res.ok || res.status === 302 || res.status === 200) {
            redirect(303, '/dashboard');
        }

        if (res.status === 422) {
            const data = await res.json();
            return fail(422, {
                errors: data.errors ?? {},
                email,
            });
        }

        return fail(res.status, {
            errors: { email: 'Login failed. Please try again.' },
            email,
        });
    },
};

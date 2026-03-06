import { fail, redirect } from '@sveltejs/kit';
import { laravelFetch, forwardCookies } from '$lib/api';
import type { Actions } from './$types';

export const actions: Actions = {
    default: async ({ request, locals, cookies }) => {
        const formData = await request.formData();
        const name = formData.get('name') as string;
        const email = formData.get('email') as string;
        const password = formData.get('password') as string;
        const password_confirmation = formData.get('password_confirmation') as string;

        const res = await laravelFetch('/register', locals, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name, email, password, password_confirmation }),
        });

        forwardCookies(res, cookies);

        if (res.ok || res.status === 302 || res.status === 201) {
            redirect(303, '/dashboard');
        }

        if (res.status === 422) {
            const data = await res.json();
            return fail(422, {
                errors: data.errors ?? {},
                name,
                email,
            });
        }

        return fail(res.status, {
            errors: { email: 'Registration failed. Please try again.' },
            name,
            email,
        });
    },
};

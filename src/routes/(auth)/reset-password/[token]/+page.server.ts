import { fail, redirect } from '@sveltejs/kit';
import { laravelFetch, forwardCookies } from '$lib/api';
import type { Actions, PageServerLoad } from './$types';

export const load: PageServerLoad = async ({ params, url }) => {
    return {
        token: params.token,
        email: url.searchParams.get('email') ?? '',
    };
};

export const actions: Actions = {
    default: async ({ request, locals, cookies }) => {
        const formData = await request.formData();
        const token = formData.get('token') as string;
        const email = formData.get('email') as string;
        const password = formData.get('password') as string;
        const password_confirmation = formData.get('password_confirmation') as string;

        const res = await laravelFetch('/reset-password', locals, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ token, email, password, password_confirmation }),
        });

        forwardCookies(res, cookies);

        if (res.ok || res.status === 302) {
            redirect(303, '/login');
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

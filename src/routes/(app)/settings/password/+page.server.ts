import { fail } from '@sveltejs/kit';
import { laravelFetch, forwardCookies } from '$lib/api';
import type { Actions } from './$types';

export const actions: Actions = {
    default: async ({ request, locals, cookies }) => {
        const formData = await request.formData();
        const current_password = formData.get('current_password') as string;
        const password = formData.get('password') as string;
        const password_confirmation = formData.get('password_confirmation') as string;

        const res = await laravelFetch('/user/password', locals, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ current_password, password, password_confirmation }),
        });

        forwardCookies(res, cookies);

        if (res.ok || res.status === 302) {
            return { success: true };
        }

        if (res.status === 422) {
            const data = await res.json();
            return fail(422, {
                errors: data.errors ?? {},
            });
        }

        return fail(res.status, {
            errors: { current_password: 'Failed to update password.' },
        });
    },
};

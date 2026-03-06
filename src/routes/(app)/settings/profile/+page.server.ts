import { fail } from '@sveltejs/kit';
import { laravelFetch, forwardCookies } from '$lib/api';
import type { Actions, PageServerLoad } from './$types';

export const load: PageServerLoad = async ({ parent }) => {
    const { user } = await parent();
    return {
        mustVerifyEmail: true,
        user,
    };
};

export const actions: Actions = {
    updateProfile: async ({ request, locals, cookies }) => {
        const formData = await request.formData();
        const name = formData.get('name') as string;
        const email = formData.get('email') as string;

        const res = await laravelFetch('/api/settings/profile', locals, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name, email }),
        });

        forwardCookies(res, cookies);

        if (res.ok) {
            return { success: true };
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
            errors: { name: 'Failed to update profile.' },
            name,
            email,
        });
    },
    deleteAccount: async ({ request, locals, cookies }) => {
        const formData = await request.formData();
        const password = formData.get('password') as string;

        const res = await laravelFetch('/api/settings/profile', locals, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ password }),
        });

        forwardCookies(res, cookies);

        if (res.ok) {
            return { deleted: true };
        }

        if (res.status === 422) {
            const data = await res.json();
            return fail(422, {
                deleteErrors: data.errors ?? {},
            });
        }

        return fail(res.status, {
            deleteErrors: { password: 'Failed to delete account.' },
        });
    },
};

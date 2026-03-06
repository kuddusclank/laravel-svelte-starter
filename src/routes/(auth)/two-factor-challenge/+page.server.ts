import { fail, redirect } from '@sveltejs/kit';
import { laravelFetch, forwardCookies } from '$lib/api';
import type { Actions } from './$types';

export const actions: Actions = {
    default: async ({ request, locals, cookies }) => {
        const formData = await request.formData();
        const code = formData.get('code') as string;
        const recovery_code = formData.get('recovery_code') as string;

        const body: Record<string, string> = {};
        if (code) body.code = code;
        if (recovery_code) body.recovery_code = recovery_code;

        const res = await laravelFetch('/two-factor-challenge', locals, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(body),
        });

        forwardCookies(res, cookies);

        if (res.ok || res.status === 302) {
            redirect(303, '/dashboard');
        }

        if (res.status === 422) {
            const data = await res.json();
            return fail(422, {
                errors: data.errors ?? {},
            });
        }

        return fail(res.status, {
            errors: code
                ? { code: 'The provided code is invalid.' }
                : { recovery_code: 'The provided recovery code is invalid.' },
        });
    },
};

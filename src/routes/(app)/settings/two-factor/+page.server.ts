import { fail } from '@sveltejs/kit';
import { laravelFetch, forwardCookies } from '$lib/api';
import type { Actions, PageServerLoad } from './$types';

export const load: PageServerLoad = async ({ locals }) => {
    const userRes = await laravelFetch('/api/user', locals);
    if (!userRes.ok) {
        return {
            twoFactorEnabled: false,
            twoFactorConfirmed: false,
        };
    }

    const user = await userRes.json();
    return {
        twoFactorEnabled: user.two_factor_enabled ?? !!user.two_factor_confirmed_at,
        twoFactorConfirmed: !!user.two_factor_confirmed_at,
    };
};

export const actions: Actions = {
    enable: async ({ locals, cookies }) => {
        const res = await laravelFetch('/user/two-factor-authentication', locals, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
        });

        forwardCookies(res, cookies);

        if (!res.ok && res.status !== 302) {
            return fail(res.status, { error: 'Failed to enable 2FA.' });
        }

        // Fetch QR code and secret key
        const [qrRes, secretRes] = await Promise.all([
            laravelFetch('/user/two-factor-qr-code', locals),
            laravelFetch('/user/two-factor-secret-key', locals),
        ]);

        let qrSvg = '';
        let secretKey = '';

        if (qrRes.ok) {
            const qrData = await qrRes.json();
            qrSvg = qrData.svg;
        }
        if (secretRes.ok) {
            const secretData = await secretRes.json();
            secretKey = secretData.secretKey;
        }

        return { showSetup: true, qrSvg, secretKey };
    },
    confirm: async ({ request, locals, cookies }) => {
        const formData = await request.formData();
        const code = formData.get('code') as string;

        const res = await laravelFetch('/user/confirmed-two-factor-authentication', locals, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ code }),
        });

        forwardCookies(res, cookies);

        if (res.ok || res.status === 302) {
            return { confirmed: true };
        }

        return fail(422, { error: 'Invalid code. Please try again.' });
    },
    disable: async ({ locals, cookies }) => {
        const res = await laravelFetch('/user/two-factor-authentication', locals, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' },
        });

        forwardCookies(res, cookies);

        if (res.ok || res.status === 302) {
            return { disabled: true };
        }

        return fail(res.status, { error: 'Failed to disable 2FA.' });
    },
    showRecoveryCodes: async ({ locals }) => {
        const res = await laravelFetch('/user/two-factor-recovery-codes', locals);

        if (res.ok) {
            const codes = await res.json();
            return { recoveryCodes: codes };
        }

        return fail(res.status, { error: 'Failed to fetch recovery codes.' });
    },
    regenerateRecoveryCodes: async ({ locals, cookies }) => {
        const res = await laravelFetch('/user/two-factor-recovery-codes', locals, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
        });

        forwardCookies(res, cookies);

        // After regeneration, fetch the new codes
        const codesRes = await laravelFetch('/user/two-factor-recovery-codes', locals);
        if (codesRes.ok) {
            const codes = await codesRes.json();
            return { recoveryCodes: codes };
        }

        return { recoveryCodes: [] };
    },
};

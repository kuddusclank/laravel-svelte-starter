import { redirect } from '@sveltejs/kit';
import { LARAVEL_URL } from '$lib/api';
import type { RequestHandler } from './$types';

export const GET: RequestHandler = async ({ params }) => {
    redirect(302, `${LARAVEL_URL}/auth/${params.provider}/redirect`);
};

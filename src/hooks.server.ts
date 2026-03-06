import type { Handle } from '@sveltejs/kit';

export const handle: Handle = async ({ event, resolve }) => {
    const cookieHeader = event.request.headers.get('cookie') ?? '';
    event.locals.cookieHeader = cookieHeader;

    // Extract XSRF-TOKEN from cookies (URL-decoded)
    const xsrfMatch = cookieHeader.match(/XSRF-TOKEN=([^;]+)/);
    event.locals.xsrfToken = xsrfMatch ? decodeURIComponent(xsrfMatch[1]) : '';

    return resolve(event);
};

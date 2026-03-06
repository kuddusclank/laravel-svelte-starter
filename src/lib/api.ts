import { env } from '$env/dynamic/private';

const LARAVEL_URL = env.LARAVEL_URL ?? 'http://localhost:8000';

interface LaravelFetchOptions extends Omit<RequestInit, 'headers'> {
    headers?: Record<string, string>;
}

/**
 * Server-side fetch helper that forwards cookies and CSRF tokens to Laravel.
 * Returns the raw Response so callers can handle status codes and Set-Cookie headers.
 */
export async function laravelFetch(
    path: string,
    locals: App.Locals,
    options: LaravelFetchOptions = {},
): Promise<Response> {
    const url = `${LARAVEL_URL}${path}`;

    const headers: Record<string, string> = {
        Accept: 'application/json',
        Cookie: locals.cookieHeader,
        Referer: LARAVEL_URL,
        Origin: LARAVEL_URL,
        ...options.headers,
    };

    // Add CSRF token for state-changing requests
    if (options.method && !['GET', 'HEAD'].includes(options.method.toUpperCase())) {
        if (locals.xsrfToken) {
            headers['X-XSRF-TOKEN'] = locals.xsrfToken;
        }
    }

    return fetch(url, {
        ...options,
        headers,
        redirect: 'manual',
    });
}

/**
 * Forward Set-Cookie headers from a Laravel response to the SvelteKit response.
 */
export function forwardCookies(laravelResponse: Response, cookies: import('@sveltejs/kit').Cookies): void {
    const setCookies = laravelResponse.headers.getSetCookie();
    for (const cookie of setCookies) {
        const [nameValue, ...parts] = cookie.split(';');
        const [name, ...valueParts] = nameValue.split('=');
        const value = valueParts.join('=');

        const opts: Record<string, any> = { path: '/' };
        for (const part of parts) {
            const trimmed = part.trim().toLowerCase();
            if (trimmed.startsWith('max-age=')) opts.maxAge = parseInt(trimmed.slice(8));
            if (trimmed.startsWith('expires=')) opts.expires = new Date(part.trim().slice(8));
            if (trimmed === 'httponly') opts.httpOnly = true;
            if (trimmed === 'secure') opts.secure = true;
            if (trimmed.startsWith('samesite=')) opts.sameSite = part.trim().slice(9).toLowerCase();
            if (trimmed.startsWith('path=')) opts.path = part.trim().slice(5);
        }

        cookies.set(name.trim(), decodeURIComponent(value), opts);
    }
}

/**
 * Get a CSRF cookie from Laravel (hits /sanctum/csrf-cookie or any GET route).
 * Useful for initializing the XSRF-TOKEN before POST requests.
 */
export async function getCsrfCookie(locals: App.Locals): Promise<Response> {
    return laravelFetch('/sanctum/csrf-cookie', locals);
}

export { LARAVEL_URL };

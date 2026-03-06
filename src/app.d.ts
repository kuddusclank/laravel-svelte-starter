/// <reference types="@sveltejs/kit" />

declare namespace App {
    interface Locals {
        cookieHeader: string;
        xsrfToken: string;
    }

    interface PageData {
        user: import('$lib/types').User | null;
        socialProviders: import('$lib/types').SocialProvider[];
    }
}

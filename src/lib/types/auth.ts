export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    two_factor_enabled: boolean;
    two_factor_confirmed_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Auth {
    user: User | null;
}

import eslintPluginSvelte from 'eslint-plugin-svelte';

export default [
    ...eslintPluginSvelte.configs.recommended,
    {
        rules: {
            'no-unused-vars': 'warn',
        },
    },
    {
        ignores: [
            'vendor/**',
            'node_modules/**',
            'public/**',
            'bootstrap/ssr/**',
            'storage/**',
        ],
    },
];

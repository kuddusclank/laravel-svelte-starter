<?php

namespace App\Console\Commands;

use App\Console\Support\EnvFileEditor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\note;
use function Laravel\Prompts\password;
use function Laravel\Prompts\select;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\table;
use function Laravel\Prompts\text;

class SetupCommand extends Command
{
    protected $signature = 'app:setup';

    protected $description = 'Interactive setup wizard for the Laravel Svelte Starter';

    protected array $providers = [
        'github' => ['label' => 'GitHub', 'prefix' => 'GITHUB'],
        'facebook' => ['label' => 'Facebook', 'prefix' => 'FACEBOOK'],
        'x' => ['label' => 'X', 'prefix' => 'X'],
        'google' => ['label' => 'Google', 'prefix' => 'GOOGLE'],
        'apple' => ['label' => 'Apple', 'prefix' => 'APPLE'],
    ];

    public function handle(): int
    {
        note('Welcome to the Laravel Svelte Starter setup wizard!');
        $this->newLine();

        // Step 1: Framework selection
        $framework = select(
            label: 'Which frontend framework would you like to use?',
            options: ['Svelte (Inertia)', 'SvelteKit'],
            default: 'Svelte (Inertia)',
        );

        $useSvelteKit = $framework === 'SvelteKit';

        // Step 2: Email verification
        $emailVerification = confirm(
            label: 'Would you like to enable email verification?',
            default: false,
        );

        // Step 3: SSO providers
        $selectedProviders = multiselect(
            label: 'Which SSO providers would you like to configure?',
            options: array_map(fn ($p) => $p['label'], $this->providers),
            hint: 'Use spacebar to select, enter to confirm. You can skip all.',
        );

        // Step 4: Collect credentials for each selected provider
        $credentials = [];
        $env = new EnvFileEditor;
        $appUrl = $env->get('APP_URL', 'http://localhost');

        foreach ($selectedProviders as $key => $label) {
            $provider = array_values(array_filter(
                $this->providers,
                fn ($p) => $p['label'] === $label,
            ));

            if (empty($provider)) {
                continue;
            }

            $provider = $provider[0];
            $slug = array_search($provider, $this->providers);

            note("--- Configuring {$provider['label']} ---");
            info("Callback URL: {$appUrl}/auth/{$slug}/callback");

            $clientId = text(
                label: "{$provider['label']} Client ID",
                required: true,
            );

            $clientSecret = password(
                label: "{$provider['label']} Client Secret",
                required: true,
            );

            $credentials[$slug] = [
                'label' => $provider['label'],
                'prefix' => $provider['prefix'],
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
            ];
        }

        // Step 5: Summary
        $this->newLine();
        note('--- Configuration Summary ---');

        $rows = [
            ['Framework', $useSvelteKit ? 'SvelteKit' : 'Svelte (Inertia)'],
            ['Email Verification', $emailVerification ? 'Enabled' : 'Disabled'],
            ['SSO Providers', empty($credentials) ? 'None' : implode(', ', array_map(fn ($c) => $c['label'], $credentials))],
        ];

        foreach ($credentials as $slug => $cred) {
            $rows[] = ["{$cred['label']} Client ID", $cred['client_id']];
            $rows[] = ["{$cred['label']} Client Secret", str_repeat('*', min(strlen($cred['client_secret']), 20))];
        }

        table(['Setting', 'Value'], $rows);

        if (! confirm('Proceed with this configuration?', default: true)) {
            info('Setup cancelled. You can re-run this wizard with: php artisan app:setup');

            return self::SUCCESS;
        }

        // Step 6: Apply configuration
        note('Applying configuration...');

        // Write SSO credentials to .env
        if (! empty($credentials)) {
            $env = new EnvFileEditor;
            $appUrl = $env->get('APP_URL', 'http://localhost');

            foreach ($credentials as $slug => $cred) {
                $env->set("{$cred['prefix']}_CLIENT_ID", $cred['client_id']);
                $env->set("{$cred['prefix']}_CLIENT_SECRET", $cred['client_secret']);
                $env->set("{$cred['prefix']}_REDIRECT_URI", "{$appUrl}/auth/{$slug}/callback");
            }

            $env->save();
            info('SSO credentials written to .env');
        }

        // Toggle email verification
        $this->toggleEmailVerification($emailVerification);
        info('Email verification '.($emailVerification ? 'enabled' : 'disabled'));

        // Step 7: Framework-specific scaffolding
        $this->newLine();
        note('Scaffolding project...');

        if ($useSvelteKit) {
            $this->scaffoldSvelteKit();
        } else {
            $this->scaffoldSvelteInertia();
        }

        // Step 8: Common scaffolding
        $env = new EnvFileEditor;
        if (empty($env->get('APP_KEY'))) {
            spin(
                callback: fn () => $this->callSilently('key:generate', ['--force' => true]),
                message: 'Generating application key...',
            );
            info('Application key generated');
        }

        $dbPath = database_path('database.sqlite');
        if (! file_exists($dbPath)) {
            spin(
                callback: function () use ($dbPath) {
                    touch($dbPath);
                },
                message: 'Creating SQLite database...',
            );
            info('SQLite database created');
        }

        spin(
            callback: fn () => $this->callSilently('migrate', ['--graceful' => true, '--force' => true]),
            message: 'Running migrations...',
        );
        info('Migrations complete');

        // Remove old node_modules to force fresh install after package.json change
        if ($useSvelteKit && is_dir(base_path('node_modules'))) {
            spin(
                callback: fn () => File::deleteDirectory(base_path('node_modules')),
                message: 'Removing old node_modules...',
            );
        }

        if (! is_dir(base_path('node_modules'))) {
            spin(
                callback: fn () => exec('cd '.base_path().' && npm install 2>&1'),
                message: 'Installing npm dependencies...',
            );
            info('npm dependencies installed');
        }

        spin(
            callback: fn () => exec('cd '.base_path().' && npm run build 2>&1'),
            message: 'Building frontend assets...',
        );
        info('Frontend assets built');

        // Done
        $this->newLine();
        note('Setup complete! Run the following command to start developing:');
        $this->newLine();
        info('composer dev');

        return self::SUCCESS;
    }

    protected function scaffoldSvelteKit(): void
    {
        $base = base_path();

        // 1. Remove Inertia frontend files
        spin(
            callback: function () use ($base) {
                File::deleteDirectory("{$base}/resources/js");
                File::deleteDirectory("{$base}/resources/css");
                File::delete("{$base}/resources/views/app.blade.php");

                // Remove Inertia config files
                foreach (['vite.config.ts', 'svelte.config.js', 'tsconfig.json', 'package.json', 'package-lock.json'] as $file) {
                    File::delete("{$base}/{$file}");
                }

                File::deleteDirectory("{$base}/node_modules");
            },
            message: 'Removing Inertia frontend files...',
        );
        info('Inertia frontend files removed');

        // 2. Promote sveltekit/ contents to project root
        spin(
            callback: function () use ($base) {
                $sveltekitDir = "{$base}/sveltekit";
                if (is_dir($sveltekitDir)) {
                    // Copy all files from sveltekit/ to project root
                    $this->copyDirectory($sveltekitDir, $base);
                    File::deleteDirectory($sveltekitDir);
                }
            },
            message: 'Promoting SvelteKit files to project root...',
        );
        info('SvelteKit files promoted');

        // 3. Modify bootstrap/app.php: add API routes, remove HandleInertiaRequests
        spin(
            callback: function () use ($base) {
                $appFile = "{$base}/bootstrap/app.php";
                $contents = file_get_contents($appFile);

                // Add API route
                $contents = str_replace(
                    "->withRouting(\n        web: __DIR__.'/../routes/web.php',",
                    "->withRouting(\n        web: __DIR__.'/../routes/web.php',\n        api: __DIR__.'/../routes/api.php',",
                    $contents,
                );

                // Remove HandleInertiaRequests from middleware
                $contents = str_replace(
                    "        \$middleware->web(append: [\n            \\App\\Http\\Middleware\\HandleInertiaRequests::class,\n            AddLinkHeadersForPreloadedAssets::class,\n            HandleAppearance::class,\n        ]);",
                    "        \$middleware->web(append: [\n            HandleAppearance::class,\n        ]);",
                    $contents,
                );

                // Remove unused import
                $contents = str_replace(
                    "use Illuminate\\Http\\Middleware\\AddLinkHeadersForPreloadedAssets;\n",
                    '',
                    $contents,
                );

                file_put_contents($appFile, $contents);
            },
            message: 'Updating bootstrap/app.php...',
        );
        info('bootstrap/app.php updated');

        // 4. Modify FortifyServiceProvider: remove Inertia view bindings
        spin(
            callback: function () use ($base) {
                $fortifyProvider = "{$base}/app/Providers/FortifyServiceProvider.php";
                $contents = file_get_contents($fortifyProvider);

                // Remove the Inertia import
                $contents = str_replace("use Inertia\\Inertia;\n", '', $contents);

                // Remove all Fortify view bindings (everything from Fortify::loginView to the last view binding)
                $contents = preg_replace(
                    '/\n\s*Fortify::loginView\(.*?\);\n\s*Fortify::registerView\(.*?\);\n\s*Fortify::requestPasswordResetLinkView\(.*?\);\n\s*Fortify::resetPasswordView\(.*?\);\n\s*Fortify::verifyEmailView\(.*?\);\n\s*Fortify::confirmPasswordView\(.*?\);\n\s*Fortify::twoFactorChallengeView\(.*?\);/s',
                    '',
                    $contents,
                );

                file_put_contents($fortifyProvider, $contents);
            },
            message: 'Updating FortifyServiceProvider...',
        );
        info('FortifyServiceProvider updated');

        // 5. Set fortify views to false
        spin(
            callback: function () use ($base) {
                $fortifyConfig = "{$base}/config/fortify.php";
                $contents = file_get_contents($fortifyConfig);
                $contents = str_replace("'views' => true,", "'views' => false,", $contents);
                file_put_contents($fortifyConfig, $contents);
            },
            message: 'Disabling Fortify views...',
        );
        info('Fortify views disabled');

        // 6. Rewrite routes/web.php: keep only social auth routes
        spin(
            callback: function () use ($base) {
                $webRoutes = <<<'PHP'
<?php

use App\Http\Controllers\Auth\SocialAuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
    Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');
});
PHP;
                file_put_contents("{$base}/routes/web.php", $webRoutes);
            },
            message: 'Updating routes/web.php...',
        );
        info('routes/web.php updated');

        // 7. Remove routes/settings.php
        File::delete("{$base}/routes/settings.php");
        info('routes/settings.php removed');

        // 8. Remove Inertia/Ziggy composer packages
        spin(
            callback: fn () => exec('cd '.base_path().' && composer remove inertiajs/inertia-laravel tightenco/ziggy --no-interaction 2>&1'),
            message: 'Removing Inertia and Ziggy packages...',
        );
        info('Inertia and Ziggy packages removed');

        // 9. Set LARAVEL_URL in .env
        $env = new EnvFileEditor;
        $env->set('LARAVEL_URL', 'http://localhost:8000');
        $env->save();
        info('LARAVEL_URL set in .env');

        // 10. Update composer.json dev script for SvelteKit
        spin(
            callback: function () use ($base) {
                $composerJson = json_decode(file_get_contents("{$base}/composer.json"), true);
                $composerJson['scripts']['dev'] = [
                    'npx concurrently -c "#93c5fd,#c4b5fd,#fb923c" "php artisan serve" "npm run dev" --names=laravel,sveltekit',
                ];
                file_put_contents("{$base}/composer.json", json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n");
            },
            message: 'Updating composer.json dev script...',
        );
        info('composer.json dev script updated');
    }

    protected function scaffoldSvelteInertia(): void
    {
        $base = base_path();

        // Remove SvelteKit directory if it exists
        if (is_dir("{$base}/sveltekit")) {
            spin(
                callback: fn () => File::deleteDirectory("{$base}/sveltekit"),
                message: 'Removing SvelteKit files...',
            );
            info('SvelteKit files removed');
        }

        // Remove API routes file if it exists
        if (file_exists("{$base}/routes/api.php")) {
            File::delete("{$base}/routes/api.php");
            info('routes/api.php removed');
        }

        // Remove API controller directory if it exists
        if (is_dir("{$base}/app/Http/Controllers/Api")) {
            File::deleteDirectory("{$base}/app/Http/Controllers/Api");
            info('Api controller directory removed');
        }

        // Remove API route reference from bootstrap/app.php
        $appFile = "{$base}/bootstrap/app.php";
        $contents = file_get_contents($appFile);
        $contents = str_replace(
            "        web: __DIR__.'/../routes/web.php',\n        api: __DIR__.'/../routes/api.php',",
            "        web: __DIR__.'/../routes/web.php',",
            $contents,
        );
        file_put_contents($appFile, $contents);
    }

    protected function copyDirectory(string $source, string $destination): void
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $target = $destination.DIRECTORY_SEPARATOR.$iterator->getSubPathname();

            if ($item->isDir()) {
                if (! is_dir($target)) {
                    mkdir($target, 0755, true);
                }
            } else {
                $dir = dirname($target);
                if (! is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                copy($item->getPathname(), $target);
            }
        }
    }

    protected function toggleEmailVerification(bool $enable): void
    {
        $userModelPath = app_path('Models/User.php');
        $contents = file_get_contents($userModelPath);

        if ($enable) {
            // Uncomment the import
            $contents = str_replace(
                '// use Illuminate\Contracts\Auth\MustVerifyEmail;',
                'use Illuminate\Contracts\Auth\MustVerifyEmail;',
                $contents,
            );

            // Add implements MustVerifyEmail if not already present
            if (! str_contains($contents, 'implements MustVerifyEmail')) {
                $contents = str_replace(
                    'class User extends Authenticatable',
                    'class User extends Authenticatable implements MustVerifyEmail',
                    $contents,
                );
            }
        } else {
            // Comment out the import (only if currently uncommented)
            if (str_contains($contents, 'use Illuminate\Contracts\Auth\MustVerifyEmail;')
                && ! str_contains($contents, '// use Illuminate\Contracts\Auth\MustVerifyEmail;')) {
                $contents = str_replace(
                    'use Illuminate\Contracts\Auth\MustVerifyEmail;',
                    '// use Illuminate\Contracts\Auth\MustVerifyEmail;',
                    $contents,
                );
            }

            // Remove implements MustVerifyEmail
            $contents = str_replace(
                'class User extends Authenticatable implements MustVerifyEmail',
                'class User extends Authenticatable',
                $contents,
            );
        }

        file_put_contents($userModelPath, $contents);
    }
}

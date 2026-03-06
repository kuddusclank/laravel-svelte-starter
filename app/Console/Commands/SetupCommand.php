<?php

namespace App\Console\Commands;

use App\Console\Support\EnvFileEditor;
use Illuminate\Console\Command;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\note;
use function Laravel\Prompts\password;
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

        // Step 1: Email verification
        $emailVerification = confirm(
            label: 'Would you like to enable email verification?',
            default: false,
        );

        // Step 2: SSO providers
        $selectedProviders = multiselect(
            label: 'Which SSO providers would you like to configure?',
            options: array_map(fn ($p) => $p['label'], $this->providers),
            hint: 'Use spacebar to select, enter to confirm. You can skip all.',
        );

        // Step 3: Collect credentials for each selected provider
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

        // Step 4: Summary
        $this->newLine();
        note('--- Configuration Summary ---');

        $rows = [
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

        // Step 5: Apply configuration
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

        // Step 6: Common scaffolding
        $this->newLine();
        note('Scaffolding project...');

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

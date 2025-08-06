<?php

namespace iLaravel\Core\iApp\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RemoveSanctum extends Command
{
    protected $signature = 'remove:sanctum {--base-class= : Fully-qualified class name (FQCN) that User should extend, e.g. "App\\Models\\BaseUser"}';
    protected $description = 'Completely remove Laravel Sanctum from the project, revert to Passport and optionally change Models/User extends class';

    public function handle()
    {
        $this->info('🚀 Starting Sanctum removal process...');

        // 0. Safety check - ask for confirmation
        if (!$this->confirm('Are you sure you want to remove Sanctum and switch to Passport? Make sure you have a backup.')) {
            $this->info('Aborted by user.');
            return Command::SUCCESS;
        }

        // 1. Remove Sanctum package via Composer
        $this->info('📦 Removing Sanctum package via Composer...');
        $out = null;
        $status = null;
        exec('composer remove laravel/sanctum 2>&1', $out, $status);
        $this->line(implode("\n", $out));
        if ($status !== 0) {
            $this->error('Failed to remove laravel/sanctum via composer. You may need to run it manually.');
        } else {
            $this->info('✅ laravel/sanctum removed from composer.');
        }

        // 2. Remove config file
        if (File::exists(config_path('sanctum.php'))) {
            File::delete(config_path('sanctum.php'));
            $this->info('🗑 Deleted config/sanctum.php');
        }

        // 3. Remove Sanctum trait from User model and optionally change base class
        $userModel = app_path('Models/User.php');
        if (File::exists($userModel)) {
            $content = File::get($userModel);

            // Remove import line if exists
            $content = preg_replace('/use\s+Laravel\\\\Sanctum\\\\HasApiTokens;\s*/', '', $content);

            // Remove trait usage inside class use(...) list
            $content = preg_replace('/\bHasApiTokens\s*,?\s*/', '', $content);

            // Also remove trailing commas or double commas cleanup
            $content = preg_replace('/,\s*,/', ',', $content);
            $content = preg_replace('/\(\s*,\s*/', '(', $content);

            // Optionally change base class if option provided
            $baseClass = $this->option('base-class')?:"\\App\\Models\\iUser";
            if ($baseClass) {
                // Determine if FQCN provided
                $fqcn = trim($baseClass, '\\');
                $shortName = substr($fqcn, strrpos($fqcn, '\\') !== false ? strrpos($fqcn, '\\') + 1 : 0);

                // Add use statement if backslashes present
                if (strpos($fqcn, '\\') !== false) {
                    // Ensure use statement not already present
                    if (!preg_match('/use\s+' . preg_quote($fqcn, '/') . '\s*;/', $content)) {
                        // insert after namespace line
                        $content = preg_replace('/(namespace\s+[^\r\n]+;\s*)/i', "$1\nuse {$fqcn};\n", $content, 1);
                    }
                }

                // Replace extends Authenticatable (or any extends ...) to extends ShortName
                $content = preg_replace('/extends\s+[A-Za-z0-9_\\\\]+/', 'extends ' . $shortName, $content, 1);

                $this->info("🔧 User model will now extend: {$fqcn} (short: {$shortName})");
            } else {
                $this->info('ℹ️ No base-class provided; only HasApiTokens trait removed from User model.');
            }

            File::put($userModel, $content);
            $this->info('✂ Updated app/Models/User.php');
        } else {
            $this->warn('app/Models/User.php not found — skipping model edits.');
        }

        // 4. Remove Sanctum middleware from Kernel
        $kernelFile = app_path('Http/Kernel.php');
        if (File::exists($kernelFile)) {
            $content = File::get($kernelFile);
            $content = str_replace("\\Laravel\\Sanctum\\Http\\Middleware\\EnsureFrontendRequestsAreStateful::class,\n", '', $content);
            $content = str_replace("\\Laravel\\Sanctum\\Http\\Middleware\\EnsureFrontendRequestsAreStateful::class,", '', $content);
            File::put($kernelFile, $content);
            $this->info('✂ Removed Sanctum middleware from Kernel.php (if it existed).');
        }

        // 5. Remove migration files related to Sanctum
        $migrations = glob(database_path('migrations/*create_personal_access_tokens_table.php'));
        foreach ($migrations as $migration) {
            File::delete($migration);
            $this->info("🗑 Deleted migration file: " . basename($migration));
        }
        if (empty($migrations)) {
            $this->info('ℹ️ No personal_access_tokens migration files found.');
        }

        // 6. Update API guard to use Passport in config/auth.php
        $authConfig = config_path('auth.php');
        if (File::exists($authConfig)) {
            $content = File::get($authConfig);
            // Attempt a conservative replacement: set driver value for api guard to passport
            $content = preg_replace(
                "/('api'\s*=>\s*\[\s*([^\]]*?)'driver'\s*=>\s*)'[^']+'/",
                "$1'passport'",
                $content,
                1
            );
            File::put($authConfig, $content);
            $this->info('🔄 Attempted to set API guard driver to passport in config/auth.php (verify manually).');
        } else {
            $this->warn('config/auth.php not found — cannot update API guard automatically.');
        }

        // 7. Install Passport and run migrations + passport:install
        $this->info('📦 Installing laravel/passport via Composer...');
        exec('composer require laravel/passport 2>&1', $out, $status);
        $this->line(implode("\n", $out));
        if ($status !== 0) {
            $this->error('Failed to require laravel/passport. Please run `composer require laravel/passport` manually.');
        } else {
            $this->info('✅ laravel/passport required.');
            $this->info('🔁 Running migrations...');
            passthru('php artisan migrate', $migrateStatus);
            if ($migrateStatus !== 0) {
                $this->warn('Migrations returned non-zero status. Check migration output.');
            }

            $this->info('🔐 Running passport:install (this will create client keys)...');
            passthru('php artisan passport:install', $passportStatus);
            if ($passportStatus !== 0) {
                $this->warn('passport:install returned non-zero status. You may need to run it manually.');
            } else {
                $this->info('✅ Passport installed and clients generated.');
            }
        }

        $this->info('✅ Sanctum removal and Passport setup process finished.');
        $this->info('➡️ Please manually verify:');
        $this->line('- config/auth.php -> api guard driver set to "passport"');
        $this->line('- app/Models/User.php extends the correct class and imports exist');
        $this->line('- remove any remaining sanctum references in your codebase (search "Sanctum" or "personal_access_tokens")');

        return Command::SUCCESS;
    }
}

<?php

namespace Rez1pro\UserAccess\Console\Commands;

use File;
use Illuminate\Console\Command;

class InstallCommand extends Command
{
    // php artisan user-access:install --force
    protected $signature = 'user-access:install {--force : Overwrite existing files}';

    protected $description = 'Publish config/migrations and perform initial setup for UserAccess';

    public function handle(): int
    {
        $this->info('Installing Rez1pro UserAccess...');

        $this->call('vendor:publish', [
            '--provider' => 'Rez1pro\UserAccess\UserAccessServiceProvider',
            '--tag' => 'user-access-migrations',
            '--force' => (bool) $this->option('force'),
        ]);

        $this->createModel('Role');
        $this->createModel('Permission');

        $this->createEnum('ExamplePermissionEnum');

        $this->newLine();
        $this->components->info('UserAccess successfully installed! ✅');
        $this->components->info('Run migrate command to migrate the roles & permissions tables.');
        return self::SUCCESS;
    }

    protected function createModel(string $name): void
    {
        $stubPath = __DIR__ . "/../../../stubs/Models/{$name}.stub";
        $targetPath = app_path("Models/{$name}.php");

        if (File::exists($targetPath) && !$this->option('force')) {
            $this->warn("Model {$name} already exists. Use --force to overwrite.");
            return;
        }

        File::ensureDirectoryExists(app_path('Models'));
        File::copy($stubPath, $targetPath);

        $this->info("Created Model: {$name}");
    }

    protected function createEnum(string $name): void
    {
        $stubPath = __DIR__ . "/../../../stubs/Enums/permission.stub";
        $targetDir = app_path("Enums/Permissions");
        $targetPath = "{$targetDir}/{$name}.php";

        if (\Illuminate\Support\Facades\File::exists($targetPath) && !$this->option('force')) {
            $this->warn("Enum {$name} already exists. Use --force to overwrite.");
            return;
        }

        \Illuminate\Support\Facades\File::ensureDirectoryExists($targetDir);

        $stub = \Illuminate\Support\Facades\File::get($stubPath);
        $content = str_replace('{{ enum }}', $name, $stub);

        \Illuminate\Support\Facades\File::put($targetPath, $content);

        $this->info("Created Enum: {$name}");
    }
}
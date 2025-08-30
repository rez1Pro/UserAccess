<?php

namespace Rez1pro\UserAccess\Console\Commands;

use App\Models\Permission;
use Illuminate\Console\Command;
use function Laravel\Prompts\multiselect;

class PermissionRollbackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:rollback {name?}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback permissions from the database';

    public function handle(): void
    {
        $name = $this->argument('name');

        if (!$name) {
            $permissions = collect(
                multiselect(
                    label: 'Which permission would you like to rollback?',
                    options: Permission::all()->pluck('name')->toArray(),
                    hint: 'Use the space bar to select options.',
                    scroll: 10,
                )
            );

            foreach ($permissions as $permission) {
                Permission::where('name', $permission)->delete();
                $this->info("Deleted permission: {$permission}");
            }
        } else {
            $permission = Permission::where('name', $name)->first();
            if (!$permission) {
                $this->error("Permission not found: {$name}");
                return;
            }
            $permission->delete();
            $this->info("Deleted permission: {$name}");
        }
    }
}
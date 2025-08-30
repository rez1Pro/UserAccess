<?php

namespace Rez1pro\UserAccess\Console\Commands;

use App\Models\Permission;
use Illuminate\Console\Command;
use Rez1pro\UserAccess\Enums\BasePermissionEnums;
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

                // when rollback I want to comment the permission from enums
                $permissionWithGroup = BasePermissionEnums::getGroupWithPermissions();

                foreach ($permissionWithGroup as $pg) {
                    $enumName = str_replace(' ', '', $pg['name']);

                    $enum = app_path("Enums/Permissions/{$enumName}.php");

                    // Here you would implement the logic to comment out the permission in the enum file
                    // This is a placeholder for the actual commenting logic
                    // You might need to read the file, modify its contents, and write it back
                    foreach ($pg['permissions'] as $pm) {
                        $enumCase = str_replace(' ', '_', $pm['name']);

                        if ($permission === $pm['id']) {
                            file_put_contents($enum, str_replace("case {$enumCase} = '{$pm['id']}';", "// case {$enumCase} = '{$pm['id']}'; // commented by UserAccess package", file_get_contents($enum)));
                        }
                    }
                }

                $this->comment("Commented out permission: {$permission}");

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
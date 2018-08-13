<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ProjectService;

class MakeUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:user {name} {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '初期ユーザー作成';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $projectService = \App::make(ProjectService::class);
        $user = \App\User::create([
            'name' => $this->argument("name"),
            'email' => $this->argument("email"),
            'password' => bcrypt($this->argument("password")),
            'allow_user' => true,
            'allow_over_project' => true,
            'project' => $projectService->getCode()
        ]);

        $this->info("User Created!! ID:{$user->id}");
    }
}

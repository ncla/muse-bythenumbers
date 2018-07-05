<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Silber\Bouncer\BouncerFacade as Bouncer;

class GrantSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:grantsuperadmin {username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grants superadmin to an user based on user-name';

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
        $user = User::where('username', $this->argument('username'))->firstOrFail();

        Bouncer::assign('superadmin')->to($user);
    }
}

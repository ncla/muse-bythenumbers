<?php

namespace App\Console\Commands;

use App\Services\Voting;
use Illuminate\Console\Command;

class CalculateBallots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate 
                            {ballots* : Array of voting ballot IDs to calculate for}
                            {--private : Make results not public (for testing/backend only)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates voting ballot results and stores in database';

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
        Voting::calculateAndSaveResults($this->argument('ballots'), !$this->option('private'));

        return $this;
    }
}

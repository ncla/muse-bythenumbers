<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Voting;
use App\Services\Voting as VotingService;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('scrape:lastfm-playcount')
            ->dailyAt('00:05');

        $schedule->command('scrape:spotifytop')
            ->dailyAt('00:07');

        $schedule->command('scrape:spotifytracks')
            ->mondays()->at('00:02');

        $schedule->call(function() {
            Log::debug('Running voting ballot pre-calc task');
            // Check all voting ballots that have ended, if they don't have pre-calculated public data, pre-calculate
            $ballotsNotPreCalculated = Voting::closed()
                ->select('voting_ballots.id')
                ->leftJoin(DB::raw('(SELECT id, voting_ballot_id, public FROM voting_ballot_results WHERE public = 1) as voting_ballot_results'),
                    'voting_ballots.id', '=', 'voting_ballot_results.voting_ballot_id')
                ->where('voting_ballot_results.id', null)
                ->groupBy('voting_ballots.id')
                ->get();

            $onlyIDs = $ballotsNotPreCalculated->map(function($val) {
                return $val->id;
            })->toArray();

            Log::debug($onlyIDs);
            Log::debug(count($onlyIDs) > 0);

            if (count($onlyIDs) > 0) {
                VotingService::calculateAndSaveResults($onlyIDs, true);
            }
        })->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

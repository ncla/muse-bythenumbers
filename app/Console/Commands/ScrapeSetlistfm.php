<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Scrapers\SetlistFm\Main as SetlistScraper;

class ScrapeSetlistfm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:setlistfm
                            {--id=* : Setlist ID(s) that you want to specifically update. Leave empty if you need to update all setlists.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape full set-list history of an artist';

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
        $scraper = new SetlistScraper($this);
        $scraper->artistId = env('MUSICBRAINZ_MBID_ARTIST');
        $scraper->start($this->option('id'));

        return $this;
    }
}

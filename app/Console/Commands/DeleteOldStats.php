<?php

namespace App\Console\Commands;

use App\Repositories\UrlRequestRepository;
use Illuminate\Console\Command;

class DeleteOldStats extends Command
{
    /**
     * @var string
     */
    protected $signature = 'monitor:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old stats';
    /**
     * @var int
     */
    private $deleteTimeMinutes;

    /**
     * @param int $deleteTimeMinutes
     */
    public function __construct(int $deleteTimeMinutes)
    {
        parent::__construct();
        $this->deleteTimeMinutes = $deleteTimeMinutes;
    }

    /**
     * Execute the console command.
     *
     * @param UrlRequestRepository $urlRequestRepository
     * @return int
     */
    public function handle(UrlRequestRepository $urlRequestRepository)
    {
        $stats = $urlRequestRepository->deleteOldStats($this->deleteTimeMinutes);
        $this->info(sprintf("Done. Deleted %s stats.", $stats->count()));
        return 0;
    }
}

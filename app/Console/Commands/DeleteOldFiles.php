<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DeleteOldFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:cleanup {folder} {days}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete files older than specified days from the given folder';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $folder = $this->argument('folder');
        $daysOld = $this->argument('days');
        $files = Storage::files($folder);
        $now = Carbon::now();

        foreach ($files as $file) {
            $lastModified = Carbon::createFromTimestamp(Storage::lastModified($file));
            if ($now->diffInDays($lastModified) >= $daysOld) {
                Storage::delete($file);
                $this->info("Deleted: $file");
            }
        }
        
        $this->info("Files older than one $daysOld ". ($daysOld > 1 ? 'days' : 'day') ." in '$folder' have been deleted successfully.");
        return Command::SUCCESS;
    }
}

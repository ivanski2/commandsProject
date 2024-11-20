<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class ArchiveApplication extends Command
{
    protected $signature = 'app:archive {--include-db} {--exclude-db=} {--include-folder=} {--exclude-folder=} {--password=}';

    protected $description = 'Archive the application codebase and databases.';

    public function handle()
    {
        $this->archiveCodebase();

        if ($this->option('include-db')) {
            if ($this->archiveDatabases()) {
                $this->info('Databases archived successfully.');
                $this->sendNotification();
            } else {
                $this->error('Failed to archive databases.');
            }
        }
    }


    public function archiveCodebase()
    {
        $folders = '--exclude=.git --exclude=storage --exclude=vendor';
        if ($exclude = $this->option('exclude-folder')) {
            $folders .= " --exclude=$exclude";
        }
        if ($include = $this->option('include-folder')) {
            $folders .= " --include=$include";
        }
        $password = $this->option('password');
        $command = "zip -P $password -r app-archive.zip . $folders";
        Log::info("Running command: $command");
        $process = Process::fromShellCommandline($command);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->error('Failed to archive codebase.');
            Log::error("Error executing command: $command");
        }
        else{
            $this->info('Codebase archived successfully.');
        }
    }


    public function archiveDatabases(): bool
    {
        $dbPassword = 'root'; // Use your actual DB password or set it in .env and use it here
        $command = "mysqldump -u root -p$dbPassword --all-databases > alldbs.sql";
        if ($exclude = $this->option('exclude-db')) {
            $command = str_replace('--all-databases', '', $command) . " --ignore-table=$exclude";
        }
        $process = Process::fromShellCommandline($command);
        $process->run();

        if ($process->isSuccessful()) {
            $password = $this->option('password');
            $zipCommand = "zip -P $password db-archive.zip alldbs.sql";
            $process = Process::fromShellCommandline($zipCommand);
            $process->run();
            $this->info('Database archived successfully.');
            return true;
        } else {
            $this->error('Failed to archive codebase.');
        }
        return false;
    }

    private function sendNotification()
    {
        $to = 'ivanski34@gmial.com'; // Set your notification email or set it in .env and get it here
        $message = "Archive completed successfully.";
        $headers = 'From: webmaster@example.com' . "\r\n" .
            'Reply-To: webmaster@example.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        mail($to, 'Archive Notification', $message, $headers);
    }
}

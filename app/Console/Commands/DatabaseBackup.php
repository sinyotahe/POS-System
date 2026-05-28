<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DatabaseBackup extends Command
{
    protected $signature = 'backup:database
        {--keep=7 : Number of backup files to keep}
        {--output= : Custom output path}';

    protected $description = 'Backup the MySQL database to a SQL file';

    public function handle(): int
    {
        $dbName = config('database.connections.mysql.database');
        $user = config('database.connections.mysql.username');
        $pass = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port');

        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "backup-{$dbName}-{$timestamp}.sql";
        $output = $this->option('output') ?? storage_path("backups/{$filename}");

        $dir = dirname($output);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $command = sprintf(
            'mysqldump --host=%s --port=%s --user=%s --password=%s --routines --single-transaction %s > %s 2>&1',
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($user),
            escapeshellarg($pass),
            escapeshellarg($dbName),
            escapeshellarg($output),
        );

        $resultCode = 0;
        $outputLines = [];
        exec($command, $outputLines, $resultCode);

        if ($resultCode !== 0) {
            $this->error('Backup failed: '.implode("\n", $outputLines));

            return Command::FAILURE;
        }

        $size = file_exists($output) ? filesize($output) : 0;
        $this->info("Backup created: {$output} ({$this->formatSize($size)})");

        $keep = (int) $this->option('keep');
        $this->pruneOldBackups($dir, $keep, $dbName);

        return Command::SUCCESS;
    }

    protected function pruneOldBackups(string $dir, int $keep, string $dbName): void
    {
        $files = glob("{$dir}/backup-{$dbName}-*.sql");
        if ($files === false) {
            return;
        }

        usort($files, fn ($a, $b) => filemtime($b) <=> filemtime($a));

        foreach (array_slice($files, $keep) as $file) {
            unlink($file);
            $this->info("Pruned old backup: {$file}");
        }
    }

    protected function formatSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2).' '.$units[$i];
    }
}

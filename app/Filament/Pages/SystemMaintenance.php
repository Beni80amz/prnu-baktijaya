<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Process;

class SystemMaintenance extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static string $view = 'filament.pages.system-maintenance';

    protected static ?string $navigationGroup = 'Pengaturan Website';

    protected static ?string $navigationLabel = 'System Maintenance';

    protected static ?string $title = 'System Maintenance & Update';

    public ?string $output = null;

    public string $bypassSecret = 'prnu-baktijaya-secret';

    public function isMaintenanceMode(): bool
    {
        return app()->isDownForMaintenance();
    }

    public function toggleMaintenanceMode()
    {
        if ($this->isMaintenanceMode()) {
            $result = $this->runCommand('up');
            Notification::make()
                ->title('Maintenance Mode Dimatikan')
                ->success()
                ->send();
        } else {
            $result = $this->runCommand("down --secret=\"{$this->bypassSecret}\"");
            Notification::make()
                ->title('Maintenance Mode Diaktifkan')
                ->warning()
                ->send();
        }

        $this->output = $result;
    }

    public function clearCache()
    {
        $this->output = $this->runCommand('optimize:clear');
        Notification::make()->title('Cache berhasil dibersihkan')->success()->send();
    }

    public function optimize()
    {
        $this->output = $this->runCommand('optimize');
        Notification::make()->title('Sistem berhasil dioptimasi')->success()->send();
    }

    public function linkStorage()
    {
        $this->output = $this->runCommand('storage:link');
        Notification::make()->title('Storage Link diperbarui')->success()->send();
    }

    public function updateApplication()
    {
        $output = "--- START UPDATE ---\n";

        // 1. Git Pull
        $output .= "Executing: git pull origin main\n";

        // Try to inject HOME env var to find SSH keys (common VPS issue for www-data)
        // We assume the key might be in /root or the default user home if standard fail.
        // This command adds verbose output (-v) and redirects stderr to stdout (2>&1).
        $gitCommand = 'export HOME=/root && export GIT_SSH_COMMAND="ssh -o StrictHostKeyChecking=no" && git pull origin main 2>&1';

        $process = Process::run($gitCommand);
        $output .= $process->output();

        // Fallback: If root fails, maybe try generic home or just standard (already failed)
        if ($process->failed()) {
            $output .= "\n[RETRY] Trying default user context...\n";
            $processRetry = Process::run('git pull origin main 2>&1');
            $output .= $processRetry->output();
        }

        // 2. Migrate
        $output .= "\nExecuting: php artisan migrate --force\n";
        $output .= $this->runCommand('migrate --force');

        $output .= "\n--- UPDATE COMPLETED ---";
        $this->output = $output;

        Notification::make()->title('Aplikasi Berhasil Diupdate')->success()->send();
    }

    public function hardResetGit()
    {
        $output = "--- START HARD RESET ---\n";

        $output .= "Executing: git fetch origin\n";
        $output .= Process::run('export HOME=/root && export GIT_SSH_COMMAND="ssh -o StrictHostKeyChecking=no" && git fetch origin 2>&1')->output();

        $output .= "\nExecuting: git reset --hard origin/main\n";
        $output .= Process::run('git reset --hard origin/main')->output();

        $output .= "\nExecuting: git clean -fd\n";
        $output .= Process::run('git clean -fd')->output();

        $output .= "\n--- RESET COMPLETED ---";
        $this->output = $output;

        Notification::make()->title('Sistem Berhasil di-Reset ke Main')->danger()->send();
    }

    protected function runCommand(string $command): string
    {
        try {
            Artisan::call($command);
            return Artisan::output();
        } catch (\Exception $e) {
            return "ERROR: " . $e->getMessage();
        }
    }
}

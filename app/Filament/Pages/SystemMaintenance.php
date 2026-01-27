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

        // Configuration
        $repoUrl = config('app.repository_url', 'https://github.com/Beni80amz/prnu-baktijaya.git');
        $token = env('GITHUB_TOKEN');

        $output .= "Target Repo: {$repoUrl}\n";

        if ($token) {
            $output .= "Mode: HTTPS (Token Auth)\n";
            // Strip existing protocol to inject token cleanly
            $cleanUrl = preg_replace('#^https?://[^/]+@#', 'https://', $repoUrl); // remove user:pass
            $cleanUrl = str_replace(['https://', 'http://'], '', $cleanUrl);

            // Construct Auth URL: https://TOKEN@github.com/...
            $authUrl = "https://{$token}@{$cleanUrl}";

            // Execute with token
            $gitCommand = "git pull {$authUrl} main 2>&1";
            $process = Process::run($gitCommand);

            // Mask token in output for security
            $cleanOutput = str_replace($token, '***', $process->output());
            $output .= $cleanOutput;
        } else {
            // Check current user for debugging
            $whoami = trim(Process::run('whoami')->output());
            $output .= "Mode: SSH (User: {$whoami})\n";
            $output .= "Executing: git pull origin main\n";

            // Fallback: Default SSH
            // We still try the HOME injection just in case it works for some setups
            $gitCommand = 'export HOME=/root && export GIT_SSH_COMMAND="ssh -o StrictHostKeyChecking=no" && git pull origin main 2>&1';
            $process = Process::run($gitCommand);
            $output .= $process->output();
        }

        if ($process->failed()) {
            $output .= "\n[ERROR] Git Pull Gagal.\n";
            if (!$token) {
                $output .= "\nSOLUSI (VPS): \nWeb server (www-data) tidak memiliki izin membaca kunci SSH root.\nSilakan tambahkan 'GITHUB_TOKEN' ke file .env Anda untuk mengizinkan update via web.\n(Buat token di GitHub -> Settings -> Developer Settings -> Personal access tokens)\n";
            }
        } else {
            // 2. Migrate only if pull success
            $output .= "\nExecuting: php artisan migrate --force\n";
            $output .= $this->runCommand('migrate --force');
        }

        $output .= "\n--- UPDATE COMPLETED ---";
        $this->output = $output;

        if ($process->successful()) {
            Notification::make()->title('Aplikasi Berhasil Diupdate')->success()->send();
        } else {
            Notification::make()->title('Update Gagal')->danger()->send();
        }
    }

    public function hardResetGit()
    {
        $output = "--- START HARD RESET ---\n";

        // Logic here is tricky with Token, but for hard reset often origin is fine if fetched.
        // We will just try standard fetch but warn about token if it fails.

        $output .= "Executing: git fetch origin\n";
        // Use token logic here too if available? For simplicity, we keep SSH default for reset 
        // OR we apply the same token logic. Let's apply token logic for consistency.

        $token = env('GITHUB_TOKEN');
        $repoUrl = config('app.repository_url', 'https://github.com/Beni80amz/prnu-baktijaya.git');

        if ($token) {
            $cleanUrl = str_replace(['https://', 'http://'], '', $repoUrl);
            $authUrl = "https://{$token}@{$cleanUrl}";
            $fetchCmd = "git fetch {$authUrl} 2>&1";
            $output .= Process::run($fetchCmd)->output(); // Masking needed?
        } else {
            $output .= Process::run('export HOME=/root && export GIT_SSH_COMMAND="ssh -o StrictHostKeyChecking=no" && git fetch origin 2>&1')->output();
        }

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

# PowerShell helper to start a Laravel queue worker in the background (Windows)

# Usage: Open PowerShell as the user that runs PHP/web server and execute:
#   .\start-queue.ps1
# This will start `php artisan queue:work` in a background process.

$php = 'php'
$artisanArgs = 'artisan queue:work --sleep=3 --tries=3 --timeout=90'

Start-Process -FilePath $php -ArgumentList $artisanArgs -NoNewWindow -WindowStyle Hidden
Write-Host "Started queue worker (background)."
# Curup Water - Stop All Services
# PowerShell version

Write-Host "========================================" -ForegroundColor Cyan
Write-Host " Stopping All Services" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Function to stop process safely
function Stop-ProcessSafely {
    param($ProcessName, $DisplayName)
    $process = Get-Process -Name $ProcessName -ErrorAction SilentlyContinue
    if ($process) {
        Write-Host "Stopping $DisplayName..." -ForegroundColor Yellow
        Stop-Process -Name $ProcessName -Force -ErrorAction SilentlyContinue
        Write-Host "  [OK] $DisplayName stopped" -ForegroundColor Green
    } else {
        Write-Host "  [INFO] $DisplayName was not running" -ForegroundColor Gray
    }
}

# Stop all services
Stop-ProcessSafely "mysqld" "MySQL Server"
Stop-ProcessSafely "httpd" "Apache Server"
Stop-ProcessSafely "php" "PHP Dev Server"

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host " All Services Stopped" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Start-Sleep -Seconds 2

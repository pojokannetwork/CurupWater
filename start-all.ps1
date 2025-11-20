# Curup Water - Auto Startup Script
# PowerShell version with better error handling

Write-Host "========================================" -ForegroundColor Cyan
Write-Host " Curup Water - Starting All Services" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

$XAMPP_PATH = "C:\xampp"
$PROJECT_PATH = "C:\xampp\htdocs\CurupWater"

# Function to check if process is running
function Test-ProcessRunning {
    param($ProcessName)
    return Get-Process -Name $ProcessName -ErrorAction SilentlyContinue
}

# Function to kill process
function Stop-ProcessSafely {
    param($ProcessName)
    $process = Get-Process -Name $ProcessName -ErrorAction SilentlyContinue
    if ($process) {
        Write-Host "Stopping existing $ProcessName..." -ForegroundColor Yellow
        Stop-Process -Name $ProcessName -Force -ErrorAction SilentlyContinue
        Start-Sleep -Seconds 1
    }
}

# Check if XAMPP exists
if (-not (Test-Path "$XAMPP_PATH\mysql\bin\mysqld.exe")) {
    Write-Host "[ERROR] XAMPP not found at $XAMPP_PATH" -ForegroundColor Red
    Write-Host "Please install XAMPP or adjust the path in this script." -ForegroundColor Yellow
    Read-Host "Press Enter to exit"
    exit 1
}

# Stop existing processes
Write-Host "Cleaning up existing processes..." -ForegroundColor Yellow
Stop-ProcessSafely "mysqld"
Stop-ProcessSafely "httpd"
Stop-ProcessSafely "php"
Start-Sleep -Seconds 2

# Start MySQL
Write-Host "[1/4] Starting MySQL Server..." -ForegroundColor Green
Start-Process -FilePath "$XAMPP_PATH\mysql\bin\mysqld.exe" -ArgumentList "--console" -WindowStyle Hidden
Start-Sleep -Seconds 3

if (Test-ProcessRunning "mysqld") {
    Write-Host "  MySQL started successfully" -ForegroundColor Green
} else {
    Write-Host "  [WARNING] MySQL may not have started properly" -ForegroundColor Yellow
}

# Start Apache
Write-Host "[2/4] Starting Apache Server..." -ForegroundColor Green
Start-Process -FilePath "$XAMPP_PATH\apache\bin\httpd.exe" -WindowStyle Hidden
Start-Sleep -Seconds 3

if (Test-ProcessRunning "httpd") {
    Write-Host "  Apache started successfully" -ForegroundColor Green
} else {
    Write-Host "  [WARNING] Apache may not have started properly" -ForegroundColor Yellow
}

# Start PHP Dev Server
Write-Host "[3/4] Starting PHP Development Server..." -ForegroundColor Green
Set-Location -Path $PROJECT_PATH
Start-Process -FilePath "$XAMPP_PATH\php\php.exe" -ArgumentList "-S", "localhost:8000" -WindowStyle Normal
Start-Sleep -Seconds 2

if (Test-ProcessRunning "php") {
    Write-Host "  PHP Dev Server started successfully" -ForegroundColor Green
} else {
    Write-Host "  [WARNING] PHP Dev Server may not have started properly" -ForegroundColor Yellow
}

# Open browsers
Write-Host "[4/4] Opening browsers..." -ForegroundColor Green
Start-Sleep -Seconds 2
Start-Process "http://localhost:8000"
Start-Process "http://localhost/phpmyadmin"

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host " All Services Started!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "  Application:  http://localhost:8000" -ForegroundColor White
Write-Host "  Admin Panel:  http://localhost:8000/admin/" -ForegroundColor White
Write-Host "  phpMyAdmin:   http://localhost/phpmyadmin" -ForegroundColor White
Write-Host ""
Write-Host "Service Status:" -ForegroundColor Yellow
Write-Host "  MySQL:  $(if (Test-ProcessRunning 'mysqld') { 'RUNNING' } else { 'STOPPED' })" -ForegroundColor $(if (Test-ProcessRunning 'mysqld') { 'Green' } else { 'Red' })
Write-Host "  Apache: $(if (Test-ProcessRunning 'httpd') { 'RUNNING' } else { 'STOPPED' })" -ForegroundColor $(if (Test-ProcessRunning 'httpd') { 'Green' } else { 'Red' })
Write-Host "  PHP:    $(if (Test-ProcessRunning 'php') { 'RUNNING' } else { 'STOPPED' })" -ForegroundColor $(if (Test-ProcessRunning 'php') { 'Green' } else { 'Red' })
Write-Host ""
Write-Host "To stop all services, run: .\stop-all.ps1" -ForegroundColor Cyan
Write-Host ""

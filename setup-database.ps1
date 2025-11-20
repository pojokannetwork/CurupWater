# Curup Water - Auto Database Setup (PowerShell)
# Automatically creates database and imports schema

Write-Host "========================================" -ForegroundColor Cyan
Write-Host " Curup Water - Database Setup" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

$MYSQL_PATH = "C:\xampp\mysql\bin"
$DB_NAME = "curupwater"
$SQL_FILE = Join-Path $PSScriptRoot "setup.sql"

# Check MySQL installation
if (-not (Test-Path "$MYSQL_PATH\mysql.exe")) {
    Write-Host "[ERROR] MySQL not found at $MYSQL_PATH" -ForegroundColor Red
    Write-Host "Please check your XAMPP installation." -ForegroundColor Yellow
    Read-Host "Press Enter to exit"
    exit 1
}

# Check if MySQL is running
Write-Host "[1/4] Checking MySQL status..." -ForegroundColor Green
$mysqldProcess = Get-Process -Name mysqld -ErrorAction SilentlyContinue

if (-not $mysqldProcess) {
    Write-Host "  MySQL is not running. Starting MySQL..." -ForegroundColor Yellow
    Start-Process -FilePath "C:\xampp\mysql\bin\mysqld.exe" -ArgumentList "--console" -WindowStyle Hidden
    Start-Sleep -Seconds 5
    Write-Host "  MySQL started" -ForegroundColor Green
} else {
    Write-Host "  MySQL is already running" -ForegroundColor Green
}

# Create database if not exists
Write-Host ""
Write-Host "[2/4] Creating database if not exists..." -ForegroundColor Green
$createDbCmd = "CREATE DATABASE IF NOT EXISTS $DB_NAME; USE $DB_NAME; SHOW TABLES;"
$result = & "$MYSQL_PATH\mysql.exe" -u root -e $createDbCmd 2>&1

if ($LASTEXITCODE -eq 0) {
    Write-Host "  Database '$DB_NAME' is ready" -ForegroundColor Green
} else {
    Write-Host "  [ERROR] Failed to create database" -ForegroundColor Red
    Write-Host "  Error: $result" -ForegroundColor Yellow
    Read-Host "Press Enter to exit"
    exit 1
}

# Check SQL file
Write-Host ""
Write-Host "[3/4] Checking SQL file..." -ForegroundColor Green
if (-not (Test-Path $SQL_FILE)) {
    Write-Host "  [ERROR] setup.sql not found!" -ForegroundColor Red
    Write-Host "  Expected at: $SQL_FILE" -ForegroundColor Yellow
    Read-Host "Press Enter to exit"
    exit 1
}
Write-Host "  SQL file found: $SQL_FILE" -ForegroundColor Green

# Import database
Write-Host ""
Write-Host "[4/4] Importing database structure..." -ForegroundColor Green
$importCmd = Get-Content $SQL_FILE -Raw
$result = & "$MYSQL_PATH\mysql.exe" -u root $DB_NAME -e $importCmd 2>&1

if ($LASTEXITCODE -eq 0) {
    Write-Host "  [SUCCESS] Database imported successfully!" -ForegroundColor Green
} else {
    Write-Host "  [ERROR] Failed to import database" -ForegroundColor Red
    Write-Host "  Error: $result" -ForegroundColor Yellow
    Read-Host "Press Enter to exit"
    exit 1
}

# Verify tables
Write-Host ""
Write-Host "Verifying tables..." -ForegroundColor Yellow
$tables = & "$MYSQL_PATH\mysql.exe" -u root $DB_NAME -e "SHOW TABLES;" 2>&1

if ($LASTEXITCODE -eq 0) {
    Write-Host $tables
} else {
    Write-Host "Could not verify tables" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host " Database Setup Complete!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Default Admin Login:" -ForegroundColor Yellow
Write-Host "  Username: admin" -ForegroundColor White
Write-Host "  Password: admin123" -ForegroundColor White
Write-Host ""
Write-Host "Next Steps:" -ForegroundColor Yellow
Write-Host "  1. Run .\start-all.ps1 (or start-all.bat)" -ForegroundColor White
Write-Host "  2. Open http://localhost:8000/admin/" -ForegroundColor White
Write-Host "  3. Login with credentials above" -ForegroundColor White
Write-Host ""

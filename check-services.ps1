# Curup Water - Service Status Checker
# PowerShell version

Write-Host "========================================" -ForegroundColor Cyan
Write-Host " Service Status Check" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Function to check process
function Test-ServiceRunning {
    param($ProcessName, $DisplayName, $Port = $null)
    
    $process = Get-Process -Name $ProcessName -ErrorAction SilentlyContinue
    
    if ($process) {
        Write-Host "[RUNNING]" -ForegroundColor Green -NoNewline
        Write-Host " $DisplayName" -ForegroundColor White
        
        if ($Port) {
            $portCheck = Get-NetTCPConnection -LocalPort $Port -ErrorAction SilentlyContinue
            if ($portCheck) {
                Write-Host "          Port $Port is active" -ForegroundColor Gray
            }
        }
    } else {
        Write-Host "[STOPPED]" -ForegroundColor Red -NoNewline
        Write-Host " $DisplayName" -ForegroundColor White
    }
}

# Check all services
Write-Host "Checking Services:" -ForegroundColor Yellow
Write-Host ""
Test-ServiceRunning "mysqld" "MySQL Server" 3306
Test-ServiceRunning "httpd" "Apache Server (phpMyAdmin)" 80
Test-ServiceRunning "php" "PHP Development Server" 8000

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Additional port check
Write-Host "Port Status:" -ForegroundColor Yellow
Write-Host ""

$ports = @{
    "3306" = "MySQL"
    "80" = "Apache/phpMyAdmin"
    "8000" = "PHP Dev Server"
}

foreach ($port in $ports.Keys) {
    $connection = Get-NetTCPConnection -LocalPort $port -ErrorAction SilentlyContinue
    if ($connection) {
        Write-Host "  Port $port ($($ports[$port])): " -NoNewline
        Write-Host "IN USE" -ForegroundColor Green
    } else {
        Write-Host "  Port $port ($($ports[$port])): " -NoNewline
        Write-Host "AVAILABLE" -ForegroundColor Gray
    }
}

Write-Host ""
Write-Host "Quick Links:" -ForegroundColor Yellow
Write-Host "  http://localhost:8000" -ForegroundColor Cyan
Write-Host "  http://localhost:8000/admin/" -ForegroundColor Cyan
Write-Host "  http://localhost/phpmyadmin" -ForegroundColor Cyan
Write-Host ""

# Script PowerShell pour exécuter schema.sql dans PostgreSQL

Write-Host "Recherche de psql..." -ForegroundColor Yellow

# Chemins communs pour PostgreSQL
$possiblePaths = @(
    "C:\Program Files\PostgreSQL\16\bin\psql.exe",
    "C:\Program Files\PostgreSQL\15\bin\psql.exe",
    "C:\Program Files\PostgreSQL\14\bin\psql.exe",
    "C:\Program Files\PostgreSQL\13\bin\psql.exe",
    "C:\Program Files (x86)\PostgreSQL\16\bin\psql.exe",
    "C:\Program Files (x86)\PostgreSQL\15\bin\psql.exe"
)

$psqlPath = $null

# Chercher psql
foreach ($path in $possiblePaths) {
    if (Test-Path $path) {
        $psqlPath = $path
        Write-Host "psql trouvé : $psqlPath" -ForegroundColor Green
        break
    }
}

# Si pas trouvé, chercher récursivement
if (-not $psqlPath) {
    Write-Host "Recherche approfondie..." -ForegroundColor Yellow
    $found = Get-ChildItem -Path "C:\Program Files" -Recurse -Filter "psql.exe" -ErrorAction SilentlyContinue | Select-Object -First 1
    if ($found) {
        $psqlPath = $found.FullName
        Write-Host "psql trouvé : $psqlPath" -ForegroundColor Green
    }
}

if (-not $psqlPath) {
    Write-Host "ERREUR : psql.exe non trouvé !" -ForegroundColor Red
    Write-Host ""
    Write-Host "Solutions :" -ForegroundColor Yellow
    Write-Host "1. Utilisez pgAdmin (Option 1 dans EXECUTER_SCHEMA.md)" -ForegroundColor Cyan
    Write-Host "2. Ajoutez PostgreSQL au PATH" -ForegroundColor Cyan
    Write-Host "3. Trouvez manuellement psql.exe et utilisez le chemin complet" -ForegroundColor Cyan
    exit 1
}

# Vérifier que le fichier schema.sql existe
$schemaFile = Join-Path $PSScriptRoot "database\schema.sql"
if (-not (Test-Path $schemaFile)) {
    Write-Host "ERREUR : Fichier schema.sql non trouvé à : $schemaFile" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "Exécution du script SQL..." -ForegroundColor Yellow
Write-Host "Fichier : $schemaFile" -ForegroundColor Cyan
Write-Host "Base de données : streamflix" -ForegroundColor Cyan
Write-Host ""

# Demander le mot de passe
$password = Read-Host "Entrez le mot de passe PostgreSQL (postgres)" -AsSecureString
$passwordPlain = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($password))

# Exécuter psql
$env:PGPASSWORD = $passwordPlain
& $psqlPath -U postgres -d streamflix -f $schemaFile

if ($LASTEXITCODE -eq 0) {
    Write-Host ""
    Write-Host "✓ Script SQL exécuté avec succès !" -ForegroundColor Green
} else {
    Write-Host ""
    Write-Host "✗ Erreur lors de l'exécution du script" -ForegroundColor Red
}

# Nettoyer
$env:PGPASSWORD = $null


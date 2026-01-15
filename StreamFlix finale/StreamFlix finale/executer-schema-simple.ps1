# Version simplifiée - Remplacez le chemin par celui de votre installation PostgreSQL

$psqlPath = "C:\Program Files\PostgreSQL\15\bin\psql.exe"  # CHANGEZ ICI si nécessaire
$schemaFile = "database\schema.sql"

if (-not (Test-Path $psqlPath)) {
    Write-Host "ERREUR : psql.exe non trouvé à $psqlPath" -ForegroundColor Red
    Write-Host "Modifiez la variable `$psqlPath dans ce script avec le bon chemin" -ForegroundColor Yellow
    exit 1
}

Write-Host "Exécution du schema.sql..." -ForegroundColor Yellow
$password = Read-Host "Mot de passe PostgreSQL (postgres)" -AsSecureString
$passwordPlain = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($password))

$env:PGPASSWORD = $passwordPlain
& $psqlPath -U postgres -d streamflix -f $schemaFile

if ($LASTEXITCODE -eq 0) {
    Write-Host "✓ Succès !" -ForegroundColor Green
} else {
    Write-Host "✗ Erreur" -ForegroundColor Red
}

$env:PGPASSWORD = $null


# Script para agregar el trait AdminPanelOnly a recursos ATSA sin proteccion
$resourcesDir = "C:\laragon\www\atsa-tucuman\app\Filament\Resources"
$traitUse = "use App\Filament\Concerns\AdminPanelOnly;"

Get-ChildItem -Path $resourcesDir -Filter "*.php" | Where-Object {
    $_.Name -notmatch '^Cent' -and
    $_.Name -ne 'UserResource.php' -and
    $_.Name -ne 'SolicitudAfiliacionResource.php' -and
    $_.Name -ne 'SiteSettingResource.php'
} | ForEach-Object {
    $content = Get-Content $_.FullName -Raw
    
    # Verificar si ya tiene proteccion
    if ($content -match 'shouldRegisterNavigation|canAccess|AdminPanelOnly') {
        Write-Host "YA PROTEGIDO: $($_.Name)" -ForegroundColor Green
        return
    }
    
    # Agregar use statement despues de la primera linea "use " o despues de "namespace "
    if ($content -notmatch [regex]::Escape($traitUse)) {
        $content = $content -replace "(namespace App\\Filament\\Resources;\r?\n)", "`$1`r`n$traitUse`r`n"
    }
    
    # Agregar trait dentro de la clase
    if ($content -notmatch 'use AdminPanelOnly;') {
        $content = $content -replace "(class \w+ extends Resource\r?\n\{)", "`$1`r`n    use AdminPanelOnly;`r`n"
    }
    
    Set-Content -Path $_.FullName -Value $content -NoNewline
    Write-Host "ACTUALIZADO: $($_.Name)" -ForegroundColor Yellow
}

Write-Host "`nDone." -ForegroundColor Cyan

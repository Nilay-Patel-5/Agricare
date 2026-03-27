# Ollama Migration Script

$sourceDir = "$env:USERPROFILE\.ollama\models"
$destDir = "D:\.ollama\models"

if (!(Test-Path $sourceDir)) {
    Write-Host "Source directory $sourceDir not found. Skipping move, but will set environment variable."
} else {
    Write-Host "Creating destination directory $destDir..."
    New-Item -ItemType Directory -Force -Path $destDir | Out-Null

    Write-Host "Moving models from $sourceDir to $destDir..."
    # Note: This might be slow if there are many models. Using Copy then Delete is safer but move is faster.
    # Since we want to save space, move is better.
    Get-ChildItem -Path $sourceDir | Move-Item -Destination $destDir -Force
}

Write-Host "Setting OLLAMA_MODELS environment variable to D:\.ollama..."
[System.Environment]::SetEnvironmentVariable("OLLAMA_MODELS", "D:\.ollama", [System.EnvironmentVariableTarget]::User)

Write-Host "Migration complete!"
Write-Host "IMPORTANT: Please QUIT Ollama completely (from system tray) and restart it for changes to take effect."
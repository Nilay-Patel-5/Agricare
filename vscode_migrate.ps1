# VS Code Extensions Migration Script

$sourceDir = "C:\Users\papat\.vscode\extensions"
$destDir = "D:\.vscode\extensions"

Write-Host "IMPORTANT: Please CLOSE ALL VS Code windows before proceeding!"
Read-Host "Press Enter to continue once VS Code is closed..."

if (!(Test-Path $sourceDir)) {
    Write-Host "Source directory $sourceDir not found."
    exit
}

if (!(Test-Path "D:\.vscode")) {
    New-Item -ItemType Directory -Path "D:\.vscode" | Out-Null
}

if (Test-Path $destDir) {
    Write-Host "Destination $destDir already exists. Moving contents..."
}

Write-Host "Moving extensions to $destDir..."
Move-Item -Path $sourceDir -Destination $destDir -Force

Write-Host "Creating Junction link from C: to D:..."
# Use cmd /c mklink /J because PowerShell doesn't have a native 'mklink' command (it has New-Item -ItemType Junction)
cmd /c mklink /J "$sourceDir" "$destDir"

Write-Host "Migration complete! You can now reopen VS Code."

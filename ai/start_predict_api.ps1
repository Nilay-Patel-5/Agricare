$ErrorActionPreference = 'Stop'

Set-Location $PSScriptRoot
$python = 'C:\Program Files\Python310\python.exe'

if (-not (Test-Path $python)) {
    throw "Python runtime not found at $python"
}

$env:PORT = '5050'
& $python predict_api.py

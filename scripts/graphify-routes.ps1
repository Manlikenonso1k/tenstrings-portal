param(
    [ValidateSet('public', 'all')]
    [string]$Scope = 'public',
    [ValidateSet('svg', 'png')]
    [string]$Format = 'svg',
    [string]$OutputDir = 'docs\graphs'
)

$ErrorActionPreference = 'Stop'

function Resolve-DotPath {
    if ($env:GRAPHVIZ_DOT_PATH -and (Test-Path $env:GRAPHVIZ_DOT_PATH)) {
        return $env:GRAPHVIZ_DOT_PATH
    }

    try {
        $dotCmd = Get-Command dot -ErrorAction Stop
        if ($dotCmd.Source) {
            return $dotCmd.Source
        }
    } catch {
    }

    $candidates = @(
        'C:\Program Files\Graphviz\bin\dot.exe',
        'C:\Program Files (x86)\Graphviz\bin\dot.exe'
    )

    foreach ($candidate in $candidates) {
        if (Test-Path $candidate) {
            return $candidate
        }
    }

    throw 'Graphviz dot.exe not found. Set GRAPHVIZ_DOT_PATH or add Graphviz bin to PATH.'
}

function Escape-DotLabel([string]$text) {
    if ($null -eq $text) {
        return ''
    }

    $escaped = $text -replace '\\', '\\\\'
    $escaped = $escaped -replace '"', '\"'
    return $escaped
}

$dotPath = Resolve-DotPath
$routesJson = php artisan route:list --json
$routes = $routesJson | ConvertFrom-Json

if ($Scope -eq 'public') {
    $routes = $routes | Where-Object {
        $_.uri -like 'api/*' -or $_.uri -eq '{any?}' -or $_.uri -eq '/'
    }
}

if (-not (Test-Path $OutputDir)) {
    New-Item -Path $OutputDir -ItemType Directory -Force | Out-Null
}

$safeScope = $Scope
$dotFile = Join-Path $OutputDir "routes-$safeScope.dot"
$imageFile = Join-Path $OutputDir "routes-$safeScope.$Format"

$sb = [System.Text.StringBuilder]::new()
[void]$sb.AppendLine('digraph Routes {')
[void]$sb.AppendLine('  rankdir=LR;')
[void]$sb.AppendLine('  graph [fontname="Segoe UI"];')
[void]$sb.AppendLine('  node [shape=box, style="rounded,filled", fillcolor="#F8FAFC", color="#CBD5E1", fontname="Segoe UI", fontsize=10];')
[void]$sb.AppendLine('  edge [color="#64748B", fontname="Segoe UI", fontsize=9];')
[void]$sb.AppendLine('  "Client" [shape=oval, fillcolor="#DBEAFE", color="#93C5FD"];')

foreach ($route in $routes) {
    $uri = Escape-DotLabel($route.uri)
    $method = Escape-DotLabel(($route.method -replace '\|HEAD', ''))
    $action = Escape-DotLabel($route.action)
    $routeName = if ($route.name) { Escape-DotLabel($route.name) } else { '(unnamed)' }
    $nodeId = "route_$($uri -replace '[^a-zA-Z0-9_]', '_')"
    $label = "$method $uri`n$routeName`n$action"

    [void]$sb.AppendLine("  `"$nodeId`" [label=`"$label`"];")
    [void]$sb.AppendLine("  `"Client`" -> `"$nodeId`";")
}

[void]$sb.AppendLine('}')

[System.IO.File]::WriteAllText($dotFile, $sb.ToString())
& $dotPath "-T$Format" $dotFile "-o" $imageFile

Write-Host "Created $dotFile"
Write-Host "Created $imageFile"

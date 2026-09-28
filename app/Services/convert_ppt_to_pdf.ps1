param(
    [Parameter(Mandatory=$true)]
    [string]$pptxPath,

    [Parameter(Mandatory=$true)]
    [string]$pdfPath
)

$ErrorActionPreference = "Stop"

if (-not (Test-Path $pptxPath)) {
    Write-Error "Source file not found: $pptxPath"
    exit 1
}

$ppt = $null
$presentation = $null

try {
    $ppt = New-Object -ComObject PowerPoint.Application
    # Open(FileName, ReadOnly, Untitled, WithWindow)
    # -1 = msoTrue, 0 = msoFalse
    $presentation = $ppt.Presentations.Open($pptxPath, -1, 0, 0)
    
    # 32 = ppSaveAsPDF
    $presentation.SaveAs($pdfPath, 32)
    
    Write-Output "CONVERT_OK"
}
catch {
    Write-Error "PowerPoint COM Error: $($_.Exception.Message)"
    exit 1
}
finally {
    if ($presentation -ne $null) {
        try { $presentation.Close() } catch {}
    }
    if ($ppt -ne $null) {
        try { $ppt.Quit() } catch {}
        try { [System.Runtime.Interopservices.Marshal]::ReleaseComObject($ppt) | Out-Null } catch {}
    }
    [System.GC]::Collect()
    [System.GC]::WaitForPendingFinalizers()
}

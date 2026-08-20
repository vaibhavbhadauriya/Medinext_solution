$dir = "c:\xampp\htdocs\Medinext_solution\Medinext_solutions"

$files = @(
    (Get-ChildItem -Path "$dir\includes" -Filter "*.php").FullName,
    "$dir\config\db.php",
    (Get-ChildItem -Path "$dir" -Filter "*.php").FullName,
    (Get-ChildItem -Path "$dir\blog" -Filter "*.php").FullName,
    "$dir\database\schema.sql",
    "$dir\manifest.json",
    "$dir\robots.txt",
    "$dir\sitemap.xml",
    "$dir\humans.txt",
    "$dir\offline.html",
    "$dir\sw.js"
) | Where-Object { Test-Path $_ -PathType Leaf } | Select-Object -Unique

foreach ($file in $files) {
    $c = Get-Content $file -Raw
    if ($c) {
        $c = $c -replace 'PRISMATICA HEALTH','MEDINEXT SOLUTIONS'
        $c = $c -replace 'Prismatica Health Site','Medinext Solutions Site'
        $c = $c -replace 'Prismatica Health','Medinext Solutions'
        $c = $c -replace 'prismaticahealth\.com','medinextsolutions.com'
        $c = $c -replace '@PrismaticaHealth','@MedinextSolutions'
        $c = $c -replace 'prismahealth/prismaticahealth','Medinext_solution/Medinext_solutions'
        $c = $c -replace 'prismaticahealth','medinextsolutions'
        $c = $c -replace 'Prismatica','Medinext'
        $c = $c -replace 'Precision You Can Bank On\.','Your Trusted Partner in Revenue Cycle Management.'
        $c = $c -replace 'Precision You Can Bank On','Your Trusted Partner in Revenue Cycle Management'
        Set-Content -Path $file -Value $c -NoNewline
    }
}

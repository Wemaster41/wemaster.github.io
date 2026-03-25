<?php
/**
 * check_bold.php  —  ZipArchive болон composer шаардахгүй
 * Арга 1: zip:// stream wrapper  (php.ini-д extension=zip байвал)
 * Арга 2: PK header raw parse  (үргэлж ажилладаг fallback)
 */
session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['username'])) {
    echo json_encode(['ok' => false, 'error' => 'not_logged_in']); exit;
}
if (empty($_FILES['docfile']) || $_FILES['docfile']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['ok' => false, 'error' => 'Файл upload болсонгүй']); exit;
}
$ext = strtolower(pathinfo($_FILES['docfile']['name'], PATHINFO_EXTENSION));
if ($ext !== 'docx') {
    echo json_encode(['ok' => false, 'error' => 'Зөвхөн .docx файл зөвшөөрнө']); exit;
}

$tmp = $_FILES['docfile']['tmp_name'];

// --- Арга 1: zip:// wrapper ---
$docXml = @file_get_contents('zip://' . $tmp . '#word/document.xml');

// --- Арга 2: PK header raw parse (fallback) ---
if (empty($docXml)) {
    $docXml = readFileFromZip($tmp, 'word/document.xml');
}

if (empty($docXml)) {
    echo json_encode(['ok' => false, 'error' => 'Файл унших боломжгүй. Файлыг дахин хадгалж оролдоно уу.']); exit;
}

$count = countBold($docXml);
echo json_encode(['ok' => true, 'bold_found' => $count > 0, 'count' => $count]);

// ── Raw ZIP parser ────────────────────────────────────────────
function readFileFromZip(string $zipPath, string $targetName): string
{
    $raw = @file_get_contents($zipPath);
    if ($raw === false) return '';

    $offset = 0;
    $len    = strlen($raw);

    while ($offset < $len - 30) {
        // PK\x03\x04 — local file header signature
        if (substr($raw, $offset, 4) !== "PK\x03\x04") {
            $next = strpos($raw, "PK\x03\x04", $offset + 1);
            if ($next === false) break;
            $offset = $next;
            continue;
        }

        $compMethod = unpack('v', substr($raw, $offset + 8,  2))[1];
        $compSize   = unpack('V', substr($raw, $offset + 18, 4))[1];
        $fnLen      = unpack('v', substr($raw, $offset + 26, 2))[1];
        $extraLen   = unpack('v', substr($raw, $offset + 28, 2))[1];
        $fnStart    = $offset + 30;
        $fn         = substr($raw, $fnStart, $fnLen);
        $dataStart  = $fnStart + $fnLen + $extraLen;

        if ($fn === $targetName) {
            $compressed = substr($raw, $dataStart, $compSize);
            if ($compMethod === 0) return $compressed;           // stored
            if ($compMethod === 8) return gzinflate($compressed) ?: ''; // deflated
            return '';
        }

        $offset = $dataStart + $compSize;
    }
    return '';
}

// ── Bold тэг тоолох ──────────────────────────────────────────
function countBold(string $xml): int
{
    $n = 0;
    // <w:b/>  эсвэл  <w:b />
    $n += preg_match_all('/<w:b\s*\/>/', $xml);
    // <w:b> (хаалттай)
    $n += preg_match_all('/<w:b>/', $xml);
    // <w:b w:val="true"/>  эсвэл  <w:b w:val="1"/>
    $n += preg_match_all('/<w:b\s+w:val="(?:true|1)"\s*\/>/', $xml);
    // Paragraph style: Heading1-6, Title
    $n += preg_match_all('/<w:pStyle\s+w:val="(?:Heading[1-6]|Title)"\s*\/>/', $xml);
    return $n;
}
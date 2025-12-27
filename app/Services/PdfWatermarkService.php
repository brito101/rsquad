<?php

namespace App\Services;

use Illuminate\Support\Str;
use setasign\Fpdi\Tcpdf\Fpdi;

class PdfWatermarkService
{
    /**
     * Apply watermark to PDF file
     *
     * @param  string  $originalPath  Full path to original PDF
     * @param  string  $userName  User's full name
     * @param  string  $userEmail  User's email
     * @param  string  $downloadId  Unique download ID
     * @return string Temporary path to watermarked PDF
     */
    public function applyWatermark(string $originalPath, string $userName, string $userEmail, string $downloadId): string
    {
        // Create new PDF document
        $pdf = new Fpdi;
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetAutoPageBreak(false);

        // Get page count
        $pageCount = $pdf->setSourceFile($originalPath);

        // Process each page
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            // Import page
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);

            // Add page with same orientation as original
            $orientation = $size['width'] > $size['height'] ? 'L' : 'P';
            $pdf->AddPage($orientation, [$size['width'], $size['height']]);

            // Use imported page as template
            $pdf->useTemplate($templateId);

            // Add watermark
            $this->addWatermarkToPage($pdf, $size, $userName, $userEmail, $downloadId);
        }

        // Save to temporary file
        $tempPath = storage_path('app/temp/pdf_'.Str::uuid().'.pdf');

        // Ensure temp directory exists
        $tempDir = dirname($tempPath);
        if (! file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $pdf->Output($tempPath, 'F');

        return $tempPath;
    }

    /**
     * Add watermark text to a single page
     */
    protected function addWatermarkToPage(Fpdi $pdf, array $size, string $userName, string $userEmail, string $downloadId): void
    {
        $width = $size['width'];
        $height = $size['height'];

        // Calculate center position
        $centerX = $width / 2;
        $centerY = $height / 2;

        // Watermark text
        $watermarkText = sprintf(
            "RSquad Academy\nLicenciado para: %s - %s\n%s - ID: %s",
            $userName,
            $userEmail,
            date('d/m/Y H:i:s'),
            $downloadId
        );

        // Set watermark properties
        $pdf->SetFont('helvetica', '', 20);
        $pdf->SetTextColor(190, 190, 190); // Light gray
        $pdf->SetAlpha(0.3); // 30% opacity

        // Save current position
        $pdf->StartTransform();

        // Rotate text diagonally (45 degrees)
        $angle = 45;
        $pdf->Rotate($angle, $centerX, $centerY);

        // Calculate text position
        $lines = explode("\n", $watermarkText);
        $lineHeight = 10;
        $totalHeight = count($lines) * $lineHeight;
        $startY = $centerY - ($totalHeight / 2);

        // Draw each line centered
        foreach ($lines as $index => $line) {
            $lineWidth = $pdf->GetStringWidth($line);
            $x = $centerX - ($lineWidth / 2);
            $y = $startY + ($index * $lineHeight);

            $pdf->Text($x, $y, $line);
        }

        // Restore transformation
        $pdf->StopTransform();

        // Reset alpha
        $pdf->SetAlpha(1);

        // Reset text color
        $pdf->SetTextColor(0, 0, 0);
    }

    /**
     * Delete temporary PDF file
     */
    public function deleteTempFile(string $path): void
    {
        if (file_exists($path)) {
            unlink($path);
        }
    }
}

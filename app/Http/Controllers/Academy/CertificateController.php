<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class CertificateController extends Controller
{
    protected $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    /**
     * Display a listing of user's certificates
     */
    public function index()
    {
        $user = Auth::user();
        $certificates = $this->certificateService->getStudentCertificates($user->id);
        $statistics = $this->certificateService->getUserStatistics($user->id);

        return view('academy.certificates.index', compact('certificates', 'statistics'));
    }

    /**
     * Display the specified certificate
     */
    public function show($id)
    {
        $certificate = Certificate::with(['user', 'course'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('academy.certificates.show', compact('certificate'));
    }

    /**
     * Download certificate PDF
     */
    public function download($id)
    {
        $certificate = Certificate::with(['user', 'course'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Generate PDF if not exists
        if (! $certificate->hasPdf()) {
            $this->certificateService->generatePDF($certificate);
        }

        $pdfPath = $certificate->getPdfFullPath();

        if (! $pdfPath || ! file_exists($pdfPath)) {
            abort(404, 'Certificado não encontrado.');
        }

        $filename = 'certificado-'.$certificate->course->uri.'-'.$certificate->verification_code.'.pdf';

        return Response::download($pdfPath, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * Public view of certificate HTML (no auth required)
     */
    public function publicViewHtml($code)
    {
        $certificate = Certificate::with(['user', 'course'])
            ->where('verification_code', $code)
            ->firstOrFail();

        // Render certificate HTML
        return view('certificates.template', [
            'certificate' => $certificate,
            'user' => $certificate->user,
            'course' => $certificate->course,
        ]);
    }

    /**
     * Get certificate statistics (AJAX)
     */
    public function statistics()
    {
        $statistics = $this->certificateService->getUserStatistics(Auth::id());

        return response()->json([
            'success' => true,
            'data' => $statistics,
        ]);
    }

    /**
     * Public view of certificate (no auth required)
     */
    public function publicView($code)
    {
        $certificate = Certificate::with(['user', 'course'])
            ->where('verification_code', $code)
            ->firstOrFail();

        return view('certificates.public', compact('certificate'));
    }

    /**
     * Public PDF download (no auth required)
     */
    public function publicPdf($code)
    {
        $certificate = Certificate::with(['user', 'course'])
            ->where('verification_code', $code)
            ->firstOrFail();

        // Generate PDF if not exists
        if (! $certificate->hasPdf()) {
            $this->certificateService->generatePDF($certificate);
        }

        $pdfPath = $certificate->getPdfFullPath();

        if (! $pdfPath || ! file_exists($pdfPath)) {
            abort(404, 'Certificado não encontrado.');
        }

        $filename = 'certificado-'.$certificate->course->uri.'-'.$certificate->verification_code.'.pdf';

        return Response::download($pdfPath, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}

<?php

namespace App\Http\Controllers\Academy;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\PdfDownload;
use App\Services\PdfWatermarkService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PdfDownloadController extends Controller
{
    protected $pdfWatermarkService;

    public function __construct(PdfWatermarkService $pdfWatermarkService)
    {
        $this->pdfWatermarkService = $pdfWatermarkService;
    }

    /**
     * Download course PDF with watermark
     */
    public function downloadCoursePdf($courseId)
    {
        $course = Course::findOrFail($courseId);

        // Verificar se existe PDF
        if (! $course->hasPdf()) {
            return redirect()
                ->back()
                ->with('error', 'Este curso não possui material em PDF disponível.');
        }

        // Verificar se o aluno está matriculado
        $isEnrolled = $course->students()
            ->where('user_id', Auth::id())
            ->exists();

        if (! $isEnrolled) {
            abort(403, 'Você não está matriculado neste curso.');
        }

        // Verificar rate limiting
        if (! PdfDownload::canDownload(Auth::id(), Course::class, $courseId)) {
            return redirect()
                ->back()
                ->with('error', 'Limite de downloads excedido. Você pode baixar no máximo 5 vezes por hora.');
        }

        return $this->processDownload($course, Course::class, $courseId);
    }

    /**
     * Download module PDF with watermark
     */
    public function downloadModulePdf($moduleId)
    {
        $module = CourseModule::with('course')->findOrFail($moduleId);

        // Verificar se existe PDF
        if (! $module->hasPdf()) {
            return redirect()
                ->back()
                ->with('error', 'Este módulo não possui material em PDF disponível.');
        }

        // Verificar se o aluno está matriculado no curso
        $isEnrolled = $module->course->students()
            ->where('user_id', Auth::id())
            ->exists();

        if (! $isEnrolled) {
            abort(403, 'Você não está matriculado no curso deste módulo.');
        }

        // Verificar rate limiting
        if (! PdfDownload::canDownload(Auth::id(), CourseModule::class, $moduleId)) {
            return redirect()
                ->back()
                ->with('error', 'Limite de downloads excedido. Você pode baixar no máximo 5 vezes por hora.');
        }

        return $this->processDownload($module, CourseModule::class, $moduleId);
    }

    /**
     * Download classroom PDF with watermark
     */
    public function downloadClassroomPdf($classroomId)
    {
        $classroom = Classroom::with('course')->findOrFail($classroomId);

        // Verificar se existe PDF
        if (! $classroom->hasPdf()) {
            return redirect()
                ->back()
                ->with('error', 'Esta aula não possui material em PDF disponível.');
        }

        // Verificar se o aluno está matriculado no curso
        $isEnrolled = $classroom->course->students()
            ->where('user_id', Auth::id())
            ->exists();

        if (! $isEnrolled) {
            abort(403, 'Você não está matriculado no curso desta aula.');
        }

        // Verificar rate limiting
        if (! PdfDownload::canDownload(Auth::id(), Classroom::class, $classroomId)) {
            return redirect()
                ->back()
                ->with('error', 'Limite de downloads excedido. Você pode baixar no máximo 5 vezes por hora.');
        }

        return $this->processDownload($classroom, Classroom::class, $classroomId);
    }

    /**
     * Process PDF download with watermark
     */
    protected function processDownload($model, $modelType, $modelId)
    {
        $user = Auth::user();
        $originalPath = $model->getPdfPath();

        // Verificar se o arquivo existe
        if (! file_exists($originalPath)) {
            return redirect()
                ->back()
                ->with('error', 'Arquivo PDF não encontrado no servidor.');
        }

        try {
            // Gerar ID único para o download
            $downloadId = Str::uuid()->toString();

            // Aplicar marca d'água
            $watermarkedPath = $this->pdfWatermarkService->applyWatermark(
                $originalPath,
                $user->name,
                $user->email,
                $downloadId
            );

            // Registrar log do download
            PdfDownload::logDownload(
                $user->id,
                $downloadId,
                $modelType,
                $modelId,
                $model->pdf_file,
                request()->ip()
            );

            // Nome do arquivo para download
            $fileName = Str::slug($model->name).'-'.date('Y-m-d').'.pdf';

            // Fazer download e depois deletar o arquivo temporário
            return response()
                ->download($watermarkedPath, $fileName, [
                    'Content-Type' => 'application/pdf',
                ])
                ->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Erro ao processar download de PDF: '.$e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Erro ao processar o download do PDF. Por favor, tente novamente.');
        }
    }
}

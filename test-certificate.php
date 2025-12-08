<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTE DE GERAÇÃO DE CERTIFICADO ===" . PHP_EOL . PHP_EOL;

// 1. Buscar curso e aluno
$course = App\Models\Course::first();
$user = App\Models\User::whereHas('roles', function($q) {
    $q->where('name', 'Aluno');
})->first();

if (!$user) {
    echo 'Nenhum aluno encontrado. Criando usuário de teste...' . PHP_EOL;
    $user = App\Models\User::create([
        'name' => 'Aluno Teste Certificado',
        'email' => 'aluno.teste@exemplo.com',
        'password' => bcrypt('password'),
        'email_verified_at' => now()
    ]);
    
    $role = Spatie\Permission\Models\Role::where('name', 'Aluno')->first();
    if ($role) {
        $user->assignRole($role);
    }
}

echo "=== DADOS DO TESTE ===" . PHP_EOL;
echo "Curso: {$course->name} (ID: {$course->id})" . PHP_EOL;
echo "Aluno: {$user->name} (ID: {$user->id})" . PHP_EOL;
echo "Carga horária: {$course->total_hours}h" . PHP_EOL;
echo PHP_EOL;

// 2. Criar inscrição
$enrollment = App\Models\CourseStudent::firstOrCreate([
    'user_id' => $user->id,
    'course_id' => $course->id
]);
echo "✓ Inscrição criada/encontrada" . PHP_EOL;

// 3. Buscar aulas do curso
$classrooms = App\Models\Classroom::where('course_id', $course->id)->get();
echo "✓ Total de aulas: {$classrooms->count()}" . PHP_EOL;

if ($classrooms->isEmpty()) {
    echo "✗ ERRO: Curso sem aulas! Impossível gerar certificado." . PHP_EOL;
    exit(1);
}

// 4. Marcar todas as aulas como assistidas
foreach ($classrooms as $classroom) {
    $progress = App\Models\ClassroomProgress::firstOrCreate([
        'user_id' => $user->id,
        'classroom_id' => $classroom->id
    ], [
        'first_viewed_at' => now()->subDays(rand(1, 30))
    ]);
    
    $progress->watched = true;
    $progress->save();
}
echo "✓ Todas as aulas marcadas como assistidas (100%)" . PHP_EOL;
echo PHP_EOL;

// 5. Testar geração do certificado
echo "=== GERANDO CERTIFICADO ===" . PHP_EOL;
$service = new App\Services\CertificateService();

// Verificar elegibilidade
$eligible = $service->checkEligibility($user->id, $course->id);
$percentage = $service->getCompletionPercentage($user->id, $course->id);
echo "Progresso: {$percentage}%" . PHP_EOL;
echo "Elegível: " . ($eligible ? "✓ SIM" : "✗ NÃO") . PHP_EOL;

if ($eligible) {
    try {
        $certificate = $service->generateCertificate($user->id, $course->id);
        
        if ($certificate) {
            echo "✓ Certificado gerado com sucesso!" . PHP_EOL;
            echo PHP_EOL;
            echo "=== DADOS DO CERTIFICADO ===" . PHP_EOL;
            echo "ID: {$certificate->id}" . PHP_EOL;
            echo "Código: {$certificate->verification_code}" . PHP_EOL;
            echo "Emitido em: {$certificate->issued_at->format('d/m/Y H:i')}" . PHP_EOL;
            echo "Iniciado em: {$certificate->started_at->format('d/m/Y')}" . PHP_EOL;
            echo "Período: {$certificate->getFormattedPeriod()}" . PHP_EOL;
            echo "Duração: {$certificate->getDurationInDays()} dias" . PHP_EOL;
            echo "PDF: " . ($certificate->hasPdf() ? "✓ Gerado" : "✗ Pendente") . PHP_EOL;
            
            if ($certificate->hasPdf()) {
                echo "Caminho: {$certificate->pdf_path}" . PHP_EOL;
                $fullPath = $certificate->getPdfFullPath();
                if ($fullPath && file_exists($fullPath)) {
                    $size = filesize($fullPath);
                    echo "Tamanho: " . number_format($size / 1024, 2) . " KB" . PHP_EOL;
                }
            }
            
            echo PHP_EOL;
            echo "=== LINKS ===" . PHP_EOL;
            echo "Verificação: {$certificate->getVerificationUrl()}" . PHP_EOL;
            echo "LinkedIn: " . substr($certificate->getLinkedInShareUrl(), 0, 100) . "..." . PHP_EOL;
            
        } else {
            echo "✗ ERRO: generateCertificate retornou null" . PHP_EOL;
        }
    } catch (Exception $e) {
        echo "✗ ERRO: {$e->getMessage()}" . PHP_EOL;
        echo "Linha: {$e->getLine()}" . PHP_EOL;
        echo "Arquivo: {$e->getFile()}" . PHP_EOL;
        echo PHP_EOL;
        echo "Stack trace:" . PHP_EOL;
        echo $e->getTraceAsString() . PHP_EOL;
    }
} else {
    echo "✗ ERRO: Aluno não elegível para certificado" . PHP_EOL;
}

echo PHP_EOL;
echo "=== TESTE CONCLUÍDO ===" . PHP_EOL;

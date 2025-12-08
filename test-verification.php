<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTE DE VERIFICAÇÃO DE CERTIFICADO ===" . PHP_EOL . PHP_EOL;

// Buscar certificado gerado
$certificate = App\Models\Certificate::latest()->first();

if (!$certificate) {
    echo "✗ Nenhum certificado encontrado" . PHP_EOL;
    exit(1);
}

echo "=== CERTIFICADO ENCONTRADO ===" . PHP_EOL;
echo "ID: {$certificate->id}" . PHP_EOL;
echo "Código: {$certificate->verification_code}" . PHP_EOL;
echo "Aluno: {$certificate->user->name}" . PHP_EOL;
echo "Curso: {$certificate->course->name}" . PHP_EOL;
echo PHP_EOL;

// Testar verificação via service
$service = new App\Services\CertificateService();
$verified = $service->verifyCertificate($certificate->verification_code);

if ($verified) {
    echo "✓ Verificação bem-sucedida!" . PHP_EOL;
    echo PHP_EOL;
    echo "=== DADOS RETORNADOS ===" . PHP_EOL;
    echo "Aluno: {$verified->user->name}" . PHP_EOL;
    echo "Curso: {$verified->course->name}" . PHP_EOL;
    echo "Carga horária: {$verified->course->total_hours}h" . PHP_EOL;
    echo "Período: {$verified->getFormattedPeriod()}" . PHP_EOL;
    echo "Emitido em: {$verified->issued_at->format('d/m/Y')}" . PHP_EOL;
} else {
    echo "✗ Falha na verificação" . PHP_EOL;
}

// Testar código inválido
echo PHP_EOL;
echo "=== TESTE COM CÓDIGO INVÁLIDO ===" . PHP_EOL;
$invalid = $service->verifyCertificate('CODIGO_INVALIDO');
if ($invalid) {
    echo "✗ ERRO: Código inválido foi aceito!" . PHP_EOL;
} else {
    echo "✓ Código inválido rejeitado corretamente" . PHP_EOL;
}

echo PHP_EOL;
echo "=== ESTATÍSTICAS DO ALUNO ===" . PHP_EOL;
$stats = $service->getUserStatistics($certificate->user_id);
echo "Total de certificados: {$stats['total_certificates']}" . PHP_EOL;
echo "Total de horas: {$stats['total_hours']}" . PHP_EOL;
echo "Cursos completos: {$stats['completed_courses']}" . PHP_EOL;

echo PHP_EOL;
echo "=== TESTE CONCLUÍDO ===" . PHP_EOL;

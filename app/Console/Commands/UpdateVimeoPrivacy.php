<?php

namespace App\Console\Commands;

use App\Models\Classroom;
use App\Services\VimeoService;
use Illuminate\Console\Command;

class UpdateVimeoPrivacy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vimeo:update-privacy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza a privacidade de todos os vídeos do Vimeo para unlisted';

    /**
     * Execute the console command.
     */
    public function handle(VimeoService $vimeoService)
    {
        $classrooms = Classroom::whereNotNull('vimeo_uri')->get();

        $this->info("Encontrados {$classrooms->count()} vídeos para atualizar...");

        $bar = $this->output->createProgressBar($classrooms->count());
        $bar->start();

        $updated = 0;
        $failed = 0;

        foreach ($classrooms as $classroom) {
            try {
                $response = $vimeoService->update($classroom->vimeo_uri, [
                    'privacy' => [
                        'view' => 'unlisted',
                        'embed' => 'public',
                    ],
                    'embed' => [
                        'buttons' => [
                            'like' => false,
                            'watchlater' => false,
                            'share' => false,
                        ],
                        'logos' => [
                            'vimeo' => false,
                        ],
                    ],
                ]);

                if ($response) {
                    $updated++;
                } else {
                    $failed++;
                }
            } catch (\Exception $e) {
                $this->error("\nErro ao atualizar vídeo {$classroom->vimeo_id}: {$e->getMessage()}");
                $failed++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info('Atualização concluída!');
        $this->info("✓ Atualizados: {$updated}");
        if ($failed > 0) {
            $this->error("✗ Falhas: {$failed}");
        }

        return Command::SUCCESS;
    }
}

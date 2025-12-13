<?php

namespace Database\Seeders;

use App\Models\CategoryCourse;
use App\Models\Classroom;
use App\Models\ClassroomProgress;
use App\Models\Course;
use App\Models\CourseCategoryPivot;
use App\Models\CourseModule;
use App\Models\CourseStudent;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TestimonialsTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            // Get or create role Aluno
            $roleAluno = Role::firstOrCreate(['name' => 'Aluno']);

            // Get first admin user for category
            $adminUser = User::whereHas('roles', function ($q) {
                $q->where('name', 'Administrador');
            })->first();

            if (! $adminUser) {
                $adminUser = User::first(); // Fallback to any user
            }

            // Get or create a course category
            $category = CategoryCourse::firstOrCreate(
                ['name' => 'Segurança da Informação'],
                [
                    'description' => 'Cursos de segurança cibernética',
                    'user_id' => $adminUser->id,
                ]
            );

            // Get or use existing courses, create if needed
            $courses = Course::limit(3)->get();

            // If less than 3 courses exist, create additional ones
            if ($courses->count() < 3) {
                $coursesToCreate = 3 - $courses->count();
                $courseNames = [
                    'Blue Team Fundamentals',
                    'SOC Analyst - Analista de Centro de Operações',
                    'DFIR - Digital Forensics & Incident Response',
                ];
                $courseUris = [
                    'blue-team-fundamentals',
                    'soc-analyst',
                    'dfir-digital-forensics',
                ];

                for ($i = 0; $i < $coursesToCreate; $i++) {
                    $newCourse = Course::create([
                        'name' => $courseNames[$i],
                        'description' => 'Curso de segurança cibernética',
                        'uri' => $courseUris[$i],
                        'active' => true,
                        'user_id' => $adminUser->id,
                    ]);
                    CourseCategoryPivot::create([
                        'course_id' => $newCourse->id,
                        'category_course_id' => $category->id,
                    ]);
                    $courses->push($newCourse);
                }
            }

            $courses = $courses->take(3);

            // Create modules and classes for each course
            foreach ($courses as $index => $course) {
                $moduleCount = rand(3, 5);

                for ($m = 1; $m <= $moduleCount; $m++) {
                    $module = CourseModule::create([
                        'course_id' => $course->id,
                        'name' => "Módulo {$m} - ".$this->getModuleName($index, $m),
                        'description' => "Conteúdo do módulo {$m}",
                        'active' => true,
                        'order' => $m,
                        'user_id' => $adminUser->id,
                    ]);

                    // Create 3-5 classes per module
                    $classCount = rand(3, 5);
                    for ($c = 1; $c <= $classCount; $c++) {
                        Classroom::create([
                            'course_id' => $course->id,
                            'course_module_id' => $module->id,
                            'name' => "Aula {$c} - ".$this->getClassName($index, $m, $c),
                            'status' => 'Publicado',
                            'active' => true,
                            'order' => $c,
                            'user_id' => $adminUser->id,
                        ]);
                    }
                }
            }

            // Create 10 students
            $students = [];
            for ($i = 1; $i <= 10; $i++) {
                $student = User::create([
                    'name' => $this->getStudentName($i),
                    'email' => "aluno{$i}@teste.com",
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]);
                $student->assignRole($roleAluno);
                $students[] = $student;
            }

            // Enroll students in courses and create progress
            $this->enrollStudentsAndCreateProgress($students, $courses);

            // Create testimonials with varied ratings and content
            $this->createTestimonials($students, $courses);

            DB::commit();

            $this->command->info('✅ Dados de teste criados com sucesso!');
            $this->command->info('📚 3 cursos criados com módulos e aulas');
            $this->command->info('👥 10 alunos criados e matriculados');
            $this->command->info('⭐ Depoimentos variados criados');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ Erro ao criar dados: '.$e->getMessage());
            throw $e;
        }
    }

    private function getModuleName($courseIndex, $moduleNumber)
    {
        $modules = [
            // Course 0 - Blue Team
            ['Introdução ao Blue Team', 'Monitoramento de Redes', 'Detecção de Ameaças', 'Resposta a Incidentes', 'Hardening de Sistemas'],
            // Course 1 - SOC
            ['Fundamentos de SOC', 'SIEM e Log Analysis', 'Threat Intelligence', 'Análise de Malware', 'Automação e Orquestração'],
            // Course 2 - DFIR
            ['Introdução à Forense', 'Coleta de Evidências', 'Análise de Memória', 'Análise de Disco', 'Timeline e Reporting'],
        ];

        return $modules[$courseIndex][$moduleNumber - 1] ?? "Módulo {$moduleNumber}";
    }

    private function getClassName($courseIndex, $moduleNumber, $classNumber)
    {
        $classes = [
            'Conceitos Básicos',
            'Ferramentas Essenciais',
            'Técnicas Avançadas',
            'Hands-on Lab',
            'Case Study',
        ];

        return $classes[$classNumber - 1] ?? "Aula {$classNumber}";
    }

    private function getStudentName($index)
    {
        $names = [
            'Ana Silva Santos',
            'Bruno Costa Oliveira',
            'Carlos Eduardo Lima',
            'Diana Ferreira Alves',
            'Eduardo Santos Rocha',
            'Fernanda Ribeiro Martins',
            'Gabriel Henrique Souza',
            'Helena Maria Carvalho',
            'Igor Pereira Mendes',
            'Julia Cristina Barbosa',
        ];

        return $names[$index - 1] ?? "Aluno {$index}";
    }

    private function enrollStudentsAndCreateProgress($students, $courses)
    {
        foreach ($students as $index => $student) {
            // Each student enrolled in 1-3 courses
            $coursesToEnroll = rand(1, 3);
            $selectedCourses = collect($courses)->random(min($coursesToEnroll, count($courses)));

            foreach ($selectedCourses as $course) {
                // Enroll student
                CourseStudent::create([
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                ]);

                // Get all classes from this course
                $classes = Classroom::where('course_id', $course->id)
                    ->where('active', true)
                    ->get();

                // Define completion percentage (varied)
                $completionOptions = [0, 25, 50, 75, 100];
                $completionPercentage = $completionOptions[array_rand($completionOptions)];

                $totalClasses = $classes->count();
                $watchedCount = (int) ceil(($completionPercentage / 100) * $totalClasses);

                // Mark classes as watched
                foreach ($classes->take($watchedCount) as $class) {
                    ClassroomProgress::create([
                        'user_id' => $student->id,
                        'classroom_id' => $class->id,
                        'watched' => true,
                    ]);
                }
            }
        }
    }

    private function createTestimonials($students, $courses)
    {
        $testimonialContents = [
            [
                'text' => 'Curso excepcional! O conteúdo é muito bem estruturado e os instrutores são extremamente qualificados. Aprendi técnicas que já estou aplicando no meu trabalho.',
                'rating' => 5,
            ],
            [
                'text' => 'Excelente curso, muito prático e direto ao ponto. As aulas são bem explicadas e os laboratórios hands-on fazem toda a diferença no aprendizado.',
                'rating' => 5,
            ],
            [
                'text' => 'Muito bom! Conteúdo atualizado e relevante para o mercado. Os cases reais apresentados ajudam muito a entender como aplicar os conceitos na prática.',
                'rating' => 4,
            ],
            [
                'text' => 'Ótimo curso! Estava buscando algo assim há tempos. A didática é clara e os exemplos práticos facilitam muito o entendimento. Recomendo!',
                'rating' => 5,
            ],
            [
                'text' => 'Curso muito completo, aborda desde o básico até técnicas avançadas. A plataforma é fácil de usar e o suporte é rápido para responder dúvidas.',
                'rating' => 4,
            ],
            [
                'text' => 'Superou minhas expectativas! O material é rico em detalhes e as ferramentas apresentadas são amplamente utilizadas no mercado. Vale cada minuto.',
                'rating' => 5,
            ],
            [
                'text' => 'Bom curso, mas alguns tópicos poderiam ser mais aprofundados. No geral, é um ótimo ponto de partida para quem está começando na área.',
                'rating' => 4,
            ],
            [
                'text' => 'Conteúdo de alta qualidade! Os instrutores demonstram vasto conhecimento prático. As certificações ao final agregam muito valor ao currículo.',
                'rating' => 5,
            ],
            [
                'text' => 'Excelente investimento! Consegui uma promoção depois de aplicar os conhecimentos adquiridos. A metodologia de ensino é muito eficaz.',
                'rating' => 5,
            ],
            [
                'text' => 'Curso muito bem elaborado. A sequência dos módulos é lógica e facilita o aprendizado progressivo. Os exercícios práticos são desafiadores e educativos.',
                'rating' => 5,
            ],
            [
                'text' => 'Ótima experiência de aprendizado! O curso me deu a confiança necessária para atuar na área. Material de apoio completo e sempre atualizado.',
                'rating' => 4,
            ],
            [
                'text' => 'Adorei o curso! A abordagem prática com cenários reais do dia a dia foi fundamental. Já estou aplicando as técnicas aprendidas no trabalho.',
                'rating' => 5,
            ],
        ];

        $statuses = ['approved', 'approved', 'approved', 'approved', 'pending', 'rejected'];
        $testimonialIndex = 0;

        foreach ($students as $student) {
            // Get courses where student has 100% completion
            $enrollments = CourseStudent::where('user_id', $student->id)->get();

            foreach ($enrollments as $enrollment) {
                $course = Course::find($enrollment->course_id);
                $classes = Classroom::where('course_id', $course->id)->where('active', true)->get();
                $totalClasses = $classes->count();

                $watchedClasses = ClassroomProgress::where('user_id', $student->id)
                    ->whereIn('classroom_id', $classes->pluck('id'))
                    ->where('watched', true)
                    ->count();

                $progressPercentage = $totalClasses > 0 ? ($watchedClasses / $totalClasses) * 100 : 0;

                // Only create testimonial if 100% complete (and randomly for some variety)
                if ($progressPercentage >= 100 && rand(1, 10) > 3) { // 70% chance
                    $testimonialData = $testimonialContents[$testimonialIndex % count($testimonialContents)];
                    $status = $statuses[array_rand($statuses)];

                    Testimonial::create([
                        'user_id' => $student->id,
                        'course_id' => $course->id,
                        'rating' => $testimonialData['rating'],
                        'testimonial' => $testimonialData['text'],
                        'status' => $status,
                        'featured' => $status === 'approved' && rand(1, 10) > 4, // 60% of approved are featured
                    ]);

                    $testimonialIndex++;
                }
            }
        }
    }
}

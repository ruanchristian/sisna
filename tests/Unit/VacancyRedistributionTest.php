<?php

namespace Tests\Unit;

use App\Models\Student;
use App\Services\Selection\RedistributionGraph;
use App\Services\Selection\VacancyAllocator;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class VacancyRedistributionTest extends TestCase {

    private const VAGAS = [
        'PCD' => 2,
        'PUBLICA-AMPLA' => 25,
        'PRIVATE-AMPLA' => 6,
        'PUBLICA-PROX-EEEP' => 10,
        'PRIVATE-PROX-EEEP' => 2,
    ];

    /** Cria candidatos já ordenados por classificação: [origem, origem, ...] -> ids 1..n */
    private function ranked(array $origens): Collection {
        return new Collection(array_map(
            fn ($origem, $i) => (new Student)->forceFill(['id' => $i + 1, 'origem' => $origem]),
            $origens, array_keys($origens)
        ));
    }

    private function ids(\Illuminate\Support\Collection $c): array {
        return $c->pluck('id')->sort()->values()->all();
    }

    public function test_grafo_padrao_nao_tem_erros(): void {
        $this->assertSame([], RedistributionGraph::default()->errors());
        $this->assertSame(RedistributionGraph::DEFAULT_EDGES, RedistributionGraph::fromConfig(null)->toArray());
    }

    public function test_validacao_do_grafo(): void {
        $errors = RedistributionGraph::fromConfig([
            'PCD' => ['PCD'],                                   // laço
            'PUBLICA-AMPLA' => ['*', 'PCD'],                    // geral fora do fim
            'PRIVATE-AMPLA' => ['PCD', 'PCD'],                  // repetido
            'PUBLICA-PROX-EEEP' => ['XYZ'],                     // destino desconhecido
            'FOO' => [],                                        // origem desconhecida
        ])->errors();

        $this->assertCount(5, $errors);
    }

    public function test_categorias_sem_destino(): void {
        $grafo = RedistributionGraph::fromConfig(['PCD' => ['*'], 'PUBLICA-AMPLA' => ['*']]);
        $this->assertSame(['PRIVATE-AMPLA', 'PUBLICA-PROX-EEEP', 'PRIVATE-PROX-EEEP'], $grafo->categoriesWithoutDestination());
    }

    public function test_pcd_que_sobra_segue_a_ordem_das_arestas(): void {
        // 1 PCD para 2 vagas; 1 vaga sobra. Candidatos públicos além das vagas próprias.
        $vagas = ['PCD' => 2, 'PUBLICA-AMPLA' => 1, 'PUBLICA-PROX-EEEP' => 1];
        $std = $this->ranked(['PUBLICA-AMPLA', 'PUBLICA-AMPLA', 'PUBLICA-PROX-EEEP', 'PUBLICA-PROX-EEEP', 'PCD']);

        // regra atual: Próximo antes de Ampla -> id 4
        $aprov = (new VacancyAllocator(RedistributionGraph::default()))->allocate($std, $vagas);
        $this->assertSame([1, 3, 4, 5], $this->ids($aprov));

        // edital novo: PCD vai direto para Pública Ampla -> id 2
        $grafo = RedistributionGraph::fromConfig(['PCD' => ['PUBLICA-AMPLA']]);
        $aprov = (new VacancyAllocator($grafo))->allocate($std, $vagas);
        $this->assertSame([1, 2, 3, 5], $this->ids($aprov));
    }

    public function test_sem_destino_a_vaga_fica_vazia(): void {
        $grafo = RedistributionGraph::fromConfig(['PCD' => []]);
        $aprov = (new VacancyAllocator($grafo))->allocate($this->ranked(['PUBLICA-AMPLA', 'PUBLICA-AMPLA']), ['PCD' => 1, 'PUBLICA-AMPLA' => 1]);
        $this->assertSame([1], $this->ids($aprov));
    }

    public function test_grafo_padrao_reproduz_o_algoritmo_antigo(): void {
        mt_srand(2026);
        $allocator = new VacancyAllocator(RedistributionGraph::default());
        $cats = array_keys(self::VAGAS);

        for ($cenario = 0; $cenario < 500; $cenario++) {
            $n = mt_rand(0, 70);
            $pesos = array_map(fn () => mt_rand(0, 10), $cats); // cada cenário com uma mistura diferente
            $origens = [];
            for ($i = 0; $i < $n; $i++) {
                $k = 0;
                $r = mt_rand(0, max(1, array_sum($pesos)) - 1);
                foreach ($pesos as $k => $p) { if (($r-=$p) < 0) break; }
                $origens[] = $cats[$k];
            }
            $vagas = array_map(fn () => mt_rand(0, 12), self::VAGAS);
            $std = $this->ranked($origens);

            $this->assertSame(
                $this->legacy($std, $vagas)->pluck('id')->all(),
                $allocator->allocate($std, $vagas)->pluck('id')->all(),
                "Divergência no cenário {$cenario}"
            );
        }
    }

    /** Cópia da lógica que existia em ResultController::rsa() antes do grafo. */
    private function legacy(Collection $std, array $origens) {
        $aprov = collect();
        $auxiliar = $origens;
        foreach ($origens as $origem => $vagas) {
            $selected = $std->where('origem', $origem)->take($vagas);
            $aprov = $aprov->merge($selected);
            $auxiliar[$origem] -= $selected->count();
        }
        foreach ($auxiliar as $origem => $k) {
            if ($k > 0) {
                if ($origem === 'PCD') {
                    $publica_prox = $std->where('origem', 'PUBLICA-PROX-EEEP')->diff($aprov)->take($k);
                    $aprov = $aprov->merge($publica_prox);
                    $k -= $publica_prox->count();
                    if ($k > 0) {
                        $publica_ampla = $std->where('origem', 'PUBLICA-AMPLA')->diff($aprov)->take($k);
                        $aprov = $aprov->merge($publica_ampla);
                        $k -= $publica_ampla->count();
                    }
                    continue;
                }
                $extra = $std->diff($aprov)->take($k);
                $aprov = $aprov->merge($extra);
            }
        }
        return $aprov;
    }
}

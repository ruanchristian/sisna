<?php

namespace App\Services\Selection;

use Illuminate\Support\Collection;

/**
 * Aloca as vagas de UM curso.
 *
 * 1ª etapa: cada categoria recebe seus melhores candidatos, até o limite de vagas.
 * 2ª etapa: as vagas que sobraram percorrem as arestas do grafo de redistribuição.
 */
class VacancyAllocator {

    public function __construct(private RedistributionGraph $graph) {}

    /**
     * @param Collection $ranked candidatos do curso já ordenados pelos critérios de desempate
     *                           (cada item com ->origem; precisa ser uma Eloquent Collection
     *                           ou itens comparáveis por diff())
     * @param array<string, int> $vagas [categoria => qtd de vagas], na ordem em que as
     *                                  categorias são processadas
     * @return Collection aprovados
     */
    public function allocate(Collection $ranked, array $vagas): Collection {
        $aprov = $ranked->take(0); // coleção vazia do mesmo tipo
        $sobra = [];

        foreach ($vagas as $origem => $qtd) {
            $selected = $ranked->where('origem', $origem)->take(max(0, (int) $qtd));
            $aprov = $aprov->merge($selected);
            $sobra[$origem] = (int) $qtd - $selected->count();
        }

        foreach ($sobra as $origem => $k) {
            foreach ($this->graph->destinations($origem) as $destino) {
                if ($k <= 0) break;

                $pool = $destino === RedistributionGraph::GERAL
                    ? $ranked
                    : $ranked->where('origem', $destino);

                $extra = $pool->diff($aprov)->take($k);
                $aprov = $aprov->merge($extra);
                $k -= $extra->count();
            }
        }

        return $aprov;
    }
}

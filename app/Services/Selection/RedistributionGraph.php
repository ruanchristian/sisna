<?php

namespace App\Services\Selection;

/**
 * Grafo dirigido de redistribuição de vagas remanescentes.
 *
 * Cada categoria (nó) possui uma lista ORDENADA de arestas de saída: quando sobram
 * vagas na categoria, elas são oferecidas aos candidatos de cada destino, na ordem
 * das arestas. O nó especial GERAL representa "qualquer candidato ainda não aprovado,
 * pela ordem de classificação geral do curso".
 *
 * Não há efeito cascata: a vaga percorre apenas as arestas da própria categoria.
 * Se nenhum destino tiver candidatos, a vaga fica vazia. Por isso a lista de cada
 * categoria deve descrever a cadeia completa prevista no edital.
 */
class RedistributionGraph {

    public const GERAL = '*';

    public const CATEGORIES = [
        'PCD',
        'PUBLICA-AMPLA',
        'PRIVATE-AMPLA',
        'PUBLICA-PROX-EEEP',
        'PRIVATE-PROX-EEEP',
    ];

    public const LABELS = [
        'PCD' => 'PCD',
        'PUBLICA-AMPLA' => 'Pública Ampla',
        'PRIVATE-AMPLA' => 'Particular Ampla',
        'PUBLICA-PROX-EEEP' => 'Pública Próximo',
        'PRIVATE-PROX-EEEP' => 'Particular Próximo',
        self::GERAL => 'Classificação geral',
    ];

    /** Regra usada até hoje (antes de ser configurável). */
    public const DEFAULT_EDGES = [
        'PCD' => ['PUBLICA-PROX-EEEP', 'PUBLICA-AMPLA'],
        'PUBLICA-AMPLA' => [self::GERAL],
        'PRIVATE-AMPLA' => [self::GERAL],
        'PUBLICA-PROX-EEEP' => [self::GERAL],
        'PRIVATE-PROX-EEEP' => [self::GERAL],
    ];

    /** @param array<string, string[]> $edges */
    private function __construct(private array $edges) {}

    public static function default(): self {
        return new self(self::DEFAULT_EDGES);
    }

    /**
     * Monta o grafo a partir de uma configuração.
     */
    public static function fromConfig(array|string|null $config): self {
        if ($config === null || $config === '') return self::default();

        if (is_string($config)) {
            $config = json_decode($config, true);
            if (!is_array($config)) return self::default();
        }

        $edges = [];
        foreach ($config as $origem => $destinos) {
            $edges[(string) $origem] = array_values(array_map('strval', (array) $destinos));
        }
        foreach (self::CATEGORIES as $cat) {
            $edges[$cat] ??= [];
        }

        return new self($edges);
    }

    public function destinations(string $origem): array {
        return $this->edges[$origem] ?? [];
    }

    /** @return array<string, string[]> lista de adjacência, na ordem de CATEGORIES */
    public function toArray(): array {
        $out = [];
        foreach (self::CATEGORIES as $cat) {
            $out[$cat] = $this->destinations($cat);
        }
        return $out;
    }

    /**
     * Erros que impedem salvar o grafo.
     *
     * @return string[]
     */
    public function errors(): array {
        $errors = [];
        $validDest = [...self::CATEGORIES, self::GERAL];

        foreach ($this->edges as $origem => $destinos) {
            if (!in_array($origem, self::CATEGORIES, true)) {
                $errors[] = "Categoria de origem desconhecida: \"{$origem}\".";
                continue;
            }
            $nome = self::LABELS[$origem];

            foreach ($destinos as $i => $destino) {
                if (!in_array($destino, $validDest, true)) {
                    $errors[] = "{$nome}: destino desconhecido \"{$destino}\".";
                } elseif ($destino === $origem) {
                    $errors[] = "{$nome}: a vaga não pode ser redistribuída para a própria categoria.";
                } elseif ($destino === self::GERAL && $i !== count($destinos) - 1) {
                    $errors[] = "{$nome}: \"" . self::LABELS[self::GERAL] . '" deve ser o último destino, pois ela já abrange todos os candidatos.';
                }
            }

            foreach (array_unique(array_diff_assoc($destinos, array_unique($destinos))) as $dup) {
                $errors[] = "{$nome}: o destino \"" . (self::LABELS[$dup] ?? $dup) . '" aparece mais de uma vez.';
            }
        }

        return $errors;
    }

    /**
     * Categorias cujas vagas remanescentes podem ficar vazias (sem nenhum destino).
     *
     * @return string[]
     */
    public function categoriesWithoutDestination(): array {
        return array_values(array_filter(self::CATEGORIES, fn ($c) => $this->destinations($c) === []));
    }
}

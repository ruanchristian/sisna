// Editor do grafo de redistribuição de vagas remanescentes.
$(() => {
    const cfg = window.REDISTRIBUICAO;
    const GERAL = cfg.geral;
    const label = (c) => cfg.labels[c] ?? c;
    const hidden = document.getElementById('redistribuicao');
    const editor = document.getElementById('redis-editor');
    const diagram = document.getElementById('redis-diagram');
    const warnings = document.getElementById('redis-warnings');

    let edges;
    try {
        edges = JSON.parse(hidden.value);
    } catch (e) {
        edges = cfg.edges;
    }
    cfg.categories.forEach((c) => { edges[c] = Array.isArray(edges[c]) ? edges[c] : []; });

    const destOptions = (origem) => [...cfg.categories.filter((c) => c !== origem), GERAL];

    function errorsOf(origem) {
        const list = edges[origem];
        const errs = [];
        if (list.some((d, i) => d === GERAL && i !== list.length - 1)) {
            errs.push(`"${label(GERAL)}" deve ser o último destino, pois já abrange todos os candidatos.`);
        }
        if (new Set(list).size !== list.length) errs.push('Há destinos repetidos.');
        return errs;
    }

    function renderEditor() {
        editor.innerHTML = '';
        cfg.categories.forEach((origem) => {
            const list = edges[origem];
            const block = document.createElement('div');
            block.className = 'mb-3';
            block.innerHTML = `<label class="mb-1">Vagas que sobrarem de <b>${label(origem)}</b> vão para:</label>`;

            const ol = document.createElement('ol');
            ol.className = 'pl-4 mb-1';

            list.forEach((destino, i) => {
                const li = document.createElement('li');
                li.className = 'mb-1';
                const opts = destOptions(origem).map((d) => {
                    const usado = d !== destino && list.includes(d);
                    return `<option value="${d}" ${d === destino ? 'selected' : ''} ${usado ? 'disabled' : ''}>${label(d)}</option>`;
                }).join('');

                li.innerHTML = `
                    <div class="input-group input-group-sm">
                        <select class="form-control">${opts}</select>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-default" data-act="up" title="Subir prioridade" ${i === 0 ? 'disabled' : ''}><i class="fas fa-arrow-up"></i></button>
                            <button type="button" class="btn btn-default" data-act="down" title="Descer prioridade" ${i === list.length - 1 ? 'disabled' : ''}><i class="fas fa-arrow-down"></i></button>
                            <button type="button" class="btn btn-default text-danger" data-act="del" title="Remover destino"><i class="fas fa-times"></i></button>
                        </div>
                    </div>`;

                li.querySelector('select').addEventListener('change', (e) => { list[i] = e.target.value; update(); });
                li.querySelectorAll('button').forEach((b) => b.addEventListener('click', () => {
                    const act = b.dataset.act;
                    if (act === 'del') list.splice(i, 1);
                    if (act === 'up') [list[i - 1], list[i]] = [list[i], list[i - 1]];
                    if (act === 'down') [list[i + 1], list[i]] = [list[i], list[i + 1]];
                    update();
                }));
                ol.appendChild(li);
            });
            block.appendChild(ol);

            const livres = destOptions(origem).filter((d) => !list.includes(d));
            const fechado = list[list.length - 1] === GERAL;
            if (livres.length && !fechado) {
                const add = document.createElement('button');
                add.type = 'button';
                add.className = 'btn btn-xs btn-outline-primary ml-4';
                add.innerHTML = '<i class="fas fa-plus"></i> Adicionar destino';
                add.addEventListener('click', () => {
                    // sugere a classificação geral por último; antes dela, a primeira categoria livre
                    list.push(livres.find((d) => d !== GERAL) ?? GERAL);
                    update();
                });
                block.appendChild(add);
            }

            errorsOf(origem).forEach((err) => {
                block.insertAdjacentHTML('beforeend', `<div class="text-danger small ml-4"><i class="fas fa-exclamation-circle"></i> ${err}</div>`);
            });
            editor.appendChild(block);
        });
    }

    function renderWarnings() {
        const vazias = cfg.categories.filter((c) => edges[c].length === 0);
        warnings.innerHTML = vazias.length
            ? `<div class="alert alert-warning mb-0 py-2"><i class="fas fa-exclamation-triangle"></i>
                 As vagas remanescentes de <b>${vazias.map(label).join(', ')}</b> ficarão vazias (sem destino).</div>`
            : '';
    }

    // ---- Quantidade de vagas (lida dos campos do formulário, ao vivo) ----
    const VAGAS_INPUT = {
        'PCD': 'vagas_pcd',
        'PUBLICA-AMPLA': 'vagas_publica_ampla',
        'PRIVATE-AMPLA': 'vagas_private_ampla',
        'PUBLICA-PROX-EEEP': 'vagas_publica_prox',
        'PRIVATE-PROX-EEEP': 'vagas_private_prox',
    };
    const vagasDe = (c) => {
        const input = document.querySelector(`input[name="${VAGAS_INPUT[c]}"]`);
        const n = input ? parseInt(input.value, 10) : NaN;
        return Number.isNaN(n) ? null : n;
    };
    const vagasTxt = (n) => (n === null ? '' : `${n} ${n === 1 ? 'vaga' : 'vagas'}`);

    // ---- Leitura do grafo por extenso ----
    const ordinal = (i) => `${i + 1}ª opção`;

    function renderSummary() {
        const summary = document.getElementById('redis-summary');
        if (!summary) return;

        summary.innerHTML = cfg.categories.map((origem) => {
            const list = edges[origem];
            const n = vagasDe(origem);
            const titulo = `<b>${label(origem)}</b>${n !== null ? ` (${vagasTxt(n)})` : ''}`;

            if (n === 0) return `<li>${titulo}: não oferta vagas, nada a redistribuir.</li>`;
            if (!list.length) {
                return `<li>${titulo}: se sobrarem vagas, elas <span class="text-warning font-weight-bold">ficam vazias</span>.</li>`;
            }

            const passos = list.map((d, i) => {
                const alvo = d === GERAL
                    ? `<b>${label(GERAL)}</b> (qualquer candidato ainda não aprovado)`
                    : `candidatos de <b>${label(d)}</b>`;
                if (i > 0) return `se não houver mais, para ${alvo}`;
                return list.length === 1 ? `vão para ${alvo}` : `vão primeiro para ${alvo}`;
            });
            const fim = list[list.length - 1] === GERAL
                ? '' // a classificação geral só fica sem candidatos se não houver mais ninguém no curso
                : '; se ainda sobrar, ficam vazias';

            return `<li>${titulo}: as vagas que sobrarem ${passos.join('; ')}${fim}.</li>`;
        }).join('');
    }

    // ---- Diagrama (Mermaid) ----
    mermaid.initialize({ startOnLoad: false, theme: 'neutral', flowchart: { curve: 'basis', htmlLabels: true } });
    const nodeId = (c) => (c === GERAL ? 'GERAL' : 'N' + cfg.categories.indexOf(c));
    let renderSeq = 0;

    async function renderDiagram() {
        const lines = ['flowchart LR'];

        cfg.categories.forEach((c) => {
            const n = vagasDe(c);
            const sub = n !== null ? `<br/><small>${vagasTxt(n)}</small>` : '';
            lines.push(`  ${nodeId(c)}["<b>${label(c)}</b>${sub}"]`);
        });
        lines.push(`  ${nodeId(GERAL)}(["<b>${label(GERAL)}</b><br/><small>qualquer candidato não aprovado</small>"])`);

        const linkStyles = [];
        let linkIndex = 0;
        cfg.categories.forEach((origem) => {
            edges[origem].forEach((destino, i) => {
                const seta = i === 0 ? '==>' : '-.->';
                lines.push(`  ${nodeId(origem)} ${seta}|"${ordinal(i)}"| ${nodeId(destino)}`);
                if (i === 0) linkStyles.push(`  linkStyle ${linkIndex} stroke:#3c6cac,stroke-width:2.5px;`);
                linkIndex++;
            });
        });
        lines.push(...linkStyles);

        lines.push('  classDef geral fill:#e8f0fb,stroke:#3c6cac,stroke-width:2px;');
        lines.push('  classDef vazio fill:#fff4e5,stroke:#e8a33d,stroke-width:2px,stroke-dasharray:4 3;');
        lines.push('  class GERAL geral;');
        const semDestino = cfg.categories.filter((c) => edges[c].length === 0 && vagasDe(c) !== 0);
        if (semDestino.length) lines.push(`  class ${semDestino.map(nodeId).join(',')} vazio;`);

        const seq = ++renderSeq;
        try {
            const { svg } = await mermaid.render('redis-svg-' + seq, lines.join('\n'));
            if (seq === renderSeq) diagram.innerHTML = svg;
        } catch (e) {
            if (seq === renderSeq) diagram.textContent = 'Não foi possível desenhar o diagrama.';
        }
    }

    function update() {
        hidden.value = JSON.stringify(edges);
        renderEditor();
        renderWarnings();
        renderSummary();
        renderDiagram();
    }

    // Ao mudar a quantidade de vagas, redesenha só o que depende dela
    let vagasTimer;
    Object.values(VAGAS_INPUT).forEach((name) => {
        document.querySelector(`input[name="${name}"]`)?.addEventListener('input', () => {
            clearTimeout(vagasTimer);
            vagasTimer = setTimeout(() => { renderSummary(); renderDiagram(); }, 250);
        });
    });

    document.getElementById('redis-reset').addEventListener('click', () => {
        edges = JSON.parse(JSON.stringify(cfg.default));
        update();
    });

    update();
});

<?php

namespace App\Http\Controllers\Changelog;

use App\Http\Controllers\Controller;
use App\Models\Changelog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ChangelogController extends Controller
{
    private const TIPOS = ['adicionado', 'melhorado', 'corrigido', 'removido'];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $todas = Changelog::orderByDesc('data')->orderByDesc('id')->get(['id', 'versao']);

        $posicao = $todas->search(fn($v) => $v->versao === $user->last_seen_version);
        $novas = $posicao === false
            ? $todas->pluck('id')
            : $todas->take($posicao)->pluck('id');

        $atualId = $todas->first()->id ?? null;

        $versoes = Changelog::orderByDesc('data')->orderByDesc('id')->paginate(10);

        return view('changelog.index', compact('versoes', 'novas', 'atualId'));
    }

    public function show(Changelog $changelog)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $versoes  = Changelog::orderByDesc('data')->orderByDesc('id')->get(['id', 'versao']);
        $posAtual = $versoes->search(fn($v) => $v->id === $changelog->id);
        $posVista = $versoes->search(fn($v) => $v->versao === $user->last_seen_version);
        if ($posVista === false || $posAtual < $posVista) {
            $user->last_seen_version = $changelog->versao;
            $user->save();
        }

        return view('changelog.show', compact('changelog'));
    }

    public function create()
    {
        Gate::authorize('isDev', auth()->user());
        return view('changelog.form');
    }

    public function store(Request $request) {
        Gate::authorize('isDev', auth()->user());

        Changelog::create($this->dados($request));

        return redirect()->route('changelog.index')->with('success', 'Versão cadastrada.');
    }

    public function edit(Changelog $changelog)
    {
        Gate::authorize('isDev', auth()->user());
        return view('changelog.form', compact('changelog'));
    }

    public function update(Request $request, Changelog $changelog)
    {
        Gate::authorize('isDev', auth()->user());

        $changelog->update($this->dados($request, $changelog));

        return redirect()->route('changelog.index')->with('success', 'Versão atualizada.');
    }

    public function destroy(Changelog $changelog)
    {
        Gate::authorize('isDev', auth()->user());
        
        $changelog->delete();

        return redirect()->route('changelog.index')->with('success', 'Versão excluída.');
    }

    // valida e converte os textos pra o formato salvo no banco
    private function dados(Request $request, ?Changelog $changelog = null): array
    {
        $validated = $request->validate([
            'versao'     => ['required', 'string', 'max:20', Rule::unique('changelogs', 'versao')->ignore($changelog)],
            'data'       => ['required', 'date'],
            'mudancas'   => ['nullable', 'array'],
            'mudancas.*' => ['nullable', 'string'],
        ]);

        $mudancas = [];
        foreach (self::TIPOS as $tipo) {
            $linhas = preg_split('/\r\n|\r|\n/', $validated['mudancas'][$tipo] ?? '');
            $linhas = array_values(array_filter(array_map('trim', $linhas)));

            if ($linhas) $mudancas[$tipo] = $linhas;
        }

        if (!$mudancas) {
            throw ValidationException::withMessages([
                'mudancas' => 'Informe ao menos uma mudança.',
            ]);
        }

        return [
            'versao'   => $validated['versao'],
            'data'     => $validated['data'],
            'mudancas' => $mudancas,
        ];
    }
}

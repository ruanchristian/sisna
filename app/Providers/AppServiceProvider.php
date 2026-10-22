<?php

namespace App\Providers;

use App\Models\Changelog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Event::listen(BuildingMenu::class, function (BuildingMenu $event) {
            $user = Auth::user();

            if (!$user) return;

            $versoes = Changelog::orderByDesc('data')->orderByDesc('id')->get(['id', 'versao', 'data']);

            // Versões mais novas que a última vista pelo usuário
            $posVista = $versoes->search(fn($v) => $v->versao === $user->last_seen_version);
            $novas = $posVista === false
                ? $versoes->pluck('id')
                : $versoes->take($posVista)->pluck('id');

            $submenu = [];
            foreach ($versoes->take(5) as $v) {
                $sub = [
                    'text'  => 'v' . $v->versao . ' · ' . $v->data->format('d/m/Y'),
                    'route' => ['changelog.show', ['changelog' => $v->id]],
                    'icon'  => 'fas fa-fw fa-code-branch',
                ];

                if ($novas->contains($v->id)) {
                    $sub['label']       = 'Novo';
                    $sub['label_color'] = 'novo';
                }

                $submenu[] = $sub;
            }

            $submenu[] = [
                'text'  => 'Ver todas as versões',
                'route' => 'changelog.index',
                'icon'  => 'fas fa-fw fa-list',
            ];

            $item = [
                'text'    => 'Novidades',
                'icon'    => 'fas fa-fw fa-paper-plane',
                'topnav'  => true,
                'submenu' => $submenu,
            ];

            if ($novas->isNotEmpty()) {
                $item['label']       = 'Novo';
                $item['label_color'] = 'novo';
            }

            $event->menu->add($item);
        });
    }
}

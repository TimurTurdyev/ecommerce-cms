<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Компоненты layout
        Blade::component('layouts.components.page-header', 'page-header');

        // Sharing navigation data with all views
        View::composer('layouts.partials.sidebar', function ($view) {
            $view->with('navigation', $this->getNavigation());
            $view->with('unreadNotificationsCount', auth()->check()
                ? auth()->user()->unreadNotifications()->count()
                : 0
            );
            $view->with('recentNotifications', auth()->check()
                ? auth()->user()->notifications()->take(5)->get()->map(function ($notification) {
                    return [
                        'title' => $notification->data['title'] ?? 'Уведомление',
                        'icon' => $notification->data['icon'] ?? 'information-circle',
                        'url' => $notification->data['url'] ?? '#',
                        'time' => $notification->created_at->diffForHumans(),
                    ];
                })
                : []
            );
        });

        View::composer('layouts.partials.header', function ($view) {
            $view->with('quickActions', [
                [
                    'label' => 'Новый пользователь',
                    'icon' => 'user-plus',
                    'url' => '#',
                ],
                [
                    'label' => 'Новый проект',
                    'icon' => 'folder-plus',
                    'url' => '#',
                ],
                [
                    'label' => 'Новая задача',
                    'icon' => 'document-plus',
                    'url' => '#',
                ],
            ]);
        });

        View::composer('layouts.partials.footer', function ($view) {
            $view->with('footerLinks', $this->getFooterLinks());
        });
    }

    protected function getNavigation(): array
    {
        return [
            [
                'label' => 'Примеры',
                'items' => [
                    [
                        'label' => 'Example 1',
                        'icon' => 'code-bracket',
                        'url' => route('example'),
                        'active' => request()->routeIs('example'),
                    ],
                ],
            ],
            [
                'label' => 'Настройки',
                'items' => [
                    [
                        'label' => 'Настройки системы',
                        'icon' => 'cog-6-tooth',
                        'url' => '#',
                        'active' => request()->routeIs('settings.system'),
                    ],
                    [
                        'label' => 'Пользователи',
                        'icon' => 'user-group',
                        'url' => route('user.index'),
                        'active' => request()->routeIs('user.*'),
                    ],
                    [
                        'label' => 'Интеграции',
                        'icon' => 'puzzle-piece',
                        'url' => '#',
                        'active' => request()->routeIs('settings.integrations'),
                    ],
                ],
            ],
        ];
    }


    protected function getFooterLinks(): array
    {
        return [
            [
                'label' => 'Документация',
                'url' => '#',
                'external' => false,
            ],
            [
                'label' => 'Поддержка',
                'url' => '#',
                'external' => false,
            ],
            [
                'label' => 'API',
                'url' => '#',
                'external' => false,
            ],
            [
                'label' => 'Статус',
                'url' => '#',
                'external' => true,
            ],
            [
                'label' => 'GitHub',
                'url' => '#',
                'external' => true,
            ],
        ];
    }
}

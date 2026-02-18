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
                'label' => 'Каталог',
                'items' => [
                    [
                        'label' => 'Товары',
                        'icon' => 'cube',
                        'url' => route('product.index'),
                        'active' => request()->routeIs('product.*'),
                    ],
                    [
                        'label' => 'Категории',
                        'icon' => 'folder',
                        'url' => route('category.index'),
                        'active' => request()->routeIs('category.*'),
                    ],
                    [
                        'label' => 'Производители',
                        'icon' => 'building-office',
                        'url' => route('manufacturer.index'),
                        'active' => request()->routeIs('manufacturer.*'),
                    ],
                ],
            ],
            [
                'label' => 'Заказы',
                'items' => [
                    [
                        'label' => 'Заказы',
                        'icon' => 'shopping-cart',
                        'url' => route('order.index'),
                        'active' => request()->routeIs('order.*'),
                    ],
                ],
            ],
            [
                'label' => 'Контент',
                'items' => [
                    [
                        'label' => 'Статьи',
                        'icon' => 'document-text',
                        'url' => route('information.index'),
                        'active' => request()->routeIs('information.*'),
                    ],
                ],
            ],
            [
                'label' => 'Настройки',
                'items' => [
                    [
                        'label' => 'Пользователи',
                        'icon' => 'user-group',
                        'url' => route('user.index'),
                        'active' => request()->routeIs('user.*'),
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

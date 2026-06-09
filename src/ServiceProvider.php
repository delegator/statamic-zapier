<?php

namespace Delegator\ZapierForms;

use Statamic\Stache\Stache;
use Statamic\Facades\CP\Nav;
use Statamic\Facades\Permission;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $listen = [
        'Statamic\Events\FormSubmitted' => [
            'Delegator\ZapierForms\Listeners\PushToWebhook',
        ],
    ];

    protected $routes = [
        'cp' => __DIR__ . '/../routes/cp.php',
    ];

    public function boot()
    {
        parent::boot();

        // load publishables
        $this->bootPublishables();

        // load navigation
        $this->bootNavigation();

        // permissions
        Permission::group('zapier-forms', 'Zapier Forms', function () {
            Permission::register('configure form zapier webhooks')->label('Configure Webhooks');
        });
    }

    public function bootPublishables(): static
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/statamic/zapier.php' => config_path('/statamic/zapier.php'),
            ], 'config');
        }

        return $this;
    }

    private function bootNavigation(): void
    {
        Nav::extend(function ($nav) {
            $nav->tools('Zapier Forms')
                ->can('configure form zapier webhooks')
                ->route('zapier-forms.index')
                ->icon('hierarchy-hub-integration-connection');
        });
    }
}

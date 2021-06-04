<?php

namespace PowerComponents\LivewirePowerGrid\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use PowerComponents\LivewirePowerGrid\Actions\ButtonAction;
use PowerComponents\LivewirePowerGrid\Actions\RedirectAction;
use PowerComponents\LivewirePowerGrid\Commands\PowerGridCommand;
use PowerComponents\LivewirePowerGrid\Http\Livewire\ConfirmationModal;

class PowerGridServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([PowerGridCommand::class]);
        }

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'livewire-powergrid');

        $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/laravel-powergrid')], 'livewire-powergrid-views');

        $this->publishes([
            __DIR__ . '/../../resources/config/livewire-powergrid.php' => config_path('livewire-powergrid.php'),
        ], 'livewire-powergrid-config');

        $this->publishes([
            __DIR__ . '/../../resources/lang' => resource_path('lang/vendor/livewire-powergrid')
        ], 'livewire-powergrid-lang');

        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'livewire-powergrid');

        Blade::directive('powerGridStyles', function () {
            return "<?php echo view('livewire-powergrid::assets.styles')->render(); ?>";
        });

        Blade::directive('powerGridScripts', function () {
            return "<?php echo view('livewire-powergrid::assets.scripts')->render(); ?>";
        });

        Blade::directive('UIBadge', function ($expression) {
            return "<?php echo PowerComponents\LivewirePowerGrid\UI\UI::badge($expression); ?>";
        });

        Livewire::component('livewire-powergrid:button-action', ButtonAction::class);
        Livewire::component('livewire-powergrid:redirect-action', RedirectAction::class);
        Livewire::component('livewire-powergrid:confirmation-modal', ConfirmationModal::class);

    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../resources/config/livewire-powergrid.php', 'livewire-powergrid'
        );
    }
}

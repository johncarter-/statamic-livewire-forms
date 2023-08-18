<?php

namespace Johncarter\StatamicLivewireForms;

use Johncarter\StatamicLivewireForms\Commands\MakeViewCommand;
use Johncarter\StatamicLivewireForms\Http\Livewire\StatamicForm;
use Livewire\Livewire;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    public function boot()
    {
        parent::boot();
        Livewire::component('statamic-form', StatamicForm::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeViewCommand::class,
            ]);
        }
    }
}

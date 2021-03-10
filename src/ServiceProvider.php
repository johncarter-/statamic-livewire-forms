<?php

namespace Johncarter\StatamicLivewireForms;

use Johncarter\StatamicLivewireForms\Http\Livewire\LivewireForm;
use Livewire\Livewire;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    public function boot()
    {
        parent::boot();
        Livewire::component('livewire-form', LivewireForm::class);
    }
}

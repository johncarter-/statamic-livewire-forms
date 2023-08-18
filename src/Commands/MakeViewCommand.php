<?php

namespace Johncarter\StatamicLivewireForms\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Statamic\Facades\Form;
use Illuminate\Support\Str;

class MakeViewCommand extends Command
{
    public $signature = 'statamic-livewire-forms:make:view';

    public $description = 'Create form component views based on current Statamic forms';

    public function handle()
    {
        $availableForms = Form::all();

        if (!$availableForms->count()) {
            return $this->info("Whoops! You haven't created any forms in Statamic yet.");
        }

        $formTitles = $availableForms->map(function ($form) {
            return $form->title();
        })->toArray();

        $chosenForm = $this->choice(
            'Which form do you want a view for?',
            $formTitles,
            0
        );

        $form = $availableForms->filter(function ($form) use ($chosenForm) {
            return $form->title() == $chosenForm;
        })->first()->toAugmentedCollection();

        $viewName = $this->ask('Name the view file:', Str::slug($form->get('handle')));

        File::ensureDirectoryExists(resource_path('views/livewire/'));

        $destination = resource_path('views/livewire/' . $viewName . '.blade.php');

        if (file_exists($destination)) {
            return $this->comment('The view already exists: ' . $this->getRelativePath($destination));
        }

        copy(__DIR__ . '/../../stubs/resources/views/livewire/form.blade.php', resource_path('views/livewire/' . $viewName . '.blade.php'));

        $this->info('View was created: ' . $this->getRelativePath($destination));
    }

    protected function getRelativePath($path)
    {
        return str_replace(base_path() . '/', '', $path);
    }
}

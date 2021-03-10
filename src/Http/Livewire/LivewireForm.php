<?php

namespace Johncarter\StatamicLivewireForms\Http\Livewire;

use Illuminate\Support\Facades\URL;
use Livewire\Component;
use Statamic\Events\SubmissionCreated;
use Statamic\Facades\Form;
use Statamic\Facades\Site;
use Statamic\Forms\SendEmails;
use Illuminate\Support\Str;

class LivewireForm extends Component
{
    public $formHandle;
    public $fields;
    public $success;

    protected $form;

    public function mount($formHandle)
    {
        $this->formHandle = $formHandle;
        $this->getForm();
        $this->fields = array_fill_keys($this->form->blueprint()->fields()->all()->keys()->toArray(), '');
    }

    public function hydrate()
    {
        $this->getForm();
    }

    private function getForm()
    {
        $this->form = Form::find($this->formHandle);
    }

    protected function rules()
    {
        return $this->form->blueprint()->fields()->all()->mapWithKeys(function ($field) {
            return ['fields.' . $field->handle() => collect($field->rules())->flatten()];
        })->toArray();
    }

    protected function validationAttributes()
    {
        return $this->form->blueprint()->fields()->all()->mapWithKeys(function ($field) {
            return ['fields.' . $field->handle() => $field->display()];
        })->toArray();
    }

    public function submit()
    {
        $site = Site::findByUrl(URL::previous());

        $validatedData = $this->validate();

        $submission = $this->form->makeSubmission()->data($validatedData['fields']);

        if ($this->form->store()) {
            $submission->save();
        }

        SubmissionCreated::dispatch($submission);
        SendEmails::dispatch($submission, $site);

        $this->reset('fields');
        $this->success = true;
    }

    public function render()
    {
        return view('livewire.' . Str::slug($this->formHandle));
    }
}

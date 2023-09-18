<?php

namespace Johncarter\StatamicLivewireForms\Http\Livewire;

use Illuminate\Support\Facades\URL;
use Livewire\Component;
use Statamic\Events\SubmissionCreated;
use Statamic\Facades\Form;
use Statamic\Facades\Site;
use Statamic\Forms\SendEmails;
use Illuminate\Support\Str;
use Statamic\Fields\Field;

class StatamicForm extends Component
{
    public $handle;
    public $view;
    public $data;
    public $fields;
    public $success;

    protected $form;

    public function mount($handle, $view = null)
    {
        $this->handle = $handle;
        $this->view = $view ?? Str::slug($this->handle);
        $this->getForm();
        $this->data = array_fill_keys($this->form->blueprint()->fields()->all()->keys()->toArray(), '');
        $this->fields = $this->form->blueprint()->fields()->all()->mapWithKeys(function (Field $field, string $fieldHandle) {
            $fieldConfig = $field->config();
            $fieldConfig['required'] = in_array('required', $fieldConfig['validate']);
            unset($fieldConfig['validate']);
            return [$fieldHandle => $fieldConfig];
        })->all();
    }

    public function hydrate()
    {
        $this->getForm();
    }

    private function getForm()
    {
        $this->form = Form::find($this->handle);
    }

    protected function rules()
    {
        return $this->form->blueprint()->fields()->all()->mapWithKeys(function ($field) {
            return ['data.' . $field->handle() => collect($field->rules())->flatten()];
        })->toArray();
    }

    protected function validationAttributes()
    {
        return $this->form->blueprint()->fields()->all()->mapWithKeys(function ($field) {
            return ['data.' . $field->handle() => $field->display()];
        })->toArray();
    }

    public function submit()
    {
        $site = Site::findByUrl(URL::previous());

        $validatedData = $this->validate();

        $submission = $this->form->makeSubmission()->data($validatedData['data']);

        if ($this->form->store()) {
            $submission->save();
        }

        SubmissionCreated::dispatch($submission);
        SendEmails::dispatch($submission, $site);

        $this->reset('data');
        $this->success = true;
    }

    public function render()
    {
        return view('livewire.statamic-forms.' . $this->view);
    }
}

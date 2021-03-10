# Livewire Statamic Forms

This addon allows you to submit forms in Statamic CMS using Laravel Livewire.

## Installation

You can install the package via composer:

```bash
composer require johncarter/statamic-livewire-forms
```

## Usage

1. Include [Livewire styles and scripts](https://github.com/jonassiewertsen/statamic-livewire#how-to-be-used-with-the-antlers-template-engine) in your layout.
2. [Create your form](https://statamic.dev/forms#create-the-form) in Statamic.
3. Create a Livewire view component using the snake case name of your form. e.g. If my form's name was `Contact Form` then my view would like this be: `resources/views/livewire/contact-form.blade.php`. You _can_ use Antlers but accessing the default Laravel Error Bag isn't supported yet. See https://github.com/statamic/ideas/issues/179
4. Add this boilerplate to your view component to get started:
```html
<div>
    <form wire:submit.prevent="submit">
        {{-- Add your fields here binding them like this: wire:model.lazy="fields.your_field_name" --}}
        {{-- <input autocomplete="name" type="text" wire:model.lazy="fields.name" /> --}}
        {{-- @error('fields.name')<div>{{ $message }}</div>@enderror --}}
        <button>Submit</button>
        @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
            <li class="mb-1">{{ $error }}</li>
            @endforeach
        </ul>
        @endif
        @if($success)
        <div>
            <p>Thanks!</p>
        </div>
        @endif
    </form>
</div>
```
5. Use `{{ livewire:livewire-form formHandle="contact_form" }}` In your template where you want the form rendered.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
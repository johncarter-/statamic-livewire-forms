## Unreleased
- Changed livewire component name from `livewire:livewire-form` to `livewire:statamic-form`.
- Fixed error when no livewire view directory exists.
- Changed data binding property from `field.*` to `data.*`
- Added automatic field rendering example to the form component stub.
- Changed default view directory for components.

## [0.4.0] - 2021-03-17
### Added
- View generation command to create form component views based on current Statamic forms that allows users to select a form and make a basic view from a stub. ( Doesn't auto-populate fields)
### Changed
- Allows you to specify a view on the livewire component incase you want to have two forms with different views.
- Tidys up README.

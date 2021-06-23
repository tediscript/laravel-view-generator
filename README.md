# laravel-view-generator
Laravel module to generate view from artisan command

## Installation
Since it's not (yet) available in packagist.org then here is how to install it. Just add this script to your `composer.json` and run `composer update`

```
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/tediscript/laravel-view-generator"
        }
    ],
    "require-dev": {
        "tediscript/laravel-view-generator": "^0.1.0"
    }
```

## Usage
Just like you create model via php artisan.

### Make View Command
```
php artisan make:view view-name
```
It will generate file `resources/views/view-name.blade.php` using `plain.stub` template.


```
php artisan make:view schools.edit -model=School --layout=edit
```
It will generate file `resources/views/schools/edit.blade.php` using `edit.stub` template.

- argument `schools.edit` is the path name of blade file (just like view name in render)
- option `--model=School` is the model name. It is optional. Default model name is `Item`
- option `--layout=edit` is used to specify the view stub file. The option are `create`, `edit`, `index`, `plain`, `show`. The default value is `plain`

### Create your own layout template
You can create your own stub file inside folder `resources/stubs`. 
For example create file `resources/stubs/welcome.stub` then you can call artisan command:
```
php artisan make:view awesome --layout=welcome
```
It will generate file `resources/views/awesome.blade.php` using `welcome.stub` layout.

The supported variable to render are:
- `{{ model }}` we get it from option `--model`
- `{{ pluralModel }}` we get it from pluralize of `model`
- `{{ resourceName }}` we get it from lower case of `pluralModel`
- `{{ instanceModel }}` we get it from camel case of `model`
- `{{ instanceCollectionModel }}` we get it from pluralize of `instanceModel`

Thats it.

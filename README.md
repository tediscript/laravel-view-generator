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
php artisan make:view schools/edit School --type=edit
```
It will generate file `resources/views/schools/edit.blade.php` using `edit.stub` template.

- argument `schools/edit` is the name of blade file
- argument `School` is the model name. It is optional. Default model name is `Item`
- option `--type=edit` is used to specify the view stub file. The option are `create`, `edit`, `index`, `plain`, `show`. The default value is `plain`

### Create your own stub file
You can create your own stub file inside folder `resources/stubs/`. 
For example create file `resources/stubs/welcome.stub` then you can call artisan command:
```
php artisan make:view awesome --type=welcome
```
It will generate file `resources/views/awesome.blade.php`

The supported variable to render are:
- `{{ name }}` we get it from argument model name
- `{{ pluralName }}` we get it from pluralize of `name`
- `{{ resourceName }}` we get it from lower case of `pluralName`
- `{{ modelName }}` we get it from camel case of `name`
- `{{ modelCollectionName }}` we get it from pluralize of `modelName`

Thats it.

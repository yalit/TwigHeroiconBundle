# TwigHeroicon Bundle
Simple Symfony bundle that provides a Twig extension 'heroicon' to get all the Heroicons svg automatically. The bundle depends on the node package [tailwindlabs/heroicons](https://github.com/tailwindlabs/heroicons/) to get the svg icons.

1. [Installation](#installation)
2. [Usage](#usage)
3. [Configuration](#configuration)

    a. [Default Configuration](#default-configuration)

    b. [Webpack Configuration](#webpack-configuration)

## Installation
First, start by installing the package via composer:
```bash
composer require yalit/twig-heroicon-bundle
```

Then, enable the bundle in your `config/bundles.php` file:
```php
// config/bundles.php
<?php 

return [
    // ...
    Yalit\TwigHeroiconBundle\TwigHeroiconBundle::class => ['all' => true],
];
```

Then, install the node package [tailwindlabs/heroicons](https://github.com/tailwindlabs/heroicons/) using your favorite package manager (here shown using npm):
```bash
npm install taiwindlabs/heroicons
```

## Usage
The usage is simple, you can use the `heroicon` function in your twig templates to get the svg icons. The function has the following signature:
```twig
{{ heroicon('name', 'type', 'size', 'classname') }}
```
**where:**
- `name` is a string and the name of the icon (required)
- `type` is a string and the type of the icon (optional) => the possible values are 'outline' and 'solid' (see https://heroicons.com/)
- `size` is a __string__ and the size of the icon (optional) => the possible values are '24', '20', '16' (see https://heroicons.com/)
  - !! for the sizes 16 and 20, the icons are only availabe in the 'solid type' (see https://heroicons.com/)
- `classname` is a string and the class name of the icon (optional and default is no specific class)

Here is an example of how to use the function:
```twig
{{ heroicon('academic-cap', 'solid', '20', 'text-red-500') }}
```

## Configuration
### Default Configuration
The bundle can be configured to change:
- the source of the svg icons
  - the possible values are 'node' and 'webpack' (see below [Webpack Configuration](#webpack-configuration))
- the default display type of the icons
  - this value is used if no `type` is provided in the `heroicon` function
  - the possible values are 'outline' and 'solid'
- the default size of the icons
  - this value is used if no `size` is provided in the `heroicon` function
  - the possible values are '24', '20', '16' (see above for the possible combination with the types)

Here is the default configuration of the bundle:
```yaml
twig_heroicon:
    source: 'node'
    default_display_type: 'outline'
    default_size: '24'
```

### Webpack Configuration
The default configuration source the svg icons directly from the node_modules/heroicons folder needing to keep that folder in the project. If you want to use only the needed icons and get them from the webpack build, you can change the source to 'webpack' and add the following configuration:
```yaml
twig_heroicon:
    source: 'webpack'
```

Then, you need to add the following configuration in your `webpack.config.js` file:
```javascript
const TwigHeroiconPlugin = require("./vendor/yalit/twig-heroicon-bundle/assets/twigHeroiconPlugin");

Encore
    // ...
    .addPlugin(new TwigHeroiconPlugin())
```
This configuration will copy the needed svg icons from the node_modules/heroicons folder to the public/build folder and the Twig extension will then fetch the icons from there.

#### Webpack plugin options
This plugin by default will (during the build) look at the templates folder to all the twig files and get all the needed icons used in the `heroicon` function. \
It can also get a list of specific icons from the node_modules/heroicons folder.

The following options can be passed to the plugin and here is the default configuration:
```javascript
 {
    templatePaths: ["templates"], 
    defaultSize: "24", 
    defaultDisplayType: "outline",
    importType: 'twig', 
    importedHeroicons: [] 
}
```
**where:**
- `templatePaths` is an array of strings and the paths of the root folders of the templates to look for twig files
  - the default value is `["templates"]`
- `defaultSize` is a string and the default size of the icons => if no size is provided in the `heroicon` function, then this will be the size of the icon fetched
  - the possible values are '24', '20', '16' (see above for the possible combination with the types)
- `defaultDisplayType` is a string and the default display type of the icons => if no type is provided in the `heroicon` function, then this will be the display type of the icon fetched
  - the possible values are 'outline' and 'solid'
- `importType` is a string and the type of the import
  - 'twig' will only look at the twig files located in the folders mentioned in the templatesPaths array
  - 'specific' will only look at the `importedHeroicons` array to fetch the icons
  - 'both' will combine the two above
- `importedHeroicons` is an array of strings or objects
  - if the `importType` is 'specific', then this array will be used to fetch the icons
  - if it's a string it must be a valid icon name and the defaultSize and defaultDisplayType will be used
  - if it's an object, then it must have the following properties:
      - `name` is a string and the name of the icon (required)
      - `displayType` is a string and the type of the icon (required) => the possible values are 'outline' and 'solid' (see https://heroicons.com/)
      - `size` is a __string__ and the size of the icon (required) => the possible values are '24', '20', '16' (see https://heroicons.com/)


These options can be passed to the plugin in the `addPlugin` function like this:
```javascript
.addPlugin(new TwigHeroiconPlugin({
    importType: 'both',
    importedHeroicons: [
        {name: 'academic-cap', displayType: 'solid', size: '20'},
        'adjustments-vertical',
    ]    
}))
```

**Note** : if the webpack configuration is used, you can install the tailwindlabs/heroicons package as a dev dependency:
```bash
npm install taiwindlabs/heroicons --save-dev
```

LeafLet Extension for Yii2
==========================

Extension library to display interactive maps with [LeafletJs](https://leafletjs.com/)

Installation
------------

The preferred way to install this extension is through
[composer](https://getcomposer.org/download/).  This requires the
[`composer-asset-plugin`](https://github.com/francoispluchino/composer-asset-plugin),
which is also a dependency for yii2 – so if you have yii2 installed, you are
most likely already set.

Either run

```sh
composer require boriskorobkov/yii2-leaflet-extension:^2.0.0
```

or add

```json
"boriskorobkov/yii2-leaflet-extension" : "^2.0.0"
```

to the require section of your application's `composer.json` file.

Migration from 1.x to 2.x
-------------------------

1. Replace namespace `dosamigos/yii2-leaflet-extension` with `boriskorobkov/yii2-leaflet-extension` in your code
2. If you use a custom widget, check the correct usage of the `;` symbol

Usage
-----

One of the things to take into account when working with [LeafletJs](https://leafletjs.com/) is that we need a Tile
Provider. Is very important, if we fail to provide a Tile Provider Url, the map will display plain, without any maps at
all.

The following example, is making use of [MapQuest](https://developer.mapquest.com/):

```php
// first lets setup the center of our map
$center = new boriskorobkov\leaflet\types\LatLng(['lat' => 51.508, 'lng' => -0.11]);

// now lets create a marker that we are going to place on our map
$marker = new \boriskorobkov\leaflet\layers\Marker(['latLng' => $center, 'popupContent' => 'Hi!']);

// The Tile Layer (very important)
$tileLayer = new \boriskorobkov\leaflet\layers\TileLayer([
   'urlTemplate' => 'https://otile{s}.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.jpeg',
    'clientOptions' => [
        'attribution' => 'Tiles Courtesy of <a href="https://www.mapquest.com/" target="_blank">MapQuest</a> ' .
        '<img src="https://developer.mapquest.com/content/osm/mq_logo.png">, ' .
        'Map data &copy; <a href="https://openstreetmap.org">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
        'subdomains' => ['1', '2', '3', '4'],
    ],
]);

// now our component and we are going to configure it
$leaflet = new \boriskorobkov\leaflet\LeafLet([
    'center' => $center, // set the center
]);
// Different layers can be added to our map using the `addLayer` function.
$leaflet->addLayer($marker)      // add the marker
        ->addLayer($tileLayer);  // add the tile layer

// finally render the widget
echo \boriskorobkov\leaflet\widgets\Map::widget(['leafLet' => $leaflet]);

// we could also do
// echo $leaflet->widget();
```

Testing
-------

To test the extension, is better to clone this repository on your computer. After, go to the extensions folder and do
the following (assuming you have `composer` installed on your computer): 

```sh 
$ composer install --no-interaction --prefer-source --dev
```
Once all required libraries are installed then do: 

```sh 
$ vendor/bin/phpunit
```

Further Information
-------------------

For further information regarding the multiple settings of LeafLetJS library please visit
[its API reference](https://leafletjs.com/reference.html)

Contributing
------------

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

Credits
-------

- [Antonio Ramirez](https://github.com/tonydspaniard)
- [Boris Korobkov](https://github.com/BorisKorobkov)
- [All Contributors](../../contributors)

License
-------

The BSD License (BSD). Please see [License File](LICENSE.md) for more information. 

> [![2amigOS!](https://www.gravatar.com/avatar/55363394d72945ff7ed312556ec041e0.png)](https://www.2amigos.us)  
<i>Web development has never been so fun!</i>  
[www.2amigos.us](https://www.2amigos.us)

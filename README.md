Cuisine - WordPress Utilitybelt
===========================

Cuisine is a WordPress utilitybelt / framework aimed at making WordPress development easy, quick and fun.

---

## Requirements

| Prerequisite    | How to check | How to install
| --------------- | ------------ | ------------- |
| PHP >= 5.4.x    | `php -v`     | [php.net](http://php.net/manual/en/install.php) |



## Features

* Completely OOP
* Field- Metaboxbuilders
* Asset pipeline for Sass files and Scripts
* Quickly add new Post Types, Taxonomies and Routing
* Cleans up the eventual HTML output 
* Some handy shortcodes ( buttons, google analytics event links, etc. )


## Installing

Clone the git repo - `git clone https://github.com/chefduweb/cuisine.git` or install with composer:

`composer require chefduweb/cuisine`

After you have all the files you need to install cuisine like a regular WordPress plugin:

1. move the files to wp-content/plugins
2. get into the WordPress admin and go to plugins
3. activate Cuisine.

## Getting Started

Creating plugins and themes with the power of Cuisine is quite easy; you just create regular WordPress themes & plugins. However, if you want to start off with some boilerplate code we recommand [Carte Blanche] (https://github.com/chefduweb/carte-blanche), our WordPress empty-canvas theme and [Crouton](https://github.com/chefduweb/crouton), a scaffolded plugin.

Here are some examples:

### Post Types & Taxonomies

```php

/**
 * register a custom post type:
 * @param string  post_type
 * @param string  plural label
 * @param string  singular label
 * @set-param array   regular post_type arguments (optional)
 */
PostType::make( 'project', 'Projects', 'Project' )->set( $params ); 

//register a custom taxonomy
//@make params: slug, post_type, plural, singular
/**
 * register a custom taxonomy
 * @param string  slug
 * @param string  post_type
 * @param string  Plural label
 * @param string  Singular label
 * @set-param array   regular taxonomy arguments (optional)
 */
Taxonomy::make( 'client', 'project', 'Clients', 'Client' )->set($params);


```

### Routing

```php

/**
 * register a custom rewrite for the post_type 'project'
 * @param  string post_type
 * @param  string overview-url
 * @param  string singular-url
*/
Route::url( 'project', 'our-work', 'project' );

//route a post_type to a custom template
/**
 * register a custom template redirect for the post_type 'project'
 * @param string post_type
 * @param string overview-template filename
 * @param string single-template filename (optional, defaults to {$post_type}-single.php );
 */
Route::template( 'project', 'our-work', 'project' );


```

### Metaboxes & Fields

```php


//fields in an array, for use in the metabox
$fields = array(

    //creating a simple text-field:
    Field::text( 'field_name', 'Field Label' ),
    
    //adding more variables:
    Field::text( 
        'field_name_2',
        'Field Label 2',
        array(
            'defaultValue' =>  'This is a Textfield',
            'validate'     =>  array( 'required', 'textual' ),
            'placeholder'  =>  'Put yer text here'
        )
    ),
    
    //Media gallery
    Field::media( 
           'media',
           'Image', 
           array(
                'label'  => 'top'
           )
    )
);

/**
 * Create a metabox on the fly
 * @param string Metabox-name
 * @param mixed post_type (string or array of strings)
 */
Metabox::make( 'A Metabox', 'post' )->set( $fields );

```

Building a metabox like this gives you the following result:
![Our custom Metabox with fields](http://www.chefduweb.nl/wp-content/uploads/2015/06/metabox1.png)

This metabox will automatically validate and save the fields in it. So you can get the results of the first text-field by using `get_post_meta( get_the_ID(), 'field_name', true );`.


### Adding assets (sass & scripts)

```php

//get the url of this plugin:
$url = Url::plugin( 'crouton/Assets/' );

/**
 * Register a script
 * @param string ID
 * @param string Url of the file ( file extension optional )
 * @param bool Auto-load this script from the get-go
 */
Script::register( 'crouton-script', $url.'Frontend', false );


/**
 * Register a sass-file
 * @param string ID
 * @param string Url of the file ( file extension optional )
 * @param bool Force-overwrite this sass-file 
 */
Sass::register( 'template', $url.'_template', false );

```

###Further Documentation
Our full documentation is a work in progress, [but can be found here](http://docs.chefduweb.nl)


## Contributing

Everyone is welcome to help [contribute](CONTRIBUTING.md) and improve this project. There are several ways you can contribute:

* Reporting issues (please read [issue guidelines](https://github.com/necolas/issue-guidelines))
* Suggesting new features
* Writing or refactoring code
* Fixing [issues](https://github.com/chefduweb/cuisine/issues)



requirejs.config({
    baseUrl: Cuisine.baseUrl,
    paths: Cuisine.scripts
});

//autoload everthing
requirejs( Cuisine.load );
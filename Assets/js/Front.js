
//base configuration:
requirejs.config({

    baseUrl: Cuisine.baseUrl,
    paths: Cuisine.scripts

});


//remove caching, if cacheBust is set to true:
if( Cuisine.cacheBust ){

	requirejs.config({
		urlArgs: "bust=" + ( new Date() ).getTime()
	});

}

//autoload everthing
requirejs( Cuisine.load );

//base configuration:
requirejs.config({

    baseUrl: Cuisine.baseUrl,
    paths: Cuisine.scripts

});


//add shims, if there are any:
if( Cuisine.shims.length > 0 ){
	
	requirejs.config({
		shim: Cuisine.shims
	});

}

//remove caching, if cacheBust is set to true:
if( Cuisine.cacheBust ){
	requirejs.config({
		urlArgs: "bust=" + ( new Date() ).getTime()
	});
}

//autoload everthing
requirejs( Cuisine.load );
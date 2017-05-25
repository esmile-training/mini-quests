$(function(){    
    var headerHeight = $('.headerPosition > .headerImg').height();
    var footerHeight = $('.footerPosition > .footerImg').height();
    
    var main = document.getElementById( "main" );
    var mainHeight =  $('#main').height();
    
    headerHeight = headerHeight-(headerHeight/7);
    footerHeight = footerHeight+(footerHeight/8);
    
     var bodyHeight = screen.height - (headerHeight + footerHeight);

    if(mainHeight < bodyHeight){
	mainHeight = bodyHeight;
    }

    main.style.marginTop = headerHeight+'px';
    main.style.height = mainHeight+'px';
    main.style.marginBottom = footerHeight+'px';
    

});

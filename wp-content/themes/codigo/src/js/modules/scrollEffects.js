
import consoleHello from './consoleHello';

const scrollEffects = () => {
    //consoleHello('Scroll effects');

    (function($) {

        let prevScrollpos   = window.pageYOffset;
        const displayWidth  = $(window).width();
        const displayHeight = $(window).height();


        //Hide menu on scroll
        $(window).on('scroll',function (event) {
            let vscroll = $(document).scrollTop();
            //let $navbarmenu = $("#main-nav");
            let $navbarmenu = $("body");
            //console.log(vscroll);

            let scrollLimit = 100;
            if( displayWidth > 767 ) {
                scrollLimit = displayHeight;
            }

            
            if (vscroll >= scrollLimit) {
                $navbarmenu.addClass("scrolled");
            } else {
                $navbarmenu.removeClass("scrolled");
            }
            

            let currentScrollPos = window.pageYOffset;
            if (vscroll >= 1) {
                    if (prevScrollpos > currentScrollPos) {
                    //document.getElementById("navbar").style.top = "0";
                    $navbarmenu.addClass("scroll-up");
                    $navbarmenu.removeClass("scroll-down");
                    } else {
                        $navbarmenu.removeClass("scroll-up");
                        $navbarmenu.addClass("scroll-down");
                    }
                    prevScrollpos = currentScrollPos;
            } else {
                $navbarmenu.removeClass("scroll-up");
                $navbarmenu.removeClass("scroll-down");
            }
        });


        //if there is an anchor on the URL
        if(window.location.hash) {
            window.scrollTo(0,0);
            scroll(0,0);
            //console.log('hash in url -> scroll to hash');
            $(window).on('load', function() {
                // smooth scroll to the anchor id
                setTimeout( function() {
                    console.log('scrolling');
                    $('html, body').animate({
                        scrollTop: $(window.location.hash).offset().top  
                    }, 1000, 'swing');
                }, 1000);
            });
        }

        const queryString = window.location.search;
        //console.log(queryString);
        const urlParams = new URLSearchParams(queryString);
        const scrollto = urlParams.get('scrollto')
        //console.log(scrollto);
        if(scrollto === 'careers') {
            //We click three times becasue we need to wait until team list is fully loaded
            setTimeout( function() {
                console.log('scrolling to '+ scrollto);
                $('#leftMenu li a[href="#careers"]').trigger("click");
            }, 500);
            setTimeout( function() {
                console.log('scrolling to '+ scrollto);
                $('#leftMenu li a[href="#careers"]').trigger("click");
            }, 1500);
            setTimeout( function() {
                console.log('scrolling to '+ scrollto);
                $('#leftMenu li a[href="#careers"]').trigger("click");
            }, 2500);
        }


    })( jQuery );    		
}

export default scrollEffects;
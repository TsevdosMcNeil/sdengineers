
import consoleHello from './consoleHello';

const menuEffects = () => {
    //consoleHello('Menu effect loaded');
    (function($) {

       function changeMenuBg(bgcolor) {
            $('#mainMenuWrapper').removeClass(function (index, css) {
                return (css.match (/\bbg\S+/g) || []).join(' '); // removes anything that starts with "bg"
            }).addClass(
                "bg-"+bgcolor
            );
        }
        function removeBgClass() {
            $('#mainMenuWrapper').removeClass(function (index, css) {
                return (css.match (/\bbg\S+/g) || []).join(' '); // removes anything that starts with "bg"
            });
        }
        function changeBodyClass(itemkey) {
            $('body').removeClass(function (index, css) {
                return (css.match (/\bselected\-left\-menu\-\S+/g) || []).join(' '); // removes anything that starts with "selected-left-menu-"
            }).addClass(
                "selected-left-menu-"+itemkey
            );
        }
        function removeBodyClass() {
            $('body').removeClass(function (index, css) {
                return (css.match (/\bselected\-left\-menu\-\S+/g) || []).join(' '); // removes anything that starts with "selected-left-menu-"
            });
        }


        let currentbgcolor = 'purple';
        if($('#mainMenuWrapper #main-menu li.active').length) {
            currentbgcolor = $('#mainMenuWrapper #main-menu li.active .nav-link').data("color")
            //console.log('changing color to '+currentbgcolor)
            changeMenuBg(currentbgcolor) 
        }

        $('#mainMenuWrapper .nav-link').on("mouseover", function () {
            changeMenuBg($(this).data("color")) 
        });

        $('#mainMenuWrapper .nav-link').on("mouseout", function () {
            if(currentbgcolor) {
                changeMenuBg(currentbgcolor) 
            } else {
                removeBgClass();
            }
        });


        $('.sidebar-block-back').on("mouseover", function () {
            $('body').addClass("hide-block-back-background");
        });

        $('.sidebar-block-back').on("mouseout", function () {
            $('body').removeClass("hide-block-back-background");
        });

        


        //Left menu (Expertise and Practise pages)
        $('#leftMenu li a').on("mouseover", function () {
            changeBodyClass($(this).data("menu")) 
        });
        $('#leftMenu li a').on("mouseout", function () {
            removeBodyClass() 
        });
        $('#leftMenu li a').on('click', function (event) {
            event.preventDefault();
            
            if(typeof $($.attr(this, 'href')).offset() !== 'undefined' ) {
                //scroll(0,0);
                let cust_off = 0;
                if (typeof $(this).data('offset') !== 'undefined') {
                    cust_off = $(this).data('offset');
                }
                console.log('scroll to ' + $.attr(this, 'href'));
                console.log('scrolling ' + $($.attr(this, 'href')).offset().top );
                $('html, body').animate({
                    scrollTop: $($.attr(this, 'href')).offset().top - cust_off + 'px'
                }, 1000, 'swing');
            }
        });

        

    })( jQuery );
}

export default menuEffects;
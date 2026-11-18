
import consoleHello from './consoleHello';

const burgerEffects = () => {
    //consoleHello('Burger menu effect');
    (function($) {
        //Burger menu effect
        $('.navbar-toggler.burger, .navbar-toggler.togglemenu').on("click", function () {
            //remove display:none menu the first time burger is clicked
            $(document.body).removeClass("hide-menu");
            $(document.body).removeClass("show-search-menu");
            $(document.body).removeClass("scrolled");
            $(document.body).removeClass("scroll-up");

            $(document.body).toggleClass("show-menu");
            $(this).attr('aria-hidden', function (i, attr) {
                return attr == 'true' ? 'false' : 'true'
            });
        });

        //Contact section
        $('.navbar-toggler.contact').on("click", function () {
            $(document.body).toggleClass("show-contact-menu");
            $(this).attr('aria-hidden', function (i, attr) {
                return attr == 'true' ? 'false' : 'true'
            });
        });
        $('.nav-link[data-contact]').on("click", function () {
            $(document.body).removeClass("hide-contact-menu");

            $(document.body).toggleClass("show-contact-menu");
            $(this).attr('aria-hidden', function (i, attr) {
                return attr == 'true' ? 'false' : 'true'
            });
        });

        //Search section
        // $('.navbar-toggler.search').on("click", function () {
        //     consoleHello('show/hide search nav');
        //     $(document.body).toggleClass("show-search-menu");
        //     $(this).attr('aria-hidden', function (i, attr) {
        //         return attr == 'true' ? 'false' : 'true'
        //     });
        // });
        $('.navbar-toggler.closesearch').on("click", function () {
            consoleHello('hide search nav, show menu');
            $(document.body).removeClass("show-search-menu");
            $(document.body).removeClass("show-menu");
            $(this).attr('aria-hidden', function (i, attr) {
                return attr == 'true' ? 'false' : 'true'
            });
        });
        $('.navbar-button.btn-search').on("click", function () {
            consoleHello('Search clicked');
            $(document.body).removeClass("hide-search-menu");

            $(document.body).toggleClass("show-search-menu");
            $(this).attr('aria-hidden', function (i, attr) {
                return attr == 'true' ? 'false' : 'true'
            });
        });
    })( jQuery );
}

export default burgerEffects;
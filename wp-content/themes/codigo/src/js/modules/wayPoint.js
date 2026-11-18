
import consoleHello from './consoleHello';

//https://stackoverflow.com/questions/43282985/waypoint-npm-error-cant-resolve-waypoint
//waypoints comes with various flavours, after installing waypoints (npm install waypoints)  if you look into /waypoints/lib/ folder you will see zepto|jquery|noframework.waypoints.js , you need to select which one suits you better.
import 'waypoints/lib/jquery.waypoints.min';


const scrollWayPoint = () => {
    //consoleHello('scrollWayPoint');
    (function($) {
        /*
        //default waypoint flavour
        var waypoint = new Waypoint({
            element: document.getElementById('waypoint_anchor'),
            handler: function() {
                console.log('Basic waypoint triggered')
            }
        });
        */

        //Toggle class visible in all .sidebar-block when they appear in viewport
        $('body').find('.sidebar-block-wrapper').each(function(){

          let wrapper = $(this);
          let sidebar = wrapper.find('.sidebar-block');

          //Fade in sections
          wrapper.waypoint(function(direction) {
            if (direction === 'down') {
              sidebar.addClass("mobile_fixed");
            }

          }, {
              offset: '90%'
          });
          wrapper.waypoint(function(direction) {
            if (direction === 'down') {
              sidebar.removeClass("mobile_fixed");
            }

          }, {
              offset: '-10%'
          });

          wrapper.waypoint(function(direction) {
            if (direction === 'up') {
              sidebar.removeClass("mobile_fixed");
            }

          }, {
              offset: '90%'
          });
          wrapper.waypoint(function(direction) {
            if (direction === 'up') {
              sidebar.addClass("mobile_fixed");
            }

          }, {
              offset: '-10%'
          });

        });


        //jquery waypoint flavour
        $('body').find('.entry-content > *:not(.nowaypoint) , #phpPosts > .col, footer.entry-footer > *').each(function(){

            let $section = $(this);

            //Fade in sections
            $section.waypoint(function(direction) {
                if (direction === 'down') {
                    $section.addClass("scroll-visible");
                }

            }, {
                offset: '90%'
            });
        });

        $('#wrapper-footer').waypoint(function(direction) {
            if (direction === 'down') {
                $('body').addClass("force-hide-block-back-background");
            }

        }, {
            offset: '95%'
        });

        $('#wrapper-footer').waypoint(function(direction) {
            if (direction === 'up') {
                $('body').removeClass("force-hide-block-back-background");
            }

        }, {
            offset: '95%'
        });

        //Mobile -> Fixed top menu in home animation
        if($('body').hasClass('home') ) {
            $('body').addClass("no-mobile-scrolled");
            $('.entry-content').waypoint(function(direction) {
                if (direction === 'down') {
                    //console.log( '.entry-content appeared' )
                    $('body').removeClass("no-mobile-scrolled");
                }

            }, {
                offset: '15%'
            });

            $('.entry-content').waypoint(function(direction) {
                if (direction === 'up') {
                    //console.log( '.entry-content disappeared' )
                    $('body').addClass("no-mobile-scrolled");
                }

            }, {
                offset: '15%'
            });
        }

    })( jQuery );

}
export default scrollWayPoint;

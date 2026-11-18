
import consoleHello from './consoleHello';
import getCookie from './getCookie';

const splash = () => {
    //consoleHello('Splash');
    (function($) {
        /**
         * 
         * @param {object} $splash 
         * @param {number} splashDuration 
         */
        function launchSplash(
            $splash, 
            splashDuration
        ) {
            //consoleHello('splashDuration ' + splashDuration)
            
            $splash.removeClass('hide');

            setTimeout(
                function() {
                    $(document.body).find('#page').css('opacity','1');
                }, 
                splashDuration
            );
            setTimeout(
                function() {
                    $splash.addClass('fadeOut')
                }, 
                (splashDuration + 1000)
            );
            setTimeout(
                function() {
                    $splash.addClass('hide');
                }, 
                (splashDuration + 2000)
            );	
            
        }

        if ($(document.body).find('.splash-page').length) {

            const $splash = $(document.body).find('.splash-page');
            const splashDuration = $splash.data('duration')??10000;

            var splashCookie = getCookie('splashshowed');
            //var splashCookie = null;

            var createCookie = false;
            
            if( splashCookie === null ) {
                createCookie = true;
            } 

            if( createCookie ) {
                var now = new Date();
                var created = now.toUTCString();
                var time = now.getTime();
                var expireTime = time + 1000*3600*24*30; //One month
                now.setTime(expireTime);
                document.cookie = 'splashshowed='+created+';expires='+now.toUTCString()+';path=/;SameSite=Strict';
                //console.log(document.cookie);  // 'Wed, 21 Apr 2021 17:42:22 GMT'

                //console.log('show splash '+modalID);
                launchSplash($splash, splashDuration);

            } else {
                //console.log('No splash'); 
                $(document.body).find('#page').css('opacity','1');
                $splash.addClass('hide');
            }                    
            
        } 
    })( jQuery );
}

export default splash;
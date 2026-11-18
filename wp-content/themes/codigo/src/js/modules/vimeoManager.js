
import consoleHello from './consoleHello';

//https://github.com/vimeo/player.js
//You can install the Vimeo Player API through  npm: npm install @vimeo/player
import Player from '@vimeo/player';


const vimeoManager = () => {
    //consoleHello('vimeoManager');
    (function($) {
        if ($(".wp-block-embed-vimeo iframe").length) {
        
            //const iframe = document.querySelector('iframe');
            const iframe = $(".wp-block-embed-vimeo iframe");
            const player = new Player(iframe);

            player.getVideoTitle().then(function(title) {
                //console.log('title:', title);
            }).catch(function(error) {
                console.log(error);
            });

            /*
            player.getLoop().then(function(loop) {
                // loop = whether loop is turned on or not
                //console.log('loop: '+ loop );

                // if(loop === true) {
                //     console.log('setting loop to true via js' );

                //     player.setLoop(true).then(function(sloop) {
                //         console.log('loop was turned on: ' +sloop);
                //     }).catch(function(error) {
                //         console.log(error);
                //     });
                // }
            }).catch(function(error) {
                console.log(error);
            });
            */

            // player.on('play', function(data) {
            //     console.log('played the video!');
            //     console.log(data);
            // });

            // player.on('timeupdate', function(data) {
            //     console.log('timeupdate');
            //     console.log(data);
            // });

            // player.on('ended', function(data) {
            //     console.log('ended');
            //     console.log(data);
            // });
            // player.on('pause', function(data) {
            //     console.log('pause');
            //     console.log(data);
            // });

            player.on('error', function(data) {
                console.log('error');
                console.log(data);
            });

            const playbutton = $(".wp-block-embed-vimeo button.vimeo-control.play");
            const muted = $(".wp-block-embed-vimeo button.vimeo-control.muted");
            const unmuted = $(".wp-block-embed-vimeo button.vimeo-control.unmuted");

            playbutton.on('click', function() {
                // Note: on iOS and some other mobile devices, you cannot programmatically trigger play
                player.play().then(function() {
                    // the video was played
                }).catch(function(error) {
                    console.log(error);
                });
            });

            muted.on('click', function() {
                $(this).addClass('hide');
                unmuted.removeClass('hide');
                player.setMuted(true).then(function(muted) {
                    // muted was turned on
                    //console.log('muted clicked!');
                }).catch(function(error) {
                    console.log(error);
                });
            });

            unmuted.on('click', function() {
                $(this).addClass('hide');
                muted.removeClass('hide');
                player.setMuted(false).then(function(muted) {
                    // muted was turned off
                    //console.log('unmuted clicked!');
                }).catch(function(error) {
                    console.log(error);
                });
            });
            
        }
        
    })( jQuery );

}
export default vimeoManager;

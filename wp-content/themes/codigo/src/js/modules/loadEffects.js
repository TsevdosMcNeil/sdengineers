
import consoleHello from './consoleHello';

/**
 * Adds the classname "loaded" to the <body> 
 * in order to trigger some CSS rules associated to this classname
 */
const loadEffects = () => {
    (function($) {
        //consoleHello('Window loaded');
        $('body').addClass("loaded");
    })( jQuery );
}

export default loadEffects;

import consoleHello from './consoleHello';

const cssElements = () => {
    //consoleHello('cssElements loaded');

    const setDocHeight = () => {
        document.documentElement.style.setProperty('--vh', `${window.innerHeight/100}px`);
        document.documentElement.style.setProperty('--vw', `${window.innerWidth/100}px`);
        document.documentElement.style.setProperty('--scrollbar-width', `${window.innerWidth - document.documentElement.clientWidth}px`);
    };
    window.addEventListener('resize', setDocHeight());
    window.addEventListener('orientationchange', setDocHeight());
    (function($) {
        $(window).on('resize',function() {
            setDocHeight()
        });
    })( jQuery );

    setDocHeight()
    

}

export default cssElements;
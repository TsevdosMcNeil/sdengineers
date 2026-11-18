
import consoleHello from './consoleHello';
import changeContent from './changeContent';

const modalEffects = () => {
    //consoleHello('Modal effects');
    (function($) {

        $('[data-inactivate-carousel]').on("click", function () {
            
            let carousel_id = $(this).data('inactivate-carousel');
            //consoleHello('inactivate ' + carousel_id);
            $('#' + carousel_id).find('.carousel-item').removeClass('active');
        });

    })( jQuery );

    const myModalEl = document.getElementById('modalMember')
    if( myModalEl 
        && typeof myModalEl !== 'undefined'
    ) {
        myModalEl.addEventListener('show.bs.modal', event => {
            //consoleHello('Modal opened');
            changeContent();
        })
    }
}

export default modalEffects;

/*
* Modify DOM and CSS elements
*/

import consoleHello from './consoleHello';

const changeContent = () => {
    //consoleHello('change Content');
    (function($) {

        if($('.move-out-mobile').length) {
            $('.move-out-mobile').each(function(){
                const classString = $(this).attr('class');

                const targetID = classString
                    .match(/(\bmoveto\S+\b)/ig)
                    .toString()
                    .replace(
                        'moveto-', 
                        ''
                    )
                //consoleHello('target: '+ targetID);

                const posNumber = parseInt(
                    classString
                    .match(/(\bmovepos\S+\b)/ig)
                    .toString()
                    .replace(
                        'movepos-', 
                        ''
                    )
                ) - 1;
                //consoleHello('position: '+ posNumber);
                const $newEle  = $(this).clone().addClass('d-md-none')
                $(this).addClass('d-none d-md-block')

                let $parentEle = $('#'+targetID);
                if($(this).closest('.carousel-item').length) {
                    $parentEle = $(this).closest('.carousel-item').find('#'+targetID);
                } 

                //consoleHello('$parentEle: ');
                //consoleHello($parentEle.attr('id'));

                if(posNumber < 0) {
                    $parentEle.prepend($newEle) 
                } else {
                    const $targetEle = $parentEle.find(' > *:nth("'+posNumber+'")');
                    //consoleHello('$targetEle: ');
                    //consoleHello($targetEle.attr('id'));

                    //$newEle.insertAfter('#'+targetID+' > *:nth("'+posNumber+'")');
                    $newEle.insertAfter($targetEle );
                }
                
            });
        }
        

    })( jQuery );
}

export default changeContent;
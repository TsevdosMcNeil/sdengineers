<?php

/**
 * Home Lottie Video
 *
 * @package Codigo
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;


$homevideo_page   = get_field('homepage_video_page');
$alternative_page = get_field('homepage_alternative_page');

$lottieJSON    = get_field('homevideo_json_file_url', $homevideo_page->ID);
$framesNumber  = get_field('homevideo_frames_number', $homevideo_page->ID);
$mobileMessage = get_field('homevideo_mobile_message', $homevideo_page->ID);

$lottieJSONLogo   = get_field('homevideo_logo_json_file_url', $homevideo_page->ID);
$framesNumberLogo = get_field('homevideo_logo_frames_number', $homevideo_page->ID);

$guideHeight = 10;
$lottieFrames = "[0, 1]";
if($framesNumber > 1) {
  $guideHeight = $framesNumber - 100;
  if ($framesNumber <= 200) {
    $guideHeight = 10; //100
  }
  $lottieFrames = "[0, '" . ($framesNumber - 1) . "']";
}

$guideHeightLogo = 200;
$lottieFramesLogo = "[0, 1]";
if($framesNumberLogo > 1) {
  $lottieFramesLogo = "[0, '" . ($framesNumberLogo - 1) . "']";
  $guideHeightLogo = $framesNumberLogo - 100;
  if ($framesNumberLogo <= 200) {
    $guideHeightLogo = 200;
  }
}

//Bottom arrow
ob_start(); ?>
  <div id="arrowHome"
    class="move-down">
    <a id="arrowLink"
      href="#expertise"></a>
    <?php if ($mobileMessage): ?>
      <div class="small d-md-none"><?php echo $mobileMessage; ?></div>
    <?php endif; ?>
  </div><?php
  $arrowHTML = ob_get_contents();
ob_end_clean();




      ?>
<!-- codigo/global-templates/home-lottie.php -->

<?php if ($lottieJSONLogo): ?>
<div id="wrapperAnimationLogo" style="display:none">
    <lottie-player
      id="logoLottie"
      src="<?= $lottieJSONLogo ?>"
    ></lottie-player>
    <div id="lottieGuideLogo"
      style="height: calc(var(--vh, 1vh) * 100);
        margin-top: 100vh;"
    ></div>
</div>
<?php endif; ?>

<div id="wrapperAnimation">

  <div id="loadingPage"></div>

  <div id="wrapperLottie"
    class="wrapper-lottie"
    style="display:none">
    <div class="content-lottie sticky-top">
      <?php echo apply_filters(
        'the_content',
        $homevideo_page->post_content
      ); ?>
      <div class="wrapper-lottie_player">
        <lottie-player
          id="firstLottie"
          src="<?= $lottieJSON ?>"
        ></lottie-player>
      </div>

      <?= $arrowHTML ?>
    </div>

    <div id="lottieGuide"></div>
  </div>

  <div id="wrapperAlternative"
    class="wrapper-alternative"
    style="display:none">
    <?php /*
        echo apply_filters(
                'the_content',
                $alternative_page->post_content
        ); */ ?>

    <?= $arrowHTML ?>
  </div>

</div>

<div id="lottieBottom"
  style="display:none"></div>

<script>
  let frameoffset = 30;
  let transdist = '10rem';
  const groups = [];

  let wait_miliseconds = 8000; // Maximum waiting time to load Lottie JSON
  let alternative_miliseconds = 500; // Forced waiting time to load the alternative video

  let lenis;
  let lottie_allowed = true;
  let is_ready_lottie = false;
  let is_alternative_loaded = false;


  let wrapperLottie        = document.getElementById("wrapperLottie");
  let wrapperAnimationLogo = document.getElementById("wrapperAnimationLogo");
  let lottieBottom         = document.getElementById("lottieBottom");
  let lottieGuide          = document.getElementById("lottieGuide");
  let lottieGuideLogo      = document.getElementById("lottieGuideLogo");
  let wrapper_alternative  = document.getElementById("wrapperAlternative");
  let loading_page         = document.getElementById("loadingPage");

  const isFirefox = function() {
    return /firefox/i.test(navigator.userAgent);
  }

  if (isFirefox() ) {
    lottie_allowed = false;
  }
  // if (window.innerWidth < 768) {
  //   lottie_allowed = false;
  // }
  <?php if(defined('DEV_VERSION')):  ?>
  lottie_allowed = true;
  <?php endif ?>

  // If there is a splash page, we wait until it finishes
  const decodedCookie = decodeURIComponent(document.cookie);
  if (!decodedCookie.includes('splashshowed')) {
    let splash_pages = document.getElementsByClassName('splash-page');
    if (typeof splash_pages[0] !== 'undefined') {
      let splash_page = splash_pages[0];
      alternative_miliseconds = parseInt(splash_page.dataset.duration);
      wait_miliseconds = wait_miliseconds + parseInt(splash_page.dataset.duration);
    }
  }

  /**
   *  Checks if Lottie JSON has been loaded
   *
   * @return void
   */
  function lottieReady() {
    if (lottie_allowed) {
      //wrapperLottie.classList.add("animation-ready")
      loading_page.classList.add("ready");
      is_ready_lottie = true;

      // Removes the lottie error div (no working)
      var diverror = document.querySelector("#firstLottie div.error");
      if (diverror) {
        diverror.style.display = 'none';
      }
    }
  }

  /**
   *  Triggers Lottie and Lenis
   *
   * @return void
   */
  function loadLottie() {

    if(!wrapperLottie || !lottieBottom || !wrapper_alternative || !loading_page ) {
      return;
    }

    wrapperLottie.style.display = 'block';
    lottieBottom.style.display = 'block';

    if(wrapperAnimationLogo ) {
      wrapperAnimationLogo.style.display = 'block';
    }

    let firstLottie, logoLottie;

    if(lottieGuide) {
      lottieGuide.style.height = 'calc(var(--vh, 1vh) * <?= $guideHeight ?>)';
      lottieGuide.style.marginTop = '100vh';
      firstLottie = document.getElementById('firstLottie');
    }

    if(lottieGuideLogo) {
      lottieGuideLogo.style.height = 'calc(var(--vh, 1vh) * <?= $guideHeightLogo ?>)';
      lottieGuideLogo.style.marginTop = '100vh';
      logoLottie = document.getElementById('logoLottie');
    }


    function initialiseLottieInteraction(player, container, frames) {
      if (!player || !container) {
        console.error('Lottie player or guide container is missing', {
          player,
          container
        });

        return;
      }

      const createInteraction = () => {
        LottieInteractivity.create({
          mode: 'scroll',
          player,
          container,
          actions: [
            {
              visibility: [0, 1],
              type: 'seek',
              frames,
            },
          ],
        });
      };

      /*
      * getLottie() is available once the animation has loaded.
      * This also handles cached animations that loaded before this code ran.
      */
      if (typeof player.getLottie === 'function' && player.getLottie()) {
        createInteraction();
      } else {
        player.addEventListener('load', createInteraction, {
          once: true
        });
      }
    }

    if(firstLottie ) {
      //console.log('firstLottie');
      initialiseLottieInteraction(
        firstLottie,
        lottieGuide,
        <?= $lottieFrames ?>
      );
      firstLottie.addEventListener('load', lottieReady, {
        once: true
      });
    }

    if(logoLottie ) {
      //console.log('logoLottie');
      initialiseLottieInteraction(
        logoLottie,
        lottieGuide,
        <?= $lottieFramesLogo ?>
      );

      logoLottie.addEventListener('load', lottieReady, {
        once: true
      });
    }

    //Lenis
    lenis = new Lenis({
      duration: 1.1,
      //duration: 3,
      easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
      //easing: (t) => (1 - Math.pow(1 - t, 5)),
      direction: "vertical",
      gestureDirection: "vertical",
      smooth: true,
      mouseMultiplier: 1,
      smoothTouch: false,
      touchMultiplier: 1,
      infinite: false
    });

    function raf(time) {
      lenis.raf(time);
      requestAnimationFrame(raf);
    }

    requestAnimationFrame(raf);

    (function($) {

      $('.lenis-group').each(function() {
        let lenisobj = {}
        lenisobj.startframe = $(this).find('.leniscnf').data('startframe')
        lenisobj.starteffect = $(this).find('.leniscnf').data('starteffect')
        lenisobj.endframe = $(this).find('.leniscnf').data('endframe')
        lenisobj.endeffect = $(this).find('.leniscnf').data('endeffect')
        lenisobj.element = $(this).find('.wp-block-column > *:not(.wp-block-spacer)')[0]

        lenisobj.element.style.zIndex = 9;
        if (lenisobj.starteffect.some(efct => efct === 'fade')) {
          //The start effect is fade, so the default opacity must be 0
          lenisobj.element.style.opacity = 0;
        }
        if (lenisobj.starteffect.some(efct => efct === 'scale')) {
          //The start effect is scale, so the default scale must be 0
          lenisobj.element.style.transform = `scale(0)`;
        }
        if (lenisobj.starteffect.some(efct => efct === 'translationX')) {
          //The start effect is translationX, so the default X position must be -transdist
          lenisobj.element.style.transform = `translateX(-${transdist})`;
        }
        if (lenisobj.starteffect.some(efct => efct === 'translationY')) {
          //The start effect is translationX, so the default Y position must be -transdist
          lenisobj.element.style.transform = `translateY(${transdist})`;
        }

        groups.push(lenisobj);
      })

      $(window).on('scroll', function(event) {
        $('#arrowLottie a').addClass('noshake')
      });

    })(jQuery);


    lenis.on("scroll", ({
      scroll
    }) => {
      let frameNumber = (<?= $framesNumber ?> * scroll) / wrapperLottie.offsetHeight;

      groups.forEach(function(item, index, arr) {
        let increaseStart = [],
          decreaseStart = [],
          increaseEnd = [],
          decreaseEnd = [];


        if (frameNumber <= (item.startframe - frameoffset)) {
          item.element.style.zIndex = 9;
          if (item.starteffect.some(efct => efct === 'fade')) {
            item.element.style.opacity = 0;
          }
          if (item.starteffect.some(efct => efct === 'scale')) {
            item.element.style.transform = `scale(0)`;
          }
          if (item.starteffect.some(efct => efct === 'translationX')) {
            item.element.style.transform = `translateX(-${transdist})`;
          }
          if (item.starteffect.some(efct => efct === 'translationY')) {
            item.element.style.transform = `translateY(${transdist})`;
          }
        }


        if (frameNumber > (item.startframe - frameoffset)) {
          item.element.style.zIndex = 10;
          increaseStart[index] = (frameNumber - (item.startframe - frameoffset)) / frameoffset
          if (increaseStart[index] > 1) increaseStart[index] = 1;
          decreaseStart[index] = 1 - increaseStart[index];

          if (item.starteffect.some(efct => efct === 'fade')) {
            //This item starts with fade effect in frame item.startframe.
            item.element.style.opacity = increaseStart[index];
          }
          if (item.starteffect.some(efct => efct === 'scale')) {
            item.element.style.transform = `scale(${increaseStart[index]})`;
          }
          if (item.starteffect.some(efct => efct === 'translationX')) {
            item.element.style.transform = `translateX(calc(${decreaseStart[index]} * ${transdist}))`;
          }
          if (item.starteffect.some(efct => efct === 'translationY')) {
            item.element.style.transform = `translateY(calc(${decreaseStart[index]} * -${transdist}))`;
          }
        }

        if (frameNumber > (item.endframe - frameoffset)) {
          item.element.style.zIndex = 9;
          increaseEnd[index] = (frameNumber - (item.endframe - frameoffset)) / frameoffset
          if (increaseEnd[index] > 1) increaseEnd[index] = 1;
          decreaseEnd[index] = 1 - increaseEnd[index];

          if (item.endeffect.some(efct => efct === 'fade')) {
            item.element.style.opacity = decreaseEnd[index];
          }
          if (item.endeffect.some(efct => efct === 'scale')) {
            item.element.style.transform = `scale(${decreaseEnd[index]})`;
          }
          if (item.endeffect.some(efct => efct === 'translationX')) {
            item.element.style.transform = `translateX(calc(${increaseEnd[index]} * ${transdist}))`;
          }
          if (item.endeffect.some(efct => efct === 'translationY')) {
            item.element.style.transform = `translateY(calc(${increaseEnd[index]} * -${transdist}))`;
          }
        }
      })


    });
  }

  /**
   * Displays the alternative mp4 video version
   *
   * @return void
   */
  function loadAlternative() {
    if (!is_alternative_loaded) {
      
      wrapperLottie.style.display = 'none';
      lottieBottom.style.display = 'none';

      if(wrapperAnimationLogo ) {
        wrapperAnimationLogo.style.display = 'none';
      }

      wrapper_alternative.style.display = 'block';
      wrapper_alternative.style.height = 'calc(var(--vh, 1vh) * 100)';

      var getURL = '<?php echo esc_url_raw(rest_url('wp/v2/pages/')) . $alternative_page->ID; ?>';

      (function($) {
        let previous_HTML = $('#wrapperAlternative').html();
        $.get(getURL, function(r) {
          $('#wrapperAlternative').html(r.content.rendered + previous_HTML);
        }).done(function() {
          alternativeReady();
        }).fail(function(error) {
          console.log(error);
        });
      })(jQuery);

      window.dispatchEvent(new Event('resize')); //To start waypoint
    }

  }

  /**
   *  Checks if alternative  has been loaded
   *
   * @return void
   */
  function alternativeReady() {
    loading_page.classList.add("ready");
    is_alternative_loaded = true;
  }

  if (lottie_allowed) {
    loadLottie();
  }

  setTimeout(() => {
    if (!lottie_allowed) {
      loadAlternative()
    }
  }, alternative_miliseconds);

  setTimeout(() => {
    if (!is_ready_lottie) {
      loadAlternative();
    }
  }, wait_miliseconds);
</script>
<!-- End codigo/global-templates/home-lottie.php -->
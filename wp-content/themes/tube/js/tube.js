/**
 * Various functions for the .TUBE Theme
 */

!function ($) {   

  // prevent jump down page when hash in address 
  if (location.hash) {               // do the test straight away    
    window.scrollTo(0, 0);         // execute it straight away
    setTimeout(function() {
      window.scrollTo(0, 0);     // run it a bit later also for browser compatibility
    }, 1);
  };
  
  // scroll to hash function
  window.scrollToHash = function($target) {
    $target = $target.length ? $target : $('[name=' + this.hash.slice(1) + ']');
    if ($target.length) {
      var scrollTopPos = $target.offset().top;
      var scrollTopOffset = getNavbarsHeight();
      scrollTopOffset = scrollTopOffset - 1;
      $('html,body').animate({
          scrollTop: $target.offset().top - scrollTopOffset
      }, 750, function() {});
    }
  };

  // common function to get height of navbars     
  window.getNavbarsHeight = function() {
    var scrollTopOffset = 15;
    
    var $navbars = $('.navbar-fixed-top');

    if($navbars.length!==0){
        scrollTopOffset = scrollTopOffset + $navbars.outerHeight();
    }
    
    var $adminbar = $('#wpadminbar');

    if($adminbar.length!==0){
        scrollTopOffset = scrollTopOffset + $adminbar.outerHeight();
    }
    
    return scrollTopOffset;
  };

  $(window).on('load', function() {
    if (window.location.hash) {
      scrollToHash($(window.location.hash));
    }
  });  
    
   
    
    

  $(function(){         // document ready
  
    $('a.scroll-to-hash').on('click', function(event) {
      event.preventDefault();
      event.stopPropagation();
      if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname)     
      {
        var the_hash = $(this).attr("href");
        var $target = $(this.hash);
        scrollToHash($target);
      }
    });
    
    
    // Prevent modal trigger from getting "focus" on modal close
    // http://stackoverflow.com/a/28633342
    $('.modal').on('shown.bs.modal', function(e){        
        $("*[data-toggle='modal']").one('focus', function(e){$(this).blur();});        
    });

    
    /// Stop video when modal closes by refreshing iframe source
    //http://stackoverflow.com/questions/13799377/twitter-bootstrap-modal-stop-youtube-video
    $(".modal ").on('hidden.bs.modal', function (e) {
      $(this).find("iframe").attr("src", $(".modal iframe").attr("src"));
    });
    
    
    
    // search toggle on the masthead
    $('.btn-masthead-search-toggle').on('click', function(event) {
     
      event.preventDefault();
      event.stopPropagation();
      
      $that = $(this); 
     
      $('#searchbar').slideToggle( function(){
        $that.toggleClass('btn-warning btn-ghost').blur().find('i').toggleClass('fa-search fa-close');
      });
      
    });
    
    
    $('#nav-icon1,#nav-icon2,#nav-icon3,#nav-icon4').click(function(){
      $(this).toggleClass('open');
    });
        
    
    // add custom classes to the cancel reply link for threaded comments
    $('#cancel-comment-reply-link').addClass('btn btn-sm btn-default');

  });
}(window.jQuery);
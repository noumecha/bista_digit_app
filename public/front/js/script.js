$(function(){
  $('.menu-toggle').on('click', function(){
    $('.menu-toggle').toggleClass('active')
    $('.menu').toggleClass('active')
  });
});
$( () => {
  //On Scroll Functionality
  $(window).on('scroll', () => {
    var windowTop = $(window).scrollTop();
    windowTop > 50 ? $('header').addClass('og-hf') : $('header').removeClass('og-hf');
  });
});
/** counter section */
$('.counting').each(function() {
  var $this = $(this),
  countTo = $this.attr('data-count');
  $({ countNum: $this.text()}).animate(
    {
      countNum: countTo
    },{
      duration: 3000,
      easing:'linear',
      step: function() {
        $this.text(Math.floor(this.countNum));
      },
      complete: function() {
        $this.text(this.countNum);
      }
  });
});
/** owl carousel configuration */
$(function() {
  var owl = $('.owl-carousel');
  owl.owlCarousel({
    /*loop: false,
    margin: 10,
    navRewind: false,
    pagination: true,
    responsive: {
      0: {
        items: 1
      },
      440:{
        items: 2
      },
      600: {
        items: 3
      },
      1000: {
        items: 4
      }
    }*/
    slideSpeed : 200,
    paginationSpeed : 800,
    autoPlay : false,
    goToFirst : true,
    goToFirstSpeed : 1000,
    navigation : false,
    navigationText : ["prev", "next"],
    pagination : true,
    paginationNumbers: true,
    responsive: true,
    items : 5,
    itemsDesktop : [1199, 4],
    itemsDesktopSmall : [980, 3],
    itemsTablet : [768, 2],
    itemsMobile : [479, 1]
  })
})
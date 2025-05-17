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

$(document).ready(function() {
    // Start baffle on any element(s).
  var b = baffle('.someSelector').start();
  b.set({
    speed: 100,
    characters: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
  });
  b.reveal(3000);

  $('a[href^="#"]').bind('click.smoothscroll',function (e) {
    var destination = e.target.hash;
    $('html, body').animate({
      scrollTop: parseInt($(destination).offset().top)
    }, 2000);
  });

});

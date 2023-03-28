$(function(){

  // scrollbar js initialization
  $(".nano").nanoScroller();

  hH = $('.header').height();
  //alert(hH);


  // cloning menu items in mobile
  $mobileNav = $('<div id="mobileNav"></div>').prependTo('.offcanvas .nano .nano-content');
  $mobileNav.append($('.header .my-menu > ul').clone());
  $mobileNav.append($('.header .header-action > .header-search').clone());
  $mobileNav.append($('.header .header-action > .action-icon').clone());

  // offcanvas toggle
  $('.toggle a').click(function(e){
    $('.offcanvas').addClass('show-offcanvas');
    $('body').addClass('pushed');
    $('.body-inactive').fadeIn(350);
    e.preventDefault();
  });

  // closing ups clicking on the screen
  $('.body-inactive, .coff a').click(function(e){
    $('.offcanvas').removeClass('show-offcanvas');
    $('.body-inactive').fadeOut(150);
    e.preventDefault();
  });


})


$(document).ready(function(){
// Submenu DropDown
$('#mobileNav li.hasDropdown > a').click(function(e){
console.log("dasf")
  findSubMenuDisplay = $(this).parent().children('.dropdown').css('display');
  if(findSubMenuDisplay == 'block'){
    $(this).parent().children('.dropdown').slideUp(350);
  }
  else{
    $('.dropdown').slideUp(350);
    $(this).parent().children('.dropdown').slideDown(350);
  }
  e.preventDefault();
})
});

$('#detailSlider').carousel({
  interval: false,
});

$('.panel-collapse').on('show.bs.collapse', function () {
  $(this).siblings('.panel-heading').addClass('active');
});

$('.panel-collapse').on('hide.bs.collapse', function () {
  $(this).siblings('.panel-heading').removeClass('active');
});
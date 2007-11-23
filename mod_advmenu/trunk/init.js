window.addEvent('domready', function(){
 element = $('advmenu')
 if(!element.hasClass('disabled')) {
 var menu = new JMenu(element)
 document.menu = menu
 }
});

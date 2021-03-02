import $ from 'jquery';
import 'what-input';
// Foundation JS relies on a global variable. In ES6, all imports are hoisted
// to the top of the file so if we used `import` to import Foundation,
// it would execute earlier than we have assigned the global variable.
// This is why we have to use CommonJS require() here since it doesn't
// have the hoisting behavior.
window.jQuery = $;
require('foundation-sites');
// If you want to pick and choose which modules to include, comment out the above and uncomment
// the line below
//import './lib/foundation-explicit-pieces';
$(document).foundation();

$('[data-open-details]').click(function (e) {
  e.preventDefault();
  $(this).next().toggleClass('is-active');
  $(this).toggleClass('is-active');
});
$(function(){  
  $.ajax({
      type: 'GET',
      url: 'ajax.php',
      success: function(data){
          $('#unread_m').append(data);
      }
  });
  setInterval(function(){//setInterval() method execute on every interval until called clearInterval()
          $('#unread_m').load('ajax.php').fadeIn('slow');
          //load() method fetch data from fetch.php page
          }, 1000);    
  });
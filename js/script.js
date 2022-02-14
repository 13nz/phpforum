
$(document).ready(function() {
  $(".disappear").fadeOut(5000);

});




function swapStyleSheet(sheet) {
    document.getElementById("theme").setAttribute("href", sheet);
    if (typeof get_cookie("theme") !== 'undefined') {
      delete_cookie("theme");
    }
    set_cookie("theme", sheet, 7);
};



function set_cookie ( cookie_name, cookie_value, lifespan_in_days, valid_domain )
{
  var domain_string = valid_domain ? ("; domain=" + valid_domain) : '' ;
  document.cookie = cookie_name + "=" + encodeURIComponent( cookie_value ) +
      "; max-age=" + 60 * 60 * 24 * lifespan_in_days +
      "; path=/" + domain_string;
}


function get_cookie ( cookie_name )
{
  var cookie_string = document.cookie ;
  if (cookie_string.length != 0) {
    var cookie_array = cookie_string.split( '; ' );
    for (i = 0 ; i < cookie_array.length ; i++) {
      cookie_value = cookie_array[i].match ( cookie_name + '=(.*)' );
      if (cookie_value != null) {
        return decodeURIComponent ( cookie_value[1] ) ;
      }
    }
  }
  return '' ;
}


function delete_cookie ( cookie_name, valid_domain )
{
  // https://www.thesitewizard.com/javascripts/cookies.shtml
  var domain_string = valid_domain ? ("; domain=" + valid_domain) : '' ;
  document.cookie = cookie_name + "=; max-age=0; path=/" + domain_string ;
}



function setFromCookie() {
  let cookie = get_cookie("theme");
  if(cookie.length > 0) {
    swapStyleSheet(cookie);
  }
}


/*
function ellipse(str) {
   if (str.length > 20) {
      return str.substring(0, 20) + '...';
   }
   return str;
};
*/

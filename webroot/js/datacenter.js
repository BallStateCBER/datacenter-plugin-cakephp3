// jQuery equivalent of Prototype's down() function
(function($) {
  $.fn.down = function() {
    var el = this[0] && this[0].firstChild;
    while (el && el.nodeType != 1)
      el = el.nextSibling;
    return $(el);
  };
})(jQuery);

(function ($) {
  FWP.hooks.addAction('facetwp/refresh/yearly', function ($this, facet_name) {
    var val = $this.find('.facetwp-yearly').val();
    FWP.facets[facet_name] = val ? [val] : [];
  });

  FWP.hooks.addAction('facetwp/ready', function () {
    $(document).on('change', '.facetwp-facet .facetwp-yearly', function () {
      var $facet = $(this).closest('.facetwp-facet');
      if ('' != $facet.find(':selected').val()) {
        FWP.static_facet = $facet.attr('data-name');
      }
      FWP.autoload();
    });
  });
})(jQuery);
/**
 * "gallery" control in the Customizer (Bira_Gallery_Control): pick multiple images at once from the Media Library.
 * Setting value = comma-separated attachment IDs, in selection order.
 */
(function ($) {
  $(document).on('click', '[data-bira-gallery] .bira-gallery__select', function () {
    var root = $(this).closest('[data-bira-gallery]');
    var input = root.find('input[type="hidden"]');
    var list = root.find('.bira-gallery__list');
    var current = (input.val() || '').split(',').filter(Boolean).map(Number);

    var frame = wp.media({
      title: 'Select logos',
      button: { text: 'Use these logos' },
      library: { type: 'image' },
      multiple: 'add', // click to add / remove from the selection without holding Ctrl
    });

    // Mark already-selected logos when the media library opens.
    frame.on('open', function () {
      var selection = frame.state().get('selection');
      current.forEach(function (id) {
        var attachment = wp.media.attachment(id);
        attachment.fetch();
        selection.add(attachment);
      });
    });

    frame.on('select', function () {
      var items = frame.state().get('selection').toJSON();
      input.val(items.map(function (a) { return a.id; }).join(',')).trigger('change');
      list.empty();
      items.forEach(function (a) {
        var url = (a.sizes && a.sizes.thumbnail && a.sizes.thumbnail.url) || a.url;
        list.append($('<li>').append($('<img>', { src: url, alt: '' })));
      });
    });

    frame.open();
  });

  $(document).on('click', '[data-bira-gallery] .bira-gallery__clear', function () {
    var root = $(this).closest('[data-bira-gallery]');
    root.find('input[type="hidden"]').val('').trigger('change');
    root.find('.bira-gallery__list').empty();
  });
})(jQuery);

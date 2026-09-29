jQuery(function ($) {
  const list = $('#tavernenfest-program-list');
  if (!list.length) return;
  list.sortable({
    handle: '.dashicons-menu',
    placeholder: 'tavernenfest-sort-placeholder',
    update: function () {
      const order = list.children('li').map(function () { return $(this).data('id'); }).get();
      $('#tavernenfest-order-status').text('Speichere Reihenfolge ...');
      $.post(tavernenfestProgram.ajaxUrl, { action: 'tavernenfest_save_order', nonce: tavernenfestProgram.nonce, order: order })
        .done(function (response) { $('#tavernenfest-order-status').text(response.success ? 'Reihenfolge gespeichert.' : 'Speichern fehlgeschlagen.'); })
        .fail(function () { $('#tavernenfest-order-status').text('Speichern fehlgeschlagen.'); });
    }
  });
});

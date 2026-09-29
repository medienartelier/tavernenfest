jQuery(function ($) {
  $('.tavernenfest-select-image').on('click', function (event) {
    event.preventDefault();
    const field = $('#tavernenfest_image');
    const frame = wp.media({ title: 'Programmbild auswählen', button: { text: 'Bild verwenden' }, multiple: false, library: { type: 'image' } });
    frame.on('select', function () { field.val(frame.state().get('selection').first().toJSON().url); });
    frame.open();
  });
});

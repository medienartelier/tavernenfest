jQuery(function ($) {
  $('.tavernenfest-select-impressions-video').on('click', function (event) {
    event.preventDefault();
    const field = $('#tavernenfest_impressions_video_file');
    const frame = wp.media({ title: 'Video auswählen oder hochladen', button: { text: 'Video verwenden' }, multiple: false, library: { type: 'video' } });
    frame.on('select', function () { field.val(frame.state().get('selection').first().toJSON().url); });
    frame.open();
  });
});

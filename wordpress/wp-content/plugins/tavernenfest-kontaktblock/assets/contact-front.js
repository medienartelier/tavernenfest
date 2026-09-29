document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('[data-contact-form]').forEach(function (form) {
    const status = form.querySelector('[data-contact-status]');
    const button = form.querySelector('.contact-submit');
    const submitLabel = form.dataset.submitLabel || 'Nachricht senden';
    const sendingLabel = form.dataset.sendingLabel || 'Wird gesendet...';

    function setStatus(message, type) {
      if (!status) return;
      status.textContent = message || '';
      status.dataset.type = type || '';
    }

    function clearErrors() {
      form.querySelectorAll('.has-error').forEach(function (field) {
        field.classList.remove('has-error');
      });
      form.querySelectorAll('.contact-error').forEach(function (error) {
        error.remove();
      });
    }

    function showFieldError(name, message) {
      const input = form.querySelector('[name="' + name + '"]');
      if (!input) return;
      const field = input.closest('.contact-field');
      if (!field) return;
      field.classList.add('has-error');
      const error = document.createElement('p');
      error.className = 'contact-error';
      error.textContent = message;
      field.appendChild(error);
    }

    form.addEventListener('submit', function (event) {
      event.preventDefault();
      clearErrors();
      setStatus('', '');
      if (button) {
        button.disabled = true;
        button.textContent = sendingLabel;
      }

      fetch(tavernenfestContact.ajaxUrl, {
        method: 'POST',
        body: new FormData(form),
        credentials: 'same-origin'
      })
        .then(function (response) {
          return response.json().then(function (payload) {
            if (!response.ok || !payload.success) throw payload;
            return payload;
          });
        })
        .then(function (payload) {
          form.reset();
          setStatus(payload.data.message || 'Danke, deine Nachricht wurde gesendet.', 'success');
        })
        .catch(function (payload) {
          const data = payload && payload.data ? payload.data : {};
          if (data.errors) {
            Object.keys(data.errors).forEach(function (name) {
              showFieldError(name, data.errors[name]);
            });
          }
          setStatus(data.message || 'Bitte prüfe deine Eingaben.', 'error');
        })
        .finally(function () {
          if (button) {
            button.disabled = false;
            button.textContent = submitLabel;
          }
        });
    });
  });
});

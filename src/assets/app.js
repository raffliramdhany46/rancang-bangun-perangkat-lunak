(function () {
  async function refreshTodoCount() {
    var target = document.getElementById('todo-list');
    if (!target) return;

    try {
      var response = await fetch('/api/todos', { headers: { Accept: 'application/json' } });
      if (!response.ok) return;

      var payload = await response.json();
      var total = Array.isArray(payload.data) ? payload.data.length : 0;
      target.textContent = 'Total todo (sinkron via API): ' + total;
      target.className = 'card muted';
    } catch (error) {
      // Fallback ke SSR tanpa mengganggu UX.
    }
  }

  async function handleCreateForm(event) {
    var form = event.target;
    if (!form.matches('form[data-api-form="create"]')) return;

    event.preventDefault();
    var formData = new FormData(form);

    try {
      var response = await fetch('/api/todos', {
        method: 'POST',
        headers: { Accept: 'application/json' },
        body: formData
      });

      if (response.ok) {
        window.location.href = '/todos';
        return;
      }
    } catch (error) {
      // fallback ke submit normal
    }

    form.submit();
  }

  async function handleEditForm(event) {
    var form = event.target;
    if (!form.matches('form[data-api-form="edit"]')) return;

    event.preventDefault();
    var id = form.getAttribute('data-todo-id');
    var formData = new FormData(form);
    var body = {
      title: formData.get('title'),
      description: formData.get('description')
    };

    try {
      var response = await fetch('/api/todos/' + encodeURIComponent(id), {
        method: 'PATCH',
        headers: {
          Accept: 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(body)
      });

      if (response.ok) {
        window.location.href = '/todos';
        return;
      }
    } catch (error) {
      // fallback ke submit normal
    }

    form.submit();
  }

  document.addEventListener('submit', function (event) {
    handleCreateForm(event);
    handleEditForm(event);
  });

  refreshTodoCount();
})();


document.querySelectorAll('.delete-task').forEach(button => {
    button.addEventListener('click', function() {
        fetch('/tasks/' + this.dataset.id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(response => {
            if (response.ok) {
                this.parentElement.remove();
            } else {
                alert('Error: ' + response.statusText);
            }
        });
    });
});

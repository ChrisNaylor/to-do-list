document.addEventListener('DOMContentLoaded', function() {

    /**
     * Handles the form submission for adding a new task. Sends a POST request to the server with the task name.
     * If the request is successful, the form is cleared otherwise an alert is shown with the error message.
     */
    document.getElementById('add-task-form').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        const formData = new FormData(this); // Collect form data

        fetch('/', { // Send the form data to the server using fetch
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest', // Important for Laravel to recognize Ajax request
                    'X-CSRF-TOKEN': formData.get('_token') // CSRF token for Laravel validation
                },
                body: formData
            })
            .then(data => {
                console.log('Success:', data);
                this.reset();
                // add new task to the list
                location.reload(); //consider how to update the dom without reloading

                // Handle success (clear the form, show a success message, update the task list)
            })
            .catch((error) => {
                console.error('Error:', error);
                // Handle error (show an error message in console)
            });
    });

    // Get the modal and form elements
    const modal = document.getElementById('edit-modal');
    const form = document.getElementById('edit-form');
    const editId = document.getElementById('edit-id');
    const editName = document.getElementById('edit-name');
    const editCompleted = document.getElementById('edit-completed');

    /**
     * Handles the opening of the modal to edit a task.
     * Listens for the click event on the edit link then populates the form with the task details
     */
    document.querySelectorAll('.edit-link').forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            editId.value = this.dataset.id;
            editName.value = this.dataset.name;
            editCompleted.checked = this.dataset.completed === '1';
            modal.classList.remove('hidden');
        });
    });

    /**
     * Handles the form submission for updating a task. Sends a PUT request to the server with the updated task name.
     * If request is successful, reload the page otherwise an alert is shown with the error message.
     */
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        fetch('/api/tasks/' + editId.value, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name: editName.value,
                    completed: editCompleted.checked
                })
            }).then(response => response.json())
            .then(data => {
                if (data.message) {
                    console.log(data.message);
                    location.reload();
                } else {
                    console.error('Error: ' + data.error);
                }
            }).catch(error => {
                console.error('Error:', error);
            });
    });

    /**
     * Handles the completion of a task. Sends a PUT request to the server with the task ID and the completed status.
     * If the request is successful, reload the page otherwise an alert is shown with the error message.
     */
    document.querySelectorAll('.complete-task').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Are you sure you want to mark this task as completed?')) {
                fetch('/api/tasks/' + this.dataset.id, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            name: this.dataset.name,
                            completed: true
                        })
                    }).then(response => response.json())
                    .then(data => {
                        if (data.message) {
                            console.log(data.message);
                            location.reload();
                        } else {
                            console.error('Error: ' + data.error);
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                    });
            };
        });
    });

    /**
     * Handles the deletion of a task. Sends a DELETE request to the server with the task ID.
     * If the request is successful, the task is removed from the list otherwise an alert is shown with the error message.
     */
    document.querySelectorAll('.delete-task').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this task?')) {
                fetch('/api/tasks/' + this.dataset.id, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(response => response.json())
                    .then(data => {
                        if (data.message) {
                            console.log(data.message);
                            this.parentElement.remove();
                            document.getElementById('task-' + this.dataset.id).remove();
                        } else {
                            console.error('Error: ' + data.error);
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                    });
            }
        });
    });

});

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MLP To-Do</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')

</head>
<body>
    <div class="flex">
        <div class="w-full md:w-1/2 max-w-xs mx-auto mt-20">
            <form action="/" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Task Name
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" name="name" placeholder="Insert task name">
                </div>
                <div class="flex items-center justify-between">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Add Task
                    </button>
                </div>
            </form>
        </div>
        <div class="w-full md:w-1/2 max-w-xs mx-auto mt-20">
            <h1 class="text-2xl font-bold text-center">MLP To-Do</h1>
            <ul class="list-disc list-inside">
                @foreach($tasks as $task)
                    <li class="text-gray-700 text-base flex justify-between items-center mb-2">
                        <a href="#" class="edit-link hover:text-blue-500 @if($task->completed) line-through @endif" data-id="{{ $task->id }}" data-name="{{ $task->name }}" data-completed="{{ $task->completed }}">{{ $task->name }}</a>
                        <div class="actions">
                            <button type="button" class="complete-task bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline mr-2 @if($task->completed) opacity-50 cursor-not-allowed @endif"  data-id="{{ $task->id }}" data-name="{{ $task->name }}" data-completed="{{ $task->completed }}" @if($task->completed) disabled @endif>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>
                            <button type="button" class="delete-task bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline" data-id="{{ $task->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div id="edit-modal" class="hidden fixed top-0 left-0 w-full h-full flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-4 rounded">
            <h2 class="text-2xl mb-4">Edit Task</h2>
            <form id="edit-form">
                <input type="hidden" id="edit-id">
                <input type="text" id="edit-name" class="border p-2 w-full mb-2">
                <div class="flex items-center">
                    <input type="checkbox" id="edit-completed" class="mr-2">
                    <label for="edit-completed" class="text-gray-700">Completed</label>
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-4">Save</button>
            </form>
        </div>
    </div>
</body>
<footer>
    <script>
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
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Error: ' + data.error);
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
                            alert(data.message);
                            location.reload();
                        } else {
                            alert('Error: ' + data.error);
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
                            alert(data.message);
                            this.parentElement.remove();
                        } else {
                            alert('Error: ' + data.error);
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                    });
                }
            });
        });
    </script>
</footer>
</html>

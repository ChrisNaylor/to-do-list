<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MLP To-Do</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/images/favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')

</head>

<body class="min-h-screen">
    <header class="container mx-auto px-4 mt-4 mb-4">
        <img class="px-4" src="{{ asset('storage/images/logo.png') }}" alt="Logo">
    </header>
    <div class="container mx-auto px-4">
        <div class="flex">
            <div class="md:w-1/2 max-w-xs mx-auto mt-10 px-4">
                <form id="add-task-form" action="/" method="post">
                    @csrf
                    <div class="mb-4">
                        <input
                            class="appearance-none border border-gray-400 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="name" type="text" name="name" placeholder="Insert task name">
                    </div>
                    <div class="flex items-center justify-between">
                        <button
                            class="w-full bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            type="submit">
                            Add
                        </button>
                    </div>
                </form>
            </div>
            <div class="w-full mx-auto mt-10 px-4">
                <div class="bg-white px-4 pb-4 border border-gray-400">
                    <table id="task-list" class="w-full border-collapse">
                        <thead>
                            <tr class="border-bottom border-b-4 border-grey-custom">
                                <th class="py-2 px-4 w-10 text-left font-normal">#</th>
                                <th class="py-2 px-4  text-left font-normal">Task</th>
                                <th class="py-2 px-4 w-36"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $index => $task)
                                <tr id="task-{{ $task->id }}"
                                    class="text-gray-700 text-base @if ($index === count($tasks) - 1) border-bottom-0 @else border-bottom border-b-2 border-grey-custom @endif">
                                    <td class="py-2 px-4">{{ $task->id }}</td>
                                    <td class="py-2 px-4">
                                        <a href="#"
                                            class="edit-link hover:text-blue-500 @if ($task->completed) line-through @endif"
                                            data-id="{{ $task->id }}" data-name="{{ $task->name }}"
                                            data-completed="{{ $task->completed }}">{{ $task->name }}</a>
                                    </td>
                                    <td class="py-2 px-4">
                                        <div class="actions flex flex-row justify-center">
                                            <button type="button"
                                                class="complete-task bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline mr-2 @if ($task->completed) opacity-50 cursor-not-allowed @endif"
                                                data-id="{{ $task->id }}" data-name="{{ $task->name }}"
                                                data-completed="{{ $task->completed }}"
                                                @if ($task->completed) disabled @endif>
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                            <button type="button"
                                                class="delete-task bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline"
                                                data-id="{{ $task->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="edit-modal"
            class="hidden fixed top-0 left-0 w-full h-full flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-4 rounded">
                <h2 class="text-2xl mb-4">Edit Task</h2>
                <form id="edit-form">
                    <input type="hidden" id="edit-id">
                    <input type="text" id="edit-name" class="border p-2 w-full mb-2">
                    <div class="flex items-center">
                        <input type="checkbox" id="edit-completed" class="mr-2">
                        <label for="edit-completed" class="text-gray-700">Completed</label>
                    </div>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mt-4">Save</button>
                </form>
            </div>
        </div>
    </div>

    <footer class="sticky top-[100vh] my-2">
        <p class="w-full py-2 text-center">Copyright &copy; {{ now()->year }} All Rights Reserved.</p>
        <script>
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
        </script>
    </footer>

</body>

</html>

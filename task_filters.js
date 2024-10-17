document.addEventListener('DOMContentLoaded', () => {
    const taskList = document.getElementById('task-list');
    
    // Example filter function (can be expanded)
    function filterTasks(criteria) {
        // Apply filter logic here (e.g., based on location, skills, etc.)
    }

    // Fetch tasks via AJAX and populate task list (example function)
    fetch('api_get_tasks.php')
        .then(response => response.json())
        .then(tasks => {
            tasks.forEach(task => {
                const taskDiv = document.createElement('div');
                taskDiv.classList.add('task');
                taskDiv.innerHTML = `
                    <h3>${task.title}</h3>
                    <p>${task.description}</p>
                    <p>Payment: $${task.payment}</p>
                    <p>Location: ${task.location}</p>
                `;
                taskList.appendChild(taskDiv);
            });
        });
});

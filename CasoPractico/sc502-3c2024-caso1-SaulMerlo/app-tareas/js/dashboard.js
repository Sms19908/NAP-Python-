document.addEventListener('DOMContentLoaded', function () {

    let isEditMode = false;
    let edittingId;
    const tasks = [{
        id: 1,
        title: "Complete project report",
        description: "Prepare and submit the project report",
        dueDate: "2024-10-29",
    },
    {
        id: 2,
        title: "Team Meeting",
        description: "Get ready for the season",
        dueDate: "2024-12-01"
    },
    {
        id: 3,
        title: "Code Review",
        description: "Check partners code",
        dueDate: "2024-12-01"
    },
    {
        id: 4,
        title: "Deploy",
        description: "Check deploy steps",
        dueDate: "2024-12-01"
    }];

    const comments = [{
        taskId: 1,
        commentId: 1,
        comment: "test1",
    },
    {
        taskId: 2,
        commentId: 1,
        comment: "test1",
    },
    {
        taskId: 3,
        commentId: 1,
        comment: "test1",
    },
    {
        taskId: 1,
        commentId: 1,
        comment: "Expired",
    }];
    //<p class="card-text"><small>Comment section</small></p>
    //<p class="card-text"><small class="text-muted">${task.comment}</small> </p>

    function loadTasks() {
        const taskList = document.getElementById('task-list');
        taskList.innerHTML = '';

        tasks.forEach(function (task) {
            const taskCard = document.createElement('div');
            taskCard.className = 'col-md-4 mb-3';
            const taskComments = comments
                .filter(comment => comment.taskId === task.id)
                .map(comment => `
                    <div class="comment" id="comment-${comment.commentId}">
                        <p class="card-text"><small class="text-muted">${comment.comment}</small></p>
                        <small><p><a class="delete-comment link-underline-primary"data-comment-id="${comment.commentId}">Delete</a></p></small>
                    </div>
                `)
                .join('');
            taskCard.innerHTML = `
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">${task.title}</h5>
                <p class="card-text">${task.description}</p>
                <p class="card-text"><small class="text-muted">Due: ${task.dueDate}</small> </p>
                <div class="comment-section">
                    <small>Comment Section</small>
                     ${taskComments || '<p class="card-text"><small class="text-muted">No comments yet</small></p>'}
                    </div>
                    <button class="btn btn-primary btn-sm add-comment" data-id="${task.id}" data-bs-toggle="modal" data-bs-target="#commentModal">Add comment</button> </div>
            <div class="card-footer d-flex justify-content-between">
                <button class="btn btn-secondary btn-sm edit-task" data-id="${task.id}">Edit</button>
                <button class="btn btn-danger btn-sm delete-task" data-id="${task.id}">Delete</button>
            </div>
        </div>
    `;
            taskList.appendChild(taskCard);
        });

        document.querySelectorAll('.add-comment').forEach(function (button) {
            button.addEventListener('click', function () {
                const taskId = button.getAttribute('data-task-id');
                const commentId = button.getAttribute('data-comment-id') + 1;
                openCommentModal(taskId, commentId);
            });
        });

        document.querySelectorAll('.delete-comment').forEach(function (button) {
            button.addEventListener('click', handleDeleteComment);
        });

        document.querySelectorAll('.edit-task').forEach(function (button) {
            button.addEventListener('click', handleEditTask);
        });

        document.querySelectorAll('.delete-task').forEach(function (button) {
            button.addEventListener('click', handleDeleteTask);
        });

    }

    function handleEditTask(event) {
        try {
            // alert(event.target.dataset.id);
            //localizar la tarea quieren editar
            const taskId = parseInt(event.target.dataset.id);
            const task = tasks.find(t => t.id === taskId);
            //cargar los datos en el formulario 
            document.getElementById('task-title').value = task.title;
            document.getElementById('task-desc').value = task.description;
            document.getElementById('due-date').value = task.dueDate;
            //ponerlo en modo edicion
            isEditMode = true;
            edittingId = taskId;
            //mostrar el modal
            const modal = new bootstrap.Modal(document.getElementById("taskModal"));
            modal.show();


        } catch (error) {
            alert("Error trying to edit a task");
            console.error(error);
        }
    }


    function handleDeleteTask(event) {
        // alert(event.target.dataset.id);
        const id = parseInt(event.target.dataset.id);
        const index = tasks.findIndex(t => t.id === id);
        tasks.splice(index, 1);
        loadTasks();
    }

    document.getElementById('task-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const title = document.getElementById("task-title").value;
        const description = document.getElementById("task-desc").value;
        const dueDate = document.getElementById("due-date").value;

        if (isEditMode) {
            //todo editar
            const task = tasks.find(t => t.id === edittingId);
            task.title = title;
            task.description = description;
            task.dueDate = dueDate;
        } else {
            const newTask = {
                id: tasks.length + 1,
                title: title,
                description: description,
                dueDate: dueDate
            };
            tasks.push(newTask);
        }
        const modal = bootstrap.Modal.getInstance(document.getElementById('taskModal'));
        modal.hide();
        loadTasks();
    });


    document.getElementById('taskModal').addEventListener('show.bs.modal', function () {
        if (!isEditMode) {
            document.getElementById('task-form').reset();
            // document.getElementById('task-title').value = "";
            // document.getElementById('task-desc').value = "";
            // document.getElementById('due-date').value = "";
        }
    });

    document.getElementById("taskModal").addEventListener('hidden.bs.modal', function () {
        edittingId = null;
        isEditMode = false;
    })
    loadTasks();



    function handleDeleteComment(event) {
        const commentId = parseInt(event.target.dataset.commentId);
        const commentDiv = document.getElementById(`comment-${commentId}`);

        if (commentDiv) {
            commentDiv.classList.add('highlight');
            commentDiv.remove();
            const commentIndex = comments.findIndex(c => c.commentId === commentId);
            if (commentIndex !== -1) comments.splice(commentIndex, 1);
        }
    }


    document.getElementById('comment-form').addEventListener('submit', function () {

        const taskId = document.getElementById("task-id").value;
        const commentId = document.getElementById("comment-id").value;
        const commentText = document.getElementById("comment").value;

        if (isEditMode) {
            const taskComments = comments.find(comments => comments.commentId === edittingId);
            taskComments.taskId = taskId;
            taskComments.commentId = commentId;
            taskComments.comment = commentText;
        } else {
            const newComment = {
                taskId: taskId,
                commentId: comments.length + 1,
                commentText: commentText
            };
            comments.push(newComment);
        }
        const modal = bootstrap.Modal.getInstance(document.getElementById('commentModal'));
        modal.hide();
        loadTasks();
    });


    document.getElementById('commentModal').addEventListener('show.bs.modal', function () {
        if (!isEditMode) {
            document.getElementById('comment-form').reset();
        }
    });

    document.getElementById("commentModal").addEventListener('hidden.bs.modal', function () {
        edittingId = null;
        isEditMode = false;
    })
    loadTasks();


});
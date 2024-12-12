let isEditMode = false;
    let edittingId;
    let tasks = [];
    const API_URL = 'backend/comments.php';

   //Comment Get, Put & Post


   document.getElementById('commentModal').addEventListener('show.bs.modal', function () {
    document.getElementById('comment-form').reset();
})

document.getElementById('comment-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const commentUser = document.getElementById("comment-user_id").value;
    const commentTask = document.getElementById("comment-task_id").value;
    const comment = document.getElementById("task-comment").value;

    if (isEditMode) {
        //editar
        const response = await fetch(`${API_URL}?id=${edittingId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ user_id: commentUser, task_id: commentTask, comment: comment}),
            credentials: "include"
        });
        if (!response.ok) {
            console.error("Sucedio un error");
        }

    } else {
        const newComment = {
            user_id: commentUser,
            task_id: commentTask,
            comment: comment
        };
        //enviar la tarea al backend
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(newComment),
            credentials: "include"
        });
        if (!response.ok) {
            console.error("Sucedio un error");
        }
    }
    const modal = bootstrap.Modal.getInstance(document.getElementById('commentModal'));
    modal.hide();
    loadTasks();
});

function handleEditComment(event) {
    try {
        // alert(event.target.dataset.id);
        //localizar la tarea quieren editar
        const comment_id = parseInt(event.target.dataset.comment_id);
        const comentarios = comentarios.find(c => c.comment_id === comment_id);
        //cargar los datos en el formulario 
        document.getElementById('comment-user_id').value = comentarios.user_id;
        document.getElementById('comment-task_id').value = comentarios.task_id;
        document.getElementById('task-comment').value = comentarios.comment;
        //ponerlo en modo edicion
        isEditMode = true;
        edittingId = taskId;
        //mostrar el modal
        const modal = new bootstrap.Modal(document.getElementById("commentModal"));
        modal.show();


    } catch (error) {
        alert("Error trying to edit a task");
        console.error(error);
    }
}


async function handleDeleteTask(event) {
    const comment_id = parseInt(event.target.dataset.comment_id);
    const response = await fetch(`${API_URL}?comment_id=${comment_id}`, {
        method: 'DELETE',
        credentials: 'include'
    });
    if (response.ok) {
        loadComments();
    } else {
        console.error("Error eliminando las tareas");
    }
}
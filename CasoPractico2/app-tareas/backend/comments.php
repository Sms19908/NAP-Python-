<?php

require 'db.php';

function crearComentario($comment_id,$task_id, $user_id, $comment)
{
    global $pdo;
    try {
        $sql = "INSERT INTO comments (comment_id, task_id, user_id, comment) values (:comment_id, :task_id, :user_id, :comment)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'comment_id' => $comment_id,
            'user_id' => $user_id,
            'task_id' => $task_id,
            'comment' => $comment
        ]);
        //devuelve el id del comentario creado en la linea anterior
        return $pdo->lastInsertId();
    } catch (Exception $e) {
        logError("Error creando tarea: " . $e->getMessage());
        return 0;
    }
}

function editarComentario($comment_id,$task_id, $user_id, $comment)
{
    global $pdo;
    try {
        $sql = "UPDATE comments set user_id = :user_id, task_id = :task_id, comment = :comment where comment_id = :comment_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'user_id' => $user_id,
            'task_id' => $task_id,
            'comment' => $comment,
            'comment_id' => $comment_id
        ]);
        $affectedRows = $stmt->rowCount();
        return $affectedRows > 0;
    } catch (Exception $e) {
        logError($e->getMessage());
        return false;
    }
}

function eliminarComentario($comment_id)
{
    global $pdo;
    try {
        $sql = "delete from comments where comment_id = :comment_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['comment_id' => $comment_id]);
        return $stmt->rowCount() > 0;// true si se elimina algo
    } catch (Exception $e) {
        logError("Error al eliminar la tareas: " . $e->getMessage());
        return false;
    }
}

function obtenerComentariosPorTarea($task_id)
{
    global $pdo;
    try {
        $sql = "Select * from comments where task_id = :task_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['task_id' => $task_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        logError("Error al obtener tareas: " . $e->getMessage());
        return [];
    }
}

$method = $_SERVER['REQUEST_METHOD'];
header('Content-Type: application/json');
function getJsonInput()
{
    return json_decode(file_get_contents("php://input"), true);
}

session_start();
if (isset($_SESSION['user_id'])) {
    //el usuario tiene sesion
    $user_id = $_SESSION['user_id'];
    logDebug($user_id);
    switch ($method) {
        case 'GET':
            //devolver las tareas del usuario conectado
            $comments = obtenerComentariosPorTarea($task_id);
            echo json_encode($comments);
            break;

        case 'POST':
            $input = getJsonInput();
            if (isset($input['task_id'], $input['user_id'], $input['comment'])) {
                //vamos a crear tarea
                $comment_id = crearComentario( $comment_id,$input['task_id'], $input['user_id'], $input['comment']);
                if ($task_id > 0) {
                    http_response_code(201);
                    echo json_encode(value: ["messsage" => "Comentario creado: ID:" . $task_id]);
                } else {
                    http_response_code(500);
                    echo json_encode(["error" => "Error general creando el comentario"]);
                }
            } else {
                //retornar un error
                http_response_code(400);
                echo json_encode(["error" => "Datos insuficientes"]);
            }
            break;

        case 'PUT':
            $input = getJsonInput();
            if (isset($input['task_id'], $input['user_id'], $input['comment']) && $_GET['comment_id']) {
                $editResult = editarComentario($_GET['comment_id'], $input['task_id'], $input['user_id'], $input['comment']);
                if ($editResult) {
                    http_response_code(201);
                    echo json_encode(['message' => "Comentario actualizada"]);
                } else {
                    http_response_code(500);
                    echo json_encode(["message" => "Error actualizando el comentario"]);
                }
            } else {
                //retornar un error
                http_response_code(400);
                echo json_encode(["error" => "Datos insuficientes"]);
            }
            break;

        case 'DELETE':
            if ($_GET['comment_id']) {
                $fueEliminado = eliminarComentario($_GET['comment_id']);
                if ($fueEliminado) {
                    http_response_code(200);
                    echo json_encode(['message' => "Comentario eliminada"]);
                } else {
                    http_response_code(500);
                    echo json_encode(['message' => 'Sucedio un error al eliminar el comentario']);
                }

            } else {
                //retornar un error
                http_response_code(400);
                echo json_encode(["error" => "Datos insuficientes"]);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(["error" => "Metodo no permitido"]);
            break;
    }

} else {
    http_response_code(401);
    echo json_encode(["error" => "Sesion no activa"]);
}

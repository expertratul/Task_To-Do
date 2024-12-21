<?php

define("tasks_file", "tasks.json");

function loadTasks(): array
{
    if (!file_exists(tasks_file)) {
        return [];
    }

    $data = file_get_contents(tasks_file);

    return $data ? json_decode($data, true) : [];
}


$tasks = loadTasks();


function saveTasks(array $tasks): void
{
    file_put_contents(tasks_file, json_encode($tasks, JSON_PRETTY_PRINT));
}

// print_r($_SERVER);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task']) && !empty(trim($_POST['task']))) {
        // add a task
        $tasks[] = [
            'task' => htmlspecialchars(trim($_POST['task'])),
            'done' => false
        ];
        saveTasks($tasks);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } elseif (isset($_POST['delete'])) {
        // delete a task
        unset($tasks[$_POST['delete']]);
        $tasks = array_values($tasks);
        SaveTasks($tasks);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } elseif (isset($_POST['toggle'])) {
        // print_r($tasks[$_POST['toggle']]['done']);
        // die();
        //toggle task as complete
        $tasks[$_POST['toggle']]['done'] = !$tasks[$_POST['toggle']]['done'];
        SaveTasks($tasks);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

?>

<!-- UI -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.min.css">
    <style>
        body {
            margin-top: 20px;
        }

        .task-card {
            border: 1px solid #ececec;
            padding: 20px;
            border-radius: 5px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .task {
            color: #888;
        }

        .task-done {
            text-decoration: line-through;
            color: #888;
        }

        .task-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        ul {
            padding-left: 20px;
        }

        button {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="task-card">
            <h1>To-Do App</h1>

            <!-- Add Task Form -->
            <form method="POST">
                <div class="row">
                    <div class="column column-75">
                        <input type="text" name="task" placeholder="Enter a new name" required>
                    </div>
                    <div class="column column-25">
                        <button type="submit" class="button-primary">Add submit</button>
                    </div>
                </div>
            </form>

            <!-- Task List -->
            <h2>Task Name List</h2>
            <ul style="list-style: none; padding: 0;">
                <!-- TODO: Loop through tasks array and display each task with a toggle and delete option -->
                <!-- If there are no tasks, display a message saying "No tasks yet. Add one above!" -->
                <?php if (empty($tasks)): ?>
                    <li>No tasks yet. add one above!</li>
                <?php else: ?>
                    <?php foreach ($tasks as $index => $task): ?>
                        <li class="task-item">
                            <from method='post' style="flex-grow: 1;">
                                <input type='hidden' name='toggle' value="<?= $index ?>">
                                <button type="submit" style="border: none; background:
                                 none; cursor: pointer; text-align: left; width: 100%;">
                                    <span class="task <?= $task['done'] ? 'task-done'
                                                            : '' ?>">
                                        <?= htmlspecialchars($task['task']) ?>
                                    </span>
                                </button>
                            </from>
                            <form method="POST">
                                <input type="hidden" name="delete" value="<?= $index ?>">
                                <button type="submit" class="button button-outline" style="margin-left: 10px;">Delete</button>
                            </form>
                        </li>

                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>

        </div>
    </div>
</body>

</html>
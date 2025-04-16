<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Indie+Flower&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url('assets/style.css'); ?>">
    <title>To Do-List</title>
</head>

<body>

    <div class="container mt-5">
        <h1 class="text-center" style="font-family: 'Indie Flower', sans-serif; font-weight: 700; color: #fff;">
            To Do List
        </h1>
        <h4 class="text-center mb-2" style="font-family: 'Indie Flower', sans-serif; font-weight: 400; color: #fff;">
            Ayo selesaikan tugas-tugasmu!
        </h4>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="font-family: 'Nunito', sans-serif;">
                        <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">To Do List</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="font-family: 'Nunito', sans-serif;">
                        <!-- <h1 class="text-center">To-Do List</h1> -->
                        <form method="post" action="<?php echo site_url('todo/add'); ?>" class="mb-3">
                            <div class="mb-3">
                                <input type="text" name="task" id="task" required class="form-control" placeholder="Tasks">
                            </div>
                            <div class="mb-3">
                                <input type="date" name="deadline" id="deadline" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <div id="subtasks">
                                    <input class="form-control mb-2" type="text" name="subtasks[]" placeholder="Subtask">
                                </div>
                            </div>
                            <button class="btn btn-outline-info" type="button" onclick="addSubtask()">Add Subtask</button>
                            <button class="btn btn-light" type="submit">Add Task</button>
                        </form>
                    </div>
                    <!-- <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div> -->
                </div>
            </div>
        </div>

        <div class="p-4">
        <button type="button" class="btn btn-primary mb-2 fw-bold" data-bs-toggle="modal" data-bs-target="#exampleModal" style="font-family: 'Nunito', sans-serif;">
            New Tasks
        </button>
        <?php if (empty($tasks)): ?>
    <div class="card text-center bg-light mb-3" style="font-family: 'Nunito', sans-serif;">
        <div class="card-body">
            <h5 class="card-title text-muted">Belum ada tugas</h5>
            <p class="card-text">Yuk mulai dengan menambahkan tugas baru!</p>
        </div>
    </div>
<?php else: ?> 
            <ol class="list-group">
                <?php foreach ($tasks as $task): ?>
                    <li class="list-group-item">
                        <div class="d-flex flex-column">
                            <div class="d-flex justify-content-between" style="font-family: 'Nunito', sans-serif;">
                                <div>
                                    <strong class="text-dark"> <?php echo $task->task; ?> </strong>
                                    <small class="text-muted">Deadline: <?php echo date('d M Y', strtotime($task->deadline)); ?></small>
                                </div>
                                <div>
                                    <div class="text-end"></div>
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $task->id; ?>">Edit</button>
                                    <a href="<?php echo site_url('todo/delete/' . $task->id); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus ini?')">Delete</a>
                                </div>
                            </div>

                            <ul class="list-unstyled" style="font-family: 'Nunito', sans-serif;">
                                <?php if (!empty($task->subtasks)): ?>
                                    <?php foreach ($task->subtasks as $subtask): ?>
                                        <li class="subtask-item">
                                            <div class="d-flex justify-content-between align-items-center w-100">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" onchange="updateSubtaskStatus(<?php echo $subtask->id; ?>, this.checked)" <?php echo $subtask->status == 'done' ? 'checked' : ''; ?>>
                                                    <label class="form-check-label <?php echo $subtask->status == 'done' ? 'text-decoration-line-through text-success' : ''; ?>">
                                                        <?php echo $subtask->subtask; ?>
                                                        <span class="badge <?php echo $subtask->status == 'done' ? 'bg-success' : 'bg-danger'; ?> ms-2"
                                                            style="font-size: 0.75rem; padding: 0.25em 0.5em;">
                                                            <?php echo $subtask->status == 'done' ? 'Selesai' : 'Belum dikerjakan'; ?>
                                                        </span>

                                                    </label>

                                                </div>
                                                <div class="text-end">
                                                    <a href="<?php echo site_url('todo/delete_subtask/' . $subtask->id); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus ini?')">Delete</a>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>

                        </div>
                    </li>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal<?php echo $task->id; ?>" tabindex="-1" style="font-family: 'Nunito', sans-serif;">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold">Edit Task</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="post" action="<?php echo site_url('todo/update/' . $task->id); ?>">
                                    <div class="modal-body">
                                        <label class="mb-2">Task</label>
                                        <input type="text" name="task" class="form-control" value="<?php echo $task->task; ?>">
                                        <label class="mt-3">Deadline</label>
                                        <input type="date" name="deadline" class="form-control" value="<?php echo $task->deadline; ?>">
                                        <label class="mt-3">Subtasks</label>
                                        <div id="editSubtasks<?php echo $task->id; ?>">
                                            <?php if (!empty($task->subtasks)): ?>
                                                <?php foreach ($task->subtasks as $subtask): ?>
                                                    <div>
                                                        <input type="hidden" name="subtask_ids[]" value="<?php echo $subtask->id; ?>">
                                                        <input class="form-control mb-2" type="text" name="subtasks[]" value="<?php echo $subtask->subtask; ?>">
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <input class="form-control mb-2" type="text" name="subtasks[]" placeholder="Subtask">
                                            <?php endif; ?>
                                        </div>
                                        <button class="btn btn-outline-info mt-2" type="button" onclick="addEditSubtask(<?php echo $task->id; ?>)">+ Add Subtask</button>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </ol>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function addEditSubtask(taskId) {
            const container = document.getElementById(`editSubtasks${taskId}`);

            const wrapper = document.createElement('div');
            wrapper.className = 'd-flex align-items-center mb-2';

            // Input hidden ID kosong (agar sejajar dengan subtasks[])
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'subtask_ids[]';
            hiddenInput.value = ''; // Kosong karena subtask baru

            // Input isi subtask
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'subtasks[]';
            input.className = 'form-control';
            input.placeholder = 'Subtask';
            input.required = true;

            wrapper.appendChild(hiddenInput);
            wrapper.appendChild(input);
            container.appendChild(wrapper);
        }

        function addSubtask() {
            const container = document.getElementById('subtasks');

            const input = document.createElement('input');
            input.className = 'form-control mb-2';
            input.type = 'text';
            input.name = 'subtasks[]';
            input.placeholder = 'Subtask';
            input.required = true;

            container.appendChild(input);
        }

        function updateSubtaskStatus(subtaskId, isChecked) {
            const status = isChecked ? 'done' : 'pending';

            fetch('<?php echo site_url("todo/update_subtask_status"); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${subtaskId}&status=${status}`
                })
                .then(res => res.text())
                .then(res => {
                    console.log('Server response:', res);

                    // Cari elemen checkbox dan label terkait
                    const checkbox = document.querySelector(`input[type="checkbox"][onchange="updateSubtaskStatus(${subtaskId}, this.checked)"]`);
                    if (checkbox) {
                        const label = checkbox.nextElementSibling;
                        if (label) {
                            if (isChecked) {
                                label.classList.add('text-decoration-line-through', 'text-success');
                            } else {
                                label.classList.remove('text-decoration-line-through', 'text-success');
                            }

                            const badge = label.querySelector('span.badge');
                            if (badge) {
                                badge.textContent = isChecked ? 'Selesai' : 'Belum dikerjakan';
                                badge.className = 'badge ' + (isChecked ? 'bg-success' : 'bg-danger') + ' ms-2';
                            }
                        }
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                });
        }
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>
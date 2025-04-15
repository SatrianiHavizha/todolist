<?php
class Todo_model extends CI_Model {
    public function __construct() {
        $this->load->database();
    }

    public function get_tasks() {
        $tasks = $this->db->get('tasks')->result();
        foreach ($tasks as $task) {
            $task->subtasks = $this->db->get_where('subtasks', ['task_id' => $task->id])->result();
        }
        return $tasks;
    }

    public function add_task($task, $deadline) {
        $this->db->insert('tasks', [
            'task' => $task,
            'deadline' => $deadline
        ]);
        return $this->db->insert_id();
    }

    public function add_subtask($task_id, $subtask) {
        return $this->db->insert('subtasks', ['task_id' => $task_id, 'subtask' => $subtask]);
    }

    public function delete_task($id) {
        $this->db->delete('subtasks', ['task_id' => $id]);
        return $this->db->delete('tasks', ['id' => $id]);
    }

    public function delete_subtask($id) {
       return $this->db->delete('subtasks', ['id' => $id]);
    }

    public function update_task($id, $task, $deadline) {
        $this->db->where('id', $id);
        return $this->db->update('tasks', [
            'task' => $task,
            'deadline' => $deadline,
        ]);
    }    

    public function update_subtask($id, $newName)
{
    $this->db->where('id', $id);
    $this->db->update('subtasks', ['subtask' => $newName]);
}

    public function delete_removed_subtasks($task_id, $existing_ids) {
        if (!empty($existing_ids)) {
            $this->db->where('task_id', $task_id);
            $this->db->where_not_in('id', $existing_ids);
            $this->db->delete('subtasks');
        }
    }

    public function update_subtask_status($id, $status)
{
    $this->db->where('id', $id);
    $this->db->update('subtasks', ['status' => $status]);
}


}
<?php
class todo extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('todo_model');
		$this->load->helper('url');
	}

	public function index()
	{
		$data['tasks'] = $this->todo_model->get_tasks();
		$this->load->view('todo/index', $data);
	}

	public function add()
	{
		$task = $this->input->post('task');
		$deadline = $this->input->post('deadline');

		if ($task) {
			$task_id = $this->todo_model->add_task($task, $deadline);

			$subtasks = $this->input->post('subtasks');
			if (!empty($subtasks)) {
				foreach ($subtasks as $subtasks) {
					$this->todo_model->add_subtask($task_id, $subtasks);
				}
			}
		}
		redirect('todo');
	}

	public function delete($id)
	{
		$this->todo_model->delete_task($id);
		redirect('todo');
	}

	public function delete_subtask($id)
	{
		$this->todo_model->delete_subtask($id);
		redirect('todo');
	}

	public function update($id)
	{
		$task = $this->input->post('task');
		$deadline = $this->input->post('deadline');
		$this->todo_model->update_task($id, $task, $deadline);

		$subtaskIds = $this->input->post('subtask_ids');
		$subtasks = $this->input->post('subtasks');

		if (!empty($subtasks)) {
			$existingSubtaskIds = [];

			foreach ($subtasks as $index => $subtask) {
				if (isset($subtaskIds[$index]) && !empty($subtaskIds[$index])) {
					$this->todo_model->update_subtask($subtaskIds[$index], $subtask);
					$existingSubtaskIds[] = $subtaskIds[$index];
				} else {
					$this->todo_model->add_subtask($id, $subtask);
				}
			}
		}

		redirect('todo');
	}

	public function update_subtask($id)
	{
		$newName = $this->input->post('subtask');
		$this->todo_model->update_subtask($id, $newName);
		redirect('todo');
	}

	public function update_subtask_status()
{
    $id = $this->input->post('id');
    $status = $this->input->post('status');

    // Debug sementara
    // echo "ID: $id, STATUS: $status"; exit;

    $this->todo_model->update_subtask_status($id, $status);
    echo 'OK';
}


}

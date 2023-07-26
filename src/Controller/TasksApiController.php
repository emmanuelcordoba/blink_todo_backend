<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Log\Log;

/**
 * Tasks Controller
 *
 * @property \App\Model\Table\TasksTable $Tasks
 *
 * @method \App\Model\Entity\Task[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TasksApiController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadModel('Tasks');
    }

    public function index()
    {
        $tasks = $this->Tasks->find('all');
        $this->set([
            'status' => true,
            'tasks' => $tasks,
            '_serialize' => ['status','tasks']
        ]);
    }

    public function view($id = null)
    {
        $task = $this->Tasks->get($id);
        $this->set([
            'status' => true,
            'task' => $task,
            '_serialize' => ['status','task']
        ]);
    }


    public function add()
    {
        $this->request->allowMethod(['post', 'put']);
        $task = $this->Tasks->newEntity($this->request->getData());
        if($task->getErrors())
        {
            $data = [
                'status' => false,
                'message' => 'Error',
                'errors' => $task->getErrors()
            ];
            return $this->response->withType('application/json')->withStringBody(json_encode($data))->withStatus(409);
        }
        if (!$this->Tasks->save($task))
        {
            $data = [
                'status' => false,
                'message' => 'Error'
            ];
            return $this->response->withType('application/json')->withStringBody(json_encode($data))->withStatus(400);
        }
        $this->set([
            'status' => true,
            'message' => 'Saved',
            'task' => $task,
            '_serialize' => ['status','message','task']
        ]);
    }


    public function edit($id = null)
    {
        $this->request->allowMethod(['patch', 'post', 'put']);
        $task = $this->Tasks->get($id);
        $task = $this->Tasks->patchEntity($task, $this->request->getData());
        if($task->getErrors())
        {
            $data = [
                'status' => false,
                'message' => 'Error',
                'errors' => $task->getErrors()
            ];
            return $this->response->withType('application/json')->withStringBody(json_encode($data))->withStatus(409);
        }
        if (!$this->Tasks->save($task))
        {
            $data = [
                'status' => false,
                'message' => 'Error'
            ];
            return $this->response->withType('application/json')->withStringBody(json_encode($data))->withStatus(400);
        }
        $this->set([
            'status' => true,
            'message' => 'Edited',
            'task' => $task,
            '_serialize' => ['status','message','task']
        ]);
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['DELETE']);
        $task = $this->Tasks->get($id);
        $message = 'Deleted';
        $status = true;
        if (!$this->Tasks->delete($task)) {
            $message = 'Error';
            $status = false;
        }
        $this->set([
            'status' => $status,
            'message' => $message,
            '_serialize' => ['status','message']
        ]);
    }
}

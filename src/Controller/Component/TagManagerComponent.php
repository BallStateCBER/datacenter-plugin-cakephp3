<?php
namespace DataCenter\Controller\Component;

use Cake\Controller\Component;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

class TagManagerComponent extends Component
{
    public function beforeRender()
    {
        $this->Tags = TableRegistry::get('Tags');
    }

    public function getTags($model = null, $id = null)
    {
        if (!$model) {
            $model = $this->modelClass;
        }
        $tags = $this->Tags->find('threaded')
            ->select(['name', 'id', 'parent_id', 'selectable'])
            ->order(['name' => 'ASC'])
            ->toArray();

        return $tags;
    }

    // Returns the top $limit most used tags associated with $model
    public function getTop($model, $limit = 5)
    {
        $model = strtolower($model);
        $table = "{$model}_tags";
        $connection = ConnectionManager::get('default');
        $tags = $connection->execute("
                    SELECT $table.tag_id, tags.name, COUNT($table.tag_id)
                    AS occurrences
                    FROM $table, tags
                    WHERE tags.id = $table.tag_id
                    GROUP BY $table.tag_id
                    ORDER BY occurrences DESC
                    LIMIT $limit")
                    ->fetchAll('assoc');

        return $tags;
    }
}

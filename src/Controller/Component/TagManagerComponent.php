<?php
declare(strict_types=1);

namespace DataCenter\Controller\Component;

use Cake\Controller\Component;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

class TagManagerComponent extends Component
{
    /**
     * Returns an array of tags, each with values for name, id, and occurrences
     *
     * @param string $model Name of the model associated with tags
     * @return array
     */
    public function getCloud($model)
    {
        $connection = ConnectionManager::get('default');
        $model = strtolower($model);
        $table = "{$model}_tags";
        $result = $connection->execute("
            SELECT $table.tag_id, tags.name, COUNT($table.tag_id)
            AS occurrences
            FROM $table, tags
            WHERE tags.id = $table.tag_id
            GROUP BY $table.tag_id
            ORDER BY tags.name ASC
        ");
        $tagCloud = [];
        foreach ($result as $row) {
            $name = $row['name'];
            $id = $row['tag_id'];
            $occurrences = $row['occurrences'];
            $tagCloud[] = compact('name', 'id', 'occurrences');
        }

        return $tagCloud;
    }

    /**
     * Returns a nested collection of tags for the selected (or default) model
     *
     * @return array
     */
    public function getTags()
    {
        $this->Tags = TableRegistry::get('Tags');
        $tags = $this->Tags->find('threaded')
            ->select(['name', 'id', 'parent_id', 'selectable'])
            ->order(['name' => 'ASC'])
            ->toArray();

        return $tags;
    }

    /**
     * Returns the top $limit most used tags associated with $model
     *
     * @param string $model Name of model associated with tags
     * @param int $limit Number of results to return
     * @return array
     */
    public function getTop($model, $limit = 5)
    {
        $connection = ConnectionManager::get('default');
        $model = strtolower($model);
        $table = "{$model}_tags";
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

    /**
     * Prepares the tag editor
     *
     * @param \Cake\Controller\Controller $controller CakePHP controller
     * @return void
     */
    public function prepareEditor($controller)
    {
        // Provide the full list of available tags to the tag editor in the view
        $controller->set('availableTags', $this->getTags());

        /* Check and see if these tags have a 'listed' field
         * (Listed tags show up under 'available tags' in the tag editor, unlisted do not) */
        $Tag = $this->Tags->newEntity();
        if (isset($Tag->_schema['listed'])) {
            $unlistedTags = [];

            // Find any unlisted tags associated with this form
            if ($controller->request->getData('Tags')) {
                foreach ($controller->request->getData('Tags') as $tag) {
                    $Tag->id = is_array($tag) ? $tag['id'] : $tag;
                    $listed = $tag['listed'] ?? $Tag->field('listed');
                    if (! $listed) {
                        $unlistedTags[$Tag->id] = $tag['name'] ?? $Tag->field('name');
                    }
                }
            }

            /* Since the tag editor normally auto-populates the 'selected tags' field with a list of tag IDs
             * and pulls the names of those tags from the 'available tags' field, the names of unlisted tags
             * will need to be provided to it with this variable. */
            $controller->set(compact('unlisted_tags'));
        }
    }
}

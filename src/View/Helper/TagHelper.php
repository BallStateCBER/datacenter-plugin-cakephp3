<?php
namespace DataCenter\View\Helper;

use Cake\View\Helper;
use Cake\ORM\TableRegistry;

class TagHelper extends Helper
{
    public $helpers = ['Html', 'Js'];

    private function availableTagsForJs($availableTags)
    {
        $arrayForJson = [];
        foreach ($availableTags as $tag) {
            $arrayForJson[] = [
                'id' => $tag->id,
                'name' => $tag->name,
                'selectable' => $tag->selectable,
                'children' => $this->availableTagsForJs($tag->children)
            ];
        }
        return $arrayForJson;
    }
    private function selectedTagsForJs($selectedTags)
    {
        $arrayForJson = [];
        foreach ($selectedTags as $tag) {
            $arrayForJson[] = [
                'id' => $tag->id,
                'name' => $tag->name
            ];
        }
        return $arrayForJson;
    }
    /**
     * If necessary, convert selected_tags from an array of IDs to a full array of tag info
     * @param array $selectedTags
     * @return array
     */
    private function formatSelectedTags($selectedTags)
    {
        if (empty($selectedTags)) {
            return [];
        }
        if (is_array($selectedTags[0])) {
            return $selectedTags;
        }
        $this->Tags = TableRegistry::get('Tags');
        $retval = [];

        foreach ($selectedTags as $tag_id) {
            $result = $this->Tags->find()
                ->select(['id', 'name', 'parent_id', 'listed', 'selectable'])
                ->where(['id' => $tag_id])
                ->first();
            $retval[] = $result;
        }

        return $retval;
    }
    public function setup($availableTags, $selectedTags = [], $options = [])
    {
        $params = [
            'tags: ' . json_encode($this->availableTagsForJs($availableTags))
        ];
        if (!empty($selectedTags)) {
            $selectedTags = $this->formatSelectedTags($selectedTags);
            $params[] = 'selected_tags: ' . json_encode($this->selectedTagsForJs($selectedTags));
        }
        if (! empty($options)) {
            foreach ($options as $var => $val) {
                if ($val === true) {
                    $val = 'true';
                } elseif ($val === false) {
                    $val = 'false';
                }
                $params[] = "$var: $val";
            }
        }
        echo '<script>TagManager.init({' . implode(', ', $params) . '});</script>';
    }
}

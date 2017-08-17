<?php
namespace DataCenter\View\Helper;

use Cake\View\Helper;
use Cake\ORM\TableRegistry;

class TagHelper extends Helper
{
    public $helpers = ['Html', 'Js'];

    private function availableTagsForJs($available_tags)
    {
        $array_for_json = [];
        foreach ($available_tags as $tag) {
            $array_for_json[] = [
                'id' => $tag->id,
                'name' => $tag->name,
                'selectable' => $tag->selectable,
                'children' => $this->availableTagsForJs($tag->children)
            ];
        }
        return $array_for_json;
    }
    private function selectedTagsForJs($selected_tags)
    {
        $array_for_json = [];
        foreach ($selected_tags as $tag) {
            $array_for_json[] = [
                'id' => $tag->id,
                'name' => $tag->name
            ];
        }
        return $array_for_json;
    }
    /**
     * If necessary, convert selected_tags from an array of IDs to a full array of tag info
     * @param array $selected_tags
     * @return array
     */
    private function formatSelectedTags($selected_tags)
    {
        if (empty($selected_tags)) {
            return [];
        }
        if (is_array($selected_tags[0])) {
            return $selected_tags;
        }
        $this->Tags = TableRegistry::get('Tags');
        $retval = [];

        foreach ($selected_tags as $tag_id) {
            $result = $this->Tags->find()
                ->select(['id', 'name', 'parent_id', 'listed', 'selectable'])
                ->where(['id' => $tag_id])
                ->first();
            $retval[] = $result;
        }
        return $retval;
    }
    public function setup($available_tags, $selected_tags = [], $options = [])
    {
        $this->Html->script('/data_center/js/tag_manager.js', ['inline' => false]);
        $this->Html->css('/data_center/css/tag_editor.css', ['inline' => false]);
        $params = array(
            'tags: '.$this->Js->object($this->availableTagsForJs($available_tags))
        );
        if (! empty($selected_tags)) {
            $selected_tags = $this->formatSelectedTags($selected_tags);
            $params[] = 'selected_tags: '.$this->Js->object($this->selectedTagsForJs($selected_tags));
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
        $this->Js->buffer('TagManager.init({'.implode(', ', $params).'});');
    }
}

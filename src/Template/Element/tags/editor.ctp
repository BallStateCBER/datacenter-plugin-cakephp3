<?php
// This element has $availableTags and (optionally) $selectedTags passed into it

// Counters CakePHP's variable-renaming weirdness
if (! isset($availableTags)) {
    $availableTags = isset($availableTags) ? $availableTags : [];
}
if (! isset($selectedTags)) {
    $selectedTags = isset($selectedTags) ? $selectedTags : [];
}

if (!isset($hideLabel)) {
    $hideLabel = false;
}
echo $this->Html->script('/data_center/js/tag_manager.js', ['block' => true]);
echo $this->Html->css('/data_center/css/tag_editor.css');
echo $this->element('DataCenter.jquery_ui');
?>

<div class="input" id="tag_editing">
    <div id="available_tags_container">
        <div id="available_tags"></div>
        <div id="popular_tags"></div>
    </div>
    <div class="text-muted">
        Click <img src="/data_center/img/icons/menu-collapsed.png" /> to expand groups.
        Click
        <a href="#" title="Selectable tags will appear in blue" id="example_selectable_tag">selectable tags</a>
        to select them.
        <?php $this->append('buffered'); ?>
    <div class="input" id="tag_editing">
        <div id="available_tags_container" class="form-control">
            <div id="available_tags"></div>
            <div id="popular_tags"></div>
        </div>
        <div class="text-muted">
            Click <img src="/data_center/img/icons/menu-collapsed.png" /> to expand groups.
            Click
            <a href="#" title="Selectable tags will appear in blue" id="example_selectable_tag">selectable tags</a>
            to select them.
            <?php $this->append('buffered'); ?>
            $('#example_selectable_tag').tooltip().click(function(event) {
                event.preventDefault();
            });
            <?php $this->end(); ?>
        </div>

    <div id="selected_tags_container" style="display: none;">
        <span class="label">
            Selected tags:
        </span>
        <span id="selected_tags"></span>
        <div class="text-muted">
            Click on a tag to unselect it.
        </div>
    </div>

    <div id="custom_tag_input_wrapper">
        <label for="custom_tag_input">
            Additional Tags
            <span id="tag_autosuggest_loading" style="display: none;">
                <img src="/data_center/img/loading_small.gif" alt="Working..." title="Working..." style="vertical-align:top;" />
            </span>
        </label>
        <?php
            echo $this->Form->input('customTags', [
                'label' => false,
                'class' => 'form-control',
                'id' => 'custom_tag_input'
            ]);
        ?>
        <div class="text-muted">
            Write out tags, separated by commas. <a href="#new_tag_rules" data-toggle="collapse">Rules for creating new tags</a>
        </div>
        <div id="new_tag_rules" class="alert alert-info collapse">
            <p>
                Before entering new tags, please search for existing tags that meet your needs.
                Once you start typing, please select any appropriate suggestions that appear below the input field.
                Doing this will make it more likely that your event will be linked to popular tags that are viewed by more visitors.
            </p>

            <p>
                New tags must:
            </p>
            <ul>
                <li>
                    be short, general descriptions that people might search for, describing what will take place at the event
                </li>
                <li>
                    be general enough to also apply to other events (including events outside of a series)
                </li>
            </ul>

            <p>
                Must not:
            </p>
            <ul>
                <li>
                    include punctuation, such as dashes, commas, slashes, periods, etc.
                </li>
                <li>
                    include profanity, email addresses, or website addresses
                </li>
                <li>
                    be the name of the location (having this as a tag would be redundant, since people can already view events by location)
                </li>
            </ul>
        </div>
    </div>
</div>

<?php
    if (! isset($options)) {
        $options = [];
    }
    echo $this->Tag->setup($availableTags, $selectedTags, $options);
    if ($allow_custom) {
        $this->append('buffered');
        echo 'TagManager.setupCustomTagInput();';
        $this->end();
    }

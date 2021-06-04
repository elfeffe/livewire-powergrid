<?php


namespace PowerComponents\LivewirePowerGrid;

use PowerComponents\LivewirePowerGrid\UI\UI;

class Column
{
    public string $title = '';
    public string|bool $badge = false;
    public bool $searchable = true;
    public bool $sortable = false;
    public string $field = '';
    public string $header_class = '';
    public string $header_style = '';
    public string $body_class = '';
    public string $body_style = '';
    public bool $hidden = false;
    public bool $visible_in_export = true;
    public array $inputs = [];
    public bool $editable = false;
    public bool $html = false;
    public array $toggleable = [];
    public array|bool $actions = false;
    public bool $click_to_copy = false;
    public string $data_field = '';
    public int $width = 100;
    /**
     * @return static
     */
    public static function add()
    {
        return new static();
    }

    /**
     * Column title representing a field
     *
     * @param string $title
     * @return $this
     */
    public function title( string $title ): Column
    {
        $this->title = $title;
        return $this;
    }

    public function html()
    {
        $this->html = true;
        return $this;
    }

    public function width($width)
    {
        $this->width = $width;
        return $this;
    }

    public function badge($badge = 'success')
    {
        $this->badge = $badge;
        return $this;
    }

    public function actions($actions = [])
    {
        $this->field = 'actions_' . rand();
        $this->actions = $actions;
        return $this;
    }

    /**
     * Will enable the column for search
     *
     * @return $this
     */
    public function searchable(): Column
    {
        $this->searchable = true;
        return $this;
    }

    /**
     * Will enable the column for sort
     *
     * @return $this
     */
    public function sortable(): Column
    {
        $this->sortable = true;
        $this->html = false;
        return $this;
    }

    /**
     * Field name in the database
     *
     * @param string $field
     * @return $this
     */
    public function field( string $field ): Column
    {
        $this->field = $field;
        return $this;
    }

    /**
     * Class html tag header table
     *
     * @param string $class_attr
     * @param string $style_attr
     * @return $this
     */
    public function headerAttribute( string $class_attr = '', string $style_attr = '' ): Column
    {
        $this->header_class = $class_attr;
        $this->header_style = $style_attr;
        return $this;
    }

    /**
     * Class html tag body table
     *
     * @param string $class_attr
     * @param string $style_attr
     * @return $this
     */
    public function bodyAttribute( string $class_attr = '', string $style_attr = '' ): Column
    {
        $this->body_class = $class_attr;
        $this->body_style = $style_attr;
        return $this;
    }

    public function hidden(): Column
    {
        $this->hidden = true;
        return $this;
    }

    public function show(): bool
    {
        if($this->hidden)
        {
            return false;
        }

        if($this->actions && !$this->editable)
        {
            return false;
        }

        return true;
    }

    public function visibleInExport( bool $visible ): Column
    {
        $this->visible_in_export = $visible;
        $this->searchable = false;
        return $this;
    }

    /**
     * @param $data_source
     * @param string $display_field
     * @param string $relation_id
     * @param array $settings
     * @return $this
     */
    public function makeInputSelect( $data_source, string $display_field, string $relation_id, array $settings = [] ): Column
    {
        $this->editable = false;
        $this->inputs['select']['data_source'] = $data_source;
        $this->inputs['select']['display_field'] = $display_field;
        $this->inputs['select']['relation_id'] = $relation_id;
        $this->inputs['select']['class'] = $settings['class'] ?? '';
        $this->inputs['select']['live-search'] = $settings['live-search'] ?? true;
        return $this;
    }

    /**
     * @param $data_source
     * @param string $display_field
     * @param string $relation_id
     * @return $this
     */
    public function makeInputMultiSelect( $data_source, string $display_field, string $relation_id ): Column
    {
        $this->editable = false;
        $this->inputs['multi_select']['data_source'] = $data_source;
        $this->inputs['multi_select']['display_field'] = $display_field;
        $this->inputs['multi_select']['relation_id'] = $relation_id;
        $this->inputs['multi_select']['live-search'] = $settings['live-search'] ?? true;
        return $this;
    }

    /**
     * @param string $data_field
     * @param array $settings [
     * 'only_future' => true,
     * 'no_weekends' => true
     * ]
     * @param string $class_attr
     * @return Column
     */
    public function makeInputDatePicker( string $data_field, array $settings = [], string $class_attr = '' ): Column
    {
        $this->inputs['date_picker']['enabled'] = true;
        $this->inputs['date_picker']['class'] = $class_attr;
        $this->inputs['date_picker']['config'] = $settings;
        $this->data_field = $data_field;

        return $this;
    }

    /**
     * Adds Edit on click to a column
     *
     * @param bool $hasPermission
     * @return Column
     */
    public function editOnClick( bool $hasPermission = true ): Column
    {
        $this->editable = $hasPermission;
        return $this;
    }

    /**
     * Adds Toggle to a column
     *
     * @param bool $hasPermission
     * @param string $trueLabel Label for true
     * @param string $falseLabel Label for false
     * @return Column
     */
    public function toggleable( bool $hasPermission = true, string $trueLabel = 'Yes', $falseLabel = 'No'): Column
    {
        $this->editable = false;
        $this->toggleable = [
            'enabled' => $hasPermission,
            'default' => [$trueLabel,  $falseLabel]
        ];
        return $this;
    }

    /**
     * @param string $data_field
     * @param string $thousands
     * @param string $decimal
     * @return $this
     */
    public function makeInputRange( string $data_field = '', string $thousands = '', string $decimal = '' ): Column
    {
        $this->inputs['number']['enabled'] = true;
        $this->inputs['number']['decimal'] = $decimal;
        $this->inputs['number']['thousands'] = $thousands;
        $this->data_field = $data_field;
        return $this;
    }

    /**
     * @param string $data_field
     * @return $this
     */
    public function makeInputText( string $data_field = '' ): Column
    {
        $this->inputs['input_text']['enabled'] = true;
        $this->data_field = $data_field;
        return $this;
    }

    /**
     * @param bool $hasPermission
     * @return $this
     */
    public function clickToCopy( bool $hasPermission = true): Column
    {
        $this->click_to_copy = $hasPermission;
        return $this;
    }

    /**
     * @param string $data_field
     * @param string $trueLabel Label for true
     * @param string $falseLabel Label for false
     * @param array $settings Settings
     * @return $this
     */
    public function makeBooleanFilter(string $data_field = '' , string $trueLabel = 'Yes', $falseLabel = 'No', array $settings = []): Column
    {
        $this->inputs['boolean_filter']['enabled'] = true;
        $this->inputs['boolean_filter']['true_label'] = $trueLabel;
        $this->inputs['boolean_filter']['false_label'] = $falseLabel;
        $this->inputs['boolean_filter']['class'] = $settings['class'] ?? '';
        $this->inputs['boolean_filter']['live-search'] = $settings['live-search'] ?? true;
        $this->data_field = $data_field;
        return $this;
    }

}

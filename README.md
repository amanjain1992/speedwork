#Common snippets

###Change status with select

```html
<div class="ac-action-status" data-link="{speed link="index.php?option=orders&task=status&id={$row.id}"}">
    {html_options name="status[]" options=$status_list  selected=$row.status}
</div>
```

###Change status with link

```html
<div class="ac-action-status" data-link="{speed link="index.php?option=orders&task=status&id={$row.id}"}">
    {$row.status|status:1}
</div>
```

###Change status task in php

```php
    if ($task == 'status') {
        $id  = $this->get['id'];
        $res = $this->database->update('#__table', ['status' => $this->get['status']], ['id' => $id]);

        $status            = [];
        $status['status']  = ($res) ? 'OK' : 'ERROR';
        $status['message'] = ($res) ? trans('Status changed Successfully..') : trans('An error occured');

        return $status;
    }
```

###Delete Task

```php
    if ($task == 'delete') {
        $id  = $this->get['id'];
        $res = $this->database->delete('#__table', ['id' => $id]);

        $status            = [];
        $status['status']  = ($res) ? 'OK' : 'ERROR';
        $status['message'] = ($res) ? trans('Deleted Successfully..') : trans('An error occured');

        return $status;
    }
```

###Sorting task

```php
        $this->get('resolver')->widget('jui.sortable');
        $this->get('resolver')->widget('jui.draggable');
        $this->get('resolver')->widget('jui.droppable');
```

```html
<tbody  class="ac-ajax-content table_sortable_body">
    ....
    <td class="griddragdrop" align="center">
        <i class="fa fa-arrows-v fa-lg"></i>
        <input type="hidden" name="sorting[{$row.id}]" value ="{$row.ordering}">
    </td>
```

```php
        if ($task == 'sorting') {
            $sorting = $this->data['sorting'];
            $this->get('resolver')->helper('Speed')->sorting('#__table', $sorting, 'id');
        }
```
### Enable Ordering

```html
    <th data-order="name"><span>Name</span></th>
```

```php
    $order       = $this->get('resolver')->ordering($this->post, ['id DESC']);
```

### Listing details

```php
    $conditions  = $this->get('resolver')->conditions($this->post);
    $order       = $this->get('resolver')->ordering($this->post, ['id DESC']);

    $rows = $this->database->paginate('#__table', 'all', [
        'conditions' => $conditions,
        'order'      => $order,
        ]
    );

    $this->ajax('index.php?option=orders', $rows['total'], ['method' => 'post']);

    return [
        'rows'  => $rows,
    ];
```

### Add & Edit Details

```php
    $task   = $this->post['task'];
    $status = [];

    if ($task == 'save') {
        $id               = $this->post['id'];
        $save             = $this->post['data'];
        $save['modified'] = time();

        if ($id) {
            $res = $this->database->update('#__table', $save, ['id' => $id]);

            $status['status']  = ($res) ? 'OK' : 'ERROR';
            $status['message'] = ($res) ? trans('Updated Successfully') : trans('Some Error Occured');
        } else {
            $save['created']   = time();

            $speed = $this->get('resolver')->helper('Speed');

            $save['ordering']  = $speed->nextOrder('#__table');

            $res = $this->database->save('#__table', $save);

            $status['status']  = ($res) ? 'OK' : 'ERROR';
            $status['message'] = ($res) ? trans('Added Successfully') : trans('Some Error Occured');
        }

        return $status;
    }

    $id  = $this->get['id'];
    $row = [];
    if (isset($id)) {
        $row = $this->database->find('#__chat_rooms', 'first', [
            'conditions' => ['id' => $id],
            ]
        );
    }

    return [
        'row'    => $row,
    ];
```

### Autocomplete

Required widgets
```php
        $this->get('resolver')->widget('jui.autocomplete');
```

```html
<td>
    <input type="text" value="{$row.role}" data-auto="index.php?option=people&view=roles" placeholder="Role" size="20"/>
    <input type="hidden" name="data[person_role_id][]" class="ac-auto-value" required="required" value="{$row.person_role_id}"/>
</td>
```           

```php
        if ($this->get['task'] == 'auto') {
            $q = $this->get['q'];

            return $this->database->find("#__box_people_roles", 'all', [
                'fields' => ['role_id as id', 'role_title as label'],
                'conditions' => ['role_title LIKE ' => $q.'%'],
                'limit' => 10,
                ]
            );
        }
```

### Export Functionality

```html
    <a href="#" title="Export Products"  data-target="#dropdown" class="qtiph btn btn-primary" role="button"><i class="fa fa-cloud-download fa-lg"></i></a>
    <div id="dropdown" class="hidden">
        <ul class="list-group">
            <li class="list-group-item">
                <a href="{speed link="index.php?option=products&view=export&file=xls&format=raw"}"><i class="fa fa-lg fa-file-excel-o"></i> Excel</a>
            </li>
            <li class="list-group-item">
                <a href="{speed link="index.php?option=products&view=export&file=csv&format=raw"}"><i class="fa fa-lg fa-file-code-o"></i> CSV</a>
            </li>
            <li class="list-group-item">
                <a href="{speed link="index.php?option=products&view=export&file=txt&format=raw"}"><i class="fa fa-lg fa-file-o"></i> Text</a>
            </li>
        </ul>
    </div>
```

```php
    public function export()
    {
        //get Find iterator
        $iterator = $this->get('resolver')->helper('findIterator');

        $conditions  = $this->get('resolver')->conditions($this->get);

        $joins   = [];
        $joins[] = [
            'table'      => '#__ezo_companies',
            'alias'      => 'c',
            'type'       => 'LEFT',
            'conditions' => ['c.id = p.company_id'],
        ];

        $iterator->find(
            $this->database, [
                'table'      => '#__ezo_products',
                'type'       => 'all',
                'joins'      => $joins,
                'alias'      => 'p',
                'conditions' => $conditions,
                'fields'     => ['p.*', 'c.name as company'],
                'limit'      => 10000,
            ]
        );

        $categories = $this->products->listCategories();

        // Change the data with callback
        $callable = function ($row) use ($categories) {
            $row['category']  = $categories[$row['category_id']];

            return $row;
        };

        $reader = $this->get('resolver')->helper('reader');

        $iterator = $reader->modify($iterator, $callable);

        //get the stream helper
        $stream     = $this->get('resolver')->helper('stream');
        $filename   = 'products.'.$this->get['file'];

        $stream->start($filename);
        $fields = [
            'title'      => 'Title',
            'sku'        => 'SKU',
            'category'   => 'Category',
            'company'    => 'Company',
            'descn'      => 'Descn',
            'created'    => 'Created',
            'modified'   => 'Last Modified',
        ];

        $stream->output($iterator, $fields);
    }

if ($task == 'save') {
    $rows = $this->post['row'];
    $ids  = explode(',', $this->post['ids']);

    $speed = $this->get('resolver')->helper('Speed');
    $rows  = $speed->formatToSave($rows, 'degree,college');

    $exist = [];
    foreach ($rows as $save) {
        $id = $save['id'];
        unset($save['id']);

        if ($id) {
            $exist[] = $id;
            $this->database->update('#__therapy_doctor_education', $save, ['id' => $id]);
        } else {
            $save['created'] = time();
            $this->database->save('#__therapy_doctor_education', $save);
        }
    }

    $delete = array_diff($ids, $exist);
    if (!empty($delete)) {
        $this->database->delete('#__therapy_doctor_education', ['id' => $delete]);
    }

    $status['status']  = 'OK';
    $status['message'] = trans('Details Saved Successfully');

    return $status;
}

```php

public function saveTags($post_id, $list)
{
    if (empty($list)) {
        return true;
    }

    $ids    = explode(',', $list['old']);
    $delete = array_flip(array_filter($ids));

    $tags = explode(',', $list['new']);
    $map  = [];

    foreach ($tags as $tag) {
        $tag = trim($tag);

        if (substr($tag, 0, 3) == 'id:') {
            //is new map
            if (!isset($delete[$tag])) {
                $map[] = substr($tag, 3);
            } else {
                unset($delete[$tag]);
            }
        } else {
            $save             = [];
            $save['name']     = $tag;
            $save['slug']     = preg_replace('/[^a-zA-Z0-9]/', '-', strtolower($tag));
            $save['created']  = time();
            $save['taxonomy'] = 'post_tag';

            $this->database->save('#__blog_terms', $save);
            $map[] = $this->database->lastInsertId();
        }
    }

    // Delete the remaining
    $delete = array_keys($delete);

    if (!empty($delete)) {
        foreach ($delete as $id) {
            $this->database->delete('#__blog_post_terms', ['term_id' => substr($id, 3), 'post_id' => $post_id]);
        }
    }

    if (!empty($map)) {
        foreach ($map as $value) {
            $save            = [];
            $save['post_id'] = $post_id;
            $save['term_id'] = $value;

            $this->database->save('#__blog_post_terms', $save);
        }
    }
}

```

#save checkboxes in associate table
```php

    if ($task == 'lang') {
        $ids  = $this->post['ids'];
        $keys = $this->post['keys'];

        $delete = [];
        $save   = [];
        foreach ($keys as $key => $value) {
            if ($value && !isset($ids[$key])) {
                $delete[] = $key;
            }

            if (empty($value) && isset($ids[$key])) {
                $save[] = ['language_id' => $key, 'created' => time()];
            }
        }

        if (!empty($delete)) {
            $this->database->delete('#__therapy_user_languages', ['language_id' => $delete]);
        }

        if (!empty($save)) {
            $this->database->save('#__therapy_user_languages', $save);
        }

        $status['status']  = 'OK';
        $status['message'] = trans('Updated Successfully');

        return $status;
    }
```

#save selected items in associate table

```php

$this->model->saveLanguages([
    'ids'  => $this->model->getUserLangId($id),
    'keys' => $this->post['languages'],
]);

public function saveLanguages($list)
{
    //select
    $delete = $list['ids'];
    $keys   = $list['keys'];

    $ids  = [];
    $save = [];
    foreach ($keys as $key) {
        if (!isset($delete[$key])) {
            $ids[] = $key;
        } else {
            unset($delete[$key]);
        }
    }

    $delete = array_keys($delete);
    if (!empty($delete)) {
        $this->database->delete('#__therapy_user_languages', ['language_id' => $delete]);
    }

    if (!empty($ids)) {
        $save = [];
        foreach ($ids as $id) {
            $save[] = ['language_id' => $id, 'created' => time()];
        }
        $this->database->save('#__therapy_user_languages', $save);
    }
}
```
Contributing
------------

1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Make your changes
4. Run the tests, adding new ones for your own code if necessary (`phpunit`)
5. Commit your changes (`git commit -am 'Added some feature'`)
6. Push to the branch (`git push origin my-new-feature`)
7. Create new Pull Request

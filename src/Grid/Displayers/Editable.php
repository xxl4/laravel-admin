<?php

namespace Nicelizhi\Admin\Grid\Displayers;

use Nicelizhi\Admin\Admin;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Editable extends AbstractDisplayer
{
    /**
     * @var array
     */
    protected $arguments = [];

    /**
     * Type of editable.
     *
     * @var string
     */
    protected $type = '';

    /**
     * Options of editable function.
     *
     * @var array
     */
    protected $options = [
        'emptytext'  => '<i class="fa fa-pencil"></i>',
    ];

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Add options for editable.
     *
     * @param array $options
     */
    public function addOptions($options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * Add attributes for editable.
     *
     * @param array $attributes
     */
    public function addAttributes($attributes = [])
    {
        $this->attributes = array_merge($this->attributes, $attributes);
    }

    /**
     * Text type editable.
     */
    public function text()
    {
    }

    /**
     * Textarea type editable.
     */
    public function textarea()
    {
    }

    /**
     * Select type editable.
     *
     * @param array|\Closure $options
     */
    public function select($options = [])
    {
        $useClosure = false;

        if ($options instanceof \Closure) {
            $useClosure = true;
            $options = $options->call($this, $this->row);
        }

        $source = [];

        foreach ($options as $value => $text) {
            $source[] = compact('value', 'text');
        }

        if ($useClosure) {
            $this->addAttributes(['data-source' => json_encode($source)]);
        } else {
            $this->addOptions(compact('source'));
        }
    }

    public function select2($url) {
        $this->addOptions(compact('url'));
    }

    /**
     * Date type editable.
     */
    public function date()
    {
        $this->combodate();
    }

    /**
     * Datetime type editable.
     */
    public function datetime()
    {
        $this->combodate('YYYY-MM-DD HH:mm:ss');
    }

    /**
     * Year type editable.
     */
    public function year()
    {
        $this->combodate('YYYY');
    }

    /**
     * Month type editable.
     */
    public function month()
    {
        $this->combodate('MM');
    }

    /**
     * Day type editable.
     */
    public function day()
    {
        $this->combodate('DD');
    }

    /**
     * Time type editable.
     */
    public function time()
    {
        $this->combodate('HH:mm:ss');
    }

    /**
     * Combodate type editable.
     *
     * @param string $format
     */
    public function combodate($format = 'YYYY-MM-DD')
    {
        $this->type = 'combodate';

        $this->addOptions([
            'format'     => $format,
            'viewformat' => $format,
            'template'   => $format,
            'combodate'  => [
                'maxYear' => 2035,
            ],
        ]);
    }

    /**
     * @param array $arguments
     */
    protected function buildEditableOptions(array $arguments = [])
    {
        $this->type = Arr::get($arguments, 0, 'text');

        call_user_func_array([$this, $this->type], array_slice($arguments, 1));
    }

    /**
     * @return string
     */
    public function display()
    {
        $this->options['name'] = $column = $this->getName();
        $this->options['mode'] = "inline";
        //$this->options['onblur'] = "ignore";

        $class = 'grid-editable-'.str_replace(['.', '#', '[', ']'], '-', $column);

        $this->buildEditableOptions(func_get_args());

        $options = json_encode($this->options);

        $options = substr($options, 0, -1).<<<'STR'
    ,
    "success":function(response, newValue){
        if (response.status){
            $.admin.toastr.success(response.message, '', {positionClass:"toast-top-center"});
        } else {
            $.admin.toastr.error(response.message, '', {positionClass:"toast-top-center"});
        }
        if(response.refresh==true) {
            $.pjax.reload('#pjax-container');
        }
    }
}
STR;


        if($this->type=="select2") {      
            $html = "var select_url='".$this->options['url']."';";
            Admin::script($html);
            $options = json_encode($this->options);

            $options = substr($options, 0, -1).<<<'STR'
            ,
            "tpl": '<select></select>',
            "autotext": "always",
            "id": function (item) {
                return item.text;
            },
            "display": function(value, sourceData) {
                console.log(value);
                console.log(sourceData);
                $(this).html(value);
             },
            "select2": {
                "placeholder": 'Select',
                "width": '15em',
                "dropdownAutoWidth": true,
                "ajax": {
                    "url": select_url,
                    "dataType": "json",
                    "processResults": function (data) {
                        console.log(data)
                        return {
                            "results": data
                        };
                    },
                },
            },
            "formatResult": function (item) {
                console.log("formatResult")
                console.log(item);
                return item.text;
            },
            "formatSelection": function (item) {
                console.log("formatSelection")
                console.log(item);
                return item.text;
            },
            "initSelection": function (element, callback) {
                console.log("init")
                console.log(element);
                return $.get('/getById', { query: element.val() }, function (data) {
                    callback(data);
                });
            }
            }
        STR;
            Admin::script("$('.$class').editable($options);");
        }else{
            Admin::script("$('.$class').editable($options).on('shown', function(ev, editable) {
                setTimeout(function() {
                    editable.input.\$input.select();
                },0);
            });");
        }
        
        //$add_js = "$('.column-".$column."').find('input').select();";
        //Admin::script($add_js);

        // 针对与在 textare 显示的时候，可以使用substr 组件完成字符的截取与点开编辑后的查看处理
        $this->original = $this->type === 'textarea' ? $this->getColumn()->getOriginal() : $this->value; 
        //$this->original = $this->type === 'text' ? "" : $this->original;
        $this->original = htmlentities($this->original ?? '');
        $this->original = str_replace("'", '"', $this->original); // 在有“”
        


        $attributes = [
            'href'       => '#',
            'class'      => "$class",
            'id'         => "{$column}_{$this->getKey()}",
            'data-type'  => $this->type,
            'data-pk'    => "{$this->getKey()}",
            'data-url'   => "{$this->getResource()}/{$this->getKey()}",
            'data-value' => "{$this->original}",
        ];

        if (!empty($this->attributes)) {
            $attributes = array_merge($attributes, $this->attributes);
        }

        $attributes = collect($attributes)->map(function ($attribute, $name) {
            return "$name='$attribute'";
        })->implode(' ');

        $html = $this->type === 'select' ? '' : $this->value;

        return "<a $attributes>{$html}</a>";
    }
}

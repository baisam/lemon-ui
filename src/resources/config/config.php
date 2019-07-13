<?php

return [
    /**
     * 布局配置
     */
    'layouts' => [
        'app' => [
            'view' => 'ui::layouts.app',
            'navbar' => [
                'navbar' => [
                    'nav' => 'navigation',
                    'search' => 'form',
                    'menu' => 'navigation'
                ]
            ],
            'sidebar' => [
                'left' => [
                    'menu'
                ]
            ],
            'footer' => []
        ],
        'login' => [
            'view' => 'ui::layouts.login',
            'footer' => []
        ]
    ],

    'menus' => [
        // 'primary' => '主菜单'
    ],

    /*
     * File upload setting.
     */
    'uploads'  => [

        'default' => [
            'disk' => 'default', // return storage root path and url
            'root' => '{domain}',
            'path' => '{year}/{month}/{day}/{dispersion}',
            'allowFileTypes' => ['image'],
            'allowExtensions'=> ['jpg', 'png', 'gif'],
            'maxFileSize'    => 10737418240, // 10M
            'maxFileCount'   => 1,
            'uniqueName'     => false,
            'uploadUrl'      => '',
            'deleteUrl'      => '',
            'previewUrl'     => '',
            'downloadUrl'    => ''
        ],

        /*'image' => [],
        'video' => [],
        'audio' => []*/
    ],

    /**
     * 扩展表单字段类型
     */
    'fields' => [
        //'textarea' => \BaiSam\UI\Form\Field\Textarea::class
    ],

    /**
     * 扩展表格列渲染类型
     */
    'renders' => [
        // 'date' => \BaiSam\UI\Grid\Render\Date::class
    ],

    /**
     * 资源文件基本路径
     */
    'baseUrl' => '',

    /*
    |--------------------------------------------------------------------------
    | UI resource
    |--------------------------------------------------------------------------
    |
    | This is the resources used by ui of css and js file map.
    |
    */
    'resources' => [
        'icheck'      => ['css/icheck/minimal/blue.css', 'js/plugins/icheck.min.js'],
        'colorpicker' => ['css/plugins/bootstrap-colorpicker.css', 'js/plugins/bootstrap-colorpicker.js'],
        'inputmask'   => ['css/plugins/inputmask.css', 'js/plugins/inputmask/inputmask.js', 'js/plugins/inputmask/inputmask.extensions.js'],
        'inputmask.phone' => ['js/plugins/inputmask/inputmask.phone.extensions.js'],
        'inputmask.date' => ['js/plugins/inputmask/inputmask.date.extensions.js'],
        'inputmask.numeric' => ['js/plugins/inputmask/inputmask.numeric.extensions.js'],
        'inputmask.regex' => ['js/plugins/inputmask/inputmask.regex.extensions.js'],
        'inputmask.jquery' => ['js/plugins/inputmask/jquery.inputmask.js'],
        'maxlength'   => ['js/plugins/bootstrap-maxlength.js'],
        'moment'      => ['js/moment.js'],
        'lodash'      => ['js/lodash.js'],
        'datepicker'  => ['css/plugins/bootstrap-datepicker.css', 'js/plugins/bootstrap-datepicker.js'],
        'daterangepicker' => ['css/plugins/bootstrap-daterangepicker.css', 'js/plugins/bootstrap-daterangepicker.js'],
        'datetimepicker' => ['css/plugins/bootstrap-datetimepicker.css', 'js/plugins/bootstrap-datetimepicker.js'],
        'timepicker'  => ['css/plugins/bootstrap-timepicker.css', 'js/plugins/bootstrap-timepicker.js'],
        'ckeditor'    => ['ckeditor/ckeditor.js', 'ckeditor/config.js'],
        'kvsortable'  => ['js/plugins/bootstrap-fileinput/plugins/sortable.js'],
        'fileinput'   => ['css/plugins/bootstrap-fileinput.css', 'js/plugins/bootstrap-fileinput/fileinput.js', 'js/plugins/bootstrap-fileinput/plugins/piexif.js', 'js/plugins/bootstrap-fileinput/plugins/purify.js'],
        'numberinput' => ['js/plugins/bootstrap-input-spinner.js'],
        'select2'     => ['css/plugins/select2.css', 'js/plugins/select2.min.js'],
        'rangeSlider' => ['css/ion-rangeslider/ion.rangeSlider.css', 'css/ion-rangeslider/ion.rangeSlider.skinNice.css', 'js/plugins/ion.rangeSlider.min.js'],
        'slider'      => ['css/plugins/bootstrap-slider.css', 'js/plugins/bootstrap-slider.js'],
        'switch'      => ['css/plugins/bootstrap-switch.css', 'js/plugins/bootstrap-switch.js'],

        'smartWizard' => ['css/smartwizard/smart_wizard.css', 'js/plugins/jquery.smartWizard.js'],
        'smartWizard.arrows' => ['css/smartwizard/smart_wizard_theme_arrows.css'],
        'smartWizard.circles' => ['css/smartwizard/smart_wizard_theme_circles.css'],
        'smartWizard.dots' => ['css/smartwizard/smart_wizard_theme_dots.css'],

        'chart'       => ['js/plugins/chart.js'],
        'fullcalendar'=> ['css/plugins/fullcalendar.min.css', 'css/plugins/fullcalendar.print.min.css'],
        'jvectormap'  => ['css/plugins/jquery-jvectormap.css', 'js/plugins/jvectormap/jvectormap.js'],
        'morris'      => ['css/plugins/morris.css', 'js/plugins/morris.js'],
        'toastr'      => ['css/plugins/toastr.css', 'js/plugins/toastr.js'],
        'pace'        => ['js/plugins/pace.js'],
        'slimscroll'  => ['css/plugins/slimscroll.css', 'js/plugins/slimscroll.js'],
        'raphael'     => ['js/plugins/raphael.js'],

        'jquery.knob' => ['js/plugins/jquery.knob.js'],
        'jquery.mousewheel' => ['js/plugins/jquery.mousewheel.js'],
        'jquery.nestable' => ['js/plugins/jquery.nestable.js'],
        'jquery.slimscroll' => ['js/plugins/jquery.slimscroll.js'],
        'jquery.sparkline' => ['js/plugins/jquery.sparkline.js'],

        'datatable'   => ['css/dataTables/dataTables.bootstrap.min.css', 'js/dataTables/jquery.dataTables.js'],
        'datatable.responsive'=> ['datatable', 'css/dataTables/plugins/responsive.bootstrap.min.css', 'js/dataTables/plugins/dataTables.responsive.js'],
        'datatable.fixedColumns' => ['datatable', 'css/dataTables/plugins/fixedColumns.bootstrap.min.css', 'js/dataTables/plugins/dataTables.fixedColumns.js'],
        'datatable.fixedHeader' => ['datatable', 'css/dataTables/plugins/fixedHeader.bootstrap.min.css', 'js/dataTables/plugins/dataTables.fixedHeader.js'],
        'datatable.rowReorder' => ['datatable', 'css/dataTables/plugins/rowReorder.bootstrap.min.css', 'js/dataTables/plugins/dataTables.rowReorder.js'],
        'datatable.colReorder' => ['datatable', 'css/dataTables/plugins/colReorder.bootstrap.min.css', 'js/dataTables/plugins/dataTables.colReorder.js'],
        'datatable.keyTable' => ['datatable', 'css/dataTables/plugins/keyTable.bootstrap.min.css', 'js/dataTables/plugins/dataTables.keyTable.js'],
        'datatable.rowGroup' => ['datatable', 'css/dataTables/plugins/rowGroup.bootstrap.min.css', 'js/dataTables/plugins/dataTables.rowGroup.js'],
        'datatable.scroller' => ['datatable', 'css/dataTables/plugins/scroller.bootstrap.min.css', 'js/dataTables/plugins/dataTables.scroller.js'],
        'datatable.select' => ['datatable', 'css/dataTables/plugins/select.bootstrap.min.css', 'js/dataTables/plugins/dataTables.select.js'],

        'jeditable'   => ['js/plugins/jeditable/jquery.jeditable.js'],
        'jeditable.autogrow' => ['js/plugins/jeditable/jquery.jeditable.autogrow.js'],
        'jeditable.charcounter' => ['js/plugins/jeditable/jquery.jeditable.charcounter.js'],
        'jeditable.checkbox' => ['js/plugins/jeditable/jquery.jeditable.checkbox.js'],
        'jeditable.datepicker' => ['js/plugins/jeditable/jquery.jeditable.datepicker.js'],
        'jeditable.masked' => ['js/plugins/jeditable/jquery.jeditable.masked.js'],
        'jeditable.time' => ['js/plugins/jeditable/jquery.jeditable.time.js'],

        'form.checkbox'  => ['icheck'],
        'form.radio'     => ['icheck'],
        'form.file'      => ['fileinput'],
        'form.select'    => [],
        'form.input'     => ['inputmask'],
        'form.text'      => ['inputmask'],
        'form.textarea'  => ['maxlength'],
        'form.color'     => ['colorpicker'],
        'form.currency'  => ['inputmask'],
        'form.date'      => ['moment', 'datetimepicker'],
        'form.daterange' => ['form.date'],
        'form.datetime'  => [],
        'form.datetimerange'=> [],
        'form.decimal'   => ['inputmask', 'inputmask.numeric'],
        'form.editor'    => ['ckeditor'],
        'form.email'     => ['inputmask'],
        'form.icon'      => [],
        'form.image'     => ['fileinput', 'kvsortable'],
        'form.ip'        => ['inputmask'],
        'form.map'       => [],
        'form.mobile'    => ['inputmask', 'inputmask.phone'],
        'form.month'     => [],
        'form.number'    => ['numberinput', 'inputmask.numeric'],
        'form.rate'      => [],
        'form.slider'    => ['rangeSlider'],
        'form.switcher'  => ['switch'],
        'form.tags'      => ['select2'],
        'form.time'      => [],
        'form.timerange' => [],
        'form.url'       => ['inputmask'],
        'form.year'      => [],
        'grid'           => ['datatable', 'datatable.fixedHeader'],
        'grid.fixed'     => ['datatable.fixedColumns'],
        'grid.editable'  => ['jeditable']
    ],

    /**
     * 组件样式定义
     */
    'styles' => [
        'button' => [
            'size' => [
                \BaiSam\UI\UIRepository::STYLE_DEFAULT       => '',
                \BaiSam\UI\UIRepository::STYLE_SIZE_LARGE    => 'btn-lg',
                \BaiSam\UI\UIRepository::STYLE_SIZE_SMALL    => 'btn-sm',
                \BaiSam\UI\UIRepository::STYLE_SIZE_MINI     => 'btn-xs',
            ],
            'color' => [
                \BaiSam\UI\UIRepository::STYLE_DEFAULT       => 'btn-default',
                \BaiSam\UI\UIRepository::STYLE_COLOR_PRIMARY => 'btn-primary',
                \BaiSam\UI\UIRepository::STYLE_COLOR_SUCCESS => 'btn-success',
                \BaiSam\UI\UIRepository::STYLE_COLOR_INFO    => 'btn-info',
                \BaiSam\UI\UIRepository::STYLE_COLOR_WARNING => 'btn-warning',
                \BaiSam\UI\UIRepository::STYLE_COLOR_DANGER  => 'btn-danger',
                \BaiSam\UI\UIRepository::STYLE_COLOR_WHITE   => 'btn-white',
                \BaiSam\UI\UIRepository::STYLE_COLOR_LINK    => 'btn-link'
            ]
        ],
        'submit' => [
            'button'
        ],
        'checkbox' => [
            \BaiSam\UI\UIRepository::STYLE_INLINE => 'checkbox-inline',
            \BaiSam\UI\UIRepository::STYLE_CIRCLE => 'checkbox-circle',
            'color'  => [
                \BaiSam\UI\UIRepository::STYLE_DEFAULT   => '',
                \BaiSam\UI\UIRepository::STYLE_COLOR_PRIMARY   => 'checkbox-primary',
                \BaiSam\UI\UIRepository::STYLE_COLOR_SUCCESS   => 'checkbox-success',
                \BaiSam\UI\UIRepository::STYLE_COLOR_INFO      => 'checkbox-info',
                \BaiSam\UI\UIRepository::STYLE_COLOR_WARNING   => 'checkbox-warning',
                \BaiSam\UI\UIRepository::STYLE_COLOR_DANGER    => 'checkbox-danger'
            ]
        ],
        'radio' => [
            \BaiSam\UI\UIRepository::STYLE_INLINE => 'radio-inline',
            'color' => [
                \BaiSam\UI\UIRepository::STYLE_DEFAULT        => '',
                \BaiSam\UI\UIRepository::STYLE_COLOR_PRIMARY  => 'radio-primary',
                \BaiSam\UI\UIRepository::STYLE_COLOR_SUCCESS  => 'radio-success',
                \BaiSam\UI\UIRepository::STYLE_COLOR_INFO     => 'radio-info',
                \BaiSam\UI\UIRepository::STYLE_COLOR_WARNING  => 'radio-warning',
                \BaiSam\UI\UIRepository::STYLE_COLOR_DANGER   => 'radio-danger'
            ]
        ],
        'input' => [
            'size' => [
                \BaiSam\UI\UIRepository::STYLE_DEFAULT         => '',
                \BaiSam\UI\UIRepository::STYLE_SIZE_LARGE      => 'input-lg',
                \BaiSam\UI\UIRepository::STYLE_SIZE_SMALL      => 'input-sm'
            ]
        ],
        'progress' => [
            'size' => [
                \BaiSam\UI\UIRepository::STYLE_DEFAULT          => '',
                \BaiSam\UI\UIRepository::STYLE_SIZE_LARGE    => 'progress-lg',
                \BaiSam\UI\UIRepository::STYLE_SIZE_SMALL    => 'progress-sm',
                \BaiSam\UI\UIRepository::STYLE_SIZE_MINI     => 'progress-xs',
                \BaiSam\UI\UIRepository::STYLE_SIZE_TINY     => 'progress-xxs',
            ],
            'color' => [
                \BaiSam\UI\UIRepository::STYLE_DEFAULT        => '',
                \BaiSam\UI\UIRepository::STYLE_COLOR_PRIMARY  => 'progress-bar-primary',
                \BaiSam\UI\UIRepository::STYLE_COLOR_SUCCESS  => 'progress-bar-success',
                \BaiSam\UI\UIRepository::STYLE_COLOR_INFO     => 'progress-bar-info',
                \BaiSam\UI\UIRepository::STYLE_COLOR_WARNING  => 'progress-bar-warning',
                \BaiSam\UI\UIRepository::STYLE_COLOR_DANGER   => 'progress-bar-danger'
            ]
        ],
        'dialog' => [
            'size' => [
                \BaiSam\UI\UIRepository::STYLE_DEFAULT         => '',
                \BaiSam\UI\UIRepository::STYLE_SIZE_LARGE      => 'modal-lg',
                \BaiSam\UI\UIRepository::STYLE_SIZE_SMALL      => 'modal-sm'
            ],
            'color' => [
                \BaiSam\UI\UIRepository::STYLE_DEFAULT        => '',
                \BaiSam\UI\UIRepository::STYLE_COLOR_PRIMARY  => 'modal-primary',
                \BaiSam\UI\UIRepository::STYLE_COLOR_SUCCESS  => 'modal-success',
                \BaiSam\UI\UIRepository::STYLE_COLOR_INFO     => 'modal-info',
                \BaiSam\UI\UIRepository::STYLE_COLOR_WARNING  => 'modal-warning',
                \BaiSam\UI\UIRepository::STYLE_COLOR_DANGER   => 'modal-danger'
            ]
        ],
        'datasheet' => [
            'icon' => [
                'plus' => 'plus',
                'minus' => 'minus',
                'up' => 'arrow-up',
                'down' => 'arrow-down',
            ]
        ],
        'icon' => [
        ],
        'row' => [],
        'column' => [
            'width' => [

            ]
        ],
        'sidebar' => [

        ],
        'navbar' => [
            \BaiSam\UI\Layout\Component\Navbar::STYLE_TOP           => 'navbar-fixed-top',
            \BaiSam\UI\Layout\Component\Navbar::STYLE_BOTTOM        => 'navbar-fixed-bottom',
            \BaiSam\UI\Layout\Component\Navbar::STYLE_NAVIGATION    => 'navbar-nav',
            \BaiSam\UI\Layout\Component\Navbar::STYLE_FORM          => 'navbar-form',
            \BaiSam\UI\Layout\Component\Navbar::STYLE_BUTTON        => 'navbar-btn',
            \BaiSam\UI\Layout\Component\Navbar::STYLE_TEXT          => 'navbar-text'
        ],
        'nav' => [
            \BaiSam\UI\Layout\Component\Navigation::STYLE_JUSTIFIED => 'nav-justified',
            \BaiSam\UI\Layout\Component\Navigation::STYLE_PILLS     => 'nav-pills',
            \BaiSam\UI\Layout\Component\Navigation::STYLE_STACKED   => 'nav-stacked',
            \BaiSam\UI\Layout\Component\Navigation::STYLE_TABS      => 'nav-tabs'
        ],
        'custom' => []
    ]
];

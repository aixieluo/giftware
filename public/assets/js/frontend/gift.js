define(['jquery', 'bootstrap', 'frontend', 'form', 'template', 'table'], function ($, undefined, Frontend, Form, Template, Table) {
    var validatoroptions = {
        invalid: function (form, errors) {
            $.each(errors, function (i, j) {
                Layer.msg(j);
            });
        }
    };
    var Controller = {
        orders: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'gift/orders' + location.search,
                    table: 'order',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        // {field: 'id', title: __('ID')},
                        // {field: 'user_id', title: __('User_id')},
                        {field: 'sn', title: __('Sn'), operate: 'LIKE'},
                        // {field: 'tb_sn', title: __('Tb_sn'), operate: 'LIKE'},
                        // {field: 'pdd_sn', title: __('Pdd_sn'), operate: 'LIKE'},
                        {field: 'type', title: __('Type'), operate: 'LIKE'},
                        {field: 'courier', title: __('Courier'), operate: 'LIKE'},
                        {field: 'total', title: __('Total'), operate:'BETWEEN'},
                        {field: 'item', title: __('Item'), operate: 'LIKE'},
                        {field: 'recipient', title: __('Recipient'), operate: 'LIKE'},
                        {field: 'receipt_number', title: __('Receipt_number'), operate: 'LIKE'},
                        {field: 'receipt_address', title: __('Receipt_address'), operate: 'LIKE'},
                    ]
                ]
            });

            // 为表格绑定事件
            Form.api.bindevent($("form[role=form]"));
        },
    };
    return Controller;
});

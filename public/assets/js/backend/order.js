define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'order/index' + location.search,
                    add_url: 'order/add',
                    edit_url: 'order/edit',
                    del_url: 'order/del',
                    multi_url: 'order/multi',
                    import_url: 'order/import',
                    dadan_url: 'order/dadan',
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
                        {field: 'id', title: __('Id')},
                        {field: 'user_id', title: __('User_id')},
                        {field: 'real_sn', title: __('Sn'), operate: 'LIKE'},
                        // {field: 'tb_sn', title: __('Tb_sn'), operate: 'LIKE'},
                        // {field: 'pdd_sn', title: __('Pdd_sn'), operate: 'LIKE'},
                        {field: 'total', title: __('Total'), operate:'BETWEEN'},
                        {field: 'item', title: __('Item'), operate: 'LIKE'},
                        {field: 'recipient', title: __('Recipient'), operate: 'LIKE'},
                        {field: 'receipt_number', title: __('Receipt_number'), operate: 'LIKE'},
                        {field: 'receipt_address', title: __('Receipt_address'), operate: 'LIKE'},
                        {field: 'plattype_text', title: __('Type')},
                        {field: 'courier', title: __('Courier'), operate: 'LIKE'},
                        {field: 'courier_sn', title: '快递号'},
                        {field: 'uid', title: '快宝UID'},
                        {field: 'reason', title: '原因'},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            $('#btn-dadan').click(() => {
                $.ajax({
                    dataType:'json',
                    type:'post',
                    url:"/order/dadan",
                    success:function(res){
                        Toastr.success('已开始打单，请稍后...')
                    }
                })
            })

            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.bindevent()
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});

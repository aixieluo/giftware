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
                        {field: 'courier_sn', title: __('Courier_sn'), operate: 'LIKE'},
                        {field: 'courier', title: __('Courier'), operate: 'LIKE'},
                        // {field: 'tb_sn', title: __('Tb_sn'), operate: 'LIKE'},
                        // {field: 'pdd_sn', title: __('Pdd_sn'), operate: 'LIKE'},
                        {field: 'plattype_text', title: __('Type'), operate: 'LIKE'},
                        {field: 'total', title: __('Total'), operate: 'BETWEEN'},
                        {field: 'item', title: __('Item'), operate: 'LIKE'},
                        {field: 'recipient', title: __('Recipient'), operate: 'LIKE'},
                        {field: 'receipt_number', title: __('Receipt_number'), operate: 'LIKE'},
                        {field: 'receipt_address', title: __('Receipt_address'), operate: 'LIKE'},
                    ]
                ]
            });

            // 为表格绑定事件
            Controller.api.bindevent();
        },
        buy() {
            $('#tsid').change(function () {
                ptid = $('#tsid option:selected').attr('ptid')
                if(ptid==3){
                    var str='<label class="checkdiv"><input class="checkpt" name="type" type="radio" value="1" data-rule="checked">菜鸟单号（淘宝、天猫、1688）</label>';
                    str=str+'<label class="checkdiv"><input class="checkpt" name="type" type="radio" value="2" data-rule="checked">拼多多电子（拼多多、京东可用）</label>'
                    $("#platids").html(str);
                }else if(ptid==2){
                    var str='<label><input  name="type" type="radio" value="1" checked>菜鸟单号（淘宝、天猫、1688）</label>';
                    $("#platids").html(str);
                }else if (ptid==1){
                    var str='<label><input  name="type" type="radio" value="2" checked>拼多多电子（拼多多、京东可用）</label>';
                    $("#platids").html(str);
                }
            });
            $('#items').click(function () {
                did = $('#tsid').val()
                if (did <= 0) {
                    layer.msg('请先选择发货仓库！');
                    return;
                }
                Fast.api.open('/index/gift/items?depot_id='+did, '选择礼品', {
                    data: {
                        depot_id: did
                    },
                    callback(value) {
                        $('#items').html(`已选择 ${value.name} 单价：${value.price}`)
                        $('#sgid').val(value.id)
                        $('#platid').html(`<img src="${value.image}" style="width: 80px;height: 80px" />`)
                    }
                });
            })
            Controller.api.bindevent();
        },
        items() {
            console.log(location.search);
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'gift/items'+location.search,
                    table: 'gift',
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
                        {field: 'id', title: __('Id')},
                        {field: 'name', title: __('Name')},
                        {field: 'price', title: __('Price')},
                        {field: 'weight', title: __('Weight')},
                        {field: 'express_codes', title: 'code',visible: false, operate: 'LIKE'},
                        {
                            field: 'image',
                            title: __('Image'),
                            operate: false,
                            events: Table.api.events.image,
                            formatter: Table.api.formatter.image
                        },
                        {
                            field: 'operate', title: __('Operate'), table: table, buttons: [
                                {
                                    name: 'select',
                                    text: '选择',
                                    title: '选择',
                                    classname: 'btn btn-xs btn-success btn-run-amor',
                                }
                            ], events: Controller.events.operate, formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Controller.api.bindevent();
        },
        order() {
            //验证手动提交地址
            $(document).on("click", ".btn-dialog", function () {
                var adds =$("#addstext").val();
                var addts1 =adds.replace(/，/g,',');
                var addtext =addts1.replace(/^\s+|\s+$/g,'');
                var addtextarr= new Array();
                var adddan=new Array();
                addtextarr=addtext.split("\n");
                for(i=0;i<addtextarr.length;i++){
                    if(addtextarr[i]!=''){
                        if(addtextarr[i].indexOf(",") == -1){
                            alert("第"+(i+1)+"个收货地址【"+addtextarr[i]+"】收件人号码地址用逗号分开(姓名,号码,地址)");
                            return;
                        }
                        adddan=addtextarr[i].split(",");
                        addhao = adddan[1].replace(/ /g,'');
                        if(adddan.length!=3 && adddan.length!=4){layer.alert("第"+(i+1)+"个收货地址【"+addtextarr[i]+"】格式有错误，收件人号码地址用逗号分开(姓名,号码,地址)"); return; }
                        if(addhao.length!=11 && addhao.length!=14){layer.alert("第"+(i+1)+"个地址【"+addtextarr[i]+"】的手机号码格式不对，请仔细检查！"); return;}
                        var addr_char = $.trim(adddan[2]);
                        var address_arr = addr_char.split(' ');
                        if(address_arr.length<4){
                            layer.alert("第"+(Number(i)+Number(1))+" 个收货地址【"+addtextarr[i]+"】格式中省、市、区或县之间应该用空格隔开，请仔细检查！");
                            return;
                        }
                    }else{
                        layer.alert("第"+(i+1)+"个地址不能为空,请删除空数据");
                        return;
                    }
                }
                layer.alert('验证通过'+addtextarr.length+'个地址，请提交');
                $("#addstext").val(addtext);
                $('.btn-embossed').attr('disabled',false);
            });
            Controller.api.bindevent()
        },
        upload() {
            $("#faupload-image").data("upload-success", function (data) {
                var url = Fast.api.cdnurl(data.url);
                //  console.log('数组：', data);
                $("#excel-msg").html('已选择：'+url);
                // Toastr.success(__('Upload successful'));
            });
            Controller.api.bindevent()
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"), function (data, ret) {
                    setTimeout(function () {
                        if (ret.code == 302) {
                            location.href = ret.url
                        }
                    }, 1000)
                }, function (data, ret) {
                    setTimeout(function () {
                        if (ret.code == 302) {
                            location.href = ret.url
                        }
                    }, 1000)
                });
            }
        },
        events: {
            operate: {
                'click .btn-run-amor': function (e, value, row, index) {
                    e.stopPropagation();
                    Fast.api.close(row)
                }
            }
        }
    };
    return Controller;
});

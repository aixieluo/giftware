define(['jquery', 'bootstrap', 'frontend', 'form', 'template', 'table', 'clipboard'], function ($, undefined, Frontend, Form, Template, Table, ClipboardJS) {
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
                        {field: 'sn', title: '订单号', operate: 'LIKE', formatter: function (value) {
                                if (!value) {
                                    return value
                                } else {
                                    return "<span class='btn btn-xs btn-fuzhi btn-success' data-toggle='tooltip' title='"+value+"' >查看</span>"
                                }
                            }},
                        {field: 'courier_sn', title: __('Courier_sn'), operate: 'LIKE',formatter:function(value,row,index){
                                if(!value){
                                    return value
                                }else{
                                    return "<a href='https://www.baidu.com/s?ie=UTF-8&wd="+value+"' target='_blank' data-toggle='tooltip' data-original-title='点击查询物流记录'>"+value+"</a>  <a href='javascript:;'  data-clipboard-text='"+value+"' class='btn btn-xs btn-fuzhi btn-success' data-toggle='tooltip' title='复制："+value+"' >复制</a>";
                                }}},
                        {field: 'courier', title: __('Courier'), operate: 'LIKE'},
                        // {field: 'pdd_sn', title: __('Pdd_sn'), operate: 'LIKE'},
                        {field: 'plattype_text', title: __('Type'), searchable:false},
                        {field: 'total', title: __('Total'), operate: 'BETWEEN'},
                        {field: 'item', title: __('Item'), operate: 'LIKE'},
                        {field: 'recipient', title: __('Recipient'), operate: 'LIKE'},
                        {field: 'receipt_number', title: __('Receipt_number'), operate: 'LIKE'},
                        {field: 'receipt_address', title: __('Receipt_address'), operate: 'LIKE'},
                        {field: 'reason', title: '原因', searchable:false, formatter: function (value) {
                                if (!value) {
                                    return value
                                } else {
                                    return "<span class='btn btn-xs btn-fuzhi btn-success' data-toggle='tooltip' title='"+value+"' >查看</span>"
                                }
                            }},
                    ]
                ]
            });
            var clipboard = new ClipboardJS('.btn-fuzhi');
            clipboard.on('success', function(e) {
                Toastr.success('复制成功');
            });

            clipboard.on('error', function(e) {
                Toastr.error('复制失败');
            });


            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.bindevent();
        },
        buy() {
            Controller.api.selectpt();
            $('#tsid').change(function () {
                ptid = $('#tsid option:selected').attr('ptid')
                Controller.api.pintai(ptid)
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
                exportDataType: 'selected',
                search:false,
                showToggle: false,
                showColumns: false,
                showExport: false,
                columns: [
                    [
                        {field: 'id', title: __('Id')},
                        {field: 'name', title: __('Name')},
                        {field: 'price', title: __('Price')},
                        {field: 'weight', title: __('Weight')},
                        {
                            field: 'image',
                            title: __('Image'),
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
            $(function () {
                var price = $('.price').html();//商品单价
                var total_price = $('.total-price');//总价 包含快递费
                var goods_price = $('.goods-price');//商品总价 不包含快递费
                $("input[name='depot_id'].depot").click(function(event){
                    event.stopPropagation();
                    var depot_id = $(this).val();
                    $.ajax({
                        data:{depot_id:depot_id},
                        dataType:'json',
                        type:'post',
                        url:"/index/gift/express_price",
                        success:function(res){
                            $('.express_price').html(res.express_price);
                            total_price.html((Number(price*1)+Number(res.express_price)).toFixed(2));
                            goods_price.html((price*1).toFixed(2));
                            Controller.api.pintai(res.ptid)
                        }
                    })
                })
            })
            //验证手动提交地址
            $(document).on("click", ".btn-dialog", function () {
                var adds =$("#addstext").val();
                var addts1 =adds.replace(/，/g,',');
                var addtext =addts1.replace(/^\s+|\s+$/g,'');
                var addtextarr= new Array();
                var adddan=new Array();
                addtextarr=addtext.split("\n");
                for(i=0;i<addtextarr.length;i++){
                    if (addtextarr[i].split(',').length != 4) {
                        layer.alert("第"+(i+1)+"个地址【"+addtextarr[i]+"】缺少信息，请仔细检查是否用\"，\"分隔好订单编号，姓名，手机，地址！")
                        return;
                    }
                    if(addtextarr[i]!=''){
                        if(addtextarr[i].indexOf(",") == -1){
                            alert("第"+(i+1)+"个收货地址【"+addtextarr[i]+"】收件人号码地址用逗号分开(姓名,号码,地址)");
                            return;
                        }
                        adddan=addtextarr[i].split(",");
                        addhao = adddan[2].replace(/ /g,'');
                        if(adddan.length!=3 && adddan.length!=4){layer.alert("第"+(i+1)+"个收货地址【"+addtextarr[i]+"】格式有错误，收件人号码地址用逗号分开(姓名,号码,地址)"); return; }
                        if(addhao.length!=11 && addhao.length!=14){layer.alert("第"+(i+1)+"个地址【"+addtextarr[i]+"】的手机号码格式不对，请仔细检查！"); return;}
                        var addr_char = $.trim(adddan[3]);
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
            pintai: function(ptid) {
                if(ptid==3){
                    var str='<label class="col-sm-6 checkdiv"><input class="checkpt" name="type" type="radio" value="1" data-rule="checked"><img src="/assets/img/tb.png"> 菜鸟单号（淘宝、天猫、1688）</label>';
                    str=str+'<label class="col-sm-6 checkdiv"><input class="checkpt" name="type" type="radio" value="2" data-rule="checked"><img src="/assets/img/pdd.gif"> 拼多多单号（拼多多、京东可用）</label>'
                    $("#platids").html(str);
                }else if(ptid==2){
                    var str='<label class="col-sm-6 checkdiv"><input  name="type" type="radio" value="1" checked><img src="/assets/img/tb.png"> 菜鸟单号（淘宝、天猫、1688）</label>';
                    $("#platids").html(str);
                }else if (ptid==1){
                    var str='<label class="col-sm-6 checkdiv"><input  name="type" type="radio" value="2" checked><img src="/assets/img/pdd.gif"> 拼多多单号（拼多多、京东可用）</label>';
                    $("#platids").html(str);
                }
            },
            selectpt: function () {
                ptid = $('#tsid option:selected').attr('ptid')
                Controller.api.pintai(ptid)
            },
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

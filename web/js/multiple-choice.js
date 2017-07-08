/**
 * Created by jiangcoco on 2017/4/12.
 */

var questionShowModal = $('#question_show_modal'),
    addSingleModal = $('#add_single_modal'),
    addSingleForm = $('#add_single_form'),
    questionEditModal = $('#question_edit_modal'),
    questionEditForm = $('#question_edit_form'),
    addUrl = "/admin/questions/add",
    getOneUrl = "/admin/questions/one",
    removeUrl = "/admin/questions/remove",
    updateUrl = "/admin/questions/update";

$(function () {
    var question_type = $('#question_type').text();
    $('#question_nav a').each(function(){
       if($(this).text() == question_type){
           $(this).addClass('btn-primary');
       }
    });
    //加载编辑器
    CKEDITOR.replace('question_title',{toolbar:'Short',height:'100px'});
    CKEDITOR.replace('question_edit_title',{toolbar:'Short',height:'100px'});
    CKEDITOR.replace('question_answer_1',{toolbar:'Short',height:'80px'});
    CKEDITOR.replace('question_answer_2',{toolbar:'Short',height:'80px'});
    CKEDITOR.replace('question_answer_3',{toolbar:'Short',height:'80px'});
    CKEDITOR.replace('question_answer_4',{toolbar:'Short',height:'80px'});

    //显示
    questionShowModal.on('show.bs.modal', function (e) {
        $('#question_show_answers').empty();
        $.ajax({
            url: getOneUrl,
            type: "post",
            dataType: "json",
            data: {
                'id':e.relatedTarget.name,
            },
            beforeSend : function ()
            {
                $('#loading_data').show()
            },
            success: function (data) {
                $('#loading_data').hide();
                $('#question_show_title').html(data.title + '<hr>');
                for(var i=0;i<data.question_answer.length;i++){
                    var flag = String.fromCharCode(64 + parseInt(i+1));
                    var q = '<div>'+flag+'.'+data.question_answer[i]+'</div>';
                    $('#question_show_answers').append(q);
                }
                $('#question_answer').html('<strong>正确答案:</strong>【'+data.answer+'】');

            },
            error: function (data) {
                $('#loading').modal('hide');
                toastr.error('code 500 未知错误');
            }
        })
    })
    //删除按钮
    $("button[class='btn btn-white btn-sm question_remove_btn']").click(function (e) {
        swal({
            title: "您确定要删除这条信息吗",
            text: "删除后将无法恢复，请谨慎操作！",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "删除",
            closeOnConfirm: true,
        }, function () {
            $.ajax({
                url: removeUrl,
                type: "post",
                dataType: "json",
                data: {
                    'id':e.target.name,
                },
                beforeSend : function ()
                {
                    $('#loading').modal('show');
                },
                success: function (data) {
                    $('#loading').modal('hide');
                    if(data.code === 0){
                        toastr.error(data.message);
                    }else if(data.code === 1){
                        toastr.success(data.message);
                        location.reload();
                    }else{
                        toastr.error('code 501 未知错误');
                    }
                },
                error: function (data) {
                    $('#loading').modal('hide');
                    toastr.error('code 500 未知错误');
                }
            })
        })

    })
   //获取one数据
    function getOne(id) {
        $('#question_edit_answers_content').empty();
        $.ajax({
            url: getOneUrl,
            type: "post",
            dataType: "json",
            data: {
                'id':id,
            },
            beforeSend : function ()
            {
                $('#loading').modal('show');
            },
            success: function (data) {
                $('#loading').modal('hide');
                if(data){
                    $(":radio[name='question_edit_level'][value='" + data.level + "']").prop("checked", "checked");
                    CKEDITOR.instances.question_edit_title.setData(data.title);
                    for (var i = 1; i<=data.question_answer.length; i++){
                        var alphabet= String.fromCharCode(64 + parseInt(i));
                        var ckid = 'question_answer_edit'+i;
                        var html =' <div class="form-group">'+
                            '<textarea id='+ckid+' cols="20" rows="1" class="ckeditor" placeholder="请输入选项'+alphabet+'..." ></textarea>'+
                            '<label style="font-size: 16px;color: #0a6aa1;">'+
                            '<input type="checkbox" name="question_edit_answer" id="'+alphabet+'"  value="'+alphabet+'">【选项'+alphabet+'】 </label></div>';
                        $(html).appendTo('#question_edit_answers_content');
                        CKEDITOR.replace(ckid,{toolbar:'Short',height:'80px'});
                        CKEDITOR.instances[ckid].setData(data.question_answer[i-1]);
                    }
                    for(var i = 0; i<=data.answer.length; i++)
                    {
                        $(":checkbox[name='question_edit_answer'][value='" + data.answer[i] + "']").prop("checked", "checked");
                    }

                } else{
                    toastr.error('code 501 获取数据失败');
                }
            },
            error: function (data) {
                $('#loading').modal('hide');
                toastr.error('code 500 未知错误');
            }
        })
    }
    //编辑按钮
    questionEditModal.on('show.bs.modal', function (e) {
        getOne(e.relatedTarget.name);
        var anwserArray =[];
        questionEditForm.validate({
            submitHandler: function (form) {
                //检测复选框必须大于2个选项
                if($('input:checkbox[name=question_edit_answer]:checked').length > 1) {
                    var checkboxArray=new Array();
                    $('input:checkbox[name=question_edit_answer]:checked').each(function(){
                        checkboxArray.push($(this).val());//向数组中添加元素
                    });
                }else{
                    toastr.error('警告：答案个数必须>=2！');
                    return false;
                }
                if (CKEDITOR.instances.question_edit_title.getData() == '') {
                    toastr.error('警告：内容不得为空！');
                    CKEDITOR.instances.question_edit_title.focus();
                    return false;
                }
                var answersNum = $('#question_edit_answers_content').children().length;
                for(var i =1;i<=answersNum;i++){
                    var ckname = 'question_answer_edit' + i;
                    if (CKEDITOR.instances[ckname].getData() == '') {
                        toastr.error('警告：内容不得为空！');
                        CKEDITOR.instances[ckname].focus();
                        return false;
                    }else{
                        anwserArray.push(CKEDITOR.instances[ckname].getData());
                    }
                }
                //console.log(anwserArray);
                $.ajax({
                    url: updateUrl,
                    type: "post",
                    dataType: "json",
                    data: {
                        'id':e.relatedTarget.name,
                        'title': CKEDITOR.instances.question_edit_title.getData(),
                        'question_level': $("input[name='question_edit_level']:checked").val(),
                        'answer': checkboxArray,
                        'question_answer': anwserArray
                    },
                    beforeSend : function ()
                    {
                        $('#loading').modal('show');
                    },
                    success: function (data) {
                        $('#loading').modal('hide');
                        if(data.code === 0){
                            toastr.error(data.message);
                        }else if(data.code === 1){
                            toastr.success(data.message);
                            //关闭窗口
                            questionEditForm.modal('hide');
                            location.reload();
                        }else{
                            toastr.error('code 501 未知错误');
                        }
                    },
                    error: function (data) {
                        $('#loading').modal('hide');
                        toastr.error('code 500 未知错误');
                    }
                })
            }
        })

    })
   //添加更多
    $('#more_options').click(function () {
        var flag = $('#answer_options').children().length+1;
        var alphabet= String.fromCharCode(64 + parseInt(flag));
        var ckid = 'question_answer_'+flag;
        var html =' <div class="form-group">'+
            '<textarea id='+ckid+' cols="20" rows="1" class="ckeditor" placeholder="请输入选项'+alphabet+'..." ></textarea>'+
            '<label  style="font-size: 16px;color: #0a6aa1;">'+
            '<input type="checkbox" name="question_answer" id="'+alphabet+'"  value="'+alphabet+'"> 【选项'+alphabet+'】 </label></div>';
        $(html).appendTo('#answer_options');
        CKEDITOR.replace(ckid,{toolbar:'Short',height:'80px'});
    })
    //保存按钮
    addSingleModal.on('show.bs.modal', function (e) {
        var anwserArray =[];
        addSingleForm.validate({
            submitHandler: function (form) {
                //检测复选框必须大于2个选项
                if($('input:checkbox[name=question_answer]:checked').length > 1) {
                    var checkboxArray=new Array();
                    $('input:checkbox[name=question_answer]:checked').each(function(){
                        checkboxArray.push($(this).val());//向数组中添加元素
                    });
                }else{
                    toastr.error('警告：答案个数必须>=2！');
                    return false;
                }
                if (CKEDITOR.instances.question_title.getData() == '') {
                    toastr.error('警告：内容不得为空！');
                    CKEDITOR.instances.question_title.focus();
                    return false;
                }
                var answersNum = $('#answer_options').children().length;
                for(var i =1;i<=answersNum;i++){
                    var ckname = 'question_answer_' + i;
                    if (CKEDITOR.instances[ckname].getData() == '') {
                        toastr.error('警告：内容不得为空！');
                        CKEDITOR.instances[ckname].focus();
                        return false;
                    }else{
                        anwserArray.push(CKEDITOR.instances[ckname].getData());
                    }
                }
                //console.log(anwserArray);
                $.ajax({
                    url: addUrl,
                    type: "post",
                    dataType: "json",
                    data: {
                        'id':e.relatedTarget.name,
                        'title': CKEDITOR.instances.question_title.getData(),
                        'question_level': $("input[name='question_level']:checked").val(),
                        'answer': checkboxArray,
                        'type':1,
                        'question_answer': anwserArray
                    },
                    beforeSend : function ()
                    {
                        $('#loading').modal('show');
                    },
                    success: function (data) {
                        $('#loading').modal('hide');
                        if(data.code === 0){
                            toastr.error(data.message);
                        }else if(data.code === 1){
                            toastr.success(data.message);
                            //关闭窗口
                            addSingleForm.modal('hide');
                            location.reload();

                        }else{
                            toastr.error('code 501 未知错误');
                        }
                    },
                    error: function (data) {
                        $('#loading').modal('hide');
                        toastr.error('code 500 未知错误');
                    }
                })
            }
        })

    })

        //刷新
        $('#refresh').click(function () {
            location.reload();
        })
})
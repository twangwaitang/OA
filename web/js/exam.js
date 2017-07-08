/**
 * Created by jiangcoco on 2017/4/12.
 */
var table =  $('#exams'),
    addForm = $('#add_exam_form'),
    editExamModal = $('#edit_exam_modal'),
    editExamForm = $('#edit_exam_form'),
    selectQuestionsModal = $('#select_questions_modal'),
    addQuestionToExamBtn = $('#add_question_to_exam_btn'),
    examShowModal = $('#exam_show_modal'),
    submitForm = $('#submit_form'),
    questionToExamUrl = '/admin/exam/add_question_to_exam',
    getQuestionsUrl = '/admin/exam/questions',
    listUrl = '/admin/questions/list',
    addUrl = "/admin/exam/add",
    removeUrl = "/admin/exam/remove",
    updateUrl = "/admin/exam/update",
    checkUrl = "exam/check",
    getOneUrl =  '/admin/exam/one';


$(function () {
    var start = {
        elem: "#start_date_time",
        format: "YYYY/MM/DD hh:mm:ss",
        min: laydate.now(),
        max: "2099-06-16 23:59:59",
        istime: true,
        istoday: false,
        choose: function (datas) {

        }
    };
    var end = {
        elem: "#end_date_time",
        format: "YYYY/MM/DD hh:mm:ss",
        min: laydate.now(),
        max: "2099-06-16 23:59:59",
        istime: true,
        istoday: false,
        choose: function (datas) {

        }
    };
    var edit_start = {
        elem: "#edit_start_date_time",
        format: "YYYY/MM/DD hh:mm:ss",
        min: laydate.now(),
        max: "2099-06-16 23:59:59",
        istime: true,
        istoday: false,
        choose: function (datas) {

        }
    };
    var edit_end = {
        elem: "#edit_end_date_time",
        format: "YYYY/MM/DD hh:mm:ss",
        min: laydate.now(),
        max: "2099-06-16 23:59:59",
        istime: true,
        istoday: false,
        choose: function (datas) {

        }
    };
    laydate(start);
    laydate(end);
    laydate(edit_start);
    laydate(edit_end);

    CKEDITOR.replace('exam_info',{toolbar:'Base',height:'100px'});
    CKEDITOR.replace('edit_exam_info',{toolbar:'Base',height:'100px'});



    //所有习题库数据
    $('#single_chioce').click(function (e) {
        table.bootstrapTable('refresh',{query: {'courseId': e.target.name, 'type': 0}});
    })
    $('#multiple_chioce').click(function (e) {
        table.bootstrapTable('refresh',{query: {'courseId': e.target.name, 'type': 1}});
    })
    $('#true_or_false').click(function (e) {
        table.bootstrapTable('refresh',{query: {'courseId': e.target.name, 'type': 3}});
    })
    //加载习题
    selectQuestionsModal.on('show.bs.modal', function (e) {
        addQuestionToExamBtn.attr('name',$(e.relatedTarget).data("examid"));
        table.bootstrapTable({
            url: listUrl,
            method:'POST',
            contentType: 'application/x-www-form-urlencoded',
            queryParams:{
                'courseId': e.relatedTarget.name,
                'type': 0
            },
            striped: true,
            sortStable: true,
            clickToSelect: true,
            pagination: true, //分页
            pageNumber:1,                       //初始化加载第一页，默认第一页
            pageSize: 10,                       //每页的记录行数（*）
            pageList: [10, 25, 50, 100],
            columns: [{
                checkbox:true,
            },{
                field: 'id',
                title: '编号',
                formatter: function (value, row,index) {
                    return '<a href="javascript:void(0)">'+(index+1)+'</a>';
                }
            },
                {
                    field: 'title',
                    title: '题目',
                },
                {
                    field: 'level',
                    title: '难度',
                    sortable:true,
                    formatter: function (value, row,index) {
                        if(value == 0) return '简单';
                        if(value == 1) return '中等';
                        if(value == 2) return '困难';
                    }
                }
            ],

        });
    })
    //添加习题保存按钮
    addQuestionToExamBtn.click(function (e) {
        var rows = table.bootstrapTable('getSelections');
        if(rows.length == 0)  return toastr.error('请选择数据');
        var exam_id = e.target.name;
        $.ajax({
            url: questionToExamUrl,
            type: "post",
            dataType: "json",
            data: {
                'exam_id':exam_id,
                'rows':rows,
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
                    selectQuestionsModal.modal('hide');
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
    //显示试卷
    examShowModal.on('show.bs.modal', function (e){

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
                //$('#exam_show').empty();
                $('#loading_data').hide();
                $('.exam_title h1').html(data.title);
                $('.exam_info h4').html(data.info);
                $('.exam_duration h4').html('考试时间:'+data.duration+'分钟');
                for(var i=0;i<data.questions.length;i++){
                    var question_title = '<div>'+data.questions[i].title+'</div>';
                    $('.exam_single').append(question_title);
                    for(var j=0;j<data.questions[i].question_answer.length;j++){
                        var flag = String.fromCharCode(64 + parseInt(j+1));
                        var q = '<div>'+flag+'.'+data.questions[i].question_answer[j]+'</div>';
                        $('.exam_single').append(q);
                    }
                    var answer = '<strong>正确答案:</strong>【'+data.questions[i].answer+'】<hr>';
                    $('.exam_single').append(answer);
                }
            },
            error: function (data) {
                $('#loading').modal('hide');
                toastr.error('code 500 未知错误');
            }
        })
        $('#exam_show').html()
    })
    // 新增
    addForm.validate({
        submitHandler: function (form) {
            var param = $(form).serialize() + '&info=' + CKEDITOR.instances.exam_info.getData();
            $.ajax({
                url: addUrl,
                type: "post",
                dataType: "json",
                data: param,
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
                        $('#add_exam_modal').modal('hide');
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
    //编辑按钮
    editExamModal.on('show.bs.modal', function (e) {
        getOne(e.relatedTarget.name);
        editExamForm.validate({
            submitHandler: function (form) {
                var param = $(form).serialize() + '&info=' + CKEDITOR.instances.edit_exam_info.getData()+'&id='+e.relatedTarget.name;
                $.ajax({
                    url: updateUrl,
                    type: "post",
                    dataType: "json",
                    data: param,
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
                            editExamModal.modal('hide');
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
    function getOne(id) {
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
                    $('#edit_exam_name').val(data.title);
                    $('#edit_start_date_time').val(data.startTime);
                    $('#edit_end_date_time').val(data.endTime);
                    $('#edit_exam_duration').val(data.duration);
                    $('#edit_single_score').val(data.scoreRate[0]);
                    $('#edit_multiple_score').val(data.scoreRate[1]);
                    $('#edit_check_score').val(data.scoreRate[2]);
                    CKEDITOR.instances.edit_exam_info.setData(data.info);
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
    //删除按钮
    $("button[class='btn btn-white btn-sm exam_remove_btn']").click(function (e) {
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
    //刷新
    $('#refresh').click(function () {
        location.reload();
    })
})
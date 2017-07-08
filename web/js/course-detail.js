
var
    table                   =  $('#questions'),
    showCourseBtn           =  $('#show_course_btn'),
    addMoreLessonsBtn       = $('#add_more_lessons'),
    addVideoLinkBtn         = $('#add_video_link_btn'),
    questionAddBtn          = $('#question_add_btn'),
    chapterAddForm          = $('#chapter_add_form'),
    chapterEditForm         = $('#chapter_edit_form'),
    lessonEditForm          = $('#lesson_edit_form'),
    addVideoQuestionForm    = $('#add_video_question_form'),
    lessonAddVideoLinkForm  = $('#lesson_add_video_link_form'),
    lessonAddForm           = $('#lesson_add_form'),
    lessonResForm           = $('#lesson_res_form'),
    chapterAddModal         = $('#chapter_add_modal'),
    lessonResModal          = $('#lesson_res_modal'),
    lessonAddModal          = $('#lesson_add_modal'),
    lessonResModal          = $('#lesson_res_modal'),
    addVideoQuestionModal   = $('#add_video_question_modal'),
    showCourseModal         = $('#show_course_modal'),
    lessonAddVideoLinkModal = $('#lesson_add_video_link_modal'),
    lessAddVideoModal       = $('#lesson_add_video_modal'),
    questionAddModal        = $('#question_add_modal'),
    getOneUrl               = '/admin/course/one',
    addUrl = "/admin/chapter/add",
    addLessonUrl = "/admin/lesson/add",
    getOneUrl = "/admin/lesson/one",
    removeUrl = "/admin/chapter/remove",
    removeLessonUrl = "/admin/lesson/remove",
    updateUrl = "/admin/chapter/update",
    updateLessonUrl = "/admin/lesson/update",
    addWebLessonUrl= "/admin/lesson/addwebvideo",
    addMakersUrl = "/admin/makers/add",
    resUrl = "/admin/lesson/res",
    uploadUrl= "/admin/lesson/upload";

$(function () {
    // var $modalElement = this.$element;
    // $(document).on('focusin.modal', function (e) {
    //     var $parent = $(e.target.parentNode);
    //     if ($modalElement[0] !== e.target && !$modalElement.has(e.target).length
    //         // add whatever conditions you need here:
    //         &&
    //         !$parent.hasClass('cke_dialog_ui_input_select') && !$parent.hasClass('cke_dialog_ui_input_text')) {
    //         $modalElement.focus()
    //     }
    // })
    //加载编辑器
    CKEDITOR.replace('res',{toolbar:'Res',height:'200px'});
    function getOne($id) {
        $.ajax({
            url: getOneUrl,
            type: "post",
            dataType: "json",
            data: {
                'id':$id,
            },
            beforeSend : function ()
            {
                $('#loading').modal('show');
            },
            success: function (data) {
                $('#loading').modal('hide');
                CKEDITOR.instances.res.setData(data.res);
            },
            error: function (data) {
                $('#loading').modal('hide');
                toastr.error('code 500 未知错误');
            }
        })

    }
    //资源按钮
    lessonResModal.on('show.bs.modal', function (e) {
        getOne(e.relatedTarget.id);
        lessonResForm.validate({
            submitHandler: function () {
                if (CKEDITOR.instances.res.getData() == '') {
                    toastr.error('警告：内容不得为空！');
                    CKEDITOR.instances.res.focus();
                    return false;
                }
                $.ajax({
                    url: resUrl,
                    type: "post",
                    dataType: "json",
                    data: {
                        'id':e.relatedTarget.id,
                        'res': CKEDITOR.instances.res.getData()
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
                            lessonResModal.modal('hide');
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
    // 新增章
    chapterAddForm.validate({
        submitHandler: function (form) {
            var param = $(form).serialize();
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
                        //关闭窗口
                        chapterAddForm.modal('hide');
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
    // 新增网络视屏
    lessonAddVideoLinkModal.on('show.bs.modal', function (e) {
        $('#add_video_link_lesson_id').val(e.relatedTarget.name);
        lessonAddVideoLinkForm.validate({
            submitHandler: function (form) {
                var param = $(form).serialize();
                $.ajax({
                    url: addWebLessonUrl,
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
                            //关闭窗口
                            lessonAddForm.modal('hide');
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
    // 新增节
    lessonAddModal.on('show.bs.modal', function (e) {
    $('#chapter_id').val(e.relatedTarget.name);
    lessonAddForm.validate({
        submitHandler: function (form) {
            var param = $(form).serialize();
            $.ajax({
                url: addLessonUrl,
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
                        //关闭窗口
                        lessonAddForm.modal('hide');
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
    //添加更多节
    addMoreLessonsBtn.click(function () {
        var lesson = '<div class="form-group"><input name="name[]" type="text" placeholder="请输入2-30位节名称" class="form-control"'+
                     'required=""'+
                     'maxlength="30"'+
                     'minlength="2" ></div>';
        $(lesson).appendTo($('#lesson_add_content'));
    })
    //关闭是初始化
    chapterAddModal.on('hide.bs.modal', function () {
        $('#chapter_name').val('');
    })
    //编辑章
    $("button[class='btn btn-primary btn-sm edit_chapter_btn']").click(function (e){
        $('#chapter_edit_name').val( e.target.name);
        chapterEditForm.validate({
            submitHandler: function () {
                $.ajax({
                    url: updateUrl,
                    type: "post",
                    dataType: "json",
                    data: {
                        'id':e.target.id,
                        'name':$.trim($('#chapter_edit_name').val()),
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
                            chapterAddForm.modal('hide');
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
    //编辑节
    $("a[class='lesson-edit glyphicon glyphicon-edit']").click(function (e){
        $('#lesson_edit_name').val( e.target.name);
        lessonEditForm.validate({
            submitHandler: function () {
                $.ajax({
                    url: updateLessonUrl,
                    type: "post",
                    dataType: "json",
                    data: {
                        'id':e.target.id,
                        'name':$.trim($('#lesson_edit_name').val()),
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
                            $('#lesson_edit_modal').modal('hide');
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
    //删除章按钮
    $("button[class='btn btn-primary btn-sm remove_chapter_btn']").click(function (e) {
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
    //删除节按钮
    $("a[class='lesson-remove glyphicon glyphicon-trash']").click(function (e) {
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
                url: removeLessonUrl,
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
    //上传视频
    lessAddVideoModal.on('show.bs.modal', function (x) {
        var  uploadButton = $('<button/>')
            .addClass('btn btn-primary')
            .prop('disabled', true)
            .text('处理中...')
            .on('click', function () {
                var $this = $(this),
                    data = $this.data();
                $this
                    .off('click')
                    .text('取消')
                    .on('click', function () {
                        $this.remove();
                        data.abort();
                    });
                data.submit().always(function () {
                    $this.remove();
                });
            });
        $('#fileupload').fileupload({
            url: uploadUrl,
            type: "POST",
            dataType: 'json',
            autoUpload: false,
            acceptFileTypes: /(\.|\/)(mp4)$/i,
            maxFileSize: 200*1024*1024,//50M
            maxNumberOfFiles:1,
            messages: {
                maxFileSize: '上传文件不得超过100Mb',
                acceptFileTypes: '文件类型不正确'
            }
        }).on('fileuploadadd', function (e, data) {
            $('#files').empty();
            data.context = $('<div class="row"></div>').appendTo('#files');
            $.each(data.files, function (index, file) {
                var node = $('<div class="col-sm-12"></div>');

                //console.log(file.preview.duration);
                if (!index) {
                    node.prepend($('<div class="col-sm-1"></div>').html(uploadButton.clone(true).data(data)));
                    node.prepend($('<div class="col-sm-2"></div>').html(file.name));
                    node.prepend($('<div class="col-sm-2"></div>').html(parseFloat(file.size/(1024*1024)).toFixed(2) +'Mb'));
                }
                node.appendTo(data.context);
            });
        }).on('fileuploadprocessalways', function (e, data) {
            var index = data.index,
                file = data.files[index],
                node = $(data.context.children()[index]);
            if (file.preview) {
                node.prepend($('<div class="col-sm-5" id="video-perview"></div>').html(file.preview));
                file.preview.addEventListener("loadedmetadata", function() {
                    $('<div class="col-sm-1" id="video-duration"></div>').html(parseInt(file.preview.duration)).insertAfter($('#video-perview'));
                });
            }
            if (file.error) {
                node
                    .append('<br>')
                    .append($('<span class="text-danger"/>').text(file.error));
            }
            if (index + 1 === data.files.length) {
                data.context.find('button')
                    .text('上传')
                    .prop('disabled', !!data.files.error);
            }
        }).on('fileuploadprogressall', function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
        }).on('fileuploadsubmit', function (e, data) {
            data.formData = {
                'id':x.relatedTarget.name,
                'duration':$('#video-duration').html()
            };
        }).on('fileuploaddone', function (e, data) {
            if (data.result.code){
                toastr.success(data.result.message);
                lessAddVideoModal.modal('hide');
                location.reload();
            }else{
                toastr.error('上传失败! ' + data.result.message);
            }
        }).on('fileuploadfail', function (e, data) {
            $.each(data.files, function (index) {
                var error = $('<span class="text-danger"/>').text('文件上传失败');
                $(data.context.children()[index])
                    .append('<br>')
                    .append(error);
            });
        }).prop('disabled', !$.support.fileInput)
            .parent().addClass($.support.fileInput ? undefined : 'disabled');
    })

    // 弹题
    addVideoQuestionModal.on('show.bs.modal', function (e) {
        var player = videojs('my-video');
            player.src('http://'+window.location.host+"/uploads/lesson/"+e.relatedTarget.name);
            player.ready(function() {
                player.markers({
                    markers: [],
                    onMarkerClick: function(marker) {

                    },
                    onMarkerReached: function(marker) {
                        alert('到达记号点');
                    },
                });
            });
            player.on('timeupdate', function () {
               // if(player.currentTime() == '4') alert('弹题');
                //console.log(player.currentTime());
            });
            player.one('loadedmetadata', function() {
            //var duration = player.duration();
            //alert(duration);
        });
            player.on('ended', function() {
                videojs.log('播放结束了');
            });
        $("div[class='vjs-mouse-display']").click(function (e) {
            e.stopPropagation();
            var currentTime = $('.vjs-mouse-display').attr('data-current-time');
            var time = timeToInt(currentTime);
            var li = '<li>时间:'+currentTime+'s  ' +
                '<a class="add_question" name="'+time+'">添加习题</a> ' +
                '<a class="makers_remove">删除</a></li>';
            $('#makers').append(li);
            questionAddBtn.attr('name',time);
            $("a[class='add_question']").click(function () {
                questionAddModal.modal('show');
            })
            $("a[class='makers_remove']").click(function (e) {
                var index = $(this).parent().index();
                //console.log(index);
                $(this).parent().remove();
                player.markers.remove([index]);
            })
        });

        questionAddBtn.click(function () {
            var rows = table.bootstrapTable('getSelections');
            if(rows.length == 1){
                player.markers.add([{
                    time: questionAddBtn.attr('name'),
                    text: rows[0].id
                }]);
                questionAddModal.modal('hide');
            }else{
                toastr.error('只能选择一条数据');
            }
            console.log(player.markers.getMarkers());
        })

        $('#makers_submit').click(function () {
            $.ajax({
                url: addMakersUrl,
                type: "post",
                dataType: "json",
                data: {
                    'id':e.relatedTarget.id,
                    'pramas':player.markers.getMarkers(),
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
            console.log(player.markers.getMarkers());
        });

        addVideoQuestionModal.on('hide.bs.modal', function (e) {
            location.reload();
            // player.dispose;
            // player.markers.destroy;
        })
    });
    questionAddModal.on('hidden.bs.modal', function () {
        table.bootstrapTable('refresh');
    });
    questionAddModal.on('show.bs.modal', function (e) {
        getQuestions()
    });

    //所有习题库数据
   function getQuestions() {
       table.bootstrapTable({
           url: '/admin/questions/list?'+Math.random(),
           method:'POST',
           contentType: 'application/x-www-form-urlencoded',
           cache: false,
           refresh: true,
           queryParams:{
               'courseId': $('#course_id').val(),
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
   }
    //时间转换
   function timeToInt(currentTime) {
       var array = currentTime.split(":");
        return parseInt(array[0]*60) + parseInt(array[1]);
    }

    //刷新
    $('#refresh').click(function () {
        location.reload();
    })
})

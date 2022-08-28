<?php
include('header.php');
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/errorcode.php';

?>
<!DOCTYPE html>
<html>

<body>


    <!-- MENU SECTION END-->

    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">THỂ LOẠI</h4>
                    <button class="btn btn-success" data-toggle="modal" data-target="#updateCategory">
                        <i class="fa fa-plus-square"></i>
                        Thêm
                    </button>
                </div>
            </div>
            <div class="row">

                <div class="col-md-12">
                    <!--    Hover Rows  -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Danh sách thể loại
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <div class="form-inline">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label>Tìm kiếm: <input type="search" placeholder="Tên thể loại" class="form-control input-sm" id="search"></label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div id="page">Page 1 of 10</div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="dataTables_paginate paging_simple_numbers">
                                                <ul class="pagination">
                                                    <li id="btn_prev"><a id="prev">Previous</a></li>
                                                    <li id="btn_next"><a id="next">Next</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="table table-striped table-bordered table-hover" id="table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Avatar</th>
                                                <th>Tên</th>
                                                <th>Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End  Hover Rows  -->
                </div>
            </div>
            <!-- /. ROW  -->

        </div>
    </div>

    <!-- CONTENT-WRAPPER SECTION END-->
    <!-- MODAL -->

    <div class="modal fade" id="updateCategory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">CẬP NHẬT THỂ LOẠI</h4>
                </div>
                <div class="panel-body">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Ảnh minh họa
                            <input id="choose-img" value="abc" type="file" style="display: none;" />
                            <button class="btn btn-success" id="addImage"><i class="fa fa-plus-square"></i> Add</button>
                            <button class="btn btn-danger" id="deleteImage"><i class="fa fa-trash-o"></i> Delete</button>
                        </div>
                        <div class="panel-body text-center recent-users-sec" id="image">
                        </div>
                    </div>
                    <form role="form" id="formUpdate" method="post">
                        <div class="form-group">
                            <label>Tên</label>
                            <input name="name" id="name" class="form-control" type="text" />
                        </div>
                        <div class="form-group">
                            <label>Thể loại</label>
                            <select name="superCategory" id="superCategory">
                            </select>
                        </div>
                        <div class="form-group" style="color: red;" id="errUpdate"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">CANCLE</button>
                            <button id="submitUpdate" type="button" class="btn btn-primary">OK</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteCategory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">XÓA THỂ LOẠI</h4>
                </div>
                <div class="modal-body">
                    Bạn có chắc là xóa thể loại này ?
                    <div class="form-group" style="color: red;" id="errDelete"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="submitDelete" type="button" class="btn btn-primary">OK</button>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL END-->
    <section class="footer-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    &copy; 2014 Yourdomain.com |<a href="http://www.binarytheme.com/" target="_blank"> Designed by : binarytheme.com</a>
                </div>
            </div>

        </div>
    </section>
    <script>
        var isSubmit = false;
        var focus = null;
        var relatedTarget = null;
        var category = null;
        var page = 1;
        var total = 1;
        var type = 0;
        var avatar = null;

        const uuid = () => {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                var r = Math.random() * 16 | 0,
                    v = c == 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
        }

        const fetchAPI = async (url, option) => {
            const res = await fetch(url, option);
            return res.json();
        }

        $('#updateCategory').on('show.bs.modal', function(event) {

            var modal = $(this);
            relatedTarget = $(event.relatedTarget);
            focus = relatedTarget.data('category');

            if (focus == null) {
                modal.find("#myModalLabel").html("THÊM THỂ LOẠI");
                focus = {
                    id: -1,
                    name: "",
                    avatar: null,
                    category: null
                };
            } else {
                modal.find("#myModalLabel").html("SỬA THỂ LOẠI");
            }
            console.log(focus);
            modal.find('#errUpdate').text("");
            modal.find('#name').val(focus.name);
            var category_id = focus.category == null ? null : focus.category.id;
            category = focus.category;
            if (category == null) {
                category = {
                    id: null,
                    name: null
                }
            }
            console.log(category);
            avatar = focus.avatar;

            if (avatar != null) modal.find('#image').html(`<img class='img-thumbnail' src=${avatar} >`);
            else modal.find('#image').html("");
            modal.find('#choose-img').val("");

            modal.find('#deleteImage').on('click', function(event) {
                event.stopPropagation();
                event.stopImmediatePropagation();
                modal.find('#image').html("");
                avatar = null;
            });
            modal.find('#addImage').on('click', function(event) {
                event.stopPropagation();
                event.stopImmediatePropagation();
                modal.find('#choose-img').trigger('click');
            });
            modal.find("#superCategory").html(`<option value=${null}>None</option>`)

            $.ajax({
                url: '../../api/category/category_getLevel_0.php',
                type: 'GET',
                headers: {
                    "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
                },
                success: (res) => {
                    console.log(res);
                    if (!res.is_error) {
                        for (var i = 0; i < res.data.length; i++) {
                            modal.find("#superCategory").append(
                                `<option
                                    ${(focus.category != null && category_id == res.data[i].id) ?"selected":""} 
                                    ${(focus.id == res.data[i].id) ?"disabled":""} 
                                    value=${res.data[i].id}>${res.data[i].name}
                                </option>`
                            )
                        }
                    } else {
                        $.ajax({
                            url: '../../config/errorcode.php',
                            type: 'POST',
                            data: {
                                code: res.code
                            },
                            success: (res) => {
                                window.alert(res);
                                type = 0;
                            }
                        });
                    }
                }
            });

            modal.find('#choose-img').on('change', function(event) {
                const ref = firebase.storage().ref(uuid());
                const uploadTask = ref.put(modal.find('#choose-img')[0].files[0]);
                uploadTask.on(firebase.storage.TaskEvent.STATE_CHANGED,
                    (snapshot) => {},
                    (err) => {
                        console.log("err: ", err)
                    },
                    () => {
                        uploadTask.snapshot.ref.getDownloadURL().then(url => {
                            console.log("URL: ", url);
                            modal.find('#image').html(`<img class='img-thumbnail' src=${url} >`);
                            avatar = url;
                        })
                    }
                )
            });

            modal.find("#superCategory").on("change", () => {
                category_id = $("#superCategory").val()
                category.id = $("#superCategory").val();
                category.name = $("#superCategory option:selected").text();
                console.log(category);
            });

            modal.find('#submitUpdate').on('click', async function(event) {
                if (isSubmit) {
                    return;
                }
                const id = parseInt(focus.id);
                const name = modal.find('#name').val();
                const url = `../../api/category/category_update.php`;
                if (category_id != null) category_id = parseInt(category_id);
                const body = {
                    id,
                    name,
                    avatar,
                    category_id
                };
                console.log(JSON.stringify(body));
                const options = {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
                    },
                    body: JSON.stringify(body)
                }
                try {
                    isSubmit = true;
                    const result = await fetchAPI(url, options);
                    console.log(result);
                    if (!result.is_error) {
                        modal.modal("hide");
                        if (focus.id != -1) {
                            tr = "";
                            tr += "<td>" + id + "</td>";
                            if (avatar != null && avatar != "") {
                                tr += "<td><img src=" + avatar + " width='80' height='60'  style='object-fit: cover'/></td>";
                            } else {
                                tr += "<td><img src='https://www.salonlfc.com/wp-content/uploads/2018/01/image-not-found-1-scaled-1150x647.png' width='80' height='60'  /></td>";
                            }
                            tr += "<td>" + name + "</td>";
                            const data = {
                                id,
                                name,
                                avatar,
                                category,
                            };
                            tr += `<td><button data-category='${JSON.stringify(data)}' class="btn btn-primary" data-toggle="modal" data-target="#updateCategory"><i class="fa fa-edit "></i> Edit</button><button data-category='${JSON.stringify(data)}' class="btn btn-danger" data-toggle="modal" data-target="#deleteCategory"><i class="fa fa-trash-o"></i> Delete</button></td>`;
                            relatedTarget.parent().parent().html(tr);
                        }
                    } else {
                        isSubmit = false;
                        $.ajax({
                            url: '../../config/errorcode.php',
                            type: 'POST',
                            data: {
                                code: result.code
                            },
                            success: function(data) {
                                $("#errUpdate").text(data);
                            }
                        });
                    }
                } catch (err) {
                    isSubmit = false;
                    console.log(err);
                }
            })

        })
        $('#updateCategory').on('hidden.bs.modal', function(event) {
            isSubmit = false;
            focus = null;
            avatar = null;
            $("#superCategory").removeData();
        })


        $('#deleteCategory').on('show.bs.modal', function(event) {
            var modal = $(this);
            relatedTarget = $(event.relatedTarget);
            focus = relatedTarget.data('category');
            modal.find('#errDelete').text("");
            modal.find('#submitDelete').on('click', async function(event) {
                if (isSubmit) {
                    return;
                }
                const url = `../../api/category/category_remove.php`;
                const data = new URLSearchParams();
                data.append("id", focus.id);
                const options = {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
                    },
                    body: data
                }
                try {
                    isSubmit = true;
                    const result = await fetchAPI(url, options);
                    console.log(result);
                    if (!result.is_error) {
                        modal.modal("hide");
                        relatedTarget.parent().parent().html("");
                    } else {
                        isSubmit = false;
                        $.ajax({
                            url: '../../config/errorcode.php',
                            type: 'POST',
                            data: {
                                code: result.code
                            },
                            success: function(data) {
                                $("#errDelete").text(data);
                            }
                        });
                    }
                } catch (err) {
                    isSubmit = false;
                    console.log(err);
                }
            })
        })
        $('#deleteCategory').on('hidden.bs.modal', function(event) {
            isSubmit = false;
            focus = null;
        })

        loadData();

        function loadData() {
            var url = "";
            if (type == 0) {
                url = `../../api/category/getAll.php?page=${page}&limit=10`;
            } else {
                url = `../../api/category/search.php?page=${page}&limit=10&query=${$('#search').val()}`;
            }

            $.ajax({
                type: "GET",
                url: url,
                headers: {
                    "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
                },
                success: (res) => {
                    console.log(res);

                    if (!res.is_error) {
                        total = res.total;

                        if (page == total) {
                            $("#btn_next").attr("class", "paginate_button previous disabled");
                        } else {
                            $("#btn_next").attr("class", "paginate_button previous");
                        }

                        if (page == 1) {
                            $("#btn_prev").attr("class", "paginate_button previous disabled ");
                        } else {
                            $("#btn_prev").attr("class", "paginate_button previous ");
                        }
                        $('#table').html("");
                        $("#page").html(`Page ${page} of ${res.total}`);

                        var categories = res.data;
                        var tr;
                        for (var i = 0; i < categories.length; i++) {
                            tr = $('<tr>');
                            tr.append("<td>" + categories[i].id + "</td>");
                            if (categories[i].avatar != null && categories[i].avatar != "") {
                                tr.append("<td><img src=" + categories[i].avatar + " width='80' height='60'  style='object-fit: cover'/></td>")
                            } else {
                                tr.append("<td><img src='https://www.salonlfc.com/wp-content/uploads/2018/01/image-not-found-1-scaled-1150x647.png' width='80' height='60'  /></td>")
                            }
                            tr.append("<td>" + categories[i].name + "</td>");
                            tr.append(`<td><button data-category='${JSON.stringify(categories[i])}' class="btn btn-primary" data-toggle="modal" data-target="#updateCategory"><i class="fa fa-edit "></i> Edit</button><button data-category='${JSON.stringify(categories[i])}' class="btn btn-danger" data-toggle="modal" data-target="#deleteCategory"><i class="fa fa-trash-o"></i> Delete</button></td>`);
                            $('#table').append(tr);
                        }
                    } else {
                        $.ajax({
                            url: '../../config/errorcode.php',
                            type: 'POST',
                            data: {
                                code: res.code
                            },
                            success: (res) => {
                                window.alert(res);
                                type = 0;
                            }
                        });
                    }
                }
            });
        }


        $('#next').on('click', function(event) {
            if (page == total) return;
            page++;
            loadData();
        })

        $('#prev').on('click', function(event) {
            if (page == 1) return;
            page--;
            loadData();
        })

        $('#search').on('search', function(event) {
            page = 1;
            type = 1;
            loadData();
        })
    </script>
</body>

</html>
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
                    <h4 class="header-line">USERS</h4>
                    <button class="btn btn-success" data-toggle="modal" data-target="#sendNoti">
                        <i class="fa fa-bell-o"></i>
                        GỬI THÔNG BÁO
                    </button>
                </div>
            </div>
            <div class="row">

                <div class="col-md-12">
                    <!--    Hover Rows  -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Danh sách người dùng
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <div class="form-inline">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label>Tìm kiếm: <input type="search" placeholder="Email hoặc SDT" class="form-control input-sm" id="search"></label>
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
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Avatar</th>
                                                <th>Email</th>
                                                <th>Tên đầy đủ</th>
                                                <th>Ví CSC</th>
                                                <th>SDT</th>
                                                <th>Trạng thái</th>
                                                <th>Quyền</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table">

                                        </tbody>
                                    </table>
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
        <div class="modal fade" id="changeStatus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">KHÓA/ MỞ KHÓA TÀI KHOẢN</h4>
                    </div>
                    <div class="modal-body">
                        Bạn có chắc là muốn thực hiện điều này?
                    </div>
                    <div class="panel-body">
                        <form role="form" id="formUpdate" method="post">
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
        <div class="modal fade" id="sendNoti" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Send Notification</h4>
                    </div>
                    <div class="panel-body">
                        <form role="form" id="formInsert" method="post">
                            <div class="form-group">
                                <label>Action</label>
                                <input name="action" id="action" class="form-control" type="text" />
                                <label>Description</label>
                                <input name="description" id="description" class="form-control" type="text" />
                            </div>
                            <div class="form-group" style="color: red;" id="errSendNoti"></div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button id="submitSendNoti" type="button" class="btn btn-primary">Send</button>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
        <div class="modal fade" id="changePermission" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">CẤP/ LẤY QUYỀN ADMIN</h4>
                    </div>
                    <div class="modal-body">
                        Bạn có chắc là muốn thực hiện điều này?
                    </div>
                    <div class="panel-body">
                        <form role="form" id="formUpdate" method="post">
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
            var page = 1;
            var total = 1;
            var type = 0;


            const fetchAPI = async (url, option) => {
                const res = await fetch(url, option);
                return res.json();
            }

            $('#changeStatus').on('show.bs.modal', function(event) {
                var modal = $(this);
                relatedTarget = $(event.relatedTarget);
                focus = relatedTarget.data('email');
                modal.find('#errUpdate').text("");
                modal.find('#submitUpdate').on('click', async function(event) {
                    if (isSubmit) {
                        return;
                    }
                    const url = "../../api/user/updateStatus.php";
                    const data = new URLSearchParams();
                    data.append("email", focus.email);
                    const options = {
                        method: 'post',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
                        },
                        body: data
                    };
                    try {
                        isSubmit = true;
                        const response = await fetchAPI(url, options);
                        console.log(response);
                        if (!response.is_error) {
                            modal.modal("hide");
                            if (relatedTarget.attr("class") != "btn btn-success change") {
                                relatedTarget.attr("class", "btn btn-success change");
                                relatedTarget.children().attr("class", "fa fa-check-square-o");
                            } else {
                                relatedTarget.attr("class", "btn btn-default change");
                                relatedTarget.children().attr("class", "fa fa-square-o ");
                            }
                        } else {
                            isSubmit = false;
                            $.ajax({
                                url: '../../config/errorcode.php',
                                type: 'POST',
                                data: {
                                    code: response.code
                                },
                                success: function(data) {
                                    $("#errUpdate").text(data);
                                }
                            });
                        }
                    } catch (err) {
                        console.log(err);
                    }
                })
            })
            $('#changeStatus').on('hidden.bs.modal', function(event) {
                isSubmit = false;
                focus = null;
                relatedTarget = null;
            })

            $('#changePermission').on('show.bs.modal', function(event) {
                var modal = $(this);
                relatedTarget = $(event.relatedTarget);
                focus = relatedTarget.data('email');
                modal.find('#errUpdate').text("");
                modal.find('#submitUpdate').on('click', async function(event) {
                    if (isSubmit) {
                        return;
                    }
                    const url = "../../api/user/updatePermission.php";
                    const data = new URLSearchParams();
                    data.append("email", focus.email);
                    const options = {
                        method: 'post',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
                        },
                        body: data
                    };
                    try {
                        isSubmit = true;
                        const response = await fetchAPI(url, options);
                        console.log(response);
                        if (!response.is_error) {
                            modal.modal("hide");
                            if (relatedTarget.attr("class") != "btn btn-success change") {
                                relatedTarget.attr("class", "btn btn-success change");
                                relatedTarget.children().attr("class", "fa fa-check-square-o");
                            } else {
                                relatedTarget.attr("class", "btn btn-default change");
                                relatedTarget.children().attr("class", "fa fa-square-o ");
                            }
                        } else {
                            isSubmit = false;
                            $.ajax({
                                url: '../../config/errorcode.php',
                                type: 'POST',
                                data: {
                                    code: response.code
                                },
                                success: function(data) {
                                    $("#errUpdate").text(data);
                                }
                            });
                        }
                    } catch (err) {
                        console.log(err);
                    }
                })
            })

            $('#changePermission').on('hidden.bs.modal', function(event) {
                isSubmit = false;
                focus = null;
                relatedTarget = null;
            })


            $('#sendNoti').on('show.bs.modal', function(event) {
                var modal = $(this);
                modal.find('#name').val("");
                modal.find('#errSendNoti').text("");
                modal.find('#submitSendNoti').on('click', async function(event) {
                    if (isSubmit) {
                        return;
                    }
                    const url = "../../api/notify/sendNotify.php";
                    const data = new URLSearchParams();
                    data.append("action", modal.find('#action').val());
                    data.append("description", modal.find('#description').val());
                    const options = {
                        method: 'post',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
                        },
                        body: data
                    };
                    try {
                        isSubmit = true;
                        const result = await fetchAPI(url, options);
                        if (!result.is_error) {
                            modal.modal("hide");
                            window.alert("Gửi thông báo thành công");
                        } else {
                            isSubmit = false;
                            $.ajax({
                                url: '../../config/errorcode.php',
                                type: 'POST',
                                data: {
                                    code: response.code
                                },
                                success: (res) => {
                                    window.alert(res);
                                }
                            });
                        }
                    } catch (err) {
                        console.log(err);
                    }
                })
            })
            $('#sendNoti').on('hidden.bs.modal', function(event) {
                isSubmit = false;
                focus = null;
                relatedTarget = null;
            })

            const user = JSON.parse(localStorage.getItem("user"));
            loadData();

            function loadData() {

                var url = "";
                if (type == 0) {
                    url = `../../api/user/user_getAll.php?permission=${user.data.permission}&page=${page}&limit=10`;
                } else {
                    url = `../../api/user/user_search.php?permission=${user.data.permission}&page=${page}&limit=10&query=${$('#search').val()}`;
                }
                $.ajax({
                    type: "GET",
                    url: url,
                    headers: {
                        "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
                    },
                    success: (res) => {
                        console.log(res);
                        if (!res["is_error"]) {
                            total = res.total;
                            if (page == total) {
                                $("#btn_next").attr("class", "paginate_button previous disabled");
                            } else {
                                $("#btn_next").attr("class", "paginate_button previous");
                            }

                            if (page == 1) {
                                $("#btn_prev").attr("class", "paginate_button previous disabled");
                            } else {
                                $("#btn_prev").attr("class", "paginate_button previous");
                            }

                            $("#page").html(`Page ${page} of ${total}`);
                            $('#table').html("");
                            $('#table').append(tr);
                            const users = res.data;
                            var tr;
                            for (var i = 0; i < users.length; i++) {
                                tr = $('<tr/>');
                                tr.append("<td>" + users[i].id + "</td>");
                                if (users[i].avatar != null) {
                                    tr.append("<td><img src=" + users[i].avatar + " width='80' height='60'  style='object-fit: cover'/></td>")
                                } else {
                                    tr.append("<td><img src='https://www.salonlfc.com/wp-content/uploads/2018/01/image-not-found-1-scaled-1150x647.png' width='80' height='60'  /></td>")
                                }
                                tr.append("<td>" + users[i].email + "</td>");
                                tr.append("<td>" + users[i].fullname + "</td>");
                                tr.append("<td>" + users[i].wallet + "</td>");
                                tr.append("<td>" + users[i].phone + "</td>");
                                if (users[i].status == 1) {
                                    tr.append(`<td><button data-email='{"email":"${users[i].email}"}' class="btn btn-success change" data-toggle="modal" data-target="#changeStatus"><i class="fa fa-check-square-o "></i> Active</button></td>`);
                                } else {
                                    tr.append(`<td><button data-email='{"email":"${users[i].email}"}' class="btn btn-default change" data-toggle="modal" data-target="#changeStatus"><i class="fa fa-square-o "></i> Active</button></td>`);
                                }
                                if (users[i].permission != 0) {
                                    tr.append(`<td><button data-email='{"email":"${users[i].email}"}' class="btn btn-success change" data-toggle="modal" data-target="#changePermission"><i class="fa fa-check-square-o "></i> Admin</button></td>`);
                                } else {
                                    tr.append(`<td><button data-email='{"email":"${users[i].email}"}' class="btn btn-default change" data-toggle="modal" data-target="#changePermission"><i class="fa fa-square-o "></i> Admin</button></td>`);
                                }
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
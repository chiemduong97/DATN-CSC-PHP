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
                    <h4 class="header-line">CHI NHÁNH</h4>
                    <button class="btn btn-success" data-toggle="modal" data-target="#addCategory">
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
                            Danh sách chi nhánh
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <div class="form-inline">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label>Tìm kiếm: <input type="search" placeholder="..." class="form-control input-sm" id="search"></label>
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

    <div class="modal fade" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">ADD BRANCH</h4>
                </div>
                <div class="panel-body">
                    <form role="form" id="formInsert" method="post">
                        <div class="form-group">
                            <label>Name</label>
                            <input name="name" id="name" class="form-control" type="text" />
                        </div>
                        <div class="form-group" style="color: red;" id="errAdd"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button id="submitAdd" type="button" class="btn btn-primary">Save</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="updateCategory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">UPDATE BRANCH</h4>
                </div>
                <div class="panel-body">
                    <form role="form" id="formUpdate" method="post">
                        <div class="form-group">
                            <label>Name</label>
                            <input name="name" id="name" class="form-control" type="text" />
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
                    <h4 class="modal-title" id="myModalLabel">DELETE BRANCH</h4>
                </div>
                <div class="modal-body">
                    Are you sure ?
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
        var focusUpdate = null;
        var relatedTarget = null;
        var page = 1;
        var total = 1;
        var type = 0;


        // const fetchAPI = async (url, option) => {
        //     const res = await fetch(url, option);
        //     return res.json();
        // }
        // $('#addCategory').on('show.bs.modal', function(event) {
        //     var modal = $(this);
        //     modal.find('#name').val("");
        //     modal.find('#errAdd').text("");
        //     modal.find('#submitAdd').on('click', async function(event) {
        //         if (isSubmit) {
        //             return;
        //         }
        //         const name = modal.find('#name').val();
        //         const url = `category/insert.php?name=${name}`;
        //         const options = {
        //             method: 'get',
        //             headers: {
        //                 'Content-Type': 'application/json',
        //                 "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
        //             },
        //         }
        //         try {
        //             isSubmit = true;
        //             const result = await fetchAPI(url, options);
        //             if (result.status) {
        //                 window.location.href = 'branch.php';
        //             } else {
        //                 if (result.code != null) {
        //                     isSubmit = false;
        //                     switch (result.code) {
        //                         case 1001:
        //                             modal.find('#errAdd').text("<?php echo $Err_1001; ?>");
        //                             break;
        //                         case 1002:
        //                             modal.find('#errAdd').text("<?php echo $Err_1002; ?>");
        //                             break;
        //                         case 1003:
        //                             modal.find('#errAdd').text("<?php echo $Err_1003; ?>");
        //                             break;
        //                     }
        //                 }
        //             }
        //         } catch (err) {
        //             console.log(err);
        //         }
        //     })
        // })
        // $('#addCategory').on('hidden.bs.modal', function(event) {
        //     isSubmit = false;
        // })
        // $('#updateCategory').on('show.bs.modal', function(event) {
        //     var a = $(event.relatedTarget);
        //     focusUpdate = a.data('category');
        //     var modal = $(this);
        //     modal.find('#errUpdate').text("");
        //     modal.find('#name').val(focusUpdate.name);
        //     modal.find('#submitUpdate').on('click', async function(event) {
        //         if (isSubmit) {
        //             return;
        //         }
        //         const name = modal.find('#name').val();
        //         const url = `category/update.php?id=${focusUpdate.id}&name=${name}`;
        //         const options = {
        //             method: 'get',
        //             headers: {
        //                 'Content-Type': 'application/json',
        //                 "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
        //             },
        //         }
        //         try {
        //             isSubmit = true;
        //             const result = await fetchAPI(url, options);
        //             if (result.status) {
        //                 window.location.href = 'category.php';
        //             } else {
        //                 if (result.code != null) {
        //                     isSubmit = false;
        //                     switch (result.code) {
        //                         case 1001:
        //                             modal.find('#errUpdate').text("<?php echo $Err_1001; ?>");
        //                             break;
        //                         case 1002:
        //                             modal.find('#errUpdate').text("<?php echo $Err_1002; ?>");
        //                             break;
        //                         case 1003:
        //                             modal.find('#errUpdate').text("<?php echo $Err_1003; ?>");
        //                             break;
        //                     }
        //                 }
        //             }
        //         } catch (err) {
        //             console.log(err);
        //         }
        //     })

        // })
        // $('#updateCategory').on('hidden.bs.modal', function(event) {
        //     isSubmit = false;
        //     focusUpdate = null;
        // })


        // $('#deleteCategory').on('show.bs.modal', function(event) {
        //     var modal = $(this);
        //     var b = $(event.relatedTarget);
        //     focusDelete = b.data('id').id;
        //     modal.find('#errDelete').text("");
        //     modal.find('#submitDelete').on('click', async function(event) {
        //         if (isSubmit) {
        //             return;
        //         }
        //         const url = `category/delete.php?id=${focusDelete}`;
        //         const options = {
        //             method: 'get',
        //             headers: {
        //                 'Content-Type': 'application/json',
        //                 "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
        //             },
        //         }
        //         try {
        //             isSubmit = true;
        //             const result = await fetchAPI(url, options);
        //             if (result.status) {
        //                 window.location.href = 'category.php';
        //             } else {
        //                 if (result.code != null) {
        //                     isSubmit = false;
        //                     switch (result.code) {
        //                         case 1001:
        //                             modal.find('#errDelete').text("<?php echo $Err_1001; ?>");
        //                             break;
        //                         case 1005:
        //                             modal.find('#errDelete').text("<?php echo $Err_1005; ?>");
        //                             break;
        //                     }
        //                 }
        //             }
        //         } catch (err) {
        //             console.log(err);
        //         }
        //     })
        // })
        // $('#deleteCategory').on('hidden.bs.modal', function(event) {
        //     isSubmit = false;
        //     focusDelete = null;
        // })

        loadData();

        function loadData() {
            var url = `../../api/branch/branch_getAll.php`;
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
                        $("#btn_prev").attr("class", "paginate_button previous disabled ");
                     
                        $('#table').html("");
                        $("#page").html(`Page ${page} of ${res.total}`);

                        var branches = res.data;
                        var tr;
                        for (var i = 0; i < branches.length; i++) {
                            tr = $('<tr/>');
                            tr.append("<td>" + branches[i].id + "</td>");
                           
                            tr.append("<td>" + branches[i].name + "</td>");
                            tr.append("<td>" + branches[i].address + "</td>");
                            tr.append(`<td><button data-category='{"id":${branches[i].id},"name":"${branches[i].name}"}' class="btn btn-primary" data-toggle="modal" data-target="#updateCategory"><i class="fa fa-edit "></i> Edit</button><button data-id='{"id":${branches[i].id}}' class="btn btn-danger" data-toggle="modal" data-target="#deleteCategory"><i class="fa fa-trash-o"></i> Delete</button></td>`);
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
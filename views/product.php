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
                    <h4 class="header-line">SẢN PHẨM</h4>
                    <button class="btn btn-success" data-toggle="modal" data-target="#updateProduct">
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
                            Danh sách sản phẩm
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <div class="form-inline">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label>Tìm kiếm: <input type="search" placeholder="Tên sản phẩm" class="form-control input-sm" id="search"></label>
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
                                                <th>#</th>
                                                <th>Tên sản phẩm</th>
                                                <th>Tồn kho</th>
                                                <th>Giá bán</th>
                                                <th>Ngày thêm</th>
                                                <th>Trạng thái</th>
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
        <div class="modal fade" id="showProduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">CHI TIẾT SẢN PHẨM</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped table-bordered">
                            <tbody>
                                <tr>
                                    <td>Mã sản phẩm</td>
                                    <td id="id">1</td>
                                </tr>
                                <tr>
                                    <td>Tên sản phẩm</td>
                                    <td id="name">Nước Ngọt Coca Cola Lon 320ml - 1 Lon</td>
                                </tr>
                                <tr>
                                    <td>Hình minh họa</td>
                                    <td id="avatar"><img src='https://www.salonlfc.com/wp-content/uploads/2018/01/image-not-found-1-scaled-1150x647.png' width='80' height='60' /></td>
                                </tr>
                                <tr>
                                    <td>Mô tả</td>
                                    <td id="description">Là loại nước ngọt được nhiều người yêu thích với hương vị thơm ngon, sảng khoái. Nước ngọt Coca Cola 320ml với lượng gas lớn sẽ giúp bạn xua tan mọi cảm giác mệt mỏi, căng thẳng, đem lại cảm giác thoải mái sau khi hoạt động ngoài trời</td>
                                </tr>
                                <tr>
                                    <td>Giá</td>
                                    <td id="price">12.000 đ</td>
                                </tr>
                                <tr>
                                    <td>Thể loại</td>
                                    <td id="category">Đồ uống - nước giải khát</td>
                                </tr>
                                <tr>
                                    <td>Tồn kho</td>
                                    <td id="quantity">100</td>
                                </tr>
                                <tr>
                                    <td>Trạng thái</td>
                                    <td id="status">Đang bậc</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group" style="color: red;" id="err"></div>
                    </div>
                    <div class="panel-body">
                        <form role="form" id="form" method="post">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
                                <button id="submit" type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="showWarehouse" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">NHẬP KHO</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped table-bordered">
                            <tbody>
                                <tr>
                                    <td>Mã sản phẩm</td>
                                    <td id="id">1</td>
                                </tr>
                                <tr>
                                    <td>Tên sản phẩm</td>
                                    <td id="name">Nước Ngọt Coca Cola Lon 320ml - 1 Lon</td>
                                </tr>
                                <tr>
                                    <td>Số lượng nhập kho</td>
                                    <td><input id="quantity" type="number" /></td>
                                </tr>
                                <tr>
                                    <td>Người nhập kho</td>
                                    <td id="email">chiemduong@lozi</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group" style="color: red;" id="err"></div>
                    </div>
                    <div class="panel-body">
                        <form role="form" id="form" method="post">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
                                <button id="submit" type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="updateProduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">THÊM SẢN PHẨM</h4>
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
                                <select name="category" id="category">
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Mô tả</label>
                                <input name="description" id="description" class="form-control" type="text" />
                            </div>
                            <div class="form-group">
                                <label>Giá bán</label>
                                <input name="price" id="price" class="form-control" type="number" />
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

            $('#showProduct').on('show.bs.modal', function(event) {
                var modal = $(this);
                relatedTarget = $(event.relatedTarget);
                focusUpdate = relatedTarget.data('id');
                console.log(focusUpdate);
                modal.find("#err").html("");
                $.ajax({
                    url: `../../api/product/product_getById.php?id=${focusUpdate.id}`,
                    type: 'GET',
                    headers: {
                        "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
                    },
                    success: (res) => {
                        console.log(res);
                        if (!res.is_error) {
                            const product = res.data;
                            modal.find("#id").html(product.id);
                            modal.find("#name").html(product.name);
                            if (product.avatar == null) {
                                modal.find("#avatar").html("Chưa có");
                            } else {
                                modal.find("#avatar").html(`<img src=${product.avatar} width='80' height='60'  style='object-fit: cover'/>`);
                            }
                            modal.find("#description").html(product.description);
                            modal.find("#price").html(toVND(product.price));
                            modal.find("#category").html(product.category.name);
                            modal.find("#quantity").html(`${product.quantity} <button id="update" data-product='{"id":"${product.id}","name":"${product.name}"}' data-toggle="modal" data-target="#showWarehouse" class="btn btn-primary change"></i> Nhập kho</button></b>`);
                            modal.find("#status").html(getStatus(product.status));
                            modal.find("#update").on("click", function() {
                                modal.modal("hide");
                            })
                        } else {
                            modal.modal("hide");
                            $.ajax({
                                url: '../../config/errorcode.php',
                                type: 'POST',
                                data: {
                                    code: res.code
                                },
                                success: (res) => {
                                    window.alert(res);
                                }
                            });
                        }
                    }
                });

            })
            $('#showProduct').on('hidden.bs.modal', function(event) {
                isSubmit = false;
                focusUpdate = null;
                relatedTarget = null;
                $("#search").removeAttr("data-order");
                $("#search").removeAttr("data-toggle");
                $("#search").removeAttr("data-target");
                $("#search").removeData();
            })

            $('#showWarehouse').on('show.bs.modal', function(event) {
                var modal = $(this);
                relatedTarget = $(event.relatedTarget);
                focusUpdate = relatedTarget.data('product');
                console.log(focusUpdate);
                modal.find("#err").html("");
                modal.find("#id").html(focusUpdate.id);
                modal.find("#name").html(focusUpdate.name);
                modal.find("#quantity").val("");
                const email = JSON.parse(localStorage.getItem("user")).email;
                const product_id = focusUpdate.id;
                modal.find("#email").html(email);
                modal.find("#submit").on("click", async function(event) {
                    if (isSubmit) {
                        return;
                    }
                    const quantity = modal.find("#quantity").val();
                    const url = `../../api/product/warehouse.php`;
                    const body = {
                        product_id,
                        quantity,
                        email,
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
                            window.alert("Nhập kho thành công!");
                            loadData();
                        } else {
                            isSubmit = false;
                            $.ajax({
                                url: '../../config/errorcode.php',
                                type: 'POST',
                                data: {
                                    code: result.code
                                },
                                success: function(data) {
                                    modal.find("#err").text(data);
                                }
                            });
                        }
                    } catch (err) {
                        isSubmit = false;
                        console.log(err);
                    }
                })


            })
            $('#showWarehouse').on('hidden.bs.modal', function(event) {
                isSubmit = false;
                focusUpdate = null;
                relatedTarget = null;
            })

            $('#updateProduct').on('show.bs.modal', function(event) {
                var modal = $(this);
                modal.find('#errUpdate').text("");
                modal.find('#image').html("");
                modal.find('#choose-img').val("");
                modal.find('#name').val("");
                modal.find('#description').val("");
                modal.find('#price').val("");
                var category;
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
                    url: '../../api/category/category_getLevel_1_admin.php',
                    type: 'GET',
                    headers: {
                        "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
                    },
                    success: (res) => {
                        console.log(res);
                        if (!res.is_error) {
                            for (var i = 0; i < res.data.length; i++) {
                                modal.find("#category").append(
                                    `<option value=${res.data[i].id}>${res.data[i].name}</option>`
                                )
                            }
                            category = res.data[0].id;
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

                modal.find("#category").on("change", () => {
                    category = modal.find("#category").val();
                });

                modal.find('#submitUpdate').on('click', async function(event) {
                    if (isSubmit) {
                        return;
                    }
                    const name = modal.find('#name').val();
                    const description = modal.find('#description').val();
                    if (category != null) category = parseInt(category);
                    const price = parseFloat(modal.find('#price').val());
                    const url = `../../api/product/product_insert.php`;
                    const body = {
                        name,
                        avatar,
                        description,
                        price,
                        category
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
                            window.alert("Thêm thành công");
                            loadData();
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
            $('#updateProduct').on('hidden.bs.modal', function(event) {
                isSubmit = false;
                focus = null;
                avatar = null;
                $("#category").removeData();
            })

            loadData();

            function loadData() {
                var url = "";
                if (type == 0) {
                    url = `../../api/product/product_getNew.php?page=${page}&limit=10`;
                } else {
                    url = `../../api/product/product_search.php?page=${page}&limit=10&query=${$('#search').val()}`;
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

                            $('#table').html("");
                            $("#page").html(`Page ${page} of ${total}`);
                            const products = res.data;
                            var tr;
                            for (var i = 0; i < products.length; i++) {
                                tr = $(`<tr data-id='{"id":"${products[i].id}"}' data-toggle="modal" data-target="#showProduct">`);
                                tr.append("<td>" + products[i].id + "</td>");
                                tr.append("<td>" + products[i].name + "</td>");
                                tr.append("<td>" + products[i].quantity + "</td>");
                                tr.append("<td>" + toVND(products[i].price) + "</td>");
                                tr.append("<td>" + products[i].created_at + "</td>");
                                tr.append(`<td id="status">` + getStatus(products[i].status) + `</td>`);
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

            function getStatus(status) {
                var mStatus = "";
                if (status == 1) mStatus = `<b style="color:#36E605">Đang bật</b>`;
                if (status == 0) mStatus = `<b style="color:#CBCBCB">Đã tắt</b>`;
                return mStatus;
            }

            function toVND(amount) {
                return parseInt(amount).toLocaleString('vi', {
                    style: 'currency',
                    currency: 'VND'
                });
            }

            function getPayment(payment) {
                var payment_method = "";
                if (payment == "COD") payment_method = "Trả bằng tiền mặt khi nhận hàng";
                if (payment == "MOMO") payment_method = "Ví momo";
                if (payment == "WALLET") payment_method = "Ví CSC";
                return payment_method;
            }

            $('#search').on('search', function(event) {
                focusUpdate = `{"order_code":"${$(this).val()}"}`
                page = 1;
                type = 1;
                loadData();
            })
        </script>
</body>

</html>
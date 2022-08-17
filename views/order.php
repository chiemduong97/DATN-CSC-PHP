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
                    <h4 class="header-line">ĐƠN HÀNG</h4>
                </div>
            </div>
            <div class="row">

                <div class="col-md-12">
                    <!--    Hover Rows  -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Danh sách đơn hàng
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <div class="form-inline">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label>Xử lý đơn hàng: <input style="text-transform:uppercase" type="search" placeholder="Mã đơn" class="form-control input-sm" id="search"></label>
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
                                                <th>Thời gian</th>
                                                <th>Nơi mua hàng</th>
                                                <th>Nơi giao hàng</th>
                                                <th>Trạng thái</th>
                                                <th>SDT</th>
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
        <div class="modal fade" id="showOrder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">CHI TIẾT ĐƠN HÀNG</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped table-bordered">
                            <tbody>
                                <tr>
                                    <td>Mã đơn</td>
                                    <td id="order_code">33WE3XN</td>
                                </tr>
                                <tr>
                                    <td>Đặt hàng lúc</td>
                                    <td id="created_at">13/08 10:50</td>
                                </tr>
                                <tr>
                                    <td>Nơi mua hàng</td>
                                    <td id="branch_name">CSC Huỳnh Tấn Phát</td>
                                </tr>
                                <tr>
                                    <td>Danh sách sản phẩm</td>
                                    <td>
                                        <table class="table" id="order_details">
                                            <tbody>
                                                <tr>
                                                    <td>Snack</td>
                                                    <td>x2</td>
                                                    <td>10.000 đ</td>
                                                </tr>
                                                <tr>
                                                    <td>Snack</td>
                                                    <td>x2</td>
                                                    <td>10.000 đ</td>
                                                </tr>
                                                <tr>
                                                    <td>Snack</td>
                                                    <td>x2</td>
                                                    <td>10.000 đ</td>
                                                </tr>
                                            </tbody>

                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Nơi nhận hàng</td>
                                    <td id="address">70 Lữ Gia, Phường 15, Quận 11, Thành phố Hồ Chí Minh 700000, Việt Nam</td>
                                </tr>
                                <tr>
                                    <td>Khoảng cách</td>
                                    <td id="distance">1.9 km</td>
                                </tr>
                                <tr>
                                    <td>T.khoản đặt đơn</td>
                                    <td id="user_email">chiemduong@lozi.vn</td>
                                </tr>
                                <tr>
                                    <td>Tên người nhận</td>
                                    <td id="user_name">Đỗ Chiếm Dương</td>
                                </tr>
                                <tr>
                                    <td>SDT người nhận</td>
                                    <td id="user_phone">0976453651</td>
                                </tr>
                                <tr>
                                    <td>Tiền hàng</td>
                                    <td id="amount">125.000 đ</td>
                                </tr>
                                <tr>
                                    <td>Phí ship</td>
                                    <td id="shipping_fee">0 đ</td>
                                </tr>
                                <tr>
                                    <td>Mã giảm giá</td>
                                    <td id="promotion">PROMOTION7 - 20.000 đ</td>
                                </tr>
                                <tr>
                                    <td>Tổng tiền</td>
                                    <td id="total"><b>105.000 đ<b></td>
                                </tr>
                                <tr>
                                    <td>Hình thức thanh toán</td>
                                    <td id="payment_method">Trả bằng tiền mặt khi nhận hàng</td>
                                </tr>
                                <tr>
                                    <td>Trạng thái đơn hàng</td>
                                    <td id="status">Chờ xử lý</td>
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

            $('#showOrder').on('show.bs.modal', function(event) {
                var modal = $(this);
                relatedTarget = $(event.relatedTarget);
                focusUpdate = relatedTarget.data('order');
                console.log(focusUpdate);
                modal.find("#err").html("");
                $.ajax({
                    url: `../../api/order/order_getByOrderCode.php?order_code=${focusUpdate.order_code}`,
                    type: 'GET',
                    headers: {
                        "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
                    },
                    success: (res) => {
                        console.log(res);
                        if (!res.is_error) {
                            const order = res.data;
                            modal.find("#order_code").html(order.order_code);
                            modal.find("#created_at").html(order.created_at);
                            modal.find("#branch_name").html(order.branch.name);
                            $('#order_details').html("");
                            var tr;
                            var order_details = order.order_details;
                            for (var i = 0; i < order_details.length; i++) {
                                tr = $(`<tr>`);
                                tr.append("<td>" + `x${order_details[i].quantity}` + "</td>");
                                tr.append("<td>" + order_details[i].product.name + "</td>");
                                tr.append("<td>" + `${toVND(order_details[i].price)}` + "</td>");
                                $('#order_details').append(tr);
                            }
                            modal.find("#address").html(order.address);
                            modal.find("#distance").html(`${Math.ceil(order.distance*10)/10} km`);
                            modal.find("#user_email").html(order.user.email);
                            modal.find("#user_name").html(order.user.fullname);
                            modal.find("#user_phone").html(order.user.phone);
                            modal.find("#amount").html(toVND(order.amount));
                            modal.find("#shipping_fee").html(toVND(order.shipping_fee));
                            const promotion = order.promotion;
                            var value = 0;
                            if (promotion != null) {
                                value = promotion.value < 1 ? parseInt((promotion.value * order.amount) / 1000) * 1000 : promotion.value;
                                modal.find("#promotion").html(`${promotion.code} - ${toVND(value)}`);
                            } else {
                                modal.find("#promotion").html("");
                            }
                            modal.find("#shipping_fee").html(toVND(order.shipping_fee));
                            modal.find("#total").html(`<b>${toVND(order.amount - value - -order.shipping_fee)}</b>`);
                            modal.find("#payment_method").html(getPayment(order.payment_method));
                            modal.find("#status").html(getOrderStatus(order.status));

                            modal.find("#update").on("click", function() {
                                $.ajax({
                                    url: '../../api/order/updateStatus.php',
                                    type: 'POST',
                                    headers: {
                                        "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
                                    },
                                    data: {
                                        order_code: order.order_code,
                                        status: order.status
                                    },
                                    success: (res) => {
                                        if (!res.is_error) {
                                            modal.click();
                                            relatedTarget.find("#status").html(getStatus(parseInt(order.status) + 1));
                                        } else {
                                            $.ajax({
                                                url: '../../config/errorcode.php',
                                                type: 'POST',
                                                data: {
                                                    code: res.code
                                                },
                                                success: (res) => {
                                                    model.find("#err").text(res);
                                                }
                                            });
                                        }

                                    }
                                });
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
            $('#showOrder').on('hidden.bs.modal', function(event) {
                isSubmit = false;
                focusUpdate = null;
                relatedTarget = null;
                $("#search").removeAttr("data-order");
                $("#search").removeAttr("data-toggle");
                $("#search").removeAttr("data-target");
                $("#search").removeData();
            })


            loadData();

            function loadData() {

                $.ajax({
                    type: "GET",
                    url: `../../api/order/order_getAll.php?page=${page}&limit=10`,
                    headers: {
                        "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
                    },
                    success: (res) => {
                        console.log(res);
                        total = res.total;
                        $('#table').html("");
                        if (page == total) {
                            $("#btn_next").attr("class", "paginate_button previous disabled");
                        } else {
                            $("#btn_next").attr("class", "paginate_button previous");
                        }

                        if (page == 1) {
                            $("#btn_prev").attr("class", "paginate_button previous disabled ");
                        } else {
                            $("#btn_prev").attr("class", "paginate_button previous");
                        }

                        if (!res["is_error"]) {
                            $("#page").html(`Page ${page} of ${res.total}`);
                            const orders = res.data;
                            var tr;
                            for (var i = 0; i < orders.length; i++) {
                                tr = $(`<tr data-order='{"order_code":"${orders[i].order_code}"}' data-toggle="modal" data-target="#showOrder">`);
                                tr.append("<td>" + orders[i].order_code + "</td>");
                                tr.append("<td>" + orders[i].created_at + "</td>");
                                tr.append("<td>" + orders[i].branch.name + "</td>");
                                tr.append("<td>" + orders[i].address + "</td>");
                                tr.append(`<td id="status">` + getStatus(orders[i].status) + `</td>`);
                                tr.append("<td>" + orders[i].user.phone + "</td>");
                                $('#table').append(tr);
                            }
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
                if (status == 0) mStatus = `<b style="color:#F5A623">Đang chờ</b>`;
                if (status == 1) mStatus = `<b style="color:#001FE8">Đã nhận đơn</b>`;
                if (status == 2) mStatus = `<b style="color:#F7001E">Đang giao</b>`;
                if (status == 3) mStatus = `<b style="color:#36E605">Hoàn thành</b>`;
                if (status == 4) mStatus = `<b style="color:#CBCBCB">Đã hủy</b>`;
                return mStatus;
            }

            function getOrderStatus(status) {
                var mStatus = "";
                if (status == 0) mStatus = `<b style="color:#F5A623">Đang chờ <button id="update" class="btn btn-primary change"></i> Nhận đơn</button></b> `;
                if (status == 1) mStatus = `<b style="color:#001FE8">Đã nhận đơn <button id="update" class="btn btn-primary change"></i> Giao hàng</button></b> `;
                if (status == 2) mStatus = `<b style="color:#F7001E">Đang giao <button id="update" class="btn btn-primary change"></i> Hoàn thành</button></b> `;
                if (status == 3) mStatus = `<b style="color:#36E605">Hoàn thành</b>`;
                if (status == 4) mStatus = `<b style="color:#CBCBCB">Đã hủy</b>`;
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
                $(this).attr("data-order", focusUpdate);
                $(this).attr("data-toggle", "modal");
                $(this).attr("data-target", "#showOrder");
                $(this).val("");
                $(this).click();
            })
        </script>
</body>

</html>
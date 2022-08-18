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

            <div class="row">

                <div class="col-md-12">
                <h4 class="header-line">THỐNG KÊ</h4>
                    <div class="form-inline">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Thống kê:
                                    <select id="option">
                                        <option value="1">Doanh thu theo ngày</option>
                                        <option value="2">Danh thu theo tháng</option>
                                        <option value="3">Số lượng đơn hàng theo trạng thái</option>
                                        <option value="4">Số lượng đơn hàng theo ngày</option>
                                        <option value="5">Top 10 sản phẩm bán chạy</option>
                                        <option value="6">Nhập kho theo ngày</option>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <div class="row" id="time">
                            <div class="col-sm-12">
                                <label>Ngày bắt đầu: <input id="start" type="date" />

                                </label>
                                <label>Ngày kết thúc: <input id="end" type="date" />

                                </label>
                                <button class="btn btn-success" id="run">
                                    <i class="fa fa-play-circle"></i>
                                    Xem
                                </button>
                            </div>
                        </div>
                        <table class="table " id="table">

                        </table>
                    </div>
                </div>
            </div>
            <!-- /. ROW  -->

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
        var selected = "1";

        const fetchAPI = async (url, option) => {
            const res = await fetch(url, option);
            return res.json();
        }

        $("#option").on("change", () => {
            $('#table').html("");
            selected = $("#option").val();
            console.log(selected);
            if (selected != "1" && selected != "4" && selected != "6") $("#time").attr("class", "row hide");
            else $("#time").attr("class", "row");
            switch (selected) {
                case "2":
                    revenueByMonth();
                    break;
                case "3":
                    countOrder();
                    break;
                case "5":
                    topProduct();
                    break;
            }

        });


        $("#run").on("click", () => {
            if (selected == 1) revenueByDate();
            else if (selected == 4) countOrderByDate();
            else warehouse();
        });

        function revenueByDate() {
            var start = $("#start").val();
            var end = $("#end").val();
            $.ajax({
                type: "GET",
                url: `../../api/statistics/revenueByDate.php?start=${start}&end=${end}`,
                headers: {
                    "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
                },
                success: (res) => {
                    console.log(res);

                    if (!res.is_error) {
                        $('#table').html("");
                        $('#table').append(`<thead><tr><th>Ngày</th><th>Doanh thu</th></tr></thead>`);
                        var data = res.data;
                        var tr;
                        for (var i = 0; i < data.length; i++) {
                            tr = $('<tr>');
                            tr.append("<td>" + data[i].date + "</td>");
                            tr.append("<td>" + toVND(data[i].total) + "</td>");
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
        function revenueByMonth() {
            $.ajax({
                type: "GET",
                url: `../../api/statistics/revenueByMonth.php`,
                headers: {
                    "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
                },
                success: (res) => {
                    console.log(res);

                    if (!res.is_error) {
                        $('#table').html("");
                        $('#table').append(`<thead><tr><th>Tháng</th><th>Doanh thu</th></tr></thead>`);
                        var data = res.data;
                        var tr;
                        for (var i = 0; i < data.length; i++) {
                            tr = $('<tr>');
                            tr.append("<td>" + data[i].month + "</td>");
                            tr.append("<td>" + toVND(data[i].total) + "</td>");
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

        function countOrder() {
            $.ajax({
                type: "GET",
                url: `../../api/statistics/countOrder.php`,
                headers: {
                    "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
                },
                success: (res) => {
                    console.log(res);

                    if (!res.is_error) {
                        $('#table').html("");
                        $('#table').append(`<thead><tr><th>Trạng thái</th><th>Số đơn</th></tr></thead>`);
                        var data = res.data;
                        var tr;
                        var total = 0;
                        for (var i = 0; i < data.length; i++) {
                            total += parseInt(data[i].quantity);
                            tr = $('<tr>');
                            tr.append("<td>" + getOrderStatus(data[i].status) + "</td>");
                            tr.append("<td>" + data[i].quantity + "</td>");
                            $('#table').append(tr);
                        }
                        $('#table').append(`<thead><tr><th>Tổng</th><th>${total}</th></tr></thead>`);
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

        function countOrderByDate() {
            var start = $("#start").val();
            var end = $("#end").val();
            $.ajax({
                type: "GET",
                url: `../../api/statistics/countOrderByDate.php?start=${start}&end=${end}`,
                headers: {
                    "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
                },
                success: (res) => {
                    console.log(res);

                    if (!res.is_error) {
                        $('#table').html("");
                        $('#table').append(`<thead><tr><th>Ngày</th><th>Số đơn</th><th>Tỉ lệ hoàn thành đơn</th></tr></thead>`);
                        var data = res.data;
                        var tr;
                        for (var i = 0; i < data.length; i++) {
                            tr = $('<tr>');
                            tr.append("<td>" + data[i].date + "</td>");
                            tr.append("<td>" + data[i].quantity + "</td>");
                            tr.append("<td>" + toPercent(data[i].percent) + "</td>");
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

        function topProduct() {

            $.ajax({
                type: "GET",
                url: `../../api/product/product_getHighLight.php`,
                headers: {
                    "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
                },
                success: (res) => {
                    console.log(res);

                    if (!res.is_error) {
                        $('#table').html("");
                        $('#table').append(`<thead><tr><th>Mã sản phẩm</th><th>Tên sản phẩm</th><th>Số lượng bán</th></tr></thead>`);
                        var data = res.data;
                        var tr;
                        for (var i = 0; i < data.length; i++) {
                            tr = $('<tr>');
                            tr.append("<td>" + data[i].id + "</td>");
                            tr.append("<td>" + data[i].name + "</td>");
                            tr.append("<td>" + data[i].sold + "</td>");
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
        function warehouse() {
            var start = $("#start").val();
            var end = $("#end").val();
            $.ajax({
                type: "GET",
                url: `../../api/statistics/warehouse.php?start=${start}&end=${end}`,
                headers: {
                    "Authorization": 'Bearer ' + localStorage.getItem('accessToken')
                },
                success: (res) => {
                    console.log(res);

                    if (!res.is_error) {
                        $('#table').html("");
                        $('#table').append(`<thead><tr><th>Mã nhập kho</th><th>Tên sản phẩm</th><th>Số lượng</th><th>Người nhập</th><th>Ngày nhập</th>/tr></thead>`);
                        var data = res.data;
                        var tr;
                        for (var i = 0; i < data.length; i++) {
                            tr = $('<tr>');
                            tr.append("<td>" + data[i].id + "</td>");
                            tr.append("<td>" + data[i].product.name + "</td>");
                            tr.append("<td>" + data[i].quantity + "</td>");
                            tr.append("<td>" + data[i].email + "</td>");
                            tr.append("<td>" + data[i].date + "</td>");
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


        function toVND(amount) {
            return parseInt(amount).toLocaleString('vi', {
                style: 'currency',
                currency: 'VND'
            });
        }

        function toPercent(percent) {
            return (percent == null) ? "0%" : `${parseInt(percent*10000)/100}%`;
        }

        function getOrderStatus(status) {
                var mStatus = "";
                if (status == 0) mStatus = `<b style="color:#F5A623">Đang chờ</b> `;
                if (status == 1) mStatus = `<b style="color:#001FE8">Đã nhận đơn</b> `;
                if (status == 2) mStatus = `<b style="color:#F7001E">Đang giao</b> `;
                if (status == 3) mStatus = `<b style="color:#36E605">Hoàn thành</b>`;
                if (status == 4) mStatus = `<b style="color:#CBCBCB">Đã hủy</b>`;
                return mStatus;
            }
    </script>
</body>

</html>
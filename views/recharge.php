<?php 
    include('header.php'); 
    include_once $_SERVER['DOCUMENT_ROOT'].'/config/errorcode.php';

?>
<!DOCTYPE html>
<html>
<body>

   
    <!-- MENU SECTION END-->

    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">REQUEST RECHARGE</h4>
                </div>
            </div>
            <div class="row">

                <div class="col-md-12">
                    <!--    Hover Rows  -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            LIST REQUEST
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Code</th>
                                            <th>CreateAt</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
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


    <div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">CONFIRM RECHARGE</h4>
                </div>
                <div class="modal-body">
                    Are you sure ?
                    <div class="form-group" style="color: red;" id="err"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="submit" type="button" class="btn btn-primary">OK</button>                    
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

        const fetchAPI = async (url,option) => {
            const res = await fetch(url,option);
            return res.json();
        }
        $('#confirm').on('show.bs.modal',function(event){
            var modal = $(this);
            var b = $(event.relatedTarget);
            focus = b.data('recharge');
            modal.find('#err').text("");
            modal.find('#submit').on('click', async function(event){
                if (isSubmit) {
                    return;
                }
                const url = `recharge/confirm.php?ordercode=${focus.ordercode}`;
                const options = {
                    method:'get',
                    headers:{'Content-Type':'application/json',"Authorization": 'Bearer ' + localStorage.getItem('accessToken')},
                }
                try{
                    isSubmit = true;
                    const result = await fetchAPI(url,options);
                    if(result.status){

                        const url = "../views/noti/noti.php";
                        const data = new URLSearchParams();
                        data.append("action","Yêu cầu nạp tiền đã được duyệt.");
                        const vnd = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(focus.amount);
                        data.append("description",`Mã đơn #${focus.ordercode} số tiền ${vnd}.`);
                        data.append("user",focus.user);
                        const options = {
                            method:'post',
                            headers:{'Content-Type':'application/x-www-form-urlencoded',"Authorization": 'Bearer ' + localStorage.getItem('accessToken')},
                            body:data
                        };
                        try{
                            const result = await fetchAPI(url,options);
                            if(result.status){
                                window.location.href = 'recharge.php';
                            }
                            else{
                                switch(result.code){
                                    case 1001:
                                        $("#err").text("<?php echo $Err_1001; ?>");
                                        break;
                                }
                            }
                        }
                        catch(err){
                            console.log(err);
                        }

                    }
                    else{
                        if(result.code!=null){
                            isSubmit = false;
                            switch(result.code){
                                case 1001:
                                    modal.find('#err').text("<?php echo $Err_1001; ?>");
                                    break;
                        
                            }
                        }
                    }
                }
                catch(err){
                    console.log(err);
                }
            })
        })
        $('#confirm').on('hidden.bs.modal', function (event){
            isSubmit = false;
            focus = null;
        })

        $.ajax({
            type: "GET",
            url:`recharge/getWait.php`,
            headers: {"Authorization": 'Bearer ' + localStorage.getItem('accessToken')},
            success:(res) => {
                if(res["status"]==false){
                    window.location.href = "login.php";
                }
                else{
                    var tr;
                    for (var i = 0; i < res.length; i++) {
                        tr = $('<tr/>');
                        tr.append("<td>" + res[i].id+ "</td>");
                        tr.append("<td>" + res[i].ordercode + "</td>");
                        tr.append("<td>" + res[i].createAt + "</td>");
                        tr.append("<td>" + res[i].amount + "</td>");
                        tr.append(`<td><button data-recharge='{"ordercode":"${res[i].ordercode}","user":"${res[i].user}","amount":"${res[i].amount}"}' class="btn btn-success" data-toggle="modal" data-target="#confirm"><i class="fa fa-edit "></i> CONFIRM</button></td>`);
                        $('#table').append(tr);
                    }
                }
            }
        });
    </script>
</body>

</html>
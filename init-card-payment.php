<?php
$message = '';
if (isset($_POST['submit'])) {
    if (
        !isset($_POST['amount']) || !isset($_POST['txnRef'])
        || !isset($_POST['mobileNumber']) || !isset($_POST['emailAddress'])
        || !isset($_POST['callBackUrl']) || !isset($_POST['zainboxCode'])
        || !isset($_POST['token'])
    ) {
        $message = 'Fill out all fields';
    } else {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sandbox.zainpay.ng/zainbox/card/initialize/payment',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "amount" => $_POST['amount'],
                "txnRef" => $_POST['txnRef'],
                "mobileNumber" => $_POST['mobileNumber'],
                "zainboxCode" => $_POST['zainboxCode'],
                "emailAddress" => $_POST['emailAddress'],
                "callBackUrl" => $_POST['callBackUrl'],
            ]),
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer" . $token,
                "Content-Type: application/json"
            ),
        ));

        $json_response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $message = $err;
        } else {
            $response = (array) json_decode($json_response);
            if ($response['code'] == "00" && $response['status'] == 'Success') {
                header("Location: " . $response['data']);
            }
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <h1 class="text-center">Zainpay Card Payment Test</h1>
                <h3 class="text-danger"><?= $message; ?></h3>
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="text" class="form-control" name="amount" id="amount" required="true">
                    </div>

                    <div class="form-group">
                        <label for="trnx">Transaction Reference</label>
                        <input type="text" class="form-control" name="txnRef" id="trnx" required="true">
                    </div>

                    <div class="form-group">
                        <label for="mobile">Mobile</label>
                        <input type="text" class="form-control" name="mobileNumber" id="mobile" required="true">
                    </div>

                    <div class="form-group">
                        <label for="zcode">emailAddress</label>
                        <input type="emailAddress" class="form-control" name="emailAddress" id="emailAddress" required="true">
                    </div>

                    <div class="form-group">
                        <label for="failureCB">Callback</label>
                        <input type="text" class="form-control" name="callBackUrl" required="true">
                    </div>

                    <div class="form-group">
                        <label for="zcode">Zainbox Code</label>
                        <input type="text" class="form-control" name="zainboxCode" id="zainboxCode" required="true">
                    </div>

                    <div class="form-group">
                        <label for="zcode">Bearer Token</label>
                        <input type="text" class="form-control" name="token" id="token" required="true">
                    </div>

                    <div class="form-group">
                        <input type="submit" class="form-control btn btn-primary" value="Submit" name="submit" id="token">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
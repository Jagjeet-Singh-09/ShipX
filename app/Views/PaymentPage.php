<!DOCTYPE html>
<html>

<head>

    <title>Add Money To Wallet</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">


    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>


    <!-- Razorpay -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>


    <style>
        body {
            background: #f4f6f9;
        }


        .wallet-card {

            max-width: 500px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 5px 20px #ccc;

        }


        .wallet-header {

            text-align: center;
            margin-bottom: 25px;

        }


        .wallet-icon {

            font-size: 50px;
        }


        .payment-details {

            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;

        }
    </style>


</head>


<body>



    <div class="container">


        <div class="wallet-card">


            <div class="wallet-header">

                <div class="wallet-icon">
                    💳
                </div>

                <h3>
                    Add Money
                </h3>


                <p class="text-muted">
                    Recharge your wallet securely
                </p>


            </div>




            <div class="mb-3">


                <label>
                    Vendor ID
                </label>


                <input
                    type="number"
                    id="vendor_id"
                    class="form-control"
                    value="1">


            </div>





            <div class="mb-3">


                <label>
                    Amount
                </label>


                <div class="input-group">


                    <span class="input-group-text">
                        ₹
                    </span>


                    <input
                        type="number"
                        id="amount"
                        class="form-control"
                        placeholder="Enter Amount">


                </div>


            </div>






            <div class="mb-3">


                <label>
                    Gateway
                </label>


                <select id="gateway" class="form-control">


                    <option value="razorpay">
                        Razorpay
                    </option>


                </select>


            </div>





            <div class="mb-3">


                <label>
                    Payment Type
                </label>


                <select id="type" class="form-control">


                    <option value="credit">
                        Credit
                    </option>


                    <option value="debit">
                        Debit
                    </option>


                </select>


            </div>






            <button
                class="btn btn-primary w-100"
                onclick="payNow()">



                Proceed To Pay


            </button>






            <div class="payment-details">


                <h6>
                    Payment Details
                </h6>


                <p>
                    Status :
                    <span id="status">
                        Waiting
                    </span>
                </p>


                <p>
                    Order ID :
                    <span id="order_id">
                        -
                    </span>
                </p>


                <p>
                    Payment ID :
                    <span id="payment_id">
                        -
                    </span>
                </p>


            </div>




        </div>


    </div>







    <script>
        function payNow() {

            let amount = $("#amount").val();

            if (amount == "" || amount <= 0) {

                alert("Enter valid amount");
                return;

            }

            $.ajax({
                url: "/api/payment/create-order",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify({
                    vendor_id: $("#vendor_id").val(),
                    amount: amount,
                    gateway: $("#gateway").val(),
                    type: $("#type").val()
                }),

                success: function(data) {
                    $("#order_id").text(data.order_id);
                    let options = {
                        key: data.key,
                        amount: data.amount,
                        currency: "INR",
                        name: "My Website",
                        description: "Wallet Recharge",
                        order_id: data.order_id,
                        handler: function(response) {

                            $("#payment_id").text(response.razorpay_payment_id);
                            $("#status").text("Verifying...");
                            $.ajax({
                                url: "/api/payment/verify",
                                type: "POST",
                                contentType: "application/json",
                                data: JSON.stringify({
                                local_payment_id:data.local_payment_id,

                                    razorpay_order_id: response.razorpay_order_id,
                                    razorpay_payment_id: response.razorpay_payment_id,
                                    razorpay_signature: response.razorpay_signature
                                }),
                                success: function(result) {
                                    if (result.status == "success") {
                                        $("#status")
                                            .text("Success");
                                        alert(
                                            "Wallet Updated Successfully"
                                        );

                                    } else {
                                        $("#status")
                                            .text("Failed");

                                        alert("Payment Failed");


                                    }
                                }
                            });

                        },

                        prefill: {


                            name: "Vendor Name",

                            email: "vendor@test.com",

                            contact: "9999999999"


                        },




                        theme: {


                            color: "#0d6efd"


                        }


                    };




                    let razorpay = new Razorpay(options);


                    razorpay.open();



                },




                error: function() {


                    alert("Unable to create order");


                }



            });



        }
    </script>




</body>


</html>
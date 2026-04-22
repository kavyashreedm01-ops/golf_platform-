const API = "/golf_platform/api";

function subscribe(plan){

    console.log("PLAN CLICKED:", plan); // ✅ DEBUG

    fetch(API + "/create_order.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "plan=" + encodeURIComponent(plan)
    })
    .then(res => res.json())
    .then(order => {

        if(!order.id){
            alert("Order creation failed");
            return;
        }

        var options = {
            key: "rzp_test_SfpnQubAWb1FEr",
            amount: order.amount,
            currency: "INR",
            name: "Digital Heroes",
            description: plan + " Subscription",
            order_id: order.id,

            handler: function (response){

                console.log("VERIFYING PLAN:", plan); // ✅ DEBUG

                fetch(API + "/verify_payment.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        razorpay_order_id: response.razorpay_order_id,
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_signature: response.razorpay_signature,
                        plan: plan   // ✅ CRITICAL FIX
                    })
                })
                .then(res => res.json())
                .then(data => {

                    console.log("VERIFY RESPONSE:", data); // ✅ DEBUG

                    if(data.status === "success"){
                        alert(data.message);
                        window.location.href = "userdashboard.php"; // ✅ FIX
                    } else {
                        alert(data.message);
                    }
                });
            }
        };

        var rzp = new Razorpay(options);
        rzp.open();
    })
    .catch(err => {
        alert("Error: " + err.message);
    });
}
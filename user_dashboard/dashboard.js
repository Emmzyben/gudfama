 
  // document.getElementById("package").addEventListener("change", function() {
  //   document.getElementById("error").style.display = "none";
  //   calculateTotalCost();
  // });

  // document.getElementById("paymentPlan").addEventListener("change", function() {
  //   if (document.getElementById("package").value === "") {
  //     document.getElementById("error").style.display = "block";
  //   } else {
  //     document.getElementById("error").style.display = "none";
  //     calculateTotalCost();
  //   }
  // });

  // function calculateTotalCost() {
  //   var package = document.getElementById("package").value;
  //   var paymentPlan = document.getElementById("paymentPlan").value;
  //   var cost = 0;

  //   if (package === "") {
  //     return; // No package selected, do nothing
  //   }

  //   if (package === "10 Fishes") {
  //     cost = 13100;
  //   } else if (package === "25 Fishes") {
  //     cost = 32750;
  //   } else if (package === "50 Fishes") {
  //     cost = 65500;
  //   } else if (package === "100 Fishes") {
  //     cost = 131000;
  //   } else if (package === "200 Fishes") {
  //     cost = 262000;
  //   } else if (package === "500 Fishes") {
  //     cost = 655000;
  //   } else if (package === "1,000 Fishes") {
  //     cost = 1310000;
  //   } else if (package === "2,000 Fishes") {
  //     cost = 2620000;
  //   } else if (package === "5,000 Fishes") {
  //     cost = 6550000;
  //   } else if (package === "10,000 Fishes") {
  //     cost = 13100000;
  //   } else if (package === "20,000 Fishes") {
  //     cost = 26200000;
  //   }

  //   var partPayment = 0;
  //   if (paymentPlan === "full_payment") {
  //     partPayment = cost;
  //   } else if (paymentPlan === "Weekly_Payment") {
  //     partPayment = cost / 4;
  //   } else if (paymentPlan === "Monthly_Payment") {
  //     partPayment = cost / 4;
  //   } else if (paymentPlan === "Daily_Payment") {
  //     partPayment = cost / 30;
  //   }

  //   document.getElementById("partPayment").value = partPayment;
  //   document.getElementById("totalCost").value = cost;
  // }
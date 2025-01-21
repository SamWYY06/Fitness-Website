<?php

//Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "huanFitnessPal"; //Database name


//Connection
$conn = mysqli_connect($servername, $username, $password, $dbname);


//Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paymentFor = $_POST['paymentFor'];
    $paymentMethod = $_POST['paymentMethod'];
    $paymentAmount = $_POST['paymentAmount'];
    $paymentDate = date('Y-m-d H:i:s');

    // Additional fields based on payment method
    $additionalInfo = '';
    if ($paymentMethod == 'Bank Transfer') {
        $additionalInfo = "Bank: " . $_POST['bank'] . ", Account: " . $_POST['accountNumber'];
    } elseif ($paymentMethod == 'Touch n Go') {
        $additionalInfo = "Receipt uploaded";
        // Handle file upload here
    } elseif ($paymentMethod == 'Credit Card') {
        $additionalInfo = "Card: " . substr($_POST['cardNum'], -4);
    }

    $sql = "INSERT INTO payments (paymentFor, paymentMethod, paymentAmount, paymentDate, additionalInfo) 
            VALUES ('$paymentFor', '$paymentMethod', '$paymentAmount', '$paymentDate', '$additionalInfo')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Payment recorded successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}
// Display the payment options form
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Options</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
        }

        h1, h2 {
            color: #4CAF50; /* Green color for the heading */
        }

        label {
            display: block; /* Make labels block elements */
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"], select {
            width: 100%; /* Full width inputs */
            padding: 10px;
            margin-bottom: 15px; /* Space below inputs */
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* Include padding and border in the element's total width and height */
        }

        input[type="submit"], .cancel-button {
            padding: 10px 15px; /* Padding around the text */
            border-radius: 4px; /* Rounded corners */
            cursor: pointer; /* Cursor style */
            font-size: 16px; /* Font size */
            margin: 10px 0; /* Space above and below each button */
            width: 100%; /* Full width buttons */
            border: none; /* No border to maintain consistency */
        }

        input[type="submit"] {
            background-color: #4CAF50; /* Green for submit button */
            color: white; /* White text for visibility */
        }

        input[type="submit"]:hover {
            background-color: #45a049; /* Darker green on hover */
        }

        input[type="submit"]:hover {
            background-color: #45a049; /* Darker green on hover */
        }
        .cancel-button {
            background-color: #FF0000; /* Red */
            color: white;
        }

        .cancel-button:hover {
            background-color: #8B0000; /* Darker red on hover */
        }

        .tabs {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .error {
            color: red; /* Error message color */
            display: none; /* Hide error by default */
        }
    </style>
</head>
<body>
<header>
        <h1>Payment</h1>
        <p>Please choose a payment type:</p>
        </header>

<main>
    <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" onsubmit="return validateForm();" enctype="multipart/form-data">
        <label for="paymentFor">Payment For:</label>
        <select id="paymentFor" name="paymentFor" onchange="updateAmount();" required>
            <option value="" disabled selected>Select payment type</option>
            <option value="Consultation">Consultation - RM 20</option>
            <option value="Bronze Membership">Bronze Membership - RM 50</option>
            <option value="Silver Membership">Silver Membership - RM 75</option>
            <option value="Gold Membership">Gold Membership - RM 100</option>
        </select>

        <label for="amount">Amount (RM):</label>
        <input type="text" id="amount" name="paymentAmount" readonly>

        <label for="paymentMethod">Select a payment method:</label>
        <select id="paymentMethod" name="paymentMethod" onchange="showPaymentMethod();" required>
            <option value="" disabled selected>Select a payment method</option>
            <option value="Bank Transfer">Bank Transfer</option>
            <option value="Touch n Go">Touch and Go</option>
            <option value="Credit Card">Credit Card</option>
        </select>

        <div id="Bank Transfer" class="tab-content" style="display: none;">
            <h2>Bank Transfer</h2>
            <p>Ensure the transfer is to according:</p>
            <ul>
                <li>112345678912 @ Ah Huat for Maybank</li>
                <li>1234567890 @ Ah Huat for CIMB</li>
            </ul>

            <label for="bankSelect">Choose your bank:</label>
            <select id="bankSelect" name="bank" onchange="updateAccountNumber();" required>
                <option value="" disabled selected>Select a bank</option>
                <option value="Maybank">Maybank</option>
                <option value="CIMB">CIMB</option>
            </select>

            <label for="accountNumber">Account Number:</label>
            <input type="text" id="accountNumber" name="accountNumber" placeholder="Enter your account number" required>

            <label for="huatAccountNumber">Transferring to:</label>
            <input type="text" id="huatAccountNumber" name="huatAccountNumber" placeholder="Select a bank first" readonly>

            <p id="error-message" class="error"></p>
        </div>

        <div id="Touch n Go" class="tab-content" style="display: none;">
            <h2>Touch n Go</h2>
            <p>Please scan this QR code for payment:</p>
            <img src="https://i.pinimg.com/736x/a9/ef/a9/a9efa9e0d9a868bf182a920938c0c094.jpg" width="400" height="400">
            <form>
                <br><br>
                <label for="myfile">Please upload a receipt as proof:</label>
                <input type="file" id="myfile" name="myfile" required>
                <br>
            </form>
        </div>

        <div id="Credit Card" class="tab-content" style="display: none;">
            <h2>Credit Card</h2>
            <h4>Please fill in the required details:</h4>
            <form>
                <label for="cardNum">Credit Card Number:</label>
                <input type="text" id="cardNum" name="cardNum" placeholder="Enter your credit card number" maxlength="16" required>

                <label for="cardExpiryDate">Card Expiry Date:</label>
                <input type="date" id="cardExpiryDate" name="cardExpiryDate" placeholder="Enter your credit card expiry date" required><br><br>

                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv" placeholder="Enter your CVV" maxlength="3" required>

                <input type="button" value="Request OTP code"><br><br>
                <label for="OTP">OTP Code:</label>
                <input type="text" name="OTP" id="OTP" placeholder="OTP code" maxlength="6" required><br>
            </form>
        </div>

        <input type="submit" value="Submit Payment">
        <input type="button" class="cancel-button" value="Cancel" onclick="window.location.href='consultation.php';">
        </form>
    </main>
    <script>
        function showPaymentMethod() {
            var selectedPayment = document.getElementById("paymentMethod").value;
            var paymentTabs = document.getElementsByClassName("tab-content");

            for (var i = 0; i < paymentTabs.length; i++) {
                paymentTabs[i].style.display = "none";
            }

            if (selectedPayment) {
                document.getElementById(selectedPayment).style.display = "block";
            }
        }

        function validateAccountNumber() {
            const bankSelect = document.getElementById('bankSelect');
            const accountNumberInput = document.getElementById('accountNumber');
            const errorMessage = document.getElementById('error-message');
            const selectedBank = bankSelect.value;

            if (selectedBank === 'Maybank' && accountNumberInput.value.length !== 12) {
                errorMessage.textContent = "Account number for Maybank must be 12 digits.";
                errorMessage.style.display = 'block';
                return false;
            } else if (selectedBank === 'CIMB' && accountNumberInput.value.length !== 10) {
                errorMessage.textContent = "Account number for CIMB must be 10 digits.";
                errorMessage.style.display = 'block';
                return false;
            } else {
                errorMessage.style.display = 'none'; // Hide error message if valid
                return true;
            }
        }

        function updateAccountNumber() {
            const bankSelect = document.getElementById('bankSelect');
            const accountNumberInput = document.getElementById('huatAccountNumber');
            const selectedBank = bankSelect.value;

            // Set the account number based on the selected bank
            if (selectedBank === 'Maybank') {
                accountNumberInput.value = '112345678912';
            } else if (selectedBank === 'CIMB') {
                accountNumberInput.value = '1234567890';
            } else {
                accountNumberInput.value = ''; // Clear account number if no bank is selected
            }
        }

        function updateAmount() {
            const paymentFor = document.getElementById('paymentFor').value;
            const amountField = document.getElementById('amount');

            if (paymentFor === 'Consultation') {
                amountField.value = 'RM 20';
            } else if (paymentFor === 'Gold Membership') {
                amountField.value = 'RM 100';
            } else if (paymentFor === 'Silver Membership') {
                amountField.value = 'RM 75';
            } else if (paymentFor === 'Bronze Membership') {
                amountField.value = 'RM 50';
            } else {
                amountField.value = '';
            }
        }

    </script>
</body>
</html>

<?php
mysqli_close($conn);
?>
<?php
    require_once APPROOT.'/helpers/auth_check.php';
    authCheck(['Admin']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TripTrack Report</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f4f6f8;
      margin: 0;
      padding: 20px;
    }

    .report-container {
      max-width: 1000px;
      margin: auto;
      background-color: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .header {
      text-align: center;
      margin-bottom: 30px;
    }

    .header h1 {
      margin: 0;
      font-size: 2rem;
      color: #333;
    }

    .header p {
      font-size: 0.9rem;
      color: #666;
    }

    .section {
      margin-bottom: 30px;
    }

    .section-title {
      font-size: 1.5rem;
      color: #00796b;
      margin-bottom: 10px;
      border-bottom: 2px solid #b2dfdb;
      padding-bottom: 5px;
    }

    .metrics-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      margin-top: 15px;
    }

    .box {
      background-color: #e0f7fa;
      padding: 20px;
      border-radius: 10px;
      text-align: center;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .box h3 {
      margin-bottom: 8px;
      font-size: 1.1rem;
      color: #00695c;
    }

    .box span {
      font-size: 1.5rem;
      font-weight: bold;
      color: #004d40;
    }

    .sub-metrics {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      margin-top: 10px;
      padding-left: 20px;
    }

    .sub-box {
      background-color: #f1f8e9;
      padding: 15px;
      border-radius: 8px;
      flex: 1 1 200px;
      text-align: center;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .sub-box h4 {
      margin-bottom: 5px;
      font-size: 1rem;
      color: #558b2f;
    }

    .sub-box span {
      font-size: 1.3rem;
      font-weight: bold;
      color: #33691e;
    }

    .generate-btn {
      margin-top: 30px;
      display: block;
      padding: 12px 24px;
      background-color: #00796b;
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s ease;
      margin-left: auto;
      margin-right: auto;
    }

    .generate-btn:hover {
      background-color: #004d40;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    th, td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: left;
    }

    th {
      background-color: #f0f0f0;
    }

    @media print {
      .no-print {
        display: none !important;
      }
    }

    .month-selector {
      text-align: center;
      margin-bottom: 20px;
    }

    .month-selector select {
      padding: 8px;
      font-size: 1rem;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    .back-button {
      margin-bottom: 15px;
      padding: 8px 16px;
      background-color: #ccc;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 0.9rem;
    }

  </style>
</head>
<body>

<button class="back-button no-print" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/home'">Back</button>

<!-- Month/Year Dropdown -->
<div class="month-selector no-print">
  <label for="monthSelect">Select Month: </label>
  <select id="monthSelect">
    <?php
      $months = ["January", "February", "March", "April", "May", "June", 
                 "July", "August", "September", "October", "November", "December"];
      foreach ($months as $month) {
        echo "<option value=\"$month\">$month</option>";
      }
    ?>
  </select>

  <label for="yearSelect">Select Year: </label>
  <select id="yearSelect">
    <?php
      $currentYear = date('Y');
      for ($y = $currentYear; $y >= 2020; $y--) {
        echo "<option value=\"$y\">$y</option>";
      }
    ?>
  </select>
</div>

<div class="report-container" id="report-content">
  <div class="header">
    <h1>TripTrack Report</h1>
    <p id="report-month">Month: <?= date('F Y') ?></p>
  </div>

  <div class="section">
    <div class="section-title">Bookings</div>
    <div class="metrics-grid">
      <div class="box">
        <h3>Total Bookings</h3>
        <span id="totalBookings">0</span>
        <div class="sub-metrics">
          <div class="sub-box">
            <h4>Guest Bookings</h4>
            <span id="guestBookings">0</span>
          </div>
          <div class="sub-box">
            <h4>Registered Bookings</h4>
            <span id="registeredBookings">0</span>
          </div>
        </div>
      </div>
    </div>
  </div>

   <div class="section">
    <div class="section-title">Booking Cancellations</div>
    <div class="metrics-grid">
      <div class="box">
        <h3>Total Cancellations</h3>
        <span id="totalCancellations">
          <?= $data['totalCancellations'] ?? 0 ?>
        </span>
        <div class="sub-metrics">
          <div class="sub-box">
            <h4>Online Cancellations</h4>
            <span id="onlineCancellations">0</span>
          </div>
          <div class="sub-box">
            <h4>Cash Cancellations</h4>
            <span id="cashCancellations">
              <?= $data['cashCancellations'] ?? 0 ?>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="section">
    <div class ="section-title">Payments</div>
    <div class="metrics-grid">
      <div class="box">
        <h3>Total Payments</h3>
        <span id="totalPayments">0</span>
        <div class="sub-metrics">
          <div class="sub-box">
            <h4>Guest User Payments</h4>
            <span id="guestPayments">0</span>
          </div>
          <div class="sub-box">
            <h4>Registered User Payments</h4>
            <span id="registeredPayments">0</span>
          </div>
        </div>
      </div>
  </div>

  <div class="section">
    <div class="section-title">Cancellations</div>
    <div class="metrics-grid">
      <div class="box">
        <h3>Cancellations</h3>
        <div class="sub-metrics">
          <div class="sub-box">
            <h4>Refund Cancellations</h4>
              <span id="totalRefunds">0</span>
          </div>
          <div class="sub-box">
            <h4>Total Cancellation Fees</h4>
              <span id="totalCancellationFees">0</span>
          </div>
        </div>
      </div>
    </div>

  <div class="section">
    <div class="section-title">System Overview</div>
    <div class="metrics-grid">
      <div class="box">
        <h3>Total Income</h3>
        <span id="totalIncome">
          0
        </span>
      </div>
      <div class="box">
        <h3>Registered Customers</h3>
        <span id="registeredCustomers">
          0
        </span>
      </div>
    </div>
  </div>

  <div class="section">
    <div class="section-title">Employees</div>
    <div class="metrics-grid">
      <div class="box">
        <h3>Total Employees</h3>
        <span id="totalEmployees">
          0
        </span>
        <div class="sub-metrics">
          <div class="sub-box">
            <h4>Drivers</h4>
            <span id="totalDrivers">
              0
            </span>
          </div>
          <div class="sub-box">
            <h4>Conductors</h4>
            <span id="totalConductors">
              0
            </span>
          </div>
          <div class="sub-box">
            <h4>System Admins</h4>
            <span id="totalAdmins">
              0
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="section">
    <div class="section-title">Assets & Routes</div>
    <div class="metrics-grid">
      <div class="box">
        <h3>Total Routes</h3>
        <span id="totalRoutes">
          0
        </span>
      </div>
      <div class="box">
        <h3>Total Buses</h3>
        <span id="totalBuses">
          0
        </span>
      </div>
      <div class="box">
        <h3>Total Schedules</h3>
        <span id="totalSchedules">
          0
        </span>
      </div>
    </div>
  </div>
</div>



  <!-- Other sections go here with IDs for values like totalIncome, totalEmployees, etc. -->

</div>


<button class="generate-btn no-print" onclick="generatePDF()">Generate PDF</button>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
  const reportData = {
    guestBookings: <?= json_encode($data['guestBookings'] ?? []) ?>,
    registeredBookings: <?= json_encode($data['registeredBookings'] ?? []) ?>,
    totalBookings: <?= json_encode($data['totalBookings'] ?? []) ?>,
    totalCancellations: <?= json_encode($data['totalCancellations'] ?? []) ?>,
    onlineCancellations: <?= json_encode($data['onlineCancellations'] ?? []) ?>,
    cashCancellations: <?= json_encode($data['cashCancellations'] ?? []) ?>,
    guestPayments: <?= json_encode($data['totalGuestIncome'] ?? []) ?>,
    registeredPayments: <?= json_encode($data['totalRegisteredIncome'] ?? []) ?>,
    totalPayments: <?= json_encode($data['totalBookingIncome'] ?? []) ?>,
    registeredCustomers: <?= json_encode($data['registeredCustomers'] ?? []) ?>,
    totalDrivers: <?= json_encode($data['totalDrivers'] ?? []) ?>,
    totalConductors: <?= json_encode($data['totalConductors'] ?? []) ?>,
    totalAdmins: <?= json_encode($data['totalAdmins'] ?? []) ?>,
    totalEmployees: <?= json_encode($data['totalEmployees'] ?? []) ?>,
    totalIncome: <?= json_encode($data['totalIncome'] ?? []) ?>,
    totalRoutes: <?= json_encode($data['totalRoutes'] ?? []) ?>,
    totalBuses: <?= json_encode($data['totalBuses'] ?? []) ?>,
    totalSchedules: <?= json_encode($data['totalSchedules'] ?? []) ?>,
    totalRefunds: <?= json_encode($data['totalRefunds'] ?? []) ?>,
    totalCancellationFees: <?= json_encode($data['totalCancellationFees'] ?? []) ?>,


    // Add other datasets here in same format if needed
  };

  function updateReportMonth() {
    const monthName = document.getElementById("monthSelect").value;
    const year = document.getElementById("yearSelect").value;

    // Convert month name to MM format
    const monthIndex = new Date(`${monthName} 1, 2000`).getMonth() + 1;
    const formattedMonth = monthIndex.toString().padStart(2, '0');
    const key = `${year}-${formattedMonth}`;

    document.getElementById("report-month").innerText = `Month: ${monthName} ${year}`;

    // const guestPayments = findTotal(reportData.guestPayments);
    // const registeredPayments = findTotal(reportData.registeredPayments);
    // const totalPaymentIncome = guestPayments + registeredPayments;

    // Helper function to find total by key
    function findTotal(dataArray, field = 'total') {
      const found = dataArray.find(entry => entry.month === key);
      return found ? (found[field] ?? 0) : 0;
    }
    const totalPayments = parseFloat(findTotal(reportData.totalPayments));
    const totalRefunds = parseFloat(findTotal(reportData.totalRefunds, 'refund_total'));
    const totalFees = parseFloat(findTotal(reportData.totalCancellationFees, 'fee_total'));

    const calculatedIncome = totalPayments - totalRefunds + totalFees;

    console.log(calculatedIncome);
    document.getElementById("guestBookings").innerText = findTotal(reportData.guestBookings);
    document.getElementById("registeredBookings").innerText = findTotal(reportData.registeredBookings);
    document.getElementById("totalBookings").innerText = findTotal(reportData.totalBookings);
    document.getElementById("totalCancellations").innerText = findTotal(reportData.totalCancellations);
    document.getElementById("onlineCancellations").innerText = findTotal(reportData.onlineCancellations);
    document.getElementById("cashCancellations").innerText = findTotal(reportData.cashCancellations);
    document.getElementById("guestPayments").innerText = findTotal(reportData.guestPayments);
    document.getElementById("registeredPayments").innerText = findTotal(reportData.registeredPayments);
    document.getElementById("totalPayments").innerText = findTotal(reportData.totalPayments);
    document.getElementById("totalRefunds").innerText = findTotal(reportData.totalRefunds, 'refund_total');
    document.getElementById("totalCancellationFees").innerText = findTotal(reportData.totalCancellationFees, 'fee_total');
    document.getElementById("registeredCustomers").innerText = reportData.registeredCustomers ?? 0;
    document.getElementById("totalDrivers").innerText = reportData.totalDrivers?.total ?? 0;
    document.getElementById("totalConductors").innerText = reportData.totalConductors?.total ?? 0;
    document.getElementById("totalAdmins").innerText = reportData.totalAdmins?.total ?? 0;
    document.getElementById("totalEmployees").innerText = reportData.totalEmployees?.total ?? 0;
    document.getElementById("totalIncome").innerText = `Rs. ${reportData.totalIncome?.total ?? 0}`;
    document.getElementById("totalRoutes").innerText = reportData.totalRoutes?.total ?? 0;
    document.getElementById("totalSchedules").innerText = reportData.totalSchedules?.total ?? 0;
    document.getElementById("totalBuses").innerText = reportData.totalBuses?.total ?? 0;
    document.getElementById("totalIncome").innerText = `Rs. ${calculatedIncome.toFixed(2)}`;

    
  
    // Repeat above logic for other metrics if needed
  }

  window.onload = function () {
    const now = new Date();
    document.getElementById("monthSelect").value = now.toLocaleString('default', { month: 'long' });
    document.getElementById("yearSelect").value = now.getFullYear();
    updateReportMonth();
  };

  document.getElementById("monthSelect").addEventListener("change", updateReportMonth);
  document.getElementById("yearSelect").addEventListener("change", updateReportMonth);

  async function generatePDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'pt', 'a4');
    document.querySelectorAll('.no-print').forEach(el => el.style.display = 'none');
    await doc.html(document.getElementById('report-content'), {
      callback: function (pdf) {
        const month = document.getElementById("monthSelect").value;
        const year = document.getElementById("yearSelect").value;
        pdf.save(`TripTrack_Report_${month}_${year}.pdf`);
        document.querySelectorAll('.no-print').forEach(el => el.style.display = '');
      },
      margin: [20, 20, 20, 20],
      x: 10,
      y: 10,
      html2canvas: { scale: 0.5 }
    });
  }

</script>

</body>
</html>

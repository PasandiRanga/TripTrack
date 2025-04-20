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
    <button class="back-button no-print" onclick="window.location.href='<?php echo URLROOT; ?>/SuperAdminPages/home'">Back</button>
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

  </style>
</head>
<body>

<div class="report-container" id="report-content">
  <div class="header">
    <h1>TripTrack Report</h1>
    <p id="report-date"></p>
  </div>

  <div class="section">
    <div class="section-title">Bookings</div>
    <div class="metrics-grid">
      <div class="box">
        <h3>Total Bookings</h3>
        <span>320</span>
        <div class="sub-metrics">
          <div class="sub-box">
            <h4>Guest Bookings</h4>
            <span>120</span>
          </div>
          <div class="sub-box">
            <h4>Registered Bookings</h4>
            <span>200</span>
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
        <span>45</span>
        <div class="sub-metrics">
          <div class="sub-box">
            <h4>Online Cancellations</h4>
            <span>25</span>
          </div>
          <div class="sub-box">
            <h4>Cash Cancellations</h4>
            <span>20</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="section">
    <div class="section-title">System Overview</div>
    <div class="metrics-grid">
      <div class="box">
        <h3>Total Income</h3>
        <span>$12,500</span>
      </div>
      <div class="box">
        <h3>Registered Customers</h3>
        <span>540</span>
      </div>
    </div>
  </div>

  <div class="section">
    <div class="section-title">Employees</div>
    <div class="metrics-grid">
      <div class="box">
        <h3>Total Employees</h3>
        <span>80</span>
        <div class="sub-metrics">
          <div class="sub-box">
            <h4>Drivers</h4>
            <span>35</span>
          </div>
          <div class="sub-box">
            <h4>Conductors</h4>
            <span>30</span>
          </div>
          <div class="sub-box">
            <h4>System Admins</h4>
            <span>15</span>
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
        <span>42</span>
      </div>
      <div class="box">
        <h3>Total Buses</h3>
        <span>60</span>
      </div>
      <div class="box">
        <h3>Total Schedules</h3>
        <span>75</span>
      </div>
    </div>
  </div>

      <div class="section">
      <h2>Income per Bus</h2>
      <table>
        <thead>
          <tr>
            <th>Bus ID</th>
            <th>Income ( LKR )</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>BUS001</td><td>25,000</td></tr>
          <tr><td>BUS002</td><td>30,000</td></tr>
          <tr><td>BUS003</td><td>18,000</td></tr>
        </tbody>
      </table>
    </div>

    <div class="section">
      <h2>Income per Route</h2>
      <table>
        <thead>
          <tr>
            <th>Route Number</th>
            <th>Income ( LKR )</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>RT001</td><td>40,000</td></tr>
          <tr><td>RT002</td><td>55,000</td></tr>
          <tr><td>RT003</td><td>30,000</td></tr>
        </tbody>
      </table>
    </div>
  </div>

  <button class="generate-btn no-print" onclick="generatePDF()">Generate PDF</button>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
  // Set current date
  const today = new Date();
  document.getElementById("report-date").textContent = `Date: ${today.toLocaleDateString()}`;

  // PDF Generation
    async function generatePDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'pt', 'a4');

    // Hide buttons temporarily
    document.querySelectorAll('.no-print').forEach(el => el.style.display = 'none');

    await doc.html(document.getElementById('report-content'), {
        callback: function (pdf) {
        pdf.save(`TripTrack_Report_${today.toISOString().split('T')[0]}.pdf`);

        // Restore buttons after saving
        document.querySelectorAll('.no-print').forEach(el => el.style.display = '');
        },
        margin: [20, 20, 20, 20],
        autoPaging: 'text',
        x: 10,
        y: 10,
        html2canvas: {
        scale: 0.5, // Shrinks layout for A4
        windowWidth: document.body.scrollWidth
        }
    });
    }
</script>

</body>
</html>


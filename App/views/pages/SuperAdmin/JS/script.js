var sidebarOpean = false;
var sidebar = document.getElementById("sidebar");

function openSidebar(){
    if (!sidebarOpean){
        sidebar.classList.add("sidebar-responsive");
        sidebarOpean = true;
    }
}

function closeSidebar(){
    if (sidebarOpean){
        sidebar.classList.remove("sidebar-responsive");
        sidebarOpean = false;
    }
}

const barChartOptions = {
    series: [
      {
        data: [12034, 8500, 6250, 4235, 2856],
        name: 'routes_income',
      },
    ],
    chart: {
      type: 'bar',
      background: 'transparent',
      height: 350,
      toolbar: {
        show: false,
      },
    },
    colors: ['#2962ff', '#d50000', '#2e7d32', '#ff6d00', '#583cb3'],
    plotOptions: {
      bar: {
        distributed: true,
        borderRadius: 4,
        horizontal: false,
        columnWidth: '40%',
      },
    },
    dataLabels: {
      enabled: false,
    },
    fill: {
      opacity: 1,
    },
    grid: {
      borderColor: '#55596e',
      yaxis: {
        lines: {
          show: true,
        },
      },
      xaxis: {
        lines: {
          show: true,
        },
      },
    },
    legend: {
      labels: {
        colors: '#f5f7ff',
      },
      show: true,
      position: 'top',
    },
    stroke: {
      colors: ['transparent'],
      show: true,
      width: 2,
    },
    tooltip: {
      shared: true,
      intersect: false,
      theme: 'dark',
    },
    xaxis: {
      categories: ['Galle-Makubura', 'Colombo-Homagama', 'Galle-Matara', 'Kottawa-Petta', 'Colombo-Kandy'],
      title: {
        style: {
          color: '#f5f7ff',
        },
      },
      axisBorder: {
        show: true,
        color: '#55596e',
      },
      axisTicks: {
        show: true,
        color: '#55596e',
      },
      labels: {
        style: {
          colors: '#f5f7ff',
        },
      },
    },
    yaxis: {
      title: {
        text: 'Amount',
        style: {
          color: '#f5f7ff',
        },
      },
      axisBorder: {
        color: '#55596e',
        show: true,
      },
      axisTicks: {
        color: '#55596e',
        show: true,
      },
      labels: {
        style: {
          colors: '#f5f7ff',
        },
      },
    },
  };
  
  const barChart = new ApexCharts(
    document.querySelector('#bar-chart'),
    barChartOptions
  );
  barChart.render();
  


  
  // AREA CHART
  const areaChartOptions = {
    series: [
      {
        name: 'Bookings',
        data: [31, 40, 28, 51, 42, 109, 100],
      },
      {
        name: 'Cancellations',
        data: [11, 32, 22, 32, 34, 52, 41],
      },
    ],
    chart: {
      type: 'area',
      background: 'transparent',
      height: 350,
      stacked: false,
      toolbar: {
        show: false,
      },
    },
    colors: ['#00ab57', '#d50000'],
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
    dataLabels: {
      enabled: false,
    },
    fill: {
      gradient: {
        opacityFrom: 0.4,
        opacityTo: 0.1,
        shadeIntensity: 1,
        stops: [0, 100],
        type: 'vertical',
      },
      type: 'gradient',
    },
    grid: {
      borderColor: '#55596e',
      yaxis: {
        lines: {
          show: true,
        },
      },
      xaxis: {
        lines: {
          show: true,
        },
      },
    },
    legend: {
      labels: {
        colors: '#f5f7ff',
      },
      show: true,
      position: 'top',
    },
    markers: {
      size: 6,
      strokeColors: '#1b2635',
      strokeWidth: 3,
    },
    stroke: {
      curve: 'smooth',
    },
    xaxis: {
      axisBorder: {
        color: '#55596e',
        show: true,
      },
      axisTicks: {
        color: '#55596e',
        show: true,
      },
      labels: {
        offsetY: 5,
        style: {
          colors: '#f5f7ff',
        },
      },
    },
    yaxis: [
      {
        title: {
          text: 'Purchase Orders',
          style: {
            color: '#f5f7ff',
          },
        },
        labels: {
          style: {
            colors: ['#f5f7ff'],
          },
        },
      },
      {
        opposite: true,
        title: {
          text: 'Sales Orders',
          style: {
            color: '#f5f7ff',
          },
        },
        labels: {
          style: {
            colors: ['#f5f7ff'],
          },
        },
      },
    ],
    tooltip: {
      shared: true,
      intersect: false,
      theme: 'dark',
    },
  };
  
  const areaChart = new ApexCharts(
    document.querySelector('#area-chart'),
    areaChartOptions
  );
  areaChart.render();


  //logout
let popup = document.getElementById("logoutModal");
function Openpopup(){
  popup.classList.add("open-popup");
}
function showLogoutModal() {
    document.getElementById('logoutModal').style.display = 'flex';
}

// Hide the logout modal
function hideLogoutModal() {
    document.getElementById('logoutModal').style.display = 'none';
}

// Proceed with logout and redirect to login page
function proceedLogout() {
    window.location.href = "login.php"; // Replace with your login form file
}


 // Function to redirect to login page
 function proceedLogout() {
  window.location.href = "login.php"; // Replace with your login form file
}

// Function to redirect back to dashboard
function cancelLogout() {
  window.location.href = "Dashboard.php"; // Replace with your dashboard file
}
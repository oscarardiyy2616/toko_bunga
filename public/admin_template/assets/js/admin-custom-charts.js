// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';

// --- Data dari Backend (PHP) ---
// Variabel 'salesDataFromServer' dan 'revenueDataFromServer'
// akan diisi oleh data JSON yang Anda kirim dari controller PHP.
// Contoh:
// const salesDataFromServer = {"labels":["Jan","Feb","Mar"],"values":[50,75,120]};
// const revenueDataFromServer = {"labels":["Jan","Feb","Mar"],"values":[5000000,7500000,12000000]};
// Pastikan variabel ini sudah didefinisikan di tag <script> di file HTML/PHP Anda
// SEBELUM script ini dimuat.

// Fungsi untuk memformat angka sebagai mata uang Rupiah (opsional, untuk tooltip dan sumbu Y)
function formatRupiah(angka) {
  if (typeof angka !== 'number') {
    return angka;
  }
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
}

// Sales Chart (Bar Chart)
var ctxSales = document.getElementById('salesChart'); // Pastikan ID canvas ini ada di HTML Anda
if (ctxSales && typeof salesDataFromServer !== 'undefined' && salesDataFromServer) {
  var mySalesChart = new Chart(ctxSales, {
    type: 'bar',
    data: {
      labels: salesDataFromServer.labels,
      datasets: [
        {
          label: 'Jumlah Unit Terjual',
          backgroundColor: 'rgba(2,117,216,1)', // Biru
          borderColor: 'rgba(2,117,216,1)',
          data: salesDataFromServer.values,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false, // Bisa disesuaikan
      scales: {
        xAxes: [
          {
            gridLines: {
              display: false,
            },
            ticks: {
              maxTicksLimit: 6, // Sesuaikan dengan jumlah label Anda
            },
          },
        ],
        yAxes: [
          {
            ticks: {
              beginAtZero: true,
              callback: function (value) {
                return Number.isInteger(value) ? value : null;
              }, // Hanya tampilkan integer
            },
            gridLines: {
              display: true,
            },
          },
        ],
      },
      legend: {
        display: true, // Tampilkan legend jika perlu
        position: 'top',
      },
      tooltips: {
        callbacks: {
          label: function (tooltipItem, data) {
            var label = data.datasets[tooltipItem.datasetIndex].label || '';
            if (label) {
              label += ': ';
            }
            label += tooltipItem.yLabel + ' unit';
            return label;
          },
        },
      },
    },
  });
} else if (ctxSales) {
  console.warn("Data untuk Sales Chart (salesDataFromServer) tidak ditemukan atau elemen canvas 'salesChart' tidak ada.");
}

// Revenue Chart (Line Chart)
var ctxRevenue = document.getElementById('revenueChart'); // Pastikan ID canvas ini ada di HTML Anda
if (ctxRevenue && typeof revenueDataFromServer !== 'undefined' && revenueDataFromServer) {
  var myRevenueChart = new Chart(ctxRevenue, {
    type: 'line',
    data: {
      labels: revenueDataFromServer.labels,
      datasets: [
        {
          label: 'Total Pendapatan',
          lineTension: 0.3,
          backgroundColor: 'rgba(40,167,69,0.2)', // Hijau area
          borderColor: 'rgba(40,167,69,1)', // Hijau garis
          pointRadius: 5,
          pointBackgroundColor: 'rgba(40,167,69,1)',
          pointBorderColor: 'rgba(255,255,255,0.8)',
          pointHoverRadius: 5,
          pointHoverBackgroundColor: 'rgba(40,167,69,1)',
          pointHitRadius: 50,
          pointBorderWidth: 2,
          data: revenueDataFromServer.values,
        },
      ],
    },
    options: {
      // Anda bisa mengkopi dan menyesuaikan options dari chart-area-demo.js
      responsive: true,
      maintainAspectRatio: false, // Bisa disesuaikan
      scales: {
        xAxes: [
          {
            gridLines: { display: false },
            ticks: { maxTicksLimit: 7 }, // Sesuaikan
          },
        ],
        yAxes: [
          {
            ticks: {
              beginAtZero: false,
              callback: function (value) {
                return formatRupiah(value);
              },
            },
            gridLines: { color: 'rgba(0, 0, 0, .125)' },
          },
        ],
      },
      legend: {
        display: true, // Tampilkan legend jika perlu
        position: 'top',
      },
      tooltips: {
        callbacks: {
          label: function (tooltipItem, data) {
            var label = data.datasets[tooltipItem.datasetIndex].label || '';
            if (label) {
              label += ': ';
            }
            label += formatRupiah(tooltipItem.yLabel);
            return label;
          },
        },
      },
    },
  });
} else if (ctxRevenue) {
  console.warn("Data untuk Revenue Chart (revenueDataFromServer) tidak ditemukan atau elemen canvas 'revenueChart' tidak ada.");
}

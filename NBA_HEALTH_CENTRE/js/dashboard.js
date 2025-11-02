document.addEventListener("DOMContentLoaded", () => {
  console.log("dashboard.js loaded ✅");

  fetch('backend/Dashboard.php')
      .then(response => response.json())
      .then(data => {
          console.log("Fetched data:", data);

          // Update the numbers
          document.getElementById('totalPatients').textContent = data.totalPatients;
          document.getElementById('activeCases').textContent = data.activeCases;
          document.getElementById('closedCases').textContent = data.closedCases;

          // Render the chart
          const ctx = document.getElementById('casesChart').getContext('2d');
          new Chart(ctx, {
              type: 'bar',
              data: {
                  labels: ['Total Patients', 'Active Cases', 'Closed Cases'],
                  datasets: [{
                      label: 'Patient Statistics',
                      data: [data.totalPatients, data.activeCases, data.closedCases],
                      backgroundColor: [
                          'rgba(54, 162, 235, 0.6)',
                          'rgba(75, 192, 192, 0.6)',
                          'rgba(255, 99, 132, 0.6)'
                      ],
                      borderColor: [
                          'rgba(54, 162, 235, 1)',
                          'rgba(75, 192, 192, 1)',
                          'rgba(255, 99, 132, 1)'
                      ],
                      borderWidth: 1
                  }]
              },
              options: {
                  responsive: true,
                  scales: {
                      y: {
                          beginAtZero: true
                      }
                  }
              }
          });
      })
      .catch(error => {
          console.error('Error fetching dashboard data:', error);
      });
});

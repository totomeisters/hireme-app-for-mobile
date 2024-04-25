const jobListingPieChart = document.getElementById('JobListingPieChart');
const jobListingBarChart = document.getElementById('JobListingBarChart');

const data = {
    labels: [
      'Rejected',
      'Pending',
      'Verified'
    ],
    datasets: [{
      label: ' Job Listings',
      data: [100, 50, 300],
      backgroundColor: [
        'rgb(198, 40, 40)',
        'rgb(251, 192, 45)',
        'rgb(56, 142, 60)'
      ],
      hoverOffset: 4
    }]
  };

new Chart(jobListingBarChart, {
  type: 'bar',
  data: {
    labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
    datasets: [{
      label: ' Number of Applicants',
      data: [8, 19, 13, 15, 12, 13, 42, 29, 13, 65, 12, 23],
      borderWidth: 1
    }]
  },
  backgroundColor: ['rgb(56, 142, 60)'],
  options: {
    aspectRatio: 1.5,
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});

new Chart(jobListingPieChart, {
    type: 'doughnut',
    data: data,
    options: {
      aspectRatio: 1.5,
    }
});

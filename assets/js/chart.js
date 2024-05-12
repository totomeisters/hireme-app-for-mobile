document.addEventListener("DOMContentLoaded", function () {
// applicant chart
  const applicantBarChart = document.getElementById("JobListingBarChart");
  var applicantChart = new Chart(applicantBarChart, {
    type: "bar",
    data: {
      labels: [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
      ],
      datasets: [
        {
          label: " Number of Applicants",
          data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
          borderWidth: 1,
        },
      ],
    },
    options: {
      aspectRatio: 1.5,
    },
  });

  $.ajax({
    url: "../functions/getapplicantsdata.php",
    method: "POST",
    data: { jobID: 4 }, // hardcoded, will fix later
    success: function(response) {
      try {
        var jsonData = JSON.parse(response);
  
        if (Array.isArray(jsonData) && jsonData.length > 0) {
          var dataValues = Array(12).fill(0); // Initialize an array with 12 zeros
          jsonData.forEach(function(item) {
            var monthIndex = parseInt(item.month) - 1;
            if (monthIndex >= 0 && monthIndex < 12) {
              dataValues[monthIndex] = item.applicants;
            }
          });
  
          // Update chart data
          applicantChart.data.datasets[0].data = dataValues;
          applicantChart.update();
        } else {
          console.error("Response does not contain valid data:", jsonData);
        }
      } catch (error) {
        console.error("Error parsing JSON response:", error);
      }
    },
    error: function(xhr, status, error) {
      console.error("Error fetching data from the database:", error);
    },
  });

// joblisting chart
  const jobListingPieChart = document.getElementById("JobListingPieChart");
  var jobListings = new Chart(jobListingPieChart, {
    type: "doughnut",
    data: {
      labels: ["Rejected", "Pending", "Verified"],
      datasets: [
        {
          label: "Job Listings",
          data: [1, 1, 1],
          backgroundColor: [
            "rgb(56, 142, 60)",
            "rgb(251, 192, 45)",
            "rgb(198, 40, 40)",
          ],
          hoverOffset: 4,
        },
      ],
    },
    options: {
      aspectRatio: 1.5,
    },
  });

  $.ajax({
    url: "../functions/getjobsdata.php",
    method: "POST",
    data: { companyID: 2 }, //hardcoded, will fix later
    success: function (response) {
      try {
        var jsonData = JSON.parse(response);

        if (Array.isArray(jsonData) && jsonData.length > 0) {
          var labels = [];
          var dataValues = [];
          jsonData.forEach(function (item) {
            labels.push(item.label);
            dataValues.push(item.value);
          });

          jobListings.data.labels = labels;
          jobListings.data.datasets[0].data = dataValues;
          jobListings.update();
        } else {
          console.error("Response does not contain valid data:", jsonData);
        }
      } catch (error) {
        console.error("Error parsing JSON response:", error);
      }
    },
    error: function (xhr, status, error) {
      console.error("Error fetching data from the database:", error);
    },
  });
});

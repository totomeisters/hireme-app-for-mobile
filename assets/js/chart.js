document.addEventListener("DOMContentLoaded", function () {
  var companyID = document.getElementById("companyID").value;
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
    data: { companyID: companyID },
    success: function(response) {
      try {
        var dataValues = JSON.parse(response);
    
        // Update chart data
        applicantChart.data.datasets[0].data = dataValues;
        applicantChart.update();
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
      labels: ["Verified", "Rejected", "Pending"],
      datasets: [
        {
          label: "Job Listings",
          data: [0, 0, 0],
          backgroundColor: [
            "rgb(56, 142, 60)",
            "rgb(251, 192, 45)",
            "rgb(198, 40, 40)"
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
    data: { companyID: companyID },
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
          document.getElementById("ChartDiv").innerHTML = "<div class='col-lg-6 mb-2 order-1'><div class='card p-3'><p>It looks like you have not yet posted a job?</p><p>Once you post a job, two charts will appear here!</p><p>So, get verified, then make sure to add one!</p></div></div>";
          console.log("Response does not contain valid data:", jsonData);
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

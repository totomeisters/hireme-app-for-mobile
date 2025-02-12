document.addEventListener("DOMContentLoaded", function () {
    // joblisting chart
    const companyListingPieChart = document.getElementById("CompanyListingPieChart");
    var companyListings = new Chart(companyListingPieChart, {
        type: "doughnut",
        data: {
            labels: ["Verified", "Rejected", "Pending"],
            datasets: [
                {
                    label: " Companies",
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
            plugins: {
                title: {
                    display: true,
                    text: 'Company Listings',
                    font: {
                        size: 18
                    }
                }
            },
            aspectRatio: 1.5,
        },
    });
    $.ajax({
        url: "../functions/getallcompanies.php",
        method: "POST",
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

                    // Update chart data
                    companyListings.data.labels = labels;
                    companyListings.data.datasets[0].data = dataValues;
                    companyListings.update();
                }
            } catch (e) {
                console.error("Error parsing JSON response:", e);
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX request failed:", status, error);
        }
    });

    // applications chart
    const applicationsBarChart = document.getElementById('ApplicationsBarChart').getContext('2d');
        var applicationsChart = new Chart(applicationsBarChart, {
            type: 'bar',
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
                {   label: 'Verified',
                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                    backgroundColor: 'rgb(56, 142, 60)'
                }, {
                    label: 'Pending',
                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                    backgroundColor: 'rgb(251, 192, 45)'
                }, {
                    label: 'Rejected',
                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                    backgroundColor: 'rgb(198, 40, 40)'
                }, {
                    label: 'Hired',
                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                    backgroundColor: 'rgb(33, 48, 253)'
                }
                ]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Job Applications',
                        font: {
                            size: 18
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                let datasetLabel = tooltipItem.dataset.label || '';
                                let value = tooltipItem.raw;
                                return `${datasetLabel}: ${value} application/s`;
                            }
                        }
                    }
                },
                responsive: true,
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        stacked: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        }
                    }
                }
            }
        });
        $.ajax({
            url: '../functions/getallapplications.php',
            method: 'POST',
            dataType: 'json',
            success: function(response) {
                var verifiedData = response.verified;
                var pendingData = response.pending;
                var rejectedData = response.rejected;
                var hiredData = response.hired;
                
                var emptyArray = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            
                if (JSON.stringify(verifiedData) !== JSON.stringify(emptyArray)) {
                    applicationsChart.data.datasets[0].data = verifiedData;
                    applicationsChart.data.datasets[0].label = 'Verified';
                    applicationsChart.data.datasets[0].backgroundColor= 'rgb(56, 142, 60)';
                }
                if (JSON.stringify(pendingData) !== JSON.stringify(emptyArray)) {
                    applicationsChart.data.datasets[1].data = pendingData;
                    applicationsChart.data.datasets[1].label = 'Pending';
                    applicationsChart.data.datasets[1].backgroundColor= 'rgb(251, 192, 45)';
                }
                if (JSON.stringify(rejectedData) !== JSON.stringify(emptyArray)) {
                    applicationsChart.data.datasets[2].data = rejectedData;
                    applicationsChart.data.datasets[2].label = 'Rejected';
                    applicationsChart.data.datasets[2].backgroundColor= 'rgb(198, 40, 40)';
                }
                if (JSON.stringify(hiredData) !== JSON.stringify(emptyArray)) {
                    applicationsChart.data.datasets[3].data = hiredData;
                    applicationsChart.data.datasets[3].label = 'Hired';
                    applicationsChart.data.datasets[3].backgroundColor= 'rgb(33, 48, 253)';
                }
                
                applicationsChart.update();
            },            
            error: function(error) {
                console.log('Error fetching data: ', error);
            }
        });
});
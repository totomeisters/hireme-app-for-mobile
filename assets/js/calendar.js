  $(document).ready(function() {
    var calendarEl = document.getElementById('calendar');
    var cardEl = document.getElementById('card');
    var companyID = $('#companyID').val();
  
    var calendar = new FullCalendar.Calendar(calendarEl, {
      themeSystem: 'bootstrap5',
      initialView: 'dayGridMonth',
      events: function(info, successCallback) {
        $.ajax({
          url: '../functions/getinterviewdates.php',
          dataType: 'json',
          data: { companyID: companyID},
          success: function(events) {
            successCallback(events);
          }
        });
      },
      eventClick: function(clickInfo) {
        var eventId = clickInfo.event.id;
        updateCard(clickInfo.event);
      }
    });
  
    calendar.render();
  
    // Function to update card content
    function updateCard(event) {
        var cardContent = ` 
        <div class="card">      
            <div class="card-body">
              <h5 class="card-title">${event.title}</h5>
              <p class="card-text">Interview Date: ${event.extendedProps.interviewdate}</p>
              <p class="card-text">Applicant: ${event.extendedProps.name}</p>
              <p class="card-text">Job Title: ${event.extendedProps.job}</p>
              <a href="#" class="btn btn-primary">View Applicant Details</a>
            </div>
        </div> `;
        cardEl.innerHTML = cardContent;
      }  
  });
  
  $(document).ready(function() {
    var calendarEl = document.getElementById('calendar');
    var cardEl = document.getElementById('card');
    var companyID = $('#companyID').val();
  
    var calendar = new FullCalendar.Calendar(calendarEl, {
      themeSystem: 'bootstrap5', //doesn't work idk why, worked for fullcalendar v5.11.3, maybe because v6.1.11 have its own css now
      initialView: 'dayGridMonth',//<button type="button" title="This month" disabled="" aria-pressed="false" class="fc-today-button fc-button fc-button-primary">today</button>
      headerToolbar: {
        left: 'prev,next',
        center: 'title',
        right: 'today,timeGridDay,timeGridWeek,dayGridMonth,dayGridYear'
      },
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
      },
      eventColor: '#3333aa'
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
                <form action="./viewapplicant.php" method="post">
                    <input type="text" value="${event.extendedProps.applicantID}" name="applicantID" hidden>
                    <input type="text" value="${event.extendedProps.jobID}" name="jobID" hidden>
                    <input type="text" value="Interviews" name="referer" hidden>
                    <button type="submit" class="btn btn-primary">View Applicant Details</button>
                </form>
            </div>
        </div> `;
        cardEl.innerHTML = cardContent;
      }  
  });
  
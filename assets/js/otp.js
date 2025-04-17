getotp.init();

document.getElementById("email").addEventListener("input", function () {
    const emailValue = this.value.trim();
    const verifyBtn = document.getElementById("checkEmailBtn");
    verifyBtn.disabled = emailValue === "";
});

document.getElementById('checkEmailBtn').addEventListener('click', function (e) {
    e.preventDefault();
    const email = document.getElementById('email').value;

    fetch('../functions/otp.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `email=${encodeURIComponent(email)}`
      })
      .then(res => res.json())
      .then(response => {
        if (response.link) {
          window.open(response.link, '_blank');
        } else {
          alert('OTP request failed.');
        }
      })
      .catch(err => alert('Error: ' + err));
  });

    getotp.onSuccess(function (payload) {

      var callback_otp_id = payload.otp_id;
      var redirect_url = payload.redirect_url;
      
      // do something
    });

    getotp.onFailed(function (payload) {
  
      var callback_otp_id = payload.otp_id;
      var redirect_url = payload.redirect_url;
    
      // do something
    });
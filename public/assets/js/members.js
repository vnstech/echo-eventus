document.addEventListener("DOMContentLoaded", function () {
  const searchBtn   = document.getElementById("check_email_btn");
  const emailInput  = document.getElementById("userEmail");
  const feedbackDiv = document.getElementById("check-result");
  const submitBtn   = document.getElementById("submitBtn");
  const container = document.querySelector('.container');
  const eventId   = container.dataset.eventId;

  searchBtn.addEventListener("click", function () {
    const email = emailInput.value.trim();
    feedbackDiv.textContent = "";
    submitBtn.disabled = true;

    if (!email) {
      feedbackDiv.textContent = "Please enter an email.";
      return;
    }

    fetch(`/events/${eventId}/members/check-email?email=${encodeURIComponent(email)}&event_id=${eventId}`)
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          feedbackDiv.innerHTML = `<span class="text-success">${data.message} — ${data.user.name}</span>`;
          submitBtn.disabled = false;
        } else {
          feedbackDiv.innerHTML = `<span class="text-danger">${data.message}</span>`;
        }
      })
      .catch(() => {
        feedbackDiv.innerHTML = '<span class="text-danger">Error in the request.</span>';
      });
  });
});

document.querySelectorAll(".delete-event-form").forEach((form) => {
  form.addEventListener("submit", function (event) {
    const confirmed = confirm("Are you sure you want to delete this event?");
    if (!confirmed) {
      event.preventDefault();
    }
  });
});

const table = document.querySelector("table");
//Add Event
var addEventModal = document.getElementById("addevent");
var addBtn = document.getElementById("addBtn");
var addEventClose = document.getElementById("addEventClose");

//Function to open modal
addBtn.onclick = function () {
   addEventModal.style.display = "block";
}
//Function to close modal
addEventClose.onclick = function () {
   addEventModal.style.display = "none";
}

//Editing Events Modal
const editModal = document.getElementById("editEvent");
const editBtns = document.querySelectorAll("#editBtn");
const editClose = document.getElementById("editClose");

// Iterate over each button in the editBtns array.
editBtns.forEach(editBtn => {
   // Add an event listener to each button that triggers on a click event.
   editBtn.addEventListener("click", (event) => {
      // Get the closest element with the class "column-item" from the clicked button.
      const card = event.target.closest(".column-item");

      // Extract the name of the event from the card.
      const name = card.querySelector(".card-title").textContent.trim().split(': ')[1];

      // Extract and format the date of the event from the card.
      const formattedDate = (([dayMonth, year]) => `${year}-${('0' + (["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"].indexOf(dayMonth.split(" ")[0]) + 1)).slice(-2)}-${dayMonth.split(" ")[1]}`)(card.querySelector('ul li:nth-child(1)').textContent.slice(6).trim().split(", "));

      // Extract the venue of the event from the card.
      const venue = card.querySelector("ul li:nth-child(2)").textContent.slice(7).trim();

      // Extract the time of the event from the card.
      const time = card.querySelector("ul li:nth-child(3)").textContent.slice(6).trim();

      // Extract the ticket price of the event from the card.
      const ticketPrice = card.querySelector("ul li:nth-child(4)").textContent.slice(15).trim();

      // Extract the tickets capacity of the event from the card.
      const ticketsCapacity = card.querySelector("ul li:nth-child(5)").textContent.slice(18).trim();

      // Extract the event id from the closest element with the tag "td".
      const eventId = card.closest("td").dataset.id;

      // Get the form element from the editModal.
      const form = editModal.querySelector("form");

      // Fill the form with the extracted event details.
      form.querySelector('input[name="eventId"]').value = eventId;
      form.querySelector('input[name="event_name"]').value = name;
      form.querySelector('input[name="date"]').value = formattedDate;
      form.querySelector('input[name="venue"]').value = venue;
      form.querySelector('input[name="time"]').value = time;
      form.querySelector('input[name="ticket_price"]').value = ticketPrice.replace(/[^\d]/g, "");
      form.querySelector('input[name="ticket_capacity"]').value = ticketsCapacity.replace(/[^\d]/g, "");

      // Update the modal's title with the event's name.
      editModal.querySelector("#event-name-edt").textContent = `Edit "${name}" event details`;

      // Display the editModal.
      editModal.style.display = "block";
   });
});


// Function to close modal
editClose.onclick = function () {
   editModal.style.display = "none";
};


//Deleting Events Alert
var deleteModal = document.getElementById("deleteEvent");
var deleteBtns = document.querySelectorAll("#deleteBtn");
var deleteClose = document.getElementById("deleteClose");


deleteBtns.forEach(deleteBtn => {
   deleteBtn.addEventListener("click", (event) => {
      const card = event.target.closest(".column-item");
      const name = card.querySelector(".card-title").textContent.trim().split(': ')[1];

      const eventId = card.closest("td").dataset.id;
      const form = deleteModal.querySelector("form");
      form.querySelector('input[name="eventId"]').value = eventId;

      deleteModal.querySelector("#event-name-edt-del").textContent = `Delete "${name}" Event `;

      deleteModal.style.display = "block";
   });
});


//Function to close modal
deleteClose.onclick = function () {
   deleteModal.style.display = "none";
}






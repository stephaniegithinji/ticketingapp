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

// Function to open modal
editBtns.forEach(editBtn => {
   editBtn.addEventListener("click", (event) => {
      const card = event.target.closest(".column-item");
      const name = card.querySelector(".card-title").textContent.trim().split(': ')[1];
      // const originalDate = card.querySelector('ul li:nth-child(1)').textContent.slice(6).trim();
      // const formattedDate = new Date(originalDate).toLocaleDateString('en-US', { month: '2-digit', day: '2-digit', year: 'numeric' }).replace(/(\d+)\/(\d+)\/(\d+)/, '$2/$1/$3');      
      const venue = card.querySelector("ul li:nth-child(2)").textContent.slice(7).trim();
      const time = card.querySelector("ul li:nth-child(3)").textContent.slice(6).trim();
      const ticketPrice = card.querySelector("ul li:nth-child(4)").textContent.slice(15).trim();
      const ticketsCapacity = card.querySelector("ul li:nth-child(5)").textContent.slice(18).trim();

      const eventId = card.closest("td").dataset.id;
      const form = editModal.querySelector("form");
      form.querySelector('input[name="eventId"]').value = eventId;
      form.querySelector('input[name="event_name"]').value = name;
      // form.querySelector('input[name="date"]').value = formattedDate;
      form.querySelector('input[name="venue"]').value = venue;
      form.querySelector('input[name="time"]').value = time;
      form.querySelector('input[name="ticket_price"]').value = ticketPrice.replace(/[^\d]/g, "");
      form.querySelector('input[name="ticket_capacity"]').value = ticketsCapacity.replace(/[^\d]/g, "");

      editModal.querySelector("#event-name-edt").textContent = `Edit "${name}" event details`;

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

//Function to open modal
// deleteBtn.onclick = function () {

   
   
// }
//Function to close modal
deleteClose.onclick = function () {
   deleteModal.style.display = "none";
}






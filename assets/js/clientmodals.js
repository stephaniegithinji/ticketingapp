//Buying Tickets
const buyingModal = document.getElementById("buying");
const buyingBtns = document.querySelectorAll("#buyingBtn");
const buyingClose = document.getElementById("buyingClose");

// Function to open modal
buyingBtns.forEach(buyingBtn => {
   buyingBtn.addEventListener("click", (event) => {
      const card = event.target.closest(".column-item");
      const name = card.querySelector(".card-title").textContent.trim().split(': ')[1];
      const ticketPrice = card.querySelector("ul li:nth-child(4)").textContent.slice(18).trim();

      const eventId = card.closest("td").dataset.id;
      const form = buyingModal.querySelector("form");
      form.querySelector('input[name="eventId"]').value = eventId;
      form.querySelector('input[name="ticket_price"]').value = ticketPrice.replace(/[^\d]/g, "");
      buyingModal.querySelector("#event-name-buy").textContent = `Purchase ticket(s) for "${name}" event`;
      buyingModal.style.display = "block";
   });
});

// Function to close modal
buyingClose.onclick = function () {
   buyingModal.style.display = "none";
};


//QR Code 

var qrModal = document.getElementById("qr");
var qrCodeBtn = document.getElementById("qrCodeBtn");
var qrClose = document.getElementById("qrClose");

//Function to open modal
qrCodeBtn.onclick = function() {
   qrModal.style.display = "block";
}
//Function to close modal
qrClose.onclick = function() {
   qrModal.style.display = "none";
}




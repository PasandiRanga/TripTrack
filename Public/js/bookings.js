    
    document.addEventListener("DOMContentLoaded", function () {
    const currentDate = document.querySelector(".current-date");
    const daysTag = document.querySelector(".days");
    const prevNextIcons = document.querySelectorAll(".icons span");
    const column2 = document.querySelector(".column:nth-child(2)");

    

    let date = new Date(),
        currYear = date.getFullYear(),
        currMonth = date.getMonth();

    const months = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    function getBookingStatusForDate(dateStr) {
        const scheduleIds = new Set();
        let hasUpcoming = false;
        let hasPast = false;
        let hasCancels = false;
    

        // Check upcoming bookings
        upcomingBookings.forEach(booking => {
            const schedule = upcomingScheduleData.find(s => s.scheduleId === booking.schedule_id);
            if (schedule && schedule.date === dateStr) {
                hasUpcoming = true;
                scheduleIds.add(booking.schedule_id);
            }
        });

        // Check past bookings
        pastBookings.forEach(booking => {
            const schedule = pastScheduleData.find(s => s.scheduleId === booking.schedule_id);
            if (schedule && schedule.date === dateStr) {
                hasPast = true;
                scheduleIds.add(booking.schedule_id);
            }
        });

        cancelledBookings.forEach(booking => {
            // Try to find the schedule in either past or upcoming
            const schedule = pastScheduleData.find(s => s.scheduleId === booking.schedule_id) || 
                            upcomingScheduleData.find(s => s.scheduleId === booking.schedule_id);
            
            if (schedule && schedule.date === dateStr) {
                hasCancels = true;
                scheduleIds.add(booking.schedule_id);
            }
        });
        console.log("Has cancels ",hasCancels);


        return {
            hasUpcoming,
            hasPast,
            hasCancels,
            scheduleIds: Array.from(scheduleIds)
        };
    }

    function renderCalendar() {
        let firstDayofMonth = new Date(currYear, currMonth, 1).getDay();
        let lastDateofMonth = new Date(currYear, currMonth + 1, 0).getDate();
        let lastDayofMonth = new Date(currYear, currMonth, lastDateofMonth).getDay();
        let lastDateofLastMonth = new Date(currYear, currMonth, 0).getDate();
        let liTag = "";

        for (let i = firstDayofMonth; i > 0; i--) {
            liTag += `<li class="inactive">${lastDateofLastMonth - i + 1}</li>`;
        }

        for (let i = 1; i <= lastDateofMonth; i++) {
            const dateStr = `${currYear}-${String(currMonth + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
            const bookingStatus = getBookingStatusForDate(dateStr);
            
            let className = "";
            if (i === date.getDate() && currMonth === new Date().getMonth() && currYear === new Date().getFullYear()) {
                className = "active";
            }
            if (bookingStatus.hasUpcoming) {
                className += " upcoming-booking";
            } else if (bookingStatus.hasPast) {
                className += " past-booking";
            } else if (bookingStatus.hasCancels) {
                className += " cancelled-booking";
            }


            liTag += `<li class="${className}" data-date="${dateStr}">${i}</li>`;
        }

        for (let i = lastDayofMonth; i < 6; i++) {
            liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`;
        }

        currentDate.innerText = `${months[currMonth]} ${currYear}`;
        daysTag.innerHTML = liTag;

        // Attach event listeners to dates
        document.querySelectorAll(".days li").forEach(day => {
            day.addEventListener("click", function () {
                if (!this.classList.contains("inactive")) {
                    showDateDetails(this.getAttribute("data-date"));
                    column2.style.display = "block";
                }
            });
        });
    }

    function showDateDetails(dateStr) {
        const bookingStatus = getBookingStatusForDate(dateStr);
        let dateInfo = `<h3>Bookings for ${dateStr}</h3>`;

        if (!bookingStatus.scheduleIds.length) {
            dateInfo += `<p>No bookings available for this date.</p>
                         <br><br>
                         <img class="cal" src="${URLROOT}/Public/images/calendar.png" alt="calendar">`;
        } else {
            dateInfo += `<div class="booking-list">`;
            
            // Show upcoming bookings
            if (bookingStatus.hasUpcoming) {
                dateInfo += `<h4>Upcoming Bookings</h4>`;
                upcomingBookings.forEach(booking => {
                    const schedule = upcomingScheduleData.find(s => s.scheduleId === booking.schedule_id);
                    if (schedule && schedule.date === dateStr) {
                        const bus = busData.find(b => b.busId === schedule.busId);
                        dateInfo += `
                            <div class="booking-item upcoming" onclick="toggleDetails(event, this)">
                                <div class="three-dots" onclick="toggleMenu(event)">&#x22EE;</div>
                                <div class="menu">
                                    <ul>
                                        <li class="view-ticket">View Tikcet</li>
                                        <li class="cancel-booking" data-booking-id="${booking.id}" data-schedule-id="${schedule.scheduleId}">Cancel Booking</li>
                                    </ul>
                                </div>
                                <div class="booking-summary">
                                    <span class="arrow-icon">▼</span>
                                    <p><strong>${booking.from_location} - </strong>
                                    <strong>${booking.to_location}</strong></p>
                                </div>
                                <div class="booking-details">
                                    <p><strong>From:</strong> ${booking.from_location}</p>
                                    <p><strong>To:</strong> ${booking.to_location}</p>
                                    <p><strong>Time:</strong> ${schedule.departureTime}</p>
                                    <p><strong>Bus:</strong> ${bus ? bus.License_id : 'N/A'}</p>
                                    <p><strong>Booking ID:</strong> ${booking.id}</p>
                                    <p><strong>Payment Method:</strong> ${booking.paymentMethod}</p>
                                </div>
                            </div>
                        `;
                    }
                });
            }

            // Show past bookings
            if (bookingStatus.hasPast) {
                dateInfo += `<h4>Past Bookings</h4>`;
                pastBookings.forEach(booking => {
                    const schedule = pastScheduleData.find(s => s.scheduleId === booking.schedule_id);
                    if (schedule && schedule.date === dateStr) {
                        const bus = busData.find(b => b.busId === schedule.busId);
                        dateInfo += `
                            <div class="booking-item past" onclick="toggleDetails(event, this)">
                                <div class="three-dots" onclick="toggleMenu(event)">&#x22EE;</div>
                                <div class="menu">
                                    <ul>
                                        <li class="view-ticket" data-booking-id="${booking.id}" data-schedule-id="${schedule.scheduleId}">View Ticket</li>
                                        <li class="add-review" data-booking-id="${booking.id}" data-schedule-id="${schedule.scheduleId}">Add Reviews</li>
                                    </ul>
                                </div>
                                <div class="booking-summary">
                                    <span class="arrow-icon">▼</span>
                                    <p><strong>${booking.from_location} - </strong>
                                    <strong>${booking.to_location}</strong></p>
                                </div>
                                <div class="booking-details">
                                    <p><strong>From:</strong> ${booking.from_location}</p>
                                    <p><strong>To:</strong> ${booking.to_location}</p>
                                    <p><strong>Time:</strong> ${schedule.departureTime}</p>
                                    <p><strong>Bus:</strong> ${bus ? bus.License_id : 'N/A'}</p>
                                    <p><strong>Booking ID:</strong> ${booking.id}</p>
                                    <p><strong>Payment Method:</strong> ${booking.paymentMethod}</p>

                                </div>
                            </div>
                        `;
                    }
                });
            }

            //Cancel bookings
            if (bookingStatus.hasCancels){
                dateInfo += `<h4>Cancelled Bookings</h4>`;
                cancelledBookings.forEach(booking => {
                    const pastschedule = pastScheduleData.find(s => s.scheduleId === booking.schedule_id);
                    const upcomingschedule = upcomingScheduleData.find(s => s.scheduleId === booking.schedule_id);
                    
                    // Use either past or upcoming schedule based on what's available
                    const schedule = pastschedule || upcomingschedule;
                    
                    if (schedule && schedule.date === dateStr) {
                        const bus = busData.find(b => b.busId === schedule.busId);
                        dateInfo += `
                            <div class="booking-item cancelled" onclick="toggleDetails(event, this)">
                                <div class="three-dots" onclick="toggleMenu(event)">&#x22EE;</div>
                                <div class="menu">
                                    <ul>
                                        <li class="view-ticket" data-booking-id="${booking.id}" data-schedule-id="${booking.schedule_id}">view ticket</li>                                    
                                    </ul>
                                </div>
                                <div class="booking-summary">
                                    <span class="arrow-icon">▼</span>
                                    <p><strong>${booking.from_location} - </strong>
                                    <strong>${booking.to_location}</strong></p>
                                </div>
                                <div class="booking-details">
                                    <p><strong>From:</strong> ${booking.from_location}</p>
                                    <p><strong>To:</strong> ${booking.to_location}</p>
                                    <p><strong>Time:</strong> ${schedule.departureTime}</p>
                                    <p><strong>Bus:</strong> ${bus ? bus.License_id : 'N/A'}</p>
                                    <p><strong>Booking ID:</strong> ${booking.id}</p>
                                    <p><strong>Payment Method:</strong> ${booking.paymentMethod}</p>
                                    <p><strong>Cancelled Date:</strong> ${booking.time_date}</p>
                                </div>
                            </div>
                        `;
                    }
                });
            }

            
            dateInfo += `</div>`;
        }

        document.getElementById("date-info").innerHTML = dateInfo;

        // Add this after the dateInfo is inserted into the DOM
        document.querySelectorAll('.cancel-booking').forEach(item => {
            item.addEventListener('click', function() {
                const bookingId = this.getAttribute('data-booking-id');
                const scheduleId = this.getAttribute('data-schedule-id');
                
                // Find the booking and schedule objects
                const bookingObj = [...upcomingBookings, ...pastBookings].find(b => b.id == bookingId);
                const scheduleObj = upcomingScheduleData.find(s => s.scheduleId == scheduleId) || pastScheduleData.find(s => s.scheduleId == scheduleId);
                
                if (bookingObj && scheduleObj) {
                    showCancelPolicy(bookingObj, scheduleObj);
                }
            });
        });

        //rating and review box
        document.querySelectorAll('.add-review').forEach(item => {
        item.addEventListener('click', function () {
            const bookingId = this.getAttribute('data-booking-id');
            const scheduleId = this.getAttribute('data-schedule-id');

            const bookingObj = [...upcomingBookings, ...pastBookings].find(b => b.id == bookingId);
            const scheduleObj = upcomingScheduleData.find(s => s.scheduleId == scheduleId) || pastScheduleData.find(s => s.scheduleId == scheduleId);

            if (bookingObj && scheduleObj) {
                showReviewPopup(bookingObj, scheduleObj);
            }
        });
        });

        // After you attach event listeners for cancellation and reviews in the showDateDetails function,
        // Add this code to handle ticket viewing:

        document.querySelectorAll('.view-ticket').forEach(item => {
            item.addEventListener('click', function() {
                // Try to get IDs directly from this element first
                let bookingId = this.getAttribute('data-booking-id');
                let scheduleId = this.getAttribute('data-schedule-id');
                
                // If not found, try to get from siblings
                if (!bookingId || !scheduleId) {
                    const bookingItem = this.closest('.booking-item');
                    bookingId = bookingId || bookingItem.querySelector('.cancel-booking')?.getAttribute('data-booking-id') || 
                                bookingItem.querySelector('.add-review')?.getAttribute('data-booking-id');
                    scheduleId = scheduleId || bookingItem.querySelector('.cancel-booking')?.getAttribute('data-schedule-id') || 
                                bookingItem.querySelector('.add-review')?.getAttribute('data-schedule-id');
                }
                
                // Find the booking and schedule objects
                const bookingObj = [...upcomingBookings, ...pastBookings, ...cancelledBookings].find(b => b.id == bookingId);
                const scheduleObj = upcomingScheduleData.find(s => s.scheduleId == scheduleId) || 
                                pastScheduleData.find(s => s.scheduleId == scheduleId);
                const busObj = scheduleObj ? busData.find(b => b.License_id === scheduleObj.License_id || b.busId === scheduleObj.busId) : null;
                
                if (bookingObj && scheduleObj && busObj) {
                    showTicket(bookingObj, scheduleObj, busObj, userData);
                } else {
                    console.error("Missing data:", {bookingObj, scheduleObj, busObj});
                }
            });
        });
    }
    //----------Rating and Review Box----------

    // Show review popup
    function showReviewPopup(bookingObj, scheduleObj) {
        const popup = document.getElementById("reviewPopup");
        const details = document.getElementById("review-popup-details");
        
        // Find the bus associated with this schedule
        const bus = busData.find(b => b.busId === scheduleObj.busId);
        const licenseId = bus ? bus.License_id : 'N/A';
        
        console.log("License ID:", licenseId);
        console.log("Booking ID:", bookingObj.id);

        document.getElementById("license_id_input").value = licenseId;
        
        // Display booking details in the popup if needed
        // details.innerHTML = `
        //     <p><strong>From:</strong> ${bookingObj.from_location}</p>
        //     <p><strong>To:</strong> ${bookingObj.to_location}</p>
        // `;
        
        popup.classList.remove("hidden");
    
        // Attach close button listener
        const closeBtn = popup.querySelector(".cancel-btn");
        if (closeBtn) {
            closeBtn.onclick = closeReviewBox;
        }
    }

    //close reviewPopup
    document.getElementById("closeButton").addEventListener("click", function (event) {
        event.preventDefault();
        closeReviewBox();
    });

    function closeReviewBox() {
        const popup = document.getElementById("reviewPopup");
        popup.classList.add("hidden");
    }


    renderCalendar();

    prevNextIcons.forEach(icon => {
        icon.addEventListener("click", () => {
            currMonth = icon.id === "prev" ? currMonth - 1 : currMonth + 1;
            if (currMonth < 0 || currMonth > 11) {
                date = new Date(currYear, currMonth);
                currYear = date.getFullYear();
                currMonth = date.getMonth();
            } else {
                date = new Date();
            }
            renderCalendar();
        });
    });
});

function toggleMenu(event) {
    event.stopPropagation(); // Prevent bubbling
    const menu = event.target.nextElementSibling;
    console.log("Clicked menu:", menu); // Debug log

    if (menu && menu.classList.contains('menu')) {
        document.querySelectorAll('.menu').forEach(m => {
            if (m !== menu) m.classList.remove('show');
        });
        menu.classList.toggle('show');
    }
}


// Add event listener to detect clicks outside the menu
document.addEventListener("click", function(event) {
    const menu = document.querySelector('.menu');
    const threeDots = document.querySelector('.three-dots');
    
    // If the click is outside the menu and the three dots
    if (!menu.contains(event.target) && event.target !== threeDots) {
        menu.classList.remove('show');
    }
});

function showCancelPolicy(booking , schedule){
    const policypopup = document.getElementById('cancelPolicyPopup');
    const policydetails = document.getElementById('policypopup-details');
    const understandBtn = document.getElementById('understand');

    const containerDiv = document.createElement('div');
    containerDiv.className = 'cancellation-form';

    policydetails.innerHTML = `
        <div class="p-4">
            <h3 class="text-lg font-bold mb-4" align="center">Cancellation Policy</h3>
            <ul class="my-4">
                <li>Cancellation 1 or more days before departure: 10% cancellation fee </li>
                <li>Cancellation within 24 hours of departure: 50% cancellation fee</li>
            </ul>
        </div>
    `;

    // policydetails.innerHTML = '';
    // policydetails.appendChild(containerDiv);

    understandBtn.onclick = function(){
        showCancelPopup(booking , schedule);
        policypopup.classList.add('hidden');
    };

    policypopup.classList.remove('hidden');
}

function calculateCancellationFeePercentage(scheduledate) {
    const today = new Date();
    const scheduleDate = new Date(scheduledate);
    const diffTime = scheduleDate.getTime() - today.getTime();
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
    if (diffDays >= 1) {
        return 0.10; // 10% fee
    } else {
        return 0.50; // 50% fee
    }
}

function showCancelPopup(booking , schedule){
    const popup = document.getElementById('cancelPopup');
    const details = document.getElementById('popup-details');
    const confirmBtn = document.getElementById('confirmCancel');

    const cancellationFeePercentage = calculateCancellationFeePercentage(schedule.date);
    const feeAmount = booking.total_price * cancellationFeePercentage;
    const refundAmount = booking.total_price - feeAmount;

    // const containerDiv = document.createElement('div');
    // containerDiv.className = 'cancellation-form';

    if(booking.paymentMethod == 'Online'){
        details.innerHTML = `
        <div class ="cancellation-form">
            <div class="booking-details">
                <div class="detail-row">
                    <span class="detail-label">Booking ID:</span>
                    <span class="detail-value">${booking.id}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date : </span>
                    <span class="detail-value">${schedule.date}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Total Price : </span>
                    <span class="detail-value">LKR ${Number(booking.total_price).toFixed(2)}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Cancellation Fee : </span>
                    <span class="detail-value amount-highlight fee-amount">LKR ${feeAmount.toFixed(2)}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Refund Amount : </span>
                    <span class="detail-value amount-highlight refund-amount">LKR ${refundAmount.toFixed(2)}</span>
                </div>
            </div>

            <div class="bank-details-section">
                <h4 class="section-title">Bank Details for Refund</h4>
                <div class="form-group">
                    <label class="form-label">Account Holder Name</label>
                    <input type="text" id="accountName" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Bank Name</label>
                    <input type="text" id="bankName" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Account Number</label>
                    <input type="text" id="accountNumber" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Branch</label>
                    <input type="text" id="branch" class="form-input" required>
                </div>
            </div>
        </div>
                
    `;

        // details.innerHTML = '';
        // details.appendChild(containerDiv);
        
        // Add event listener to confirm button
        confirmBtn.onclick = function() {
            const bankDetails = {
                accountName: document.getElementById('accountName').value,
                bankName: document.getElementById('bankName').value,
                accountNumber: document.getElementById('accountNumber').value,
                branch: document.getElementById('branch').value
            };
            console.log(bankDetails);
                        
            if (!bankDetails.accountName || !bankDetails.bankName || 
                !bankDetails.accountNumber || !bankDetails.branch) {
                alert('Please fill in all bank details');
                return;
            }
                    
        confirmOnlineCancellation(booking.id, feeAmount, refundAmount, bankDetails,schedule.scheduleId);

        };

    } else if(booking.paymentMethod === "Cash"){
        details.innerHTML = `
            <div class="cancellation-form">
                <div class="booking-details">
                    <div class="detail-row">
                        <span class="detail-label">Booking ID:</span>
                        <span class="detail-value">${booking.id}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Route:</span>
                        <span class="detail-value">${booking.from_location} to ${booking.to_location}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Date:</span>
                        <span class="detail-value">${schedule.date}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Total Price:</span>
                        <span class="detail-value">LKR ${Number(booking.total_price).toFixed(2)}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Cancellation Fee:</span>
                        <span class="detail-value amount-highlight fee-amount">LKR ${feeAmount.toFixed(2)}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Refund Amount:</span>
                        <span class="detail-value amount-highlight refund-amount">LKR ${refundAmount.toFixed(2)}</span>
                    </div>
                </div>
                <p> Confirm will take you to the payment portal to collect the cancellation fee</p>
        `;

        // Clear previous content and append the new container
        // details.innerHTML = '';
        // details.appendChild(containerDiv);

        confirmBtn.onclick = function() {
            const queryParams = new URLSearchParams({
                bookingId: booking.id,
                cancellationFee: feeAmount,
                refundAmount: refundAmount,
                scheduleId: schedule.scheduleId
            }).toString();

        window.location.href = '/TripTrack/RegisteredPages/paymentPortal?' + queryParams;        };

    }
    popup.classList.remove('hidden');
}

// Close the popup
function closeCancelBox() {
    console.log("closeCancelBox");
    document.getElementById('cancelPopup').classList.add('hidden');
}

function closePolicyBox() {
    console.log("closeCancelBox");
    document.getElementById('cancelPolicyPopup').classList.add('hidden');
}

// Confirm cancellation (AJAX or form submission)
function confirmOnlineCancellation(bookingId, cancellationFee, refundAmount, bankDetails, scheduleId) {
    console.log(bankDetails);
    console.log(scheduleId);
    console.log(refundAmount);
    console.log(cancellationFee);
    console.log("inside confirmOnlineCancellation");
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?php echo URLROOT; ?>/RegisteredPages/cancelBooking';
            
    const data = {
        schedule_id:scheduleId, 
        booking_id: bookingId,
        cancellation_fee: cancellationFee,
        refund_amount: refundAmount,
        bank_details_json: JSON.stringify(bankDetails)
    };

    console.log(data);

            
    // Create hidden inputs for all data
    Object.entries(data).forEach(([key, value]) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        form.appendChild(input);
    });
            
    document.body.appendChild(form);
        form.submit();
}

    function openTicketBox() {
        document.getElementById('ticketBox').classList.remove('hidden');
        document.getElementById('overlay').classList.remove('hidden');
    }

    function closeTicketBox() {
        document.getElementById('ticketBox').classList.add('hidden');
        document.getElementById('overlay').classList.add('hidden');
    }

function toggleDetails(event, element) {
    // Don't toggle details if click is on the three-dots or menu
    if (event.target.closest('.three-dots') || event.target.closest('.menu')) {
        return;
    }

    const details = element.querySelector('.booking-details');
    const arrow = element.querySelector('.arrow-icon');

    if (details.classList.contains('show')) {
        details.classList.remove('show');
        arrow.textContent = '▼';
    } else {
        details.classList.add('show');
        arrow.textContent = '▲';
    }
}

    //javascript for star filling
    const allStar = document.querySelectorAll('.rating .star')
    const ratingValue = document.querySelector('.rating input')

    allStar.forEach((item,idx)=>{
        item.addEventListener('click',function(){
            let click=0
            ratingValue.value = idx + 1
            console.log(ratingValue.value)
            allStar.forEach(i=>{
                i.classList.replace('bxs-star','bx-star')
                i.classList.remove('active')
            })
            for(let i=0;i<allStar.length;i++){
                if(i<=idx){
                    allStar[i].classList.replace('bx-star','bxs-star')
                    allStar[i].classList.add('active')
                }else{
                    allStar[i].style.setProperty('--i',click)
                    click++
                }
            }
        })
    })

    // Add close button functionality
    const closeColumnBtn = document.querySelector(".close-column-btn");
    const column2 = document.querySelector(".column:nth-child(2)");
    
    closeColumnBtn.addEventListener("click", function() {
        column2.style.display = "none";
    });

function showTicket(booking, schedule, bus, user) {
    const ticketPopup = document.getElementById('ticketViewPopup');
    const ticketContent = ticketPopup.querySelector('.ticketView-popup-details');
    
    // Get the matching schedule and bus for this booking
    const bookingScheduleId = booking.schedule_id;
    
    // Handle both array and single object for schedule
    const matchedSchedule = Array.isArray(schedule) ? 
        schedule.find(s => s.scheduleId == bookingScheduleId) : 
        (schedule.scheduleId == bookingScheduleId ? schedule : null);
    
    // Get the bus license ID from the schedule
    const busLicenseId = matchedSchedule ? matchedSchedule.License_id : null;
    
    // Find the matching bus
    const matchedBus = Array.isArray(bus) ? 
        bus.find(b => b.License_id == busLicenseId) : 
        (bus.License_id == busLicenseId ? bus : null);
    
    // Format data for display
    const seats = booking.Seats || "N/A";
    const numberOfSeats = booking.number_of_seats || 1;
    const userName = user.Name || user.user_name || "User";
    const userNIC = user.NIC || user.nic || "N/A";
    
    // Get route number - it might be in different properties based on your DB structure
    const routeNumber = matchedBus ? (matchedBus.routeNumber || matchedBus.route_number) : "N/A";
    const formattedPrice = booking.total_price ? Number(booking.total_price).toFixed(2) : "0.00";
    
    let qrImagePath = booking.qrcode_path || "";
    let qrDisplay;

    if (qrImagePath) {
        // Remove any leading slashes and adjust the path structure
        qrImagePath = qrImagePath.replace(/^\/+/, '');
        
        // Use the correct path based on your file structure
        qrDisplay = `<img src="${URLROOT}/public/qrcode/qrimage/${qrImagePath}" alt="QR Code" class="ticket-qr">`;
        
        console.log("QR Image URL:", `${URLROOT}App/public/qrcode/qrimage/${qrImagePath}`);
    } else {
        qrDisplay = `<i class="fas fa-qrcode fa-5x"></i>`;
    }
    
    ticketContent.innerHTML = `
        <div class="bus-ticket">
            <div class="ticket-header">
                <div class="location">
                    <h2>${booking.from_location.toUpperCase()}&nbsp</h2>
                </div>
                <hr class="dotted-line">
                <div class="icon">
                    <i class="fas fa-bus-alt"></i>
                </div>
                <hr class="dotted-line">
                <div class="location">
                    <h2>&nbsp${booking.to_location.toUpperCase()}</h2>
                </div>
            </div>
            
            <hr class="dotted-separator">
            
            <div class="ticket-body">
                <div class="info">
                    <p><strong>Route No:</strong>&nbsp;&nbsp; ${routeNumber}</p>
                    <p><strong>Bus Number:</strong>&nbsp;&nbsp; ${busLicenseId || "N/A"}</p>
                    <p><strong>Ticket Reference No:</strong>&nbsp;&nbsp; ${booking.id}</p>
                    <p><strong>Date:</strong>&nbsp;&nbsp; ${booking.scheduleDate || (matchedSchedule ? matchedSchedule.date : "N/A")}</p>
                    <p><strong>Time:</strong>&nbsp;&nbsp; ${booking.departureTime || (matchedSchedule ? matchedSchedule.departureTime : "N/A")}</p>
                </div>
                <div class="price">
                    <h3>LKR ${formattedPrice}</h3>
                </div>
            </div>
            
            <hr class="dotted-separator">
            
            <div class="ticket-footer">
                <div class="passenger-info">
                    <p><strong>Name:</strong>&nbsp;&nbsp; ${userName}</p>
                    <p><strong>NIC No:</strong>&nbsp;&nbsp; ${userNIC}</p>
                    <p><strong>Seat Numbers:</strong>&nbsp;&nbsp; ${seats}</p>
                    <p><strong>No of Seats:</strong>&nbsp;&nbsp; ${numberOfSeats}</p>
                </div>
                <div class="qr-code">
                    ${qrDisplay}
                </div>
            </div>
        </div>
    `;

    // Show the ticket popup
    ticketPopup.classList.remove('hidden');
    
    // Ensure the popup fits on screen
    document.body.style.overflow = 'hidden'; // Prevent body scrolling when popup is active
    
    // Add event listener to close button
    document.getElementById('closeTicketBtn').addEventListener('click', function() {
        ticketPopup.classList.add('hidden');
        document.body.style.overflow = ''; // Restore body scrolling
    });
    
    // Close popup when clicking outside the ticket (optional)
    ticketPopup.addEventListener('click', function(e) {
        if (e.target === ticketPopup) {
            ticketPopup.classList.add('hidden');
            document.body.style.overflow = '';
        }
    });
    
    // Debug information to help troubleshoot
    console.log("Booking:", booking);
    console.log("Schedule:", matchedSchedule);
    console.log("Bus:", matchedBus);
    console.log("User:", user);
    console.log("Route Number:", routeNumber);
    console.log("NIC:", userNIC);
    console.log("QR Path:", qrImagePath);
}


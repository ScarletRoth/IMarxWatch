<?php
SessionManager::init();
SessionManager::requireAuth();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Seats - IMarxWatch</title>
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/footer.css">
    <link rel="stylesheet" href="/css/bookings-select.css">
</head>

<body>
    <header>
        <div class="container">
            <nav>
                <div class="nav-left">
                    <button class="hamburger" id="hamburger" aria-label="Menu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <div class="logo">🎬 IMarxWatch</div>
                </div>
                <ul id="navMenu">
                    <li><a href="/movies">Now Showing</a></li>
                    <li><a href="/user/bookings">My Bookings</a></li>
                    <li><a href="/user/profile">Profile</a></li>
                    <?php if (SessionManager::isAdmin()): ?>
                        <li><a href="/admin/dashboard">Admin Panel</a></li>
                    <?php endif; ?>
                    <li><a href="/logout" class="menu-logout">Logout</a></li>
                </ul>
                <div class="auth-buttons">
                </div>
            </nav>
        </div>
    </header>

    <div class="page-shell">
        <a href="/movies/<?php echo $session['movie_id']; ?>" class="back-link">← Back to Movie</a>

        <div class="booking-header">
            <div>
                <p class="booking-subtitle">Select your seats</p>
                <h1><?php echo html($session['movie_title']); ?></h1>
            </div>
            <div class="booking-info">
                <div class="info-chip">
                    <span class="chip-label">Time</span>
                    <span class="chip-value"><?php echo date('d M H:i', strtotime($session['starts_at'])); ?></span>
                </div>
                <div class="info-chip">
                    <span class="chip-label">Room</span>
                    <span class="chip-value"><?php echo html($session['room_name']); ?></span>
                </div>
                <div class="info-chip">
                    <span class="chip-label">Price</span>
                    <span class="chip-value">€<?php echo number_format($session['price'], 2); ?></span>
                </div>
            </div>
        </div>

        <form method="POST" action="/bookings/<?php echo $session['id']; ?>" id="bookingForm" class="booking-layout">
            <input type="hidden" name="session_id" value="<?php echo $session['id']; ?>">

            <section class="seat-map-card">
                <div class="seat-map-header">
                    <h2>Seat map</h2>
                    <div class="legend">
                        <div class="legend-item">
                            <span class="legend-color legend-available"></span>
                            Available
                        </div>
                        <div class="legend-item">
                            <span class="legend-color legend-selected"></span>
                            Selected
                        </div>
                        <div class="legend-item">
                            <span class="legend-color legend-booked"></span>
                            Booked
                        </div>
                    </div>
                </div>

                <div class="screen">SCREEN</div>

                <div class="seats-grid">
                    <?php foreach ($allSeats as $seat): ?>
                        <?php $isBooked = in_array($seat['id'], $bookedSeatIds); ?>
                        <label class="seat <?php echo $isBooked ? 'booked' : 'available'; ?>"
                            title="<?php echo $seat['seat_row'] . $seat['seat_number']; ?>">
                            <input type="checkbox"
                                name="seats[]"
                                value="<?php echo $seat['id']; ?>"
                                <?php echo $isBooked ? 'disabled' : ''; ?>
                                class="seat-checkbox">
                            <?php echo $seat['seat_row'] . $seat['seat_number']; ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </section>

            <aside class="summary-card">
                <h2>Booking Summary</h2>
                <div class="summary-row">
                    <span class="summary-label">Selected Seats</span>
                    <span class="summary-value" id="selectedList">None</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Number of Tickets</span>
                    <span class="summary-value" id="ticketCount">0</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Price per Ticket</span>
                    <span class="summary-value" id="pricePerTicket">€0.00</span>
                </div>
                <div class="summary-total">
                    <span>Total</span>
                    <span>€<span id="totalPrice">0.00</span></span>
                </div>

                <button type="submit" class="confirm-btn" onclick="return validateSelection()">Confirm Booking</button>
                <p class="summary-note">By confirming this booking, you agree to our terms. All sales are final and non-refundable.</p>
            </aside>
        </form>
    </div>

    <footer class="site-footer">
        <p>&copy; 2026 IMarxWatch. All rights reserved.</p>
    </footer>

    <script>
        const hamburger = document.getElementById('hamburger');
        const navMenu = document.getElementById('navMenu');

        hamburger.addEventListener('click', function(e) {
            e.stopPropagation();
            this.classList.toggle('active');
            navMenu.classList.toggle('active');
        });

        document.addEventListener('click', function(e) {
            if (!navMenu.contains(e.target) && !hamburger.contains(e.target)) {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
            }
        });

        const pricePerSeat = <?php echo $session['price']; ?>;
        const seatCheckboxes = document.querySelectorAll('.seat-checkbox');
        const selectedList = document.getElementById('selectedList');
        const totalPrice = document.getElementById('totalPrice');

        document.querySelectorAll('label.seat').forEach(label => {
            const checkbox = label.querySelector('.seat-checkbox');

            if (!checkbox.disabled) {
                label.style.cursor = 'pointer';
                label.addEventListener('click', function(e) {
                    e.preventDefault();
                    checkbox.checked = !checkbox.checked;
                    updateSelection();
                });
            }
        });

        function updateSelection() {
            seatCheckboxes.forEach(checkbox => {
                const label = checkbox.closest('label');
                if (checkbox.checked) {
                    label.classList.add('selected');
                } else {
                    label.classList.remove('selected');
                }
            });

            const selected = Array.from(seatCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.closest('label').title);

            if (selected.length === 0) {
                selectedList.textContent = 'None';
                totalPrice.textContent = '0.00';
            } else {
                selectedList.textContent = selected.join(', ');
                totalPrice.textContent = (selected.length * pricePerSeat).toFixed(2);
            }

            document.getElementById('ticketCount').textContent = selected.length;
        }

        document.getElementById('pricePerTicket').textContent = `€${pricePerSeat.toFixed(2)}`;

        function validateSelection() {
            const selected = Array.from(seatCheckboxes).filter(cb => cb.checked);
            if (selected.length === 0) {
                alert('Please select at least one seat');
                return false;
            }
            return true;
        }
    </script>
</body>

</html>
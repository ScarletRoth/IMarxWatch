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
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 100%);
            color: #e4e6eb;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        .back-link {
            color: #f59e0b;
            text-decoration: none;
            margin-bottom: 30px;
            display: block;
        }
        .booking-header {
            background: #1e2530;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        .booking-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 16px;
        }
        .seats-container {
            background: #1e2530;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 30px;
        }
        .screen {
            width: 90%;
            height: 40px;
            background: linear-gradient(180deg, #8b5cf6 0%, #7c3aed 100%);
            margin: 0 auto 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: white;
            font-weight: bold;
        }
        .seats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(30px, 1fr));
            gap: 8px;
            margin-bottom: 30px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        .seat {
            width: 30px;
            height: 30px;
            background: #374151;
            border: 1px solid #4b5563;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
        }
        .seat:hover {
            background: #4b5563;
        }
        .seat.available:hover {
            background: #10b981;
        }
        .seat.booked {
            background: #dc2626;
            cursor: not-allowed;
        }
        .seat.selected {
            background: #f59e0b;
            border-color: #fbbf24;
        }
        .legend {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 30px;
            font-size: 12px;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 4px;
        }
        .booking-form {
            background: #1e2530;
            padding: 20px;
            border-radius: 12px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #d1d5db;
        }
        input[type="checkbox"] {
            margin-right: 8px;
        }
        .selected-seats {
            margin: 20px 0;
            padding: 15px;
            background: #2a3340;
            border-radius: 8px;
        }
        .total-price {
            font-size: 18px;
            font-weight: 600;
            color: #f59e0b;
            margin-top: 15px;
        }
        button {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            width: 100%;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="/movies/<?php echo $session['movie_id']; ?>" class="back-link">← Back to Movie</a>
        
        <div class="booking-header">
            <h1><?php echo html($session['movie_title']); ?></h1>
            <div class="booking-info">
                <div>
                    <strong>Time:</strong> <?php echo date('d M H:i', strtotime($session['starts_at'])); ?>
                </div>
                <div>
                    <strong>Room:</strong> <?php echo html($session['room_name']); ?>
                </div>
                <div>
                    <strong>Price per seat:</strong> €<?php echo number_format($session['price'], 2); ?>
                </div>
            </div>
        </div>

        <div class="seats-container">
            <h3>Select Your Seats</h3>
            
            <div class="screen">SCREEN</div>
            
            <div class="legend">
                <div class="legend-item">
                    <div class="legend-color" style="background: #374151;"></div>
                    Available
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #dc2626;"></div>
                    Booked
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #f59e0b;"></div>
                    Selected
                </div>
            </div>

            <form method="POST" action="/bookings/<?php echo $session['id']; ?>" id="bookingForm">
                <input type="hidden" name="session_id" value="<?php echo $session['id']; ?>">
                
                <div class="seats-grid">
                    <?php foreach ($allSeats as $seat): ?>
                        <?php $isBooked = in_array($seat['id'], $bookedSeatIds); ?>
                        <label class="seat <?php echo $isBooked ? 'booked' : 'available'; ?>" 
                               title="<?php echo $seat['seat_row'] . $seat['seat_number']; ?>">
                            <input type="checkbox" 
                                   name="seats[]" 
                                   value="<?php echo $seat['id']; ?>"
                                   <?php echo $isBooked ? 'disabled' : ''; ?>
                                   class="seat-checkbox"
                                   style="display: none;">
                            <?php echo $seat['seat_row'] . $seat['seat_number']; ?>
                        </label>
                    <?php endforeach; ?>
                </div>

                <div class="selected-seats">
                    <strong>Selected Seats:</strong> <span id="selectedList">None</span>
                    <div class="total-price">Total: €<span id="totalPrice">0.00</span></div>
                </div>

                <button type="submit" onclick="return validateSelection()">Complete Booking</button>
            </form>
        </div>
    </div>

    <script>
        const pricePerSeat = <?php echo $session['price']; ?>;
        const seatCheckboxes = document.querySelectorAll('.seat-checkbox');
        const selectedList = document.getElementById('selectedList');
        const totalPrice = document.getElementById('totalPrice');

        // Add click handlers to all seat labels
        document.querySelectorAll('label.seat').forEach(label => {
            const checkbox = label.querySelector('.seat-checkbox');
            
            // Only allow clicking on available seats
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
            // Update visual state of all seats
            seatCheckboxes.forEach(checkbox => {
                const label = checkbox.closest('label');
                if (checkbox.checked) {
                    label.classList.add('selected');
                } else {
                    label.classList.remove('selected');
                }
            });

            // Update selected seats list and total price
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
        }

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

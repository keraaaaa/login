fetch('auth/count_id.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('idCount').innerText = data;
            })
            .catch(error => {
                document.getElementById('idCount').innerText = "Error loading data";
            });

        fetch('auth/home_id.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('homeCount').innerText = data;
            })
            .catch(error => {
                document.getElementById('homeCount').innerText = "Error loading data";
            });
            fetch('auth/booking_id.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('booking_count').innerText = data;
            })
            .catch(error => {
                document.getElementById('booking_count').innerText = "Error loading data";
            });
            
            fetch('auth/room_count.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('room_count').innerText = data;
            })
            .catch(error => {
                document.getElementById('room_count').innerText = "Error loading data";
            });
           

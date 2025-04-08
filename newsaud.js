    document.addEventListener('DOMContentLoaded', () => {
    const player = document.getElementById('radio-player');
    const playerBox = document.querySelector('.player-box');
    const toggleButton = document.getElementById('toggle-button');
    const stations = document.querySelectorAll('.station');

    // Set default station
    player.src = stations[0].dataset.src;

    // Add click event to stations
    stations.forEach(station => {
        station.addEventListener('click', () => {
        player.src = station.dataset.src;
        playerBox.style.opacity = '1';
        });
    });

    // Toggle player visibility
    toggleButton.addEventListener('click', () => {
        if (playerBox.style.opacity === '0') {
        playerBox.style.opacity = '1';
        toggleButton.textContent = 'Hide Player';
        } else {
        playerBox.style.opacity = '0';
        toggleButton.textContent = 'Show Player';
        }
    });
    });
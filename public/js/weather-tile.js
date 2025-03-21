// Weather Tile Functionality
document.addEventListener('DOMContentLoaded', function() {
    updateWeatherTile();
});

function updateWeatherTile() {
    // Get elements
    const greetingElement = document.getElementById('greeting');
    const dateElement = document.getElementById('current-date');
    const tempElement = document.getElementById('temperature-value');
    const humidityElement = document.getElementById('humidity-value');
    const pressureElement = document.getElementById('pressure-value');
    const emojiElement = document.getElementById('weather-emoji');

    // Update greeting based on time of day
    const greeting = getGreeting();
    if (greetingElement) greetingElement.textContent = greeting;

    // Update current date
    const formattedDate = getFormattedDate();
    if (dateElement) dateElement.textContent = formattedDate;

    // Get weather data from the page (JavaScript data variables)
    const temperature = parseFloat(tempElement?.dataset.value || 0);
    const humidity = parseFloat(humidityElement?.dataset.value || 0);
    const pressure = parseFloat(pressureElement?.dataset.value || 0);

    // Update emoji based on weather conditions
    const emoji = getWeatherEmoji(temperature, humidity, pressure);
    if (emojiElement) emojiElement.textContent = emoji;
}

function getGreeting() {
    const hour = new Date().getHours();

    if (hour >= 5 && hour < 12) {
        return 'Good morning';
    } else if (hour >= 12 && hour < 18) {
        return 'Good afternoon';
    } else {
        return 'Good evening';
    }
}

function getFormattedDate() {
    const options = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    };
    return new Date().toLocaleDateString(undefined, options);
}

function getWeatherEmoji(temperature, humidity, pressure) {
    // Default emoji if we can't determine
    if (!temperature && !humidity && !pressure) return '🌤️';

    // Simplified logic to determine weather condition
    // Temperature is primary factor, humidity and pressure influence the decision

    // Hot weather conditions
    if (temperature > 30) {
        return '☀️'; // Sunny/hot
    }
    // Warm weather
    else if (temperature > 20) {
        if (humidity > 70) return '🌥️'; // Warm but humid
        return '🌤️'; // Warm and clear
    }
    // Mild weather
    else if (temperature > 10) {
        if (humidity > 80) return '🌧️'; // Likely raining
        if (humidity > 70) return '🌦️'; // Partly cloudy with possible rain
        return '⛅'; // Partly cloudy
    }
    // Cold weather
    else if (temperature > 0) {
        if (humidity > 80) return '🌨️'; // Cold and wet, possibly snow
        return '❄️'; // Cold
    }
    // Freezing
    else {
        return '🥶'; // Freezing
    }
}
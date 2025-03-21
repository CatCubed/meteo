// Weather Tile Functionality
document.addEventListener('DOMContentLoaded', function() {
    updateWeatherTile();
    updateMeasurementTiles();
});

function updateMeasurementTiles() {
    // Update the measurement-specific tiles with appropriate icons and descriptions
    const temperatureValue = document.getElementById('temperature-value')?.dataset.value || 0;
    const humidityValue = document.getElementById('humidity-value')?.dataset.value || 0;
    const pressureValue = document.getElementById('pressure-value')?.dataset.value || 0;

    // Set appropriate icons and descriptions based on values
    updateTemperatureTile(temperatureValue);
    updateHumidityTile(humidityValue);
    updatePressureTile(pressureValue);
}

function updateTemperatureTile(value) {
    const tempIcon = document.getElementById('temperature-icon');
    const tempDesc = document.getElementById('temperature-desc');

    if (!tempIcon || !tempDesc) return;

    const tempValue = parseFloat(value);

    // Set icon and description based on temperature value
    if (tempValue > 30) {
        tempIcon.textContent = '🔥';
        tempDesc.textContent = 'Very hot temperature. Stay hydrated and avoid direct sun exposure.';
    } else if (tempValue > 20) {
        tempIcon.textContent = '☀️';
        tempDesc.textContent = 'Warm temperature. Good conditions for outdoor activities.';
    } else if (tempValue > 10) {
        tempIcon.textContent = '🌤️';
        tempDesc.textContent = 'Mild temperature. You might need a light jacket.';
    } else if (tempValue > 0) {
        tempIcon.textContent = '❄️';
        tempDesc.textContent = 'Cold temperature. Wear warm clothes.';
    } else {
        tempIcon.textContent = '🥶';
        tempDesc.textContent = 'Freezing temperature. Risk of ice - take extra precautions.';
    }
}

function updateHumidityTile(value) {
    const humIcon = document.getElementById('humidity-icon');
    const humDesc = document.getElementById('humidity-desc');

    if (!humIcon || !humDesc) return;

    const humValue = parseFloat(value);

    // Set icon and description based on humidity value
    if (humValue > 80) {
        humIcon.textContent = '💧';
        humDesc.textContent = 'Very high humidity. Air feels heavy and damp.';
    } else if (humValue > 60) {
        humIcon.textContent = '🌫️';
        humDesc.textContent = 'High humidity. Conditions may feel muggy.';
    } else if (humValue > 40) {
        humIcon.textContent = '💧';
        humDesc.textContent = 'Moderate humidity. Comfortable conditions.';
    } else if (humValue > 20) {
        humIcon.textContent = '🏜️';
        humDesc.textContent = 'Low humidity. Air feels dry.';
    } else {
        humIcon.textContent = '🏜️';
        humDesc.textContent = 'Very low humidity. Very dry conditions.';
    }
}

function updatePressureTile(value) {
    const pressIcon = document.getElementById('pressure-icon');
    const pressDesc = document.getElementById('pressure-desc');

    if (!pressIcon || !pressDesc) return;

    const pressValue = parseFloat(value);

    // Set icon and description based on pressure value
    if (pressValue > 1030) {
        pressIcon.textContent = '⬆️';
        pressDesc.textContent = 'High pressure. Typically associated with clear skies and stable weather.';
    } else if (pressValue > 1010) {
        pressIcon.textContent = '➡️';
        pressDesc.textContent = 'Normal pressure. Generally stable weather conditions.';
    } else if (pressValue > 990) {
        pressIcon.textContent = '⬇️';
        pressDesc.textContent = 'Low pressure. Might indicate changing weather conditions.';
    } else {
        pressIcon.textContent = '🌀';
        pressDesc.textContent = 'Very low pressure. Often associated with storms or significant weather changes.';
    }
}

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
document.addEventListener('DOMContentLoaded', function() {
    // Update the current weather stats
    updateCurrentWeather();

    // Initialize charts
    initializeCharts();
});

function updateCurrentWeather() {
    // Check if we have the latest measurement
    if (!latestMeasurement) {
        document.getElementById('currentTemp').textContent = 'N/A';
        document.getElementById('currentHumidity').textContent = 'N/A';
        document.getElementById('currentPressure').textContent = 'N/A';
        document.getElementById('lastUpdated').textContent = 'N/A';
        document.getElementById('weatherEmoji').textContent = '❓';
        return;
    }

    // Format and display the current weather stats
    document.getElementById('currentTemp').textContent = latestMeasurement.temperature.toFixed(1);
    document.getElementById('currentHumidity').textContent = latestMeasurement.humidity.toFixed(1);
    document.getElementById('currentPressure').textContent = latestMeasurement.pressure.toFixed(1);

    // Format the date
    const date = new Date(latestMeasurement.createdAt);
    document.getElementById('lastUpdated').textContent = date.toLocaleString();

    // Set weather emoji based on conditions
    document.getElementById('weatherEmoji').textContent = getWeatherEmoji(
        latestMeasurement.temperature,
        latestMeasurement.humidity,
        latestMeasurement.pressure
    );
}

function getWeatherEmoji(temperature, humidity, pressure) {
    // Simple logic to determine weather emoji based on conditions
    if (temperature > 30) return '☀️'; // Hot sun
    if (temperature > 20) {
        if (humidity > 80) return '🌧️'; // Rain
        return '🌤️'; // Sun with cloud
    }
    if (temperature > 10) {
        if (humidity > 70) return '🌧️'; // Rain
        return '⛅'; // Partly cloudy
    }
    if (temperature > 0) {
        if (humidity > 80) return '🌧️'; // Rain
        return '☁️'; // Cloudy
    }
    return '❄️'; // Snow/cold
}

function initializeCharts() {
    // Temperature Chart
    if (temperatureData && temperatureData.length > 0) {
        const temperatureOptions = {
            series: [{
                name: 'Temperature',
                data: temperatureData
            }],
            chart: {
                type: 'line',
                height: 350,
                zoom: {
                    enabled: true
                },
                toolbar: {
                    show: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            title: {
                text: 'Temperature Trends',
                align: 'left'
            },
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'],
                    opacity: 0.5
                },
            },
            xaxis: {
                type: 'datetime',
            },
            yaxis: {
                title: {
                    text: 'Temperature (°C)'
                }
            },
            tooltip: {
                x: {
                    format: 'dd MMM yyyy HH:mm'
                }
            },
            colors: ['#f44336']
        };

        const temperatureChart = new ApexCharts(document.querySelector("#temperatureChart"), temperatureOptions);
        temperatureChart.render();
    }

    // Humidity Chart
    if (humidityData && humidityData.length > 0) {
        const humidityOptions = {
            series: [{
                name: 'Humidity',
                data: humidityData
            }],
            chart: {
                type: 'line',
                height: 350,
                zoom: {
                    enabled: true
                },
                toolbar: {
                    show: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            title: {
                text: 'Humidity Trends',
                align: 'left'
            },
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'],
                    opacity: 0.5
                },
            },
            xaxis: {
                type: 'datetime',
            },
            yaxis: {
                title: {
                    text: 'Humidity (%)'
                },
                min: 0,
                max: 100
            },
            tooltip: {
                x: {
                    format: 'dd MMM yyyy HH:mm'
                }
            },
            colors: ['#2196f3']
        };

        const humidityChart = new ApexCharts(document.querySelector("#humidityChart"), humidityOptions);
        humidityChart.render();
    }

    // Pressure Chart
    if (pressureData && pressureData.length > 0) {
        const pressureOptions = {
            series: [{
                name: 'Pressure',
                data: pressureData
            }],
            chart: {
                type: 'line',
                height: 350,
                zoom: {
                    enabled: true
                },
                toolbar: {
                    show: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            title: {
                text: 'Pressure Trends',
                align: 'left'
            },
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'],
                    opacity: 0.5
                },
            },
            xaxis: {
                type: 'datetime',
            },
            yaxis: {
                title: {
                    text: 'Pressure (hPa)'
                }
            },
            tooltip: {
                x: {
                    format: 'dd MMM yyyy HH:mm'
                }
            },
            colors: ['#4caf50']
        };

        const pressureChart = new ApexCharts(document.querySelector("#pressureChart"), pressureOptions);
        pressureChart.render();
    }
}
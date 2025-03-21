// Dashboard charts for weather measurements
document.addEventListener('DOMContentLoaded', function() {
    // Create the charts
    initTemperatureChart();
    initHumidityChart();
    initPressureChart();
});

// Helper function to generate timestamps for last 24 hours (hourly)
function getLast24HoursLabels() {
    const labels = [];
    const now = new Date();

    for (let i = 24; i > 0; i--) {
        const d = new Date(now);
        d.setHours(d.getHours() - i);
        labels.push(d.getHours() + ':00');
    }

    return labels;
}

// Helper function to generate sample data based on current value
function generateSampleData(currentValue, fluctuation = 5, points = 24) {
    const data = [];
    const value = parseFloat(currentValue);

    if (isNaN(value)) return Array(points).fill(0);

    for (let i = 0; i < points; i++) {
        // Create a somewhat realistic pattern with some randomness
        const timeOfDay = i % 24;
        let modifier = 0;

        // For temperature: cooler at night, warmer during day
        if (fluctuation > 3) { // Assuming temperature has higher fluctuation
            modifier = timeOfDay >= 8 && timeOfDay <= 18
                ? (Math.random() * (fluctuation/2)) // Warmer during day
                : -(Math.random() * (fluctuation/2)); // Cooler at night
        } else {
            modifier = (Math.random() - 0.5) * fluctuation;
        }

        data.push((value + modifier).toFixed(1));
    }

    return data;
}

function initTemperatureChart() {
    const temperatureElement = document.getElementById('temperature-value');
    if (!temperatureElement) return;

    const currentTemp = parseFloat(temperatureElement.dataset.value || 0);
    const timeLabels = getLast24HoursLabels();
    const tempData = generateSampleData(currentTemp, 8); // Higher fluctuation for temperature

    const options = {
        series: [{
            name: 'Temperature',
            data: tempData
        }],
        chart: {
            height: 300,
            type: 'line',
            zoom: {
                enabled: false
            },
            toolbar: {
                show: false
            },
            fontFamily: 'inherit',
            background: 'transparent'
        },
        title: {
            text: 'Last 24 Hours',
            align: 'center',
            style: {
                fontSize: '14px',
                color: '#fff',
                opacity: 0.7
            }
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        colors: ['#FF5F6D'],
        grid: {
            borderColor: 'rgba(255, 255, 255, 0.1)',
            row: {
                colors: ['transparent'],
                opacity: 0.5
            },
            padding: {
                left: 15,
                right: 15
            }
        },
        markers: {
            size: 3,
            strokeWidth: 0,
            hover: {
                size: 5
            }
        },
        xaxis: {
            categories: timeLabels,
            labels: {
                style: {
                    colors: '#fff',
                    opacity: 0.7
                }
            },
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            }
        },
        yaxis: {
            labels: {
                formatter: function(val) {
                    return val.toFixed(1) + '°C';
                },
                style: {
                    colors: '#fff',
                    opacity: 0.7
                }
            }
        },
        tooltip: {
            theme: 'dark',
            y: {
                formatter: function(val) {
                    return val + '°C';
                }
            }
        }
    };

    const chart = new ApexCharts(document.querySelector("#temperature-chart"), options);
    chart.render();
}

function initHumidityChart() {
    const humidityElement = document.getElementById('humidity-value');
    if (!humidityElement) return;

    const currentHumidity = parseFloat(humidityElement.dataset.value || 0);
    const timeLabels = getLast24HoursLabels();
    const humidityData = generateSampleData(currentHumidity, 15); // Humidity can fluctuate more

    const options = {
        series: [{
            name: 'Humidity',
            data: humidityData
        }],
        chart: {
            height: 300,
            type: 'line',
            zoom: {
                enabled: false
            },
            toolbar: {
                show: false
            },
            fontFamily: 'inherit',
            background: 'transparent'
        },
        title: {
            text: 'Last 24 Hours',
            align: 'center',
            style: {
                fontSize: '14px',
                color: '#fff',
                opacity: 0.7
            }
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        colors: ['#ffffff'],
        grid: {
            borderColor: 'rgba(255, 255, 255, 0.1)',
            row: {
                colors: ['transparent'],
                opacity: 0.5
            },
            padding: {
                left: 15,
                right: 15
            }
        },
        markers: {
            size: 3,
            strokeWidth: 0,
            hover: {
                size: 5
            }
        },
        xaxis: {
            categories: timeLabels,
            labels: {
                style: {
                    colors: '#fff',
                    opacity: 0.7
                }
            },
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            }
        },
        yaxis: {
            labels: {
                formatter: function(val) {
                    return val.toFixed(1) + '%';
                },
                style: {
                    colors: '#fff',
                    opacity: 0.7
                }
            }
        },
        tooltip: {
            theme: 'dark',
            y: {
                formatter: function(val) {
                    return val + '%';
                }
            }
        }
    };

    const chart = new ApexCharts(document.querySelector("#humidity-chart"), options);
    chart.render();
}

function initPressureChart() {
    const pressureElement = document.getElementById('pressure-value');
    if (!pressureElement) return;

    const currentPressure = parseFloat(pressureElement.dataset.value || 0);
    const timeLabels = getLast24HoursLabels();
    const pressureData = generateSampleData(currentPressure, 2); // Pressure typically fluctuates less

    const options = {
        series: [{
            name: 'Pressure',
            data: pressureData
        }],
        chart: {
            height: 300,
            type: 'line',
            zoom: {
                enabled: false
            },
            toolbar: {
                show: false
            },
            fontFamily: 'inherit',
            background: 'transparent'
        },
        title: {
            text: 'Last 24 Hours',
            align: 'center',
            style: {
                fontSize: '14px',
                color: '#fff',
                opacity: 0.7
            }
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        colors: ['#ffffff'],
        grid: {
            borderColor: 'rgba(255, 255, 255, 0.1)',
            row: {
                colors: ['transparent'],
                opacity: 0.5
            },
            padding: {
                left: 15,
                right: 15
            }
        },
        markers: {
            size: 3,
            strokeWidth: 0,
            hover: {
                size: 5
            }
        },
        xaxis: {
            categories: timeLabels,
            labels: {
                style: {
                    colors: '#fff',
                    opacity: 0.7
                }
            },
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            }
        },
        yaxis: {
            labels: {
                formatter: function(val) {
                    return val.toFixed(1) + ' hPa';
                },
                style: {
                    colors: '#fff',
                    opacity: 0.7
                }
            }
        },
        tooltip: {
            theme: 'dark',
            y: {
                formatter: function(val) {
                    return val + ' hPa';
                }
            }
        }
    };

    const chart = new ApexCharts(document.querySelector("#pressure-chart"), options);
    chart.render();
}
document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    createDailyComparisonChart();
    createMonthlyTrendsChart();
    createYearlyTrendsChart();
});

function createDailyComparisonChart() {
    // Create a chart that compares today's and yesterday's data
    if (!todayData || !yesterdayData) {
        console.warn('Daily data missing for comparison chart');
        return;
    }

    // Determine the time points to use (prefer today's if available)
    const timeLabels = todayData.time_labels.length > 0 ? todayData.time_labels : yesterdayData.time_labels;

    // If no time labels are available, don't create the chart
    if (timeLabels.length === 0) {
        console.warn('No time labels available for daily comparison chart');
        return;
    }

    const temperatureOptions = {
        series: [
            {
                name: `Today (${todayData.date})`,
                data: todayData.temperature.values
            },
            {
                name: `Yesterday (${yesterdayData.date})`,
                data: yesterdayData.temperature.values
            }
        ],
        chart: {
            type: 'line',
            height: 350,
            toolbar: {
                show: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        title: {
            text: 'Temperature Comparison',
            align: 'left'
        },
        xaxis: {
            categories: timeLabels
        },
        yaxis: {
            title: {
                text: 'Temperature (°C)'
            }
        },
        tooltip: {
            shared: true,
            intersect: false
        },
        colors: ['#f44336', '#FF9800']
    };

    const temperatureChart = new ApexCharts(document.querySelector("#dailyComparisonChart"), temperatureOptions);
    temperatureChart.render();
}

function createMonthlyTrendsChart() {
    if (!monthlyData || !monthlyData.labels || monthlyData.labels.length === 0) {
        console.warn('No monthly data available');
        return;
    }

    const options = {
        series: [
            {
                name: 'Temperature (°C)',
                data: monthlyData.temperature
            },
            {
                name: 'Humidity (%)',
                data: monthlyData.humidity
            },
            {
                name: 'Pressure (hPa/10)',
                data: monthlyData.pressure.map(val => val / 10) // Scale down pressure for better visualization
            }
        ],
        chart: {
            type: 'line',
            height: 350,
            toolbar: {
                show: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        title: {
            text: 'Monthly Weather Trends',
            align: 'left'
        },
        xaxis: {
            categories: monthlyData.labels
        },
        yaxis: {
            title: {
                text: 'Value'
            }
        },
        tooltip: {
            y: {
                formatter: function(value, { seriesIndex }) {
                    if (seriesIndex === 2) {
                        // This is the pressure series, so multiply by 10 to get original value
                        return (value * 10).toFixed(1) + ' hPa';
                    } else if (seriesIndex === 1) {
                        // Humidity
                        return value.toFixed(1) + '%';
                    } else {
                        // Temperature
                        return value.toFixed(1) + '°C';
                    }
                }
            }
        },
        colors: ['#f44336', '#2196f3', '#4caf50']
    };

    const chart = new ApexCharts(document.querySelector("#monthlyTrendsChart"), options);
    chart.render();
}

function createYearlyTrendsChart() {
    if (!yearlyData || !yearlyData.labels || yearlyData.labels.length === 0) {
        console.warn('No yearly data available');
        return;
    }

    const options = {
        series: [
            {
                name: 'Temperature (°C)',
                data: yearlyData.temperature
            },
            {
                name: 'Humidity (%)',
                data: yearlyData.humidity
            },
            {
                name: 'Pressure (hPa/10)',
                data: yearlyData.pressure.map(val => val / 10) // Scale down pressure for better visualization
            }
        ],
        chart: {
            type: 'line',
            height: 350,
            toolbar: {
                show: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        title: {
            text: 'Yearly Weather Trends',
            align: 'left'
        },
        xaxis: {
            categories: yearlyData.labels
        },
        yaxis: {
            title: {
                text: 'Value'
            }
        },
        tooltip: {
            y: {
                formatter: function(value, { seriesIndex }) {
                    if (seriesIndex === 2) {
                        // This is the pressure series, so multiply by 10 to get original value
                        return (value * 10).toFixed(1) + ' hPa';
                    } else if (seriesIndex === 1) {
                        // Humidity
                        return value.toFixed(1) + '%';
                    } else {
                        // Temperature
                        return value.toFixed(1) + '°C';
                    }
                }
            }
        },
        colors: ['#f44336', '#2196f3', '#4caf50']
    };

    const chart = new ApexCharts(document.querySelector("#yearlyTrendsChart"), options);
    chart.render();
}
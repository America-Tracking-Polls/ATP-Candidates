// ATP Professional Analytics Charts
// Uses Chart.js for standard visualizations and custom JS for polling bars

document.addEventListener('DOMContentLoaded', () => {
    initCharts();
    initPollingBars();
});

function initCharts() {
    // Official Colors
    const colors = {
        navy: '#0B1C33',
        red: '#E60000',
        cream: '#F9F9F7',
        grey: '#e0e0e0',
        text: '#111111'
    };

    // Global Defaults - Clean & Editorial
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#555';
    Chart.defaults.borderColor = '#eee';
    
    // 1. Presidential Approval Trend (Line Chart)
    const ctxTrend = document.getElementById('chart-trend');
    if (ctxTrend) {
        new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: ['Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb'],
                datasets: [
                    {
                        label: 'Approval',
                        data: [42, 43, 41, 45, 46, 48, 49],
                        borderColor: colors.navy,
                        // No Gradient - Solid Alpha Fill
                        backgroundColor: 'rgba(11, 28, 51, 0.1)', 
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: colors.navy,
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Disapproval',
                        data: [50, 48, 51, 49, 47, 45, 44],
                        borderColor: colors.red,
                        borderWidth: 3,
                        borderDash: [5, 5], // Dashed line for contrast
                        pointBackgroundColor: '#fff',
                        pointBorderColor: colors.red,
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        tension: 0.3,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            padding: 20,
                            font: { size: 12, weight: 600 }
                        }
                    },
                    tooltip: {
                        backgroundColor: colors.navy,
                        titleFont: { family: "'Merriweather', serif" },
                        padding: 12,
                        cornerRadius: 2
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 30,
                        max: 60,
                        grid: { borderDash: [2, 2] },
                        title: { display: true, text: 'Percentage (%)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // 2. Demographics (Doughnut)
    const ctxDemo = document.getElementById('chart-demographics');
    if (ctxDemo) {
        new Chart(ctxDemo, {
            type: 'doughnut',
            data: {
                labels: ['18-29', '30-49', '50-64', '65+'],
                datasets: [{
                    data: [18, 32, 28, 22],
                    backgroundColor: [
                        '#0B1C33', // Navy
                        '#2C4B70', // Light Navy
                        '#E60000', // Red
                        '#FF4D4D'  // Light Red
                    ],
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { usePointStyle: true, padding: 15 }
                    }
                }
            }
        });
    }

    // 4. Demographic Momentum (Velocity) Chart
    const ctxVelocity = document.getElementById('chart-velocity');
    if (ctxVelocity) {
        new Chart(ctxVelocity, {
            type: 'bar',
            data: {
                labels: ['Suburban Women', 'Independents', 'Gen Z (18-24)', 'Latino Men', 'Rural Working Class', 'Seniors (65+)'],
                datasets: [{
                    label: 'Shift in Support (Last Quarter)',
                    data: [5.2, 3.8, 8.5, -2.1, -1.5, 1.2],
                    backgroundColor: (ctx) => {
                        const val = ctx.raw;
                        // Blue for Gain (Up/Right), Red for Loss (Down/Left)
                        return val >= 0 ? colors.navy : colors.red;
                    },
                    borderRadius: 4,
                    barPercentage: 0.6
                }]
            },
            options: {
                indexAxis: 'y', // Horizontal Bar
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `${ctx.raw > 0 ? '+' : ''}${ctx.raw}% Shift`
                        },
                        backgroundColor: colors.navy,
                        titleFont: { family: "'Merriweather', serif" }
                    }
                },
                scales: {
                    x: {
                        grid: { color: colors.grey, borderDash: [2, 2] },
                        ticks: {
                            callback: (val) => `${val}%`,
                            font: { weight: 'bold' }
                        },
                        title: { display: true, text: 'Margin Shift (%)' }
                    },
                    y: {
                        grid: { display: false },
                        ticks: {
                            font: { family: "'Merriweather', serif", size: 13, weight: 'bold' },
                            color: colors.navy
                        }
                    }
                }
            }
        });
    }
}

// Sophisticated Animated Polling Bars (DOM-based)
function initPollingBars() {
    const container = document.getElementById('issue-bars');
    if (!container) return;

    // Data
    const issues = [
        { label: 'Economy & Jobs', value: 42, type: 'navy' },
        { label: 'Healthcare', value: 28, type: 'red' },
        { label: 'National Security', value: 18, type: 'navy' },
        { label: 'Education', value: 12, type: 'red' }
    ];

    container.innerHTML = ''; // Clear

    issues.forEach(issue => {
        // Create Elements
        const wrapper = document.createElement('div');
        wrapper.className = 'poll-bar-container';

        const labelRow = document.createElement('div');
        labelRow.className = 'poll-label';
        
        const name = document.createElement('span');
        name.textContent = issue.label;
        
        const percent = document.createElement('span');
        percent.textContent = '0%'; // Start at 0 for animation
        
        labelRow.appendChild(name);
        labelRow.appendChild(percent);

        const track = document.createElement('div');
        track.className = 'poll-track';

        const fill = document.createElement('div');
        fill.className = `poll-fill ${issue.type === 'red' ? 'red' : ''}`;
        fill.style.width = '0%';

        track.appendChild(fill);
        wrapper.appendChild(labelRow);
        wrapper.appendChild(track);
        container.appendChild(wrapper);

        // Animate
        setTimeout(() => {
            fill.style.width = `${issue.value}%`;
            
            // Count up animation
            let start = 0;
            const duration = 1500;
            const startTime = performance.now();

            function update(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const ease = 1 - Math.pow(1 - progress, 3); // Cubic ease out
                
                const currentVal = Math.floor(start + (issue.value - start) * ease);
                percent.textContent = `${currentVal}%`;

                if (progress < 1) {
                    requestAnimationFrame(update);
                }
            }
            requestAnimationFrame(update);

        }, 300); // Slight delay
    });
}

// Global refresh function for the button
window.updateCharts = function() {
    // Reload charts with random small variations
    const charts = Chart.instances;
    Object.values(charts).forEach(chart => {
        chart.data.datasets.forEach(dataset => {
            dataset.data = dataset.data.map(v => {
                const noise = Math.floor(Math.random() * 5) - 2;
                return Math.max(0, v + noise);
            });
        });
        chart.update();
    });

    // Re-run bar animation
    initPollingBars();
};

// charts.js

function initCharts(trackPercentage, detailedCompetencies, coreCompetencies) {

    const ctx2 = document.getElementById('doughnutChart');
    if (ctx2) {
        ctx2.getContext('2d');
        Chart.defaults.font.size = 14;

        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: [
                    'Computer Engineering',
                    'Computer Science',
                    'Information Technology',
                    'Multimedia Arts'
                ],
                datasets: [{
                    data: [
                        trackPercentage['Computer Engineering'].percentage,
                        trackPercentage['Computer Science'].percentage,
                        trackPercentage['Information Technology'].percentage,
                        trackPercentage['Multimedia Arts'].percentage
                    ],
                    backgroundColor: [
                        '#8b5cf6',
                        '#ffcd56',
                        '#36a2eb',
                        '#7dff7d'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    datalabels: { display: false }
                }
            }
        });
    }

    // ===== Your existing logic =====
    const filteredComp = Object.entries(detailedCompetencies)
        .filter(([key, item]) => item.score > 0);

    const filteredEntries = Object.entries(coreCompetencies)
        .filter(([key, item]) => item.score > 0);

    const labels = filteredEntries.map(([key]) => key);
    const scores = filteredEntries.map(([_, item]) => item.score * 100);
    const levels = filteredEntries.map(([_, item]) => item.level);

    const ctx_bar = document.getElementById('bar-chart').getContext('2d');

    const data = {
        labels: labels,
        datasets: [{
            axis: 'y',
            label: labels,
            data: scores,
            barThickness: 16,    // Height of the bar in pixels
            maxBarThickness: 40, // Ensures it never gets too chunky'
            // categoryPercentage: 0.8,   // more vertical spacing
            // barPercentage: 0.9,        // keep bars solid
            fill: false,
            backgroundColor: [
                'rgb(102, 130, 255)'
            ],
            borderWidth: 0,
            borderRadius: 16,
            borderSkipped: false,
        }]
    };

    const config = {
        type: 'bar',
        data: data,
        plugins: [ChartDataLabels],
        options: {
            indexAxis: 'y',
            maintainAspectRatio: false,
            // layout: {
            //     padding: { left: 50, right: 50 }
            // },
            plugins: {
                legend: {
                    display: false
                },
                datalabels: { display: false },
                // Tooltip
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            // Main Bar Label
                            return `Total Score: ${context.parsed.x.toFixed(2)}%`;
                        },
                        afterLabel: function(context) {
                            const label = context.label;
                            let lines = [];

                            // 1. Define which sub-keys belong to which bar
                            const mapping = {
                                'Logical-Mathematical Reasoning': ['logical_reasoning', 'algorithmic_thinking', 'problem_solving'],
                                'Syntax & Structure Analysis': ['syntax_analysis'],
                                'Systems Hardware & Networking': ['hardware_systems', 'networking_systems', 'system_organization'],
                                'Digital Aesthetics & UI Design': ['ui_design', 'digital_creativity']
                            };

                            // 2. Get the sub-keys for the current hovered bar
                            const subKeys = mapping[label] || [];

                            // 3. Loop through sub-keys and pull data from your 'detailedCompetencies' object
                            subKeys.forEach(key => {
                                if (detailedCompetencies[key]) {
                                    const score = (detailedCompetencies[key].score * 100).toFixed(2);
                                    const name = key.replace(/_/g, ' ').replace(/^\w/, c => c.toUpperCase());
                                    lines.push(`${name}: ${score}%`);
                                }
                            });

                            return lines; // This returns each sub-item on a new line
                        }
                    }
                }
            },
            scales: {
                // 2. Hide the Labels on the left (Y-axis)
                    y: {
                    ticks: {
                        display: false
                    },
                    grid: {
                        display: false,   // Hides horizontal grid lines
                        drawBorder: false // Hides the Y-axis line
                    },
                    border: {
                        display: false
                    }
                },
                x: {
                    max: 100,
                    display: false,
                    grid: { 
                        display: false,
                        drawBorder: false
                    },
                }
            }
        }
    };
    new Chart(ctx_bar, config);

    // Custom labels
    const labelsContainer = document.getElementById('custom-labels');

    labelsContainer.innerHTML = labels.map((label, index) => {
        return `
            <div style="flex:1;display:grid;grid-template-rows:auto 1fr 1fr;">
                <div style="display:flex;justify-content:space-between;align-items:end;padding-left:8px;">
                    <span style="">${label} — ${levels[index]}</span>
                    <span style="">${scores[index].toFixed(2)}%</span>
                </div>
                <div></div>
                <div></div>
            </div>
        `;
    }).join('');
}
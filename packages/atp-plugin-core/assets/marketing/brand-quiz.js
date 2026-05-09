/* ATP Voter Alignment Quiz & Issue Intensity Grid */

document.addEventListener('DOMContentLoaded', () => {
    initQuiz();
    initSleekQuiz();
    initIntensityGrid();
});

// --- Sleek Quiz Logic (Slider Variant) ---
function initSleekQuiz() {
    const container = document.getElementById('sleek-quiz');
    if (!container) return;

    const questions = [
        { label: "Fiscal Approach", left: "Austerity", right: "Stimulus" },
        { label: "Social Reform", left: "Traditional", right: "Progressive" },
        { label: "Global Stance", left: "Isolationist", right: "Globalist" }
    ];

    // 2-Column Layout: Result Card (Left) + Sliders (Right)
    let html = `
    <div class="sleek-layout">
        <!-- Left: Result Card -->
        <div class="sleek-result-col">
            <div id="sleek-result-card" class="sleek-card">
                <div class="sleek-card-header">
                    <span class="sleek-subtitle">Voter Archetype</span>
                    <h2 id="sleek-archetype">Pragmatic Centrist</h2>
                </div>
                <div class="sleek-card-body">
                    <p id="sleek-desc">You seek practical solutions regardless of ideology, balancing fiscal responsibility with social progress.</p>
                    
                    <!-- Visual Bars (Static for demo, could be dynamic) -->
                    <div class="sleek-bars-display">
                        <div class="sleek-bar-row">
                            <span>Growth</span>
                            <div class="sleek-bar-track"><div class="sleek-bar-fill" style="width: 50%" id="bar-growth"></div></div>
                            <span id="pct-growth">50%</span>
                        </div>
                        <div class="sleek-bar-row">
                            <span>Stability</span>
                            <div class="sleek-bar-track"><div class="sleek-bar-fill" style="width: 50%" id="bar-stability"></div></div>
                            <span id="pct-stability">50%</span>
                        </div>
                    </div>

                    <button class="btn-primary" style="width: 100%; margin-top: 1.5rem;" onclick="initSleekQuiz()">RETAKE ASSESSMENT</button>
                </div>
            </div>
        </div>

        <!-- Right: Sliders -->
        <div class="sleek-questions-col">
            <div class="sleek-questions">`;
    
    questions.forEach((q, index) => {
        html += `
            <div class="sleek-question-row">
                <div class="sleek-labels-top">
                    <span class="l-label">${q.left}</span>
                    <span class="c-label">${q.label}</span>
                    <span class="r-label">${q.right}</span>
                </div>
                <div class="sleek-slider-wrapper">
                    <input type="range" min="0" max="100" value="50" class="sleek-slider" data-index="${index}">
                </div>
            </div>
        `;
    });

    html += `   </div>
        </div>
    </div>`;

    container.innerHTML = html;

    // Logic
    const sliders = container.querySelectorAll('.sleek-slider');
    const archetypeEl = document.getElementById('sleek-archetype');
    const descEl = document.getElementById('sleek-desc');
    const barGrowth = document.getElementById('bar-growth');
    const barStability = document.getElementById('bar-stability');
    const pctGrowth = document.getElementById('pct-growth');
    const pctStability = document.getElementById('pct-stability');

    sliders.forEach(slider => {
        slider.addEventListener('input', updateResult);
    });

    function updateResult() {
        let total = 0;
        sliders.forEach(s => total += parseInt(s.value));
        const avg = total / sliders.length;

        let type = "Pragmatic Centrist";
        let desc = "You seek practical solutions regardless of ideology.";
        let growthVal = 50; // Dynamic fake value

        if (avg < 35) {
            type = "Traditional Conservative";
            desc = "You favor established structures, fiscal restraint, and limited intervention.";
            growthVal = 30 + (Math.random() * 10); 
        } else if (avg > 65) {
            type = "Progressive Reformer";
            desc = "You support active change, government investment, and social equity.";
            growthVal = 70 + (Math.random() * 10);
        } else {
            type = "Independent Thinker";
            desc = "You evaluate each issue on its merits, refusing to fit into a single box.";
            growthVal = avg;
        }

        archetypeEl.innerText = type;
        descEl.innerText = desc;
        
        // Update Bars visually
        barGrowth.style.width = `${growthVal}%`;
        pctGrowth.innerText = `${Math.round(growthVal)}%`;
        
        const stabVal = 100 - growthVal;
        barStability.style.width = `${stabVal}%`;
        pctStability.innerText = `${Math.round(stabVal)}%`;
    }
}

// --- Voter Alignment Quiz Logic ---
function initQuiz() {
    const quizContainer = document.getElementById('voter-quiz');
    if (!quizContainer) return;

    // Quiz Data
    const questions = [
        {
            id: 1,
            question: "When it comes to economic growth, which approach do you prioritize?",
            options: [
                { text: "Tax incentives for small businesses", match: "Growth", percent: 58 },
                { text: "Direct infrastructure investment", match: "Stability", percent: 42 }
            ]
        },
        {
            id: 2,
            question: "How should the district address housing affordability?",
            options: [
                { text: "Relax zoning for more density", match: "Growth", percent: 65 },
                { text: "Rent control & preservation", match: "Stability", percent: 35 }
            ]
        },
        {
            id: 3,
            question: "What is the most effective way to improve public safety?",
            options: [
                { text: "Community policing & mental health", match: "Stability", percent: 71 },
                { text: "Increased patrols & enforcement", match: "Growth", percent: 29 }
            ]
        }
    ];

    let currentStep = 0;
    let userSelections = [];

    // Render Initial State
    renderStep(currentStep);

    function renderStep(index) {
        if (index >= questions.length) {
            renderResults();
            return;
        }

        const q = questions[index];
        
        quizContainer.innerHTML = `
            <div class="quiz-step fade-in">
                <div class="quiz-progress">
                    <span style="width: ${((index + 1) / questions.length) * 100}%"></span>
                </div>
                <h3 class="quiz-question">${q.question}</h3>
                <div class="quiz-options">
                    ${q.options.map((opt, i) => `
                        <button class="quiz-btn" onclick="handleAnswer(${index}, ${i})">
                            ${opt.text}
                        </button>
                    `).join('')}
                </div>
                <div class="quiz-meta">Question ${index + 1} of ${questions.length}</div>
            </div>
        `;
    }

    // Global handler for inline onClick
    window.handleAnswer = function(qIndex, optIndex) {
        const question = questions[qIndex];
        const selected = question.options[optIndex];
        
        userSelections.push(selected);
        
        // Show immediate feedback
        const btns = document.querySelectorAll('.quiz-btn');
        btns.forEach((btn, i) => {
            const opt = question.options[i];
            // Disable all
            btn.disabled = true;
            btn.style.opacity = '0.7';
            
            // Show stats
            const statSpan = document.createElement('span');
            statSpan.className = 'quiz-stat-pop';
            statSpan.innerText = `${opt.percent}% agree`;
            btn.appendChild(statSpan);
            
            if (i === optIndex) {
                btn.classList.add('selected');
                btn.style.opacity = '1';
            }
        });

        // Advance after delay
        setTimeout(() => {
            currentStep++;
            renderStep(currentStep);
        }, 1500);
    };

    function renderResults() {
        // Calculate alignment
        // Simple logic: Alignment Score based on majority consensus
        const consensusCount = userSelections.filter(s => s.percent > 50).length;
        let archetype = "Independent Thinker";
        if (consensusCount === 3) archetype = "Consensus Builder";
        if (consensusCount === 0) archetype = "Contrarian Voice";

        quizContainer.innerHTML = `
            <div class="quiz-results fade-in">
                <div class="result-header">
                    <h4>Voter Archetype</h4>
                    <h2>${archetype}</h2>
                </div>
                <div class="result-breakdown">
                    <p>You aligned with the majority of ATP poll respondents on <strong>${consensusCount} of ${questions.length}</strong> key issues.</p>
                    <div class="result-visual">
                        ${userSelections.map(s => `
                            <div class="result-row">
                                <span>${s.match}</span>
                                <div class="result-bar-bg"><div class="result-bar-fill" style="width: ${s.percent}%"></div></div>
                                <span>${s.percent}% Match</span>
                            </div>
                        `).join('')}
                    </div>
                </div>
                <button class="btn-primary" onclick="initQuiz()">Retake Assessment</button>
            </div>
        `;
    }
}

// --- Issue Intensity Grid Logic ---
function initIntensityGrid() {
    const container = document.getElementById('intensity-grid');
    if (!container) return;

    const issues = [
        { name: "Inflation Impact", intensity: 92, trend: "up", category: "Economic", source: "Q1 Consumer Price Sentiment (N=2,400)" },
        { name: "Border Security", intensity: 88, trend: "up", category: "National", source: "Daily Tracking Avg. (Jan-Feb)" },
        { name: "Healthcare Access", intensity: 75, trend: "stable", category: "Social", source: "Annual Patient Experience Study" },
        { name: "Tech Regulation", intensity: 45, trend: "down", category: "Economic", source: "Innovation Sector Pulse" },
        { name: "Climate Resilience", intensity: 68, trend: "up", category: "Environmental", source: "FEMA/NOAA Risk Assessment Data" },
        { name: "Education Funding", intensity: 82, trend: "stable", category: "Social", source: "Parent & Teacher Union Poll" }
    ];

    container.innerHTML = issues.map(issue => {
        // Color Logic: Rising = Blue, Cooling = Red, Stable = Grey
        let colorVar = 'var(--atp-grey)';
        if (issue.trend === 'up') colorVar = 'var(--atp-navy)';
        if (issue.trend === 'down') colorVar = 'var(--atp-red)';

        return `
        <div class="intensity-card">
            <div class="intensity-header">
                <span class="intensity-cat">${issue.category}</span>
                <span class="intensity-trend ${issue.trend}">${getTrendIcon(issue.trend)}</span>
            </div>
            <h4>${issue.name}</h4>
            <div class="intensity-source">Source: ${issue.source}</div>
            <div class="intensity-meter">
                <div class="intensity-label" style="display:flex; justify-content:space-between; font-size:0.85rem; margin-bottom:0.5rem; color:#555;">
                    <span>Intensity Score</span>
                    <strong>${issue.intensity}/100</strong>
                </div>
                <div class="meter-track">
                    <div class="meter-fill" style="width: 0%; background: ${colorVar};" data-width="${issue.intensity}%"></div>
                </div>
            </div>
        </div>
    `}).join('');

    // Animate bars on load
    setTimeout(() => {
        const fills = container.querySelectorAll('.meter-fill');
        fills.forEach(fill => {
            fill.style.width = fill.getAttribute('data-width');
        });
    }, 500);
}

function getTrendIcon(trend) {
    if (trend === 'up') return '▲ Rising';
    if (trend === 'down') return '▼ Cooling';
    return '• Stable';
}

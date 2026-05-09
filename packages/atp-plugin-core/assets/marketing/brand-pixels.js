/* ATP "Dot Matrix" Pixel Animation Script */
/* Creates grid-based population visualizations with "fine print" rigor */

document.addEventListener('DOMContentLoaded', () => {
    // Register GSAP Plugin if available
    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
        gsap.registerPlugin(ScrollTrigger);
    }
    // Small delay to ensure layout is settled
    setTimeout(initPixelVisualizations, 100);
});

function initPixelVisualizations() {
    const containers = document.querySelectorAll('.pixel-canvas');
    if (!containers || containers.length === 0) {
        console.warn('ATP: No .pixel-canvas containers found.');
        return;
    }

    containers.forEach((container, idx) => {
        const percentRaw = container.getAttribute('data-percent');
        const percent = percentRaw ? parseInt(percentRaw) : 0;
        const totalDots = 100; // Exact 100 dots (20x5 grid)
        const activeDots = Math.floor((percent / 100) * totalDots);
        
        console.log(`ATP: Initializing Pixel Grid ${idx+1} with ${percent}% (${activeDots}/${totalDots})`);

        // Clear container
        container.innerHTML = '';

        // Create dots
        const fragment = document.createDocumentFragment();
        const dots = [];
        for (let i = 0; i < totalDots; i++) {
            const dot = document.createElement('div');
            dot.className = 'pixel-dot';
            
            // Interaction: Pulse on hover
            dot.addEventListener('mouseenter', () => {
                if (typeof gsap !== 'undefined') gsap.to(dot, { scale: 1.5, duration: 0.2 });
            });
            dot.addEventListener('mouseleave', () => {
                if (typeof gsap !== 'undefined') gsap.to(dot, { scale: 1, duration: 0.2 });
            });

            fragment.appendChild(dot);
            dots.push(dot);
        }
        container.appendChild(fragment);

        // Animation Logic
        const runAnimation = () => {
            // Reset
            dots.forEach(d => d.classList.remove('active'));
            
            // Randomize activation for "organic" feel
            const indices = Array.from({length: totalDots}, (_, i) => i);
            shuffleArray(indices);
            const selectedIndices = indices.slice(0, activeDots);
            
            // Animate
            selectedIndices.forEach((index, i) => {
                const dot = dots[index];
                // Use GSAP if available for smoother timing, else setTimeout
                if (typeof gsap !== 'undefined') {
                    gsap.delayedCall(i * 0.015, () => {
                        dot.classList.add('active');
                        gsap.fromTo(dot, 
                            { scale: 1.8, opacity: 0.5 }, 
                            { scale: 1, opacity: 1, duration: 0.4, ease: "back.out(1.7)" }
                        );
                    });
                } else {
                    setTimeout(() => {
                        dot.classList.add('active');
                    }, i * 15);
                }
            });
        };

        // Trigger on Scroll
        if (typeof ScrollTrigger !== 'undefined') {
            ScrollTrigger.create({
                trigger: container,
                start: 'top 90%', // Trigger slightly earlier
                onEnter: () => {
                    console.log(`ATP: Triggering animation for Grid ${idx+1}`);
                    runAnimation();
                }
            });
        } else {
            // Fallback if no ScrollTrigger
            console.log('ATP: GSAP ScrollTrigger not found, running immediately.');
            runAnimation();
        }
    });
}

// Utility: Shuffle Array (Fisher-Yates)
function shuffleArray(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
}

// Expose refresh function
window.refreshPixels = function() {
    initPixelVisualizations();
};

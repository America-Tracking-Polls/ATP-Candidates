// ATP Interactive Issues Bubble Chart (D3.js)

document.addEventListener('DOMContentLoaded', () => {
    initIssuesChart();
});

function initIssuesChart() {
    const container = document.getElementById('issues-bubble-chart');
    if (!container) return;

    const width = container.clientWidth;
    const height = 500;
    
    // Data: Issues driving the conversation
    const data = [
        { id: "Economy", value: 42, type: "primary", change: "+2.4%" },
        { id: "Healthcare", value: 28, type: "alert", change: "-1.1%" },
        { id: "Immigration", value: 25, type: "primary", change: "+0.5%" },
        { id: "Education", value: 20, type: "neutral", change: "+1.2%" },
        { id: "Climate", value: 18, type: "neutral", change: "+0.8%" },
        { id: "Crime", value: 15, type: "alert", change: "+3.1%" },
        { id: "Housing", value: 12, type: "neutral", change: "-0.5%" },
        { id: "Tech", value: 10, type: "neutral", change: "+0.2%" },
        { id: "Trade", value: 8, type: "primary", change: "-0.1%" }
    ];

    // Color Scale
    const colorScale = {
        primary: "#0B1C33", // Navy
        alert: "#E60000",   // Red
        neutral: "#999999"  // Grey
    };

    // Create SVG
    const svg = d3.select("#issues-bubble-chart")
        .append("svg")
        .attr("width", width)
        .attr("height", height)
        .attr("viewBox", [0, 0, width, height])
        .attr("style", "max-width: 100%; height: auto;");

    // Pack Layout
    // We use a pack layout to calculate initial sizes, but we'll use a force simulation for movement
    const pack = d3.pack()
        .size([width, height])
        .padding(10);

    const root = d3.hierarchy({ children: data })
        .sum(d => d.value);

    const nodes = pack(root).leaves();

    // Simulation
    const simulation = d3.forceSimulation(nodes)
        .force("x", d3.forceX(width / 2).strength(0.05))
        .force("y", d3.forceY(height / 2).strength(0.05))
        .force("collide", d3.forceCollide().radius(d => d.r + 2).iterations(2))
        .force("charge", d3.forceManyBody().strength(-20)); // Gentle repulsion

    // Render Nodes
    const node = svg.selectAll("g")
        .data(nodes)
        .join("g")
        .attr("class", "node")
        .attr("cursor", "pointer")
        .call(d3.drag()
            .on("start", dragstarted)
            .on("drag", dragged)
            .on("end", dragended));

    // Bubbles
    const circles = node.append("circle")
        .attr("r", d => d.r)
        .attr("fill", d => colorScale[d.data.type])
        .attr("opacity", 0.9)
        .attr("stroke", "#fff")
        .attr("stroke-width", 2)
        .on("mouseover", function(event, d) {
            d3.select(this).transition().duration(200).attr("transform", "scale(1.05)");
            updateHighlight(d.data);
        })
        .on("mouseout", function() {
            d3.select(this).transition().duration(200).attr("transform", "scale(1)");
        });

    // Labels
    node.append("text")
        .text(d => d.data.id)
        .attr("text-anchor", "middle")
        .attr("dy", "-0.2em")
        .style("font-family", "'Inter', sans-serif")
        .style("font-weight", "600")
        .style("font-size", d => Math.min(d.r / 3, 14) + "px")
        .style("fill", "#fff")
        .style("pointer-events", "none");

    node.append("text")
        .text(d => d.data.value + "%")
        .attr("text-anchor", "middle")
        .attr("dy", "1em")
        .style("font-family", "'Inter', sans-serif")
        .style("font-size", d => Math.min(d.r / 3, 12) + "px")
        .style("fill", "rgba(255,255,255,0.8)")
        .style("pointer-events", "none");

    // Animation Tick
    simulation.on("tick", () => {
        // Constrain to bounds
        node.attr("transform", d => {
            d.x = Math.max(d.r, Math.min(width - d.r, d.x));
            d.y = Math.max(d.r, Math.min(height - d.r, d.y));
            return `translate(${d.x},${d.y})`;
        });
        
        // Add gentle "floating" movement
        simulation.alphaTarget(0.05); 
    });

    // Interaction Functions
    function dragstarted(event, d) {
        if (!event.active) simulation.alphaTarget(0.3).restart();
        d.fx = d.x;
        d.fy = d.y;
    }

    function dragged(event, d) {
        d.fx = event.x;
        d.fy = event.y;
    }

    function dragended(event, d) {
        if (!event.active) simulation.alphaTarget(0.05); // Keep floating
        d.fx = null;
        d.fy = null;
    }

    function updateHighlight(data) {
        const highlight = document.getElementById('issue-highlight');
        const stat = document.getElementById('issue-stat');
        
        if (highlight && stat) {
            highlight.textContent = data.id;
            highlight.style.color = colorScale[data.type];
            stat.textContent = `${data.value}% Importance (${data.change})`;
        }
    }
}

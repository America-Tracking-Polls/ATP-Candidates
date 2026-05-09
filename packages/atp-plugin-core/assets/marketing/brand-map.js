// ATP Interactive Electoral Map (D3.js State Grid)
// Visualizes electoral college projection using a Tile Grid Map for data clarity

document.addEventListener('DOMContentLoaded', () => {
    initMap();
});

function initMap() {
    const container = document.getElementById('us-map-container');
    if (!container) return;

    // Dimensions
    const width = container.clientWidth;
    const height = 300;
    const cellSize = Math.floor(width / 13); // 12 columns + padding
    const padding = 2;

    // US State Grid Layout (Row, Column)
    // 0-indexed, approx standard grid map layout
    const states = [
        { id: "ME", r: 0, c: 11, v: "D" },
        { id: "WI", r: 1, c: 6, v: "D" }, { id: "VT", r: 1, c: 10, v: "D" }, { id: "NH", r: 1, c: 11, v: "D" },
        { id: "WA", r: 2, c: 1, v: "D" }, { id: "ID", r: 2, c: 2, v: "R" }, { id: "MT", r: 2, c: 3, v: "R" }, { id: "ND", r: 2, c: 4, v: "R" }, { id: "MN", r: 2, c: 5, v: "D" }, { id: "IL", r: 2, c: 6, v: "D" }, { id: "MI", r: 2, c: 7, v: "D" }, { id: "NY", r: 2, c: 9, v: "D" }, { id: "MA", r: 2, c: 10, v: "D" },
        { id: "OR", r: 3, c: 1, v: "D" }, { id: "NV", r: 3, c: 2, v: "D" }, { id: "WY", r: 3, c: 3, v: "R" }, { id: "SD", r: 3, c: 4, v: "R" }, { id: "IA", r: 3, c: 5, v: "R" }, { id: "IN", r: 3, c: 6, v: "R" }, { id: "OH", r: 3, c: 7, v: "R" }, { id: "PA", r: 3, c: 8, v: "D" }, { id: "NJ", r: 3, c: 9, v: "D" }, { id: "CT", r: 3, c: 10, v: "D" }, { id: "RI", r: 3, c: 11, v: "D" },
        { id: "CA", r: 4, c: 1, v: "D" }, { id: "UT", r: 4, c: 2, v: "R" }, { id: "CO", r: 4, c: 3, v: "D" }, { id: "NE", r: 4, c: 4, v: "R" }, { id: "MO", r: 4, c: 5, v: "R" }, { id: "KY", r: 4, c: 6, v: "R" }, { id: "WV", r: 4, c: 7, v: "R" }, { id: "VA", r: 4, c: 8, v: "D" }, { id: "MD", r: 4, c: 9, v: "D" }, { id: "DE", r: 4, c: 10, v: "D" },
        { id: "AZ", r: 5, c: 2, v: "D" }, { id: "NM", r: 5, c: 3, v: "D" }, { id: "KS", r: 5, c: 4, v: "R" }, { id: "AR", r: 5, c: 5, v: "R" }, { id: "TN", r: 5, c: 6, v: "R" }, { id: "NC", r: 5, c: 7, v: "R" }, { id: "SC", r: 5, c: 8, v: "R" }, { id: "DC", r: 5, c: 9, v: "D" },
        { id: "OK", r: 6, c: 4, v: "R" }, { id: "LA", r: 6, c: 5, v: "R" }, { id: "MS", r: 6, c: 6, v: "R" }, { id: "AL", r: 6, c: 7, v: "R" }, { id: "GA", r: 6, c: 8, v: "D" },
        { id: "HI", r: 7, c: 0, v: "D" }, { id: "AK", r: 7, c: 1, v: "R" }, { id: "TX", r: 7, c: 4, v: "R" }, { id: "FL", r: 7, c: 9, v: "R" }
    ];

    // Colors
    const colors = {
        D: "#0B1C33", // Navy
        R: "#E60000", // Red
        Toss: "#cccccc"
    };

    const svg = d3.select("#us-map-container")
        .append("svg")
        .attr("width", width)
        .attr("height", height);

    // Group for the grid
    const g = svg.append("g")
        .attr("transform", `translate(0, 20)`); // Center visually if needed

    // Draw State Cells
    const cells = g.selectAll("g")
        .data(states)
        .enter()
        .append("g")
        .attr("transform", d => `translate(${d.c * cellSize}, ${d.r * cellSize})`);

    // Rectangles
    cells.append("rect")
        .attr("width", cellSize - padding)
        .attr("height", cellSize - padding)
        .attr("fill", "#eee") // Initial state
        .attr("rx", 2)
        .transition()
        .delay((d, i) => i * 15) // Stagger animation
        .duration(500)
        .attr("fill", d => colors[d.v]);

    // Labels
    cells.append("text")
        .text(d => d.id)
        .attr("x", (cellSize - padding) / 2)
        .attr("y", (cellSize - padding) / 2)
        .attr("dy", "0.35em")
        .attr("text-anchor", "middle")
        .attr("fill", "white")
        .style("font-family", "'Inter', sans-serif")
        .style("font-size", "10px")
        .style("font-weight", "600")
        .attr("opacity", 0)
        .transition()
        .delay((d, i) => i * 15 + 200)
        .duration(300)
        .attr("opacity", 1);

    // Hover Effect
    cells.on("mouseenter", function(event, d) {
        d3.select(this).select("rect")
            .transition().duration(100)
            .attr("transform", "scale(1.1)")
            .attr("stroke", "#333")
            .attr("stroke-width", 2);
    }).on("mouseleave", function(event, d) {
        d3.select(this).select("rect")
            .transition().duration(200)
            .attr("transform", "scale(1)")
            .attr("stroke", "none");
    });
}

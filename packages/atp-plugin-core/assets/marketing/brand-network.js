// ATP Brand Network Background
// Uses Three.js to create a connected node network representing data integration

document.addEventListener('DOMContentLoaded', () => {
    initNetworkBackground();
});

function initNetworkBackground() {
    const container = document.getElementById('canvas-container');
    if (!container) return;

    // Scene Setup
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
    
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(window.devicePixelRatio);
    container.appendChild(renderer.domElement);

    // Particles
    const particlesGeometry = new THREE.BufferGeometry();
    const particleCount = 150;
    
    const posArray = new Float32Array(particleCount * 3);
    const particlesMaterial = new THREE.PointsMaterial({
        size: 0.02,
        color: 0x4CC9F0, // Data Blue
        transparent: true,
        opacity: 0.8,
    });

    for(let i = 0; i < particleCount * 3; i++) {
        posArray[i] = (Math.random() - 0.5) * 15; // Spread
    }

    particlesGeometry.setAttribute('position', new THREE.BufferAttribute(posArray, 3));
    const particlesMesh = new THREE.Points(particlesGeometry, particlesMaterial);
    scene.add(particlesMesh);

    // Connecting Lines
    // We will update line geometry in the animation loop based on distance
    const linesMaterial = new THREE.LineBasicMaterial({
        color: 0x0B1C33,
        transparent: true,
        opacity: 0.15
    });

    const linesGeometry = new THREE.BufferGeometry();
    const linesMesh = new THREE.LineSegments(linesGeometry, linesMaterial);
    scene.add(linesMesh);

    // Camera Position
    camera.position.z = 5;

    // Interaction
    let mouseX = 0;
    let mouseY = 0;

    document.addEventListener('mousemove', (event) => {
        mouseX = event.clientX / window.innerWidth - 0.5;
        mouseY = event.clientY / window.innerHeight - 0.5;
    });

    // Animation Loop
    function animate() {
        requestAnimationFrame(animate);

        // Gentle rotation
        particlesMesh.rotation.y += 0.001;
        particlesMesh.rotation.x += 0.0005;

        // Mouse interaction parallax
        particlesMesh.rotation.y += 0.05 * (mouseX - particlesMesh.rotation.y) * 0.05;
        particlesMesh.rotation.x += 0.05 * (mouseY - particlesMesh.rotation.x) * 0.05;

        linesMesh.rotation.y = particlesMesh.rotation.y;
        linesMesh.rotation.x = particlesMesh.rotation.x;

        // Update Lines
        // This is computationally expensive, so in a real app might be optimized
        // For this demo, we'll connect close particles
        updateLines();

        renderer.render(scene, camera);
    }

    function updateLines() {
        const positions = particlesMesh.geometry.attributes.position.array;
        const connectDistance = 2.5;
        const linePositions = [];
        const worldPos = [];

        // Transform local positions to world to check distance correctly (simplified: treating local as world for static mesh)
        // Actually, since we rotate the mesh, the relative distances *between points* inside the mesh stay constant.
        // So we only need to calculate connections once if they don't move relative to each other.
        // But let's make them move slightly for "futuristic" feel? 
        // For performance, let's keep points static relative to each other and just rotate the group.
        
        // If we want lines, we calculate them once or update if points move. 
        // Let's just calculate lines once for static mesh to save CPU, or dynamic if we want pulsing.
        // Let's do dynamic for the "alive" feel.
        
        for (let i = 0; i < particleCount; i++) {
            const x1 = positions[i * 3];
            const y1 = positions[i * 3 + 1];
            const z1 = positions[i * 3 + 2];

            // Slowly animate positions (noise)
            // positions[i * 3] += Math.sin(Date.now() * 0.001 + i) * 0.002;

            for (let j = i + 1; j < particleCount; j++) {
                const x2 = positions[j * 3];
                const y2 = positions[j * 3 + 1];
                const z2 = positions[j * 3 + 2];

                const dist = Math.sqrt(Math.pow(x1-x2, 2) + Math.pow(y1-y2, 2) + Math.pow(z1-z2, 2));

                if (dist < connectDistance) {
                    linePositions.push(x1, y1, z1);
                    linePositions.push(x2, y2, z2);
                }
            }
        }

        linesMesh.geometry.setAttribute('position', new THREE.Float32BufferAttribute(linePositions, 3));
    }

    animate();

    // Resize Handler
    window.addEventListener('resize', () => {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
    });
}

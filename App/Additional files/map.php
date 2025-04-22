<h3>Bus Route Map - Route <?php echo htmlspecialchars($selectedBus['routeNumber']); ?></h3>
                    <iframe
                        id="googleMap"
                        width="100%"
                        height="450"
                        style="border:0"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen>
                    </iframe>

                    <script>
document.addEventListener('DOMContentLoaded', function() {
    const busStops = <?php echo json_encode(array_map('trim', $busStops)); ?>;
    const routeNo = <?php echo json_encode($selectedBus['route_no']); ?>;
    
    try {
        // Create a more specific search query focusing on Sri Lanka
        const fromLocation = encodeURIComponent(busStops[0] + ', Sri Lanka');
        const toLocation = encodeURIComponent(busStops[busStops.length - 1] + ', Sri Lanka');
        
        // Use directions instead of search to show the route
        const simpleRouteUrl = `https://maps.google.com/maps?`
            + `saddr=${fromLocation}`
            + `&daddr=${toLocation}`
            + `&t=m` // Map type: m = normal map
            + `&z=9` // Higher zoom level (closer view)
            + `&output=embed`
            + `&ie=UTF8`
            + `&ll=7.8731,80.7718` // Coordinates for Sri Lanka's center
            + `&spn=3.0,3.0`; // Viewport span

        // Set the iframe src with error handling
        const mapFrame = document.getElementById('googleMap');
        mapFrame.onerror = function() {
            mapFrame.parentElement.innerHTML = '<p>Unable to load map. Please try again later.</p>';
        };
        mapFrame.src = simpleRouteUrl;
    } catch (error) {
        console.error('Error loading map:', error);
        document.getElementById('googleMap').parentElement.innerHTML = 
            '<p>Unable to load map. Please try again later.</p>';
    }
});
</script>
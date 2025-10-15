/**
 * Rundown Map Management with OpenStreetMap and Leaflet.js
 *
 * This script handles the Leaflet.js integration for the rundown feature,
 * including initializing the map, displaying place markers, and drawing routes.
 */

let map = null;
let markers = [];
let routeLines = [];

/**
 * Initializes the Leaflet map on the page.
 * @param {number} rundownId - The ID of the rundown to display.
 */
function initRundownMap(rundownId) {
    const mapElement = document.getElementById('map');
    if (!mapElement) {
        console.error('Map element not found.');
        return;
    }

    // Default center (e.g., Jakarta)
    const defaultCenter = [-6.2088, 106.8456];

    map = L.map(mapElement).setView(defaultCenter, 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    if (rundownId) {
        loadRundownMapData(rundownId);
    }
}

/**
 * Fetches map data from the server for a specific rundown.
 * @param {number} rundownId - The ID of the rundown.
 */
function loadRundownMapData(rundownId) {
    const mapDataUrl = `/rundowns/${rundownId}/map-data`;

    fetch(mapDataUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.places && data.places.length > 0) {
                displayPlacesOnMap(data.places);
                drawRoute(data.route);
                if (data.center) {
                    map.setView([data.center.lat, data.center.lng], 13);
                }
            } else {
                clearMap();
            }
        })
        .catch(error => {
            console.error('Error loading map data:', error);
            document.getElementById('map').innerHTML = '<div class="p-4 text-red-600">Gagal memuat data peta.</div>';
        });
}

/**
 * Displays place markers on the map.
 * @param {Array} places - An array of place objects.
 */
function displayPlacesOnMap(places) {
    clearMarkers();

    const bounds = [];

    places.forEach((place, index) => {
        const position = [parseFloat(place.latitude), parseFloat(place.longitude)];

        const icon = L.divIcon({
            html: `<div class="flex items-center justify-center w-8 h-8 bg-blue-600 text-white font-bold rounded-full shadow-lg">${index + 1}</div>`,
            className: '',
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        });

        const marker = L.marker(position, { icon: icon }).addTo(map);

        const popupContent = `
            <div class="p-1">
                <h3 class="font-semibold mb-1">${place.name}</h3>
                <p class="text-sm text-gray-600">${place.address}</p>
                <p class="text-xs text-gray-500 mt-1">${place.category}</p>
            </div>
        `;

        marker.bindPopup(popupContent);

        markers.push(marker);
        bounds.push(position);
    });

    if (places.length > 0) {
        map.fitBounds(bounds, { padding: [50, 50] });
    }
}

/**
 * Draws the route between places on the map.
 * @param {Array} route - An array of route segment objects.
 */
function drawRoute(route) {
    clearRouteLines();

    if (!route || route.length === 0) {
        return;
    }

    const latlngs = route.map(segment => [
        [parseFloat(segment.from.lat), parseFloat(segment.from.lng)],
        [parseFloat(segment.to.lat), parseFloat(segment.to.lng)]
    ]).flat();

    const polyline = L.polyline(latlngs, {
        color: '#4F46E5',
        weight: 4,
        opacity: 0.8
    }).addTo(map);

    routeLines.push(polyline);
}

/**
 * Clears all markers from the map.
 */
function clearMarkers() {
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];
}

/**
 * Clears all route lines from the map.
 */
function clearRouteLines() {
    routeLines.forEach(line => map.removeLayer(line));
    routeLines = [];
}

/**
 * Clears the entire map (markers and routes).
 */
function clearMap() {
    clearMarkers();
    clearRouteLines();
}

/**
 * Refreshes the map and reloads data.
 * @param {number} rundownId - The ID of the rundown.
 */
function refreshRundownMap(rundownId) {
    if (map) {
        map.invalidateSize();
        loadRundownMapData(rundownId);
    }
}

// Expose functions to the global scope
window.initRundownMap = initRundownMap;
window.refreshRundownMap = refreshRundownMap;
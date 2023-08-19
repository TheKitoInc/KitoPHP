function initialize (x, y, z) {
  if (GBrowserIsCompatible()) {
    const map = new GMap2(document.getElementById('map_canvas'))
    map.setCenter(new GLatLng(x, y), z)
    map.setUIToDefault()
  }
}

const staticDevCoffee = "dev-coffee-site-v1"
const assets = [
  "/",
  "/index.html",
  "/assets/front/css/style.css",
  "/assets/front/css/default.css",
  "/assets/front/css/detail.css",
  "/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css",
  "/assets/plugins/toastr/toastr.min.css",
  "/assets/plugins/lightbox/css/lightbox.css",
  "/assets/plugins/bootstrap/js/bootstrap.bundle.js",
  "/assets/plugins/lightbox/js/lightbox.min.js",
  "/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js",
  "/assets/plugins/toastr/toastr.min.js",
  "/assets/front/js/plugins.js",
  "/assets/front/js/main.js",
  "/public/js/app.js",
  "/public/js/manifest.js",
  "/storage/app/public/homecontent/banner_1651757363.png",
  "/storage/app/public/avatar/chef_1645434991.jpg",
  "/storage/app/public/avatar/chef_1642579916.jpg",
  "/storage/app/public/avatar/chef_1641955850.jpg"
]

self.addEventListener("install", installEvent => {
  installEvent.waitUntil(
    caches.open(staticDevCoffee).then(cache => {
      cache.addAll(assets)
    })
  )
})


self.addEventListener("fetch", fetchEvent => {
  fetchEvent.respondWith(
    caches.match(fetchEvent.request).then(res => {
      return res || fetch(fetchEvent.request)
    })
  )
})
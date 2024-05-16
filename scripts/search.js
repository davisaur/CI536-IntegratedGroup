window.addEventListener("load", function(){
    document.getElementById("searchbar").addEventListener("keypress", function(event) {
        if(event.key === "Enter") {
            var result = document.getElementById("searchbar").value;
            var url = new URL(window.location.href);
            url.searchParams.set("search", result);
            window.location.href = url.href;
        }
    });

    document.querySelectorAll(".category-filter").forEach(function(link) {
        link.addEventListener("click", function(event) {
            event.preventDefault();
            var categoryId = this.getAttribute("data-category");
            var url = new URL(window.location.href);
            url.searchParams.set("category", categoryId);
            window.location.href = url.href;
        });
    });

document.querySelectorAll(".price-filter").forEach(function(link) {
            link.addEventListener("click", function(event) {
                event.preventDefault();
                var price = this.getAttribute("data-price");
                var url = new URL(window.location.href);
                var params = new URLSearchParams(url.search);
                params.set("price", price);
                url.search = params.toString();
                window.location.href = url.href;
            });
        });
});
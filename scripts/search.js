window.addEventListener("load", function(){
    document.getElementById("searchbar").addEventListener("keypress", function(event) {
        if(event.key === "Enter") {
            var result = document.getElementById("searchbar").value;  
            window.location.href = 'search.php?search='+result;
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
    
});
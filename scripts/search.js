window.addEventListener("load", function(){
    document.getElementById("searchbar").addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            var result = document.getElementById("searchbar").value.trim();
            if (result === "") {
                // Redirect to search.php when the search term is empty
                window.location.href = 'search.php';
            } else {
                // Redirect to search.php with the search term
                window.location.href = 'search.php?search=' + result;
            }
        }
    });

    // Function to handle category selection from dropdown
    document.getElementById("categoryDropdown").addEventListener("change", function(event) {
        var categoryId = this.options[this.selectedIndex].getAttribute("data-category"); // Get the data-category attribute of the selected option
        console.log(categoryId);
        if (categoryId !== "") {
            window.location.href = 'search.php?category=' + categoryId; // Redirect to search.php with the category parameter
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
            
            // Add or update the price parameter
            params.set("price", price);
            
            // Get the current search and category parameters
            var search = params.get("search");
            var category = params.get("category");
    
            // Remove the existing search and category parameters from the URL
            params.delete("search");
            params.delete("category");
    
            // Re-add the search and category parameters if they exist
            if (search) {
                params.set("search", search);
            }
            if (category) {
                params.set("category", category);
            }
    
            // Update the URL with the modified parameters
            url.search = params.toString();
            window.location.href = url.href;
        });
    });
});
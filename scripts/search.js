window.addEventListener("load", function(){
    document.getElementById("searchbar").addEventListener("keypress", function(event) {
        if(event.key === "Enter") {
            var result = document.getElementById("searchbar").value;  
            window.location.href = 'search.php?search='+result;
        }
    });
});
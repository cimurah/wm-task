function getArticles(){
    var url = document.getElementById('categoryForm').getAttribute("action");
    var categoryInput = document.getElementById("category").value;
    
    if (!categoryInput.trim()) {
        document.getElementById("articlesList").innerHTML = "<ul><li>Please enter a category name</li></ul>";
        return;
    }
    
    fetch(url, {
        headers: { "Content-Type": "application/json; charset=utf-8" },
        method: 'POST',
        body: JSON.stringify({category: categoryInput})
    })
    .then((response) => response.json())
    .then( function(data){
        var articles_html = "<ul>";
        if(data.error){
            articles_html += `<li>${data.error}</li>`;
        }
        else{
            console.log(data);
            var articles = data.articles;
            
            if(Object.keys(articles).length){
                Object.keys(articles).forEach(key => {
                    let value = articles[key];
                    articles_html += `<li>${key} - ${value}</li>`; 
                });

            }
            else{
                articles_html += "<li>No Results</li>"
            }
        }
        articles_html += "</ul>";
        document.getElementById("articlesList").innerHTML = articles_html;
    })
    .catch((error) => { alert(`There was an error ${error}`); console.log(error);})
}

document.getElementById('categoryForm').addEventListener('submit', function(event){
    event.preventDefault();
    getArticles();
});

